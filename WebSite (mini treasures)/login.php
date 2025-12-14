<?php
session_start();
include 'database_connect/db_connect.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 
    $role = $_POST['login-role'];

    if ($role === 'admin') {
        if ($password === 'admin123') { 
            $_SESSION['mt_admin_auth'] = true;
            header("Location: admin.php");
            exit();
        } else {
            $error_msg = "Invalid Admin Password";
        }
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($password === $row['password']) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['full_name']; // Ensure your DB column is full_name or name
                $_SESSION['email'] = $row['email'];
                
                setcookie("mt_userEmail", $row['email'], time() + (86400 * 30), "/"); 
                
                header("Location: index.php");
                exit();
            } else {
                $error_msg = "Incorrect password.";
            }
        } else {
            $error_msg = "User not found. Please register.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini Treasures</title>
    <link rel="icon" type="image/x-icon" href="/static/favicon.ico">
    <script src="cdn/tailwind.js"></script>
    <script src="cdn/feather-unpkg.js"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="pictures/mtlogo.png" alt="Mini Treasures Logo">
                        <span class="ml-2 text-xl font-bold text-pink-500">Mini Treasures</span>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <a href="index.php" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Home</a>
                    <a href="register.php" class="ml-4 text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Create an account</a>
                    <a href="cart.php" class="ml-4 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none relative">
                        <i data-feather="shopping-cart"></i>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-pink-500 rounded-full"><span id="cart-count">0</span></span>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="ml-4 text-sm text-gray-700 font-bold">
                         Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                        </span>
                        <a href="logout.php" class="ml-3 px-3 py-1 text-sm bg-gray-100 rounded hover:bg-gray-200 text-gray-700">Logout</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div x-show="showModal" 
         x-transition:enter="modal-enter"
         x-transition:enter-active="modal-enter-active"
         x-transition:leave="modal-leave-active"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"> <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 mt-10 mx-auto relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Sign in to your account</h2>
                <a href="index.php" class="text-gray-400 hover:text-gray-500">
                    <i data-feather="x"></i>
                </a>
            </div>
            <form class="mt-8 space-y-6" action="login.php" method="POST">
    <?php 
        if($error_msg): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <?php echo $error_msg; ?>
            </div>
    <?php 
            endif; ?>
            
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md shadow-sm space-y-4">
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center text-sm">
                            <input type="radio" name="login-role" value="customer" checked class="mr-2"> Customer
                        </label>
                        <label class="inline-flex items-center text-sm">
                            <input type="radio" name="login-role" value="admin" class="mr-2"> Admin
                        </label>
                    </div>
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 focus:z-10 sm:text-sm" placeholder="Email address">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-pink-600 hover:text-pink-500">Forgot your password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition duration-150 ease-in-out">
                        Sign in
                    </button>
                </div>
            </form>
            
            <div class="relative mt-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or continue with</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 mt-6">
                <div>
                    <button type="button" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i data-feather="facebook" class="h-5 w-5"></i>
                    </button>
                </div>
                <div>
                    <button type="button" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i data-feather="twitter" class="h-5 w-5"></i>
                    </button>
                </div>
                <div>
                    <button type="button" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i data-feather="github" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="cdn/cart.js"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>