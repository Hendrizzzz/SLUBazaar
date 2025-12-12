<?php

declare(strict_types=1);

class ItemPageBidDTO implements JsonSerializable
{
    public function __construct(
        public readonly string $bidderName,  // "Juan D."
        public readonly float $amount,       // 500.00
        public readonly string $timeAgo      // "5 mins ago"
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'bidder' => $this->bidderName,
            'amount' => $this->amount,
            'time'   => $this->timeAgo
        ];
    }

    public static function fromArray(array $row): self
    {
        // Format Name: "Juan Dela Cruz" -> "Juan D." (Privacy best practice)
        // Or just use full name if requirements say so.
        $name = $row['fname'] . ' ' . substr($row['lname'], 0, 1) . '.';

        return new self(
            $name,
            (float)$row['bid_amount'],
            self::formatTimeAgo($row['bid_timestamp'])
        );
    }

    private static function formatTimeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        return date('M j', $time);
    }
}