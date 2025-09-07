<?php
// view_page.php
require_once 'config.php';

if (!isset($_GET['slug'])) {
    redirect('index.php');
}

$slug = sanitize($_GET['slug']);

$page_query = "SELECT * FROM pages WHERE slug = '$slug'";
$page_result = mysqli_query($conn, $page_query);
$page = mysqli_fetch_assoc($page_result);

if (!$page) {
    // Page not found, redirect to 404 page or home page
    redirect('index.php');
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8"><?php echo htmlspecialchars($page['title']); ?></h1>

    <div class="prose max-w-none">
        <?php echo nl2br(htmlspecialchars($page['content'])); ?>
    </div>
</div>

<?php include 'footer.php'; ?>
