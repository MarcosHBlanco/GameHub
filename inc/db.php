<?php
require_once __DIR__ . '/../config.php';

function db(): PDO {
    static $pdo;
    if ($pdo) {
        return $pdo;
    }

    $drivers = PDO::getAvailableDrivers();
    $useSqlite = false;

    // 1) Railway or local MySQL, only if PDO MySQL driver exists
    if (in_array('mysql', $drivers, true) && getenv('MYSQLHOST')) {
        // Railway MySQL (CURRENTLY NOT USED on Railway because mysql driver is missing)
        $host = getenv('MYSQLHOST');
        $port = getenv('MYSQLPORT') ?: '3306';
        $db   = getenv('MYSQLDATABASE');
        $user = getenv('MYSQLUSER');
        $pass = getenv('MYSQLPASSWORD');

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    } elseif (in_array('mysql', $drivers, true)) {
        // Local XAMPP MySQL
        $host = '127.0.0.1';
        $port = '3307';
        $db   = 'gamehub';
        $user = 'root';
        $pass = '';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    } else {
        // 2) Fallback: SQLite (this is what Railway is using right now)
        $useSqlite = true;
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

    // If we are using SQLite (Railway), ensure the games table exists
    if ($useSqlite) {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS games (
                id         INTEGER PRIMARY KEY AUTOINCREMENT,
                title      TEXT NOT NULL,
                platform   TEXT NOT NULL,
                notes      TEXT,
                cover_url  TEXT,
                favorite   INTEGER NOT NULL DEFAULT 0,
                played     INTEGER NOT NULL DEFAULT 0,
                created_at TEXT NOT NULL DEFAULT (datetime('now'))
            )
        ");
    }

    return $pdo;
}
