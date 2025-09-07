<?php
// edit_profile.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $error = "New passwords do not match";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET full_name = '$full_name', email = '$email', password = '$hashed_password' WHERE id = $user_id";
        }
    } else {
        $update_query = "UPDATE users SET full_name = '$full_name', email = '$email' WHERE id = $user_id";
    }

    if (empty($error)) {
        if (mysqli_query($conn, $update_query)) {
            $success = "Profile updated successfully";
        } else {
            $error = "Failed to update profile. Please try again.";
        }
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Profile</h1>

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

    <form action="edit_profile.php" method="POST" class="max-w-md mx-auto">
        <div class="mb-4">
            <label for="full_name" class="block mb-2">Full Name</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="new_password" class="block mb-2">New Password (leave blank to keep current password)</label>
            <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="block mb-2">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Profile</button>
    </form>
</div>

<?php include 'footer.php'; ?>
