<?php
// book_slot.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['slot_id'])) {
    redirect('search.php');
}

$slot_id = sanitize($_GET['slot_id']);
$query = "SELECT s.*, e.name AS station_name FROM slots s JOIN ev_stations e ON s.station_id = e.id WHERE s.id = $slot_id";
$result = mysqli_query($conn, $query);
$slot = mysqli_fetch_assoc($result);

if (!$slot) {
    redirect('search.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_time = sanitize($_POST['start_time']);
    $end_time = sanitize($_POST['end_time']);
    $user_id = $_SESSION['user_id'];

    // Calculate total price
    $start = new DateTime($start_time);
    $end = new DateTime($end_time);
    $duration = $end->diff($start);
    $hours = $duration->days * 24 + $duration->h + $duration->i / 60;
    $total_price = $hours * $slot['price_per_hour'];

    $booking_query = "INSERT INTO bookings (user_id, slot_id, start_time, end_time, total_price) VALUES ($user_id, $slot_id, '$start_time', '$end_time', $total_price)";

    if (mysqli_query($conn, $booking_query)) {
        $success = "Booking successful! Total price: Rs." . number_format($total_price, 2);
    } else {
        $error = "Booking failed. Please try again.";
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Book Slot</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4"><?php echo htmlspecialchars($slot['station_name']); ?> - Slot #<?php echo $slot['slot_number']; ?></h2>
        <p class="mb-4"><strong>Price per hour:</strong> Rs. <?php echo number_format($slot['price_per_hour'], 2); ?></p>
    </div>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $success; ?></p>
        </div>
        <div class="text-center">
            <a href="user_dashboard.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Go to Dashboard</a>
        </div>
    <?php else: ?>
        <form action="book_slot.php?slot_id=<?php echo $slot_id; ?>" method="POST" class="max-w-md mx-auto">
            <div class="mb-4">
                <label for="start_time" class="block mb-2">Start Time</label>
                <input type="datetime-local" id="start_time" name="start_time" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="end_time" class="block mb-2">End Time</label>
                <input type="datetime-local" id="end_time" name="end_time" required class="w-full px-3 py-2 border rounded">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Book Slot</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>