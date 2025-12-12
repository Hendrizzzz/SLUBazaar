<?php

/**
 * 1. itemId
 * 2. Title
 * 3. ImageURL
 * 4. CurrentBid
 * 5. SellerId
 * 6. meetupcode
 * 7. 
 */


/**
 * Used in Profile View (To Claim)
 */
class ClaimItemCardDTO implements JsonSerializable
{

    public function __construct(
        public readonly int $itemId,
        public readonly string $title,
        public readonly string $imageUrl,
        public readonly float $currentBid,
        public readonly int $sellerId,
        public readonly int $meetupCode
    ) {}


    public static function fromArray(array $data) : self
    {
        return new self(
            (int)$data['item_id'],
            $data['title'],
            $data['image_url'],
            (float)$data['current_bid'],
            (int)$data['selled_id'],
            (int)$data['meetup_code']
        );
    }


    public function jsonSerialize(): array
    {
        return [
            'itemId' => $this->itemId,
            'title' => $this->title,
            'imageUrl' => $this->imageUrl,
            'currentBid' => $this->currentBid,
            'sellerId' => $this->sellerId,
            'meetupCode' => $this->meetupCode
        ];
    }
}