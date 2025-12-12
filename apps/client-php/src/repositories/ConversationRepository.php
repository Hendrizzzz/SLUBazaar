<?php

declare(strict_types=1);


class ConversationRepository
{

    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }


    /**
     * Adds a conversation in the database.
     */
    public function addConversation(Conversation $conversation): void
    {
        $itemId = $conversation->getItemId();
        $buyerId = $conversation->getBuyerId();
        $sellerId = $conversation->getSellerId();
        $status = $conversation->getStatus()->value;

        $query = "INSERT INTO conversation (item_id, buyer_id, seller_id, status) values (?, ?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was a problem preparing the query: " . $this->db->error);

        $statement->bind_param('iiis', $itemId, $buyerId, $sellerId, $status);

        if (!$statement->execute())
            throw new Exception("Adding a conversation failed: " . $statement->error);

        $conversationId = $this->db->insert_id;
        $conversation->setConversationId($conversationId);

        $statement->close();
    }



    /**
     * Archives a conversation. 
     */
    public function archiveByItemId(int $itemId): void
    {
        $status = ConversationStatus::Archived->value;
        $statement = $this->db->prepare("UPDATE conversation SET status = ? WHERE `item_id` = ?");

        if (!$statement)
            throw new Exception("There was a problem preparing the data: " . $this->db->error);

        $statement->bind_param('si', $status, $itemId);

        if (!$statement->execute())
            throw new Exception("Archiving an item by id failed: " . $statement->error);

        $statement->close();
    }


    /**
     * Retrieves all conversations of a user.
     */
    public function getConversationsByUserId(int $userId): array
    {
        $query = $this->retrieveGetConversationsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare getConversationsByUserId query. " . $this->db->error);

        $statement->bind_param('iiii', $userId, $userId, $userId, $userId);

        if (!$statement->execute())
            throw new Exception("getConversationsByUserId failed. " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        $conversations = [];

        foreach ($rows as $row)
            $conversations[] = ConversationDTO::fromArray($row);

        return $conversations;
    }



    /**
     * Retrieves all active conversations of a user.
     */
    public function getActiveConversationsByUserId(int $userId): array
    {
        $query = $this->retrieveGetStatusConversationsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare the getActiveConversationByUserId query: " . $this->db->error);

        $status = ConversationStatus::Active->value;
        $statement->bind_param('iiiis', $userId, $userId, $userId, $userId, $status);

        if (!$statement->execute())
            throw new Exception("Failed to do getActiveConversationsByUserId: " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);

        $activeConversationsOfUser = [];
        foreach ($rows as $row)
            $activeConversationsOfUser[] = ConversationDTO::fromArray($row);

        return $activeConversationsOfUser;
    }

    /**
     * Retrieves all archived conversations of a user. 
     */
    public function getArchivedConversationsByUserId(int $userId): array
    {
        $query = $this->retrieveGetStatusConversationsByUserId();
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Failed to prepare the getArchivedConversationByUserId query: " . $this->db->error);

        $status = ConversationStatus::Archived->value;
        $statement->bind_param('iiiis', $userId, $userId, $userId, $userId, $status);

        if (!$statement->execute())
            throw new Exception("Failed to do getArchivedConversationsByUserId: " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        $archivedConversationsOfUser = [];

        foreach ($rows as $row)
            $archivedConversationsOfUser[] = ConversationDTO::fromArray($row);

        return $archivedConversationsOfUser;
    }




    public function getById(int $conversationId): ?Conversation
    {
        $query = "SELECT * FROM conversation WHERE conversation_id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param('i', $conversationId);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();

        if (!$row)
            return null;

        return Conversation::fromArray($row);
    }



    private function retrieveGetConversationsByUserId(): string
    {
        return "SELECT 
                c.conversation_id, 
                c.status,
                i.title AS item_title,
                
                -- FIXED: Fetch the image from the item_image table
                (SELECT image_url FROM item_image WHERE item_id = i.item_id LIMIT 1) AS item_image_url,
                
                -- Get the OTHER person's details
                u.fname AS other_fname, 
                u.lname AS other_lname,

                -- Message Info (Handle NULL if no messages exist yet)
                COALESCE(m.message_text, 'No messages yet') AS message_text,
                m.is_read, 
                
                -- 'is_seller' refers to who SENT the last message. 
                -- 1 = Seller sent it, 0 = Buyer sent it.
                m.is_seller AS last_msg_from_seller,

                -- Helper to know if 'I' (User 2) am the seller in this specific chat
                (c.seller_id = ?) AS am_i_the_seller,

                -- TIME LOGIC: 
                -- 1. Last message time
                -- 2. If no msg, use Date Sold
                -- 3. If not sold, use Item Created At
                COALESCE(m.created_at, i.date_sold, i.created_at) AS sort_time

            FROM conversation c
            JOIN item i ON c.item_id = i.item_id

            -- Join User to find the 'Other' person
            -- If I am the buyer, join the seller. If I am the seller, join the buyer.
            JOIN user u ON u.user_id = IF(c.buyer_id = ?, c.seller_id, c.buyer_id)

            -- It ensures the conversation appears even if there are 0 messages.
            LEFT JOIN message m ON m.message_id = (
                SELECT message_id FROM message m2 
                WHERE m2.conversation_id = c.conversation_id 
                ORDER BY m2.created_at DESC LIMIT 1
            ) 

            WHERE c.buyer_id = ? OR c.seller_id = ?
            ORDER BY sort_time DESC;
            ";
    }




    private function retrieveGetStatusConversationsByUserId(): string
    {
        return "SELECT 
                    c.conversation_id, 
                    c.status,
                    i.title AS item_title,
                    
                    -- Image Subquery
                    (SELECT image_url FROM item_image WHERE item_id = i.item_id LIMIT 1) AS item_image_url,
                    
                    u.fname AS other_fname, 
                    u.lname AS other_lname,

                    COALESCE(m.message_text, 'No messages yet') AS message_text,
                    m.is_read, 
                    m.is_seller AS last_msg_from_seller,
                    (c.seller_id = ?) AS am_i_the_seller,

                    COALESCE(m.created_at, i.date_sold, i.created_at) AS sort_time

                FROM conversation c
                JOIN item i ON c.item_id = i.item_id
                
                JOIN user u ON u.user_id = IF(c.buyer_id = ?, c.seller_id, c.buyer_id)

                LEFT JOIN message m ON m.message_id = (
                    SELECT message_id FROM message m2 
                    WHERE m2.conversation_id = c.conversation_id 
                    ORDER BY m2.created_at DESC LIMIT 1
                ) 

                -- NOTICE THE PARENTHESES HERE!
                -- We want: (I am involved) AND (Status matches)
                WHERE (c.buyer_id = ? OR c.seller_id = ?) 
                AND c.status = ?
                
                ORDER BY sort_time DESC";
    }



}