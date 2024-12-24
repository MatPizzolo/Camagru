<?php
// /app/models/User.php

class UserModel {
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

    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user !== false ? $user : null;
    }

    public function findByUsername($username)
    {
        $stmt = $this->db->prepare('SELECT id, username, email FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($userId)
    {
        $stmt = $this->db->prepare('SELECT id, username, email FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
