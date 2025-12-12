<?php

declare(strict_types=1);


class Item 
{

    private ?int $itemId;
    private int $sellerId;
    private string $title;
    private string $description;
    private float $startingBid;
    private float $currentBid;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $auctionStart;
    private DateTimeImmutable $auctionEnd;
    private ItemStatus $itemStatus;
    private ?string $meetUpCode;
    private Category $category;
    private ?DateTimeImmutable $dateSold;

    public function __construct(?int $itemId, int $sellerId, string $title, string $description, 
                                float $startingBid, float $currentBid, DateTimeImmutable $createdAt, 
                                DateTimeImmutable $auctionStart, DateTimeImmutable $auctionEnd, 
                                ItemStatus $itemStatus, ?string $meetUpCode, Category $category,
                                ?DateTimeImmutable $dateSold)
    {
        $this->itemId = $itemId; 
        $this->sellerId = $sellerId;
        $this->title = $title;
        $this->description = $description;
        $this->startingBid = $startingBid;
        $this->currentBid = $currentBid;
        $this->createdAt = $createdAt;
        $this->auctionStart = $auctionStart;
        $this->auctionEnd = $auctionEnd;
        $this->itemStatus = $itemStatus;
        $this->meetUpCode = $meetUpCode;
        $this->category = $category;
        $this->dateSold = $dateSold;
    }



    public static function fromArray(array $item) : self 
    {
        $dateSold = $item['date_sold'] ? new DateTimeImmutable($item['date_sold']) : null;
        
        return new self(
            (int)$item['item_id'], 
            (int)$item['seller_id'], 
            $item['title'], 
            $item['description'], 
            (float)$item['starting_bid'], 
            (float)$item['current_bid'], 
            new DateTimeImmutable($item['created_at']), 
            new DateTimeImmutable($item['auction_start']), 
            new DateTimeImmutable($item['auction_end']),
            ItemStatus::from($item['item_status']), 
            $item['meetup_code'] ?? null, 
            Category::from($item['category']),
            $dateSold // Use the fixed nullable value
        );
    }

    



    /**
     * Get the value of itemId
     */
    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    /**
     * Set the value of itemId
     */
    public function setItemId(?int $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get the value of sellerId
     */
    public function getSellerId(): int
    {
        return $this->sellerId;
    }

    /**
     * Set the value of sellerId
     */
    public function setSellerId(int $sellerId): self
    {
        $this->sellerId = $sellerId;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of startingBid
     */
    public function getStartingBid(): float
    {
        return $this->startingBid;
    }

    /**
     * Set the value of startingBid
     */
    public function setStartingBid(float $startingBid): self
    {
        $this->startingBid = $startingBid;

        return $this;
    }

    /**
     * Get the value of currentBid
     */
    public function getCurrentBid(): float
    {
        return $this->currentBid;
    }

    /**
     * Set the value of currentBid
     */
    public function setCurrentBid(float $currentBid): self
    {
        $this->currentBid = $currentBid;

        return $this;
    }

    /**
     * Get the value of auctionStart
     */
    public function getAuctionStart(): DateTimeImmutable
    {
        return $this->auctionStart;
    }

    /**
     * Set the value of auctionStart
     */
    public function setAuctionStart(DateTimeImmutable $auctionStart): self
    {
        $this->auctionStart = $auctionStart;

        return $this;
    }

    /**
     * Get the value of auctionEnd
     */
    public function getAuctionEnd(): DateTimeImmutable
    {
        return $this->auctionEnd;
    }

    /**
     * Set the value of auctionEnd
     */
    public function setAuctionEnd(DateTimeImmutable $auctionEnd): self
    {
        $this->auctionEnd = $auctionEnd;

        return $this;
    }

    /**
     * Get the value of itemStatus
     */
    public function getItemStatus(): ItemStatus
    {
        return $this->itemStatus;
    }

    /**
     * Set the value of itemStatus
     */
    public function setItemStatus(ItemStatus $itemStatus): self
    {
        $this->itemStatus = $itemStatus;

        return $this;
    }

    /**
     * Get the value of meetUpCode
     */
    public function getMeetUpCode(): ?string
    {
        return $this->meetUpCode;
    }

    /**
     * Set the value of meetUpCode
     */
    public function setMeetUpCode(?string $meetUpCode): self
    {
        $this->meetUpCode = $meetUpCode;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * Set the value of category
     */
    public function setCategory(Category $category): self
    {
        $this->category = $category;

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

    /**
     * Get the value of dateSold
     */
    public function getDateSold(): ?DateTimeImmutable
    {
        return $this->dateSold;
    }

    /**
     * Set the value of dateSold
     */
    public function setDateSold(?DateTimeImmutable $dateSold): self
    {
        $this->dateSold = $dateSold;

        return $this;
    }
}