<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';
$pageTitle='Edit Game';
$id=(int)($_GET['id']??0);
$stmt=db()->prepare("SELECT * FROM games WHERE id=:id"); 
$stmt->execute([':id'=>$id]); 
$game=$stmt->fetch();
if(!$game){ 
  http_response_code(404); 
  exit('Not found'); 
}
$errors=[]; $form=$game;
if($_SERVER['REQUEST_METHOD']==='POST'){
  csrf_check(); 
  [$errors,$data]=validate_game($_POST);
  if(!$errors){
    $up=db()->prepare("UPDATE games SET title=:t, platform=:p, notes=:n, cover_url=:c, favorite=:f, played=:pl WHERE id=:id");
    $up->execute([':t'=>$data['title'],':p'=>$data['platform'],':n'=>$data['notes'],':c'=>$data['cover_url'],':f'=>$data['favorite'],':pl'=>$data['played'],':id'=>$id]);
    flash('Game updated!'); 
    header('Location: index.php'); 
    exit;
  } else { 
      $form=array_merge($form,$data); 
    }
}
include __DIR__ . '/inc/header.php';
?>
<section class="card"><h2>Edit Game</h2>
<form method="post" id="gameForm" novalidate>
  <?= csrf_field() ?>
  <label>Title * <?php if(isset($errors['title'])): ?><span class="err"><?= e($errors['title']) ?></span><?php endif; ?>
    <input name="title" maxlength="120" required value="<?= e($form['title']) ?>">
  </label>
  <label>Platform * <?php if(isset($errors['platform'])): ?><span class="err"><?= e($errors['platform']) ?></span><?php endif; ?>
    <input name="platform" maxlength="40" required value="<?= e($form['platform']) ?>">
  </label>
  <label>Cover URL <?php if(isset($errors['cover_url'])): ?><span class="err"><?= e($errors['cover_url']) ?></span><?php endif; ?>
    <input name="cover_url" type="url" value="<?= e($form['cover_url']) ?>">
  </label>
  <label>Notes
    <textarea name="notes" rows="3"><?= e($form['notes']) ?></textarea>
  </label>
  <fieldset class="inline"><legend class="sr-only">Flags</legend>
    <label><input type="checkbox" name="favorite" <?= $form['favorite']?'checked':'' ?>> Favorite</label>
    <label><input type="checkbox" name="played" <?= $form['played']?'checked':'' ?>> Played</label>
  </fieldset>
  <button type="submit">Update</button>
</form>
</section>
<?php include __DIR__ . '/inc/footer.php'; ?>
