<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';

$pageTitle='Home';
$q=trim($_GET['q']??''); 
$platform=trim($_GET['platform']??'');
$sql="SELECT * FROM games WHERE 1"; 
$params=[];
if($q!==''){ 
  $sql.=" AND (title LIKE :q OR notes LIKE :q)"; 
  $params[':q']="%$q%"; 
}
if($platform !==''){ 
  $sql.=" AND platform = :p"; 
  $params[':p']=$platform; 
}
$sql.=" ORDER BY created_at DESC";
$stmt=db()->prepare($sql); 
$stmt->execute($params); 
$games=$stmt->fetchAll();
$ps=db()->query("SELECT DISTINCT platform FROM games ORDER BY platform")->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/inc/header.php';
?>
<section class="toolbar">
  <form class="filter-row" method="get" action="mygames.php">
    <label class="sr-only" for="q">Search</label>
    <input id="q" name="q" placeholder="Search title or notes" value="<?= e($q) ?>"/>
    <label class="sr-only" for="platform">Platform</label>
    <select id="platform" name="platform"><option value="">All platforms</option>
      <?php foreach($ps as $p): ?><option value="<?= e($p) ?>" <?= $p===$platform?'selected':'' ?>><?= e($p) ?></option><?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
    <?php if($q!==''||$platform!==''): ?><a class="link" href="index.php">Clear</a><?php endif; ?>
  </form>
</section>
<section>
<?php if(!$games): ?><p class="muted">No games yet. <a href="add.php">Add one</a>.</p>
<?php else: ?><ul class="grid">
<?php foreach($games as $g): ?><li class="card game-card">
  <div class="cover-wrap">
    <?php if($g['cover_url']): ?><img src="<?= e($g['cover_url']) ?>" alt="Cover for <?= e($g['title']) ?>" loading="lazy">
    <?php else: ?><div class="cover-placeholder" aria-hidden="true">No Cover</div><?php endif; ?>
  </div>
  <div class="card-body">
    <h3><?= e($g['title']) ?></h3>
    <p class="muted">Platform: <?= e($g['platform']) ?></p>
    <?php if($g['notes']): ?><p><?= e($g['notes']) ?></p><?php endif; ?>
    <div class="actions">
      <a class="btn" href="edit.php?id=<?= e($g['id']) ?>">Edit</a>
      <form method="post" action="delete.php" onsubmit="return confirm('Delete this game?');">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= e($g['id']) ?>"><button class="btn danger" type="submit">Delete</button>
      </form>
    </div>
    <div class="flags">
      <?php if($g['favorite']): ?><span class="badge">★ Favorite</span><?php endif; ?>
      <?php if($g['played']): ?><span class="badge">✔ Played</span><?php endif; ?>
    </div>
  </div>
</li><?php endforeach; ?>
</ul><?php endif; ?>
</section>
<?php include __DIR__ . '/inc/footer.php'; ?>
