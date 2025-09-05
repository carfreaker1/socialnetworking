<?php
require_once 'db.php';

class LikeDislike {
    private $conn;
    private $table = "users";
    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
    }
    public function toggleAction($userId, $postId, $action) {
        $checkSql = "SELECT * FROM likes_dislikes WHERE user_id=? AND post_id=?";
        $stmt = $this->conn->prepare($checkSql);
        $stmt->execute([$userId, $postId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if ($row['action'] === $action) {
                // Remove action
                $deleteSql = "DELETE FROM likes_dislikes WHERE user_id=? AND post_id=?";
                $deleteStmt = $this->conn->prepare($deleteSql);
                return $deleteStmt->execute([$userId, $postId]);
            } else {
                // Update action
                $updateSql = "UPDATE likes_dislikes SET action=? WHERE user_id=? AND post_id=?";
                $updateStmt = $this->conn->prepare($updateSql);
                return $updateStmt->execute([$action, $userId, $postId]);
            }
        } else {
            // Insert new
            $insertSql = "INSERT INTO likes_dislikes (user_id, post_id, action) VALUES (?, ?, ?)";
            $insertStmt = $this->conn->prepare($insertSql);
            return $insertStmt->execute([$userId, $postId, $action]);
        }
    }

    public function countActions($postId) {
        $sql = "SELECT 
                SUM(action='like') AS likes,
                SUM(action='dislike') AS dislikes
                FROM likes_dislikes WHERE post_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserAction($userId, $postId) {
        $sql = "SELECT action FROM likes_dislikes WHERE user_id=? AND post_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId, $postId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['action'] : null;
    }
}
?>
