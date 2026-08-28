-- Skema Database E-Commerce / Katalog Native PHP
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
-- DATA AWAL (SEED DATA)
-- ==========================================

-- Admin Default: username: admin / password: password123
-- Hash Bcrypt dari password 'password123'
INSERT INTO `admins` (`id`, `username`, `password`, `name`, `email`) VALUES
(1, 'admin', '$2y$10$eO1dI2eR96sZtJ9Z8F9/v.V0bX0gO7gMlmx7xK8/6C/e4q7Zp.q72', 'Admin Toko', 'admin@katalogstore.id')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Pengaturan Toko Default
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('store_name', 'KatalogStore Indonesia'),
('store_slogan', 'Katalog Belanja Modern & Praktis via WhatsApp'),
('store_description', 'Toko online terpercaya dengan berbagai produk pilihan terbaik. Pesan langsung dengan mudah via WhatsApp tanpa ribet!'),
('whatsapp_number', '6281234567890'),
('store_email', 'kontak@katalogstore.id'),
('store_phone', '+62 812-3456-7890'),
('store_address', 'Jl. Jenderal Sudirman No. 88, Karet Semanggi, Setiabudi, Jakarta Selatan 12930'),
('currency', 'Rp'),
('instagram_url', 'https://instagram.com/'),
('facebook_url', 'https://facebook.com/'),
('hero_title', 'Temukan Produk Terbaik Dengan Harga Terjangkau'),
('hero_subtitle', 'Pilihan produk terlengkap untuk kebutuhan harian, gadget, fashion, dan rumah tangga dengan pengiriman ke seluruh Indonesia.'),
('hero_badge', '🔥 Promo Spesial Hari Ini!')
ON DUPLICATE KEY UPDATE `setting_key`=`setting_key`;

-- Kategori Produk
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`) VALUES
(1, 'Gadget & Elektronik', 'gadget-dan-elektronik', 'Pilihan gadget pintar, audio, dan aksesoris elektronik berkualitas tinggi.', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60', 1),
(2, 'Fashion & Pakaian', 'fashion-dan-pakaian', 'Koleksi busana pria dan wanita kasual maupun formal modern.', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=60', 1),
(3, 'Perlengkapan Rumah', 'perlengkapan-rumah', 'Peralatan rumah tangga estetik, fungsional, dan ramah lingkungan.', 'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?w=500&auto=format&fit=crop&q=60', 1),
(4, 'Kesehatan & Kecantikan', 'kesehatan-dan-kecantikan', 'Produk perawatan kulit, tubuh, dan kesehatan alami pilihan.', 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=500&auto=format&fit=crop&q=60', 1)
ON DUPLICATE KEY UPDATE `name`=`name`;

-- Produk Pilihan
INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `promo_price`, `stock`, `image`, `is_featured`, `is_active`) VALUES
(1, 1, 'Headphone Wireless Active Noise Cancelling ANC', 'headphone-wireless-active-noise-cancelling-anc', 'Headphone nirkabel premium dengan fitur Active Noise Cancelling (ANC) tingkat tinggi, suara bass bertenaga, daya tahan baterai hingga 40 jam, dan bantalan telinga yang empuk untuk kenyamanan sepanjang hari.', 799000, 649000, 25, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80', 1, 1),
(2, 1, 'Smartwatch AMOLED Fitness Tracker V2', 'smartwatch-amoled-fitness-tracker-v2', 'Jam tangan pintar dengan layar AMOLED jernih, sensor detak jantung 24/7, SpO2, GPS internal, water resistant 5ATM, serta baterai awet hingga 14 hari pemakaian normal.', 899000, 749000, 18, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80', 1, 1),
(3, 2, 'Tas Ransel Urban Anti-Air Laptop 15.6 Inch', 'tas-ransel-urban-anti-air-laptop-156-inch', 'Backpack minimalis tahan air (water repellent) berkapasitas besar, kompartemen laptop busa tebal hingga 15.6 inch, slot USB charger eksternal, dan desain ergonomis modern.', 389000, 299000, 40, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&auto=format&fit=crop&q=80', 1, 1),
(4, 3, 'Diffuser Aromaterapi Ultrasonik Wood Grain 500ml', 'diffuser-aromaterapi-ultrasonik-wood-grain-500ml', 'Humidifier & diffuser aroma estetik dengan motif serat kayu alami, lampu LED 7 warna yang menenangkan, remote control nirkabel, dan sistem auto off saat air habis.', 249000, 199000, 30, 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=800&auto=format&fit=crop&q=80', 1, 1),
(5, 3, 'Set Cangkir Keramik Estetik Nordic Minimalis', 'set-cangkir-keramik-estetik-nordic-minimalis', 'Set cangkir kopi dan teh keramik berkualitas tinggi dengan saucer dan sendok emas. Cocok untuk hadiah, perlengkapan kafe rumah, atau dekorasi meja santai.', 159000, NULL, 50, 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80', 0, 1),
(6, 1, 'Mechanical Keyboard Wireless RGB 75% Layout', 'mechanical-keyboard-wireless-rgb-75-layout', 'Keyboard mekanikal compact 75% dengan hot-swappable switch, koneksi triple mode (Bluetooth 5.0, 2.4Ghz dongle, & kabel Type-C), lampu RGB dinamis, dan keycaps PBT.', 650000, 575000, 15, 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80', 1, 1),
(7, 2, 'Kacamata Hitam Polarized UV400 Classic Matte', 'kacamata-hitam-polarized-uv400-classic-matte', 'Kacamata sunglasses dengan lensa polarized anti silau dan proteksi sinar UV400 penuh. Frame ringan dari bahan polikarbonat fleksibel dan awet.', 189000, 129000, 60, 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=800&auto=format&fit=crop&q=80', 0, 1),
(8, 4, 'Serum Wajah Vitamin C + Hyaluronic Booster', 'serum-wajah-vitamin-c-hyaluronic-booster', 'Serum pencerah kulit wajah dengan formula stabil Vitamin C, Hyaluronic Acid 2%, dan Niacinamide untuk menyamarkan noda hitam serta menjaga kelembapan kulit secara intensif.', 175000, 139000, 45, 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&auto=format&fit=crop&q=80', 1, 1)
ON DUPLICATE KEY UPDATE `name`=`name`;
