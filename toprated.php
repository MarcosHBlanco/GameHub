<?php include 'inc/header.php'; ?>
<h1>Top Rated Games</h1>

<!-- Error + list + loading + sentinel -->
<div id="error" class="err" style="display:none; margin-bottom:8px;"></div>

<ul class="grid" id="toprated"></ul>

<!-- Loading indicator + sentinel for infinite scroll -->
<div id="loading" class="muted" style="margin-top:16px;">Loading…</div>
<div id="sentinel"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const list      = document.getElementById('toprated');
  const loadingEl = document.getElementById('loading');
  const errorEl   = document.getElementById('error');
  const sentinel  = document.getElementById('sentinel');

  if (!list || !loadingEl || !errorEl || !sentinel) {
    console.error('Missing one of the required elements for Top Rated page');
    return;
  }

  let page     = 1;
  let loading  = false;
  let finished = false;

  // ---- Render helper: append cards to the list ----
  function appendGames(results) {
    results = Array.isArray(results) ? results : [];
    if (!results.length && list.children.length === 0) {
      const li = document.createElement('li');
      li.textContent = 'No results (check console).';
      list.appendChild(li);
      return;
    }

    results.forEach(g => {
      const cover = g.background_image || null;

      const platforms = (g.platforms ?? [])
        .map(p => p.platform?.name)
        .filter(Boolean)
        .slice(0, 3)
        .join(', ');

      const genres = (g.genres ?? [])
        .map(gn => gn.name)
        .filter(Boolean)
        .slice(0, 3);

      const li = document.createElement('li');
      li.classList.add('card','card-bg');
      if (cover) li.style.backgroundImage = `url("${cover}")`;

      const content = document.createElement('div');
      content.className = 'content';

      const h3 = document.createElement('h3');
      h3.className = 'title';
      h3.textContent = g.name || g.title || '(no title)';
      content.appendChild(h3);

      const rating = document.createElement('span');
      rating.className = 'badge';
      const ratingValue = (typeof g.rating === 'number') ? g.rating.toFixed(1) : 'N/A';
      rating.textContent = '⭐ ' + ratingValue;
      content.appendChild(rating);

      if (genres.length){
        const line = document.createElement('div');
        genres.forEach(name => {
          const b = document.createElement('span');
          b.className = 'badge';
          b.textContent = name;
          line.appendChild(b);
        });
        content.appendChild(line);
      }

      if (platforms){
        const small = document.createElement('div');
        small.style.opacity = '0.9';
        small.style.fontSize = '12px';
        small.textContent = platforms;
        content.appendChild(small);
      }

      if (typeof g.metacritic === 'number'){
        const mc = document.createElement('span');
        mc.className = 'badge';
        mc.textContent = `Metacritic: ${g.metacritic}`;
        content.appendChild(mc);
      }

      // Reddit link (from game_details endpoint)
      const reddit = document.createElement('a');
      reddit.className = 'link';
      reddit.textContent = 'Reddit';
      reddit.target = '_blank';
      reddit.rel = 'noopener';
      reddit.style.display = 'none'; // show when we have a URL
      content.appendChild(reddit);

      const ident = g.id ?? g.slug;
      if (ident != null) {
        fetch(`api/game_details.php?id=${encodeURIComponent(ident)}`)
          .then(r => r.ok ? r.json() : null)
          .then(d => {
            if (d && d.reddit_url) {
              reddit.href = d.reddit_url;
              reddit.style.display = 'inline-block';
            }
          })
          .catch(() => {});
      }

      li.appendChild(content);
      list.appendChild(li);

      // Optional: click on card (ignoring Reddit link)
      li.addEventListener('click', (ev) => {
        if (ev.target.tagName === 'A') return;
        console.log('Open details for:', g.id, g.slug);
        // e.g., window.location.href = 'game.php?id=' + (g.id ?? g.slug);
      });
    });
  }

  // ---- Infinite-scroll loader ----
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

    try {
      const res = await fetch('api/games_general.php?' + params.toString());
      if (!res.ok) throw new Error('HTTP ' + res.status);

      const data    = await res.json();
      const results = Array.isArray(data.results) ? data.results : [];

      appendGames(results);

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

  // ---- IntersectionObserver for sentinel ----
  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        loadGames();
      }
    });
  });

  observer.observe(sentinel);

  // Initial load
  loadGames();
});
</script>

<?php include 'inc/footer.php'; ?>
