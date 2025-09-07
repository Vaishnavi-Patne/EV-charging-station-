<?php
// register_station.php
require_once 'config.php';

// Ensure the user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $area = sanitize($_POST['area']);
    $address = sanitize($_POST['address']);
    $directions_link = sanitize($_POST['directions_link']);
    $description = sanitize($_POST['description']);
    $user_id = $_SESSION['user_id'];

    // Check if the user already has a registered station
    $check_query = "SELECT * FROM ev_stations WHERE user_id = $user_id";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "You have already registered a station. You can only register one station per account.";
    } else {
        $insert_query = "INSERT INTO ev_stations (name, area, address, directions_link, description, user_id, is_approved) 
                         VALUES ('$name', '$area', '$address', '$directions_link', '$description', $user_id, 0)";
        
        if (mysqli_query($conn, $insert_query)) {
            $success = "Your station has been registered successfully and is pending approval.";
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}

include 'header.php';
?>
<style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: url('back.jpg') no-repeat center center fixed; /* Add background image */
    background-size: cover; /* Ensure the image covers the entire page */
}
</style>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Register Your EV Charging Station</h1>

    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $success; ?></p>
        </div>
    <?php else: ?>
        <form action="register_station.php" method="POST" class="max-w-lg mx-auto">
            <div class="mb-4">
                <label for="name" class="block mb-2">Station Name</label>
                <input type="text" id="name" name="name" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="area" class="block mb-2">Area</label>
                <input type="text" id="area" name="area" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="address" class="block mb-2">Address</label>
                <input type="text" id="address" name="address" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="directions_link" class="block mb-2">Directions Link (Google Maps URL)</label>
                <input type="url" id="directions_link" name="directions_link" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block mb-2">Description</label>
                <textarea id="description" name="description" required class="w-full px-3 py-2 border rounded" rows="4"></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Register Station</button>
        </form>
    <?php endif; ?>

    <div class="mt-8 text-center">
        <a href="index.php" class="text-blue-500 hover:underline">Back to Home</a>
    </div>
</div>

<?php include 'footer.php'; ?>
