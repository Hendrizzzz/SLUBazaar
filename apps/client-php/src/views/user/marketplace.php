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
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fname'] ?? 'User'); ?>&background=random" alt="Me">
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

            <div class="marketplace-layout">
                
                <aside class="filters-sidebar">
                    <div class="filter-header">
                        <h3>Filters</h3>
                        <button onclick="clearFilters()" class="clear-btn">Clear</button>
                    </div>

                    <div class="filter-group">
                        <label>Search</label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" id="search-input" placeholder="Keyword...">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Sort By</label>
                        <select id="sort-select">
                            <option value="newest">Newest Listed</option>
                            <option value="ending_soon">Ending Soonest</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Price Range (₱)</label>
                        <div class="price-inputs">
                            <input type="number" id="min-price" placeholder="Min">
                            <span>-</span>
                            <input type="number" id="max-price" placeholder="Max">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Categories</label>
                        <div class="checkbox-list">
                            <label><input type="checkbox" name="category" value="Textbooks"> Textbooks</label>
                            <label><input type="checkbox" name="category" value="Stationery"> Stationery</label>
                            <label><input type="checkbox" name="category" value="Electronics"> Electronics</label>
                            <label><input type="checkbox" name="category" value="Clothing"> Clothing</label>
                            <label><input type="checkbox" name="category" value="Sports Equipment"> Sports Equipment</label>
                            <label><input type="checkbox" name="category" value="Accessories"> Accessories</label>
                            <label><input type="checkbox" name="category" value="Furniture"> Furniture</label>
                            <label><input type="checkbox" name="category" value="Collectibles"> Collectibles</label>
                            <label><input type="checkbox" name="category" value="Other"> Other</label>
                        </div>
                    </div>

                    <button id="apply-filters" class="btn-apply">Apply Filters</button>
                </aside>

                <main class="listings-area">
                    <div class="grid-container" id="auction-container">
                        </div>
                </main>

            </div>
        </div>
    </div>

    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/marketplace.js?v=<?php echo time(); ?>"></script>

</body>
</html>