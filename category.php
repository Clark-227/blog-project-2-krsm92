<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <!-- Custom styles -->
    <link rel="stylesheet" href="css/style.css">
    <title>Category &mdash; CTEC 227 Blog</title>
</head>

<body>

    <?php
    // Require a category ID in the URL
    if (!isset($_GET['id'])) {
        die("<p>No category ID provided.</p>");
    }
    $category_id = $_GET['id'];

    require "inc/db_connect.inc.php"; // connect to the blog database
    require "inc/functions.inc.php";  // limit_text helper
    ?>

    <!-- Include the navbar -->
    <?php require "inc/navbar.inc.php"; ?>

    <div class="container mt-5">

        <?php
        // Fetch the category name by ID
        $stmt = $db->prepare("SELECT category_id, category FROM category WHERE category_id = :category_id");
        $stmt->execute(["category_id" => $category_id]);
        $cat = $stmt->fetch();

        // Unknown category ID
        if (!$cat) {
            echo "<p>Category not found.</p>";
            echo "<a href='blog.php'>&larr; Back to Blog</a>";
        } else {

            // Page heading
            echo "<h1>" . htmlspecialchars($cat->category) . "</h1>";
            echo "<p class='text-muted'>Browsing posts in this category</p>";
            echo "<hr>";

            // Fetch all posts in this category via post_category join
            $stmt2 = $db->prepare(
                "SELECT post.post_id, post.title, post.date, post.content,
                        author.author_id, author.first_name, author.last_name
                    FROM post
                    JOIN author ON post.author = author.author_id
                    JOIN post_category ON post.post_id = post_category.post_id
                    WHERE post_category.category_id = :category_id
                    ORDER BY post.date DESC"
            );
            $stmt2->execute(["category_id" => $category_id]);
            $posts = $stmt2->fetchAll();

            // If no posts found, show a friendly message
            if (count($posts) === 0) {
                echo "<p>No posts were found for this category.</p>";
                echo "<a href='blog.php'>&larr; Back to all posts</a>";
            } else {
                // Loop through and render each post as a card
                foreach ($posts as $row) {
                    $date = date_create($row->date);

                    echo "<div class='post-card mb-4'>";

                    // Title links to single post
                    echo "<h2><a href='single.php?id=" . htmlspecialchars($row->post_id) . "'>" . htmlspecialchars($row->title) . "</a></h2>";
                    echo "<hr>";

                    // Author link and date
                    echo "<p class='post-meta'>"
                        . "<a href='author.php?id=" . htmlspecialchars($row->author_id) . "'>"
                        . htmlspecialchars($row->first_name) . " " . htmlspecialchars($row->last_name)
                        . "</a>"
                        . " | " . $date->format('M d, Y')
                        . "</p>";

                    // 15-word content preview
                    echo "<p>" . htmlspecialchars(limit_text($row->content, 15)) . "</p>";
                    echo "<a class='read-more' href='single.php?id=" . htmlspecialchars($row->post_id) . "'>Read more &rarr;</a>";

                    echo "</div>"; // closing .post-card
                }
            }
        }
        ?>

    </div>

    <?php require "inc/footer.inc.php"; ?>

</body>

</html>