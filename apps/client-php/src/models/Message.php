<?php
declare(strict_types=1);

class Message 
{
    private ?int $messageId;
    private int $conversationId;
    private string $messageText;
    private bool $isSeller;
    private DateTimeImmutable $createdAt;
    private bool $isRead;

    public function __construct(?int $messageId, int $conversationId, string $messageText, 
                                bool $isSeller, DateTimeImmutable $createdAt, bool $isRead)
    {   
        $this->messageId = $messageId;
        $this->conversationId = $conversationId;
        $this->messageText = $messageText;
        $this->isSeller = $isSeller;
        $this->createdAt = $createdAt;
        $this->isRead = $isRead;
    }



    public static function fromArray(array $message) : self
    {
        return new self (
            (int)$message['message_id'], 
            (int)$message['conversation_id'], 
            $message['message_text'], 
            (bool)$message['is_seller'], 
            new DateTimeImmutable($message['created_at']),
            (bool)$message['is_read']
        );
    }
    

    /**
     * Get the value of messageId
     */
    public function getMessageId(): ?int
    {
        return $this->messageId;
    }

    /**
     * Set the value of messageId
     */
    public function setMessageId(?int $messageId): self
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Get the value of conversationId
     */
    public function getConversationId(): int
    {
        return $this->conversationId;
    }

    /**
     * Set the value of conversationId
     */
    public function setConversationId(int $conversationId): self
    {
        $this->conversationId = $conversationId;

        return $this;
    }

    /**
     * Get the value of messageText
     */
    public function getMessageText(): string
    {
        return $this->messageText;
    }

    /**
     * Set the value of messageText
     */
    public function setMessageText(string $messageText): self
    {
        $this->messageText = $messageText;

        return $this;
    }

    /**
     * Get the value of isSeller
     */
    public function isIsSeller(): bool
    {
        return $this->isSeller;
    }

    /**
     * Set the value of isSeller
     */
    public function setIsSeller(bool $isSeller): self
    {
        $this->isSeller = $isSeller;

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
     * Get the value of isRead
     */
    public function isIsRead(): bool
    {
        return $this->isRead;
    }

    /**
     * Set the value of isRead
     */
    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

        return $this;
    }
}