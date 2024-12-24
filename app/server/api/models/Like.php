<?php

class LikeModel
{
	private $db;

    public function __construct() {
        $this->db = Database::getConnection(); // use a singleton connection
    }

	public function addLike($userId, $pictureId)
	{
		$query = "INSERT INTO likes (user_id, picture_id) VALUES (?, ?)";
		$stmt = $this->db->prepare($query);
		return $stmt->execute([$userId, $pictureId]);
	}
}
