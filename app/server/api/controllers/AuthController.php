<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/jwtUtils.php';


class AuthController
{
	public function login($request)
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405); // Method Not Allowed
			echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
			return;
		}

		// Merge $_POST with JSON input
		$input = json_decode(file_get_contents('php://input'), true);
		$request = array_merge($request, $input ?? []);

		$email = $request['email'] ?? null;
		$password = $request['password'] ?? null;

		if (empty($email) || empty($password)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Email and password are required.']);
			return;
		}

		$userModel = new UserModel();
		$user = $userModel->findByEmail($email);

		if (!$user || !password_verify($password, $user['password'])) {
			http_response_code(400); // Unauthorized
			echo json_encode(['status' => 'error', 'message' => 'Invalid credentials.']);
			return;
		}

		$token = JwtUtils::generateToken($user['id'], $user['username']);

		http_response_code(200);
		echo json_encode(['status' => 'ok', 'data' => ['token' => $token]]);
	}

	public function register($request)
	{
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			http_response_code(405); // Method Not Allowed
			echo json_encode(['status' => 'error', 'message' => 'Method not allowed.']);
			return;
		}

		// Merge $_POST with JSON input
		$input = json_decode(file_get_contents('php://input'), true);
		$request = array_merge($request, $input ?? []);

		$username = $request['username'] ?? null;
		$email = $request['email'] ?? null;
		$password = $request['password'] ?? null;

		if (empty($username) || empty($email) || empty($password)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
			return;
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
			return;
		}

		if (strlen($password) < 6) {
			http_response_code(400); // Bad Request
			echo json_encode(['status' => 'error', 'message' => 'Password must be at least 6 characters.']);
			return;
		}

		$userModel = new UserModel();
		if ($userModel->isUsernameTaken($username)) {
			http_response_code(409); // Conflict
			echo json_encode(['status' => 'error', 'message' => 'Username is already taken.']);
			return;
		}

		if ($userModel->isEmailTaken($email)) {
			http_response_code(409); // Conflict
			echo json_encode(['status' => 'error', 'message' => 'Email is already taken.']);
			return;
		}

		$userModel->register($username, $email, $password);
		$user = $userModel->findByEmail($email);
		$token = JwtUtils::generateToken($user['id'], $user['username']);

		http_response_code(200);
		echo json_encode(['status' => 'ok', 'data' => ['token' => $token]]);
	}
}
