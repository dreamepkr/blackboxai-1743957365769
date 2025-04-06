<?php require_once 'config.php'; 
$conn = getDBConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kshetri Events - Kshetri Samaj</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
                    <a href="events.php" class="py-4 px-2 text-red-600 border-b-4 border-red-600 font-semibold">Events</a>
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

    <!-- Events Section -->
    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Upcoming Kshetri Events</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <?php
                $sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="bg-white rounded-lg overflow-hidden shadow-md event-card transition duration-300">';
                        echo '<div class="p-6">';
                        echo '<div class="flex justify-between items-start mb-2">';
                        echo '<div class="text-red-600 font-semibold">' . date("F j, Y", strtotime($row["event_date"])) . '</div>';
                        echo '<span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">' . date("g:i a", strtotime($row["event_date"])) . '</span>';
                        echo '</div>';
                        echo '<h3 class="text-xl font-bold mb-2">' . htmlspecialchars($row["title"]) . '</h3>';
                        echo '<p class="text-gray-600 mb-2"><i class="fas fa-map-marker-alt text-red-600 mr-2"></i>' . htmlspecialchars($row["location"]) . '</p>';
                        echo '<p class="text-gray-700 mb-4">' . htmlspecialchars($row["description"]) . '</p>';
                        echo '<a href="#" class="text-red-600 font-semibold hover:underline">More Details</a>';
                        echo '</div></div>';
                    }
                } else {
                    echo '<div class="col-span-2 text-center py-12">';
                    echo '<i class="fas fa-calendar-alt text-5xl text-gray-300 mb-4"></i>';
                    echo '<p class="text-gray-500">No upcoming events found. Check back later!</p>';
                    echo '</div>';
                }
                ?>
            </div>

            <div class="mt-12">
                <h3 class="text-2xl font-bold mb-6">Past Events</h3>
                <div class="space-y-4">
                    <?php
                    $sql = "SELECT * FROM events WHERE event_date < CURDATE() ORDER BY event_date DESC LIMIT 5";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="bg-gray-50 p-4 rounded-lg">';
                            echo '<div class="flex items-center">';
                            echo '<div class="text-red-600 font-medium w-32">' . date("M j, Y", strtotime($row["event_date"])) . '</div>';
                            echo '<div class="flex-1">';
                            echo '<h4 class="font-semibold">' . htmlspecialchars($row["title"]) . '</h4>';
                            echo '<p class="text-gray-600 text-sm">' . htmlspecialchars($row["location"]) . '</p>';
                            echo '</div></div></div>';
                        }
                    }
                    ?>
                </div>
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
        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>