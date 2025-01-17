<?php

class PictureModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection(); // use a singleton connection
    }

    public function savePicture($userId, $imageUrl, $description)
    {
        $query = "INSERT INTO pictures (user_id, image_url, description) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $baseUrl = "http://" . $_SERVER['HTTP_HOST'];
        $imageUrl = $baseUrl . "/uploads/" . $imageUrl;

        return $stmt->execute([$userId, $imageUrl, $description]);
    }

    public function getPosts($page = 1, $limit = 10)
    {
        // Validate and sanitize input
        $page = max(1, intval($page));
        $limit = max(1, min(50, intval($limit)));
        $offset = ($page - 1) * $limit;

        // Get posts with user information, like counts, and comment counts
        // $query = "SELECT 
        //                 p.id,
        //                 p.user_id,
        //                 p.image_url,
        //                 p.description,
        //                 p.created_at,
        //                 u.username,
        //                 COUNT(DISTINCT l.id) as likes_count,
        //                 COUNT(DISTINCT c.id) as comments_count
        //             FROM pictures p 
        //             JOIN users u ON p.user_id = u.id 
        //             LEFT JOIN likes l ON p.id = l.picture_id
        //             LEFT JOIN comments c ON p.id = c.picture_id
        //             GROUP BY p.id, p.user_id, p.image_url, p.description, p.created_at, u.username
        //             ORDER BY p.created_at DESC 
        //             LIMIT ? OFFSET ?";
        $query = "SELECT 
                    p.id,
                    p.user_id,
                    p.image_url,
                    p.description,
                    p.created_at,
                    u.username,
                    COUNT(DISTINCT l.id) as likes_count,
                    COUNT(DISTINCT c.id) as comments_count
                FROM pictures p 
                JOIN users u ON p.user_id = u.id 
                LEFT JOIN likes l ON p.id = l.picture_id
                LEFT JOIN comments c ON p.id = c.picture_id
                GROUP BY p.id, p.user_id, p.image_url, p.description, p.created_at, u.username
                ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($posts as &$post) {
            $post['id'] = intval($post['id']);
            $post['user_id'] = intval($post['user_id']);
            $post['likes_count'] = intval($post['likes_count']);
            $post['comments_count'] = intval($post['comments_count']);
            $post['created_at'] = date('Y-m-d H:i:s', strtotime($post['created_at']));
            // Check if the current user has liked this post
            $likeModel = new LikeModel();
            $post['user_has_liked'] = $likeModel->hasLiked($_REQUEST['user_id'], $post['id']);
        }

        $countStmt = $this->db->query("SELECT COUNT(*) FROM pictures");
        $totalPosts = intval($countStmt->fetchColumn());
        $totalPages = ceil($totalPosts / $limit);

        return [
            'posts' => $posts,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'limit' => $limit,
                'total_posts' => $totalPosts
            ]
        ];
    }

    public function getPostById($pictureId)
    {
        $stmt = $this->db->prepare("SELECT 
        p.id, 
        p.user_id, 
        p.image_url, 
        p.description, 
        p.created_at, 
        u.username, 
        COUNT(DISTINCT l.id) as likes_count, 
        COUNT(DISTINCT c.id) as comments_count 
    FROM pictures p 
    JOIN users u ON p.user_id = u.id 
    LEFT JOIN likes l ON p.id = l.picture_id 
    LEFT JOIN comments c ON p.id = c.picture_id 
    WHERE p.id = :pictureId 
    GROUP BY p.id");

        $stmt->bindParam(':pictureId', $pictureId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUsersWhoLikedPost($pictureId) {
        $stmt = $this->db->prepare("SELECT u.username FROM users u JOIN likes l ON u.id = l.user_id WHERE l.picture_id = :pictureId");
        $stmt->bindParam(':pictureId', $pictureId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
