<?php
// index.php
require_once 'config.php';

// Fetch some featured EV stations
$query = "SELECT * FROM ev_stations WHERE is_approved = 1 LIMIT 3";
$result = mysqli_query($conn, $query);
$featured_stations = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Include header
include 'header.php';
?>

<!-- Add custom CSS for animations -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes slideIn {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-fadeIn {
        animation: fadeIn 1s ease-out;
    }
    .animate-slideIn {
        animation: slideIn 0.5s ease-out;
    }
</style>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-5xl font-bold mb-8 text-center text-blue-600 animate-fadeIn">Welcome to EV Zone</h1>
    
    <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-8 rounded-lg mb-12 shadow-lg animate-slideIn">
        <h2 class="text-3xl font-semibold mb-4 text-white">Find and Book EV Charging Stations</h2>
        <p class="mb-6 text-white text-lg">Easily locate and reserve charging slots for your electric vehicle. Join the green revolution today!</p>
        <a href="search.php" class="bg-white text-blue-600 px-6 py-3 rounded-full font-bold hover:bg-blue-100 transition duration-300">Search Stations</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-12">
        <div class="bg-white p-6 rounded-lg shadow-md animate-slideIn" style="animation-delay: 0.2s;">
            <h3 class="text-2xl font-semibold mb-4 text-blue-600">Why Choose EV Zone?</h3>
            <ul class="list-disc list-inside space-y-2">
                <li>Wide network of charging stations</li>
                <li>Easy booking and payment process</li>
                <li>24/7 customer support</li>
                <li>Eco-friendly transportation solution</li>
            </ul>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md animate-slideIn" style="animation-delay: 0.4s;">
            <h3 class="text-2xl font-semibold mb-4 text-blue-600">How It Works</h3>
            <ol class="list-decimal list-inside space-y-2">
                <li>Search for nearby charging stations</li>
                <li>Select your preferred time slot</li>
                <li>Book and pay securely online</li>
                <li>Charge your EV hassle-free</li>
            </ol>
        </div>
    </div>

    <h2 class="text-3xl font-semibold mb-6 text-center text-blue-600 animate-fadeIn">Featured EV Stations</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php foreach ($featured_stations as $index => $station): ?>
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 animate-slideIn" style="animation-delay: <?php echo (0.6 + $index * 0.2); ?>s;">
                <h3 class="text-xl font-semibold mb-2 text-blue-600"><?php echo htmlspecialchars($station['name']); ?></h3>
                <p class="mb-2 text-gray-600"><?php echo htmlspecialchars($station['area']); ?></p>
                <p class="mb-4 text-sm text-gray-500"><?php echo substr(htmlspecialchars($station['description']), 0, 100) . '...'; ?></p>
                <a href="station.php?id=<?php echo $station['id']; ?>" class="text-blue-500 hover:underline font-semibold">View Details →</a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-12 bg-gray-100 p-8 rounded-lg animate-fadeIn">
        <h2 class="text-3xl font-semibold mb-6 text-center text-blue-600">Join the EV Revolution</h2>
        <p class="text-center text-lg mb-6">Be part of the sustainable future. Register your EV or charging station today!</p>
        <div class="flex justify-center space-x-4">
            <a href="register.php" class="bg-blue-600 text-white px-6 py-3 rounded-full font-bold hover:bg-blue-700 transition duration-300">Register as User</a>
            <a href="register_station.php" class="bg-green-600 text-white px-6 py-3 rounded-full font-bold hover:bg-green-700 transition duration-300">Register Your Station</a>
        </div>
    </div>
</div>
<section class="text-gray-600 body-font">
  <div class="container px-5 py-24 mx-auto flex flex-wrap">
    <div class="flex w-full mb-20 flex-wrap">
      <h1 class="sm:text-3xl text-2xl font-medium title-font text-gray-900 lg:w-1/3 lg:mb-0 mb-4">Electronic Vehicle </h1>
      <p class="lg:pl-6 lg:w-2/3 mx-auto leading-relaxed text-base">An electric vehicle (EV) is a type of automobile that is powered entirely or primarily by electricity rather than traditional internal
         combustion engines that run on gasoline or diesel. EVs use electric motors and rechargeable battery packs to drive the vehicle</p>
    </div>
    <div class="flex flex-wrap md:-m-2 -m-1">
      <div class="flex flex-wrap w-1/2">
        <div class="md:p-2 p-1 w-1/2">
          <img alt="gallery" class="w-full object-cover h-full object-center block" src="car2.jpg">
        </div>
        <div class="md:p-2 p-1 w-1/2">
          <img alt="gallery" class="w-full object-cover h-full object-center block" src="car9.webp">
        </div>
        <div class="md:p-2 p-1 w-full">
          <img alt="gallery" class="w-full h-full object-cover object-center block" src="Kona-1.avif">
        </div>
      </div>
      <div class="flex flex-wrap w-1/2">
        <div class="md:p-2 p-1 w-full">
          <img alt="gallery" class="w-full h-full object-cover object-center block" src="ev1.jpg">
        </div>
        <div class="md:p-2 p-1 w-1/2">
          <img alt="gallery" class="w-full object-cover h-full object-center block" src="image8.jpg">
        </div>
        <div class="md:p-2 p-1 w-1/2">
          <img alt="gallery" class="w-full object-cover h-full object-center block" src="car3.webp">
        </div>
      </div>
    </div>
  </div>
</section>

<?php
// Include footer
include 'footer.php';

?>