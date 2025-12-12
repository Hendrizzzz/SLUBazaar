<?php

declare(strict_types=1);

abstract class BaseController
{
    /**
     * Helper to send JSON responses (for AJAX) and exit.
     */
    protected function jsonResponse(array $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }




    /**
     * Helper to send JSON errors.
     */
    protected function errorResponse(string $message, int $statusCode = 400): void
    {
        $this->jsonResponse(['success' => false, 'error' => $message], $statusCode);
    }




    /**
     * Helper to get input from $_POST or JSON Body (AJAX).
     */
    protected function getInput(): array
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if JSON
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (strpos($contentType, 'application/json') !== false) {
                $content = file_get_contents('php://input');
                return json_decode($content, true) ?? [];
            }
            return $_POST;
        }
        return $_GET;
    }




    /**
     * Helper to check if request is AJAX.
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }




    /**
     * Security: Ensure user is logged in.
     */
    protected function requireLogin(): int
    {
        if (!isset($_SESSION['user_id']))
            if ($this->isAjax())
                $this->errorResponse("Unauthorized", 401);
            else {
                header("Location: index.php?action=login");
                exit;
            }
            
        return (int)$_SESSION['user_id'];
    }


    
}