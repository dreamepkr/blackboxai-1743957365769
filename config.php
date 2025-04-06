<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'khsetri_db');

// Establish database connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

// Security functions
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Error handling
function displayError($message) {
    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">';
    echo '<p>' . $message . '</p>';
    echo '</div>';
}

// Success messages
function displaySuccess($message) {
    echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">';
    echo '<p>' . $message . '</p>';
    echo '</div>';
}
?>