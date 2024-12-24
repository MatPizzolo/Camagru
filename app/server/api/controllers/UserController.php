<?php

require_once __DIR__ . '/../models/User.php';
// require_once __DIR__ . '/../utils/JwtUtils.php';

class UserController
{
	// Get user info by user ID
	public function getUserInfoById()
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
			http_response_code(405); // Method Not Allowed
			echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
			return;
		}

		$userModel = new UserModel();
		$userId = $_REQUEST['user_id'];
		$user = $userModel->findById($userId);

		if (!$user) {
			http_response_code(404); // Not Found
			echo json_encode(['status' => 'error', 'message' => "User Not Found"]);
			return;
		}

		// Remove sensitive fields before returning the response
		unset($user['password']);

		http_response_code(200); // OK
		echo json_encode(['status' => 'ok', 'data' => $user]);
	}
}
?>