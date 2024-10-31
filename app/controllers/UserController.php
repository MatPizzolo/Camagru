<?php
// /app/controllers/UserController.php
require_once __DIR__ . '/../models/User.php';

class UserController {
    public function showRegisterForm() {
        require_once __DIR__ . '/../views/register.php';
    }
	public function showLoginForm() {
        require_once __DIR__ . '/../views/login.php';
    }

	public function register() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$userModel = new User();
			
			// Fetch inputs
			$username = $_POST['username'] ?? null;
			$email = $_POST['email'] ?? null;
			$password = $_POST['password'] ?? null;

			
			if (empty($username) || empty($email) || empty($password)) {
				echo "All fields are required.";
				return;
			}
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				echo "Invalid email format.";
				return;
			}
	
			if (strlen($password) < 6) {
				echo "Password must be at least 6 characters.";
			}
			
			if ($userModel->isUsernameTaken($username)) {
				echo "Username is already taken. Please choose another one.";
				return;
			} else if ($userModel->isEmailTaken($email)) {
				echo "Email is already taken. Please choose another one.";
				return;
			}
			
			$userModel->register($username, $email, $password);
			echo "REGISTERR";
			header('Location: /');
			exit();
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
