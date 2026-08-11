<?php
/*
 * Temporary debugging output.
 * Remove or comment these lines once the issue is resolved.
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
 * Start or resume the session so we can read/write cart data.
 * Return JSON content type because this script is an API endpoint.
 */
header('Content-Type: application/json');
session_start();
require_once 'db_config.php';

/*
 * Product metadata fallback for guest carts and total calculation.
 * In production, this should be replaced with a real products table lookup.
 */
$mock_products_db = [
    101 => ['name' => 'Golden Windstone', 'price' => 129.00, 'image' => 'images/chime-001.webp'],
    102 => ['name' => 'Golden SkyStone',  'price' => 159.00, 'image' => 'images/chime-002.webp']
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $mock_user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $cart_items = [];

    if ($mock_user_id !== null) {
        try {
            $stmt = $pdo->prepare('SELECT product_id, quantity, product_image FROM user_carts WHERE user_id = ?');
            $stmt->execute([$mock_user_id]);
            foreach ($stmt->fetchAll() as $row) {
                $cart_items[(int)$row['product_id']] = [
                    'quantity' => (int)$row['quantity'],
                    'product_image' => $row['product_image'] ?? null
                ];
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to fetch cart data from database.']);
            exit;
        }
    } else {
        $cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    $cart_count = 0;
    $cart_total = 0.00;
    $product_image = null;

    foreach ($cart_items as $id => $item) {
        $quantity = is_array($item) ? ($item['quantity'] ?? 0) : (int)$item;
        $cart_count += $quantity;
        $price = null;

        if (isset($item['product_image']) && $product_image === null) {
            $product_image = $item['product_image'];
        }

        if (isset($mock_products_db[$id]['price'])) {
            $price = $mock_products_db[$id]['price'];
        } elseif (isset($_SESSION['cart_meta'][$id]['price'])) {
            $price = (float)$_SESSION['cart_meta'][$id]['price'];
        }

        if ($price !== null) {
            $cart_total += $price * $quantity;
        }

        if ($product_image === null) {
            if (isset($_SESSION['cart_meta'][$id]['image'])) {
                $product_image = $_SESSION['cart_meta'][$id]['image'];
            } elseif (isset($mock_products_db[$id]['image'])) {
                $product_image = $mock_products_db[$id]['image'];
            }
        }
    }

    echo json_encode([
        'status' => 'success',
        'cart_count' => $cart_count,
        'cart_total' => round($cart_total, 2),
        'product_image' => $product_image,
        'cart_items' => $cart_items
    ]);
    exit;
}

try {
    /*
     * Read the raw POST body and decode it from JSON.
     * We expect `product_id` and `quantity` values from the client.
     */
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !isset($data['product_id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or missing JSON input variables']);
        exit;
    }

    /*
     * Normalize values to safe scalar types.
     */
    $product_id = (int)$data['product_id'];
    $requested_qty = (int)$data['quantity'];
    $image = isset($data['image']) ? trim($data['image']) : null;
    $price = isset($data['price']) ? (float)$data['price'] : null;
    $mock_user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

    /*
     * Persist cart changes.
     * If the user is logged in, use database storage. Otherwise use the session cart.
     */
    if ($mock_user_id !== null) {
        try {
            $sql = "INSERT INTO user_carts (user_id, product_id, product_image, quantity) 
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
        if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $requested_qty;
        } else {
            $_SESSION['cart'][$product_id] = $requested_qty;
        }

        if ($image !== null || $price !== null) {
            if (!isset($_SESSION['cart_meta']) || !is_array($_SESSION['cart_meta'])) {
                $_SESSION['cart_meta'] = [];
            }

            if (!isset($_SESSION['cart_meta'][$product_id]) || !is_array($_SESSION['cart_meta'][$product_id])) {
                $_SESSION['cart_meta'][$product_id] = [];
            }

            if ($image !== null) {
                $_SESSION['cart_meta'][$product_id]['image'] = $image;
            }
            if ($price !== null) {
                $_SESSION['cart_meta'][$product_id]['price'] = $price;
            }
        }
    }

    /*
     * Calculate cart summary totals for the response.
     */
    $cart_count = 0;
    $cart_total = 0.00;

    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    foreach ($_SESSION['cart'] as $id => $qty) {
        $price = null;

        if (isset($mock_products_db[$id]['price'])) {
            $price = $mock_products_db[$id]['price'];
        } elseif (isset($_SESSION['cart_meta'][$id]['price'])) {
            $price = (float)$_SESSION['cart_meta'][$id]['price'];
        }

        if ($price !== null) {
            $cart_count += $qty;
            $cart_total += $price * $qty;
        }
    }

    /*
     * Return the standard JSON success payload.
     */
    echo json_encode([
        'status' => 'success',
        'message' => 'Added to cart',
        'cart_count' => $cart_count,
        'cart_total' => round($cart_total, 2)
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
