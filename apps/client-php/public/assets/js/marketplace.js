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
                if(btn) btn.remove();
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
                    <button class="btn-action btn-bid">View / Bid</button>
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

        const targetDate = new Date(targetIso);
        const now = new Date();
        let diff = targetDate.getTime() - now.getTime();

        const labelElement = timerElement.previousElementSibling;
        const isEndsIn = labelElement && labelElement.innerText.includes('Ends in');

        let display = '';
        if (diff < 0) {
            display = isEndsIn ? 'Auction Ended' : 'Auction Started';
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
        { id: 'active',   label: 'Active' },
        { id: 'sold',     label: 'Sold' },
        { id: 'unsold',   label: 'Unsold / Expired' }
    ],
    buying: [
        { id: 'claim',     label: 'To Claim' },
        { id: 'active',    label: 'Active Bids' },
        { id: 'history',   label: 'Past Bids' },
        { id: 'watchlist', label: 'Watchlist' }
    ],
    reputation: [
        { id: 'received', label: 'Received' },
        { id: 'given',    label: 'Given' }
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
        btn.className = 'sub-tab-pill';
        btn.innerText = sub.label;
        btn.onclick = () => loadContent(sub.id, btn);
        subContainer.appendChild(btn);

        if (index === 0) loadContent(sub.id, btn);
    });
}

async function loadContent(filterId, btnElement) {
    currentSubFilter = filterId;

    document.querySelectorAll('.sub-tab-pill').forEach(btn => btn.classList.remove('active'));
    if(btnElement) btnElement.classList.add('active');
    else {
        const buttons = Array.from(document.querySelectorAll('.sub-tab-pill'));
        const target = buttons.find(b => b.innerText.toLowerCase().includes(filterId.replace('_',' ')));
        if(target) target.classList.add('active');
    }

    const grid = document.getElementById('content-grid');
    if(!grid) return;

    grid.innerHTML = '<div class="empty-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Loading...</p></div>';

    const data = await apiFetch(`index.php?action=get_profile_tab&tab=${currentMainTab}&filter=${filterId}`);

    // SAFETY CHECK: Ensure data is actually an array before looping
    if (!data || !Array.isArray(data)) {
        if(currentMainTab === 'selling' && filterId === 'handover') {
            // Fallback if handover is empty/fails
            const buttons = document.querySelectorAll('.sub-tab-pill');
            if(buttons[1]) {
                loadContent('active', buttons[1]);
                return;
            }
        }
        grid.innerHTML = '<div class="empty-state"><p>No items found or server error.</p></div>';
        return;
    }

    // Smart Redirect for Handover if empty
    if (currentMainTab === 'selling' && filterId === 'handover' && data.length === 0) {
        const buttons = document.querySelectorAll('.sub-tab-pill');
        if(buttons[1]) {
            loadContent('active', buttons[1]);
            return;
        }
    }

    grid.innerHTML = '';

    if (data.length === 0) {
        grid.innerHTML = '<div class="empty-state"><i class="fa-solid fa-folder-open" style="font-size:2rem; margin-bottom:10px;"></i><p>No items found in this section.</p></div>';
        return;
    }

    if (currentMainTab === 'reputation') {
        grid.style.display = 'block';
        data.forEach(item => grid.innerHTML += createReputationCard(item));
    } else {
        grid.style.display = 'grid';
        data.forEach(item => grid.innerHTML += createItemCard(item, filterId));
    }
}

function createReputationCard(item) {
    let starsHtml = '';
    const ratingValue = item.rating || 0;

    for(let i=0; i<5; i++) {
        if(i < ratingValue) starsHtml += '<i class="fa-solid fa-star"></i>';
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



function openVerifyModal(itemId) { alert("TODO"); }
// TODO: RATINGS
function openRateModal(itemId, userId) { alert("Rating feature check console for details."); }
async function placeBid(itemId) {}
async function toggleWatchlist(itemId, btn) {}
async function cancelAuction(itemId) {}