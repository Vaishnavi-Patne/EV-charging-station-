<?php
// manage_stations.php
require_once 'config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('login.php');
}

$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

$stations_query = "SELECT * FROM ev_stations";
if (!empty($search)) {
    $stations_query .= " WHERE name LIKE '%$search%' OR area LIKE '%$search%' OR address LIKE '%$search%'";
}
$stations_query .= " ORDER BY id DESC";

$stations_result = mysqli_query($conn, $stations_query);
$stations = mysqli_fetch_all($stations_result, MYSQLI_ASSOC);

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Manage EV Charging Stations</h1>

    <form action="manage_stations.php" method="GET" class="mb-8">
        <div class="flex">
            <input type="text" name="search" placeholder="Search stations..." value="<?php echo htmlspecialchars($search); ?>" class="flex-grow px-3 py-2 border rounded-l">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600">Search</button>
        </div>
    </form>

    <div class="mb-4">
        <a href="add_station.php" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Add New Station</a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Area</th>
                    <th class="py-3 px-6 text-left">Address</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <?php foreach ($stations as $station): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left"><?php echo $station['id']; ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($station['name']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($station['area']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($station['address']); ?></td>
                        <td class="py-3 px-6 text-left"><?php echo $station['is_approved'] ? 'Approved' : 'Pending'; ?></td>
                        <td class="py-3 px-6 text-left">
                            <a href="edit_station.php?id=<?php echo $station['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
                            <a href="delete_station.php?id=<?php echo $station['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-2" onclick="return confirm('Are you sure you want to delete this station?')">Delete</a>
                            <?php if (!$station['is_approved']): ?>
                                <a href="approve_station.php?id=<?php echo $station['id']; ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 ml-2">Approve</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
