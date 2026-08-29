-- Skema Database E-Commerce / Katalog Vanilla PHP
-- Karakter UTF-8 multibyte untuk emoji & karakter khusus

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(50) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NULL,
  `name` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `promo_price` DECIMAL(12, 2) NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `customer_address` TEXT NOT NULL,
  `customer_notes` TEXT NULL,
  `total_amount` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `status` ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
  `whatsapp_url` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NULL,
  `product_name` VARCHAR(200) NOT NULL,
  `price` DECIMAL(12, 2) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(12, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==========================================
-- INITIAL SEED DATA
-- ==========================================

-- Default Admin: username: admin / password: password123
INSERT INTO `admins` (`id`, `username`, `password`, `name`, `email`) VALUES
(1, 'admin', '$2y$10$eO1dI2eR96sZtJ9Z8F9/v.V0bX0gO7gMlmx7xK8/6C/e4q7Zp.q72', 'Store Administrator', 'admin@store.local')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Default Store Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('store_name', 'Store Showcase'),
('store_slogan', 'Curated Modern Tech & Lifestyle'),
('store_description', 'A modern e-commerce showcase built with Vanilla PHP UI primitives, Alpine.js reactive cart, and instant WhatsApp ordering.'),
('whatsapp_number', '15552345678'),
('store_email', 'contact@store.local'),
('store_phone', '+1 (555) 234-5678'),
('store_address', '742 Evergreen Terrace, Springfield, OR 97477'),
('currency', '$'),
('instagram_url', 'https://instagram.com/'),
('facebook_url', 'https://facebook.com/'),
('hero_title', 'Discover Premium Curated Products'),
('hero_subtitle', 'Browse our curated catalog, add items to your interactive cart drawer, and place orders directly via WhatsApp.'),
('hero_badge', '🔥 Special Showcase Deals!'),
('theme_primary_color', 'zinc'),
('theme_radius', 'standard')
ON DUPLICATE KEY UPDATE `setting_key`=`setting_key`;

-- Product Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`) VALUES
(1, 'Audio & Electronics', 'audio-and-electronics', 'High-fidelity audio, smart wearables, and electronic accessories.', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60', 1),
(2, 'Fashion & Apparel', 'fashion-and-apparel', 'Modern casual and formal apparel collection for everyday living.', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=60', 1),
(3, 'Home & Living', 'home-and-living', 'Minimalist, aesthetic, and functional home & workspace decor.', 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=500&auto=format&fit=crop&q=60', 1),
(4, 'Health & Beauty', 'health-and-beauty', 'Premium skincare, wellness essentials, and self-care products.', 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=500&auto=format&fit=crop&q=60', 1)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Featured Showcase Products
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `promo_price`, `stock`, `image`, `is_featured`, `is_active`) VALUES
(1, 1, 'Wireless Active Noise Cancelling Headphones ANC', 'wireless-active-noise-cancelling-headphones-anc', 'Premium wireless over-ear headphones with advanced Active Noise Cancellation (ANC), rich acoustic detail, 40-hour battery life, and plush memory foam earcups for all-day listening comfort.', 299.00, 249.00, 25, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80', 1, 1),
(2, 1, 'AMOLED Smartwatch Fitness Tracker V2', 'amoled-smartwatch-fitness-tracker-v2', 'Smart wearable featuring a vivid AMOLED display, 24/7 heart rate & SpO2 biometric sensors, integrated GPS, 5ATM water resistance, and up to 14-day battery life.', 199.00, 149.00, 18, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80', 1, 1),
(3, 2, 'Urban Water-Resistant Laptop Backpack 15.6"', 'urban-water-resistant-laptop-backpack-156-inch', 'Minimalist water-repellent backpack with dedicated 15.6" padded laptop sleeve, external quick-access USB port, and ergonomic breathable shoulder straps.', 89.00, 69.00, 40, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80', 1, 1),
(4, 3, 'Ultrasonic Wood Grain Aromatherapy Diffuser 500ml', 'ultrasonic-wood-grain-aromatherapy-diffuser-500ml', 'Aesthetic natural wood grain humidifier and essential oil diffuser featuring 7 ambient LED mood lights, wireless remote, and auto-off safety protection.', 59.00, 45.00, 30, 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&auto=format&fit=crop&q=80', 1, 1),
(5, 3, 'Minimalist Nordic Ceramic Mug & Saucer Set', 'minimalist-nordic-ceramic-mug-saucer-set', 'Artisanal matte ceramic coffee and tea set with matching saucer and gold stirring spoon. Perfect for home cafe baristas, gifts, or desk decor.', 35.00, NULL, 50, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80', 0, 1),
(6, 1, 'Compact 75% Wireless Mechanical Keyboard RGB', 'compact-75-wireless-mechanical-keyboard-rgb', 'Compact 75% mechanical keyboard featuring hot-swappable switches, triple-mode connectivity (Bluetooth 5.0, 2.4GHz & USB-C), dynamic per-key RGB backlighting, and durable PBT keycaps.', 149.00, 119.00, 15, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80', 1, 1),
(7, 2, 'Classic Matte Polarized Sunglasses UV400', 'classic-matte-polarized-sunglasses-uv400', 'Timeless sunglasses engineered with anti-glare polarized lenses and complete UV400 optical protection. Ultra-lightweight flexible frame.', 49.00, 29.00, 60, 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800&auto=format&fit=crop&q=80', 0, 1),
(8, 4, 'Vitamin C + Hyaluronic Booster Facial Serum', 'vitamin-c-hyaluronic-booster-facial-serum', 'Radiance boosting facial serum formulated with stable Vitamin C, 2% Hyaluronic Acid, and Niacinamide to brighten tone and restore deep hydration.', 39.00, 29.00, 45, 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80', 1, 1)
ON DUPLICATE KEY UPDATE `name`=`name`;
