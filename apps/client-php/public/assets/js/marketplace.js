// SLUBazaar/public/assets/js/marketplace.js

document.addEventListener('DOMContentLoaded', () => {
    // 1. Only run Marketplace logic if the container exists
    if (document.getElementById('auction-container')) {
        loadAuctions();
        setupTabs();
        setInterval(updateTimers, 1000);
        updateTimers();
    }

    // 2. Only run Profile logic if the grid exists
    if (document.getElementById('content-grid')) {
        setMainTab('selling');
    }

    // 3. Global Click Handler for Cards
    document.addEventListener('click', (event) => {
        const card = event.target.closest('.item-card');
        if (card && !event.target.closest('button') && !event.target.closest('a')) {
            const itemId = card.getAttribute('data-item-id');
            if (itemId) openItemDetails(itemId);
        }
    });

    // 4. Timer Logic for ITEM DETAILS page only (prevents crash on other pages)
    const detailTimer = document.querySelector('.timer-text');
    if (detailTimer) {
        setInterval(() => {
            const target = new Date(detailTimer.dataset.target).getTime();
            const now = new Date().getTime();
            const dist = target - now;
            const btn = document.querySelector('.btn-bid');

            if (dist < 0) {
                detailTimer.innerText = "Auction Ended";
                if (btn) btn.remove();
            } else {
                const d = Math.floor(dist / (1000 * 60 * 60 * 24));
                const h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((dist % (1000 * 60)) / 1000);
                detailTimer.innerText = `${d}d ${h}h ${m}m ${s}s`;
            }
        }, 1000);
    }
});

// =============================
//      DATA & API
// =============================

async function fetchAuctions() {
    const url = 'index.php?action=marketplace';
    const data = await apiFetch(url);
    if (data && data.success === false) {
        console.error("Failed to fetch auctions:", data.error);
        return [];
    }
    return data || [];
}
async function loadAuctions() {
    const container = document.getElementById('auction-container');
    if (!container) return;

    container.innerHTML = '<p style="text-align:center;">Loading...</p>';
    const items = await fetchAuctions();
    container.innerHTML = '';

    if (!items || items.length === 0) {
        container.innerHTML = '<div style="text-align:center; padding:50px; color:#94a3b8;"><i class="fa-solid fa-store-slash" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i><p>No active listings found.</p></div>';
        return;
    }

    items.forEach(item => {
        container.innerHTML += createCardHTML(item);
    });
}

function createCardHTML(item) {
    const title = item.title;
    const img = item.image;
    const price = item.price.amount;
    const timerLabel = item.timer.label;

    let borderClass = '';
    let badgeHTML = '';

    if (item.status === 'Pending') {
        badgeHTML = `<span class="status-badge badge-closed">Pending</span>`;
        borderClass = 'opacity-75';
    } else {
        badgeHTML = `<span class="status-badge badge-winning">Active</span>`;
    }

    return `
            <div class="item-card ${borderClass}" data-item-id="${item.itemId}">
                <div class="card-img-wrapper">
                    <img src="${img}" alt="${title}">
                    ${badgeHTML}
                </div>
                <h4>${title}</h4>
                <div class="item-price">₱ ${parseFloat(price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                <div class="item-timer">
                    <span class="timer-label">${timerLabel}</span> 
                    <span class="timer-value" data-target="${item.timer.target}"></span>
                </div>
                <div style="margin-top: auto; width: 100%;">
                    <button class="btn-action btn-bid">View Auction</button>
                </div>
            </div>
    `;
}

function setupTabs() {
    const tabs = document.querySelectorAll('.d-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const tabText = this.innerText.trim();
            if (tabText.includes('Live Auctions')) loadAuctions();
        });
    });
}

window.openItemDetails = function (itemId) {
    window.location.href = `index.php?action=item_details&id=${itemId}`;
}

