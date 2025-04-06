<?php
session_start();
require_once '../config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Get stats for dashboard
$conn = getDBConnection();
$news_count = $conn->query("SELECT COUNT(*) FROM news")->fetch_row()[0];
$events_count = $conn->query("SELECT COUNT(*) FROM events")->fetch_row()[0];
$members_count = $conn->query("SELECT COUNT(*) FROM members")->fetch_row()[0];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kshetri Samaj</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .sidebar {
            transition: all 0.3s;
        }
        .sidebar-link:hover {
            background-color: rgba(220, 38, 38, 0.1);
        }
        .sidebar-link.active {
            background-color: rgba(220, 38, 38, 0.2);
            border-left: 4px solid #e53e3e;
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
                    <a href="dashboard.php" class="sidebar-link active flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
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
                    <a href="settings.php" class="sidebar-link flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
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
                    <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
                    <div class="text-sm text-gray-500">
                        <?php echo date('l, F j, Y'); ?>
                    </div>
                </div>
            </header>
            
            <main class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">News Articles</p>
                                <h3 class="text-2xl font-bold"><?php echo $news_count; ?></h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-newspaper text-red-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Upcoming Events</p>
                                <h3 class="text-2xl font-bold"><?php echo $events_count; ?></h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-red-600"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500">Registered Members</p>
                                <h3 class="text-2xl font-bold"><?php echo $members_count; ?></h3>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                <i class="fas fa-users text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Activity</h3>
                        <a href="#" class="text-sm text-red-600 hover:underline">View All</a>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                <i class="fas fa-newspaper text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">New article published</p>
                                <p class="text-sm text-gray-500">"Kshetri Cultural Festival 2023" was published</p>
                                <p class="text-xs text-gray-400">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                <i class="fas fa-user-plus text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">New member registered</p>
                                <p class="text-sm text-gray-500">Ram Bahadur Kshetri joined the community</p>
                                <p class="text-xs text-gray-400">5 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-plus text-gray-500"></i>
                            </div>
                            <div>
                                <p class="font-medium">New event added</p>
                                <p class="text-sm text-gray-500">"Annual General Meeting" scheduled for Nov 15</p>
                                <p class="text-xs text-gray-400">1 day ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Mobile menu toggle would be added here if needed
        // Currently the admin panel is designed for desktop use primarily
    </script>
</body>
</html>