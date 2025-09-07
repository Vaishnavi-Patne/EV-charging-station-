<?php
// reject_station.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('admin_dashboard.php');
}

$station_id = sanitize($_GET['id']);

$delete_query = "DELETE FROM ev_stations WHERE id = $station_id";
if (mysqli_query($conn, $delete_query)) {
    $_SESSION['success_message'] = "Station rejected and removed successfully.";
} else {
    $_SESSION['error_message'] = "Failed to reject station. Please try again.";
}

redirect('admin_dashboard.php');
