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
    <link rel="stylesheet" href="/assets/css/user/marketplace.css">
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
            <button class="btn-edit-profile" onclick="alert('Edit Profile Feature Coming Soon')">Edit Profile</button>
        </div>

        <div class="profile-tabs">
            <button class="tab-btn active main-tab-btn" onclick="setMainTab('selling')">Selling Center</button>
            <button class="tab-btn main-tab-btn" onclick="setMainTab('buying')">Buying Activity</button>
            <button class="tab-btn main-tab-btn" onclick="setMainTab('reputation')">Reputation</button>
        </div>

        <div id="sub-tabs-container" class="sub-tabs-wrapper">
        </div>

        <div id="content-grid" class="item-grid active">
            <div class="empty-state">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <p>Loading...</p>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="rate-modal">
    <div class="modal-content" style="height: auto; max-width: 500px; padding: 0; display:flex; flex-direction:column;">
        <button class="close-btn" style="top:10px; right:15px;" onclick="closeRateModal()">&times;</button>
        <div style="padding: 30px; width: 100%; text-align:center;">
            <h2 style="font-family:'Lalezar'; margin-bottom:10px; color:var(--text-dark);">Rate Seller</h2>
            <p style="color:#64748b; margin-bottom:25px;">How was your experience with this transaction?</p>

            <form id="rating-form" onsubmit="event.preventDefault(); submitRating();">
                <input type="hidden" id="rate-item-id">
                <input type="hidden" id="rate-seller-id">
                <input type="hidden" id="rate-stars" value="0">

                <div class="star-rating-input" style="font-size: 2.5rem; color: #cbd5e1; cursor: pointer; margin-bottom: 25px; display:flex; gap:15px; justify-content:center;">
                    <i class="fa-solid fa-star" data-value="1"></i>
                    <i class="fa-solid fa-star" data-value="2"></i>
                    <i class="fa-solid fa-star" data-value="3"></i>
                    <i class="fa-solid fa-star" data-value="4"></i>
                    <i class="fa-solid fa-star" data-value="5"></i>
                </div>

                <div class="form-group" style="margin-bottom:25px;">
                        <textarea id="rate-comment" placeholder="Leave a comment (optional)..."
                                  style="width:100%; padding:15px; border:1px solid #e2e8f0; border-radius:12px; resize:none; height:120px; font-family:inherit; font-size:0.95rem;"></textarea>
                </div>

                <button type="submit" class="btn-sold-action" style="background:var(--primary); color:white; width:100%; padding:12px; font-size:1.1rem;">Submit Rating</button>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="verify-modal">
    <div class="modal-content" style="height: auto; max-width: 400px; padding: 0; display:flex; flex-direction:column;">
        <button class="close-btn" style="top:10px; right:15px;" onclick="closeVerifyModal()">&times;</button>
        <div style="padding: 30px; width: 100%; text-align:center;">
            <div style="background:#eff6ff; width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
                <i class="fa-solid fa-shield-halved" style="font-size:1.8rem; color:var(--primary);"></i>
            </div>
            <h2 style="font-family:'Lalezar'; margin-bottom:10px; color:var(--text-dark);">Verify Transaction</h2>
            <p style="color:#64748b; margin-bottom:25px; font-size:0.95rem;">
                Ask the buyer for their 6-digit code to complete this sale.
            </p>

            <form id="verify-form" onsubmit="event.preventDefault(); submitVerification();">
                <input type="hidden" id="verify-item-id">

                <div class="form-group" style="margin-bottom:20px;">
                    <input type="text" id="verify-code" placeholder="Enter 6-digit Code" maxlength="6"
                           style="width:100%; padding:15px; border:2px solid #e2e8f0; border-radius:12px; font-size:1.5rem; text-align:center; letter-spacing:5px; font-weight:bold; outline:none; transition:border-color 0.2s;"
                           onfocus="this.style.borderColor='var(--primary)'" onblur="this.style.borderColor='#e2e8f0'">
                </div>

                <button type="submit" class="btn-sold-action" style="background:var(--primary); color:white; width:100%; padding:12px; font-size:1.1rem;">Verify & Complete</button>
            </form>
        </div>
    </div>
</div>

<script src="/assets/js/utils.js"></script>
<script src="/assets/js/marketplace.js?v=<?php echo time(); ?>"></script>
</body>
</html>