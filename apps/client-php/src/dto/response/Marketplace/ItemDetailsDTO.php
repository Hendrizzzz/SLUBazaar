<?php

declare(strict_types=1);


class ItemDetailsDTO implements JsonSerializable
{
    public function __construct(
        // 1. Core Item Details
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $description,
        public readonly string $category,
        public readonly string $condition, // Derived from description or specific column
        public readonly string $status,    // 'Active', 'Sold', etc.

        // 2. Pricing
        public readonly float $currentPrice,
        public readonly float $nextMinimumBid,
        public readonly int $bidCount,

        // 3. Timing
        public readonly DateTimeImmutable $auctionEnd,
        public readonly string $timeLeftLabel, // "2 days, 5 hours left"

        // 4. Visuals
        public readonly array $images, // Array of strings (URLs)

        // 5. Seller Info (For Trust)
        public readonly int $sellerId,
        public readonly string $sellerName,
        public readonly float $sellerRating,
        public readonly string $sellerAvatar,

        // 6. Context (User specific)
        public readonly bool $isWatchlisted,
        public readonly bool $isOwner, // To hide "Bid" button if you are the seller

        // 7. History
        public readonly array $bidHistory // Array of ItemPageBidDTO
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->itemId,
            'info' => [
                'title'       => $this->title,
                'description' => $this->description,
                'category'    => $this->category,
                'status'      => $this->status,
                'images'      => $this->images, // Array of URLs
            ],
            'price' => [
                'current'  => $this->currentPrice,
                'next_min' => $this->nextMinimumBid,
                'count'    => $this->bidCount,
            ],
            'timer' => [
                'end_iso' => $this->auctionEnd->format('c'), // ISO 8601 for JS Countdown
                'label'   => $this->timeLeftLabel,
            ],
            'seller' => [
                'id'     => $this->sellerId,
                'name'   => $this->sellerName,
                'rating' => $this->sellerRating,
                'avatar' => $this->sellerAvatar,
            ],
            'user_context' => [
                'is_watching' => $this->isWatchlisted,
                'is_owner'    => $this->isOwner,
            ],
            'history' => $this->bidHistory // The array of bid objects
        ];
    }

    /**
     * Factory method to build the full view data from disparate sources.
     * 
     * @param Item $item The main item entity
     * @param User $seller The seller entity
     * @param array $imageUrls Array of strings ['img1.jpg', 'img2.jpg']
     * @param array $bids Array of ItemPageBidDTO objects
     * @param bool $isWatchlisted Is current user watching?
     * @param int $currentUserId To check ownership
     */
    public static function create(
        Item $item, 
        User $seller, 
        array $imageUrls, 
        array $bids, 
        bool $isWatchlisted,
        int $currentUserId
    ): self {
        // --- Logic: Price ---
        // If 0 bids, current price is starting bid. Otherwise, it's current bid.
        $currentPrice = ($item->getCurrentBid() > 0) ? $item->getCurrentBid() : $item->getStartingBid();
        
        // --- Logic: Next Bid Increment ---
        // Example: If price < 1000, increment 10. If > 1000, increment 50.
        $increment = ($currentPrice >= 1000) ? 50.00 : 10.00;
        $nextMin = $currentPrice + $increment;

        // --- Logic: Time Label ---
        $now = new DateTimeImmutable();
        $diff = $now->diff($item->getAuctionEnd());
        $timeLabel = $diff->invert ? 'Ended' : $diff->format('%d days, %h hours left');

        // --- Logic: Seller Name ---
        // Format: "Juan D."
        $sellerDisplayName = $seller->getFirstName() . ' ' . substr($seller->getLastName(), 0, 1) . '.';

        // --- Logic: Images ---
        // If no images, provide default
        if (empty($imageUrls)) {
            $imageUrls = ['/assets/images/default-item-large.png'];
        }

        return new self(
            $item->getItemId(),
            $item->getTitle(),
            $item->getDescription(),
            $item->getCategory()->value,
            "Used", // You might want to add 'Condition' to your DB/Item Model later
            $item->getItemStatus()->value,
            $currentPrice,
            $nextMin,
            count($bids),
            $item->getAuctionEnd(),
            $timeLabel,
            $imageUrls,
            $seller->getUserId(),
            $sellerDisplayName,
            $seller->getAverageRating() ?? 0.0,
            '/assets/img/default-profile-pic.jpg', // Or $seller->getProfilePic()
            $isWatchlisted,
            ($item->getSellerId() === $currentUserId),
            $bids
        );
    }
}