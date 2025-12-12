<?php
declare(strict_types=1);




class HistoryItemCardDTO implements JsonSerializable
{
    public readonly string $statusLabel; 

    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $imageUrl,
        public readonly float $finalPrice,
        public readonly DateTimeImmutable $dateFinished,
        
        // Logic Fields
        public readonly bool $isWinner,       
        public readonly bool $hasRated,       
        public readonly string $category,     
        public readonly int $sellerId,
        public readonly string $itemStatus
    ) {
        // Logic: Determine the human-readable label
        if ($this->isWinner) {
            $this->statusLabel = "Won";
        } elseif ($this->itemStatus === 'Sold') {
            // It is sold, but I am not the winner -> I Lost
            $this->statusLabel = "Lost";
        } else {
            // Expired, Cancelled, Removed
            $this->statusLabel = $this->itemStatus;
        }
    }

    public function jsonSerialize(): array {
        return [
            'itemId' => $this->itemId,
            'title' => $this->title,
            'imageUrl' => $this->imageUrl,
            'price' => $this->finalPrice,
            'date' => $this->dateFinished->format('M d, Y'), 
            'label' => $this->statusLabel,

            // Frontend Buttons Logic
            'canRate' => ($this->isWinner && !$this->hasRated),
            'canViewSimilar' => !$this->isWinner,
            
            // Context data for the buttons
            'searchCategory' => $this->category,
            'targetSellerId' => $this->sellerId
        ];
    }

    


    public static function fromArray(array $data, int $currentUserId) : self
    {
        // 1. Determine Date: Use date_sold if available, otherwise auction_end
        $rawDate = $data['date_sold'] ?? $data['auction_end'];
        
        // 2. Determine Winner:
        $isWinner = isset($data['buyer_id']) && (int)$data['buyer_id'] === $currentUserId;

        return new self (
            (int)$data['item_id'],
            $data['title'],
            $data['image_url'], // Handle null image
            (float)$data['current_bid'],
            new DateTimeImmutable($rawDate),
            $isWinner,
            (bool)$data['has_rated'], // 1 or 0 from DB
            $data['category'],
            (int)$data['seller_id'],
            $data['item_status']
        );
    }
}