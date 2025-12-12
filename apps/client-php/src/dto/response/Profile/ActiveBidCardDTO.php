<?php

declare(strict_types=1);

class ActiveBidCardDTO implements JsonSerializable
{

    public readonly bool $isWinning;
    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $imageUrl,
        public readonly DateTimeImmutable $auctionEnd, 
        public readonly float $myBid,
        public readonly float $currentBid
    ) {
        $this->isWinning = ($this->myBid >= $this->currentBid);
    }
    
    
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


    public function jsonSerialize(): array
    {
        return [
            'itemId' => $this->itemId,
            'title' => $this->title,
            'imageUrl' => $this->imageUrl,
            'auctionEnd' => $this->auctionEnd->format('c'), // make it countdown timer in the frontend (js)
            'myBid' => $this->myBid,
            'currentBid' => $this->currentBid,
            'isWinning' => $this->isWinning
        ];
    }
}