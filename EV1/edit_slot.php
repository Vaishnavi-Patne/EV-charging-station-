<?php
// edit_slot.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if (!isset($_GET['id'])) {
    redirect('station_dashboard.php');
}

$slot_id = sanitize($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch slot information and ensure it belongs to the logged-in user's station
$slot_query = "SELECT s.*, e.name AS station_name 
               FROM slots s 
               JOIN ev_stations e ON s.station_id = e.id 
               WHERE s.id = $slot_id AND e.user_id = $user_id";
$slot_result = mysqli_query($conn, $slot_query);
$slot = mysqli_fetch_assoc($slot_result);

if (!$slot) {
    redirect('station_dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot_number = sanitize($_POST['slot_number']);
    $price_per_hour = sanitize($_POST['price_per_hour']);

    // Check if the new slot number already exists for this station
    $check_query = "SELECT * FROM slots 
                    WHERE station_id = {$slot['station_id']} 
                    AND slot_number = $slot_number 
                    AND id != $slot_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "A slot with this number already exists for your station.";
    } else {
        $update_query = "UPDATE slots 
                         SET slot_number = $slot_number, price_per_hour = $price_per_hour 
                         WHERE id = $slot_id";
        
        if (mysqli_query($conn, $update_query)) {
            $success = "Slot updated successfully.";
            // Refresh slot data
            $slot_result = mysqli_query($conn, $slot_query);
            $slot = mysqli_fetch_assoc($slot_result);
        } else {
            $error = "Failed to update slot. Please try again.";
        }
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Edit Charging Slot</h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h2 class="text-2xl font-semibold mb-4"><?php echo htmlspecialchars($slot['station_name']); ?></h2>
        <p class="mb-4"><strong>Current Slot Number:</strong> <?php echo $slot['slot_number']; ?></p>
        <p class="mb-4"><strong>Current Price per Hour:</strong> Rs. <?php echo number_format($slot['price_per_hour'], 2); ?></p>
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
    <?php endif; ?>

    <form action="edit_slot.php?id=<?php echo $slot_id; ?>" method="POST" class="max-w-md mx-auto">
        <div class="mb-4">
            <label for="slot_number" class="block mb-2">Slot Number</label>
            <input type="number" id="slot_number" name="slot_number" value="<?php echo $slot['slot_number']; ?>" required class="w-full px-3 py-2 border rounded" min="1">
        </div>
        <div class="mb-4">
            <label for="price_per_hour" class="block mb-2">Price per Hour (Rs.)</label>
            <input type="number" id="price_per_hour" name="price_per_hour" value="<?php echo $slot['price_per_hour']; ?>" required class="w-full px-3 py-2 border rounded" min="0" step="0.01">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Slot</button>
    </form>

    <div class="mt-8 text-center">
        <a href="station_dashboard.php" class="text-blue-500 hover:underline">Back to Station Dashboard</a>
    </div>
</div>

<?php include 'footer.php'; ?>
