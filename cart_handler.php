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

// Temporary test user for development/testing
$_SESSION['user_id'] = 21;

require_once 'db_config.php';

/*
 * Use a real `products` table for product metadata (id, name, price, image).
 * The application expects a `products` table with at least: `id`, `name`, `price`, `image`.
 */

// >>>>>>>>>>>>> DELETE FUNCTIONALITY <<<<<<<<<<<<<<

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $product_id = isset($input['product_id']) ? (int)$input['product_id'] : null;
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    if (!$product_id) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing product_id for DELETE']);
        exit;
    }
    if ($user_id !== null) {
        try {
            $del = $pdo->prepare('DELETE FROM user_carts WHERE userId = ? AND productId = ?');
            $del->execute([$user_id, $product_id]);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to remove item from database.', 'details' => $e->getMessage()]);
            exit;
        }
    } else {
        if (isset($_SESSION['cart'][$product_id])) unset($_SESSION['cart'][$product_id]);
        if (isset($_SESSION['cart_meta'][$product_id])) unset($_SESSION['cart_meta'][$product_id]);
    }
    // Return updated summary (reuse same logic as GET)
    $user = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $cart_items = [];
    if ($user !== null) {
        try {
            $stmt = $pdo->prepare('SELECT productId, productQuantity FROM user_carts WHERE userId = ?');
            $stmt->execute([$user]);
            foreach ($stmt->fetchAll() as $row) {
                $cart_items[(int)$row['productId']] = (int)$row['productQuantity'];
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to fetch cart data after remove.', 'details' => $e->getMessage()]);
            exit;
        }
    } else {
        $cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }
    $cart_count = 0;
    $cart_total = 0.00;
    $product_image = null;
    $products = [];
    if (!empty($cart_items)) {
        $ids = array_keys($cart_items);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        try {
            $stmt = $pdo->prepare("SELECT InquiryID, name, price, image FROM products_chimes WHERE InquiryID IN ($placeholders)");
            $stmt->execute($ids);
            foreach ($stmt->fetchAll() as $p) $products[(int)$p['id']] = $p;
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to lookup products after remove.']);
            exit;
        }
        foreach ($cart_items as $id => $qty) {
            $qty = (int)$qty;
            $cart_count += $qty;
            if (isset($products[$id])) {
                $cart_total += (float)$products[$id]['price'] * $qty;
                if ($product_image === null && !empty($products[$id]['image'])) $product_image = $products[$id]['image'];
            } elseif (isset($_SESSION['cart_meta'][$id]['price'])) {
                $cart_total += (float)$_SESSION['cart_meta'][$id]['price'] * $qty;
                if ($product_image === null && isset($_SESSION['cart_meta'][$id]['image'])) $product_image = $_SESSION['cart_meta'][$id]['image'];
            }
        }
    }
    echo json_encode(['status'=>'success','message'=>'Removed','cart_count'=>$cart_count,'cart_total'=>round($cart_total,2),'product_image'=>$product_image,'cart_items'=>$cart_items,'products'=>$products]);
    exit;
}
// >>>>>>>>>>>>> END DELETE FUNCTIONALITY <<<<<<<<<<<<<<