function updateTimers() {
    document.querySelectorAll('.timer-value').forEach(timerElement => {
        const targetIso = timerElement.getAttribute('data-target');
        if (!targetIso) return;

        const labelElement = timerElement.previousElementSibling;

        // Fix: If already ended, don't update
        if (labelElement && labelElement.innerText === 'Auction Ended') {
            timerElement.innerText = '';
            return;
        }

        const targetDate = new Date(targetIso);
        const now = new Date();
        let diff = targetDate.getTime() - now.getTime();

        const isEndsIn = labelElement && labelElement.innerText.includes('Ends in');

        let display = '';
        if (diff < 0) {
            if (isEndsIn) {
                // Active -> Ended
                display = 'Auction Ended';
                if (labelElement) labelElement.innerText = ''; // Clear "Ends in"
            } else {
                // Pending -> Active (Started)
                // Ideally, we should reload the data here, but for now:
                display = 'Auction Started';
                if (labelElement) labelElement.innerText = 'Now';
            }
        } else {
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            if (days > 0) display = `${days}d ${hours}h ${minutes}m`;
            else display = `${hours.toString().padStart(2, '0')}h ${minutes.toString().padStart(2, '0')}m ${seconds.toString().padStart(2, '0')}s`;
        }
        timerElement.innerText = display;
    });
}

// =============================
//      PROFILE PAGE
// =============================

const TABS = {
    selling: [
        { id: 'handover', label: 'To Handover' },
        { id: 'active', label: 'Active' },
        { id: 'sold', label: 'Sold' },
        { id: 'unsold', label: 'Unsold / Expired' }
    ],
    buying: [
        { id: 'claim', label: 'To Claim' },
        { id: 'active', label: 'Active Bids' },
        { id: 'history', label: 'Past Bids' },
        { id: 'watchlist', label: 'Watchlist' }
    ],
    reputation: [
        { id: 'received', label: 'Received' },
        { id: 'given', label: 'Given' }
    ]
};

let currentMainTab = 'selling';
let currentSubFilter = 'handover';

function setMainTab(tabName) {
    currentMainTab = tabName;

    document.querySelectorAll('.main-tab-btn').forEach(btn => {
        const isActive = btn.innerText.toLowerCase().includes(
            tabName === 'selling' ? 'selling' :
                (tabName === 'buying' ? 'buying' : 'reputation')
        );
        if (isActive) btn.classList.add('active');
        else btn.classList.remove('active');
    });

    const subContainer = document.getElementById('sub-tabs-container');
    if (!subContainer) return;

    subContainer.innerHTML = '';

    TABS[tabName].forEach((sub, index) => {
        const btn = document.createElement('button');
        btn.className = 'sub-tab-pill'; // Ensure CSS exists for this or use 'tab-btn'
        btn.style.marginRight = "10px";
        btn.style.padding = "5px 15px";
        btn.style.borderRadius = "15px";
        btn.style.border = "1px solid #ccc";
        btn.style.background = "white";
        btn.style.cursor = "pointer";

        btn.innerText = sub.label;
        btn.onclick = () => {
            // Visual toggle
            Array.from(subContainer.children).forEach(c => {
                c.style.background = "white";
                c.style.color = "black";
                c.style.borderColor = "#ccc";
            });
            btn.style.background = "#3b82f6";
            btn.style.color = "white";
            btn.style.borderColor = "#3b82f6";
            loadContent(sub.id, btn);
        };
        subContainer.appendChild(btn);

        // Auto-load first tab
        if (index === 0) btn.click();

        btn.setAttribute('data-id', sub.id);
    });
}

window.openRateModal = function (itemId, sellerId) {
    const modal = document.getElementById('rate-modal');
    if (!modal) {
        console.error("Rate modal not found. Ensure HTML is present in profile.php");
        return;
    }

    // Reset Form Data
    document.getElementById('rate-item-id').value = itemId;
    document.getElementById('rate-seller-id').value = sellerId;
    document.getElementById('rate-stars').value = 0;
    document.getElementById('rate-comment').value = '';

    // Reset Visual Stars
    updateStars(0);

    // Show Modal
    modal.classList.add('active');

    // Initialize Star Click Logic (One-time setup)
    if (!window.ratingLogicInitialized) {
        setupRatingLogic();
        window.ratingLogicInitialized = true;
    }
};

// 2. Close Modal
window.closeRateModal = function () {
    document.getElementById('rate-modal').classList.remove('active');
};

