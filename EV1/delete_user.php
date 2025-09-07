<?php
// delete_user.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('manage_users.php');
}

$user_id = sanitize($_GET['id']);

// Check if the user exists
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    $_SESSION['error_message'] = "User not found.";
    redirect('manage_users.php');
}

// Prevent deleting the admin user
if ($user['username'] === 'admin') {
    $_SESSION['error_message'] = "Cannot delete the admin user.";
    redirect('manage_users.php');
}

// Delete user's bookings
$delete_bookings_query = "DELETE FROM bookings WHERE user_id = $user_id";
mysqli_query($conn, $delete_bookings_query);

// Delete user's vehicles
$delete_vehicles_query = "DELETE FROM vehicles WHERE user_id = $user_id";
mysqli_query($conn, $delete_vehicles_query);

// Delete the user
$delete_user_query = "DELETE FROM users WHERE id = $user_id";
if (mysqli_query($conn, $delete_user_query)) {
    $_SESSION['success_message'] = "User deleted successfully.";
} else {
    $_SESSION['error_message'] = "Failed to delete user. Please try again.";
}

redirect('manage_users.php');
