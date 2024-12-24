<?php
// /app/server/controllers/DefaultController.php

class DefaultController
{
	public function home()
	{
		http_response_code(200);
		echo json_encode([
			'status' => 'success',
			'message' => 'OK'
		]);
	}

	public function apiStatus()
	{
		http_response_code(200);
		echo json_encode([
			'status' => 'success',
			'message' => 'API is up and running!'
		]);
	}

	public function testJwt($request)
	{
		// Merge JSON input with the request if necessary
		$input = json_decode(file_get_contents('php://input'), true);
		$request = array_merge($request, $input ?? []);

		// Check if the token is provided in the body
		$token = $request['token'] ?? null;

		if (empty($token)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Authorization token missing.']);
			return;
		}

		// Validate the token and extract the payload
		$payload = JwtUtils::validateToken($token);

		if ($payload === false) {
			http_response_code(400); // Unauthorized
			echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']);
			return;
		}

		// Extract user data from the token payload
		$userId = $payload['user_id'];
		$username = $payload['username'];

		// Respond with user data
		http_response_code(200); // OK
		echo json_encode([
			'status' => 'ok',
			'data' => [
				'userId' => $userId,
				'username' => $username,
			]
		]);
	}
}
?>