<?php

declare(strict_types=1);


class AuctionService
{
    private ItemRepository $itemRepo;
    private BidRepository $bidRepo;
    private UserRepository $userRepo;
    private WatchlistRepository $watchlistRepo;
    private NotificationService $notifService;
    private ChatService $chatService;

    public function __construct(
        ItemRepository $itemRepo,
        BidRepository $bidRepo,
        UserRepository $userRepo,
        WatchlistRepository $watchlistRepo,
        NotificationService $notifService,
        ChatService $chatService
    ) {
        $this->itemRepo = $itemRepo;
        $this->bidRepo = $bidRepo;
        $this->userRepo = $userRepo;
        $this->watchlistRepo = $watchlistRepo;
        $this->notifService = $notifService;
        $this->chatService = $chatService;
    }

    // =========================================================================
    //  1. MARKETPLACE BROWSING (Web)
    // =========================================================================

    public function searchItems(?string $keyword, string $category, string $status, ?float $minPrice, ?float $maxPrice, string $sort): array
    {
        // 1. Convert simple inputs into the DTO expected by ItemRepository::search
        $data = [
            'q' => $keyword,
            'categories' => ($category === 'all') ? [] : [$category], // All, Electronics, etc
            'statuses' => $status, // SearchItemsRequestDTO handles 'All' -> ['Active', 'Pending'] logic
            'min' => $minPrice,
            'max' => $maxPrice,
            'sort' => $sort
        ];

        $criteria = SearchItemsRequestDTO::fromArray($data);

        return $this->itemRepo->search($criteria);
    }



    public function getItemDetailsView(int $itemId, int $currentUserId): ?ItemDetailsDTO
    {
        $item = $this->itemRepo->getItemById($itemId);
        if (!$item)
            return null;
        $seller = $this->userRepo->getUserById($item->getSellerId());
        $images = $this->itemRepo->getImageUrls($itemId);
        $bids = $this->bidRepo->getBidsByItemId($itemId);
        $isWatching = $this->watchlistRepo->isWatching($currentUserId, $itemId);

        if (!$seller) {
            // Option A: Throw a clear error
            throw new Exception("Data Error: The seller for this item (ID: {$item->getSellerId()}) does not exist.");
        }

        return ItemDetailsDTO::create(
            $item,
            $seller,
            $images,
            $bids,
            $isWatching,
            $currentUserId
        );
    }




    // =========================================================================
    //  2. BIDDING LOGIC (Web + Anti-Sniping)
    // =========================================================================

    public function placeBid(int $bidderId, int $itemId, float $amount): array
    {
        $item = $this->itemRepo->getItemById($itemId);
        if (!$item)
            throw new Exception("Item not found.");

        if ($item->getSellerId() === $bidderId)
            throw new Exception("You cannot bid on your own item.");

        if ($item->getItemStatus() !== ItemStatus::Active)
            throw new Exception("This auction is not active.");

        if ($amount <= $item->getCurrentBid())
            throw new Exception("Bid must be higher than ₱" . number_format($item->getCurrentBid(), 2));

        // Get Previous Winner (To notify them later)
        $previousBidderId = $this->bidRepo->getHighestBidderId($itemId);
        $bid = new Bid(null, $itemId, $bidderId, $amount, new DateTimeImmutable());
        $this->bidRepo->addBid($bid);

        // Anti-Sniping 
        $now = new DateTimeImmutable();
        $endTime = $item->getAuctionEnd();
        $secondsRemaining = $endTime->getTimestamp() - $now->getTimestamp();

        $extended = false;
        if ($secondsRemaining < 120 && $secondsRemaining > 0) {
            // Extend by 5 minutes
            $newEndTime = $now->modify('+5 minutes');
            $this->itemRepo->updateAuctionEnd($itemId, $newEndTime);
            $extended = true;
        }

        $this->itemRepo->updateCurrentBid($itemId, $amount);
        if ($previousBidderId && $previousBidderId !== $bidderId)
            $this->notifService->notifyOutbid($previousBidderId, $item->getTitle(), $amount);

        return [
            'success' => true,
            'new_price' => $amount,
            'time_extended' => $extended
        ];
    }





    // =========================================================================
    //  3. SELLER ACTIONS (Web)
    // =========================================================================

