<?php

declare(strict_types=1);

class MessageDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $messageId,
        public readonly int $conversationId,
        public readonly string $messageText,
        public readonly bool $isMyMessage,
        public readonly DateTimeImmutable $createdAt,
        public readonly bool $isRead
    ) {}



    public function jsonSerialize(): array
    {
        return [
            'messageId'       => $this->messageId,
            'conversationId'  => $this->conversationId,
            'messageText'     => $this->messageText,
            'isMyMessage'        => $this->isMyMessage,
            'createdAt'       => $this->createdAt->format('c'), 
            'isRead'          => $this->isRead
        ];
    }



    public static function fromArray(array $row, int $userId): self
    {
        $amISeller = (int) $row['seller_id'] === $userId;
        $msgFromSeller = (bool) $row['is_seller'];
        // If I am Seller AND Message is From Seller = TRUE (It's mine)
        // If I am Buyer  AND Message is From Buyer  = TRUE (It's mine)
        // Otherwise = FALSE
        $isMyMessage = ($amISeller === $msgFromSeller);

        return new self(
            (int) $row['message_id'],
            (int) $row['conversation_id'],
            (string) $row['message_text'],
            $isMyMessage,
            new DateTimeImmutable($row['created_at']),
            (bool) $row['is_read']
        );
    }

}
