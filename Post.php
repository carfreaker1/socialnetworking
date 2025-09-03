<?php
require_once 'db.php';

class Post  {
    private $conn;
    private $table = "users";
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
    public function createPost($userId, $content, $image = null) {
        $sql = "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$userId, $content, $image]);
    }

    public function getAllPosts() {
        $sql = "SELECT posts.*, users.name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePost($postId, $userId) {
        $sql = "DELETE FROM posts WHERE id=? AND user_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$postId, $userId]);
    }
}
?>
