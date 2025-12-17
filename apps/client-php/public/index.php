<?php

declare(strict_types=1);

session_start();

// CORS Headers (Crucial for your AJAX fetch calls to work smoothly)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

// Handle Preflight OPTIONS request (Browser checking permissions)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // loads Container.php and returns the 'new Container()' instance
    /** @var Container $container */
    $container = require_once __DIR__ . '/../src/bootstrap.php';

} catch (Exception $e) {
    http_response_code(500);
    die(json_encode(['error' => 'Bootstrap Error: ' . $e->getMessage()]));
}


// 3. ROUTING LOGIC
// Either default to marketplace or login, I made it default to login since Idk yet if direct to the marketplace without loggin in works
$action = $_GET['action'] ?? 'login';

try {
    switch ($action) {
        // =========================================================
        // AUTHENTICATION (AuthController)
        // =========================================================
        case 'login':
            $container->getAuthController()->login();
            break;
        case 'register':
            $container->getAuthController()->register();
            break;
        case 'logout':
            $container->getAuthController()->logout();
            break;

        // =========================================================
        // MARKETPLACE & ITEMS (AuctionController)
        // =========================================================
        case 'marketplace':
            // Handles Grid View & Search
            $container->getAuctionController()->marketplace();
            break;
        case 'item_details':
            // Handles Single Item Page
            $container->getAuctionController()->viewItem();
            break;
        case 'place_bid':
            // AJAX: Validates and saves bid
            $container->getAuctionController()->placeBid();
            break;
        case 'create_listing':
            // Handles Form View & POST creation
            $container->getAuctionController()->createListing();
            break;
        case 'toggle_watchlist':
            // AJAX: Heart button logic
            $container->getAuctionController()->toggleWatchlist();
            break;
        case 'cancel_auction':
            // Seller cancels active item
            $container->getAuctionController()->cancelAuction();
            break;

        // =========================================================
        // USER PROFILE & DASHBOARD (UserController)
        // =========================================================
        case 'profile':
            // Shows the HTML profile page
            $container->getUserController()->profile();
            break;
        case 'get_profile_tab':
            // AJAX: Returns JSON for "My Bids", "Selling", etc.
            $container->getUserController()->getProfileTab();
            break;
        case 'update_profile':
            // AJAX: Updates Name/Password
            $container->getUserController()->updateProfile();
            break;
        case 'submit_rating':
            // AJAX: Rate user after transaction
            $container->getUserController()->submitRating();
            break;
        case 'get_notifications':
            // AJAX: Top bar dropdown
            $container->getUserController()->getNotifications();
            break;
        case 'verify_transaction':
            // AJAX: Seller enters buyer code to complete deal
            $container->getUserController()->verifyTransaction();
            break;
        case 'report_user':
            // AJAX: User submits a report
            $container->getUserController()->reportUser();
            break;
        case 'report_item':
            // AJAX: Item submits a report
            $container->getAuctionController()->reportItem();
            break;

        // =========================================================
        // CHAT SYSTEM (ChatController)
        // =========================================================
        case 'chat':
            // Shows Inbox HTML
            $container->getChatController()->index();
            break;
        case 'get_messages':
            // AJAX: Fetches message history
            $container->getChatController()->getMessages();
            break;
        case 'send_message':
            // AJAX: Inserts new text
            $container->getChatController()->sendMessage();
            break;

        // =========================================================
        // ADMIN PANEL (AdminController)
        // =========================================================
        case 'admin_dashboard':
            $container->getAdminController()->dashboard();
            break;
        case 'admin_reports':
            $container->getAdminController()->viewReports();
            break;
        case 'resolve_report':
            $container->getAdminController()->resolveReport();
            break;
        case 'ban_user':
            $container->getAdminController()->banUser();
            break;
        case 'unban_user':
            $container->getAdminController()->unbanUser();
            break;
        case 'remove_item':
            $container->getAdminController()->removeItem();
            break;

        // =========================================================
        // 404 NOT FOUND
        // =========================================================
        default:
            http_response_code(404);
            // If AJAX, send JSON error
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo json_encode(['error' => 'Action not found']);
            } else {
                // If Browser, show nice 404 page
                // require __DIR__ . '/../src/views/404.php'; // Uncomment when you create this file
                echo "<h1>404 - Page Not Found</h1>";
            }
            break;
    }

} catch (Exception $e) {
    // =========================================================
    // GLOBAL ERROR HANDLING
    // =========================================================

    // Log the error for the developer
    error_log($e->getMessage());

    http_response_code(500);

    // If it's an AJAX request, return clean JSON so JS doesn't crash
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage() // In production, hide the specific message
        ]);
    } else {
        // If it's a browser page load, show error HTML
        echo "<h1>500 Internal Server Error</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
}