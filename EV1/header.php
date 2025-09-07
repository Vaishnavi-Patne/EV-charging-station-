<?php
// header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EV Zone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: white;
            transition: width 0.3s ease-in-out;
        }
        .nav-link:hover::after { width: 100%; }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-blue-600 text-white shadow-md">
        <nav class="container mx-auto px-4 py-4 flex flex-wrap justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">EV Zone</a>
            <button id="mobile-menu-button" class="lg:hidden text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
            <ul id="nav-links" class="hidden lg:flex lg:space-x-4 w-full lg:w-auto mt-4 lg:mt-0">
                <li><a href="index.php" class="nav-link block py-2 hover:text-green-200">Home</a></li>
                <li><a href="search.php" class="nav-link block py-2 hover:text-green-200">Search</a></li>
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <li><a href="admin_dashboard.php" class="nav-link block py-2 hover:text-green-200">Admin Dashboard</a></li>
                    <?php elseif (isStation()): ?>
                        <li><a href="station_dashboard.php" class="nav-link block py-2 hover:text-green-200">Station Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="user_dashboard.php" class="nav-link block py-2 hover:text-green-200">My Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="logout.php" class="nav-link block py-2 hover:text-green-200">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-link block py-2 hover:text-green-200">Login</a></li>
                    <li><a href="register.php" class="nav-link block py-2 hover:text-green-200">Register</a></li>
                <?php endif; ?>

                <?php if (isLoggedIn()): ?>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $station_check_query = "SELECT id FROM ev_stations WHERE user_id = $user_id";
                    $station_check_result = mysqli_query($conn, $station_check_query);
                    $has_station = mysqli_num_rows($station_check_result) > 0;
                    ?>
                    <?php if ($has_station): ?>
                        <li><a href="station_dashboard.php" class="nav-link block py-2 hover:text-green-200">Station Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="register_station.php" class="nav-link block py-2 hover:text-green-200">Register Station</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            var navLinks = document.getElementById('nav-links');
            navLinks.classList.toggle('hidden');
        });
    </script>