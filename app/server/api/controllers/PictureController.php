<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Picture.php';
require_once __DIR__ . '/../models/Like.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../utils/jwtUtils.php';

define('UPLOAD_DIR', realpath(__DIR__ . '/../uploads/'));

class PictureController
{
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
		if (empty($description)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'No image description uploaded.']);
			return;
		}

		if (!isset($_FILES['image'])) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'No image file uploaded.']);
			return;
		}

		$image = $_FILES['image'];
		$targetDir = __DIR__ . '/../uploads/';
		$targetFile = $targetDir . basename($image['name']);

		if (!is_dir($targetDir)) {
			http_response_code(500);
			echo json_encode([
				'status' => 'error',
				'message' => 'Upload directory is not accesible'
			]);
			return;
		}

		if (!is_writable($targetDir)) {
			http_response_code(500);
			echo json_encode([
				'status' => 'error',
				'message' => 'Upload directory is not writable.',
			]);
			return;
		}

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

		http_response_code(200); // Created
		echo json_encode(['status' => 'ok', 'message' => 'Picture uploaded successfully.']);
	}


	public function showPosts($request)
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			http_response_code(405);
			echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
			return;
		}

		$page = 1;
		$limit = 10;

		// Validate field types
		if (!isset($page) || !isset($limit)) {
			http_response_code(400);
			echo json_encode([
				'status' => 'error',
				'message' => '"page" and "limit" must be in body'
			]);
			return;
		}

		// Validate field types
		if (!is_numeric($page) || !is_numeric($limit)) {
			http_response_code(400);
			echo json_encode([
				'status' => 'error',
				'message' => '"page" and "limit" must be numeric values'
			]);
			return;
		}

		$pictureModel = new PictureModel();
		$result = $pictureModel->getPosts(
			$page,
			$limit
		);

		http_response_code(200);
		echo json_encode([
			'status' => 'ok',
			'data' => $result
		]);
	}

	public function showPost($request)
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			http_response_code(405);
			echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
			return;
		}

		$input = json_decode(file_get_contents('php://input'), true);
		$request = array_merge($request, $input ?? []);

		$pictureId = $request['picture_id'] ?? null;
		if (empty($pictureId)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Picture ID are required.']);
			return;
		}

		$pictureModel = new PictureModel();
		$post = $pictureModel->getPostById($pictureId);

		if (!$post) {
			http_response_code(404);
			echo json_encode(['status' => 'error', 'message' => 'Post not found']);
			return;
		}

		http_response_code(200);
		echo json_encode([
			'status' => 'ok',
			'data' => $post
		]);
	}

	public function getUsersWhoLikedPost($request) {
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405);
			echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
			return;
		}

		$input = json_decode(file_get_contents('php://input'), true);
		$request = array_merge($request, $input ?? []);

		$pictureId = $request['picture_id'] ?? null;
		if (empty($pictureId)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Picture ID are required.']);
			return;
		}
	
		$pictureModel = new PictureModel();
		$users = $pictureModel->getUsersWhoLikedPost($pictureId);
	
		if (!$users) {
			http_response_code(404);
			echo json_encode(['status' => 'error', 'message' => 'No users found who liked this post']);
			return;
		}
	
		http_response_code(200);
		echo json_encode([
			'status' => 'ok',
			'data' => $users
		]);
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

		if ($likeModel->hasLiked($userId, $pictureId)) {
			http_response_code(409); // Conflict
			echo json_encode(['status' => 'error', 'message' => 'User  has already liked this picture.']);
			return;
		}

		if (!$likeModel->addLike($userId, $pictureId)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Failed to like the picture.']);
			return;
		}

		$likeCount = $likeModel->likeCount($pictureId);
		http_response_code(200); // Created
		echo json_encode(['status' => 'ok', 'data' => ['likes' => $likeCount]]);
	}

	// Like a picture
	public function unlike($request)
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

		if (!$likeModel->hasLiked($userId, $pictureId)) {
			http_response_code(200); // Conflict
			echo json_encode(['status' => 'okk', 'message' => 'User  has NOT liked this picture.']);
			return;
		}

		if (!$likeModel->removeLike($userId, $pictureId)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Failed to like the picture.']);
			return;
		}

		$likeCount = $likeModel->likeCount($pictureId);
		http_response_code(200); // Created
		echo json_encode(['status' => 'ok', 'data' => ['likes' => $likeCount]]);
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
