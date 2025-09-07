<?php
// admin_dashboard.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

// Fetch pending station approvals
$pending_stations_query = "SELECT * FROM ev_stations WHERE is_approved = 0";
$pending_stations_result = mysqli_query($conn, $pending_stations_query);
$pending_stations = mysqli_fetch_all($pending_stations_result, MYSQLI_ASSOC);

// Fetch recent bookings
$bookings_query = "SELECT b.*, s.slot_number, e.name AS station_name, u.username 
                   FROM bookings b 
                   JOIN slots s ON b.slot_id = s.id 
                   JOIN ev_stations e ON s.station_id = e.id 
                   JOIN users u ON b.user_id = u.id 
                   ORDER BY b.start_time DESC 
                   LIMIT 10";
$bookings_result = mysqli_query($conn, $bookings_query);
$bookings = mysqli_fetch_all($bookings_result, MYSQLI_ASSOC);

// Fetch user statistics
$user_stats_query = "SELECT COUNT(*) as total_users, 
                     SUM(CASE WHEN username = 'admin' THEN 1 ELSE 0 END) as admin_users,
                     SUM(CASE WHEN username = 'station' THEN 1 ELSE 0 END) as station_users,
                     SUM(CASE WHEN username NOT IN ('admin', 'station') THEN 1 ELSE 0 END) as regular_users
                     FROM users";
$user_stats_result = mysqli_query($conn, $user_stats_query);
$user_stats = mysqli_fetch_assoc($user_stats_result);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-2">Total Users</h2>
            <p class="text-3xl font-bold"><?php echo $user_stats['total_users']; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-2">Admin Users</h2>
            <p class="text-3xl font-bold"><?php echo $user_stats['admin_users']; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-2">Station Users</h2>
            <p class="text-3xl font-bold"><?php echo $user_stats['station_users']; ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-2">Regular Users</h2>
            <p class="text-3xl font-bold"><?php echo $user_stats['regular_users']; ?></p>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Pending Station Approvals</h2>
    <div class="overflow-x-auto mb-8">
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Station Name</th>
                    <th class="py-3 px-6 text-left">Area</th>
                    <th class="py-3 px-6 text-left">Address</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($pending_stations as $station): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($station['name']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($station['area']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($station['address']); ?></td>
                        <td class="py-3 px-6 text-left">
                            <a href="approve_station.php?id=<?php echo $station['id']; ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Approve</a>
                            <a href="reject_station.php?id=<?php echo $station['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-2">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Recent Bookings</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Booking ID</th>
                    <th class="py-3 px-6 text-left">User</th>
                    <th class="py-3 px-6 text-left">Station</th>
                    <th class="py-3 px-6 text-left">Slot</th>
                    <th class="py-3 px-6 text-left">Start Time</th>
                    <th class="py-3 px-6 text-left">End Time</th>
                    <th class="py-3 px-6 text-left">Total Price</th>
                    <th class="py-3 px-6 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($bookings as $booking): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left"><?php echo $booking['id']; ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($booking['username']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($booking['station_name']); ?></td>
                        <td class="py-3 px-6 text-left">Slot #<?php echo $booking['slot_number']; ?></td>
                        <td class="py-3 px-6 text-left"><?php echo date('Y-m-d H:i', strtotime($booking['start_time'])); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo date('Y-m-d H:i', strtotime($booking['end_time'])); ?></td>
                        <td class="py-3 px-6 text-left">$<?php echo number_format($booking['total_price'], 2); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo ucfirst($booking['payment_status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        <a href="manage_users.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Manage Users</a>
        <a href="manage_stations.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-4">Manage Stations</a>
        <a href="manage_pages.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 ml-4">Manage Pages</a>
    </div>
</div>

<?php include 'footer.php'; ?>
