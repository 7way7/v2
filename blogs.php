<?php

// Function to save blog post data to JSON file
function saveBlogPostJSON($title, $text, $tags, $imageName, $creator, $link)
{
    $blogData = [
        'title' => $title,
        'text' => $text,
        'tags' => $tags,
        'image' => $imageName,
        'creator' => $creator,
        'link' => $link, // Add the link field to the blog post data
    ];

    $jsonBlogData = json_encode($blogData, JSON_PRETTY_PRINT);

    // Sanitize the title to remove unwanted characters
    $sanitizedTitle = preg_replace('/[^a-zA-Z0-9]/', '_', $title);

    // Save the data to a JSON file using the sanitized title
    $filePath = 'blog_posts/' . time() . '_' . $sanitizedTitle . '_post.json';

    // Check if the directory exists, if not, create it
    if (!is_dir('blog_posts')) {
        mkdir('blog_posts');
    }

    file_put_contents($filePath, $jsonBlogData);

    return $filePath;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $text = $_POST['text'];
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';
    $imageName = '';
    $creator = $_POST['creator'];
    $link = isset($_POST['link']) ? $_POST['link'] : ''; // Get the link from the form

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = 'uploads/' . uniqid() . '_' . $_FILES['image']['name'];

        // Check if the directory exists, if not, create it
        if (!is_dir('uploads')) {
            mkdir('uploads');
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $imageName)) {
            // Image uploaded successfully
        } else {
            echo json_encode(['error' => 'Error moving uploaded file']);
            exit();
        }
    }

    // Save the blog post in JSON format
    $jsonFilePath = saveBlogPostJSON($title, $text, $tags, $imageName, $creator, $link);

    echo json_encode(['message' => 'Blog post saved successfully', 'filePath' => realpath($jsonFilePath)]);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hacks.fun - Blogs</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js" defer></script>
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

            <form id="blogForm" enctype="multipart/form-data" method="post">
                <label for="blogTitle">Title:</label>
                <input type="text" id="blogTitle" name="title" required><br><br>

                <label for="blogText">Text:</label><br>
                <textarea id="blogText" name="text" rows="10" cols="50" required></textarea><br><br>

                <div class="input-group">
                    <div class="input-half">
                        <label for="blogTags">Tags:</label>
                        <input type="text" id="blogTags" name="tags">
                    </div>

                    <div class="input-half">
                        <label for="blogCreator">Creator:</label>
                        <input type="text" id="blogCreator" name="creator" required>
                    </div>
                </div>

                <!-- Add a link input field to the form -->
                <label for="blogLink">Link:</label>
                <input type="text" id="blogLink" name="link">

                <label for="blogImage">Image:</label>
                <input type="file" id="blogImage" name="image" accept="image/*"><br><br>

                <input type="submit" value="Save Blog Post">
            </form>

        </section>
    </main>

    <footer>
        <p>Hacks.fun @Made by Issam Bealaychi</p>
    </footer>

    <script>
        document.getElementById('blogForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Create FormData object
            const formData = new FormData(this);

            // Make a POST request to the server
            fetch('blogs.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Show a pop-up alert if the message is available
                    alert(data.message);

                    // Reset the form
                    this.reset();

                    // Reload the page
                    location.reload();
                }
                // Handle other actions or redirect if needed
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the blog post.');
            });
        });
    </script>

</body>
</html>
