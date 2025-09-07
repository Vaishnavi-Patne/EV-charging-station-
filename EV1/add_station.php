<?php
// add_station.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
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
    $is_approved = isset($_POST['is_approved']) ? 1 : 0;

    $insert_query = "INSERT INTO ev_stations (name, area, address, directions_link, description, is_approved) 
                     VALUES ('$name', '$area', '$address', '$directions_link', '$description', $is_approved)";

    if (mysqli_query($conn, $insert_query)) {
        $success = "Station added successfully.";
    } else {
        $error = "Failed to add station. Please try again.";
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Add EV Charging Station</h1>

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

    <form action="add_station.php" method="POST" class="max-w-md mx-auto">
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
            <label for="directions_link" class="block mb-2">Directions Link</label>
            <input type="url" id="directions_link" name="directions_link" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="description" class="block mb-2">Description</label>
            <textarea id="description" name="description" required class="w-full px-3 py-2 border rounded" rows="4"></textarea>
        </div>
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="is_approved" class="mr-2">
                <span>Approve Station</span>
            </label>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Station</button>
    </form>

    <div class="mt-8 text-center">
        <a href="manage_stations.php" class="text-blue-500 hover:underline">Back to Manage Stations</a>
    </div>
</div>

<?php include 'footer.php'; ?>
