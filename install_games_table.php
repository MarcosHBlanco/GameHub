<?php
// install_games_table.php
// Run this ONCE on Railway to create + fill the `games` table.

require __DIR__ . '/inc/db.php';

$pdo = db();

try {
    // 1) Drop any existing table
    $pdo->exec("DROP TABLE IF EXISTS games");

    // 2) Create table
    $createSql = "
        CREATE TABLE games (
          id int(11) NOT NULL AUTO_INCREMENT,
          title varchar(120) NOT NULL,
          platform varchar(40) NOT NULL,
          notes text DEFAULT NULL,
          cover_url varchar(255) DEFAULT NULL,
          favorite tinyint(1) NOT NULL DEFAULT 0,
          played tinyint(1) NOT NULL DEFAULT 0,
          created_at datetime NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($createSql);

    // 3) Insert your rows
    $insertSql = "
        INSERT INTO games (id, title, platform, notes, cover_url, favorite, played, created_at) VALUES
        (5, 'Donkey Kong Country: Tropical Freeze', 'Switch', '', 'https://media.rawg.io/media/games/c31/c31655ab2640da333f5ca3f223d33a12.jpg', 1, 0, '2025-10-26 13:01:27'),
        (8, 'Super Mario 3D World + Bowser’s Fury', 'Switch', '', 'https://media.rawg.io/media/games/cd2/cd22f0dcf8f080086c60f77eed7a8a93.jpg', 1, 1, '2025-10-26 15:22:28'),
        (9, 'Super Kirby Clash', 'Switch', 'Really want to play', 'https://media.rawg.io/media/games/ca3/ca39868e1e0ac997a96c58a628177183.jpg', 1, 0, '2025-10-26 20:24:05'),
        (11, 'Grim Dawn', 'PC, Xbox One', '', 'https://media.rawg.io/media/games/920/92039cd19460532b76f6244b2bb3e4ac.jpg', 1, 1, '2025-10-27 16:58:09'),
        (12, 'Super Mario Bros.', 'Nintendo Switch, Nintendo 3DS', '', 'https://media.rawg.io/media/games/154/154fea9689109f26c49c6a2db6263ef9.jpg', 1, 1, '2025-11-10 15:05:15'),
        (13, 'The Legend of Zelda: Ocarina of Time', 'Nintendo Switch, Nintendo 64', '', 'https://media.rawg.io/media/games/3a0/3a0c8e9ed3a711c542218831b893a0fa.jpg', 0, 0, '2025-11-10 15:06:51'),
        (14, 'Dark Souls: Remastered', 'PC, Xbox One', '', 'https://media.rawg.io/media/games/29c/29c6c21cc0c78cff6f45d23631cc82f4.jpg', 1, 1, '2025-11-10 15:48:13'),
        (15, 'Mario Kart World', 'Nintendo Switch', 'Loved it', 'https://media.rawg.io/media/games/1b8/1b8e007c36040ae1f5762a62ba2faeab.jpg', 1, 1, '2025-11-11 00:27:58'),
        (16, 'Dark Cloud 2', 'PlayStation 4, PlayStation 2', 'haven\'t played yet', 'https://media.rawg.io/media/screenshots/e3e/e3ed9222eb6f4e95a04c9f0f8e5ff3fd.jpg', 0, 0, '2025-11-17 14:49:29')
    ";
    $pdo->exec($insertSql);

    echo "<h1>✅ games table created and data inserted successfully.</h1>";
} catch (PDOException $e) {
    echo "<h1>❌ Error</h1><pre>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</pre>";
}
