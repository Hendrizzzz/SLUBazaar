<?php

declare(strict_types=1);


class Notification 
{
    private ?int $notifId;
    private int $userId;
    private string $notifTitle;
    private string $content;
    private NotificationType $notifType;
    private DateTimeImmutable $notifTime;

    public function __construct(?int $notifId, int $userId, string $notifTitle, string $content, 
                                NotificationType $notifType, DateTimeImmutable $notifTime) 
    {
        $this->notifId = $notifId;
        $this->userId = $userId;
        $this->notifTitle = $notifTitle;
        $this->content = $content;
        $this->notifType = $notifType;
        $this->notifTime = $notifTime;
    }



    public static function fromArray(array $notification) : self
    {
        return new self(
            (int)$notification['notif_id'], 
            (int)$notification['user_id'], 
            $notification['notif_title'], 
            $notification['content'], 
            NotificationType::from($notification['notif_type']), 
            new DateTimeImmutable($notification['notif_time'])
        );
    }


    public function getNotifId() : ?int 
    {
        return $this->notifId;
    }


    public function setNotifId(?int $notifId) : self
    {
        $this->notifId = $notifId;
        return $this;
    }


    public function setUserId(int $userId) : self 
    {
        $this->userId = $userId;
        return $this;
    }


    public function getUserId() : int 
    {
        return $this->userId;
    }


    public function getNotifTitle() : string 
    {
        return $this->notifTitle;
    }


    public function setNotifTitle(string $notifTitle) : self 
    {
        $this->notifTitle = $notifTitle;
        return $this;
    }


    public function getContent() : string
    {
        return $this->content;
    }


    public function setContent(string $content) : self 
    {
        $this->content = $content;
        return $this;
    }


    public function getNotifType() : NotificationType 
    {
        return $this->notifType;
    }


    public function setNotifType(NotificationType $notifType) : self 
    {
        $this->notifType = $notifType;
        return $this;
    }



    /**
     * Get the value of notifTime
     */
    public function getNotifTime(): DateTimeImmutable
    {
        return $this->notifTime;
    }

    /**
     * Set the value of notifTime
     */
    public function setNotifTime(DateTimeImmutable $notifTime): self
    {
        $this->notifTime = $notifTime;

        return $this;
    }
}