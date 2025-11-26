<?php
// sources.php
ini_set('display_errors', 1); error_reporting(E_ALL);

require_once __DIR__ . '/inc/functions.php';

$pageTitle = 'Sources';

include __DIR__ . '/inc/header.php';
?>
<section class="card">
  <h2>Sources</h2>

  <h3>Images and Data</h3>
  <ul>
    <li>Game data and cover images from <a href="https://rawg.io/apidocs" target="_blank" rel="noopener">RAWG API</a>.</li>
  </ul>

  <h3>Icons and Fonts</h3>
  <ul>
    <li>Google Fonts — <a href="https://fonts.google.com" target="_blank" rel="noopener">fonts.google.com</a></li>
  </ul>

  <h3>Code References</h3>
  <ul>
    <li>RAWG usage/reference: <a href="https://developer.mozilla.org/" target="_blank" rel="noopener">MDN Web Docs</a></li>
  </ul>

  <h3>Attribution</h3>
  <p>
    This site was created for educational purposes for Langara College’s CPSC 2030 course.
    All third-party resources are credited and used under appropriate licenses.
  </p>
</section>
<?php include __DIR__ . '/inc/footer.php'; ?>
