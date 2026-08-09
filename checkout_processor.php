<?php
header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

// Mock database lookup array (In production, look this up from a 'products' MySQL table)
$mock_products_db = [
    101 => ['name' => 'Golden Windstone', 'price' => 129.00],
    102 => ['name' => 'Golden SkyStone', 'price' => 159.00]
];

$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
$cart_items = [];

// 1. Fetch Cart Items based on Auth Status
if ($user_id !== null) {
    // Logged-in user: Get data from DB
    $stmt = $pdo->prepare("SELECT product_id, quantity FROM user_carts WHERE user_id = ?");
    $stmt->execute([$user_id]);
    foreach ($stmt->fetchAll() as $row) {
        $cart_items[$row['product_id']] = (int)$row['quantity'];
    }
} else {
    // Guest user: Get data from Session
    $cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
}

if (empty($cart_items)) {
    echo json_encode(['success' => false, 'message' => 'Your shopping cart is empty.']);
    exit;
}

// 2. Validate and Calculate Definitive Total
$grand_total = 0.00;
$order_items_to_save = [];

foreach ($cart_items as $prod_id => $qty) {
    if (!array_key_exists($prod_id, $mock_products_db)) {
        echo json_encode(['success' => false, 'message' => 'Invalid product detected in cart.']);
        exit;
    }
    
    $price = $mock_products_db[$prod_id]['price'];
    $subtotal = $price * $qty;
    $grand_total += $subtotal;
    
    $order_items_to_save[] = [
        'product_id' => $prod_id,
        'quantity' => $qty,
        'price' => $price
    ];
}

// 3. Save to Database using a secure Transaction
try {
    $pdo->beginTransaction();

    // Insert Order Header Record
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'completed')");
    $stmt->execute([$user_id, $grand_total]);
    $order_id = $pdo->lastInsertId();

    // Insert All Line Items Mapping to Order ID
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
    foreach ($order_items_to_save as $item) {
        $stmt->execute([$order_id, $item['product_id'], $item['quantity'], $item['price']]);
    }

    // 4. Flush and Empty Cart Data Post-Purchase
    if ($user_id !== null) {
        $stmt = $pdo->prepare("DELETE FROM user_carts WHERE user_id = ?");
        $stmt->execute([$user_id]);
    } else {
        $_SESSION['cart'] = [];
    }

    // Commit changes safely to storage
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Checkout processed successfully.',
        'order_id' => $order_id
    ]);

} catch (\PDOException $e) {
    // Revert database states completely if anything crashes
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Critical checkout database processing error.']);
    exit;
}
