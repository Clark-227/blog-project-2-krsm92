<?php require_once "inc/db_connect.inc.php"; // connect to the blog database 
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="blog.php">CTEC 227 Blog</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <!-- Home link -->
        <li class="nav-item">
          <a class="nav-link" href="blog.php">Home</a>
        </li>
        <!-- Categories dropdown — fetched from the database in A-Z order -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Categories</a>
          <ul class="dropdown-menu">
            <?php
            $stmt = $db->prepare("SELECT category_id, category FROM category ORDER BY category ASC");
            $stmt->execute();
            foreach ($stmt->fetchAll() as $cat) {
              echo "<li><a class='dropdown-item' href='category.php?id=" . htmlspecialchars($cat->category_id) . "'>" . htmlspecialchars($cat->category) . "</a></li>";
            }
            ?>
          </ul>
        </li>
        <!-- Tags page -->
        <li class="nav-item">
          <a class="nav-link" href="tag.php">Tags</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Bootstrap JS bundle (required for dropdown) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>