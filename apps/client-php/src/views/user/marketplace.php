<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Market</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/marketplace-modern.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="/assets/css/global.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <a href="index.php?action=logout" title="Logout" style="margin-top: 20px; color: #ef4444;"><i
                    class="fa-solid fa-right-from-bracket"></i></a>
        </div>
        <div class="bottom-user">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['fname'] ?? 'User'); ?>&background=random"
                alt="Me">
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

            <div class="marketplace-layout">

                <!-- 1. Header: Search & Filter Trigger -->
                <div class="market-header">
                    <div class="search-bar-wrapper">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <form class="search-bar" onsubmit="event.preventDefault();">
                            <!-- ID matches marketplace.js expectation -->
                            <input type="text" id="search-input" placeholder="Search for items...">
                        </form>
                    </div>
                    <!-- Hidden but kept for structure if needed -->
                    <button class="btn-filter-trigger" id="filter-trigger" style="display:none;">
                        <i class="fa-solid fa-sliders"></i> Filters
                    </button>
                </div>

                <!-- 2. Horizontal Category Scroll -->
                <div class="category-scroll-container">
                    <!-- 'All' effectively clears filters / sends 'all' -->
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="all"
                            onchange="if(this.checked){ document.querySelectorAll('input[name=\'category\']').forEach(el=>{if(el!=this)el.checked=false}); applyAllFilters(); }">
                        <span class="pill-label">All</span>
                    </label>

                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Textbooks"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Textbooks</span>
                    </label>
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Uniforms"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Uniforms</span>
                    </label>
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Electronics"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Electronics</span>
                    </label>
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Stationery"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Stationery</span>
                    </label>
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Clothing"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Clothing</span>
                    </label>
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Sports"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Sports</span>
                    </label>
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Furniture"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Furniture</span>
                    </label>
                    <label class="cat-pill">
                        <input type="checkbox" name="category" value="Other"
                            onchange="document.querySelector('input[value=\'all\']').checked=false; applyAllFilters()">
                        <span class="pill-label">Other</span>
                    </label>
                </div>

                <!-- 3. Results Bar & Sort -->
                <div class="results-header">
                    <div class="results-count">Live & Pending Listings</div>
                    <div class="sort-wrapper">
                        <label>Sort:</label>
                        <!-- ID matches marketplace.js -->
                        <select id="sort-select" class="sort-select">
                            <option value="newest">Newest</option>
                            <option value="ending_soon">Ending Soon</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- 4. Grid -->
                <!-- ID matches marketplace.js -->
                <main class="grid-container" id="auction-container">
                    <!-- Cards injected by JS -->
                </main>

            </div>
        </div>
    </div>

    <script src="/assets/js/utils.js"></script>
    <script src="/assets/js/marketplace.js?v=<?php echo time(); ?>"></script>

</body>

</html>