<?php

declare(strict_types=1);

/**
 * Connected to the ActiveBidCardDTO
 */
class BidRowDTO 
{
    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $imageUrl,
        public readonly DateTimeImmutable $auctionEnd, 
        public readonly float $myBid,
        public readonly float $currentBid
    ) {}
    
    
    public static function fromArray(array $data) : self
    {
        return new self(
            (int)$data['item_id'],
            $data['title'],
            $data['image_url'],
            new DateTimeImmutable($data['auction_end']),
            (float)$data['my_bid'],
            (float)$data['current_bid']
        );
    }
}