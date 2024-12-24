<?php

class PictureModel
{
	private $db;

    public function __construct() {
        $this->db = Database::getConnection(); // use a singleton connection
    }

    public function savePicture($userId, $imageUrl, $description)
    {
        $query = "INSERT INTO pictures (user_id, image_url, description) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId, $imageUrl, $description]);
    }
}
