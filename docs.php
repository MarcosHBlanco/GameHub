<?php
$pageTitle = 'Documentation';
include __DIR__ . '/inc/header.php';
?>

<section class="card">
  <h1>Documentation</h1>

  <h2>Overview</h2>
  <p>
    Game Hub is a PHP web application that lets you search games from the RAWG API
    and maintain your own personal game list. You can add games, mark them as
    favorites or played, filter/search your list, and view top-rated games with
    infinite scroll.
  </p>

  <h2>Architecture</h2>
  <ul>
    <li><strong>Frontend:</strong> PHP templates, CSS Grid/Flexbox, a bit of vanilla JS and jQuery.</li>
    <li><strong>Backend:</strong> PHP 8 with PDO, talking to a SQL database.</li>
    <li><strong>APIs:</strong> RAWG Video Games Database for external game data.</li>
    <li><strong>Deployment:</strong> Railway (GameHub service + environment variables).</li>
  </ul>

  <h2>Database</h2>
  <h3>Local (development)</h3>
  <ul>
    <li>Database: <code>gamehub</code> (MySQL / MariaDB via XAMPP).</li>
    <li>Table: <code>games</code> with fields:
      <code>id, title, platform, notes, cover_url, favorite, played, created_at</code>.
    </li>
    <li>Connection handled in <code>inc/db.php</code> using PDO + prepared statements.</li>
  </ul>

  <h3>Production (Railway)</h3>
  <ul>
    <li>Railway’s default PHP image does not include <code>pdo_mysql</code>,</li>
    <li>So the app automatically falls back to <strong>SQLite</strong> when MySQL PDO is not available.</li>
    <li>The SQLite file is stored as <code>data/gamehub.sqlite</code> inside the container.</li>
    <li><code>inc/db.php</code>:
      <ul>
        <li>Uses MySQL when PDO has the <code>mysql</code> driver (local).</li>
        <li>Uses SQLite and auto-creates the <code>games</code> table on Railway.</li>
      </ul>
    </li>
  </ul>

  <h2>Pages</h2>
  <ul>
    <li>
      <strong>index.php</strong> — Main page with infinite scroll:
      <ul>
        <li>Fetches games from <code>api/games_general.php</code> using the RAWG API.</li>
        <li>Supports pagination via RAWG’s <code>page</code>, <code>page_size</code>, and <code>next</code> URL.</li>
        <li>Client-side infinite scroll using <code>IntersectionObserver</code> and a sentinel div.</li>
      </ul>
    </li>
    <li>
      <strong>toprated.php</strong> — Top rated games:
      <ul>
        <li>Also uses <code>api/games_general.php</code> with <code>ordering=-rating</code>.</li>
        <li>Displays cards with cover, genres, platforms, and Metacritic score.</li>
        <li>Infinite scroll similar to the main page.</li>
      </ul>
    </li>
    <li>
      <strong>add.php</strong> — Add new game:
      <ul>
        <li>Form with server-side validation in <code>validate_game()</code>.</li>
        <li>Optional search field that hits <code>api/search_games.php</code> (RAWG) to pre-fill the form.</li>
        <li>Uses PDO prepared statements to insert into <code>games</code>.</li>
        <li>CSRF token required on POST.</li>
      </ul>
    </li>
    <li>
      <strong>edit.php</strong> — Edit an existing game:
      <ul>
        <li>Loads a record by ID from <code>games</code>.</li>
        <li>Reuses validation and update logic via PDO prepared statements.</li>
      </ul>
    </li>
    <li>
      <strong>mygames.php</strong> — Your saved games:
      <ul>
        <li>Lists games from the local database (MySQL locally, SQLite in production).</li>
        <li>Supports search by title and filter by platform (GET parameters).</li>
        <li>No redirect to <code>index.php</code> when filtering; the form submits back to <code>mygames.php</code>.</li>
      </ul>
    </li>
    <li>
      <strong>docs.php</strong> — This documentation page.</li>
    <li>
      <strong>sources.php</strong> — Lists external resources and references used.</li>
  </ul>

  <h2>RAWG API Integration</h2>
  <ul>
    <li>API key stored in environment variable <code>RAWG_API_KEY</code>.</li>
    <li>Backend endpoints:
      <ul>
        <li><code>api/games_general.php</code> – General game listing (supports page, search, ordering).</li>
        <li><code>api/top_rated.php</code> – (Legacy) example for top-rated; now superseded by <code>games_general.php</code> + ordering.</li>
        <li><code>api/game_details.php</code> – Fetches details for a single game (used for Reddit URL).</li>
        <li><code>api/search_games.php</code> – Lightweight search used in the Add Game form.</li>
      </ul>
    </li>
    <li>All API calls use <code>curl</code> or <code>file_get_contents</code> with proper error handling.</li>
  </ul>

  <h2>Security & Validation</h2>
  <ul>
    <li>All database access uses PDO prepared statements (no string-concatenated SQL).</li>
    <li>CSRF token:
      <ul>
        <li>Generated and stored in the session.</li>
        <li>Printed as a hidden input via <code>csrf_field()</code>.</li>
        <li>Verified on each POST via <code>csrf_check()</code>.</li>
      </ul>
    </li>
    <li>Input validation:
      <ul>
        <li>Server-side validation in <code>validate_game()</code>:</li>
        <li>Checks required fields (title, platform), max lengths, and URL format for <code>cover_url</code>.</li>
      </ul>
    </li>
    <li>HTML output escaped using <code>e()</code> to prevent XSS.</li>
  </ul>

  <h2>Styling & Layout</h2>
  <ul>
    <li>Custom CSS using variables in <code>:root</code> for colors and theme.</li>
    <li>Responsive layout using CSS Grid for game cards (<code>.grid</code>).</li>
    <li>Flexbox for small components (header, button groups, inline flags).</li>
    <li>Mobile menu toggle implemented in <code>assets/js/main.js</code> (jQuery + class toggling).</li>
  </ul>

  <h2>Future Improvements</h2>
  <ul>
    <li>User accounts and authentication (per-user lists).</li>
    <li>Additional filters (genres, year, Metacritic ranges).</li>
    <li>More robust error messages and loading skeletons.</li>
    <li>Export/import of saved lists.</li>
  </ul>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>
