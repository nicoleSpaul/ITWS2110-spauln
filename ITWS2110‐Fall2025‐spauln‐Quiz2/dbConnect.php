<?php
// Define database credentials
$DB_HOST = '';
$DB_PORT = '';
$DB_NAME = 'itws2110-fall2025-spauln-quiz2';
$DB_USER = ''; 
$DB_PASS = '';     

// Attempt to connect to the database
try {
    $dsn = "mysql:host={$DB_HOST};port={$DB_PORT};dbname={$DB_NAME}";
    
    $dbconn = new PDO($dsn, $DB_USER, $DB_PASS);
    
    // Set PDO to throw exceptions on errors (highly recommended for debugging)
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Stop execution if connection fails
    echo "<h1>Database Connection Error</h1>";
    echo "Error: " . $e->getMessage();
    exit(); 
}
