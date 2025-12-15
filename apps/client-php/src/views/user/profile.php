<?php
/** @var User $user */
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

<div class="main-wrapper">
    <div class="site-branding">
        <h1>My Profile</h1>
        <p>Manage your account and activities</p>
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

        <div class="main-tabs">
            <button class="main-tab-btn active" onclick="setMainTab('selling')">Selling Center</button>
            <button class="main-tab-btn" onclick="setMainTab('buying')">Buying Activity</button>
            <button class="main-tab-btn" onclick="setMainTab('reputation')">Reputation</button>
        </div>

        <div class="sub-tabs" id="sub-tabs-container">
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
<script src="/assets/js/marketplace.js"> </script>
</body>
</html>