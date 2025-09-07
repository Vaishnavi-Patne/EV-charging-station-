<?php
// user_dashboard.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Fetch user information
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Fetch user's bookings
$bookings_query = "SELECT b.*, s.slot_number, e.name AS station_name 
                   FROM bookings b 
                   JOIN slots s ON b.slot_id = s.id 
                   JOIN ev_stations e ON s.station_id = e.id 
                   WHERE b.user_id = $user_id 
                   ORDER BY b.start_time DESC";
$bookings_result = mysqli_query($conn, $bookings_query);
$bookings = mysqli_fetch_all($bookings_result, MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">User Dashboard</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4">Account Information</h2>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
        <a href="edit_profile.php" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit Profile</a>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Your Bookings</h2>
    <?php if (empty($bookings)): ?>
        <p>You have no bookings yet.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($bookings as $booking): ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($booking['station_name']); ?> - Slot #<?php echo $booking['slot_number']; ?></h3>
                    <p><strong>Start Time:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['start_time'])); ?></p>
                    <p><strong>End Time:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['end_time'])); ?></p>
                    <p><strong>Total Price:</strong> Rs. <?php echo number_format($booking['total_price'], 2); ?></p>
                    <p><strong>Status:</strong> <?php echo ucfirst($booking['payment_status']); ?></p>
                    <?php if ($booking['payment_status'] == 'pending'): ?>
                        <a href="process_payment.php?booking_id=<?php echo $booking['id']; ?>" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Pay Now</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
