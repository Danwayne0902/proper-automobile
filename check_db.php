<?php
try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=3306;dbname=proper_automobile;charset=utf8mb4",
        "root",
        "12345",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Database connection successful!\n";

    // Try to create a simple table to test
    $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(255))");
    echo "Test table created successfully!\n";

} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
