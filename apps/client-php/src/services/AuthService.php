<?php

declare(strict_types=1);

class AuthService
{
    private UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * Requirement A.1.1: Register new user
     * Validates SLU email, checks duplicates, hashes password, and saves.
     * @throws Exception
     */
    public function register(string $fname, string $lname, string $email, string $password, string $confirmPassword): int
    {
        if (empty($fname) || empty($lname) || empty($email) || empty($password))
            throw new Exception("All fields are required.");

        if ($password !== $confirmPassword)
            throw new Exception("Passwords do not match.");

        if (!$this->isSluEmail($email))
            throw new Exception("Registration is restricted to @slu.edu.ph emails only.");

        // === BYPASS DATABASE CHECK FOR NOW ===
        if ($this->userRepo->findByEmail($email))
            throw new Exception("This email is already registered.");

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $user = $this->constructUser($fname, $lname, $email, $passwordHash);

        return $this->userRepo->addUser($user);
    }

    private function constructUser(string $fname, string $lname, string $email, string $passwordHash): User
    {
        return new User(
            null,
            $fname,
            $lname,
            $email,
            false,
            $passwordHash,
            null,
            new DateTimeImmutable(),
            AccountStatus::Unverified,
            Role::Member
        );
    }

    /**
     * Requirement A.2.1: Login
     * Verifies credentials and returns user data array.
     */
    public function login(string $email, string $password): array
    {
        // $mockUser = [
        //     'user_id' => 1,
        //     'email' => $email, // Use the email they typed
        //     'role' => 'Member', // Default to Member
        //     'fname' => 'TestUser',
        //     'lname' => 'Lastname',
        //     'account_status' => 'Active',
        //     'password_hash' => password_hash($password, PASSWORD_DEFAULT) // Fake hash so verify passes
        // ];

        // --- DATABASE LOGIC (COMMENTED OUT FOR LATER) ---

        $user = $this->userRepo->findByEmail($email);

        if (!$user)
            throw new Exception("Invalid email or password.");

        if (!password_verify($password, $user['password_hash']))
            throw new Exception("Invalid email or password.");

        if ($user['account_status'] === 'banned')
            throw new Exception("This account has been banned. Contact Admin.");

        return $user;


        // // Return mock data immediately
        // return $mockUser;
    }

    /**
     * Requirement A.2.3: Change Password
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword, string $confirmNew): void
    {
        if ($newPassword !== $confirmNew)
            throw new Exception("New passwords do not match.");

        if (strlen($newPassword) < 6)
            throw new Exception("Password must be at least 6 characters.");

        // === BYPASS DB CHECK ===
        /*
        $user = $this->userRepo->getUserById($userId);
        if (!$user)
            throw new Exception("User not found.");

        if (!password_verify($currentPassword, $user->getPasswordHash()))
            throw new Exception("Current password is incorrect.");

        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->userRepo->updatePassword($userId, $newHash);
        */
    }

    public function startUserSession(array $user): void
    {
        // Assumes session_start() is called in index.php
        session_regenerate_id(true);

        $_SESSION['user_id'] = (int)$user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fname'] = $user['fname'];

        $_SESSION['last_activity'] = time();
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
    }

    /**
     * Helper: Get current User ID (or throw error if not logged in)
     * Used by other Services/Controllers
     */
    public function getCurrentUserId(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // === TEMPORARY: If no session, auto-login as ID 1 ===
        if (!isset($_SESSION['user_id'])) {
            // throw new Exception("Unauthorized. Please login.");
            return 1;
        }

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            $this->logout();
            throw new Exception("Session expired.");
        }
        $_SESSION['last_activity'] = time();

        return $_SESSION['user_id'];
    }

    private function isSluEmail(string $email): bool
    {
        // Optional: you can comment this out too if you want to test with 'admin@test.com'
        return (bool)preg_match('/^[a-zA-Z0-9._%+-]+@slu\.edu\.ph$/i', $email);
//        return true;
    }
}