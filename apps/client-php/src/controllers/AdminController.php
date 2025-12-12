<?php

declare(strict_types=1);

class AdminController extends BaseController
{
    private ModerationService $modService;

    public function __construct(ModerationService $modService)
    {
        $this->modService = $modService;
    }



    private function requireAdmin(): void
    {
        $this->requireLogin();

        if (($_SESSION['role'] ?? '') !== 'Admin') {
            if ($this->isAjax())
                $this->errorResponse("Access Denied: Admins only.", 403);
            else {
                // Redirect non-admins to the main marketplace
                header("Location: index.php?action=marketplace");
                exit;
            }
        }
    }



    /**
     * Route: index.php?action=admin_dashboard
     */
    public function dashboard(): void
    {
        $this->requireAdmin();

        try {
            $stats = $this->modService->getDashboardMetrics();
            require __DIR__ . '/../views/admin/dashboard.php'; // PLACEHOLDER ################################
        } catch (Exception $e) {
            echo "Error loading dashboard: " . $e->getMessage();
        }
    }




    /**
     * Route: index.php?action=admin_reports
     * Can return HTML page or JSON data for a table.
     */
    public function viewReports(): void
    {
        $this->requireAdmin();

        try {
            $reports = $this->modService->getPendingReports();

            if ($this->isAjax())
                $this->jsonResponse($reports);
            else
                require __DIR__ . '/../views/admin/reports.php'; // PLACEHOLDER ####################################
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=admin_users
     */
    public function viewUsers(): void
    {
        $this->requireAdmin();

        // doesn't support searching and filtering here
        $users = $this->modService->getAllUsers();

        if ($this->isAjax()) {
            $this->jsonResponse($users);
        } else {
            require __DIR__ . '/../views/admin/users.php'; // PLACEHOLDER ####################################
        }
    }

    /**
     * Route: index.php?action=admin_listings
     * Requirement: B.8 (Search/Manage Listings)
     */
    public function viewListings(): void
    {
        $this->requireAdmin();

        // Similar logic: Fetch all items (Active, Suspended, etc)
        // You need to add 'getAllListings()' to ModerationService
        $items = $this->modService->getAllListings();

        if ($this->isAjax()) {
            $this->jsonResponse($items);
        } else {
            require __DIR__ . '/../views/admin/listings.php'; // PLACEHOLDER ###################################
        }
    }





    /**
     * Route: index.php?action=resolve_report
     * 
     * Handles updating the report status AND performing side effects
     * (Banning user / Removing item) in one go via the Service.
     */
    public function resolveReport(): void
    {
        $this->requireAdmin();
        $input = $this->getInput();

        try {
            $reportId = (int) ($input['report_id'] ?? 0);
            $resolution = $input['resolution'] ?? ''; // 'Resolved' or 'Dismissed'
            $notes = $input['notes'] ?? '';
            $actionType = $input['action_type'] ?? 'None'; // 'BanUser', 'RemoveItem', 'None'

            if ($reportId <= 0 || !in_array($resolution, ['Resolved', 'Dismissed']))
                throw new Exception("Invalid report data.");

            $this->modService->resolveReport($reportId, $resolution, $notes, $actionType);

            $this->jsonResponse([
                'success' => true,
                'message' => 'Report processed successfully.'
            ]);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=ban_user
     * Used if Admin bans a user from the User List, not via a Report.
     */
    public function banUser(): void
    {
        $this->requireAdmin();
        $input = $this->getInput();

        try {
            $userId = (int) ($input['user_id'] ?? 0);

            if ($userId <= 0)
                throw new Exception("Invalid User ID.");

            if ($userId === (int) $_SESSION['user_id'])
                throw new Exception("You cannot ban yourself.");

            $this->modService->banUser($userId);
            $this->jsonResponse(['success' => true, 'message' => 'User banned and active items removed.']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }



    /**
     * Route: index.php?action=unban_user
     */
    public function unbanUser(): void
    {
        $this->requireAdmin();
        $input = $this->getInput();

        try {
            $userId = (int) ($input['user_id'] ?? 0);

            if ($userId <= 0)
                throw new Exception("Invalid User ID.");

            $this->modService->unbanUser($userId);
            $this->jsonResponse(['success' => true, 'message' => 'User account reactivated.']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=remove_item
     */
    public function removeItem(): void
    {
        $this->requireAdmin();
        $input = $this->getInput();

        try {
            $itemId = (int) ($input['item_id'] ?? 0);

            if ($itemId <= 0)
                throw new Exception("Invalid Item ID.");

            $this->modService->removeItem($itemId);
            $this->jsonResponse(['success' => true, 'message' => 'Item removed from marketplace.']);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
}