// 3. Setup Star Interaction
function setupRatingLogic() {
    const stars = document.querySelectorAll('.star-rating-input i');
    const hiddenInput = document.getElementById('rate-stars');

    if (!stars.length) return;

    stars.forEach(star => {
        // Click: Set value
        star.addEventListener('click', function () {
            const value = this.getAttribute('data-value');
            hiddenInput.value = value;
            updateStars(value);
        });

        // Hover: Temporary preview
        star.addEventListener('mouseover', function () {
            updateStars(this.getAttribute('data-value'), true);
        });
    });

    // Mouse leave container: Revert to selected value
    const starContainer = document.querySelector('.star-rating-input');
    if (starContainer) {
        starContainer.addEventListener('mouseleave', function () {
            updateStars(hiddenInput.value);
        });
    }
}

// 4. Update Star Visuals
function updateStars(value, isHover = false) {
    const stars = document.querySelectorAll('.star-rating-input i');
    // Gold for active, Gray for inactive.
    // If hovering, use a slightly lighter gold to indicate "preview"
    const activeColor = isHover ? '#fbbf24' : '#f59e0b';
    const inactiveColor = '#cbd5e1';

    stars.forEach(s => {
        if (parseInt(s.getAttribute('data-value')) <= value) {
            s.style.color = activeColor;
        } else {
            s.style.color = inactiveColor;
        }
    });
}

