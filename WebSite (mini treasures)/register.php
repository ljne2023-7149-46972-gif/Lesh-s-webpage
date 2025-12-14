<?php
include 'database_connect/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mini Treasures</title>
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
                    <a href="index.php" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Back to Home</a>
                    <a href="login.php" class="ml-4 text-gray-500 hover:text-gray-700 px-3 py-2 text-sm font-medium">Already have an account?</a>
                    <a href="cart.php" class="ml-4 bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none relative">
                        <i data-feather="shopping-cart"></i>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-pink-500 rounded-full"><span id="cart-count">0</span></span>
                    </a>
                    <span id="user-welcome" class="ml-4 text-sm text-gray-700 hidden"></span>
                    <button id="logout-btn" class="ml-3 px-3 py-1 text-sm bg-gray-100 rounded hidden">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Create your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Or <a href="login.php" class="font-medium text-pink-600 hover:text-pink-500">sign in to your account</a>
                </p>
            </div>
            <form class="mt-8 space-y-6" action="#" method="POST">
                <div class="rounded-md shadow-sm space-y-4">
                    <div>
                        <label for="full-name" class="sr-only">Full name</label>
                        <input id="full-name" name="name" type="text" autocomplete="name" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 focus:z-10 sm:text-sm" placeholder="Full name">
                    </div>
                    <div>
                        <label for="email-address" class="sr-only">Email address</label>
                        <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 focus:z-10 sm:text-sm" placeholder="Email address">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                    <div>
                        <label for="confirm-password" class="sr-only">Confirm Password</label>
                        <input id="confirm-password" name="confirm-password" type="password" autocomplete="new-password" required class="appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-pink-500 focus:border-pink-500 focus:z-10 sm:text-sm" placeholder="Confirm Password">
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded" required>
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the <a href="#" class="text-pink-600 hover:text-pink-500">Terms</a> and <a href="#" class="text-pink-600 hover:text-pink-500">Privacy Policy</a>
                    </label>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 transition duration-150 ease-in-out">
                        Register
                    </button>
                </div>
            </form>
            
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">Or sign up with</span>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
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

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();

        const form = document.querySelector('form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const name = document.getElementById('full-name').value.trim();
            const email = document.getElementById('email-address').value.trim().toLowerCase();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm-password').value;

            if (!name || !email || !password) {
                alert('Please fill in all required fields.');
                return;
            }

            if (password !== confirm) {
                alert('Passwords do not match.');
                return;
            }

            try {
                const response = await fetch('database_connect/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        name: name, 
                        email: email, 
                        password: password 
                    })
                });

                const result = await response.json();

                if (result.success) {
                    localStorage.setItem('mt_loggedIn', 'true');
                    localStorage.setItem('mt_userEmail', email);
                    
                    alert('Registration successful!');
                    window.location.href = 'index.php'; 
                } else {
                    alert(result.message); 
                }
            } catch (error) {
                console.error('Error:', error);
                alert('System error: Could not connect to the database.');
            }
        });
    });
</script>
</body>
</html>