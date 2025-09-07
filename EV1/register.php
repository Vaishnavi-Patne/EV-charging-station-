<?php

// register.php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = sanitize($_POST['full_name']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username or email already exists";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, email, password, full_name) VALUES ('$username', '$email', '$hashed_password', '$full_name')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = $username;
                $_SESSION['user_role'] = 'user';
                redirect('index.php');
            } else {
                $error = "Registration failed. Please try again.";
            }
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
    background: url('background.jpg') no-repeat center center fixed; /* Add background image */
    background-size: cover; /* Ensure the image covers the entire page */
}
</style>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Register</h1>
    
    <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" class="max-w-md mx-auto">
        <div class="mb-4">
            <label for="username" class="block mb-2">Username</label>
            <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2">Email</label>
            <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="full_name" class="block mb-2">Full Name</label>
            <input type="text" id="full_name" name="full_name" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="password" class="block mb-2">Password</label>
            <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="block mb-2">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-3 py-2 border rounded">
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Register</button>
    </form>
    
    <p class="mt-4 text-center">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login here</a></p>
</div>

<?php include 'footer.php'; ?>
