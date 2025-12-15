-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 04:53 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_treasures_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(10) UNSIGNED NOT NULL,
  `label` varchar(64) DEFAULT NULL COMMENT 'e.g., "Home", "Work"',
  `line1` varchar(255) NOT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `province` varchar(255) DEFAULT NULL,
  `postal_code` varchar(32) NOT NULL,
  `country` varchar(64) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `label`, `line1`, `line2`, `city`, `province`, `postal_code`, `country`, `created_at`) VALUES
(1, NULL, '#424, Maharlika Street, Brgy. San Juan', NULL, 'Ligao', 'Albay', '4343', 'Philippines', '2025-12-08 07:45:00'),
(3, NULL, '#000, Centro Occidental', NULL, 'Polangui', 'Albay', '4444', 'Philippines', '2025-12-08 10:39:07'),
(7, 'Home', '#424, Maharlika Street', NULL, 'Ligao', 'Albay', '4343', 'Philippines', '2025-12-08 11:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Price at the time of adding to cart',
  `added_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `unit_price`, `added_at`) VALUES
(15, 1, 2, 2, 59.99, '2025-12-08 06:36:49'),
(16, 1, 3, 1, 79.99, '2025-12-08 06:36:53'),
(17, 1, 4, 1, 109.99, '2025-12-08 06:36:54');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `address_id` int(10) UNSIGNED NOT NULL,
  `order_status_id` tinyint(3) UNSIGNED NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `address_id`, `order_status_id`, `total_amount`, `created_at`, `updated_at`) VALUES
(2, 1, 2, 4, 119.98, '2025-12-08 07:48:53', '2025-12-08 10:51:30'),
(3, 2, 3, 4, 849.87, '2025-12-08 10:39:07', '2025-12-08 10:58:04'),
(4, 2, 4, 1, 129.98, '2025-12-08 10:47:38', '2025-12-08 10:47:38'),
(5, 2, 5, 4, 59.99, '2025-12-08 10:48:16', '2025-12-08 10:51:22'),
(6, 2, 6, 3, 99.98, '2025-12-08 10:48:40', '2025-12-08 10:49:30'),
(7, 2, 3, 1, 89.97, '2025-12-08 11:52:30', '2025-12-08 11:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Price at the time of order',
  `total_price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 2, 1, 1, 89.99, 89.99),
(2, 2, 5, 1, 29.99, 29.99),
(3, 3, 1, 2, 89.99, 179.98),
(4, 3, 2, 2, 59.99, 119.98),
(5, 3, 3, 2, 79.99, 159.98),
(6, 3, 4, 2, 109.99, 219.98),
(7, 3, 5, 2, 29.99, 59.98),
(8, 3, 6, 1, 39.99, 39.99),
(9, 3, 7, 1, 49.99, 49.99),
(10, 3, 8, 1, 19.99, 19.99),
(11, 4, 1, 1, 89.99, 89.99),
(12, 4, 6, 1, 39.99, 39.99),
(13, 5, 2, 1, 59.99, 59.99),
(14, 6, 7, 2, 49.99, 99.98),
(15, 7, 5, 3, 29.99, 89.97);

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `code` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `code`, `name`) VALUES
(1, 'pending', 'Pending'),
(2, 'shipped', 'Shipped'),
(3, 'out_for_delivery', 'Out for Delivery'),
(4, 'delivered', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sku` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `description`, `price`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'HIR-004', 'Hirono Series 4', 'Hirono Reshape Series - Complete set of 12', 89.99, 50, '2025-12-08 01:38:17', '2025-12-08 01:38:17'),
(2, 'SKU-NGT', 'Skull Panda Nightmare', 'Skull Panda Sound Series - Limited Edition', 59.99, 30, '2025-12-08 01:38:17', '2025-12-08 01:38:17'),
(3, 'DIM-WW', 'Dimoo Weaving Wonders', 'Dimoo Weaving Wonders Series - 12 unique designs', 79.99, 40, '2025-12-08 01:38:17', '2025-12-08 01:38:17'),
(4, 'MOL-GARD', 'Molly Garden Secrets', 'Molly \"When I was 3\" Series - Chase variant included', 109.99, 20, '2025-12-08 01:38:17', '2025-12-08 01:38:17'),
(5, 'HIR-MER', 'Hirono City of Mercy', 'Series 4 - New Arrival', 29.99, 100, '2025-12-08 01:38:17', '2025-12-08 01:38:17'),
(6, 'SKU-REAL', 'Skull Image of Reality', 'Exclusive edition - New Arrival', 39.99, 60, '2025-12-08 01:38:17', '2025-12-08 01:38:17'),
(7, 'DIM-SHP', 'Dimoo Shapes in Nature', 'Limited release - New Arrival', 49.99, 45, '2025-12-08 01:38:17', '2025-12-08 01:38:17'),
(8, 'MOL-CARB', 'Molly Carb Lover', 'New Set - Carb Lover Series', 19.99, 150, '2025-12-08 01:38:17', '2025-12-08 01:38:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(512) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `url`, `is_primary`) VALUES
(1, 1, 'pictures/hirono/reshape/reshape ser. cover.jpg', 1),
(2, 2, 'pictures/skullpanda/sound series/Collection_cover.JPG', 1),
(3, 3, 'pictures/Dimoo/weaving wonders/Dimoo weaving and achuchu cover.webp', 1),
(4, 4, 'pictures/molly/when i was 3/molly wI3 cover.jpg', 1),
(5, 5, 'pictures/hirono/city of mercy/city of mercy collection cover.jpeg', 1),
(6, 6, 'pictures/skullpanda/image of reality/image of reality collection cover.jpg', 1),
(7, 7, 'pictures/Dimoo/Shapes in nature/Shapes in nature collection cover.jpg', 1),
(8, 8, 'pictures/molly/carb lover/carb lover collection cover.jpg', 1),
(9, 1, 'pictures/Team/A.jpg', 0),
(10, 1, 'pictures/Team/C.png', 0),
(11, 1, 'pictures/Team/K.jpg', 0),
(12, 1, 'pictures/Team/L.jpg', 0),
(13, 1, 'pictures/Team/S.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shopping_carts`
--

CREATE TABLE `shopping_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shopping_carts`
--

INSERT INTO `shopping_carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-12-08 06:13:16', '2025-12-08 06:13:16'),
(2, 2, '2025-12-08 10:37:34', '2025-12-08 10:37:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) DEFAULT 'https://via.placeholder.com/150'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `first_name`, `last_name`, `full_name`, `created_at`, `profile_image`) VALUES
(1, 'email@example.com', 'password_example', '', '', 'Carl Guianne G. Garcia', '2025-12-08 00:38:43', 'pictures/hirono/city of mercy/city of mercy collection cover.jpeg'),
(2, 'sheanpogi@email.com', 'password', '', '', 'Rovi Shean Salalima', '2025-12-08 10:36:10', 'pictures/Team/S.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_address_rule` (`line1`,`city`,`province`,`postal_code`,`country`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cart_id` (`cart_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_address_id` (`address_id`),
  ADD KEY `idx_order_status_id` (`order_status_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_code` (`code`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_sku` (`sku`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_id` (`product_id`);

--
-- Indexes for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `shopping_carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_address` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `fk_orders_status` FOREIGN KEY (`order_status_id`) REFERENCES `order_status` (`id`),
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD CONSTRAINT `fk_shopping_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
