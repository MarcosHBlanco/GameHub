<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';

$pageTitle='Add Game'; 
$errors=[]; 
$form=['title'=>'','platform'=>'','notes'=>'','cover_url'=>'','favorite'=>0,'played'=>0];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_check(); 
  [$errors, $form] = validate_game($_POST);
  if (!$errors) {
    $stmt = db()->prepare(
      "INSERT INTO games(title, platform, notes, cover_url, favorite, played)
       VALUES(:t, :p, :n, :c, :f, :pl)"
    );
    $stmt->execute([
      ':t'  => $form['title'],
      ':p'  => $form['platform'],
      ':n'  => $form['notes'],
      ':c'  => $form['cover_url'],
      ':f'  => $form['favorite'],
      ':pl' => $form['played'],
    ]);
    flash('Game added!'); 
    header('Location: index.php'); 
    exit;
  }
}

include __DIR__ . '/inc/header.php';
?>
<section class="card"><h2>Add Game</h2>
<form method="post" id="gameForm" novalidate>
  <?= csrf_field() ?>
  <label>Search game (API)
    <input id="gameSearch" type="text" placeholder="type at least 2 letters">
  </label>
  <ul id="gameList" style="list-style:none; padding-left:0; margin:8px 0; display:none;"></ul>
  <label>Title * <?php if(isset($errors['title'])): ?><span class="err"><?= e($errors['title']) ?></span><?php endif; ?>
    <input id='titleField' name="title" maxlength="120" required value="<?= e($form['title']) ?>">
  </label>
  <label>Platform * <?php if(isset($errors['platform'])): ?><span class="err"><?= e($errors['platform']) ?></span><?php endif; ?>
    <input id='platformField' name="platform" maxlength="40" required value="<?= e($form['platform']) ?>" placeholder="PC, PS5, Switch...">
  </label>
  <label>Cover URL <?php if(isset($errors['cover_url'])): ?><span class="err"><?= e($errors['cover_url']) ?></span><?php endif; ?>
    <input id="coverField" name="cover_url" type="url" value="<?= e($form['cover_url']) ?>" placeholder="https://...">
  </label>
  <label>Notes
    <textarea name="notes" rows="3"><?= e($form['notes']) ?></textarea>
  </label>
  <fieldset class="inline"><legend class="sr-only">Flags</legend>
    <label><input type="checkbox" name="favorite" <?= $form['favorite']?'checked':'' ?>> Favorite</label>
    <label><input type="checkbox" name="played" <?= $form['played']?'checked':'' ?>> Played</label>
  </fieldset>
  <button type="submit">Save</button>
</form>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const search   = document.getElementById('gameSearch');
  const list     = document.getElementById('gameList');
  const titleEl  = document.getElementById('titleField');
  const platEl   = document.getElementById('platformField');
  const coverEl  = document.getElementById('coverField');

  let timer = null;

  function escapeHtml(s) {
    return String(s || '').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
  }

  function render(items) {
  items = Array.isArray(items) ? items : [];

  if (items.length === 0) {
    list.style.display = 'none';
    list.innerHTML = '';
    return;
  }

  list.innerHTML = items.map((it, i) => (
    `<li class="pick" data-i="${i}" style="
        padding:6px;
        border:1px solid #1f2937;
        border-radius:8px;
        cursor:pointer;
        /* let the grid control spacing */
        margin:0;
      ">
       <strong>${escapeHtml(it.title)}</strong>
       ${it.platform
          ? ` <span style="color:#9ca3af">(${escapeHtml(it.platform)})</span>`
          : ''
       }
       ${it.cover_url
          ? ` <img src="${escapeHtml(it.cover_url)}" style="width:200px; display:block; margin-top:6px;" />`
          : ''
       }
     </li>`
  )).join('');

 
  list.style.display = 'grid';
  list.style.gridTemplateColumns = 'repeat(3, minmax(0, 1fr))';
  list.style.gap = '8px';
  list.style.listStyle = 'none';
  list.style.padding = '0';
  list.style.margin = '0';

  // click to fill fields
  list.querySelectorAll('li.pick').forEach(li => {
    li.addEventListener('click', () => {
      const i  = parseInt(li.getAttribute('data-i'), 10);
      const it = items[i] || {};
      titleEl.value = it.title || '';
      if (it.platform)  platEl.value  = it.platform;
      if (it.cover_url) coverEl.value = it.cover_url;
      list.style.display = 'none';
      list.innerHTML = '';
    });
  });
}


  search.addEventListener('input', function () {
    const q = search.value.trim();
    if (q.length < 2) { 
      list.style.display = 'none'; 
      list.innerHTML = ''; 
      return; 
    }
    clearTimeout(timer);
    timer = setTimeout(async () => {
      try {
        console.log('Searching:', q);
        const res  = await fetch('api/search_games.php?q=' + encodeURIComponent(q));
        const data = await res.json();
        console.log('API response:', data);
        render((data && data.results) ? data.results : []);
      } catch (err) {
        console.error('API error:', err);
        list.style.display = 'none';
        list.innerHTML = '';
      }
    });
  });
});
</script>
<?php include __DIR__ . '/inc/footer.php'; ?>
