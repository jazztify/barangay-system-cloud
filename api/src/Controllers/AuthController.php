<?php
namespace App\Controllers;

use App\Database\DB;

class AuthController {
    public function login() {
        global $appConfig;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $pdo = DB::connect();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                if (!$user['is_active']) {
                    $error = "Account is disabled.";
                } else {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['full_name'] = $user['full_name'];
                    
                    header("Location: {$appConfig['base_url']}/dashboard");
                    exit;
                }
            } else {
                $error = "Invalid credentials.";
            }
        }
        
        require __DIR__ . '/../../templates/auth/login.php';
    }

    public function logout() {
        global $appConfig;
        session_destroy();
        header("Location: {$appConfig['base_url']}/login");
        exit;
    }
}
