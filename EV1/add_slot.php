<?php
// add_slot.php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Fetch the user's station
$station_query = "SELECT * FROM ev_stations WHERE user_id = $user_id";
$station_result = mysqli_query($conn, $station_query);
$station = mysqli_fetch_assoc($station_result);

if (!$station) {
    redirect('station_dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot_number = sanitize($_POST['slot_number']);
    $price_per_hour = sanitize($_POST['price_per_hour']);
    $is_booked = isset($_POST['is_booked']) ? 1 : 0; // Check if the slot is marked as booked

    // Check if slot number already exists for this station
    $check_query = "SELECT * FROM slots WHERE station_id = {$station['id']} AND slot_number = $slot_number";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "A slot with this number already exists for your station.";
    } else {
        $insert_query = "INSERT INTO slots (station_id, slot_number, price_per_hour, is_booked) 
                         VALUES ({$station['id']}, $slot_number, $price_per_hour, $is_booked)";
        
        if (mysqli_query($conn, $insert_query)) {
            $success = "Slot added successfully.";
        } else {
            $error = "solt is allready.";
        }
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Add New Charging Slot</h1>

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

    <form action="add_slot.php" method="POST" class="max-w-md mx-auto">
        <div class="mb-4">
            <label for="slot_number" class="block mb-2">Slot Number</label>
            <input type="number" id="slot_number" name="slot_number" required class="w-full px-3 py-2 border rounded" min="1">
        </div>
        <div class="mb-4">
            <label for="price_per_hour" class="block mb-2">Price per Hour ($)</label>
            <input type="number" id="price_per_hour" name="price_per_hour" required class="w-full px-3 py-2 border rounded" min="0" step="0.01">
        </div>
        <div class="mb-4">
            <label for="is_booked" class="block mb-2">Is Booked?</label>
            <input type="checkbox" id="is_booked" name="is_booked" value="1" class="w-full">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Slot</button>
    </form>

    <div class="mt-8 text-center">
        <a href="station_dashboard.php" class="text-blue-500 hover:underline">Back to Station Dashboard</a>
    </div>
</div>

<?php include 'footer.php'; ?>
