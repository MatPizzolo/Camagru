<?php
// /app/models/User.php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection(); // use a singleton connection
    }

    public function register($username, $email, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            return $stmt->execute([$username, $email, $hashedPassword]);
        } catch (PDOException $e) {
            error_log("Registration Error: " . $e->getMessage());
            return false;
        }
    }
    
    public function isUsernameTaken($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public function isEmailTaken($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }    

}
?>
