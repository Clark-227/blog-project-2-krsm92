<?php require "inc/db_connect.inc.php"; // connect to the blog database 
?>
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
    <title>Blog Post</title>
</head>

<body>

    <?php
    // Require a post ID in the URL
    if (!isset($_GET['id'])) {
        die("<p>No post ID provided.</p>");
    }
    $blog_id = $_GET['id'];
    ?>

    <!-- Include the navbar -->
    <?php require "inc/navbar.inc.php"; ?>

    <div class="container mt-5">

        <?php
        // SQL to get a single post with its author
        $stmt = $db->prepare(
            "SELECT post.post_id, post.title, post.date, post.content,
                    author.author_id, author.first_name, author.last_name
                FROM post
                JOIN author ON post.author = author.author_id
                WHERE post.post_id = :blog_id"
        );
        $stmt->execute(["blog_id" => $blog_id]);
        $row = $stmt->fetch();

        // Unknown post ID
        if (!$row) {
            echo "<p>Post not found.</p>";
            echo "<a href='blog.php'>&larr; Back to Blog</a>";
        } else {

            // Format the date
            $date = date_create($row->date);

            // Get categories for this post
            $stmt_cat = $db->prepare(
                "SELECT post_category.category_id, category.category
                    FROM post_category
                    JOIN category ON post_category.category_id = category.category_id
                    WHERE post_category.post_id = :post_id"
            );
            $stmt_cat->execute(["post_id" => $row->post_id]);
            $categories = $stmt_cat->fetchAll();

            // Build category links; singular vs plural label
            $label = count($categories) === 1 ? "Category" : "Categories";
            $cat_links = [];
            foreach ($categories as $cat) {
                $cat_links[] = "<a class='badge-category' href='category.php?id=" . htmlspecialchars($cat->category_id) . "'>" . htmlspecialchars($cat->category) . "</a>";
            }

            // Get tags for this post
            $stmt_tag = $db->prepare(
                "SELECT post_tag.tag_id, tag.tag
                    FROM post_tag
                    JOIN tag ON post_tag.tag_id = tag.id
                    WHERE post_tag.post_id = :post_id"
            );
            $stmt_tag->execute(["post_id" => $row->post_id]);
            $tags = $stmt_tag->fetchAll();

            // Build tag links
            $tag_links = [];
            foreach ($tags as $tag) {
                $tag_links[] = "<a class='badge-category' href='tag.php?id=" . htmlspecialchars($tag->tag_id) . "'>" . htmlspecialchars($tag->tag) . "</a>";
            }

            // Output the post
            echo "<h2>" . htmlspecialchars($row->title) . "</h2>";
            echo "<hr>";

            // Author link and date
            echo "<p class='fw-bold'>"
                . "<a href='author.php?id=" . htmlspecialchars($row->author_id) . "'>"
                . htmlspecialchars($row->first_name) . " " . htmlspecialchars($row->last_name)
                . "</a>"
                . " - " . $date->format('M d, Y')
                . "</p>";

            // Categories
            if (!empty($cat_links)) {
                echo "<p><strong>" . $label . ":</strong> " . implode(", ", $cat_links) . "</p>";
            }

            // Tags
            if (!empty($tag_links)) {
                echo "<p><strong>Tags:</strong> " . implode(", ", $tag_links) . "</p>";
            }

            // Full post content
            echo "<p>" . htmlspecialchars($row->content) . "</p>";
            echo "<a href='blog.php'>&larr; Back to Blog</a>";
        }
        ?>

    </div>

    <?php require "inc/footer.inc.php"; ?>

</body>

</html>