<?php
/**
 * Simple ad-hoc seeder for the `products` table.
 * Run: php tools/seed_products.php
 */
require_once __DIR__ . '/../db_config.php';

$products = [
    ['id'=>101,'name'=>'Golden Windstone Chime','price'=>129.00,'image'=>'images/chime-001.webp','description'=>'Brass filigree windchime, 43" long.'],
    ['id'=>102,'name'=>'SkyStone Chime','price'=>159.00,'image'=>'images/chime-002.webp','description'=>'Elegant sky-inspired chime, 43" long.'],
    ['id'=>103,'name'=>'Crystal Tassel Chime','price'=>119.00,'image'=>'images/chime-003.webp','description'=>'Jeweled tassel chime, 43" long.'],
    ['id'=>104,'name'=>'Delicate Confetti Chime','price'=>99.00,'image'=>'images/chime-004.webp','description'=>'Lightweight confetti chime, 43" long.'],
    ['id'=>105,'name'=>'Teardrop Melody Chime','price'=>109.00,'image'=>'images/chime-005.webp','description'=>'Tear-shaped charm chime, 43" long.'],
    ['id'=>106,'name'=>'Sprite Parade Chime','price'=>139.00,'image'=>'images/chime-006.webp','description'=>'Filigree sprite chime, 43" long.'],
    ['id'=>107,'name'=>'Crystal Duet Chime','price'=>149.00,'image'=>'images/chime-007.webp','description'=>'Crystal duet chime, 43" long.'],
    ['id'=>108,'name'=>'Pocketful of Stars Chime','price'=>169.00,'image'=>'images/chime-008.webp','description'=>'Starry chime with bright notes, 43" long.']
];

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('INSERT INTO products (id, name, price, image, description) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name), price = VALUES(price), image = VALUES(image), description = VALUES(description)');
    foreach ($products as $p) {
        $stmt->execute([$p['id'], $p['name'], $p['price'], $p['image'], $p['description']]);
    }
    $pdo->commit();
    echo "Seeded products successfully.\n";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Seeding failed: " . $e->getMessage() . "\n";
}
