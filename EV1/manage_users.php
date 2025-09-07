<?php
// manage_users.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$users_query = "SELECT * FROM users WHERE username != 'admin'";
if (!empty($search)) {
    $users_query .= " AND (username LIKE '%$search%' OR email LIKE '%$search%' OR full_name LIKE '%$search%')";
}
$users_query .= " ORDER BY id DESC";

$users_result = mysqli_query($conn, $users_query);
$users = mysqli_fetch_all($users_result, MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage Users</h1>

    <form action="manage_users.php" method="GET" class="mb-8">
        <div class="flex">
            <input type="text" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>" class="flex-grow px-3 py-2 border rounded-l">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600">Search</button>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Username</th>
                    <th class="py-3 px-6 text-left">Email</th>
                    <th class="py-3 px-6 text-left">Full Name</th>
                    <th class="py-3 px-6 text-left">Created At</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($users as $user): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left"><?php echo $user['id']; ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo date('Y-m-d H:i', strtotime($user['created_at'])); ?></td>
                        <td class="py-3 px-6 text-left">
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-2" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
