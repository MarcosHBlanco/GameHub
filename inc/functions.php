<?php

if (session_status()===PHP_SESSION_NONE) session_start();
function e(string $s): string { 
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); 
}

function csrf_token(): string { 
  if(empty($_SESSION[CSRF_KEY])){
    $_SESSION[CSRF_KEY]=bin2hex(random_bytes(16)); 
  }
  return $_SESSION[CSRF_KEY]; 
} 

function csrf_field(): string { 
  return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">'; 
}

function csrf_check(): void { 
  if(!(isset($_POST['csrf']) && hash_equals($_SESSION[CSRF_KEY] ?? '', $_POST['csrf']))){
    http_response_code(400); 
    exit('Bad CSRF'); 
  } 
}

function validate_game(array $d): array {
  $errors=[];
  $title=trim($d['title']??''); 
  $platform=trim($d['platform']??''); 
  $cover=trim($d['cover_url']??'');
  if($title===''||mb_strlen($title)>120) {
    $errors['title']='Title is required (max 120).';
  }
  if($platform===''||mb_strlen($platform)>40) {
    $errors['platform']='Platform is required (max 40).';
  }
  if($cover!=='' && !filter_var($cover,FILTER_VALIDATE_URL)) {
    $errors['cover_url']='Cover must be a URL.';
  }
  return [$errors,[
    'title'=>$title,'platform'=>$platform,'notes'=>trim($d['notes']??''),'cover_url'=>$cover,
    'favorite'=>isset($d['favorite'])?1:0,'played'=>isset($d['played'])?1:0
  ]];
}
function flash(?string $msg=null){ 
  if($msg!==null){ 
    $_SESSION['flash']=$msg;
    return null; 
  } 
  $m=$_SESSION['flash']??null;
  unset($_SESSION['flash']); 
  return $m; 
}
