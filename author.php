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
    <title>Author &mdash; CTEC 227 Blog</title>
</head>

<body>

    <?php
    // Require an author ID in the URL
    if (!isset($_GET['id'])) {
        die("<p>No author ID provided.</p>");
    }
    $author_id = $_GET['id'];

    require "inc/db_connect.inc.php"; // connect to the blog database
    require "inc/functions.inc.php";  // functions
    ?>

    <!-- Include the navbar -->
    <?php require "inc/navbar.inc.php"; ?>

    <div class="container mt-5">

        <?php
        // Fetch the author by ID
        $stmt = $db->prepare("SELECT author_id, first_name, last_name FROM author WHERE author_id = :author_id");
        $stmt->execute(["author_id" => $author_id]);
        $author = $stmt->fetch();

        // Unknown author ID
        if (!$author) {
            echo "<p>Author not found.</p>";
            echo "<a href='blog.php'>&larr; Back to Blog</a>";
        } else {

            // Page heading with author's full name
            $full_name = htmlspecialchars($author->first_name) . " " . htmlspecialchars($author->last_name);
            echo "<h1>" . $full_name . "</h1>";
            echo "<p class='text-muted'>All posts by this author</p>";
            echo "<hr>";

            // Fetch all posts written by this author, newest first
            $stmt2 = $db->prepare(
                "SELECT post_id, title, date, content
                    FROM post
                    WHERE author = :author_id
                    ORDER BY date DESC"
            );
            $stmt2->execute(["author_id" => $author_id]);
            $posts = $stmt2->fetchAll();

            // If no posts found, show a friendly message
            if (count($posts) === 0) {
                echo "<p>No posts were found for this author.</p>";
                echo "<a href='blog.php'>&larr; Back to all posts</a>";
            } else {
                // Loop sthrough and render each post as a card
                foreach ($posts as $row) {
                    $date = date_create($row->date);

                    echo "<div class='post-card mb-4'>";

                    // Title links to single post
                    echo "<h2><a href='single.php?id=" . htmlspecialchars($row->post_id) . "'>" . htmlspecialchars($row->title) . "</a></h2>";
                    echo "<hr>";

                    // Date meta
                    echo "<p class='post-meta'>" . $date->format('M d, Y') . "</p>";

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