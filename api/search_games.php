<?php 
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json; charset=utf-8');

// Check for user query (?q=...)
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($q === '') {
  echo json_encode(['results' => []]);
  exit;
}

$key = getenv('RAWG_API_KEY');
if ($key === '' || $key === false) {
  echo json_encode([
    'results' => [],
    'error'   => 'Missing RAWG_API_KEY in environment'
  ]);
  exit;
}

// Build RAWG URL with search_precise=true
$params = [
  'key'            => $key,
  'search'         => $q,
  'page_size'      => 20,
  'search_precise' => true,  
];

$url = 'https://api.rawg.io/api/games?' . http_build_query($params);

$options = [
  'http' => [
    'method'  => 'GET',
    'timeout' => 5,
    'header'  => "User-Agent: cpsc2030-simple\r\n",
  ],
];

$context = stream_context_create($options);
$json    = @file_get_contents($url, false, $context);

if ($json === false) {
  echo json_encode(['results' => []]);
  exit;
}

$data = json_decode($json, true);
$out  = [];

if (!empty($data['results']) && is_array($data['results'])) {
  foreach ($data['results'] as $game) {
    $title = $game['name'] ?? '';

    // collect platform names
    $platform = null;
    if (!empty($game['platforms']) && is_array($game['platforms'])) {
      $names = [];
      foreach ($game['platforms'] as $p) {
        if (!empty($p['platform']['name'])) {
          $names[] = $p['platform']['name'];
        }
      }
      if ($names) {
        $platform = implode(', ', array_slice($names, 0, 2));
      }
    }

    $cover = $game['background_image'] ?? null;

    if ($title !== '') {
      $out[] = [
        'title'     => $title,
        'platform'  => $platform,
        'cover_url' => $cover,
      ];
    }
  }
}

echo json_encode(['results' => $out], JSON_UNESCAPED_SLASHES);
