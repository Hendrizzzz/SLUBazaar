<?php

declare(strict_types=1);


class RatingRepository 
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }


    public function addRating(Rating $rating)
    {
        $query = "INSERT INTO rating (item_id, rater_id, ratee_id, rating_value, comment, created_at) 
                    values (?, ?, ?, ?, ?, ?) ";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the addRating query : " . $this->db->error);

        $itemId = $rating->getItemId();
        $raterId = $rating->getRaterId();
        $rateeId = $rating->getRateeId();
        $ratingValue = $rating->getRatingValue();
        $comment = $rating->getComment();
        $createdAt = $rating->getCreatedAt()->format('Y-m-d H:i:s');
        $statement->bind_param('iiiiss', $itemId, $raterId, $rateeId, $ratingValue, $comment, $createdAt);

        if (!$statement->execute())
            throw new Exception("Failed to add a rating : " . $statement->error);
        
        $rating->setRatingId($this->db->insert_id);
        $statement->close();
    }



    public function getReceivedRatings(int $userId) : array
    {
        $query = "
            SELECT 
                r.rating_value, r.comment, r.created_at,
                i.item_id, i.title, i.current_bid as final_price,
                u.fname, u.lname
            FROM rating r
            JOIN item i ON r.item_id = i.item_id
            JOIN user u ON r.rater_id = u.user_id 
            WHERE r.ratee_id = ?
            ORDER BY r.created_at DESC
        ";

        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the getReceivedRatings query : " 
                                    . $this->db->error);
        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getReceivedRatings : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        $ratings = [];

        foreach($rows as $row)
            $ratings[] = RatingCardDTO::fromArray($row);

        return $ratings;
    }


    public function getGivenRatings(int $userId) : array
    {
        $query = "
            SELECT 
                r.rating_value, r.comment, r.created_at,
                i.item_id, i.title, i.current_bid as final_price,
                u.fname, u.lname
            FROM rating r
            JOIN item i ON r.item_id = i.item_id
            JOIN user u ON r.ratee_id = u.user_id 
            WHERE r.rater_id = ?
            ORDER BY r.created_at DESC
        ";

        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the getGivenRatings query : " 
                                    . $this->db->error);
        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getGivenRatings : " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        $ratings = [];

        foreach($rows as $row)
            $ratings[] = RatingCardDTO::fromArray($row);

        return $ratings;
    }


}