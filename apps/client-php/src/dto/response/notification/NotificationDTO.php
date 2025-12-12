<?php

declare(strict_types=1);



class NotificationDTO implements JsonSerializable
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $content,
        public readonly string $type,
        public readonly string $timeAgo
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'notifId'      => $this->id,
            'title'   => $this->title,
            'content' => $this->content,
            'type'    => $this->type,
            'time'    => $this->timeAgo
        ];
    }

    public static function fromArray(array $row): self
    {
        return new self(
            (int) $row['notif_id'],
            (string) $row['notif_title'],
            (string) $row['content'],
            (string) $row['notif_type'],
            self::formatTimeAgo($row['notif_time'])
        );
    }

    /**
     * Helper to convert timestamp to "5 mins ago" or to other much more better
     */
    private static function formatTimeAgo(string $datetime): string
    {
        $time = strtotime($datetime);
        $diff = time() - $time;

        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        if ($diff < 604800) return floor($diff / 86400) . 'd ago';
        
        return date('M j', $time); // Fallback to "Nov 30" for such
    }
}