// 5. Submit Rating (AJAX)
window.submitRating = async function () {
    const itemId = document.getElementById('rate-item-id').value;
    const targetUserId = document.getElementById('rate-seller-id').value;
    const stars = document.getElementById('rate-stars').value;
    const comment = document.getElementById('rate-comment').value;

    if (stars == 0) {
        showToast("Please click a star to give a rating.", "warning");
        return;
    }

    const btn = document.querySelector('#rating-form button');
    const originalText = btn.innerText;
    btn.innerText = "Submitting...";
    btn.disabled = true;

    try {
        const result = await apiFetch('index.php?action=submit_rating', {
            method: 'POST',
            body: JSON.stringify({
                item_id: itemId,
                target_user_id: targetUserId,
                stars: stars,
                comment: comment
            })
        });

        if (result && result.success) {
            showToast("Rating submitted successfully!", "success");
            closeRateModal();
            // Refresh current tab content to remove the "Rate" button
            // We assume the current active pill button is correct
            const activeBtn = document.querySelector('.sub-tab-pill[style*="rgb(59, 130, 246)"]');
            if (activeBtn) activeBtn.click();
        } else {
            showToast(result.error || "Failed to submit rating.", "error");
        }
    } catch (e) {
        console.error(e);
        showToast("An error occurred.", "error");
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
};

async function loadContent(filterId, btnElement) {
    currentSubFilter = filterId;
    const grid = document.getElementById('content-grid');
    if (!grid) return;

    grid.innerHTML = '<div class="empty-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Loading...</p></div>';

    const data = await apiFetch(`index.php?action=get_profile_tab&tab=${currentMainTab}&filter=${filterId}`);

    if (!data || !Array.isArray(data)) {
        grid.innerHTML = '<div class="empty-state"><p>No items found.</p></div>';
        return;
    }

    grid.innerHTML = '';

    if (data.length === 0) {
        grid.innerHTML = '<div class="empty-state"><i class="fa-solid fa-folder-open" style="font-size:2rem; margin-bottom:10px;"></i><p>No items found.</p></div>';
        return;
    }

    if (currentMainTab === 'reputation') {
        grid.style.display = 'block'; // Stack vertically for reviews
        data.forEach(item => grid.innerHTML += createReputationCard(item));
    } else {
        grid.style.display = 'grid';
        data.forEach(item => grid.innerHTML += createItemCard(item, filterId));
    }
}
function createReputationCard(item) {
    let starsHtml = '';
    const ratingValue = item.rating || 0;

    for (let i = 0; i < 5; i++) {
        if (i < ratingValue) starsHtml += '<i class="fa-solid fa-star"></i>';
        else starsHtml += '<i class="fa-regular fa-star"></i>';
    }

    const isReceived = currentSubFilter === 'received';
    const userLabel = isReceived ? `From: ${item.user}` : `To: ${item.user}`;
    const finalPrice = item.price || 0;

    return `
        <div class="rating-card">
            <div class="rating-header">
                <div class="rating-user">${userLabel}</div>
                <div class="rating-date">${item.date}</div>
            </div>
            <div class="rating-stars">${starsHtml}</div>
            <div class="rating-item">
                Item: <strong>${item.title}</strong> (Sold for ₱${parseFloat(finalPrice).toLocaleString()})
            </div>
            <div class="rating-comment">"${item.comment}"</div>
        </div>
    `;
}

function createItemCard(item, type) {
    const img = item.imageUrl || item.image || 'https://via.placeholder.com/300x200?text=No+Image';
    const title = item.title || 'Untitled';
    const itemId = item.itemId;

    let price = 0;
    let priceLabel = 'Price';
    let badgeHtml = '';
    let btnHtml = '';

    if (currentMainTab === 'selling') {
        if (type === 'handover') {
            price = item.currentBid;
            priceLabel = 'Sold Price';
            badgeHtml = `<span class="badge-status badge-blue"></i> Meetup</span>`;
            btnHtml = `<button class="btn-sold-action" style="background:var(--primary); color:white;" onclick="openVerifyModal(${itemId})">Verify Code</button>`;
        }
        else if (type === 'active') {
            price = item.price ? item.price.amount : 0;
            priceLabel = item.price ? item.price.label : 'Price';
            const status = item.status || 'Active';
            const badgeClass = status === 'Pending' ? 'badge-closed' : 'badge-success';
            badgeHtml = `<span class="badge-status ${badgeClass}">${status}</span>`;
            btnHtml = `<a href="index.php?action=item_details&id=${itemId}" class="btn-sold-action">View Listing</a>`;
        }
        else if (type === 'sold') {
            price = item.currentBid;
            priceLabel = 'Sold To ' + (item.buyerFname || 'Buyer');
            badgeHtml = `<span class="badge-status badge-blue">Sold</span>`;
            btnHtml = `<button class="btn-sold-action" disabled>Completed</button>`;
        }
        else if (type === 'unsold') {
            price = item.currentBid > 0 ? item.currentBid : item.startingBid;
            priceLabel = 'Final Amount';
            const status = item.itemStatus || 'Unsold';
            badgeHtml = `<span class="badge-status badge-closed">${status}</span>`;
            btnHtml = `<button class="btn-sold-action" disabled>Closed</button>`;
        }
    }
    else if (currentMainTab === 'buying') {
        if (type === 'claim') {
            price = item.currentBid;
            priceLabel = 'To Pay';
            badgeHtml = `<span class="badge-status badge-winning">Winner!</span>`;
            btnHtml = `<div style="text-align:center; background:#f0f9ff; padding:5px; border-radius:5px; border:1px dashed #0ea5e9; color:#0369a1; font-weight:bold;">Code: ${item.meetupCode}</div>`;
        }
        else if (type === 'active') {
            price = item.myBid;
            priceLabel = 'My Max Bid';
            const isWinning = item.isWinning;
            badgeHtml = isWinning
                ? `<span class="badge-status badge-winning">Winning</span>`
                : `<span class="badge-status badge-closed">Outbid</span>`;
            btnHtml = `<a href="index.php?action=item_details&id=${itemId}" class="btn-sold-action">Bid Again</a>`;
        }
        else if (type === 'history') {
            price = item.price;
            priceLabel = 'Final Price';
            const label = item.label || 'Ended';
            const badgeClass = label === 'Won' ? 'badge-winning' : 'badge-gray';
            badgeHtml = `<span class="badge-status ${badgeClass}">${label}</span>`;

            if (item.canRate) {
                btnHtml = `<button class="btn-sold-action" style="background:#f59e0b; color:white;" onclick="openRateModal(${itemId}, ${item.targetSellerId})">Rate Seller</button>`;
            } else {
                btnHtml = `<a href="index.php?action=item_details&id=${itemId}" class="btn-sold-action" style="border:1px solid #ccc;">View</a>`;
            }
        }
        else if (type === 'watchlist') {
            price = item.price ? item.price.amount : 0;
            priceLabel = 'Current Price';
            badgeHtml = `<span class="badge-status badge-gray">Watching</span>`;
            btnHtml = `<a href="index.php?action=item_details&id=${itemId}" class="btn-sold-action">View Auction</a>`;
        }
    }

    return `
        <div class="item-card" data-item-id="${itemId}">
            <div class="item-img">
                <img src="${img}" alt="${title}">
                ${badgeHtml}
            </div>
            <div class="item-info">
                <h4>${title}</h4>
                <div class="detail-row">
                    <span class="label">${priceLabel}</span>
                    <span class="value price">₱ ${parseFloat(price).toLocaleString(undefined, { minimumFractionDigits: 2 })}</span>
                </div>
                <div class="action-form">
                    ${btnHtml}
                </div>
            </div>
        </div>
    `;
}
// SLUBazaar/public/assets/js/marketplace.js

document.addEventListener('DOMContentLoaded', () => {
    // 1. Only run Marketplace logic if the container exists
    if (document.getElementById('auction-container')) {
        if (typeof setupFilters === 'function') setupFilters();
        loadAuctions();
        setupTabs();
        setInterval(updateTimers, 1000);
        updateTimers();
    }

    // 2. Only run Profile logic if the grid exists
    if (document.getElementById('content-grid')) {
        setMainTab('selling');
    }

    // 3. Global Click Handler for Cards
    document.addEventListener('click', (event) => {
        const card = event.target.closest('.item-card');
        // Prevent opening details if clicking a button (like "Rate Seller")
        if (card && !event.target.closest('button') && !event.target.closest('a')) {
            const itemId = card.getAttribute('data-item-id');
            if (itemId) openItemDetails(itemId);
        }
    });

    // 4. Check for Success Messages (e.g. after creating listing)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('success') === 'listing_created') {
        showToast("Listing Created Successfully!", "success");
        // Clean URL
        const newUrl = window.location.pathname + '?action=marketplace';
        window.history.replaceState({}, document.title, newUrl);
    }
});

