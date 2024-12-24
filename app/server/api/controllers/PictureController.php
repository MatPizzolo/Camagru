<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Picture.php';
require_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../utils/jwtUtils.php';

class PictureController
{
    // Upload a picture
    public function upload($request)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
            return;
        }

        // Merge $_POST with JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $request = array_merge($request, $input ?? []);

        // Proceed with upload logic
        $description = $request['description'] ?? '';

        if (!isset($_FILES['image'])) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'No image file uploaded.']);
            return;
        }

        $image = $_FILES['image'];
        $targetDir = __DIR__ . '/../uploads/';
        $targetFile = $targetDir . basename($image['name']);

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload the image.']);
            return;
        }

        $imageUrl = '/uploads/' . basename($image['name']);

        // Save the picture information in the database
        $pictureModel = new PictureModel();
		$userId = $_REQUEST['user_id'];
        $pictureModel->savePicture($userId, $imageUrl, $description);

        http_response_code(201); // Created
        echo json_encode(['status' => 'ok', 'message' => 'Picture uploaded successfully.']);
    }

	// Like a picture
	public function like($request)
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405); // Method Not Allowed
			echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
			return;
		}

		// Merge $_POST with JSON input
		$input = json_decode(file_get_contents('php://input'), true);
		$request = array_merge($request, $input ?? []);

		$pictureId = $request['picture_id'] ?? null;

		if (empty($pictureId)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'User ID and Picture ID are required.']);
			return;
		}

		$likeModel = new LikeModel();
		$userId = $_REQUEST['user_id'];
		if (!$likeModel->addLike($userId, $pictureId)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Failed to like the picture.']);
			return;
		}

		http_response_code(201); // Created
		echo json_encode(['status' => 'ok', 'message' => 'Picture liked successfully.']);
	}

	// Add a comment to a picture
	public function comment($request)
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405); // Method Not Allowed
			echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
			return;
		}

		// Merge $_POST with JSON input
		$input = json_decode(file_get_contents('php://input'), true);
		$request = array_merge($request, $input ?? []);

		$pictureId = $request['picture_id'] ?? null;
		$commentText = $request['comment'] ?? null;

		if (empty($pictureId) || empty($commentText)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'User ID, Picture ID, and Comment are required.']);
			return;
		}

		$commentModel = new CommentModel();
		$userId = $_REQUEST['user_id'];
		if (!$commentModel->addComment($userId, $pictureId, $commentText)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Failed to add the comment.']);
			return;
		}

		http_response_code(201); // Created
		echo json_encode(['status' => 'ok', 'message' => 'Comment added successfully.']);
	}
}
