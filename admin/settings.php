<?php
session_start();
require_once '../config.php';

$error = '';
$success = '';

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate current password
    $stmt = $conn->prepare("SELECT password_hash FROM admins WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if (!password_verify($current_password, $admin['password_hash'])) {
        $error = 'Current password is incorrect';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match';
    } elseif (strlen($new_password) < 8) {
        $error = 'New password must be at least 8 characters';
    } else {
        // Update password
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $update_stmt = $conn->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_password_hash, $_SESSION['admin_id']);
        $update_stmt->execute();
        $success = 'Password updated successfully!';
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings - Kshetri Samaj</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-white w-64 shadow-lg">
            <div class="p-4 border-b">
                <h1 class="text-xl font-bold text-gray-800">Kshetri Samaj</h1>
                <p class="text-sm text-gray-600">Admin Panel</p>
            </div>
            <div class="p-4">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-user text-red-600"></i>
                    </div>
                    <div>
                        <p class="font-medium"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
                        <p class="text-xs text-gray-500">Administrator</p>
                    </div>
                </div>
                
                <nav class="space-y-1">
                    <a href="dashboard.php" class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="news.php" class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
                        <i class="fas fa-newspaper w-5 text-center"></i>
                        <span>News</span>
                    </a>
                    <a href="events.php" class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
                        <i class="fas fa-calendar-alt w-5 text-center"></i>
                        <span>Events</span>
                    </a>
                    <a href="members.php" class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Members</span>
                    </a>
                    <a href="settings.php" class="sidebar-link active flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
                        <i class="fas fa-cog w-5 text-center"></i>
                        <span>Settings</span>
                    </a>
                    <a href="logout.php" class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">Admin Settings</h2>
                </div>
            </header>
            
            <main class="p-6">
                <!-- Success/Error Messages -->
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
                
                <!-- Password Change Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                    <form method="POST" action="settings.php">
                        <div class="mb-4">
                            <label for="current_password" class="block text-gray-700 font-medium mb-2">Current Password *</label>
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        
                        <div class="mb-4">
                            <label for="new_password" class="block text-gray-700 font-medium mb-2">New Password *</label>
                            <input type="password" id="new_password" name="new_password" required
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="block text-gray-700 font-medium mb-2">Confirm New Password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" required
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        </div>
                        
                        <button type="submit" 
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            Update Password
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>