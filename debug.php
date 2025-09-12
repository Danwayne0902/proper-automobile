<?php
require_once 'vendor/autoload.php';

$db = new SQLite3(database_path('database.sqlite'));
$results = $db->query("PRAGMA table_info(bookings)");

echo "Bookings table columns:\n";
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    echo "- " . $row['name'] . " (" . $row['type'] . ")\n";
}

echo "\nTrying to create a test booking...\n";

// Try to insert a test booking
$stmt = $db->prepare("INSERT INTO bookings (user_id, automobile_id, type, scheduled_at, preferred_time, status) VALUES (:user_id, :automobile_id, :type, :scheduled_at, :preferred_time, :status)");
$stmt->bindValue(':user_id', 1, SQLITE3_INTEGER);
$stmt->bindValue(':automobile_id', 1, SQLITE3_INTEGER);
$stmt->bindValue(':type', 'test_drive', SQLITE3_TEXT);
$stmt->bindValue(':scheduled_at', '2025-09-15 10:00:00', SQLITE3_TEXT);
$stmt->bindValue(':preferred_time', 'Morning (9:00 AM - 12:00 PM)', SQLITE3_TEXT);
$stmt->bindValue(':status', 'pending', SQLITE3_TEXT);

$result = $stmt->execute();
if ($result) {
    echo "Booking created successfully!\n";
} else {
    echo "Error creating booking: " . $db->lastErrorMsg() . "\n";
}
