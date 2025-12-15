<?php
/** @var ItemDetailsDTO $itemDetails */
if (!isset($itemDetails)) {
    header("Location: index.php?action=marketplace");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($itemDetails->title); ?> | SLU Bazaar</title>

    <link href="https://fonts.googleapis.com/css2?family=Khula:wght@300;400;600;700&family=Lalezar&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/user/item_details.css">
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
    <div class="top-nav">
        <a href="index.php?action=marketplace" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Market</a>
    </div>

    <div class="details-grid">

        <div class="image-section">
            <div class="main-image-frame">
                <img id="main-preview" src="<?php echo $itemDetails->images[0] ?? '/assets/img/default.png'; ?>" alt="Item Preview">
                <span class="status-badge <?php echo $itemDetails->status === 'Active' ? 'badge-active' : 'badge-closed'; ?>">
                        <?php echo $itemDetails->status; ?>
                    </span>
            </div>

            <div class="thumbnail-strip">
                <?php foreach($itemDetails->images as $img): ?>
                    <div class="thumb-box" onclick="changeImage('<?php echo $img; ?>')">
                        <img src="<?php echo $img; ?>" alt="thumbnail">
                    </div>
                <?php endforeach; ?>
            </div>


            <div class="header-block">
                <h1><?php echo htmlspecialchars($itemDetails->title); ?></h1>
                <div class="category-pill"><i class="fa-solid fa-tag"></i> <?php echo htmlspecialchars($itemDetails->category); ?></div>
            </div>
        </div>

        <div class="info-section">

            <div class="seller-card">
                <img src="<?php echo $itemDetails->sellerAvatar; ?>" alt="Seller" class="seller-avatar">
                <div class="seller-info">
                    <span class="label">Listed by</span>
                    <h3><?php echo htmlspecialchars($itemDetails->sellerName); ?></h3>
                    <div class="seller-rating">
                        <?php for($i=0; $i<5; $i++): ?>
                            <i class="fa-solid fa-star <?php echo $i < $itemDetails->sellerRating ? 'filled' : ''; ?>"></i>
                        <?php endfor; ?>
                        <span>(<?php echo number_format($itemDetails->sellerRating, 1); ?>)</span>
                    </div>
                </div>
                <?php if(!$itemDetails->isOwner): ?>
                    <button class="btn-chat" onclick="alert('Chat feature coming next!')"><i class="fa-regular fa-comment-dots"></i> Chat</button>
                <?php endif; ?>
            </div>

            <div class="bid-dashboard">
                <div class="price-row">
                    <div>
                        <span class="label">Current Price</span>
                        <div class="big-price">₱ <?php echo number_format($itemDetails->currentPrice, 2); ?></div>
                    </div>
                    <div class="timer-box">
                        <span class="label"><i class="fa-regular fa-clock"></i> Time Left</span>
                        <div class="timer-text" data-target="<?php echo $itemDetails->auctionEnd->format('c'); ?>">
                            <?php echo $itemDetails->timeLeftLabel; ?>
                        </div>
                    </div>
                </div>

                <?php if ($itemDetails->isOwner): ?>
                    <div class="owner-controls">
                        <p class="owner-msg">You are the seller of this item.</p>
                        <button class="btn-primary" style= "background: red; color: white; border: none;
    padding: 0 30px; border-radius: 12px; height: 3rem; font-weight: 700; font-size: 1.1rem; cursor: pointer; " onclick="cancelAuction(<?php echo $itemDetails->itemId; ?>)">Cancel Auction</button>
                    </div>
                <?php elseif ($itemDetails->status !== 'Active'): ?>
                    <div class="closed-controls">
                        <button class="btn-disabled" disabled>Auction Ended</button>
                    </div>
                <?php else: ?>
                    <div class="bid-controls">
                        <div class="input-group">
                            <span class="prefix">₱</span>
                            <input type="number" id="bid-amount"
                                   min="<?php echo $itemDetails->nextMinimumBid; ?>"
                                   value="<?php echo $itemDetails->nextMinimumBid; ?>"
                                   step="10">
                        </div>
                        <button class="btn-bid" onclick="placeBid(<?php echo $itemDetails->itemId; ?>)">
                            Place Bid
                        </button>
                        <button class="btn-watchlist <?php echo $itemDetails->isWatchlisted ? 'active' : ''; ?>"
                                onclick="toggleWatchlist(<?php echo $itemDetails->itemId; ?>, this)">
                            <i class="<?php echo $itemDetails->isWatchlisted ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                        </button>
                        <button class="btn-report <?php echo $itemDetails->isWatchlisted ? 'active' : ''; ?>"
                                onclick="toggleWatchlist(<?php echo $itemDetails->itemId; ?>, this)">
                            <i class="<?php echo $itemDetails->isWatchlisted ? 'fa-solid' : 'fa-regular'; ?> fa-heart"></i>
                        </button>
                    </div>
                    <p class="min-bid-hint">Minimum next bid: ₱ <?php echo number_format($itemDetails->nextMinimumBid, 2); ?></p>
                <?php endif; ?>
            </div>

            <div class="content-box">
                <h3>Description</h3>
                <p class="desc-text"><?php echo nl2br(htmlspecialchars($itemDetails->description)); ?></p>
            </div>

            <div class="content-box">
                <h3>Bid History <span class="count-badge"><?php echo $itemDetails->bidCount; ?></span></h3>
                <div class="history-list">
                    <?php if (empty($itemDetails->bidHistory)): ?>
                        <div class="empty-history"><?php if ($itemDetails->isOwner): ?> No bids yet.
                            <?php else: ?> No bids yet. Be the first <?php endif; ?></div>
                    <?php else: ?>
                        <?php foreach($itemDetails->bidHistory as $bid): ?>
                            <div class="history-row">
                                <div class="history-user">
                                    <div class="avatar-circle"><?php echo substr($bid->bidderName, 0, 1); ?></div>
                                    <span><?php echo htmlspecialchars($bid->bidderName); ?></span>
                                </div>
                                <div class="history-amount">₱ <?php echo number_format($bid->amount, 2); ?></div>
                                <div class="history-time"><?php echo $bid->timeAgo; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="/assets/js/utils.js"></script>
<script src = "/assets/js/marketplace.js"></script>
</body>
</html>