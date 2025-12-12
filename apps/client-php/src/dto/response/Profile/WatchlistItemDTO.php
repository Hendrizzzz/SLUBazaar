<?php
declare(strict_types=1);


/**
 * The class that will be passed to Watchlist View (Profile)
 */

class WatchlistItemDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $imageUrl,
        public readonly string $status, // either 'Pending', 'Active'
        public readonly int $bidCount,
        
        // Computed fields
        public readonly float $displayPrice,       // The actual number to show
        public readonly string $priceLabel,        // "Starting Bid" or "Current Bid"
        
        public readonly DateTimeImmutable $timerTargetIso,    // countdown timer for auction start or auction end
        public readonly string $timerLabel        // "Starts in:" or "Ends in:"
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

}