<?php

declare(strict_types=1);

// Enums
require_once __DIR__ . '/models/enum/AccountStatus.php';
require_once __DIR__ . '/models/enum/Category.php';
require_once __DIR__ . '/models/enum/ItemStatus.php';
require_once __DIR__ . '/models/enum/NotificationType.php';
require_once __DIR__ . '/models/enum/ConversationStatus.php';
require_once __DIR__ . '/models/enum/ReportStatus.php';
require_once __DIR__ . '/models/enum/ReportType.php';
require_once __DIR__ . '/models/enum/Role.php';

// Models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Item.php';
require_once __DIR__ . '/models/Bid.php';
require_once __DIR__ . '/models/Rating.php';
require_once __DIR__ . '/models/Conversation.php';
require_once __DIR__ . '/models/Message.php';
require_once __DIR__ . '/models/Report.php';
require_once __DIR__ . '/models/Watchlist.php';
require_once __DIR__ . '/models/Notification.php';

// DTOs
require_once __DIR__ . '/dto/internal/BidRowDTO.php';
require_once __DIR__ . '/dto/internal/ItemRowDTO.php';
require_once __DIR__ . '/dto/request/SearchItemsRequestDTO.php';
require_once __DIR__ . '/dto/response/Marketplace/ItemCardDTO.php';
require_once __DIR__ . '/dto/response/Marketplace/ItemDetailsDTO.php';
require_once __DIR__ . '/dto/response/Marketplace/ItemPageBidDTO.php';
require_once __DIR__ . '/dto/response/Admin/AdminUserTableRowDTO.php';
require_once __DIR__ . '/dto/response/notification/NotificationDTO.php';
require_once __DIR__ . '/dto/response/Messaging/MessagesDTO.php';
require_once __DIR__ . '/dto/response/Messaging/ConversationDTO.php';
require_once __DIR__ . '/dto/response/Profile/ActiveBidCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/ClaimItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/HistoryItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/RatingCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/SellerListingCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/SoldItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/ToHandoverItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/UnsoldItemCardDTO.php';
require_once __DIR__ . '/dto/response/Profile/WatchlistItemDTO.php';

// Repositories
require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/ItemRepository.php';
require_once __DIR__ . '/repositories/BidRepository.php';
require_once __DIR__ . '/repositories/RatingRepository.php';
require_once __DIR__ . '/repositories/NotificationRepository.php';
require_once __DIR__ . '/repositories/WatchlistRepository.php';
require_once __DIR__ . '/repositories/ConversationRepository.php';
require_once __DIR__ . '/repositories/MessageRepository.php';
require_once __DIR__ . '/repositories/ReportRepository.php';

// Services
require_once __DIR__ . '/services/NotificationService.php';
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/ChatService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/AuctionService.php';
require_once __DIR__ . '/services/ModerationService.php';

// Controllers
require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/AuctionController.php';
require_once __DIR__ . '/controllers/ChatController.php';
require_once __DIR__ . '/controllers/AdminController.php';


class Container
{
    private array $services = [];
    private ?mysqli $db = null;

