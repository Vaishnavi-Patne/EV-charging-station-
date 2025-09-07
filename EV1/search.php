<?php
// search.php
require_once 'config.php';

$search_query = isset($_GET['query']) ? sanitize($_GET['query']) : '';
$area = isset($_GET['area']) ? sanitize($_GET['area']) : '';

$sql = "SELECT * FROM ev_stations WHERE is_approved = 1";
if (!empty($search_query)) {
    $sql .= " AND (name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
}
if (!empty($area)) {
    $sql .= " AND area = '$area'";
}

$result = mysqli_query($conn, $sql);
$stations = mysqli_fetch_all($result, MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Search EV Charging Stations</h1>

    <form action="search.php" method="GET" class="mb-8">
        <div class="flex flex-wrap -mx-2 mb-4">
            <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
                <input type="text" name="query" placeholder="Search by name or description" value="<?php echo htmlspecialchars($search_query); ?>" class="w-full px-3 py-2 border rounded">
            </div>
            <div class="w-full md:w-1/2 px-2">
                <input type="text" name="area" placeholder="Filter by area" value="<?php echo htmlspecialchars($area); ?>" class="w-full px-3 py-2 border rounded">
            </div>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($stations as $station): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($station['name']); ?></h2>
                <p class="mb-2"><strong>Area:</strong> <?php echo htmlspecialchars($station['area']); ?></p>
                <p class="mb-4"><?php echo substr(htmlspecialchars($station['description']), 0, 100) . '...'; ?></p>
                <a href="station.php?id=<?php echo $station['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($stations)): ?>
        <p class="text-center mt-8">No stations found matching your search criteria.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
