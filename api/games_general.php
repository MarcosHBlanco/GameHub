<?php
// api/games.php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

$key = defined('RAWG_API_KEY') ? RAWG_API_KEY : '';

if ($key === '') {
  echo json_encode(['results' => [], 'error' => 'Missing RAWG_API_KEY']);
  exit;
}

$page     = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pageSize = isset($_GET['page_size']) ? (int)$_GET['page_size'] : 20;
$ordering = isset($_GET['ordering']) ? $_GET['ordering'] : '-rating';
$search   = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($page < 1) $page = 1;
if ($pageSize < 1 || $pageSize > 40) $pageSize = 20;

$baseUrl = 'https://api.rawg.io/api/games';
$query = [
  'key'       => $key,
  'page'      => $page,
  'page_size' => $pageSize,
  'ordering'  => $ordering,
];
if ($search !== '') {
  $query['search'] = $search;
}

$url = $baseUrl . '?' . http_build_query($query);

$rawJson = @file_get_contents($url);

if ($rawJson === false) {
  http_response_code(500);
  echo json_encode(['error' => 'Failed to fetch from RAWG']);
  exit;
}

echo $rawJson;
