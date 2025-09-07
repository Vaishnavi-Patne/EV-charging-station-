<?php
// login.php
require_once 'config.php';



if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['username'] == 'admin' ? 'admin' : ($user['username'] == 'station' ? 'station' : 'user');
            redirect('index.php');
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
}

include 'header.php';
?>
<style>
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: url('background.jpg') no-repeat center center fixed; /* Add background image */
    background-size: cover; /* Ensure the image covers the entire page */
    justify-content: center;
    align-items: center;
}
</style>


<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Login</h1>
    
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST" class="max-w-md mx-auto">
        <div class="mb-4">
            <label for="username" class="block mb-2">Username</label>
            <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="password" class="block mb-2">Password</label>
            <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Login</button>
    </form>
    
    <p class="mt-4 text-center">Don't have an account? <a href="register.php" class="text-blue-500 hover:underline">Register here</a></p>
</div>



