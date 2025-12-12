<?php

declare(strict_types=1);



class UserService
{
    private UserRepository $userRepo;
    private ItemRepository $itemRepo;
    private BidRepository $bidRepo;
    private RatingRepository $ratingRepo;
    private WatchlistRepository $watchlistRepo;

    public function __construct(
        UserRepository $userRepo,
        ItemRepository $itemRepo,
        BidRepository $bidRepo,
        RatingRepository $ratingRepo,
        WatchlistRepository $watchlistRepo
    ) {
        $this->userRepo = $userRepo;
        $this->itemRepo = $itemRepo;
        $this->bidRepo = $bidRepo;
        $this->ratingRepo = $ratingRepo;
        $this->watchlistRepo = $watchlistRepo;
    }

    /**
     * Requirement A.2.2: Profile Header Info
     */
    public function getProfileInfo(int $userId): ?User
    {
        $user = $this->userRepo->getUserById($userId);
        if (!$user) {
            throw new Exception("User not found.");
        }
        return $user;
    }

    /**
     * Requirement A.2.3: Edit Profile (Name)
     */
    public function updateProfileName(int $userId, string $firstName, string $lastName): void
    {
        if (empty(trim($firstName)) || empty(trim($lastName))) {
            throw new Exception("First name and last name cannot be empty.");
        }
        $this->userRepo->updateName($userId, trim($firstName), trim($lastName));
    }

    // /**
    //  * CORE LOGIC: Fetches the specific list based on the Tab and Sub-filter.
    //  * This maps directly to your "Role-Based Grouping" design.
    //  * 
    //  * @param string $mainTab 'selling' | 'buying' | 'reputation'
    //  * @param string $subFilter 'handover', 'active', 'sold', 'unsold', 'claim', etc.
    //  */
    // public function getProfileTabContent(int $userId, string $mainTab, string $subFilter): array
    // {
    //     return match ($mainTab) {
    //         // TAB A: Selling Center (My Inventory)
    //         'selling' => $this->getSellingContent($userId, $subFilter),
            
    //         // TAB B: Buying Activity (My Bids)
    //         'buying' => $this->getBuyingContent($userId, $subFilter),
            
    //         // TAB C: Reputation (My Reviews)
    //         'reputation' => $this->getReputationContent($userId, $subFilter),
            
    //         default => []
    //     };
    // }

    // --- Internal Helpers to route to the specific Repo methods you created ---

    public function getSellingContent(int $userId, string $subFilter): array
    {
        return match ($subFilter) {
            'handover' => $this->itemRepo->getToHandoverItemsByUserId($userId),
            'active'   => $this->itemRepo->getActiveItemsByUserId($userId),
            'sold'     => $this->itemRepo->getSoldItemsByUserId($userId),
            'unsold'   => $this->itemRepo->getUnsoldItemsByUserId($userId), // Expired/Cancelled
            default    => []
        };
    }

    public function getBuyingContent(int $userId, string $subFilter): array
    {
        return match ($subFilter) {
            'claim'     => $this->bidRepo->getToClaimBidsByUserId($userId),
            'active'    => $this->bidRepo->getActiveBidsByUserId($userId),
            'history'   => $this->bidRepo->getPastBidsByUserId($userId),
            'watchlist' => $this->watchlistRepo->getWatchlistByUserId($userId),
            default     => []
        };
    }

    public function getReputationContent(int $userId, string $subFilter): array
    {
        return match ($subFilter) {
            'received' => $this->ratingRepo->getReceivedRatings($userId),
            'given'    => $this->ratingRepo->getGivenRatings($userId),
            default    => []
        };
    }

    /**
     * Requirement A.2.12: Submit a Rating
     */
    public function submitRating(int $raterId, int $rateeId, int $itemId, int $stars, string $comment): void
    {
        if ($stars < 1 || $stars > 5) 
            throw new Exception("Rating must be between 1 and 5 stars.");

        if ($raterId === $rateeId) 
            throw new Exception("You cannot rate yourself.");

        $rating = new Rating(
            null, 
            $itemId,
            $raterId,
            $rateeId,
            $stars,
            $comment,
            new DateTimeImmutable()
        );

        $this->ratingRepo->addRating($rating);
        $this->userRepo->updateAverageRating($rateeId);
    }
}