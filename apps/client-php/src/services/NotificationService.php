<?php

declare(strict_types=1);



class NotificationService
{
    private NotificationRepository $notifRepo;

    public function __construct(NotificationRepository $notifRepo)
    {
        $this->notifRepo = $notifRepo;
    }

    /**
     * Requirement A.2.2: Get notifications for the Profile dropdown/page.
     * Returns an array of NotificationDTOs from the Repo.
     */
    public function getUserNotifications(int $userId): array
    {
        return $this->notifRepo->getNotificationsByUserId($userId);
    }




    /**
     * Internal Helper: Constructs the Entity and passes it to the Repo.
     */
    private function sendNotification(int $userId, string $title, string $content, NotificationType $type): void
    {
        $notification = new Notification(
            null, 
            $userId, 
            $title, 
            $content, 
            $type, 
            new DateTimeImmutable()
        );

        $this->notifRepo->addNotification($notification);
    }




  // THE SERVICES WILL BE THE ONE TO CALL THIS METHOD
  // ============================================================


    
    /**
     * Requirement A.2.11: Notify user they were outbid.
     */
    public function notifyOutbid(int $userId, string $itemTitle, float $newAmount): void
    {
        $this->sendNotification(
            $userId,
            "You've been outbid!",
            "Someone placed a bid of ₱" . number_format($newAmount, 2) . " on '{$itemTitle}'. Bid again now!",
            NotificationType::OUTBID
        );
    }



    /**
     * Requirement A.4.4: Notify the winner when auction ends.
     */
    public function notifyAuctionWon(int $winnerId, string $itemTitle): void
    {
        $this->sendNotification(
            $winnerId,
            "Congratulations! 🎉",
            "You won the auction for '{$itemTitle}'. Check your messages to arrange the meetup.",
            NotificationType::WIN
        );
    }



    /**
     * Requirement A.3.3: Notify the seller when item is sold.
     */
    public function notifyItemSold(int $sellerId, string $itemTitle, float $finalPrice): void
    {
        $this->sendNotification(
            $sellerId,
            "Item Sold! 💰",
            "Your item '{$itemTitle}' sold for ₱" . number_format($finalPrice, 2) . ". Chat with the buyer now.",
            NotificationType::SOLD
        );
    }



    /**
     * Requirement A.3.5: Notify seller if item expired with no bids.
     */
    public function notifyItemExpired(int $sellerId, string $itemTitle): void
    {
        $this->sendNotification(
            $sellerId,
            "Auction Expired",
            "Your listing '{$itemTitle}' ended with no bids. You can relist it from your profile.",
            NotificationType::EXPIRED
        );
    }



    /**
     * Requirement A.3.4: Notify user when transaction is verified/complete.
     */
    public function notifyTransactionComplete(int $userId, string $itemTitle): void
    {
        $this->sendNotification(
            $userId,
            "Transaction Verified ✅",
            "The deal for '{$itemTitle}' is marked as complete. Please rate your experience.",
            NotificationType::COMPLETE
        );
    }



    /**
     * Requirement B.9: Notify user if Admin removed their item.
     */
    public function notifyItemRemoved(int $userId, string $itemTitle, string $reason): void
    {
        $this->sendNotification(
            $userId,
            "Policy Violation 🛑",
            "Your item '{$itemTitle}' was removed by the admins. Reason: {$reason}",
            NotificationType::WARNING
        );
    }



    /**
     * Requirement A.2.10: Watchlist Ending Soon
     */
    public function notifyWatchlistEnding(int $userId, string $itemTitle): void
    {
        $this->sendNotification(
            $userId,
            "Ending Soon ⏳",
            "An item on your watchlist, '{$itemTitle}', is ending in less than 15 minutes.",
            NotificationType::WATCHLIST
        );
    }
}