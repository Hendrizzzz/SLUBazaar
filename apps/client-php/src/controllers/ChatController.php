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

        require __DIR__ . '/../views/user/chat.php';
    }

    /**
     * AJAX: Get Messages
     */
    public function getMessages(): void
    {
        $userId = $this->requireLogin();
        $convoId = isset($_GET['conversation_id']) ? (int) $_GET['conversation_id'] : 0;

        try {
            // 1. Get Messages
            $messageDtos = $this->chatService->getChatHistory($convoId, $userId);

            // 2. Format for JS
            $formatted = array_map(function ($msgDto) {
                return [
                    'id' => $msgDto->messageId,
                    'text' => $msgDto->messageText,

                    // The DTO already calculated 'isMyMessage' logic for us
                    'type' => $msgDto->isMyMessage ? 'outgoing' : 'incoming',

                    'created_at' => $msgDto->createdAt->format('Y-m-d H:i')
                ];
            }, $messageDtos);

            $this->jsonResponse([
                'success' => true,
                'messages' => $formatted
            ]);
        } catch (Exception $e) {
            // Log the error so you can see it in php error logs
            error_log("ChatController Error: " . $e->getMessage());
            $this->errorResponse("Failed to load messages.");
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
            if (empty($input['conversation_id']) || empty($input['message'])) {
                throw new Exception("Missing required fields");
            }

            $this->chatService->sendMessage(
                $userId,
                (int) $input['conversation_id'],
                $input['message']
            );

            $this->jsonResponse(['success' => true]);
        } catch (Exception $e) {
            error_log("SendMessage Error: " . $e->getMessage());
            $this->errorResponse($e->getMessage());
        }
    }
}