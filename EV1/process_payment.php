<?php
// process_payment.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['booking_id'])) {
    redirect('user_dashboard.php');
}

$booking_id = sanitize($_GET['booking_id']);
$user_id = $_SESSION['user_id'];

// Fetch booking information
$booking_query = "SELECT * FROM bookings WHERE id = $booking_id AND user_id = $user_id";
$booking_result = mysqli_query($conn, $booking_query);
$booking = mysqli_fetch_assoc($booking_result);

if (!$booking) {
    redirect('user_dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Simulate payment processing
    $payment_successful = true; // In a real-world scenario, this would be determined by the payment gateway

    if ($payment_successful) {
        $update_query = "UPDATE bookings SET payment_status = 'completed' WHERE id = $booking_id";
        if (mysqli_query($conn, $update_query)) {
            $success = "Payment processed successfully";
        } else {
            $error = "Failed to update payment status. Please contact support.";
        }
    } else {
        $error = "Payment processing failed. Please try again.";
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Process Payment</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $success; ?></p>
        </div>
        <a href="user_dashboard.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Return to Dashboard</a>
    <?php else: ?>
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4">Booking Details</h2>
            <p><strong>Booking ID:</strong> <?php echo $booking['id']; ?></p>
            <p><strong>Start Time:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['start_time'])); ?></p>
            <p><strong>End Time:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['end_time'])); ?></p>
            <p><strong>Total Amount:</strong> Rs. <?php echo number_format($booking['total_price'], 2); ?></p>
        </div>

        <form action="process_payment.php?booking_id=<?php echo $booking_id; ?>" method="POST" class="max-w-md mx-auto">
            <div class="mb-4">
                <label for="card_number" class="block mb-2">Card Number</label>
                <input type="text" id="card_number" name="card_number" required class="w-full px-3 py-2 border rounded" placeholder="12">
            </div>
            <div class="mb-4">
                <label for="expiry_date" class="block mb-2">Expiry Date</label>
                <input type="text" id="expiry_date" name="expiry_date" required class="w-full px-3 py-2 border rounded" placeholder="MM/YY">
            </div>
            <div class="mb-4">
                <label for="cvv" class="block mb-2">CVV</label>
                <input type="text" id="cvv" name="cvv" required class="w-full px-3 py-2 border rounded" placeholder="3">
            </div>
            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Process Payment</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
