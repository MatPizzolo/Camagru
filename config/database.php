<?php
// /config/database.php
class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection == null) {
            $host = 'localhost';
            $dbname = 'webapp';
            $username = 'root';
            $password = 'mateo';
            self::$connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        }
        return self::$connection;
    }
}
?>

