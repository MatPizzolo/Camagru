<?php

class LikeModel
{
	private $db;

	public function __construct()
	{
		$this->db = Database::getConnection(); // use a singleton connection
	}

	public function addLike($userId, $pictureId)
	{
		$query = "INSERT INTO likes (user_id, picture_id) VALUES (?, ?)";
		$stmt = $this->db->prepare($query);
		return $stmt->execute([$userId, $pictureId]);
	}

	public function removeLike($userId, $pictureId)
	{
		$query = "DELETE FROM likes WHERE user_id = ? AND picture_id = ?";
		$stmt = $this->db->prepare($query);
		return $stmt->execute([$userId, $pictureId]);
	}

	public function hasLiked($userId, $pictureId)
	{
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE user_id = ? AND picture_id = ?");
		$stmt->execute([$userId, $pictureId]);
		$count = $stmt->fetchColumn();
		return $count > 0;
	}

	public function likeCount($pictureId)
	{
		$stmt = $this->db->prepare("SELECT COUNT(*) FROM likes WHERE picture_id = ?");
		$stmt->execute([$pictureId]);
		$count = $stmt->fetchColumn();
		return $count;
	}
}
