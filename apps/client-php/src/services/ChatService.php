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
     * Requirement: A.2.13 (Privacy) - Only create chat if Item is Awaiting Handover.
     * This is usually called automatically when an auction ends (via AuctionService).
     */
    public function initiateChat(int $itemId, int $buyerId, int $sellerId): int
    {
        // TO BE IMPROVED: Check if conversation already exists (Prevent Duplicates)
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
     * Returns the list of conversations for the sidebar.
     */
    public function getUserConversations(int $userId): array
    {
        return $this->convoRepo->getConversationsByUserId($userId);
    }



    /**
     * Requirement: A.2.6 (Detailed Page) -> Load Chat History
     */
    public function getChatHistory(int $conversationId, int $userId): array
    {
        // Security Check: Is this user part of the conversation?
        return $this->messageRepo->getMessagesByConversationId($conversationId, $userId);
    }




    /**
     * Requirement: A.2.13 (Messaging)
     * Sends a message and updates the timestamp.
     */
    public function sendMessage(int $userId, int $conversationId, string $text): void
    {
        $convo = $this->convoRepo->getById($conversationId);
        
        if (!$convo)
            throw new Exception("Conversation not found.");

        if ($userId !== $convo->getBuyerId() && $userId !== $convo->getSellerId())
            throw new Exception("Unauthorized access.");

        if ($convo->getStatus() === ConversationStatus::Archived)
            throw new Exception("This conversation is archived. You cannot send messages.");

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



    /**
     * Requirement: A.3.4 (Meetup Verification)
     * Seller enters code -> Item becomes Sold -> Chat Archived.
     * THIS MIGHT BE IN THE AUCTION SERVICE
     */
    public function verifyMeetup(int $sellerId, int $itemId, string $code): bool
    {
        $item = $this->itemRepo->getItemById($itemId);

        if (!$item)
            throw new Exception("Item not found.");

        if ($item->getSellerId() !== $sellerId) 
            throw new Exception("Unauthorized. You are not the seller.");

        if ($item->getItemStatus()->value !== 'Awaiting Meetup')
            throw new Exception("This item is not ready for handover.");

        if ($item->getMeetupCode() !== $code) 
            throw new Exception("Incorrect verification code.");

        // 5. SUCCESS: Update Item Status to Sold
        $this->itemRepo->updateItemStatus($itemId, 'Sold');
        $this->itemRepo->addDateSold($itemId, new DateTimeImmutable());

        // 6. Archive the Chat (Req A.2.14)
        $this->convoRepo->archiveByItemId($itemId);

        return true;
    }
}