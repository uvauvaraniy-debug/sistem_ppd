<?php
// Script to add ic_number column to users table
include "config.php";

// Check if column exists first
$result = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'ic_number'");

if (mysqli_num_rows($result) > 0) {
    echo "✅ Column 'ic_number' already exists!<br>";
} else {
    // Add the column
    $sql = "ALTER TABLE users ADD COLUMN ic_number VARCHAR(20) DEFAULT NULL";
    if (mysqli_query($conn, $sql)) {
        echo "✅ Column 'ic_number' added successfully!<br>";
    } else {
        echo "❌ Error adding column: " . mysqli_error($conn) . "<br>";
    }
}

// Show current table structure
echo "<br><strong>Current table structure:</strong><br>";
$result = mysqli_query($conn, "DESCRIBE users");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "<br>";
}
?>