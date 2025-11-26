<?php
// docs.php
ini_set('display_errors', 1); 
error_reporting(E_ALL);

require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'Documentation';

include __DIR__ . '/inc/header.php';
?>
<section class="card">
  <h2>Documentation</h2>

  <h3>Project Overview</h3>
  <p>
    This website is a simple <strong>Game Hub</strong> where users can search for games using the RAWG API and add them to a local database.
    It was built using <strong>PHP, MySQL, HTML, CSS, and JavaScript</strong>.
  </p>

  <h3>Features Implemented</h3>
  <ul>
    <li>Dynamic content fetched from a MySQL database.</li>
    <li>Search form with client-side (JS) and server-side (PHP) validation.</li>
    <li>Games can be added, displayed, and stored persistently.</li>
    <li>Responsive design using CSS Flexbox and simple keyframe animation.</li>
    <li>Prepared statements to prevent SQL injection.</li>
    <li>CSRF protection to prevent cross-site request forgery.</li>
  </ul>

  <h3>How to Use</h3>
  <ol>
    <li>Type part of a game name (e.g., <em>Mario</em>) in the search bar.</li>
    <li>Select a suggestion to auto-fill the fields.</li>
    <li>Click <strong>Save</strong> to add it to the list.</li>
    <li>Return to the home page to see your saved games.</li>
  </ol>

  <h3>Validation and Security</h3>
  <ul>
    <li>Client-side: the search field requires at least 2 characters.</li>
    <li>Server-side: PHP validates input before inserting to DB.</li>
    <li>Database queries use prepared statements to prevent SQL injection.</li>
  </ul>

  <h3>Responsive Design and Accessibility</h3>
  <ul>
    <li>Layout built with Flexbox and Grid (no float layouts).</li>
    <li>Text with good contrast and alt attributes for images.</li>
    <li>Labels linked to form fields for accessibility.</li>
  </ul>

  <h3>Validation Screenshots</h3>
  <p><em>Include screenshots here</em> (HTML and CSS validation results).</p>

  <h3>Bonus Features</h3>
  <p>Integration with an external API (RAWG) and dynamic search suggestions.</p>
</section>
<?php include __DIR__ . '/inc/footer.php'; ?>
