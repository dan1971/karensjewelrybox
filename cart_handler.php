<?php
// 1. Start session and set JSON headers immediately at the absolute top
session_start();
header('Content-Type: application/json');

try {
    // 2. Get the raw POST data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !isset($data['product_id']) || !isset($data['quantity'])) {
        // Set bad request status code
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing JSON input variables']);
        exit;
    }

    // Require the secure database configuration file
    require_once 'db_config.php';

    // Typecast safely
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
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'user_id'            => $mock_user_id,
                'product_id'         => $product_id,
                'quantity'           => $requested_qty,
                'quantity_increment' => $requested_qty
            ]);
            
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database storage error occurred.']);
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

    // Compute totals
    $cart_count = 0;
    $cart_total = 0.00;

    // Ensure cart array initialization to prevent foreach loops crashing on empty sessions
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    foreach ($_SESSION['cart'] as $id => $qty) {
        // NOTE: Make sure $mock_products_db is defined inside your 'db_config.php'
        if (isset($mock_products_db[$id])) {
            $cart_count += $qty;
            $cart_total += ($mock_products_db[$id]['price'] * $qty);
        }
    }

    // 3. COMBINE RESPONSES INTO ONE VALID JSON PAYLOAD
    echo json_encode([
        'status' => 'success',
        'message' => 'Added to cart',
        'cart_count' => $cart_count,
        'cart_total' => $cart_total
    ]);
    exit; // Stop execution immediately

} catch (Exception $e) {
    // Return a valid single JSON error structure instead of letting the script crash
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
