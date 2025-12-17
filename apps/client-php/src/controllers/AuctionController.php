<?php

declare(strict_types=1);

class AuctionController extends BaseController
{
    private AuctionService $auctionService;
    private ModerationService $moderationService;

    public function __construct(AuctionService $auctionService, ModerationService $moderationService)
    {
        $this->auctionService = $auctionService;
        $this->moderationService = $moderationService;
    }



    /**
     * Route: index.php?action=marketplace
     * Handles both initial Page Load (HTML) and AJAX Search/Filter (JSON).
     */
    public function marketplace(): void
    {
        $input = $this->getInput();

        $search = $input['q'] ?? '';

        // Handle Category (Can be string 'all' or array of strings)
        $rawCategory = $input['category'] ?? [];
        $categories = [];

        if (is_array($rawCategory)) {
            $categories = $rawCategory;
        } elseif (is_string($rawCategory) && $rawCategory !== 'all' && !empty($rawCategory)) {
            $categories = [$rawCategory];
        }
        // If it's 'all' or empty, we leave $categories as []

        $status = $input['status'] ?? 'Active';
        $minPrice = isset($input['min']) && is_numeric($input['min']) ? (float) $input['min'] : null;
        $maxPrice = isset($input['max']) && is_numeric($input['max']) ? (float) $input['max'] : null;
        $sort = $input['sort'] ?? 'newest';

        $items = $this->auctionService->searchItems(
            $search,
            $categories, // Pass array
            $status,
            $minPrice,
            $maxPrice,
            $sort
        );

        if ($this->isAjax()) {
            $this->jsonResponse($items);
        } else {
            require __DIR__ . '/../views/user/marketplace.php';
        }
    }

    /**
     * Route: index.php?action=report_item
     */
    public function reportItem(): void
    {
        // 1. Ensure user is logged in
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            // 2. Validate Input
            $itemId = (int) ($input['item_id'] ?? 0);
            $reason = trim($input['reason'] ?? '');
            $description = trim($input['description'] ?? '');

            if ($itemId <= 0) {
                throw new Exception("Invalid item ID.");
            }
            if (empty($reason) || empty($description)) {
                throw new Exception("Please provide a reason and description.");
            }

            // 3. Call the Service (ModerationService)
            // Params: reporterId, targetUserId (null), targetItemId, reason, description
            $this->moderationService->submitReport(
                $userId,
                null,
                $itemId,
                $reason,
                $description
            );

            $this->jsonResponse(['success' => true, 'message' => 'Report submitted successfully.']);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }



    /**
     * Route: index.php?action=item_details&id=123
     */
    public function viewItem(): void
    {
        $itemId = (int) ($_GET['id'] ?? 0);
        $userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

        try {
            $itemDetails = $this->auctionService->getItemDetailsView($itemId, $userId);

            if (!$itemDetails) {
                http_response_code(404);
                echo "<h1>404 Not Found</h1><p>The item you requested does not exist.</p>";
                return;
            }

            require __DIR__ . '/../views/user/item_details.php';

        } catch (Throwable $e) { // CHANGED: Exception -> Throwable
            http_response_code(500);
            echo "<h1>500 Internal Server Error</h1><p>Error processing item request: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }






    /**
     * Route: index.php?action=place_bid
     */
    public function placeBid(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $itemId = (int) ($input['item_id'] ?? 0);
            $amount = (float) ($input['amount'] ?? 0.0);

            if ($itemId <= 0 || $amount <= 0)
                throw new Exception("Invalid bid data.");

            $result = $this->auctionService->placeBid($userId, $itemId, $amount);
            $this->jsonResponse($result);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }





    /**
     * Route: index.php?action=create_listing
     * SERVES 2 PURPOSE, NAVIGATING INTO THE 'CREATE LISTING' AND ACTUALLY SUBMITTING THE FORM for the CREATION OF LISTING
     */
    public function createListing(): void
    {
        $userId = $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // We access $_POST directly here because file uploads are multipart/form-data
                $input = $_POST;

                // Format Files array properly
                // PHP $_FILES structure is weird for multiple files: ['name'][0], ['name'][1]
                // We normalize it into an array of file objects
                $files = [];

                if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
                    $count = count($_FILES['images']['name']);

                    for ($i = 0; $i < $count; $i++) {
                        // We simply pass all entries to the service. 
                        // The Service's loop checks for UPLOAD_ERR_OK, so empty slots will be skipped there safely.
                        $files[] = [
                            'name' => $_FILES['images']['name'][$i],
                            'type' => $_FILES['images']['type'][$i],
                            'tmp_name' => $_FILES['images']['tmp_name'][$i],
                            'error' => $_FILES['images']['error'][$i],
                            'size' => $_FILES['images']['size'][$i],
                        ];
                    }
                }

                // TODO: add the start_time to the input
                $newId = $this->auctionService->createListing(
                    $userId,
                    $input['title'],
                    $input['description'],
                    (float) $input['starting_bid'],
                    $input['category'],
                    $input['start_time'],
                    $input['end_time'],
                    $files
                );

                header("Location: index.php?action=marketplace&success=listing_created&id={$newId}");
                exit;

            } catch (Exception $e) {
                // In production, you might want to log this error and redirect with an error query param
                echo "Error: " . $e->getMessage();
                exit;
            }
        } else {
            require __DIR__ . '/../views/user/create_listing.php'; // PLACEHOLDER ########################################
        }
    }





    /**
     * Route: index.php?action=cancel_auction
     */
    public function cancelAuction(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $itemId = (int) ($input['item_id'] ?? 0);

            $this->auctionService->cancelAuction($userId, $itemId);

            $this->jsonResponse(['success' => true, 'message' => 'Auction cancelled successfully.']);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=toggle_watchlist
     */
    public function toggleWatchlist(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $itemId = (int) ($input['item_id'] ?? 0);

            $isWatching = $this->auctionService->toggleWatchlist($userId, $itemId);

            $this->jsonResponse([
                'success' => true,
                'is_watching' => $isWatching, // Frontend updates UI (Heart filled/empty)
                'message' => $isWatching ? 'Added to watchlist' : 'Removed from watchlist'
            ]);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
}