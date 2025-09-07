<?php
// approve_station.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('admin_dashboard.php');
}

$station_id = sanitize($_GET['id']);

$update_query = "UPDATE ev_stations SET is_approved = 1 WHERE id = $station_id";
if (mysqli_query($conn, $update_query)) {
    $_SESSION['success_message'] = "Station approved successfully.";
} else {
    $_SESSION['error_message'] = "Failed to approve station. Please try again.";
}

redirect('admin_dashboard.php');
