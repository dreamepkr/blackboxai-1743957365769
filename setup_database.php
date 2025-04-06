<?php
require_once 'config.php';

// Read SQL file
$sql = file_get_contents('setup_database.sql');

// Execute SQL commands
$conn = getDBConnection();

if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    echo "Database setup completed successfully.";
} else {
    echo "Error setting up database: " . $conn->error;
}

$conn->close();
?>