<?php

declare(strict_types=1);

class Bid 
{
    private ?int $bidId;
    private int $itemId;
    private int $bidderId;
    private float $bidAmount;
    private DateTimeImmutable $bidTimestamp;

    public function __construct(?int $bidId, int $itemId, int $bidderId, 
                                float $bidAmount, DateTimeImmutable $bidTimestamp)
    {
        $this->bidId = $bidId;
        $this->itemId = $itemId;
        $this->bidderId = $bidderId;
        $this->bidAmount = $bidAmount;
        $this->bidTimestamp = $bidTimestamp;
    }


    public static function fromArray(array $bid) : self
    {
        return new self(
            (int)$bid['bid_id'], 
            (int)$bid['item_id'], 
            (int)$bid['bidder_id'], 
            (float)$bid['bid_amount'], 
            new DateTimeImmutable ($bid['bid_timestamp'])
        );
    }


    

    /**
     * Get the value of bidId
     */
    public function getBidId(): ?int
    {
        return $this->bidId;
    }

    /**
     * Set the value of bidId
     */
    public function setBidId(?int $bidId): self
    {
        $this->bidId = $bidId;

        return $this;
    }

    /**
     * Get the value of itemId
     */
    public function getItemId(): int
    {
        return $this->itemId;
    }

    /**
     * Set the value of itemId
     */
    public function setItemId(int $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get the value of bidderId
     */
    public function getBidderId(): int
    {
        return $this->bidderId;
    }

    /**
     * Set the value of bidderId
     */
    public function setBidderId(int $bidderId): self
    {
        $this->bidderId = $bidderId;

        return $this;
    }

    /**
     * Get the value of bidAmount
     */
    public function getBidAmount(): float
    {
        return $this->bidAmount;
    }

    /**
     * Set the value of bidAmount
     */
    public function setBidAmount(float $bidAmount): self
    {
        $this->bidAmount = $bidAmount;

        return $this;
    }

    /**
     * Get the value of bidTimeStamp
     */
    public function getBidTimeStamp(): DateTimeImmutable
    {
        return $this->bidTimestamp;
    }

    /**
     * Set the value of bidTimeStamp
     */
    public function setBidTimeStamp(DateTimeImmutable $bidTimeStamp): self
    {
        $this->bidTimestamp = $bidTimeStamp;

        return $this;
    }
}