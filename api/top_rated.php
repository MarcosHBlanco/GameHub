<?php

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

$key = getenv('RAWG_API_KEY');

if ($key === '') {
  echo json_encode(['results' => [], 'error' => 'Missing RAWG_API_KEY']);
  exit;
}

$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$size = isset($_GET['page_size']) ? max(1, min(40, (int)$_GET['page_size'])) : 27;

$url = 'https://api.rawg.io/api/games?' . http_build_query([
  'key'        => $key,
  'page'       => $page,
  'page_size'  => $size,
  'ordering'   => '-metacritic',
  'metacritic' => '94,100',
]);

$json = @file_get_contents($url);
if ($json === false) {
  echo json_encode(['results' => [], 'error' => 'Upstream error']);
  exit;
}
echo $json;
