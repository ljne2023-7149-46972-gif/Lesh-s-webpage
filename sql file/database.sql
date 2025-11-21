
CREATE TABLE `payment_methods` (
    `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(32),
    `name` VARCHAR(64)
);


CREATE TABLE `users` (
    `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255),
    `password_hash` VARCHAR(255),
    `full_name` VARCHAR(200),
    `phone` VARCHAR(32),
    `created_at` DATETIME,
    `updated_at` DATETIME
);


CREATE TABLE `addresses` (
    `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(10) UNSIGNED,
    `label` VARCHAR(64),
    `line1` VARCHAR(255),
    `line2` VARCHAR(255),
    `city` VARCHAR(128),
    `province_state` VARCHAR(128),
    `postal_code` VARCHAR(32),
    `country` VARCHAR(64),
    `created_at` DATETIME,

    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);


CREATE TABLE `order_statuses` (
    `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(32),
    `name` VARCHAR(64)
);


CREATE TABLE `orders` (
    `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(10) UNSIGNED,
    `address_id` INT(10) UNSIGNED,
    `order_status_id` INT(10) UNSIGNED,
    `total_amount` DECIMAL(12,2),
    `created_at` DATETIME,
    `updated_at` DATETIME,

    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`),
    FOREIGN KEY (`order_status_id`) REFERENCES `order_statuses`(`id`)
);


CREATE TABLE `products` (
    `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `sku` VARCHAR(64),
    `name` VARCHAR(255),
    `description` TEXT,
    `price` DECIMAL(10,2),
    `stock` INT(10) UNSIGNED,
    `created_at` DATETIME,
    `updated_at` DATETIME
);

CREATE TABLE `product_images` (
    `id` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT(10) UNSIGNED,
    `url` VARCHAR(512),

    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);


CREATE TABLE `order_items` (
    `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT(20) UNSIGNED,
    `product_id` INT(10) UNSIGNED,
    `quantity` INT(10) UNSIGNED,
    `unit_price` DECIMAL(12,2),
    `subtotal` DECIMAL(12,2),

    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);


CREATE TABLE `order_changes` (
    `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT(20) UNSIGNED,
    `changed_by` INT(10) UNSIGNED,
    `change_type` VARCHAR(64),
    `change_summary` VARCHAR(255),
    `previous_json` LONGTEXT,
    `new_json` LONGTEXT,
    `created_at` DATETIME,

    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
    FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`)
);


CREATE TABLE `payments` (
    `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT(20) UNSIGNED,
    `payment_method_id` INT(10) UNSIGNED,
    `amount` DECIMAL(12,2),
    `transaction_ref` VARCHAR(255),
    `status` VARCHAR(64),
    `paid_at` DATETIME,
    `created_at` DATETIME,

    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`),
    FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods`(`id`)
);


CREATE TABLE `deliveries` (
    `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` BIGINT(20) UNSIGNED,
    `carrier` VARCHAR(128),
    `tracking_number` VARCHAR(128),
    `shipped_at` DATETIME,
    `delivered_at` DATETIME,
    `delivery_status` VARCHAR(64),
    `created_at` DATETIME,

    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`)
);

CREATE TABLE `shopping_carts` (
    `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(10) UNSIGNED,
    `updated_at` DATETIME,

    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);


CREATE TABLE `cart_items` (
    `id` BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cart_id` BIGINT(20) UNSIGNED,
    `product_id` INT(10) UNSIGNED,
    `quantity` INT(10) UNSIGNED,
    `unit_price` DECIMAL(10,2),
    `added_at` DATETIME,

    FOREIGN KEY (`cart_id`) REFERENCES `shopping_carts`(`id`),
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
);
