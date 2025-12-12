<?php

declare(strict_types=1);

class Watchlist 
{
    private ?int $watchlistId;
    private int $userId;
    private int $itemId;
    private DateTimeImmutable $createdAt;

    public function __construct(?int $watchlistId, int $userId, int $itemId,
                                DateTimeImmutable $createdAt)
    {
        $this->watchlistId = $watchlistId;
        $this->userId = $userId;
        $this->itemId = $itemId;
        $this->createdAt = $createdAt;
    }



    public static function fromArray(array $watchlist) : self
    {
        return new self(
            (int)$watchlist['watchlist_id'], 
            (int)$watchlist['user_id'], 
            (int)$watchlist['item_id'], 
            new DateTimeImmutable($watchlist['created_at'])
        );
    }



    /**
     * Get the value of watchlistId
     */
    public function getWatchlistId(): ?int
    {
        return $this->watchlistId;
    }

    /**
     * Set the value of watchlistId
     */
    public function setWatchlistId(int $watchlistId): self
    {
        $this->watchlistId = $watchlistId;

        return $this;
    }

    /**
     * Get the value of userId
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Set the value of userId
     */
    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

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
     * Get the value of createdAt
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}