<?php
// /app/controllers/UserController.php
require_once __DIR__ . '/../models/User.php';

class UserController {
    public function showRegisterForm() {
        require_once __DIR__ . '/../views/register.php';
    }

    public function register() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$userModel = new User();
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			if ($userModel->isUsernameTaken($username)) {
				echo "Username is already taken. Please choose another one.";
				// header('Location: /');
			} else {
				$userModel->register($username, $password);
				header('Location: /');
				exit();
			}
		}
    }

	public function login() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$username = $_POST['username'];
			$password = $_POST['password'];
		
			// Check if the user exists in the database
			$sql = "SELECT * FROM users WHERE username = :username";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['username' => $username]);
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
		
			// If user exists and password is correct
			if ($user && password_verify($password, $user['password'])) {
				// Set session variables to mark the user as logged in
				$_SESSION['user_id'] = $user['id'];  // Store user ID in session
				$_SESSION['username'] = $user['username'];
				header('Location: /views/index.php');  // Redirect to some dashboard or home page
				exit;
			} else {
				echo "Invalid username or password";
			}
		}
		
	}
}
?>
