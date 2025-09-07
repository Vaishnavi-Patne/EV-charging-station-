<?php
// delete_station.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('manage_stations.php');
}

$station_id = sanitize($_GET['id']);

// Check if the station exists
$station_query = "SELECT * FROM ev_stations WHERE id = $station_id";
$station_result = mysqli_query($conn, $station_query);
$station = mysqli_fetch_assoc($station_result);

if (!$station) {
    $_SESSION['error_message'] = "Station not found.";
    redirect('manage_stations.php');
}

// Delete associated slots
$delete_slots_query = "DELETE FROM slots WHERE station_id = $station_id";
mysqli_query($conn, $delete_slots_query);

// Delete associated bookings
$delete_bookings_query = "DELETE b FROM bookings b 
                          INNER JOIN slots s ON b.slot_id = s.id 
                          WHERE s.station_id = $station_id";
mysqli_query($conn, $delete_bookings_query);

// Delete the station
$delete_station_query = "DELETE FROM ev_stations WHERE id = $station_id";
if (mysqli_query($conn, $delete_station_query)) {
    $_SESSION['success_message'] = "Station and associated data deleted successfully.";
} else {
    $_SESSION['error_message'] = "Failed to delete station. Please try again.";
}

redirect('manage_stations.php');
