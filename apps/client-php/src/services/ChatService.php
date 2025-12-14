<?php

declare(strict_types=1);

class ChatService
{
    private ConversationRepository $convoRepo;
    private MessageRepository $messageRepo;
    private ItemRepository $itemRepo;

    public function __construct(
        ConversationRepository $convoRepo,
        MessageRepository $messageRepo,
        ItemRepository $itemRepo
    ) {
        $this->convoRepo = $convoRepo;
        $this->messageRepo = $messageRepo;
        $this->itemRepo = $itemRepo;
    }

    /**
     * Requirement: A.2.13 (Privacy)
     */
    public function initiateChat(int $itemId, int $buyerId, int $sellerId): int
    {
        $convo = new Conversation(
            null,
            $itemId,
            $buyerId,
            $sellerId,
            ConversationStatus::Active
        );

        $this->convoRepo->addConversation($convo);

        return $convo->getConversationId();
    }

    /**
     * Requirement: A.2.2 (Profile - Messages Tab)
     * Returns data formatted specifically for the Frontend JS
     */
    public function getUserConversations(int $userId): array
    {
        $dtos = $this->convoRepo->getConversationsByUserId($userId);
        $formatted = [];

        foreach ($dtos as $dto) {
            $formatted[] = [
                'conversation_id' => $dto->conversationId,

                // Combine names
                'user_name'       => $dto->otherUserFname . ' ' . $dto->otherUserLname,

                // Avatar logic (using UI Avatars if null)
                'user_avatar'     => $dto->itemImageUrl ?? ('https://ui-avatars.com/api/?name=' . urlencode($dto->otherUserFname)),

                'item_title'      => $dto->itemTitle,
                'last_message'    => $dto->lastMessage,
                'is_read'         => $dto->isRead
            ];
        }

        return $formatted;
    }

    /**
     * Requirement: A.2.6 (Detailed Page)
     */
    public function getChatHistory(int $conversationId, int $userId): array
    {
        return $this->messageRepo->getMessagesByConversationId($conversationId, $userId);
    }

    /**
     * Helper to get single conversation
     */
    public function getConversation(int $conversationId): ?Conversation
    {
        return $this->convoRepo->getById($conversationId);
    }

    /**
     * Requirement: A.2.13 (Messaging)
     */
    public function sendMessage(int $userId, int $conversationId, string $text): void
    {
        $convo = $this->convoRepo->getById($conversationId);

        if (!$convo)
            throw new Exception("Conversation not found.");

        if ($userId !== $convo->getBuyerId() && $userId !== $convo->getSellerId())
            throw new Exception("Unauthorized access.");

        if ($convo->getStatus() === ConversationStatus::Archived)
            throw new Exception("This conversation is archived.");

        $isSeller = ($userId === $convo->getSellerId());

        $message = new Message(
            null,
            $conversationId,
            $text,
            $isSeller,
            new DateTimeImmutable(),
            false
        );

        $this->messageRepo->addMessage($message);
    }
}