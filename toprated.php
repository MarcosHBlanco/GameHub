<?php include 'inc/header.php'; ?>
<h1>Top Rated Games</h1>

<ul class="grid" id="toprated"></ul>

<script>
document.addEventListener('DOMContentLoaded', async () => {
  const list = document.getElementById('toprated');
  if (!list) return;

  const res = await fetch('api/top_rated.php');
  if (!res.ok) { 
    console.error('HTTP', res.status); 
    return; 
  }

  let data = {};
  try { 
    data = await res.json(); 
  } catch {}
  const results = Array.isArray(data.results) ? data.results : [];

  list.replaceChildren();

  results.forEach(g => {
    const cover = g.background_image || null;

    const platforms = (g.platforms ?? [])
      .map(p => p.platform?.name).filter(Boolean).slice(0, 3)
      .join(', ');

    const genres = (g.genres ?? [])
      .map(gn => gn.name).filter(Boolean).slice(0, 3);

    const li = document.createElement('li');
    li.classList.add('card','card-bg');
    if (cover) li.style.backgroundImage = `url("${cover}")`;

    const content = document.createElement('div');
    content.className = 'content';

    const h3 = document.createElement('h3');
    h3.className = 'title';
    h3.textContent = g.name || g.title || '(no title)';
    content.appendChild(h3);

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

    // ---- Reddit link (needs details endpoint) ----
    const reddit = document.createElement('a');
    reddit.className = 'link';
    reddit.textContent = 'Reddit';
    reddit.target = '_blank';
    reddit.rel = 'noopener';
    reddit.style.display = 'none'; // show when we have a URL
    content.appendChild(reddit);

    // fetch details by id (or slug) to get reddit_url
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
        // .catch(() => {});
    }

    li.appendChild(content);
    list.appendChild(li);

    // optional: card click (ignore clicks on the reddit link)
    li.addEventListener('click', (ev) => {
      if (ev.target.tagName === 'A') return;
      console.log('Open details for:', g.id, g.slug);
      // e.g., window.location.href = 'game.php?id=' + (g.id ?? g.slug);
    });
  });

  if (!results.length) {
    const li = document.createElement('li');
    li.textContent = 'No results (check console).';
    list.appendChild(li);
  }
});
</script>

<?php include 'inc/footer.php'; ?>
