<?php

declare(strict_types=1);




class ModerationService
{
    private ReportRepository $reportRepo;
    private UserRepository $userRepo;
    private ItemRepository $itemRepo;

    public function __construct(
        ReportRepository $reportRepo,
        UserRepository $userRepo,
        ItemRepository $itemRepo
    ) {
        $this->reportRepo = $reportRepo;
        $this->userRepo = $userRepo;
        $this->itemRepo = $itemRepo;
    }

    /**
     * Requirement A.2.16: User submits a report
     */
    public function submitReport(int $reporterId, ?int $targetUserId, ?int $targetItemId, string $reason, string $desc): int
    {
        if (empty($reason) || empty($desc))
            throw new Exception("Reason and Description are required.");

        if ($targetUserId === null && $targetItemId === null)
            throw new Exception("You must specify a target (User or Item) to report.");

        if ($targetUserId !== null && $targetItemId !== null)
            throw new Exception("Invalid request: Cannot report both User and Item simultaneously.");

        $typeEnum = ($targetUserId !== null) ? ReportType::User : ReportType::Item;
        $report = new Report(
            null, 
            $reporterId,
            $targetUserId,
            $targetItemId,
            $typeEnum, 
            $reason,
            $desc,
            ReportStatus::Pending, 
            null,     
            new DateTimeImmutable()
        );

        $this->reportRepo->addReport($report);
        return $report->getReportId();
    }




    /**
     * Requirement B.3: Admin views pending reports
     * This logic usually resides in Repo queries, but Service exposes it.
     */
    public function getPendingReports(): array
    {
        return $this->reportRepo->getReportsByStatus('Pending');
    }



    /**
     * Requirement B.5: Admin resolves a report
     * Action: Updates status and optionally bans user or removes item.
     * TODO: THINK ABOUT THIS FIRST
     */
    public function resolveReport(int $reportId, string $resolution, string $adminNotes, string $actionType): void
    {
        $this->reportRepo->updateReportStatusWithNotes($reportId, $resolution, $adminNotes);

        // 2. Take Action (if any)
        if ($resolution === 'Resolved' && $actionType !== 'None') {
            $report = $this->reportRepo->getReportById($reportId); 
            
            if ($actionType === 'BanUser' && $report->getTargetUserId())
                $this->banUser($report->getTargetUserId());

            if ($actionType === 'RemoveItem' && $report->getTargetItemId()) 
                $this->removeItem($report->getTargetItemId());
        }
    }

    /**
     * Requirement B.7: Suspend/Ban User
     */
    public function banUser(int $userId): void
    {
        $this->userRepo->updateAccountStatus($userId, AccountStatus::Banned);
        $this->itemRepo->removeAllActiveItemsBySeller($userId);
    }



    /**
     * Requirement B.7: Unban User
     */
    public function unbanUser(int $userId): void
    {
        $this->userRepo->updateAccountStatus($userId, AccountStatus::Active);
    }




    /**
     * Requirement B.9: Remove Inappropriate Listing
     */
    public function removeItem(int $itemId): void
    {
        $this->itemRepo->updateItemStatus($itemId, 'Removed By Admin');
    }


    
    /**
     * Requirement B.2: Dashboard Metrics
     * Aggregates data from multiple repositories for the Admin Dashboard.
     */
    public function getDashboardMetrics(): array
    {
        $itemStats = $this->itemRepo->getItemDashboardStats();
        $reportStats = $this->reportRepo->getPendingReportStats();
        $totalUsers = $this->userRepo->countTotalMembers();

        return [
            'active_listings'   => (int) $itemStats['active_count'],
            'closed_listings'   => (int) $itemStats['closed_count'],
            'total_users'       => $totalUsers,
            'sold_items'        => (int) $itemStats['sold_count'],
            'reported_listings' => $reportStats['item_reports'],
            'reported_users'    => $reportStats['user_reports']
        ];
    }



    public function getAllUsers(): array 
    {
        return $this->userRepo->getAllUsers(); // Ensure Repo has this
    }

    public function getAllListings(): array 
    {
        // We want EVERYTHING (Active, Sold, Removed) for Admin view
        // Ensure Repo has a method that selects without 'WHERE status=Active'
        return $this->itemRepo->getAllItemsForAdmin(); 
    }
}