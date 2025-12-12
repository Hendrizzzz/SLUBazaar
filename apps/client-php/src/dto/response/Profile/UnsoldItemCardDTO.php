<?php

declare(strict_types=1);

class UnsoldItemCardDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $itemId,
        public readonly string $title, 
        public readonly string $imageUrl,
        public readonly float $startingBid,
        public readonly float $currentBid, 
        public readonly int $bidCount,
        public readonly DateTimeImmutable $auctionEnd,
        public readonly string $itemStatus,
        public readonly ?string $removalReason // this is null if the status is Expired or Cancelled by Seller
    )
    {}



    public function jsonSerialize(): array
    {
        return [
            'itemId'        => $this->itemId,
            'title'         => $this->title,
            'imageUrl'      => $this->imageUrl,
            'startingBid'   => $this->startingBid,
            'currentBid'    => $this->currentBid,
            'bidCount'      => $this->bidCount,
            'auctionEnd'    => $this->auctionEnd->format('c'),
            'itemStatus'    => $this->itemStatus,
            'removalReason' => $this->removalReason
        ];
    }

    


    public static function fromArray(array $data) : self
    {
        return new self (
            (int)$data['item_id'],
            $data['title'],
            $data['image_url'],
            (float)$data['starting_bid'],
            (float)$data['current_bid'],
            (int)$data['bid_count'],
            new DateTimeImmutable($data['auction_end']),
            $data['item_status'],
            $data['removal_reason'] ?? null
        );
    }

}