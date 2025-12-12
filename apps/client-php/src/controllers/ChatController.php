<?php

declare(strict_types=1);

class ChatController extends BaseController
{
    private ChatService $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }



    /**
     * View Inbox Page
     */
    public function index(): void
    {
        $userId = $this->requireLogin();
        $conversations = $this->chatService->getUserConversations($userId);
        require __DIR__ . '/../views/user/chat.php'; // PLACEHOLDER ######################################
    }




    /**
     * AJAX: Get Messages
     */
    public function getMessages(): void
    {
        $userId = $this->requireLogin();
        $convoId = isset($_GET['conversation_id']) ? (int) $_GET['conversation_id'] : 0;

        try {
            $messages = $this->chatService->getChatHistory($convoId, $userId);

            $this->jsonResponse([
                'success' => true,
                'messages' => $messages
            ]);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }



    /**
     * AJAX: Send Text
     */
    public function sendMessage(): void
    {
        $userId = $this->requireLogin();
        $input = $this->getInput();

        try {
            $this->chatService->sendMessage(
                $userId,
                (int) ($input['conversation_id'] ?? 0),
                $input['message'] ?? ''
            );

            $this->jsonResponse(['success' => true]);
        } catch (Exception $e) {
            $this->errorResponse($e->getMessage());
        }
    }
}