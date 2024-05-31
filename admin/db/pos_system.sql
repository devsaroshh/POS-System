-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2024 at 04:39 PM
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
-- Database: `pos_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Idona Shields', '2024-05-29 09:23:45'),
(2, 'Salvador Gamble', '2024-05-29 09:35:55'),
(4, 'Jason White', '2024-05-29 20:49:21'),
(6, 'david', '2024-05-30 14:22:24'),
(7, 'Rhiannon Erickson', '2024-05-30 21:02:43'),
(8, 'Martha Ellis', '2024-05-30 21:02:47'),
(9, 'Christian Evans', '2024-05-30 21:02:51'),
(10, 'Orli Anderson', '2024-05-30 21:02:55'),
(11, 'Iris Allen', '2024-05-30 21:02:59'),
(12, 'Dale Copeland', '2024-05-30 21:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(3, 'david', 'david@gmail.com', 'd27d320c27c3033b7883347d8beca317', '2024-05-30 11:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `change_type` enum('add','remove') NOT NULL,
  `quantity` int(11) NOT NULL,
  `change_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `change_type`, `quantity`, `change_date`) VALUES
(24, 22, 'remove', 533, '2024-05-30 12:01:28'),
(26, 15, 'remove', 354, '2024-05-30 18:06:07'),
(27, 33, 'add', 518, '2024-05-30 18:06:11'),
(29, 21, 'remove', 723, '2024-05-30 18:06:20'),
(30, 15, 'remove', 239, '2024-05-30 18:06:24'),
(31, 24, 'remove', 665, '2024-05-30 18:06:29'),
(32, 16, 'add', 572, '2024-05-30 18:06:33'),
(33, 15, 'remove', 867, '2024-05-30 18:06:36');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `is_read`, `created_at`) VALUES
(2, 1, 'Order ID 5 status updated to Pending', 0, '2024-05-31 13:27:45'),
(3, 1, 'Product \"Hermione Benton\" is out of stock', 0, '2024-05-31 13:37:33'),
(4, 1, 'Product \"Murphy Pacheco\" is out of stock', 0, '2024-05-31 13:39:16'),
(5, 1, 'Order ID 5 status updated to completed', 0, '2024-05-31 13:42:05'),
(6, 1, 'Order ID 5 status updated to Pending', 0, '2024-05-31 13:43:05'),
(7, 1, 'Order ID 5 status updated to completed', 0, '2024-05-31 13:56:24');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `total_price`, `status`, `created_at`) VALUES
(4, 3, 823.00, 'completed', '2024-05-30 13:07:32'),
(5, 3, 4779.00, 'completed', '2024-05-30 13:29:39'),
(6, 3, 142.00, 'completed', '2024-05-30 13:49:48'),
(7, 3, 142.00, 'pending', '2024-05-30 16:31:42');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 4, 15, 1, 823.00),
(2, 5, 20, 1, 417.00),
(3, 5, 16, 1, 142.00),
(4, 5, 22, 10, 422.00),
(5, 6, 16, 1, 142.00),
(6, 7, 16, 1, 142.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `created_at`, `category_id`, `subcategory_id`) VALUES
(15, 'Denise George', 'Occaecat laborum Qu', 823.00, 23, '2024-05-29 09:45:56', 1, 1),
(16, 'Cecilia Hughes', 'Incidunt voluptatem', 142.00, 16, '2024-05-29 11:45:48', 2, 2),
(20, 'Hermione Benton', 'Quos et maiores mini', 417.00, 32, '2024-05-29 15:50:16', 2, 1),
(21, 'Griffith Hansen', 'Error odit ut autem ', 725.00, 31, '2024-05-29 16:43:23', 2, 2),
(22, 'Barclay Johnson', 'Qui qui debitis dolo', 422.00, 84, '2024-05-29 20:50:17', 4, NULL),
(24, 'Murphy Pacheco', 'Nisi eligendi ipsum', 484.00, 98, '2024-05-30 14:07:21', 4, NULL),
(26, 'Kermit Donaldson', 'Pariatur Quas aut p', 15.00, 47, '2024-05-30 14:08:55', 4, NULL),
(31, 'Clarke Fuller', 'Odit quae sed enim q', 303.00, 89, '2024-05-30 14:22:40', 6, NULL),
(32, 'Clinton Hubbard', 'Praesentium voluptas', 164.00, 85, '2024-05-30 14:39:47', 6, 4),
(33, 'Amy Mclaughlin', 'Libero voluptas comm', 512.00, 53, '2024-05-30 17:01:26', 6, 4),
(34, 'Keely Gillespie', 'Laboris ad tempore ', 877.00, 50, '2024-05-30 20:48:31', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `product_id`, `quantity`, `total_price`, `sale_date`) VALUES
(12, 20, 77, 412.00, '2024-05-30 07:55:43'),
(13, 22, 931, 126.00, '2024-05-30 07:55:53'),
(14, 32, 512, 865.00, '2024-05-30 17:58:00'),
(15, 15, 532, 36.00, '2024-05-30 17:58:06'),
(16, 22, 515, 681.00, '2024-05-30 17:58:10'),
(17, 21, 235, 211.00, '2024-05-30 17:58:15'),
(18, 34, 611, 712.00, '2024-05-30 17:58:19'),
(19, 32, 863, 930.00, '2024-05-30 17:58:23'),
(20, 21, 853, 84.00, '2024-05-30 17:58:27'),
(21, 34, 344, 99.00, '2024-05-30 17:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`, `created_at`) VALUES
(1, 1, 'Nathaniel Sanchessz', '2024-05-29 09:23:55'),
(2, 1, 'Alika Thornton', '2024-05-29 09:36:02'),
(4, 6, 'davidd', '2024-05-30 14:22:32'),
(5, 4, 'Sharon Hopper', '2024-05-30 21:29:46'),
(6, 8, 'Iris Benson', '2024-05-30 21:29:51'),
(7, 6, 'Shana Pacheco', '2024-05-30 21:31:02'),
(8, 4, 'Hadley Mccullough', '2024-05-30 21:31:07'),
(9, 2, 'Malachi Baldwin', '2024-05-30 21:31:13'),
(10, 7, 'Martha Mcintosh', '2024-05-30 21:31:20'),
(11, 8, 'Leo Carney', '2024-05-30 21:31:25'),
(12, 1, 'Kirestin Mcpherson', '2024-05-30 21:31:32'),
(13, 12, 'Geraldine Tucker', '2024-05-30 21:35:41'),
(14, 4, 'Lee Fleming', '2024-05-30 21:35:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor') NOT NULL,
  `permission` enum('edit') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `permission`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin', NULL),
(2, 'nebygobuxa', 'Pa$$w0rd!', 'editor', NULL),
(4, 'wuhoxi', 'f3ed11bbdb94fd9ebdefbaf646ab94d3', 'editor', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`);

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