    private array $dbConfig;

    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }


    public function getDb(): mysqli
    {
        if ($this->db === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                $this->db = new mysqli(
                    $this->dbConfig['host'],
                    $this->dbConfig['user'],
                    $this->dbConfig['pass'],
                    $this->dbConfig['name'],
                    (int) $this->dbConfig['port']
                    //                    (int) ($this->dbConfig['port']??3306)
                );
                $this->db->set_charset("utf8mb4");
            } catch (mysqli_sql_exception $e) {
                // Re-throw so index.php can handle it (force 200 or 500)
                throw $e;
            }
        }
        return $this->db;
    }




    // =========================================================================
    //  HELPER METHODS
    // =========================================================================

    public function getBaseUrl(): string
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        $path = dirname($_SERVER['SCRIPT_NAME']);
        $path = str_replace('\\', '/', $path);

        if ($path === '/' || $path === '.') {
            $path = '';
        }

        return rtrim($protocol . $domainName . $path, '/') . '/';
    }


    // =========================================================================
    //  REPOSITORIES
    // =========================================================================

    public function getUserRepo(): UserRepository
    {
        if (!isset($this->services['userRepo']))
            $this->services['userRepo'] = new UserRepository($this->getDb());
        return $this->services['userRepo'];
    }

    public function getItemRepo(): ItemRepository
    {
        if (!isset($this->services['itemRepo']))
            $this->services['itemRepo'] = new ItemRepository($this->getDb());
        return $this->services['itemRepo'];
    }

    public function getBidRepo(): BidRepository
    {
        if (!isset($this->services['bidRepo']))
            $this->services['bidRepo'] = new BidRepository($this->getDb());
        return $this->services['bidRepo'];
    }

    public function getRatingRepo(): RatingRepository
    {
        if (!isset($this->services['ratingRepo']))
            $this->services['ratingRepo'] = new RatingRepository($this->getDb());
        return $this->services['ratingRepo'];
    }

    public function getNotifRepo(): NotificationRepository
    {
        if (!isset($this->services['notifRepo']))
            $this->services['notifRepo'] = new NotificationRepository($this->getDb());
        return $this->services['notifRepo'];
    }

    public function getWatchlistRepo(): WatchlistRepository
    {
        if (!isset($this->services['watchlistRepo']))
            $this->services['watchlistRepo'] = new WatchlistRepository($this->getDb());
        return $this->services['watchlistRepo'];
    }

    public function getConvoRepo(): ConversationRepository
    {
        if (!isset($this->services['convoRepo']))
            $this->services['convoRepo'] = new ConversationRepository($this->getDb());
        return $this->services['convoRepo'];
    }

    public function getMessageRepo(): MessageRepository
    {
        if (!isset($this->services['messageRepo']))
            $this->services['messageRepo'] = new MessageRepository($this->getDb());
        return $this->services['messageRepo'];
    }

    public function getReportRepo(): ReportRepository
    {
        if (!isset($this->services['reportRepo']))
            $this->services['reportRepo'] = new ReportRepository($this->getDb());
        return $this->services['reportRepo'];
    }




    // =========================================================================
    //  SERVICES (Inject Repositories)
    // =========================================================================

    public function getNotifService(): NotificationService
    {
        if (!isset($this->services['notifService']))
            $this->services['notifService'] = new NotificationService($this->getNotifRepo());
        return $this->services['notifService'];
    }

    public function getAuthService(): AuthService
    {
        if (!isset($this->services['authService']))
            $this->services['authService'] = new AuthService($this->getUserRepo());
        return $this->services['authService'];
    }

    public function getChatService(): ChatService
    {
        if (!isset($this->services['chatService'])) {
            $this->services['chatService'] = new ChatService(
                $this->getConvoRepo(),
                $this->getMessageRepo(),
                $this->getItemRepo()
            );
        }
        return $this->services['chatService'];
    }

    public function getUserService(): UserService
    {
        if (!isset($this->services['userService'])) {
            $this->services['userService'] = new UserService(
                $this->getUserRepo(),
                $this->getItemRepo(),
                $this->getBidRepo(),
                $this->getRatingRepo(),
                $this->getWatchlistRepo(),
                $this->getReportRepo()
            );
        }
        return $this->services['userService'];
    }

    public function getAuctionService(): AuctionService
    {
        if (!isset($this->services['auctionService'])) {
            $this->services['auctionService'] = new AuctionService(
                $this->getItemRepo(),
                $this->getBidRepo(),
                $this->getUserRepo(),
                $this->getWatchlistRepo(),
                $this->getNotifService(),
                $this->getChatService()
            );
        }
        return $this->services['auctionService'];
    }

    public function getModerationService(): ModerationService
    {
        if (!isset($this->services['modService'])) {
            $this->services['modService'] = new ModerationService(
                $this->getReportRepo(),
                $this->getUserRepo(),
                $this->getItemRepo()
            );
        }
        return $this->services['modService'];
    }





    // =========================================================================
    //  CONTROLLERS (Inject Services)
    // =========================================================================

    public function getAuthController(): AuthController
    {
        if (!isset($this->services['authController']))
            $this->services['authController'] = new AuthController($this->getAuthService());
        return $this->services['authController'];
    }

    public function getUserController(): UserController
    {
        if (!isset($this->services['userController'])) {
            $this->services['userController'] = new UserController(
                $this->getUserService(),
                $this->getAuthService(),
                $this->getNotifService(),
                $this->getChatService()
            );
        }
        return $this->services['userController'];
    }

    public function getAuctionController(): AuctionController
    {
        if (!isset($this->services['auctionController']))
            $this->services['auctionController'] = new AuctionController($this->getAuctionService(), $this->getModerationService());
        return $this->services['auctionController'];
    }

    public function getChatController(): ChatController
    {
        if (!isset($this->services['chatController']))
            $this->services['chatController'] = new ChatController($this->getChatService());
        return $this->services['chatController'];
    }

    public function getAdminController(): AdminController
    {
        if (!isset($this->services['adminController']))
            $this->services['adminController'] = new AdminController($this->getModerationService());
        return $this->services['adminController'];
    }
}