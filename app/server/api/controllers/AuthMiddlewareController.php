<?

require_once __DIR__ . '/../utils/jwtUtils.php';

class AuthMiddleware
{
	public function handle()
	{
		$headers = apache_request_headers();

		if (isset($headers['Authorization'])) {
			$authHeader = $headers['Authorization'];

			if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
				http_response_code(401); // Unauthorized
				echo json_encode(['status' => 'error', 'message' => 'Authorization token missing or invalid.']);
				return;
			}

			$token = str_replace('Bearer ', '', $authHeader);
			if (!JwtUtils::validateToken($token)) {
				http_response_code(401); // Unauthorized
				echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
				return;
			}

			$userId = JwtUtils::getUserIdFromToken($token); // Extract user ID from token
			if (!$userId) {
				http_response_code(401); // Unauthorized
				echo json_encode(['status' => 'error', 'message' => 'Unable to extract user from token.']);
				return;
			}

			$userModel = new UserModel();
			$user = $userModel->findById($userId);
			if (!$user) {
				http_response_code(401);
				echo json_encode(['status' => 'error', 'message' => "User Not Found"]);
				return;
			}
		} else {
			http_response_code(401); // Unauthorized
			echo json_encode(['status' => 'error', 'message' => 'Authorization header not found.']);
			return;
		}
	
		$_REQUEST['user_id'] = $userId;

		return true;
	}
}