// =============================
//      FILTER & SEARCH LOGIC
// =============================

function setupFilters() {
    // Elements
    const sidebarSearch = document.getElementById('search-input');
    const topSearchForm = document.querySelector('.search-bar');
    const sortSelect = document.getElementById('sort-select');
    const categoryCheckboxes = document.querySelectorAll('input[name="category"]');
    const applyBtn = document.getElementById('apply-filters');

    // 1. Apply Button (Triggers Search, Price, and Category filters)
    if (applyBtn) {
        applyBtn.addEventListener('click', (e) => {
            e.preventDefault();
            applyAllFilters();
        });
    }

    // 2. Sort Dropdown (Triggers immediately)
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            applyAllFilters();
        });
    }

    // 3. Top Search Bar (Syncs with sidebar and triggers)
    if (topSearchForm) {
        topSearchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const val = topSearchForm.querySelector('input').value;
            if (sidebarSearch) sidebarSearch.value = val; // Sync
            applyAllFilters();
        });
    }

    // 4. Enter key on sidebar search
    if (sidebarSearch) {
        sidebarSearch.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') applyAllFilters();
        });
    }
}

function getFilterValues() {
    // Search
    const searchVal = document.getElementById('search-input')?.value || document.querySelector('.search-bar input')?.value || '';

    // Sort
    const sortVal = document.getElementById('sort-select')?.value || 'newest';

    // Categories
    const categoryCheckboxes = document.querySelectorAll('input[name="category"]:checked');
    const categories = Array.from(categoryCheckboxes).map(cb => cb.value);

    // Price
    const minPrice = document.getElementById('min-price')?.value || '';
    const maxPrice = document.getElementById('max-price')?.value || '';

    return {
        q: searchVal,
        sort: sortVal,
        category: categories.filter(c => c !== 'all'), // Array (Excludes 'all')
        min: minPrice,
        max: maxPrice
    };
}

function applyAllFilters() {
    const filters = getFilterValues();
    loadAuctions(filters);
}

window.openVerifyModal = function (itemId) {
    const modal = document.getElementById('verify-modal');
    if (!modal) {
        console.error("Verify modal not found. Ensure HTML is present in profile.php");
        return;
    }
    document.getElementById('verify-item-id').value = itemId;
    document.getElementById('verify-code').value = '';
    modal.classList.add('active');
};

window.closeVerifyModal = function () {
    document.getElementById('verify-modal').classList.remove('active');
};

