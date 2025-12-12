<?php

declare(strict_types=1);

class ConversationDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $conversationId,
        public readonly string $status,
        public readonly string $itemTitle,
        public readonly ?string $itemImageUrl,
        public readonly string $otherUserFname,
        public readonly string $otherUserLname,
        public readonly ?string $lastMessage,

        public readonly DateTimeImmutable $lastMessageTime,
        public readonly bool $isRead,
        public readonly bool $amITheSeller,
        public readonly ?bool $lastMessageFromSeller
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'conversationId' => $this->conversationId,
            'status' => $this->status,
            'itemTitle' => $this->itemTitle,
            'itemImageUrl' => $this->itemImageUrl,
            'otherUserFname' => $this->otherUserFname,
            'otherUserLname' => $this->otherUserLname,
            'lastMessage' => $this->lastMessage,
            'lastMessageTime' => $this->lastMessageTime->format('c'),
            'isRead' => $this->isRead,
            'amITheSeller' => $this->amITheSeller,
            'lastMessageFromSeller' => $this->lastMessageFromSeller
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['conversation_id'],
            $data['status'],
            $data['item_title'],
            $data['item_image_url'] ?? null,
            $data['other_fname'],
            $data['other_lname'],

            // CHANGED: Now accepts NULL if the database returns it
            $data['message_text'] ?? null,

            new DateTimeImmutable($data['sort_time']),

            // Logic: If is_read is null (no messages), treat as Read (true) so no red dot appears
            (bool) ($data['is_read'] ?? true),

            (bool) $data['am_i_the_seller'],

            isset($data['last_msg_from_seller']) ? (bool) $data['last_msg_from_seller'] : null
        );
    }
}