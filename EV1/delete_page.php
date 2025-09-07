<?php
// delete_page.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('manage_pages.php');
}

$page_id = sanitize($_GET['id']);

// Check if the page exists
$page_query = "SELECT * FROM pages WHERE id = $page_id";
$page_result = mysqli_query($conn, $page_query);
$page = mysqli_fetch_assoc($page_result);

if (!$page) {
    $_SESSION['error_message'] = "Page not found.";
    redirect('manage_pages.php');
}

// Delete the page
$delete_page_query = "DELETE FROM pages WHERE id = $page_id";
if (mysqli_query($conn, $delete_page_query)) {
    $_SESSION['success_message'] = "Page deleted successfully.";
} else {
    $_SESSION['error_message'] = "Failed to delete page. Please try again.";
}

redirect('manage_pages.php');
