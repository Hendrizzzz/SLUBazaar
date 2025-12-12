<?php

declare(strict_types=1);


/**
 * The class that will be passed to Admin (User View)
 */
class AdminUserTableRowDTO implements JsonSerializable
{
    public function __construct(
        public readonly string $fname,
        public readonly string $lname, 
        public readonly string $email, 
        public readonly float $averageRating,
        public readonly DateTimeImmutable $createdAt,
        public readonly string $accountStatus
    )
    {}



    public function jsonSerialize(): array
    {
        return [
            'fname'         => $this->fname,
            'lname'         => $this->lname,
            'email'         => $this->email,
            'averageRating' => $this->averageRating,
            'createdAt'     => $this->createdAt->format('c'),
            'accountStatus' => $this->accountStatus
        ];
    }

    



    public static function fromArray(array $data) : self
    {
        return new self(
            $data['fname'], 
            $data['lname'], 
            $data['email'], 
            (float)$data['average_rating'],
            new DateTimeImmutable($data['created_at']),
            $data['account_status']
        );
    }




}