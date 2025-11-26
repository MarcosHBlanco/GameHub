<?php
require_once __DIR__ . '/inc/db.php';
require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'Browse Games';
include __DIR__ . '/inc/header.php';
?>

<h1>Welcome</h1>
<p class="muted">Browse your favorite games.</p>

<!-- Search form -->
<form id="searchForm" style="margin:16px 0; display:flex; gap:8px; flex-wrap:wrap;">
  <label class="sr-only" for="searchInput">Search games</label>
  <input
    type="text"
    id="searchInput"
    placeholder="Search games (e.g. Elden Ring)…"
    style="max-width:280px;"
  >
  <button type="submit" class="btn">Search</button>
</form>

<!-- Error message -->
<div id="error" class="errorAlert" style="display:none;"></div>

<!-- Game grid -->
<ul class="grid" id="gamesGrid"></ul>

<!-- Loading indicator + sentinel for infinite scroll -->
<div id="loading" class="muted" style="margin-top:16px;">Loading…</div>
<div id="sentinel"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const grid      = document.getElementById('gamesGrid');
  const loadingEl = document.getElementById('loading');
  const errorEl   = document.getElementById('error');
  const sentinel  = document.getElementById('sentinel');
  const searchInp = document.getElementById('searchInput');
  const searchForm= document.getElementById('searchForm');

  let page         = 1;
  let loading      = false;
  let finished     = false;
  let currentSearch= '';

  async function loadGames() {
    if (loading || finished) return;
    loading = true;
    errorEl.style.display = 'none';
    loadingEl.textContent = 'Loading…';

    const params = new URLSearchParams({
      page: page,
      page_size: 20,
      ordering: '-rating'
    });
    if (currentSearch.trim() !== '') {
      params.set('search', currentSearch.trim());
    }

    try {
      const res = await fetch('api/games_general.php?' + params.toString());
      if (!res.ok) throw new Error('HTTP ' + res.status);

      const data    = await res.json();
      const results = Array.isArray(data.results) ? data.results : [];

      appendGames(results);

      // RAWG uses "next" for pagination
      if (!data.next) {
        finished = true;
        loadingEl.textContent = 'No more games.';
      } else {
        page++;
        loadingEl.textContent = '';
      }
    } catch (err) {
      console.error(err);
      errorEl.textContent = 'Failed to load games. Please try again.';
      errorEl.style.display = 'block';
      loadingEl.textContent = '';
    } finally {
      loading = false;
    }
  }

  function appendGames(games) {
    games.forEach(g => {
      const li = document.createElement('li');
      li.className = 'card fade-in';

      // Cover
      const coverWrap = document.createElement('div');
      coverWrap.className = 'cover-wrap';

      if (g.background_image) {
        const img = document.createElement('img');
        img.src = g.background_image;
        img.alt = g.name || 'Game cover';
        coverWrap.appendChild(img);
      } else {
        const placeholder = document.createElement('div');
        placeholder.className = 'cover-placeholder';
        placeholder.textContent = 'No cover';
        coverWrap.appendChild(placeholder);
      }

      // Card body
      const body = document.createElement('div');
      body.className = 'card-body';

      const title = document.createElement('h2');
      title.className = 'title';
      title.textContent = g.name || 'Unknown title';

      const inlineMeta = document.createElement('div');
      inlineMeta.className = 'inline muted';

      const genres = (g.genres ?? []).map(gn => gn.name).filter(Boolean).slice(0,3);

      const reddit = document.createElement('a');
      reddit.className = 'link';
      reddit.textContent = 'Reddit';
      reddit.target = '_blank';
      reddit.rel = 'noopener';
      reddit.style.display = 'none';
      body.appendChild(reddit);

      const ident = g.id ?? g.slug;
      if(ident !== null){
        fetch(`api/games_details.php?id=${encodeURIComponent(ident)}`)
        .then(r => r.ok ? r.json() : null)
        .then(d => {
          if(d && d.reddit.url) {
            reddit.href = d.reddit_url;
            reddit.style.display = 'inline-block';
          }
        })
      }

      const rating = document.createElement('span');
      rating.className = 'badge';
      const ratingValue = (typeof g.rating === 'number') ? g.rating.toFixed(1) : 'N/A';
      rating.textContent = '⭐ ' + ratingValue;

      
      if(typeof g.metacritic === 'number'){
        const metacritic = document.createElement('span');
        metacritic.className = 'badge';
        metacritic.textContent = `Metacritic: ${g.metacritic}`;
        body.appendChild(metacritic);
      }

      const released = document.createElement('span');
      if (g.released) {
        released.textContent = 'Released: ' + g.released;
      }
      else {
        released.textContent = 'Released: not available';
      }

      inlineMeta.appendChild(rating);
      if (released.textContent) inlineMeta.appendChild(released);

      // Platforms (first 3)
      let platformsText = '';
      if (Array.isArray(g.platforms)) {
        const names = g.platforms
          .map(p => p.platform && p.platform.name)
          .filter(Boolean)
          .slice(0, 3);
        platformsText = names.join(', ');
      }

      body.appendChild(title);
      body.appendChild(inlineMeta);
      if(genres.length){
        const line = document.createElement('div');
        genres.forEach(genre => {
          const g = document.createElement('span');
          g.className = 'badge';
          g.textContent = genre;
          line.appendChild(g);
        })
        body.appendChild(line);
      }
      
      if (platformsText) {
        const platformsEl = document.createElement('div');
        platformsEl.className = 'muted';
        platformsEl.textContent = platformsText;
        body.appendChild(platformsEl);
      }

      li.appendChild(coverWrap);
      li.appendChild(body);
      grid.appendChild(li);
    });
  }

  // Infinite scroll
  const observer = new IntersectionObserver(entries => {
    const entry = entries[0];
    if (entry.isIntersecting) {
      loadGames();
    }
  });
  observer.observe(sentinel);

  // Search
  searchForm.addEventListener('submit', function (e) {
    e.preventDefault();
    currentSearch = searchInp.value;
    page = 1;
    finished = false;
    grid.innerHTML = '';
    loadGames();
  });

  // Initial load
  loadGames();
});
</script>

<?php include __DIR__ . '/inc/footer.php'; ?>
