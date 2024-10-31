<?php

// config/database.php
class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            $host = '127.0.0.1';
            $dbname = 'webapp';
            $username = 'root';
            $password = 'mateo';
            try {
                self::$connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    public static function isConnected() {
        try {
            self::getConnection()->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>

