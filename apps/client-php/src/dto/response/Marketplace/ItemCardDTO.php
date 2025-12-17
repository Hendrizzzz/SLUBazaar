<?php
declare(strict_types=1);


/**
 * The class that will be passed to Marketplace View (Active Items)
 */
class ItemCardDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $imageUrl,
        public readonly string $status,
        public readonly int $bidCount,
        public readonly float $displayPrice,
        public readonly string $priceLabel,
        public readonly DateTimeImmutable $timerTargetIso,
        public readonly string $timerLabel
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'itemId' => $this->itemId,
            'title' => $this->title,
            'image' => $this->imageUrl,
            'status' => $this->status,
            'bidCount' => $this->bidCount,
            'price' => [
                'amount' => $this->displayPrice,
                'label' => $this->priceLabel
            ],
            'timer' => [
                'target' => $this->timerTargetIso->format('c'),
                'label' => $this->timerLabel
            ]
        ];
    }


    public static function fromArray(array $row): self
    {
        // 1. Calculate Bid Logic
        $bidCount = (int) ($row['bid_count'] ?? 0);

        // If there are bids, show Current Bid. If 0 bids, show Starting Bid.
        if ($bidCount > 0) {
            $displayPrice = (float) $row['current_bid'];
            $priceLabel = "Current Bid";
        } else {
            $displayPrice = (float) $row['starting_bid'];
            $priceLabel = "Starting Bid";
        }

        // 2. Calculate Effective Status (Time-based fix)
        $dbStatus = $row['item_status'];
        $auctionStart = new DateTimeImmutable($row['auction_start']);
        $auctionEnd = new DateTimeImmutable($row['auction_end']);
        $now = new DateTimeImmutable();

        // Default to DB status first
        $effectiveStatus = $dbStatus;

        // If it's supposedly "live" or "pre-live", verify with time
        if ($dbStatus === 'Active' || $dbStatus === 'Pending') {
            if ($now < $auctionStart) {
                $effectiveStatus = 'Pending';
            } elseif ($now > $auctionEnd) {
                $effectiveStatus = 'Ended';
            } else {
                $effectiveStatus = 'Active';
            }
        }

        // 3. Timer Logic based on Effective Status
        if ($effectiveStatus === 'Pending') {
            $timerTarget = $auctionStart;
            $timerLabel = "Starts in:";
        } elseif ($effectiveStatus === 'Active') {
            $timerTarget = $auctionEnd;
            $timerLabel = "Ends in:";
        } else { // Ended, Sold, etc.
            $timerTarget = $auctionEnd; // Doesn't matter much, timer stops
            $timerLabel = "Auction Ended";
        }

        return new self(
            (int) $row['item_id'],
            $row['title'],
            $row['image_url'] ?? '/assets/img/default-image.png',
            $effectiveStatus,
            $bidCount,
            $displayPrice,
            $priceLabel,
            $timerTarget,
            $timerLabel
        );
    }
}