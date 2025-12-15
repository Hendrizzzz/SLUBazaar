<?php

declare(strict_types=1);


class AuthController extends BaseController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Route: index.php?action=login
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $input = $this->getInput();

                $email = $input['email'] ?? '';
                $password = $input['password'] ?? '';
                $user = $this->authService->login($email, $password);
                $this->authService->startUserSession($user);
                $role = isset($user['role']) ? $user['role'] : 'Member';

                $redirectUrl = ($role === 'Admin')
                    ? 'admin/dashboard' // proxy it to admin
                    : 'index.php?action=marketplace';

                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect_url' => $redirectUrl
                ]);
            } catch (Exception $e) {
                $this->errorResponse($e->getMessage(), 401);
            }
        } else {
            if (isset($_SESSION['user_id'])) {
                header("Location: index.php?action=marketplace");
                exit;
            }
            require __DIR__ . '/../views/login.php'; // PLACEHOLDER #########################################
        }
    }


    /**
     * Requirement A.1.1: Register
     * Route: index.php?action=register
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $input = $this->getInput();

                $this->authService->register(
                    $input['fname'] ?? '',
                    $input['lname'] ?? '',
                    $input['email'] ?? '',
                    $input['password'] ?? '',
                    $input['confirm_password'] ?? ''
                );

                // Success Response
                // We typically ask them to login immediately after, or auto-login them.
                // For this flow, let's redirect them to login page to confirm.
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Registration successful! Please log in.',
                    'redirect_url' => 'index.php?action=login'
                ]);

            } catch (Exception $e) {
                $this->errorResponse($e->getMessage());
            }
        } else {
            if (isset($_SESSION['user_id'])) {
                header("Location: index.php?action=marketplace");
                exit;
            }
            require __DIR__ . '/../views/register.php'; // PLACEHOLDER #################################
        }
    }




    /**
     * Route: index.php?action=logout
     */
    public function logout(): void
    {
        $this->authService->logout();

        if ($this->isAjax()) {
            $this->jsonResponse(['success' => true, 'redirect_url' => 'index.php?action=login']);
        } else {
            header("Location: index.php?action=login");
            exit;
        }
    }
}