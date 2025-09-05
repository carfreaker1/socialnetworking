<?php
require_once 'db.php';
require_once 'LikeDislike.php';


class Post extends LikeDislike {
    private $conn;
    private $table = "users";
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
    public function createPost($userId, $content, $image = null) {
        try{
            $sql = "INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$userId, $content, $image]);
            return $this->conn->lastInsertId();
        }catch(PDOException $e){
            echo json_encode([
                'status' => 'error',
                'message' => 'Database insert failed: ' . $e->getMessage()
            ]);
            exit;

        }
    }

    public function getAllPosts() {
        $sql = "SELECT posts.*, users.full_name FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.id DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getIndivididualUserPosts($userId) {
        $sql = "SELECT posts.*, users.full_name, users.image AS userImage FROM posts JOIN users ON posts.user_id = users.id WHERE users.id = $userId ORDER BY posts.id DESC";
        $stmt = $this->conn->query($sql);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $likeDislike = new LikeDislike();

        foreach ($posts as &$post) {
            $counts = $likeDislike->countActions($post['id']);
            $post['likes'] = $counts['likes'] ?? 0;
            $post['dislikes'] = $counts['dislikes'] ?? 0;
            $userAction = $likeDislike->getUserAction($_SESSION['user_id'], $post['id']);
            $post['user_action'] = $userAction;
        }
        return $posts;
    }

    public function deletePost($postId, $userId) {
        $sql = "DELETE FROM posts WHERE id=? AND user_id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$postId, $userId]);
    }
}
?>
