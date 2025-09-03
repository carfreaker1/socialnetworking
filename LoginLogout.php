<?php
require_once 'db.php';
if (!isset($_SESSION)) {
    session_start();
}
class LoginLogout {
    private $conn;
    private $table = "users";
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, full_name, email,image, dob, password FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['dob'] = $user['dob'];
            $_SESSION['image'] = $user['image'];
            return true;
        }   
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_destroy();
        return true;
        exit;
    }
}
