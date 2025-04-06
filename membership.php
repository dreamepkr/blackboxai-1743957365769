<?php
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill all required fields';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters';
    } else {
        // Check if email exists
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id FROM members WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = 'Email already registered';
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new member
            $insert = $conn->prepare("INSERT INTO members (name, email, phone, address, password_hash) VALUES (?, ?, ?, ?, ?)");
            $insert->bind_param("sssss", $name, $email, $phone, $address, $password_hash);
            
            if ($insert->execute()) {
                $success = 'Registration successful! Welcome to Kshetri Samaj.';
                // Clear form
                $name = $email = $phone = $address = '';
            } else {
                $error = 'Registration failed. Please try again.';
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Registration - Kshetri Samaj</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .form-input:focus {
            border-color: #e53e3e;
            box-shadow: 0 0 0 1px #e53e3e;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="index.html" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-500 text-lg">Kshetri Samaj</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-1">
                    <a href="index.html" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Home</a>
                    <a href="news.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">News</a>
                    <a href="events.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Events</a>
                    <a href="gallery.html" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Gallery</a>
                    <a href="videos.html" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Videos</a>
                    <a href="membership.php" class="py-4 px-2 text-red-600 border-b-4 border-red-600 font-semibold">Membership</a>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <i class="fas fa-bars text-gray-500 text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Registration Form -->
    <section class="py-16 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-red-600 py-4 px-6">
                    <h2 class="text-2xl font-bold text-white">Join Kshetri Samaj</h2>
                    <p class="text-red-100">Become part of our growing community</p>
                </div>
                
                <div class="p-6">
                    <?php if ($error): ?>
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p><?php echo $error; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p><?php echo $success; ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="membership.php">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-gray-700 font-medium mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" value="<?php echo isset($name) ? $name : ''; ?>" 
                                    class="w-full px-4 py-2 border rounded-lg form-input focus:outline-none focus:border-red-500" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-gray-700 font-medium mb-2">Email *</label>
                                <input type="email" id="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>"
                                    class="w-full px-4 py-2 border rounded-lg form-input focus:outline-none focus:border-red-500" required>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo isset($phone) ? $phone : ''; ?>"
                                    class="w-full px-4 py-2 border rounded-lg form-input focus:outline-none focus:border-red-500">
                            </div>
                            
                            <div>
                                <label for="password" class="block text-gray-700 font-medium mb-2">Password *</label>
                                <input type="password" id="password" name="password" 
                                    class="w-full px-4 py-2 border rounded-lg form-input focus:outline-none focus:border-red-500" required>
                                <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                            </div>
                            
                            <div>
                                <label for="address" class="block text-gray-700 font-medium mb-2">Address</label>
                                <textarea id="address" name="address" rows="2"
                                    class="w-full px-4 py-2 border rounded-lg form-input focus:outline-none focus:border-red-500"><?php echo isset($address) ? $address : ''; ?></textarea>
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm Password *</label>
                                <input type="password" id="confirm_password" name="confirm_password" 
                                    class="w-full px-4 py-2 border rounded-lg form-input focus:outline-none focus:border-red-500" required>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-red-700 transition duration-300">
                                Register Now
                            </button>
                        </div>
                        
                        <div class="mt-4 text-center text-sm text-gray-600">
                            <p>Already a member? <a href="#" class="text-red-600 hover:underline">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Kshetri Samaj</h3>
                    <p class="text-gray-300">Preserving Kshetri heritage and connecting our global community.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.html" class="text-gray-300 hover:text-white">Home</a></li>
                        <li><a href="news.php" class="text-gray-300 hover:text-white">News</a></li>
                        <li><a href="events.php" class="text-gray-300 hover:text-white">Events</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact</h3>
                    <p class="text-gray-300"><i class="fas fa-envelope mr-2"></i> info@khsetrisamaj.com</p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; <?php echo date("Y"); ?> Kshetri Samaj. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>