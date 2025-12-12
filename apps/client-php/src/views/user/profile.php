<?php
/** @var User $user */
// Ensure we have the user object from the controller
if (!isset($user)) {
    header("Location: index.php?action=login");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($user->getFirstName()); ?> | Profile</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <link rel="stylesheet" href="/assets/css/user/profile.css">
  <link rel="stylesheet" href="/assets/css/global.css">
</head>
<body>

  <div class="bg-layer-stripes"></div>
  <div class="bg-layer-building"></div>

  <nav class="sidebar">
    <div class="nav-icons">
      <a href="index.php?action=profile" class="active" title="Profile"><i class="fa-regular fa-user"></i></a>
      <a href="index.php?action=marketplace" title="Home"><i class="fa-solid fa-house"></i></a>
      <a href="index.php?action=create_listing" title="New Listing"><i class="fa-solid fa-circle-plus"></i></a>
      <a href="index.php?action=chat" title="Messages"><i class="fa-regular fa-paper-plane"></i></a>
      <a href="index.php?action=logout" title="Logout" style="margin-top: 20px; color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div>
    <div class="bottom-user">
        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user->getFirstName()); ?>&background=random" alt="Me">
    </div>
  </nav>

  <!-- TODO -->
  <div class="main-wrapper">
        <div class="site-branding">
            <h1>My Profile</h1>
            <p>Manage your listings and bids</p>
        </div>

        <div class="page-container profile-container">
            <div class="profile-header">
                <div class="user-info">
                    <div class="profile-img-wrapper">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user->getFirstName(). ' ' .$user->getLastName()); ?>&size=128&background=333&color=fff" alt="Profile Pic">
                    </div>
                    <div class="user-text">
                        <h2><?php echo htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()); ?></h2>
                        <div class="rating">
                            <i class="fa-solid fa-star"></i> <?php echo number_format($user->getAverageRating() ?? 0, 1); ?> / 5.0
                        </div>
                        <span class="user-role"><?php echo htmlspecialchars($user->getRole()->value); ?></span>
                    </div>
                </div>
                <button class="btn-edit-profile" onclick="alert('Edit Profile Modal Coming Soon')">Edit Profile</button>
            </div>

            <div class="profile-tabs">
                <button class="tab-btn active" onclick="switchTab('selling', 'active', this)">Active Listings</button>
                <button class="tab-btn" onclick="switchTab('selling', 'sold', this)">Sold Items</button>
                <button class="tab-btn" onclick="switchTab('buying', 'history', this)">Bid History</button>
                <button class="tab-btn" onclick="switchTab('buying', 'watchlist', this)">Watchlist</button>
            </div>

            <div id="content-grid" class="item-grid active">
                <div class="empty-state">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <p>Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/utils.js"></script>
    <script>
        // --- Page Logic ---

        // Load default tab on startup
        document.addEventListener('DOMContentLoaded', () => {
            switchTab('selling', 'active', document.querySelector('.tab-btn.active'));
        });

        async function switchTab(mainTab, filter, btnElement) {
            // 1. Update UI Tabs
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            if(btnElement) btnElement.classList.add('active');

            // 2. Show Loading
            const grid = document.getElementById('content-grid');
            grid.innerHTML = '<div class="empty-state"><i class="fa-solid fa-spinner fa-spin"></i><p>Loading...</p></div>';

            // 3. Fetch Data from User Controller
            // Route: index.php?action=get_profile_tab&tab=selling&filter=active
            const data = await apiFetch(`index.php?action=get_profile_tab&tab=${mainTab}&filter=${filter}`);

            // 4. Render
            grid.innerHTML = ''; // Clear loading

            if (!data || data.length === 0) {
                grid.innerHTML = '<div class="empty-state"><i class="fa-solid fa-folder-open"></i><p>No items found.</p></div>';
                return;
            }

            data.forEach(item => {
                grid.innerHTML += createItemCard(item, filter);
            });
        }

        // Helper to generate HTML based on the type of item (Active vs Sold vs History)
        function createItemCard(item, type) {
            // Normalize fields because DTOs have slightly different names
            // (e.g. 'imageUrl' vs 'image_url' or 'currentBid' vs 'displayPrice')
            
            // Fallbacks
            const title = item.title || 'Untitled';
            const img = item.image || item.imageUrl || 'https://via.placeholder.com/300x200?text=No+Image';
            
            let price = 0;
            let priceLabel = 'Price';
            let badgeHtml = '';
            let btnHtml = '';

            // --- Logic per Type ---
            if (type === 'active') {
                price = item.price ? item.price.amount : item.startingBid;
                priceLabel = item.price ? item.price.label : 'Starting Bid';
                badgeHtml = `<span class="badge-status badge-success">Active</span>`;
                btnHtml = `<a href="index.php?action=item_details&id=${item.itemId}" class="btn-sold-action" style="background:#2563eb; color:white;">View</a>`;
            } 
            else if (type === 'sold') {
                price = item.currentBid;
                priceLabel = 'Sold For';
                badgeHtml = `<span class="badge-status badge-blue">Sold</span>`;
                btnHtml = `<button class="btn-sold-action" disabled>Closed</button>`;
            }
            else if (type === 'history') {
                price = item.price; // HistoryItemCardDTO uses 'price'
                priceLabel = 'Final Price';
                
                // Winning/Losing Badge
                if(item.label === 'Won') 
                    badgeHtml = `<span class="badge-status badge-yellow">Won</span>`;
                else 
                    badgeHtml = `<span class="badge-status badge-gray">${item.label}</span>`;
                
                btnHtml = `<a href="index.php?action=item_details&id=${item.itemId}" class="btn-sold-action" style="border:1px solid #ccc;">View</a>`;
            }
            else if (type === 'watchlist') {
                 // Watchlist DTO usually has 'price' array
                 price = item.price.amount;
                 priceLabel = item.price.label;
                 badgeHtml = `<span class="badge-status badge-gray">Watching</span>`;
                 btnHtml = `<a href="index.php?action=item_details&id=${item.itemId}" class="btn-sold-action">View Auction</a>`;
            }

            // HTML Template
            return `
                <div class="item-card">
                    <div class="item-img">
                        <img src="${img}" alt="${title}">
                        ${badgeHtml}
                    </div>
                    <div class="item-info">
                        <h4>${title}</h4>
                        <div class="detail-row">
                            <span class="label">${priceLabel}</span>
                            <span class="value price">₱ ${parseFloat(price).toLocaleString()}</span>
                        </div>
                        <div class="action-form">
                            ${btnHtml}
                        </div>
                    </div>
                </div>
            `;
        }
    </script>
</body>
</html>