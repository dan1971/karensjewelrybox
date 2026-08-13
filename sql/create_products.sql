-- Create products table and seed with chime items
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `image` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed chime products (IDs chosen to match earlier samples)
INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`) VALUES
(101, 'Golden Windstone Chime', 129.00, 'images/chime-001.webp', 'Brass filigree windchime, 43" long.'),
(102, 'SkyStone Chime', 159.00, 'images/chime-002.webp', 'Elegant sky-inspired chime, 43" long.'),
(103, 'Crystal Tassel Chime', 119.00, 'images/chime-003.webp', 'Jeweled tassel chime, 43" long.'),
(104, 'Delicate Confetti Chime', 99.00, 'images/chime-004.webp', 'Lightweight confetti chime, 43" long.'),
(105, 'Teardrop Melody Chime', 109.00, 'images/chime-005.webp', 'Tear-shaped charm chime, 43" long.'),
(106, 'Sprite Parade Chime', 139.00, 'images/chime-006.webp', 'Filigree sprite chime, 43" long.'),
(107, 'Crystal Duet Chime', 149.00, 'images/chime-007.webp', 'Crystal duet chime, 43" long.'),
(108, 'Pocketful of Stars Chime', 169.00, 'images/chime-008.webp', 'Starry chime with bright notes, 43" long')
ON DUPLICATE KEY UPDATE name=VALUES(name), price=VALUES(price), image=VALUES(image), description=VALUES(description);