window.submitVerification = async function () {
    const itemId = document.getElementById('verify-item-id').value;
    const code = document.getElementById('verify-code').value;

    if (code.length !== 6) {
        showToast("Please enter the complete 6-digit code.", "warning");
        return;
    }

    const btn = document.querySelector('#verify-form button');
    const originalText = btn.innerText;
    btn.innerText = "Verifying...";
    btn.disabled = true;

    try {
        const result = await apiFetch('index.php?action=verify_transaction', {
            method: 'POST',
            body: JSON.stringify({ item_id: itemId, code: code })
        });

        if (result && result.success) {
            showToast(result.message || "Transaction Verified!", "success");
            closeVerifyModal();

            const soldBtn = document.querySelector('.sub-tab-pill[data-id="sold"]');
            if (soldBtn) {
                soldBtn.click();
            } else {
                const activeBtn = document.querySelector('.sub-tab-pill[style*="rgb(59, 130, 246)"]');
                if (activeBtn) activeBtn.click();
            }

        } else {
            showToast(result.error || "Verification failed.", "error");
        }
    } catch (e) {
        console.error(e);
        showToast("An error occurred.", "error");
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
};


// =============================
//      DATA LOADING
// =============================

async function loadAuctions(filters = {}) {
    const container = document.getElementById('auction-container');
    if (!container) return;

    container.innerHTML = '<div style="width:100%; text-align:center; padding: 40px;"><i class="fa-solid fa-spinner fa-spin" style="font-size:2rem; color:var(--primary);"></i><p style="margin-top:10px; color:#64748b;">Loading items...</p></div>';

    // Build URL Params
    const params = new URLSearchParams();
    params.append('action', 'marketplace');

    // Append simple values
    if (filters.q) params.append('q', filters.q);
    if (filters.sort) params.append('sort', filters.sort);
    if (filters.min) params.append('min', filters.min);
    if (filters.max) params.append('max', filters.max);

    // Append Arrays (Categories)
    if (filters.category && Array.isArray(filters.category)) {
        filters.category.forEach(cat => params.append('category[]', cat));
    }

    try {
        const url = `index.php?${params.toString()}`;
        const data = await apiFetch(url);

        container.innerHTML = '';

        if (!data || data.length === 0) {
            container.innerHTML = `
                <div style="grid-column: 1/-1; text-align:center; padding:50px; color:#94a3b8; background: #f8fafc; border-radius: 12px; border: 2px dashed #e2e8f0;">
                    <i class="fa-solid fa-store-slash" style="font-size: 2.5rem; margin-bottom: 15px; display: block;"></i>
                    <p>No active listings found matching your filters.</p>
                    <button onclick="clearFilters()" style="margin-top:15px; background:none; border:none; color:var(--primary); text-decoration:underline; cursor:pointer;">Clear Filters</button>
                </div>`;
            return;
        }

        data.forEach(item => {
            container.innerHTML += createCardHTML(item);
        });
    } catch (error) {
        console.error("Load Error:", error);
        container.innerHTML = '<p style="color:red; text-align:center;">Failed to load items.</p>';
    }
}

function clearFilters() {
    // Reset Inputs
    document.querySelectorAll('input').forEach(i => {
        if (i.type === 'checkbox') i.checked = false;
        else i.value = '';
    });
    document.getElementById('sort-select').value = 'newest';

    // Reload
    loadAuctions();
}

function createCardHTML(item) {
    const title = item.title;
    const img = item.image || '/assets/img/default-image.png';
    const price = item.price.amount;
    const timerLabel = item.timer.label;

    let borderClass = '';
    let badgeHTML = '';

    if (item.status === 'Pending') {
        badgeHTML = `<span class="status-badge badge-closed" style="background:#eab308;">Pending</span>`;
        borderClass = 'opacity-75';
    } else if (item.status === 'Active') {
        badgeHTML = `<span class="status-badge badge-winning">Active</span>`;
    } else {
        // Ended, Sold, etc.
        badgeHTML = `<span class="status-badge badge-closed" style="background:#ef4444; color:white;">${item.status}</span>`;
        if (item.status === 'Ended') borderClass = 'opacity-50';
    }

    return `
            <div class="item-card ${borderClass}" data-item-id="${item.itemId}">
                <div class="card-img-wrapper">
                    <img src="${img}" alt="${title}">
                    ${badgeHTML}
                </div>
                <h4>${title}</h4>
                <div class="item-price">₱ ${parseFloat(price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                <div class="item-timer">
                    <span class="timer-label">${timerLabel}</span> 
                    <span class="timer-value" data-target="${item.timer.target}"></span>
                </div>
                <div style="margin-top: auto; width: 100%;">
                    <button class="btn-action btn-bid">View / Bid</button>
                </div>
            </div>
    `;
}

// =============================
//      UTILS & HELPERS
// =============================

function setupTabs() {
    const tabs = document.querySelectorAll('.d-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function () {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const tabText = this.innerText.trim();
            if (tabText.includes('Live Auctions')) {
                clearFilters(); // Reset filters when going back to main tab
            }
        });
    });
}

