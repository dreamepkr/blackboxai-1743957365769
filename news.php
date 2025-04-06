<?php require_once 'config.php'; 
$conn = getDBConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kshetri News - Kshetri Samaj</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation (same as index.html) -->
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
                    <a href="news.php" class="py-4 px-2 text-red-600 border-b-4 border-red-600 font-semibold">News</a>
                    <a href="events.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Events</a>
                    <a href="gallery.html" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Gallery</a>
                    <a href="videos.html" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Videos</a>
                    <a href="membership.php" class="py-4 px-2 text-gray-500 font-semibold hover:text-red-600 transition duration-300">Membership</a>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <i class="fas fa-bars text-gray-500 text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- News Section -->
    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Kshetri Community News</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                $sql = "SELECT * FROM news ORDER BY date DESC LIMIT 6";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="bg-white rounded-lg overflow-hidden shadow-md news-card transition duration-300">';
                        echo '<div class="p-6">';
                        echo '<div class="text-red-600 text-sm mb-2">' . date("F j, Y", strtotime($row["date"])) . '</div>';
                        echo '<h3 class="text-xl font-bold mb-2">' . htmlspecialchars($row["title"]) . '</h3>';
                        echo '<p class="text-gray-700 mb-4">' . substr(htmlspecialchars($row["content"]), 0, 150) . '...</p>';
                        echo '<a href="#" class="text-red-600 font-semibold hover:underline">Read More</a>';
                        echo '</div></div>';
                    }
                } else {
                    echo '<div class="col-span-3 text-center py-12">';
                    echo '<i class="fas fa-newspaper text-5xl text-gray-300 mb-4"></i>';
                    echo '<p class="text-gray-500">No news articles found. Check back later!</p>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="mt-12 text-center">
                <a href="#" class="bg-red-600 text-white font-bold py-3 px-6 rounded-full hover:bg-red-700 transition duration-300 inline-block">
                    View All News
                </a>
            </div>
        </div>
    </section>

    <?php $conn->close(); ?>
    
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
        // Mobile menu toggle (same as index.html)
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>