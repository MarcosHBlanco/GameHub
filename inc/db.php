<?php
require_once __DIR__ . '/../config.php';

function db(): PDO {
    static $pdo;
    if ($pdo) {
        return $pdo;
    }

    $drivers = PDO::getAvailableDrivers();

    // --- 1) Railway MySQL: only if PDO MySQL driver actually exists ---
    if (in_array('mysql', $drivers, true) && getenv('MYSQLHOST')) {
        $host = getenv('MYSQLHOST');
        $port = getenv('MYSQLPORT') ?: '3306';
        $db   = getenv('MYSQLDATABASE');
        $user = getenv('MYSQLUSER');
        $pass = getenv('MYSQLPASSWORD');

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    }
    // --- 2) Local XAMPP MySQL (what you already use) ---
    elseif (in_array('mysql', $drivers, true)) {
        $host = '127.0.0.1';
        $port = '3307';      // your local port
        $db   = 'gamehub';   // your local DB name
        $user = 'root';
        $pass = '';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    }
    // --- 3) Fallback: SQLite (this will happen on Railway right now) ---
    else {
        // DB file will live at /app/data/gamehub.sqlite inside Railway
        $path = __DIR__ . '/../data/gamehub.sqlite';

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $dsn  = 'sqlite:' . $path;
        $user = null;
        $pass = null;
    }

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die('Database connection failed: ' . $e->getMessage());
    }

    return $pdo;
}
