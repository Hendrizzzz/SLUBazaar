<?php

declare(strict_types=1);


class UserController extends BaseController
{
    private UserService $userService;
    private AuthService $authService;
    private NotificationService $notifService;
    private ChatService $chatService;

    public function __construct(
        UserService $userService,
        AuthService $authService,
        NotificationService $notifService,
        ChatService $chatService
    ) {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->notifService = $notifService;
        $this->chatService = $chatService;
    }

    /**
     * Route: index.php?action=profile
     */
    public function profile(): void
    {
        $userId = $this->requireLogin();

        try {
            // Get Header Info
            $user = $this->userService->getProfileInfo($userId);

            // Pass $user to the views
            require __DIR__ . '/../views/user/profile.php';
        } catch (Exception $e) {
            $this->authService->logout();
            header("Location: index.php?action=login");
            exit;
        }
    }





    /**
     * Route: index.php?action=get_profile_tab&tab=selling&filter=active
     */
    public function getProfileTab(): void
    {
        $userId = $this->requireLogin();

        $mainTab = $_GET['tab'] ?? 'selling'; // selling | buying | reputation
        $subFilter = $_GET['filter'] ?? '';   // active | history | etc.

        try {
            $data = match ($mainTab) {
                'selling' => $this->userService->getSellingContent($userId, $subFilter),
                'buying' => $this->userService->getBuyingContent($userId, $subFilter),
                'reputation' => $this->userService->getReputationContent($userId, $subFilter),
                default => []
            };

            $this->jsonResponse($data);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }





    /**
     * Route: index.php?action=update_profile
     * Handles both Name changes and Password changes.
     */
    public function updateProfile(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $messages = [];

            if (!empty($input['fname']) && !empty($input['lname'])) {
                $this->userService->updateProfileName($userId, $input['fname'], $input['lname']);
                $_SESSION['fname'] = $input['fname'];
                $messages[] = "Name updated.";
            }

            if (!empty($input['new_password'])) {
                $this->authService->changePassword(
                    $userId,
                    $input['current_password'] ?? '',
                    $input['new_password'],
                    $input['confirm_password'] ?? ''
                );
                $messages[] = "Password changed.";
            }

            if (empty($messages))
                throw new Exception("No changes detected.");

            $this->jsonResponse([
                'success' => true,
                'message' => implode(' ', $messages)
            ]);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=submit_rating
     */
    public function submitRating(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $this->userService->submitRating(
                $userId,
                (int) $input['target_user_id'],
                (int) $input['item_id'],
                (int) $input['stars'],
                $input['comment'] ?? ''
            );

            $this->jsonResponse(['success' => true, 'message' => 'Rating submitted successfully.']);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=get_notifications
     */
    public function getNotifications(): void
    {
        $userId = $this->requireLogin();

        try {
            $notifs = $this->notifService->getUserNotifications($userId);
            $this->jsonResponse($notifs);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }




    /**
     * Route: index.php?action=verify_transaction
     * Context: Called from the "To Handover" card in the Profile.
     */
    public function verifyTransaction(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $itemId = (int) ($input['item_id'] ?? 0);
            $code = trim($input['code'] ?? '');

            if ($itemId <= 0 || strlen($code) !== 6)
                throw new Exception("Invalid Item ID or Code.");

            $this->chatService->verifyMeetup($userId, $itemId, $code);

            $this->jsonResponse([
                'success' => true,
                'message' => 'Transaction Verified! Item marked as Sold.'
            ]);

        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }


}