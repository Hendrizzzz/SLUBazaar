<?php

declare(strict_types=1);

/**
 * The class that will be passed to Profile View (To Handover Items)
 */
class ToHandoverItemCardDTO implements JsonSerializable
{

    public function __construct(
        public readonly int $itemId, 
        public readonly string $title, 
        public readonly string $imageUrl,
        public readonly float $currentBid, // Final Price
        public readonly string $buyerFname, // TODO: I should make this buyer name limited
        public readonly string $buyerLname    
        )
    {}
    
    public function jsonSerialize(): array
    {
        return [
            'itemId' => $this->itemId,
            'title' => $this->title,
            'imageUrl' => $this->imageUrl, 
            'currentBid' => $this->currentBid,
            'buyerFname' => $this->buyerFname,
            'buyerLname' => $this->buyerLname
        ];
    }


    public static function fromArray(array $data) : self
    {
        return new self (
            (int)$data['item_id'],
            $data['title'],
            $data['image_url'],
            (float)$data['current_bid'],
            $data['fname'],
            $data['lname']
        );
    }
}