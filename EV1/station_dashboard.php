<?php
// station_dashboard.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Fetch station information
$station_query = "SELECT * FROM ev_stations WHERE user_id = $user_id";
$station_result = mysqli_query($conn, $station_query);
$station = mysqli_fetch_assoc($station_result);

if (!$station) {
    redirect('index.php'); // Redirect if user doesn't have a station
}

// Fetch station's slots
$slots_query = "SELECT * FROM slots WHERE station_id = {$station['id']}";
$slots_result = mysqli_query($conn, $slots_query);
$slots = mysqli_fetch_all($slots_result, MYSQLI_ASSOC);

// Fetch recent bookings
$bookings_query = "SELECT b.*, s.slot_number, u.username 
                   FROM bookings b 
                   JOIN slots s ON b.slot_id = s.id 
                   JOIN users u ON b.user_id = u.id 
                   WHERE s.station_id = {$station['id']} 
                   ORDER BY b.start_time DESC 
                   LIMIT 10";
$bookings_result = mysqli_query($conn, $bookings_query);
$bookings = mysqli_fetch_all($bookings_result, MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Station Dashboard</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4">Station Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($station['name']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($station['address']); ?></p>
        <p><strong>Area:</strong> <?php echo htmlspecialchars($station['area']); ?></p>
        <p><strong>Status:</strong> <?php echo $station['is_approved'] ? 'Approved' : 'Pending Approval'; ?></p>
        <a href="edit_station.php?id=<?php echo $station['id']; ?>" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit Station</a>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Charging Slots</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <?php foreach ($slots as $slot): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Slot #<?php echo $slot['slot_number']; ?></h3>
                <p><strong>Price per hour:</strong> Rs. <?php echo number_format($slot['price_per_hour'], 2); ?></p>
                <a href="edit_slot.php?id=<?php echo $slot['id']; ?>" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit Slot</a>
            </div>
        <?php endforeach; ?>
    </div>
    <a href="add_slot.php?station_id=<?php echo $station['id']; ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add New Slot</a>

    <h2 class="text-2xl font-semibold mb-4 mt-8">Recent Bookings</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Booking ID</th>
                    <th class="py-3 px-6 text-left">User</th>
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
                        <td class="py-3 px-6 text-left">Slot #<?php echo $booking['slot_number']; ?></td>
                        <td class="py-3 px-6 text-left"><?php echo date('Y-m-d H:i', strtotime($booking['start_time'])); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo date('Y-m-d H:i', strtotime($booking['end_time'])); ?></td>
                        <td class="py-3 px-6 text-left">Rs. <?php echo number_format($booking['total_price'], 2); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo ucfirst($booking['payment_status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>