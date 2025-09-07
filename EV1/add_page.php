<?php
// add_page.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $slug = sanitize($_POST['slug']);

    // Check if slug already exists
    $slug_check_query = "SELECT * FROM pages WHERE slug = '$slug'";
    $slug_check_result = mysqli_query($conn, $slug_check_query);
    if (mysqli_num_rows($slug_check_result) > 0) {
        $error = "A page with this slug already exists. Please choose a different slug.";
    } else {
        $insert_query = "INSERT INTO pages (title, content, slug) VALUES ('$title', '$content', '$slug')";
        if (mysqli_query($conn, $insert_query)) {
            $success = "Page added successfully.";
        } else {
            $error = "Failed to add page. Please try again.";
        }
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Add New Page</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $success; ?></p>
        </div>
    <?php endif; ?>

    <form action="add_page.php" method="POST" class="max-w-lg mx-auto">
        <div class="mb-4">
            <label for="title" class="block mb-2">Page Title</label>
            <input type="text" id="title" name="title" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="slug" class="block mb-2">Slug (URL-friendly version of title)</label>
            <input type="text" id="slug" name="slug" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="content" class="block mb-2">Page Content</label>
            <textarea id="content" name="content" required class="w-full px-3 py-2 border rounded" rows="10"></textarea>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Page</button>
    </form>

    <div class="mt-8 text-center">
        <a href="manage_pages.php" class="text-blue-500 hover:underline">Back to Manage Pages</a>
    </div>
</div>

<?php include 'footer.php'; ?>
