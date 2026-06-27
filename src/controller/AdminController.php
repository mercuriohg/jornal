<?php 
class AdminController
{
    public static function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            header("Location: /log-admin");
            exit;
        }

        $username_admin = $_POST['username'] ?? "";
        $user_senha = $_POST['password'] ?? "";

        if ($username_admin == "gremioadmin@gmail.com" && $user_senha == "gremioAdmin#123") {
            $_SESSION['username'] = $username_admin;
            header("Location: /admin");
            exit;
        } elseif ($username_admin == "gremioestudantil" && $user_senha == "gremioAdmin#123") {
            $_SESSION['username'] = $username_admin;
            header("Location: /admin");
            exit;
        }

        header("Location: /");
        exit;
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header("Location: /log-admin");
        exit;
    }
}