window.openItemDetails = function (itemId) {
    window.location.href = `index.php?action=item_details&id=${itemId}`;
}

function updateTimers() {
    document.querySelectorAll('.timer-value').forEach(timerElement => {
        const targetIso = timerElement.getAttribute('data-target');
        if (!targetIso) return;

        const targetDate = new Date(targetIso);
        const now = new Date();
        let diff = targetDate.getTime() - now.getTime();

        const labelElement = timerElement.previousElementSibling;
        const isEndsIn = labelElement && labelElement.innerText.includes('Ends in');

        let display = '';
        if (diff < 0) {
            display = isEndsIn ? 'Ended' : 'Started';
        } else {
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

            if (days > 0) display = `${days}d ${hours}h left`;
            else display = `${hours}h ${minutes}m left`;
        }
        timerElement.innerText = display;
    });
}

// =============================
//      ITEM DETAILS INTERACTION
// =============================

window.changeImage = function (src) {
    const mainImg = document.getElementById('main-preview');
    if (mainImg) {
        mainImg.style.opacity = '0.7';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 150);
    }
};

window.toggleWatchlist = async function (itemId, btn) {
    if (btn.classList.contains('processing')) return;
    btn.classList.add('processing');
    const icon = btn.querySelector('i');

    try {
        const result = await apiFetch('index.php?action=toggle_watchlist', {
            method: 'POST',
            body: JSON.stringify({ item_id: itemId })
        });

        if (result && result.success) {
            if (result.is_watching) {
                btn.classList.add('active');
                icon.classList.remove('fa-regular');
                icon.classList.add('fa-solid');
                showToast("Added to your watchlist!", "success");
            } else {
                btn.classList.remove('active');
                icon.classList.remove('fa-solid');
                icon.classList.add('fa-regular');
                showToast("Removed from watchlist.", "info");
            }
        } else {
            showToast(result.error || "Action failed.", "error");
        }
    } catch (e) {
        console.error(e);
        showToast("An error occurred.", "error");
    } finally {
        btn.classList.remove('processing');
    }
};

window.placeBid = async function (itemId) {
    const input = document.getElementById('bid-amount');
    const amount = input ? input.value : 0;

    if (!amount || amount <= 0) {
        showToast("Please enter a valid amount.", "error");
        return;
    }

    const confirmResult = await Swal.fire({
        title: 'Place Bid?',
        text: `You are about to place a bid of ₱${parseFloat(amount).toLocaleString()}`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Place Bid'
    });

    if (!confirmResult.isConfirmed) return;

    try {
        const result = await apiFetch('index.php?action=place_bid', {
            method: 'POST',
            body: JSON.stringify({ item_id: itemId, amount: amount })
        });

        if (result && result.success) {
            showToast("Bid placed successfully!", "success");
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast(result.error || "Bid failed.", "error");
        }
    } catch (e) {
        console.error(e);
        showToast("An error occurred.", "error");
    }
};

window.cancelAuction = async function (itemId) {
    const confirmResult = await Swal.fire({
        title: 'Cancel Auction?',
        text: "This action cannot be undone. Are you sure you want to cancel this listing?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Cancel It',
        cancelButtonText: 'Keep Listing'
    });

    if (!confirmResult.isConfirmed) return;

    try {
        const result = await apiFetch('index.php?action=cancel_auction', {
            method: 'POST',
            body: JSON.stringify({ item_id: itemId })
        });

        if (result && result.success) {
            showToast("Auction cancelled.", "success");
            setTimeout(() => window.location.href = 'index.php?action=marketplace', 1500);
        } else {
            showToast(result.error || "Failed to cancel.", "error");
        }
    } catch (e) {
        console.error(e);
        showToast("Error processing request.", "error");
    }
};