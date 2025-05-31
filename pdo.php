<?php
$host = 'localhost';        // or 127.0.0.1
$db   = 'portfolio_gestion';    // replace with your DB name
$user = 'root';    // replace with your DB username
$pass = '';    // replace with your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
?>
