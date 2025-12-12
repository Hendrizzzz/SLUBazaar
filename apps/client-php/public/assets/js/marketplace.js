// SLUBazaar/public/assets/js/marketplace.js

document.addEventListener('DOMContentLoaded', () => {
    loadAuctions();
    setupTabs();
    // Start the timer update loop for real-time countdowns
    setInterval(updateTimers, 1000);
    updateTimers(); // Run once immediately

    document.addEventListener('click', () => {
        const card = event.target.closest('.item-card');

        if (card) {
            const itemId = card.getAttribute('data-item-id');
            if (itemId) {
                console.log("Card clicked! ID:", itemId); // Debugging line
                openItemDetails(itemId);
            }
        }
    })
});

// --- 2. DATA FETCHING ---
/**
 * Fetches active auctions using the AuctionController AJAX endpoint.
 */
async function fetchAuctions() {
    // Calls index.php?action=marketplace. Controller defaults to status='Active' and sort='newest'.
    const url = 'index.php?action=marketplace';

    // apiFetch is defined in utils.js
    const data = await apiFetch(url);

    if (data && data.success === false) {
        console.error("Failed to fetch auctions:", data.error);
        return [];
    }
    return data || []; // Expects an array of ItemCardDTOs
}

// --- 3. RENDERING LOGIC ---
async function loadAuctions() {
    const container = document.getElementById('auction-container');
    container.innerHTML = '<p style="text-align:center;">Loading...</p>';

    const items = await fetchAuctions();
    container.innerHTML = ''; // Clear loading

    if (!items || items.length === 0) {
        container.innerHTML = '<div style="text-align:center; padding:50px; color:#94a3b8;"><i class="fa-solid fa-store-slash" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i><p>No active listings found.</p></div>';
        return;
    }

    items.forEach(item => {
        const cardHTML = createCardHTML(item);
        container.innerHTML += cardHTML;
    });
}

/**
 * Generates the HTML for a single item card based on ItemCardDTO structure.
 */
function createCardHTML(item) {
    // Destructuring DTO data from ItemCardDTO
    const title = item.title;
    const img = item.image;
    const price = item.price.amount;
    const priceLabel = item.price.label;
    const timerLabel = item.timer.label;

    let borderClass = '';
    let badgeHTML = '';

    // Status Logic
    let statusText = item.status;
    if (statusText === 'Pending') {
        badgeHTML = `<span class="status-badge badge-closed">Pending</span>`;
        borderClass = 'opacity-75';
    } else {
        badgeHTML = `<span class="status-badge badge-winning">Active</span>`;
    }

    // Button action redirects to the item details page
    const btnHTML = `<button class="btn-action btn-bid" onclick="openItemDetails(${item.itemId})">View / Bid</button>`;

    return `
        <div class="item-card ${borderClass}" data-item-id="${item.itemId}">
            <div class="card-img-wrapper">
                <img src="${img}" alt="${title}">
                ${badgeHTML}
            </div>
            <h4>${title}</h4>
            <div class="item-price">₱ ${parseFloat(price).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>
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

// --- 4. UI INTERACTION (Tabs & Modal/Redirect) ---
function setupTabs() {
    const tabs = document.querySelectorAll('.d-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Handle Tab Redirections to profile page as implemented in other views
            const tabText = this.innerText.trim();
            if(tabText.includes('Live Auctions')) {
                 loadAuctions(); // Reload active items
            }
            else if(tabText.includes('My Bids')) {
                window.location.href = 'index.php?action=marketplace'; // TODO
            }
            else if(tabText.includes('My Watchlist')) {
                window.location.href = 'index.php?action=marketplace'; // TODO
            }
        });
    });
}

// Function to redirect to the dedicated item details page
window.openItemDetails = function(itemId) {
    // This is the intended behavior since ItemDetailsDTO exists
    window.location.href = `index.php?action=item_details&id=${itemId}`;
}


// --- 5. Countdown Timer Logic ---
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
            if (isEndsIn) {
                 display = 'Auction Ended';
            } else {
                 display = 'Auction Started';
            }
        } else {
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            if (days > 0) {
                display = `${days}d ${hours}h ${minutes}m`;
            } else {
                display = `${hours.toString().padStart(2, '0')}h ${minutes.toString().padStart(2, '0')}m ${seconds.toString().padStart(2, '0')}s`;
            }
        }

        timerElement.innerText = display;
    });
}
// =============================
//         ITEM DETAILS
// =============================
// 1. Image Gallery Logic
function changeImage(src) {
    document.getElementById('main-preview').src = src;
}

// 2. Bid Logic
async function placeBid(itemId) {
    const amount = document.getElementById('bid-amount').value;
    const btn = document.querySelector('.btn-bid');

    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;

    const res = await apiFetch('index.php?action=place_bid', {
        method: 'POST',
        body: JSON.stringify({ item_id: itemId, amount: amount })
    });

    if (res && res.success) {
        alert("Bid placed successfully!");
        location.reload(); // Refresh to update history/price
    } else {
        alert(res.error || "Failed to place bid.");
        btn.innerHTML = 'Place Bid';
        btn.disabled = false;
    }
}

// 3. Watchlist Logic
async function toggleWatchlist(itemId, btn) {
    const icon = btn.querySelector('i');

    const res = await apiFetch('index.php?action=toggle_watchlist', {
        method: 'POST',
        body: JSON.stringify({ item_id: itemId })
    });

    if (res && res.success) {
        if (res.is_watching) {
            btn.classList.add('active');
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
        } else {
            btn.classList.remove('active');
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
        }
    }
}

// 4. Timer Logic (Simple version for this page)
setInterval(() => {
    const timer = document.querySelector('.timer-text');
    const target = new Date(timer.dataset.target).getTime();
    const now = new Date().getTime();
    const dist = target - now;

    if (dist < 0) {
        timer.innerText = "Auction Ended";
        document.querySelector('.btn-bid')?.remove();
    } else {
        const d = Math.floor(dist / (1000 * 60 * 60 * 24));
        const h = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
        const s = Math.floor((dist % (1000 * 60)) / 1000);
        timer.innerText = `${d}d ${h}h ${m}m ${s}s`;
    }
}, 1000);


// Keeping the original modal functions as stubs because the HTML file contains the modal structure.
const modal = document.getElementById('item-modal');
window.openModal = function() { modal.classList.add('active'); } // Not used by the cards anymore
window.closeModal = function() { modal.classList.remove('active'); }
window.onclick = function(event) { if (event.target == modal) { closeModal(); } }