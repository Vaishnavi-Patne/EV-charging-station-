<?php
// station.php
require_once 'config.php';

if (!isset($_GET['id'])) {
    redirect('search.php');
}

$station_id = sanitize($_GET['id']);
$query = "SELECT * FROM ev_stations WHERE id = $station_id AND is_approved = 1";
$result = mysqli_query($conn, $query);
$station = mysqli_fetch_assoc($result);

if (!$station) {
    redirect('search.php');
}

$slots_query = "SELECT * FROM slots WHERE station_id = $station_id";
$slots_result = mysqli_query($conn, $slots_query);
$slots = mysqli_fetch_all($slots_result, MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8"><?php echo htmlspecialchars($station['name']); ?></h1>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <p class="mb-2"><strong>Address:</strong> <?php echo htmlspecialchars($station['address']); ?></p>
        <p class="mb-2"><strong>Area:</strong> <?php echo htmlspecialchars($station['area']); ?></p>
        <p class="mb-4"><strong>Description:</strong> <?php echo htmlspecialchars($station['description']); ?></p>
        <a href="<?php echo htmlspecialchars($station['directions_link']); ?>" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Get Directions</a>
    </div>

    <h2 class="text-2xl font-semibold mb-4">Available Slots</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($slots as $slot): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Slot #<?php echo $slot['slot_number']; ?></h3>
                <p class="mb-4"><strong>Price per hour:</strong> Rs. <?php echo number_format($slot['price_per_hour'], 2); ?></p>
                <?php if (isLoggedIn()): ?>
                    <a href="book_slot.php?slot_id=<?php echo $slot['id']; ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Book Slot</a>
                <?php else: ?>
                    <p class="text-gray-500">Please <a href="login.php" class="text-blue-500 hover:underline">login</a> to book a slot.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
