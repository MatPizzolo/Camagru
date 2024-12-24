<?php

class CommentModel
{
	private $db;

    public function __construct() {
        $this->db = Database::getConnection(); // use a singleton connection
    }

    public function addComment($userId, $pictureId, $commentText)
    {
        $query = "INSERT INTO comments (user_id, picture_id, comment) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId, $pictureId, $commentText]);
    }
}
