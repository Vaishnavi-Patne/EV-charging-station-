<?php
// manage_pages.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

$pages_query = "SELECT * FROM pages ORDER BY id DESC";
$pages_result = mysqli_query($conn, $pages_query);
$pages = mysqli_fetch_all($pages_result, MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage Pages</h1>

    <div class="mb-4">
        <a href="add_page.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add New Page</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Title</th>
                    <th class="py-3 px-6 text-left">Slug</th>
                    <th class="py-3 px-6 text-left">Created At</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($pages as $page): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left"><?php echo $page['id']; ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($page['title']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($page['slug']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo date('Y-m-d H:i', strtotime($page['created_at'])); ?></td>
                        <td class="py-3 px-6 text-left">
                            <a href="edit_page.php?id=<?php echo $page['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
                            <a href="delete_page.php?id=<?php echo $page['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-2" onclick="return confirm('Are you sure you want to delete this page?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
