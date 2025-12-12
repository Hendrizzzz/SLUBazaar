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
    ) {}

    public function jsonSerialize(): array {
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

        // 2. Calculate Timer Logic
        $status = $row['item_status'];
        $auctionStart = new DateTimeImmutable($row['auction_start']);
        $auctionEnd = new DateTimeImmutable($row['auction_end']);
        $now = new DateTimeImmutable();

        // Logic: If item is Pending (future), countdown to Start. Otherwise countdown to End.
        if ($status === 'Pending' || $auctionStart > $now) {
            $timerTarget = $auctionStart;
            $timerLabel = "Starts in:";
        } else {
            $timerTarget = $auctionEnd;
            $timerLabel = "Ends in:";
        }

        return new self(
            (int) $row['item_id'],
            $row['title'],
            $row['image_url'] ?? '/assets/img/default-image.png',
            $status,
            $bidCount,
            $displayPrice,
            $priceLabel,
            $timerTarget,
            $timerLabel
        );
    }
}