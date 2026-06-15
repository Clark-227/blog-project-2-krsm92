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
    <title>Tags &mdash; CTEC 227 Blog</title>
</head>

<body>

    <?php
    require "inc/db_connect.inc.php"; // connect to the blog database
    require "inc/functions.inc.php";  // limit_text helper
    ?>

    <!-- Include the navbar -->
    <?php require "inc/navbar.inc.php"; ?>

    <div class="container mt-5">

        <?php
        // Two modes:
        //   1. ?id=X  → show posts for a specific tag
        //   2. No ID  → show all tags as a browseable index

        if (isset($_GET['id'])) {

            //  Single tag view 
            $tag_id = $_GET['id'];

            // Fetch the tag name
            $stmt = $db->prepare("SELECT id, tag FROM tag WHERE id = :tag_id");
            $stmt->execute(["tag_id" => $tag_id]);
            $tag = $stmt->fetch();

            // Unknown tag
            if (!$tag) {
                echo "<p>Tag not found.</p>";
                echo "<a href='tag.php'>&larr; All Tags</a>";
            } else {

                // Page heading
                echo "<h1>#" . htmlspecialchars($tag->tag) . "</h1>";
                echo "<p class='text-muted'>Posts tagged with this label</p>";
                echo "<hr>";

                // Fetch all posts for this tag via post_tag join
                $stmt2 = $db->prepare(
                    "SELECT post.post_id, post.title, post.date, post.content,
                            author.author_id, author.first_name, author.last_name
                        FROM post
                        JOIN author ON post.author = author.author_id
                        JOIN post_tag ON post.post_id = post_tag.post_id
                        WHERE post_tag.tag_id = :tag_id
                        ORDER BY post.date DESC"
                );
                $stmt2->execute(["tag_id" => $tag_id]);
                $posts = $stmt2->fetchAll();

                // If no posts found, show a friendly message
                if (count($posts) === 0) {
                    echo "<p>No posts were found for this tag.</p>";
                    echo "<a href='tag.php'>&larr; All Tags</a>";
                } else {
                    // Loop through and render each post as a card
                    foreach ($posts as $row) {
                        $date = date_create($row->date);

                        echo "<div class='post-card mb-4'>";
                        echo "<h2><a href='single.php?id=" . htmlspecialchars($row->post_id) . "'>" . htmlspecialchars($row->title) . "</a></h2>";
                        echo "<hr>";
                        echo "<p class='post-meta'>"
                            . "<a href='author.php?id=" . htmlspecialchars($row->author_id) . "'>"
                            . htmlspecialchars($row->first_name) . " " . htmlspecialchars($row->last_name)
                            . "</a>"
                            . " | " . $date->format('M d, Y')
                            . "</p>";
                        echo "<p>" . htmlspecialchars(limit_text($row->content, 15)) . "</p>";
                        echo "<a class='read-more' href='single.php?id=" . htmlspecialchars($row->post_id) . "'>Read more &rarr;</a>";
                        echo "</div>"; // closing .post-card
                    }
                }

                echo "<p class='mt-3'><a href='tag.php'>&larr; All Tags</a></p>";
            }
        } else {

            //  Tags index — shows all tags as browseable badges 
            echo "<h1>All Tags</h1>";
            echo "<p class='text-muted'>Browse posts by tag</p>";
            echo "<hr>";

            // Fetch all tags sorted A-Z
            $stmt = $db->prepare("SELECT id, tag FROM tag ORDER BY tag ASC");
            $stmt->execute();
            $all_tags = $stmt->fetchAll();

            if (count($all_tags) === 0) {
                echo "<p>No tags found.</p>";
            } else {
                // Render each tag as a badge link
                foreach ($all_tags as $t) {
                    echo "<a class='badge-category me-1' href='tag.php?id=" . htmlspecialchars($t->id) . "'>#" . htmlspecialchars($t->tag) . "</a>";
                }
            }
        }
        ?>

    </div>

    <?php require "inc/footer.inc.php"; ?>

</body>

</html>