// >>>>>>> GET CART FROM db / FROM SESSION <<<<<<<<
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    $cart_items = [];

    // Load cart items from DB for logged users or from session for guests
    if ($user_id !== null) {
        try {
            $stmt = $pdo->prepare('SELECT productId, productQuantity FROM user_carts WHERE userId = ?');
            $stmt->execute([$user_id]);
            foreach ($stmt->fetchAll() as $row) {
                $cart_items[(int)$row['productId']] = (int)$row['productQuantity'];
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to fetch cart data from database.', 'details' => $e->getMessage()]);
            exit;
        }
    } else {
        $cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    // If there are no items, return empty summary
    $cart_count = 0;
    $cart_total = 0.00;
    $product_image = null;

    if (!empty($cart_items)) {
        $productIds = array_keys($cart_items);
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));

        try {
            $stmt = $pdo->prepare("SELECT InquiryID, name, price, image FROM products_chimes WHERE InquiryID IN ($placeholders)");
            $stmt->execute($productIds);
            $products = [];
            foreach ($stmt->fetchAll() as $p) {
                $products[(int)$p['InquiryID']] = $p;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to lookup product metadata.', 'details' => $e->getMessage()]);
            exit;
        }

        foreach ($cart_items as $id => $qty) {
            $qty = (int)$qty;
            $cart_count += $qty;

            if (isset($products[$id])) {
                $price = (float)$products[$id]['price'];
                $cart_total += $price * $qty;
                if ($product_image === null && !empty($products[$id]['image'])) {
                    $product_image = $products[$id]['image'];
                }
            } else {
                // Fallback to session metadata if available
                if (isset($_SESSION['cart_meta'][$id]['price'])) {
                    $cart_total += (float)$_SESSION['cart_meta'][$id]['price'] * $qty;
                }
                if ($product_image === null && isset($_SESSION['cart_meta'][$id]['image'])) {
                    $product_image = $_SESSION['cart_meta'][$id]['image'];
                }
            }
        }
    }

    $products = isset($products) ? $products : [];
    echo json_encode([
        'status' => 'success',
        'cart_count' => $cart_count,
        'cart_total' => round($cart_total, 2),
        'product_image' => $product_image,
        'cart_items' => $cart_items,
        'products' => $products
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
    $user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

    // Handle remove action: POST with { action: 'remove', product_id }
    if (isset($data['action']) && $data['action'] === 'remove') {
        if ($user_id !== null) {
            try {
                $del = $pdo->prepare('DELETE FROM user_carts WHERE userId = ? AND productId = ?');
                $del->execute([$user_id, $product_id]);
            } catch (\PDOException $e) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Unable to remove item from database.', 'details' => $e->getMessage()]);
                exit;
            }
        } else {
            if (isset($_SESSION['cart'][$product_id])) {
                unset($_SESSION['cart'][$product_id]);
            }
            if (isset($_SESSION['cart_meta'][$product_id])) {
                unset($_SESSION['cart_meta'][$product_id]);
            }
        }

        // Rebuild summary and return (reuse GET logic)
        $cart_items = [];
        if ($user_id !== null) {
            try {
                $stmt = $pdo->prepare('SELECT productId, productQuantity FROM user_carts WHERE userId = ?');
                $stmt->execute([$user_id]);
                foreach ($stmt->fetchAll() as $row) {
                    $cart_items[(int)$row['productId']] = (int)$row['productQuantity'];
                }
            } catch (\PDOException $e) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Unable to fetch cart data for summary.', 'details' => $e->getMessage()]);
                exit;
            }
        } else {
            $cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
        }

        $cart_count = 0;
        $cart_total = 0.00;
        $product_image = null;
        if (!empty($cart_items)) {
            $ids = array_keys($cart_items);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            try {
                $stmt = $pdo->prepare("SELECT InquiryID, name, price, image FROM products_chimes WHERE InquiryID IN ($placeholders)");
                $stmt->execute($ids);
                $products = [];
                foreach ($stmt->fetchAll() as $p) {
                    $products[(int)$p['InquiryID']] = $p;
                }
            } catch (\PDOException $e) {
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Unable to lookup products for summary.']);
                exit;
            }

            foreach ($cart_items as $id => $qty) {
                $qty = (int)$qty;
                $cart_count += $qty;
                if (isset($products[$id])) {
                    $cart_total += (float)$products[$id]['price'] * $qty;
                    if ($product_image === null && !empty($products[$id]['image'])) {
                        $product_image = $products[$id]['image'];
                    }
                } elseif (isset($_SESSION['cart_meta'][$id]['price'])) {
                    $cart_total += (float)$_SESSION['cart_meta'][$id]['price'] * $qty;
                    if ($product_image === null && isset($_SESSION['cart_meta'][$id]['image'])) {
                        $product_image = $_SESSION['cart_meta'][$id]['image'];
                    }
                }
            }
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Removed from cart',
            'cart_count' => $cart_count,
            'cart_total' => round($cart_total, 2),
            'product_image' => $product_image,
            'cart_items' => $cart_items,
            'products' => isset($products) ? $products : []
        ]);
        exit;
    }

    /*
     * Persist cart changes.
     * If the user is logged in, use database storage. Otherwise use the session cart.
     */
    if ($user_id !== null) {
        try {
            $sql = "INSERT INTO user_carts (userId, productId, productQuantity) 
                    VALUES (:user_id, :product_id, :quantity) 
                    ON DUPLICATE KEY UPDATE productQuantity = productQuantity + :quantity_increment";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $requested_qty,
                'quantity_increment' => $requested_qty
            ]);

        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database storage error occurred.', 'details' => $e->getMessage()]);
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

    // Rebuild summary from authoritative source (DB for logged users, session for guests)
    $cart_items = [];
    if ($user_id !== null) {
        try {
            $stmt = $pdo->prepare('SELECT productId, productQuantity FROM user_carts WHERE userId = ?');
            $stmt->execute([$user_id]);
            foreach ($stmt->fetchAll() as $row) {
                $cart_items[(int)$row['productId']] = (int)$row['productQuantity'];
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to fetch cart data for summary.', 'details' => $e->getMessage()]);
            exit;
        }
    } else {
        $cart_items = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    $cart_count = 0;
    $cart_total = 0.00;
    if (!empty($cart_items)) {
        global $pdo;
        $ids = array_keys($cart_items);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        try {
            $stmt = $pdo->prepare("SELECT InquiryID, price FROM products_chimes WHERE InquiryID IN ($placeholders)");
            $stmt->execute($ids);
            $products = [];
            foreach ($stmt->fetchAll() as $p) {
                $products[(int)$p['InquiryID']] = $p;
            }
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Unable to lookup products for summary.', 'details' => $e->getMessage()]);
            exit;
        }

        foreach ($cart_items as $id => $qty) {
            $qty = (int)$qty;
            $cart_count += $qty;
            if (isset($products[$id])) {
                $cart_total += (float)$products[$id]['price'] * $qty;
            } elseif (isset($_SESSION['cart_meta'][$id]['price'])) {
                $cart_total += (float)$_SESSION['cart_meta'][$id]['price'] * $qty;
            }
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
