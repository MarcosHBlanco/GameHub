<?php
// inc/header.php
$pageTitle = $pageTitle ?? 'Game Hub (PHP/MySQL)';
require_once __DIR__ . '/functions.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($pageTitle) ?></title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script defer src="assets/js/main.js"></script>
</head>
<body>
<header class="site-header">
  <div class="container">
    <a class="logo" href="index.php">🎮 Game Hub</a>
    <button class="menu-toggle" aria-expanded="false" aria-controls="mainnav">Menu</button>
    <nav id="mainnav" aria-label="Primary">
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="add.php">Add Game</a></li>
        <li><a href="docs.php">Documentation</a></li>
        <li><a href="sources.php">Sources</a></li>
        <li><a href="toprated.php">Top Rated</a></li>
        <li><a href="mygames.php">My Games</a></li>
      </ul>
    </nav>
  </div>
</header>
<main class="container fade-in">
<?php if (function_exists('flash') && ($f = flash())): ?>
  <div class="alert" role="status" aria-live="polite"><?= e($f) ?></div>
<?php endif; ?>
