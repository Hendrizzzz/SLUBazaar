<?php

declare(strict_types=1);



class NotificationRepository 
{
    private mysqli $db;


    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }


    public function addNotification(Notification $notification)
    {
        $query = "INSERT INTO notification(user_id, notif_title, content, notif_type, notif_time) 
                    VALUES (?, ?, ?, ?, ?)";
        $statement = $this->db->prepare($query);

        if(!$statement)
            throw new Exception("There was an error preparing the addNotification query : " . $this->db->error);

        $userId = $notification->getUserId();
        $notifTitle = $notification->getNotifTitle();
        $content = $notification->getContent();
        $notifType = $notification->getNotifType()->value;
        $notifTime = $notification->getNotifTime()->format('Y-m-d H:i:s');
        $statement->bind_param('issss', $userId, $notifTitle, $content, $notifType, $notifTime);

        if (!$statement->execute())
            throw new Exception("Failed to add notification : " . $statement->error);

        $statement->close();
    }


    public function getNotificationsByUserId(int $userId): array 
    {
        $query = "SELECT * FROM notification WHERE user_id = ? ORDER BY notif_time DESC";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("Error preparing getNotificationsByUserId query: " . $this->db->error);

        $statement->bind_param('i', $userId);

        if (!$statement->execute())
            throw new Exception("Failed to getNotificationsByUserId: " . $statement->error);

        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();

        $dtos = [];
        foreach ($rows as $row)
            $dtos[] = NotificationDTO::fromArray($row);
        
        return $dtos;
    }



}