<?php
require_once 'db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class User {
    private $conn;
    private $table = "users";
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }

    // Create User
    public function create($name, $dob, $email, $password, $imagePath) {
       try{
            $hashedPass = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("INSERT INTO users (full_name, dob, email, password, image) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$name, $dob, $email, $hashedPass, $imagePath]);
            echo json_encode(['status' => 'success']);
            exit;
       }catch(PDOException $e){
        if ($e->getCode() == 23000) {
            echo json_encode([
                'status' => 'error',
                'errors' => [
                    'email' => 'Email already exists'
                ]
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Database insert failed: ' . $e->getMessage()
            ]);
            exit;
        }
       }
    }

    // Read Users
    public function getuserById($id) {
        $stmt = $this->conn->prepare("SELECT full_name, dob, image, email FROM users WHERE id=?");
         $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update User
    // public function update($id, $name, $dob, $email, $imagePath = null) {
    //     if ($imagePath) {
    //         $stmt = $this->conn->prepare("UPDATE users SET full_name=?, dob=?, email=?, image=? WHERE id=?");
    //         return $stmt->execute([$name, $dob, $email, $imagePath, $id]);
    //     } else {
    //         $stmt = $this->conn->prepare("UPDATE users SET full_name=?, dob=?, email=? WHERE id=?");
    //         return $stmt->execute([$name, $dob, $email, $id]);
    //     }
    // }

    public function update($id, $name, $dob) {
        $stmt = $this->conn->prepare("UPDATE users SET full_name=?, dob=? WHERE id=?");
        $success = $stmt->execute([$name, $dob, $id]);
        // header('Content-Type: application/json');

    if ($success) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Update Successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to update user'
        ]);
    }
    exit;
    }
    // Delete User
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        return $stmt->execute([$id]);
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT id, full_name, password FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
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
