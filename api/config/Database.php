<?php
namespace Config;

require_once __DIR__ . '/../../vendor/autoload.php'; 

use Dotenv\Dotenv;
use PDO;
use PDOException;

class Database {
    private static ?PDO $conn = null;

    private function __construct() {}

    public static function getConnection(): ?PDO {
        if (self::$conn === null) {
          
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->safeLoad();

            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $dbName = $_ENV['DB_NAME'] ?? 'student_grades';
            $username = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASS'] ?? 'royadrian07';

            try {
                self::$conn = new PDO(
                    "mysql:host=$host;dbname=$dbName;charset=utf8",
                    $username,
                    $password,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $exception) {
                error_log("Database Connection Error: " . $exception->getMessage());
                die("Database connection failed. Contact administrator.");
            }
        }
        return self::$conn;
    }
}
