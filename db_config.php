
<?php
// Secure Database Connection Configuration
$host    = 'localhost';
$db      = 'sazxjwte_cart_storage.user_carts';
$user    = 'sazxjwte_sugar';
$pass    = 'P^OVFTyQ@1IKVAhe';
$charset = 'utf8mb4';

// Data Source Name construction
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Critical security and performance driver configuration options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws exceptions on SQL errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Returns rows as clean associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Uses native prepared statements for security
];

try {
    // Create the global PDO instance
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Suppress system paths and throw a clean, generic error to the frontend
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed securely.']);
    exit;
}