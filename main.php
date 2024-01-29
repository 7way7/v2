<?php
// Function to get all blog posts from JSON files
function getAllBlogPosts() {
    $blogPosts = [];

    $files = glob('blog_posts/*.json');

    foreach ($files as $file) {
        $jsonContent = file_get_contents($file);
        $data = json_decode($jsonContent, true);

        if ($data) {
            $blogPosts[] = $data;
        }
    }

    return $blogPosts;
}

// Retrieve all blog posts
$allBlogPosts = getAllBlogPosts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacks.fun - Blogs</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js" defer></script>
    <style>
        /* Add styling for individual blog posts */
        .blogPost {
            display: flex;
            border: 2px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
        }

        /* Style for blog post images */
        .blogImage {
            max-width: 150px; /* Adjust the width as needed */
            height: auto;
            margin-right: 10px;
        }

        /* Style for blog post content (title, creator) */
        .blogContent {
            flex-grow: 1; /* Allow the content to grow and fill the available space */
        }

        .blogTitle {
            font-size: 18px; /* Adjust the font size as needed */
            margin-bottom: 5px;
        }

        .blogCreator {
            font-size: 14px; /* Adjust the font size as needed */
            color: #888; /* Add color to the creator text */
        }
    </style>
</head>
<body>
    <header>
        <h1>Hacks.fun</h1>
        <nav>
            <a href="main.php" onclick="showSection('mainContent')">Main</a>
            <a href="blogs.php" onclick="showSection('blogsContent')">Blogs</a>
            <a href="tags.php" onclick="showSection('tagsContent')">Tags</a>
            <a href="about.php" onclick="showSection('aboutContent')">About</a>
        </nav>
    </header>

    <main>
        <section id="blogsContent">
            <h2>Blogs</h2>

            <!-- Display Blogs with Images -->
            <?php foreach ($allBlogPosts as $blogPost): ?>
                <div class="blogPost">
                    <?php if (!empty($blogPost['image'])): ?>
                        <img class="blogImage" src="<?= htmlspecialchars($blogPost['image']) ?>" alt="Blog Image">
                    <?php endif; ?>
                    <div class="blogContent">
                        <h3 class="blogTitle"><?= htmlspecialchars($blogPost['title']) ?></h3>
                        <p class="blogCreator">Creator: <?= htmlspecialchars($blogPost['creator']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    </main>

    <footer>
        <p>Hacks.fun @Made by Issam Bealaychi</p>
    </footer>
</body>
</html>