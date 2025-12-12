<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Market</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/assets/css/user/marketplace.css">
    <link rel="stylesheet" href="/assets/css/global.css">
</head>
<body>

    <div class="bg-layer-stripes"></div>
    <div class="bg-layer-building"></div>

    <nav class="sidebar">
        <div class="nav-icons">
            <a href="index.php?action=profile" title="Profile"><i class="fa-regular fa-user"></i></a>
            <a href="index.php?action=marketplace" class="active" title="Home"><i class="fa-solid fa-house"></i></a>
            <a href="index.php?action=create_listing" title="New Listing"><i class="fa-solid fa-circle-plus"></i></a>
            <a href="index.php?action=chat" title="Messages"><i class="fa-regular fa-paper-plane"></i></a>
            <a href="index.php?action=logout" title="Logout" style="margin-top: 20px; color: #ef4444;"><i class="fa-solid fa-right-from-bracket"></i></a>
        </div>
        <div class="bottom-user">
             <img src="https://ui-avatars.com/api/?name=User&background=random" alt="Me">
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="site-branding">
            <img src="/assets/img/SLU Logo.png" alt="slu_logo">
            <div class="branding-text">
                <h1>SLU Bazaar</h1>
                <p>The official marketplace of<br>Saint Louis University.</p>
            </div>
        </div>

        <div class="page-container">
            <div class="dash-tabs">
                <div class="d-tab active">Live Auctions</div>
                <div class="d-tab">My Bids</div>
                <div class="d-tab">My Watchlist</div>
            </div>

            <div class="dash-content">
                <form class="search-bar" onsubmit="event.preventDefault();">
                    <input type="text" placeholder="Search items...">
                    <button type="submit" id="filter-btn">
                        <i class="fa-solid fa-magnifying-glass"></i> Search
                    </button>
                </form>
                
                <hr style="border: 0; border-top: 1px solid #ccc; margin-bottom: 30px;">
                
                <div class="grid-container" id="auction-container">
                    </div>
            </div>
        </div>
    </div>

    <div class="modal" id="item-modal">
        <div class="modal-content">
            <button id="close-modal" class="close-btn" onclick="closeModal()">&times;</button>
            <div class="modal-left">
                <img id="modal-img" src="https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&q=80&w=600" alt="Main View">
            </div>
            <div class="modal-right">
                <h2 id="modal-title">Item Title</h2>
                <p class="seller-label">Sold by: <strong id="modal-seller">...</strong></p>
                <div class="stats-row">
                    <div class="stat-box">
                        <span class="label">Current Bid</span>
                        <div class="value-pill">₱ <span id="modal-price">0</span></div>
                    </div>
                    <div class="stat-box">
                        <span class="label">Next Minimum</span>
                        <div class="value-pill">₱ <span id="modal-next">0</span></div>
                    </div>
                </div>
                <div class="timer-container">
                    <span class="label">Auction Ends in</span>
                    <div class="timer-pill">02d 14h 32m</div> <!-- timer -->
                </div>
                <form class="bid-form-area" onsubmit="alert('Bid Logic Here'); event.preventDefault();">
                    <input type="number" placeholder="Enter amount" required>
                    <button type="submit" class="btn-place-bid">Submit Bid</button>
                </form>
                <div class="info-section">
                    <h4>Description</h4>
                    <p>This is a simulated description for the preview.</p>
                </div>
                <div class="info-section">
                    <h4>Bid History</h4>
                    <p style="font-size: 0.9rem;">No bids yet.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/marketplace.js?v=<?php echo time(); ?>"></script>

</body>
</html>