    /**
     * Creates a listing and handles image uploads.
     * 
     * @param array $files The $_FILES['images'] array from the controller
     */
    public function createListing(int $sellerId, string $title, string $desc, float $startBid, string $cat, string $endString, array $files): int
    {
        if ($startBid < 0)
            throw new Exception("Starting bid cannot be negative.");

        $endDate = new DateTimeImmutable($endString);
        $now = new DateTimeImmutable();

        if ($endDate <= $now)
            throw new Exception("End time must be in the future.");

        $item = new Item(
            null,
            $sellerId,
            $title,
            $desc,
            $startBid,
            $startBid,
            $now,
            $now, // CHANGE THIS TO START TIME
            $endDate,
            ItemStatus::Active,
            null,
            Category::from($cat),
            null
        );

        $this->itemRepo->addItem($item);
        $newItemId = (int) $item->getItemId();

        $uploadedPaths = $this->processImageUploads($newItemId, $files);

        if (!empty($uploadedPaths))
            $this->itemRepo->addItemImages($newItemId, $uploadedPaths);

        return $newItemId;
    }


    /**
     * Internal Helper to handle the messy logic of file uploads.
     * Renames files to avoid conflicts (e.g. item_55_timestamp_random.jpg).
     */
    private function processImageUploads(int $itemId, array $files): array
    {
        $projectRoot = dirname(__DIR__, 2);
        $webPath = 'assets/uploads/items/';
        $targetDir = $projectRoot . '/public/' . $webPath;

        $uploadsRoot = $projectRoot . '/public/assets/uploads';

        if (is_link($uploadsRoot) || (file_exists($uploadsRoot) && !is_dir($uploadsRoot))) {
            unlink($uploadsRoot);
        }

        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0775, true)) {
                error_log("CRITICAL: Failed to create upload directory at " . $targetDir);
                throw new Exception("Server Error: Cannot create upload directory.");
            }
        }

        $savedPaths = [];

        foreach ($files as $file) {
            if ($file['error'] !== UPLOAD_ERR_OK)
                continue;

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/webp']))
                continue;

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!$extension)
                $extension = 'jpg';

            $newFileName = "item_{$itemId}_" . uniqid() . "." . $extension;
            $targetPath = $targetDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $savedPaths[] = $webPath . $newFileName;
            } else {
                error_log("Failed to move uploaded file to: " . $targetPath);
            }
        }

        return $savedPaths;
    }





    public function cancelAuction(int $userId, int $itemId): void
    {
        $item = $this->itemRepo->getItemById($itemId);

        if (!$item)
            throw new Exception("Item not found.");
        if ($item->getSellerId() !== $userId)
            throw new Exception("Unauthorized.");

        $status = $item->getItemStatus();
        if ($status !== ItemStatus::Active && $status !== ItemStatus::Pending)
            throw new Exception("Cannot cancel a completed auction.");

        $this->itemRepo->updateItemStatus($itemId, 'Cancelled By Seller');

        // Notify highest bidder if exists
        $topBidder = $this->bidRepo->getHighestBidderId($itemId);
        if ($topBidder)
            $this->notifService->notifyItemRemoved($topBidder, $item->getTitle(), "Seller cancelled the auction.");
    }




    // =========================================================================
    //  4. WATCHLIST (Web)
    // =========================================================================

    public function toggleWatchlist(int $userId, int $itemId): bool
    {
        if ($this->watchlistRepo->isWatching($userId, $itemId)) {
            $this->watchlistRepo->removeWatchlistByUserAndItemId($userId, $itemId);
            return false;
        } else {
            $this->watchlistRepo->addWatchlist(new Watchlist(null, $userId, $itemId, new DateTimeImmutable()));
            return true;
        }
    }




    public function getUserWatchlist(int $userId): array
    {
        return $this->watchlistRepo->getWatchlistByUserId($userId);
    }





    // =========================================================================
    //  5. BACKGROUND WORKER
    // =========================================================================

    public function processExpiredAuctions(): int
    {
        $count = 0;
        $items = $this->itemRepo->findExpiredActiveItems(50);

        foreach ($items as $row) {
            $this->finalizeAuction(
                (int) $row['item_id'],
                (int) $row['seller_id'],
                $row['title']
            );
            $count++;
        }

        return $count;
    }

    private function finalizeAuction(int $itemId, int $sellerId, string $title): void
    {
        $winnerId = $this->bidRepo->getHighestBidderId($itemId);

        if ($winnerId) {
            // --- SOLD SCENARIO ---
            $finalPrice = $this->bidRepo->getHighestBidAmount($itemId);
            $code = str_pad((string) rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $this->itemRepo->markAsAwaitingMeetup($itemId, $winnerId, $finalPrice, $code);

            // Initiate Chat
            $this->chatService->initiateChat($itemId, $winnerId, $sellerId);

            // Notifications
            $this->notifService->notifyItemSold($sellerId, $title, $finalPrice);
            $this->notifService->notifyAuctionWon($winnerId, $title);

        } else {
            // --- EXPIRED SCENARIO (0 Bids) ---
            $this->itemRepo->updateItemStatus($itemId, 'Expired');
            $this->notifService->notifyItemExpired($sellerId, $title);
        }
    }
}