<?php
declare(strict_types=1);

/**
 * The class that will be passed to Profile View (Ratings View)
 */
class RatingCardDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $ratingValue,
        public readonly ?string $comment,
        public readonly string $date,

        // Item Details
        public readonly int $itemId,
        public readonly string $itemTitle,
        public readonly float $itemPrice, // The final sold price

        // The "Other Person": Rater who rate you or Ratee who did you rate 
        public readonly string $otherUserName
    ) {}

    
    public function jsonSerialize(): array
    {
        return [
            'rating' => $this->ratingValue,
            'comment' => $this->comment,
            'date' => $this->date,
            'id' => $this->itemId,
            'title' => $this->itemTitle,
            'price' => $this->itemPrice,
            'user' => $this->otherUserName
        ];
    }


    public static function fromArray(array $data) : self
    {
        return new RatingCardDTO(
                (int)$data['rating_value'],
                $data['comment'] ?? null,
                (new DateTimeImmutable($data['created_at']))->format('M d, Y'),
                (int)$data['item_id'],
                $data['title'],
                (float)$data['final_price'],
                $data['fname'] . ' ' . $data['lname'] 
        );
    }
}