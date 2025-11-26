<?php
$pageTitle = 'Sources';
include __DIR__ . '/inc/header.php';
?>

<section class="card">
  <h1>Sources</h1>

  <p>
    This project was built using a mix of official documentation and learning
    resources. The main external sources are listed below.
  </p>

  <h2>APIs & Official Docs</h2>
  <ul>
    <li>
      <strong>RAWG Video Games Database API</strong> – Game data, covers, genres, platforms, and Metacritic scores.<br>
      <a href="https://rawg.io/apidocs" target="_blank" rel="noopener">
        https://rawg.io/apidocs
      </a>
    </li>
    <li>
      <strong>PHP Manual</strong> – PDO, sessions, filters, and general PHP usage.<br>
      <a href="https://www.php.net/docs.php" target="_blank" rel="noopener">
        https://www.php.net/docs.php
      </a>
    </li>
    <li>
      <strong>PDO Documentation</strong> – Prepared statements, error modes, and fetch modes.<br>
      <a href="https://www.php.net/manual/en/book.pdo.php" target="_blank" rel="noopener">
        https://www.php.net/manual/en/book.pdo.php
      </a>
    </li>
    <li>
      <strong>SQLite Documentation</strong> – SQL dialect and datetime functions (e.g. <code>datetime('now')</code>).<br>
      <a href="https://www.sqlite.org/docs.html" target="_blank" rel="noopener">
        https://www.sqlite.org/docs.html
      </a>
    </li>
  </ul>

  <h2>Frontend & JavaScript</h2>
  <ul>
    <li>
      <strong>MDN Web Docs</strong> – JavaScript, Fetch API, <code>IntersectionObserver</code>, and DOM APIs.<br>
      <a href="https://developer.mozilla.org/" target="_blank" rel="noopener">
        https://developer.mozilla.org/
      </a>
    </li>
    <li>
      <strong>jQuery Documentation</strong> – Used for the mobile navigation and some basic DOM helpers.<br>
      <a href="https://api.jquery.com/" target="_blank" rel="noopener">
        https://api.jquery.com/
      </a>
    </li>
    <li>
      <strong>CSS Grid & Flexbox Guides (MDN)</strong> – For the responsive card layout and header/footer structure.<br>
      <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout" target="_blank" rel="noopener">
        CSS Grid on MDN
      </a><br>
      <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout" target="_blank" rel="noopener">
        Flexbox on MDN
      </a>
    </li>
  </ul>

  <h2>Deployment & Hosting</h2>
  <ul>
    <li>
      <strong>Railway Docs</strong> – For configuring the PHP service, environment variables, and deployment from GitHub.<br>
      <a href="https://docs.railway.com/" target="_blank" rel="noopener">
        https://docs.railway.com/
      </a>
    </li>
  </ul>

  <h2>General Learning & References</h2>
  <ul>
    <li>
      <strong>Stack Overflow</strong> – Used occasionally to look up specific PHP/PDO or deployment issues.
    </li>
    <li>
      <strong>Course material (CPSC 2030)</strong> – Project requirements, code structure guidelines,
      and examples provided by the instructor.
    </li>
  </ul>

  <p class="muted" style="margin-top:12px;">
    All custom PHP, CSS, and JavaScript code in this project was written by the student, 
    with the resources above used as documentation and reference.
  </p>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>
