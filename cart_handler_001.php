<?php
// Force clean JSON responses
header('Content-Type: application/json');
try {
    // 1. Get the raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        throw new Exception('Invalid JSON input');
    }

    $productId = $data['product_id'] ?? null;
    $quantity = $data['quantity'] ?? null;

   session_start();

// Require the secure database configuration file
require_once 'db_config.php';

// Mock variables representing the current request context
$product_id = (int)$data['product_id'];
$requested_qty = (int)$data['quantity'];
$mock_user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// DATA PERSISTENCE LAYER: Secure Routing Path
if ($mock_user_id !== null) {
    
    // USER LOGGED IN: Upsert cart item directly into the MySQL database
    try {
        $sql = "INSERT INTO user_carts (user_id, product_id, quantity) 
                VALUES (:user_id, :product_id, :quantity) 
                ON DUPLICATE KEY UPDATE quantity = quantity + :quantity_increment";
        
        // Prepare query statement
        $stmt = $pdo->prepare($sql);
        
        // Execute with safe bound parameter arrays
        $stmt->execute([
            'user_id'            => $mock_user_id,
            'product_id'         => $product_id,
            'quantity'           => $requested_qty,
            'quantity_increment' => $requested_qty
        ]);
        
    } catch (\PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database storage error occurred.']);
        exit;
    }

} else {
    
    // GUEST USER: Fall back to localized PHP Browser Session storage
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $requested_qty;
    } else {
        $_SESSION['cart'][$product_id] = $requested_qty;
    }
}

// ... (Compute totals and echo your standard json_encode response)

$cart_count = 0;
$cart_total = 0.00;

foreach ($_SESSION['cart'] as $id => $qty) {
    if (isset($mock_products_db[$id])) {
        $cart_count += $qty;
        $cart_total += ($mock_products_db[$id]['price'] * $qty);
    }
}

// Send standard successful response format back to client UI
echo json_encode([
    'success' => true,
    'cart_count' => $cart_count,
    'cart_total' => $cart_total
]);

    echo json_encode(['status' => 'success', 'message' => 'Added to cart']);

} catch (Exception $e) {
    // 3. Return a valid JSON error instead of letting the script crash
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}


