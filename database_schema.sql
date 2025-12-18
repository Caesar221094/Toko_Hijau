-- ================================================================
-- SQL SCHEMA - SISTEM E-COMMERCE
-- Entity Relationship Diagram (ERD) Implementation
-- ================================================================

-- 1. TABEL USERS
-- Menyimpan data pengguna/customer
CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================

-- 2. TABEL CATEGORIES
-- Menyimpan kategori produk
CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================

-- 3. TABEL PRODUCTS
-- Menyimpan data produk dengan relasi ke categories
CREATE TABLE `products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(255) NOT NULL,
  `deskripsi` TEXT NULL DEFAULT NULL,
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  `harga` DECIMAL(13, 2) NOT NULL DEFAULT 0.00,
  `stok` INT UNSIGNED NOT NULL DEFAULT 0,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` 
    FOREIGN KEY (`category_id`) 
    REFERENCES `categories` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================

-- 4. TABEL ORDERS
-- Menyimpan data pesanan dengan relasi ke users
CREATE TABLE `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `order_number` VARCHAR(255) NOT NULL UNIQUE,
  `status` ENUM('pending', 'processing', 'completed', 'cancelled') 
    NOT NULL DEFAULT 'pending',
  `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `shipping_address` TEXT NULL DEFAULT NULL,
  `notes` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` 
    FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================

-- 5. TABEL ORDER_PRODUCTS (Tabel Pivot/Penghubung)
-- Menyimpan detail produk dalam pesanan
-- Implementasi relasi Many-to-Many antara Orders dan Products
CREATE TABLE `order_products` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT UNSIGNED NOT NULL DEFAULT 1,
  `price` DECIMAL(13, 2) NOT NULL,
  `subtotal` DECIMAL(15, 2) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_products_order_id_foreign` (`order_id`),
  KEY `order_products_product_id_foreign` (`product_id`),
  KEY `order_products_order_id_product_id_index` (`order_id`, `product_id`),
  CONSTRAINT `order_products_order_id_foreign` 
    FOREIGN KEY (`order_id`) 
    REFERENCES `orders` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `order_products_product_id_foreign` 
    FOREIGN KEY (`product_id`) 
    REFERENCES `products` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- PENJELASAN RELASI
-- ================================================================

-- 1. Users → Orders (One-to-Many)
--    Satu user dapat memiliki banyak pesanan
--    FK: orders.user_id → users.id

-- 2. Categories → Products (One-to-Many)
--    Satu kategori dapat memiliki banyak produk
--    FK: products.category_id → categories.id

-- 3. Orders → OrderProducts (One-to-Many)
--    Satu pesanan dapat memiliki banyak detail produk
--    FK: order_products.order_id → orders.id

-- 4. Products → OrderProducts (One-to-Many)
--    Satu produk dapat muncul di banyak detail pesanan
--    FK: order_products.product_id → products.id

-- 5. Orders ↔ Products (Many-to-Many melalui OrderProducts)
--    Satu pesanan dapat memiliki banyak produk
--    Satu produk dapat ada di banyak pesanan
--    Tabel Pivot: order_products

-- ================================================================
-- SAMPLE DATA
-- ================================================================

-- Insert sample user
INSERT INTO `users` (`name`, `email`, `password`, `email_verified_at`, `created_at`, `updated_at`) 
VALUES 
  ('Super Administrator', 'admin@admin.com', '$2y$12$...hashed...', NOW(), NOW(), NOW());

-- Insert sample categories
INSERT INTO `categories` (`nama`, `created_at`, `updated_at`) 
VALUES 
  ('Elektronik', NOW(), NOW()),
  ('Fashion', NOW(), NOW()),
  ('Makanan & Minuman', NOW(), NOW());

-- Insert sample products
INSERT INTO `products` (`nama`, `deskripsi`, `harga`, `stok`, `category_id`, `created_at`, `updated_at`) 
VALUES 
  ('Laptop Asus ROG', 'Gaming laptop powerful', 15000000.00, 10, 1, NOW(), NOW()),
  ('Mouse Logitech', 'Wireless mouse', 150000.00, 50, 1, NOW(), NOW()),
  ('Kemeja Pria', 'Kemeja formal lengan panjang', 250000.00, 30, 2, NOW(), NOW());

-- Insert sample order
INSERT INTO `orders` (`user_id`, `order_number`, `status`, `total_amount`, `shipping_address`, `created_at`, `updated_at`) 
VALUES 
  (1, 'ORD-20251218-ABC123', 'completed', 15300000.00, 'Jl. Sudirman No. 123, Jakarta', NOW(), NOW());

-- Insert sample order products
INSERT INTO `order_products` (`order_id`, `product_id`, `quantity`, `price`, `subtotal`, `created_at`, `updated_at`) 
VALUES 
  (1, 1, 1, 15000000.00, 15000000.00, NOW(), NOW()),
  (1, 2, 2, 150000.00, 300000.00, NOW(), NOW());

-- ================================================================
-- QUERY CONTOH
-- ================================================================

-- 1. Ambil semua pesanan beserta nama user
SELECT o.order_number, u.name, o.status, o.total_amount
FROM orders o
JOIN users u ON o.user_id = u.id;

-- 2. Ambil detail produk dalam pesanan tertentu
SELECT op.*, p.nama, p.harga
FROM order_products op
JOIN products p ON op.product_id = p.id
WHERE op.order_id = 1;

-- 3. Hitung total penjualan per produk
SELECT p.nama, 
       SUM(op.quantity) as total_terjual,
       SUM(op.subtotal) as total_pendapatan
FROM products p
LEFT JOIN order_products op ON p.id = op.product_id
GROUP BY p.id, p.nama;

-- 4. Ambil pesanan user beserta jumlah item
SELECT o.order_number, 
       u.name, 
       COUNT(op.id) as jumlah_item,
       o.total_amount
FROM orders o
JOIN users u ON o.user_id = u.id
LEFT JOIN order_products op ON o.id = op.order_id
GROUP BY o.id;

-- 5. Produk yang paling sering dibeli
SELECT p.nama, COUNT(op.id) as jumlah_order
FROM products p
JOIN order_products op ON p.id = op.product_id
GROUP BY p.id, p.nama
ORDER BY jumlah_order DESC
LIMIT 10;
