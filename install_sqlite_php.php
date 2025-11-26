<?php
require_once __DIR__ . '/inc/db.php';

$pdo = db();

// Create table if it doesn't exist
$sql = <<<SQL
CREATE TABLE IF NOT EXISTS games (
    id         INTEGER PRIMARY KEY AUTOINCREMENT,
    title      TEXT NOT NULL,
    platform   TEXT NOT NULL,
    notes      TEXT,
    cover_url  TEXT,
    favorite   INTEGER NOT NULL DEFAULT 0,
    played     INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT (datetime('now'))
);
SQL;

$pdo->exec($sql);

echo "SQLite games table ready.\n";
