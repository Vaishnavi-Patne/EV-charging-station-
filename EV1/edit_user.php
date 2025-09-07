<?php
// edit_user.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('manage_users.php');
}

$user_id = sanitize($_GET['id']);

$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    redirect('manage_users.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $full_name = sanitize($_POST['full_name']);
    $new_password = $_POST['new_password'];

    $update_query = "UPDATE users SET username = '$username', email = '$email', full_name = '$full_name'";
    
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query .= ", password = '$hashed_password'";
    }
    
    $update_query .= " WHERE id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        $success = "User updated successfully.";
    } else {
        $error = "Failed to update user. Please try again.";
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit User</h1>

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

    <form action="edit_user.php?id=<?php echo $user_id; ?>" method="POST" class="max-w-md mx-auto">
        <div class="mb-4">
            <label for="username" class="block mb-2">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="full_name" class="block mb-2">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="new_password" class="block mb-2">New Password (leave blank to keep current password)</label>
            <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update User</button>
    </form>

    <div class="mt-8 text-center">
        <a href="manage_users.php" class="text-blue-500 hover:underline">Back to Manage Users</a>
    </div>
</div>

<?php include 'footer.php'; ?>
