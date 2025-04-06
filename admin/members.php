<?php
session_start();
require_once '../config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Handle member verification
if (isset($_GET['verify'])) {
    $id = (int)$_GET['verify'];
    $stmt = $conn->prepare("UPDATE members SET is_verified = TRUE WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: members.php?verified=1');
    exit();
}

// Handle member deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: members.php?deleted=1');
    exit();
}

// Get all members
$members = $conn->query("SELECT * FROM members ORDER BY registration_date DESC");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members - Kshetri Samaj Admin</title>
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
                    <a href="members.php" class="sidebar-link active flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
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
                    <h2 class="text-xl font-semibold text-gray-800">Manage Members</h2>
                    <div class="text-sm text-gray-500">
                        <?php echo $members->num_rows; ?> registered members
                    </div>
                </div>
            </header>
            
            <main class="p-6">
                <!-- Success Messages -->
                <?php if (isset($_GET['verified'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>Member verified successfully!</p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>Member deleted successfully!</p>
                    </div>
                <?php endif; ?>
                
                <!-- Members List -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($members->num_rows > 0): ?>
                                    <?php while($member = $members->fetch_assoc()): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($member['name']); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($member['phone']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo htmlspecialchars($member['email']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php echo $member['is_verified'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                    <?php echo $member['is_verified'] ? 'Verified' : 'Pending'; ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($member['registration_date'])); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <?php if (!$member['is_verified']): ?>
                                                    <a href="members.php?verify=<?php echo $member['id']; ?>" class="text-green-600 hover:text-green-900 mr-3">Verify</a>
                                                <?php endif; ?>
                                                <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>" class="text-blue-600 hover:text-blue-900 mr-3">Email</a>
                                                <a href="members.php?delete=<?php echo $member['id']; ?>" class="text-red-600 hover:text-red-900" 
                                                   onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No members found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>