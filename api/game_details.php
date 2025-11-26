<?php 

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');
// read key from .env
$key = getenv('RAWG_API_KEY');

$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if($key === ''|| $id === ''){
  echo json_encode(['error' => 'Missing key or id']);
  exit;
}

$url = 'https://api.rawg.io/api/games/' . rawurlencode($id) . '?key=' . urlencode($key);
$json = @file_get_contents($url);
echo $json !== false ? $json : json_encode(['error' => 'Upstream error']);
