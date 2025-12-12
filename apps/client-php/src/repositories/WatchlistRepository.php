<?php

declare(strict_types=1);


class WatchlistRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }



    /**
     * Checks if a user has already watchlisted a specific item.
     */
    public function isWatching(int $userId, int $itemId): bool
    {
        $query = "SELECT watchlist_id FROM watchlist WHERE user_id = ? AND item_id = ? LIMIT 1";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Error preparing isWatching query: " . $this->db->error);

        $statement->bind_param('ii', $userId, $itemId);

        if (!$statement->execute())
            throw new Exception("Failed to check isWatching: " . $statement->error);

        $result = $statement->get_result();
        $exists = $result->num_rows > 0;
        $statement->close();

        return $exists;
    }



    /**
     * Adds an item to the watchlist.
     */
    public function addWatchlist(Watchlist $watchlist) : void
    {
        $query = "INSERT INTO watchlist (user_id, item_id, created_at) values (?, ?, ?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error in addWatchlist query: " . $this->db->error);

        $userId = $watchlist->getUserId();
        $itemId = $watchlist->getItemId();
        $createdAt = $watchlist->getCreatedAt()->format('Y-m-d H:i:s');
        $statement->bind_param('iis', $userId, $itemId, $createdAt);

        if(!$statement->execute())
            throw new Exception("Failed to add watchlist : " . $statement->error);

        $statement->close();
    }



    /**
     * Removes an item from watchlist based on User ID and Item ID (Used for Toggle).
     */
    public function removeWatchlistByUserAndItemId(int $userId, int $itemId): void
    {
        $query = "DELETE FROM watchlist WHERE user_id = ? AND item_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Error preparing removeWatchlistByUserAndItemId: " . $this->db->error);

        $statement->bind_param('ii', $userId, $itemId);

        if (!$statement->execute())
            throw new Exception("Failed to remove watchlist item: " . $statement->error);

        $statement->close();
    }



    /**
     * Removes a watchlist entry by its Primary Key (Used for 'Delete' button in profile).
     */
    public function removeWatchlistId(int $watchlistId) : void
    {
        $query = "DELETE from watchlist WHERE watchlist_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error in removeWatchlist query: " . $this->db->error);

        $statement->bind_param('i', $watchlistId);

        if(!$statement->execute())
            throw new Exception("Failed to remove watchlist : " . $statement->error);

        $statement->close();
    }




    public function getWatchlistByUserId(int $userId) : array
    {
        $query = $this->retrieveGetWatchlistByUserQuery();
        $statement = $this->db->prepare($query);

        if (!$statement) 
            throw new Exception("There was a problem preparing the getWatchlistByUserId() query: " 
                                . $this->db->error);

        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("getWatchlistByUserId has an error: " . $statement->error);

        $results = $statement->get_result();
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $watchlists = [];
        foreach ($rows as $row) 
            $watchlists[] = SellerListingCardDTO::fromArray($row); 
        
        return $watchlists;
    }





    private function retrieveGetWatchlistByUserQuery() : string 
    {
        return "SELECT 
                    i.item_id,
                    i.title,
                    i.item_status,
                    i.starting_bid,
                    i.current_bid,
                    i.auction_start,
                    i.auction_end,
                    -- Subquery 1: Get one image
                    (
                        SELECT image_url 
                        FROM item_image 
                        WHERE item_id = i.item_id 
                        LIMIT 1
                    ) AS image_url,
                    -- Subquery 2: Get total bids (needed for logic)
                    (
                        SELECT COUNT(*) 
                        FROM bid 
                        WHERE item_id = i.item_id
                    ) AS bid_count
                FROM 
                    watchlist w
                JOIN 
                    item i ON w.item_id = i.item_id
                WHERE 
                    w.user_id = ?
                ORDER BY 
                    -- Sort Priority: Active items first, then Pending, then others.
                    CASE 
                        WHEN i.item_status = 'Active' THEN 1
                        WHEN i.item_status = 'Pending' THEN 2
                        ELSE 3 
                    END ASC,
                    -- Secondary Sort: Items ending soonest appear at the top
                    i.auction_end ASC;";
    }
}