<?php
require_once __DIR__ . '/../config.php';

function db(): PDO {
    static $pdo;

    if ($pdo) {
        return $pdo;
    }

    // When running on Railway, these will exist
    $host = getenv('MYSQLHOST');
    $port = getenv('MYSQLPORT');
    $db   = getenv('MYSQLDATABASE');
    $user = getenv('MYSQLUSER');
    $pass = getenv('MYSQLPASSWORD');

    // When running locally (XAMPP), Railway vars won't exist
    if (!$host) {
        $host = '127.0.0.1';
        $port = '3307';     // your XAMPP MySQL port
        $db   = 'gamehub';  // your local DB name
        $user = 'root';
        $pass = '';
    }

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    return $pdo;
}
