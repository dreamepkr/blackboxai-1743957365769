<?php
session_start();
require_once '../config.php';

// Redirect to login if not authenticated
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$conn = getDBConnection();

// Handle delete request
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: news.php?deleted=1');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = sanitizeInput($_POST['title']);
    $content = sanitizeInput($_POST['content']);
    $image_url = sanitizeInput($_POST['image_url']);

    if ($id > 0) {
        // Update existing news
        $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $image_url, $id);
    } else {
        // Create new news
        $stmt = $conn->prepare("INSERT INTO news (title, content, image_url) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $image_url);
    }
    $stmt->execute();
    header('Location: news.php?saved=1');
    exit();
}

// Get all news articles
$news = $conn->query("SELECT * FROM news ORDER BY date DESC");
$editing_news = null;

// Check if we're editing a news item
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $result = $conn->query("SELECT * FROM news WHERE id = $id");
    $editing_news = $result->fetch_assoc();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News - Kshetri Samaj Admin</title>
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
                    <a href="news.php" class="sidebar-link active flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-700">
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
                    <h2 class="text-xl font-semibold text-gray-800">Manage News</h2>
                    <button onclick="document.getElementById('news-form').classList.toggle('hidden')" 
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-plus mr-2"></i>Add News
                    </button>
                </div>
            </header>
            
            <main class="p-6">
                <!-- Success/Error Messages -->
                <?php if (isset($_GET['saved'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>News article saved successfully!</p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        <p>News article deleted successfully!</p>
                    </div>
                <?php endif; ?>
                
                <!-- News Form -->
                <div id="news-form" class="bg-white rounded-lg shadow p-6 mb-6 <?php echo $editing_news ? '' : 'hidden'; ?>">
                    <h3 class="text-lg font-semibold mb-4"><?php echo $editing_news ? 'Edit News' : 'Add New News'; ?></h3>
                    <form method="POST" action="news.php">
                        <input type="hidden" name="id" value="<?php echo $editing_news ? $editing_news['id'] : ''; ?>">
                        
                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 font-medium mb-2">Title *</label>
                            <input type="text" id="title" name="title" required
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                   value="<?php echo $editing_news ? htmlspecialchars($editing_news['title']) : ''; ?>">
                        </div>
                        
                        <div class="mb-4">
                            <label for="image_url" class="block text-gray-700 font-medium mb-2">Image URL</label>
                            <input type="url" id="image_url" name="image_url"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                                   value="<?php echo $editing_news ? htmlspecialchars($editing_news['image_url']) : ''; ?>">
                        </div>
                        
                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 font-medium mb-2">Content *</label>
                            <textarea id="content" name="content" rows="6" required
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"><?php echo $editing_news ? htmlspecialchars($editing_news['content']) : ''; ?></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="document.getElementById('news-form').classList.add('hidden')" 
                                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                Save News
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- News List -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($news->num_rows > 0): ?>
                                    <?php while($item = $news->fetch_assoc()): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-medium text-gray-900"><?php echo htmlspecialchars($item['title']); ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo date('M j, Y', strtotime($item['date'])); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="news.php?edit=<?php echo $item['id']; ?>" class="text-red-600 hover:text-red-900 mr-3">Edit</a>
                                                <a href="news.php?delete=<?php echo $item['id']; ?>" class="text-red-600 hover:text-red-900" 
                                                   onclick="return confirm('Are you sure you want to delete this news article?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No news articles found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle form visibility when editing
        <?php if ($editing_news): ?>
            document.getElementById('news-form').scrollIntoView({ behavior: 'smooth' });
        <?php endif; ?>
    </script>
</body>
</html>