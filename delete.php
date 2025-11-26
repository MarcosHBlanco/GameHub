<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){ 
  http_response_code(405); exit('Method Not Allowed'); 
}
csrf_check();
$id=(int)($_POST['id']??0);
$stmt=db()->prepare("DELETE FROM games WHERE id=:id"); 
$stmt->execute([':id'=>$id]);
flash('Game deleted.'); header('Location: index.php');
