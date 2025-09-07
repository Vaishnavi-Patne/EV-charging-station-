<?php
// confirm_payment.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_SESSION['pending_booking'])) {
    redirect('search.php');
}

$booking = $_SESSION['pending_booking'];
$user_id = $_SESSION['user_id'];

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Here you would typically process the payment with a payment gateway
    // For this example, we'll just simulate a successful payment

    $booking_query = "INSERT INTO bookings (user_id, slot_id, start_time, end_time, total_price, payment_status) 
                      VALUES ($user_id, {$booking['slot_id']}, '{$booking['start_time']}', '{$booking['end_time']}', {$booking['total_price']}, 'completed')";
    
    if (mysqli_query($conn, $booking_query)) {
        $success = "Payment successful! Your booking has been confirmed.";
        unset($_SESSION['pending_booking']);
    } else {
        $error = "Payment failed. Please try again.";
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Confirm Payment</h1>

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
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4">Booking Details</h2>
            <p><strong>Start Time:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['start_time'])); ?></p>
            <p><strong>End Time:</strong> <?php echo date('Y-m-d H:i', strtotime($booking['end_time'])); ?></p>
            <p><strong>Total Price:</strong> $<?php echo number_format($booking['total_price'], 2); ?></p>
        </div>

        <form action="confirm_payment.php" method="POST" class="max-w-md mx-auto">
            <div class="mb-4">
                <label for="card_number" class="block mb-2">Card Number</label>
                <input type="text" id="card_number" name="card_number" required class="w-full px-3 py-2 border rounded" placeholder="xxxxxxxxxxxx">
            </div>
            <div class="mb-4">
                <label for="expiry_date" class="block mb-2">Expiry Date</label>
                <input type="text" id="expiry_date" name="expiry_date" required class="w-full px-3 py-2 border rounded" placeholder="MM/YY">
            </div>
            <div class="mb-4">
                <label for="cvv" class="block mb-2">CVV</label>
                <input type="text" id="cvv" name="cvv" required class="w-full px-3 py-2 border rounded" placeholder="123">
            </div>
            <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Confirm Payment</button>
        </form>
    <?php endif; ?>

    <div class="mt-8 text-center">
        <a href="search.php" class="text-blue-500 hover:underline">Cancel and Return to Search</a>
    </div>
</div>

<?php include 'footer.php'; ?>
