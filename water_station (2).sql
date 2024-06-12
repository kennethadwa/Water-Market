-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2024 at 04:38 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `water_station`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `image_path` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `description`, `price`, `stock`, `image_path`, `created_at`) VALUES
(14, 'Water Jug Large', 'Blue Container Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perferendis similique debitis eius pariatur molestiae repellendus!', 40.00, 35, 'image_product/water jug.jpg', '2024-06-10 21:54:02'),
(15, 'Water Jug Small', 'Blue Container Small Lorem ipsum dolor, sit amet consectetur adipisicing elit. Perferendis similique debitis eius pariatur molestiae repellendus!', 30.00, 450, 'image_product/water jug small.png', '2024-06-10 21:58:49'),
(16, 'Purified Water (Round Container)', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est assumenda voluptatibus animi quos incidunt doloremque!', 80.00, 100, 'image_product/purified.jpg', '2024-06-10 23:34:24'),
(21, 'Alkaline Bottled Water', 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Eius dolorum, sapiente quaerat recusandae dolores id!', 45.00, 500, 'image_product/alkaline.png', '2024-06-11 12:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `transaction_type` varchar(255) DEFAULT 'Walk In',
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'not paid',
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`sale_id`, `product_id`, `customer_name`, `transaction_type`, `quantity`, `total_price`, `payment_status`, `transaction_date`) VALUES
(16, 15, 'Allan Cayetano', 'Walk In', 20, 600.00, 'Paid', '2024-06-11 18:47:24'),
(18, 14, 'Arnold Clavio', 'Return', 40, 1600.00, 'Refund', '2024-06-11 19:35:33'),
(19, 21, 'Hannah Montana', 'Bulk Order', 84, 3780.00, 'Paid', '2024-06-12 06:57:01'),
(20, 15, 'Arnold Clavio', 'Walk In', 5, 150.00, 'Paid', '2024-06-12 07:14:29'),
(21, 15, 'Trisha Mae', 'Walk In', 5, 150.00, 'Paid', '2024-06-12 07:26:07'),
(22, 16, 'Michael Jordan', 'Walk In', 50, 4000.00, 'Paid', '2024-06-12 14:36:10'),
(23, 16, 'Henry Sy', 'Walk In', 50, 4000.00, 'Paid', '2024-06-12 14:37:10'),
(24, 21, 'Hannah Montana', 'Return', 200, 9000.00, 'Refund', '2024-06-12 14:44:30'),
(25, 21, 'Henry Sy', 'Walk In', 200, 9000.00, 'Paid', '2024-06-12 14:51:06'),
(26, 21, 'Trisha Mae', 'Walk In', 200, 9000.00, 'Paid', '2024-06-12 14:52:39'),
(27, 14, 'Arnold Clavio', 'Walk In', 25, 1000.00, 'Paid', '2024-06-12 14:54:48'),
(28, 15, 'Henry Silog', 'Walk In', 25, 750.00, 'Not Paid', '2024-06-12 14:58:53'),
(29, 15, 'Henry Sy', 'Return', 25, 750.00, 'Refund', '2024-06-12 15:03:52'),
(30, 21, 'Henry Sy', 'For Delivery', 250, 11250.00, 'Paid', '2024-06-12 15:07:30'),
(31, 15, 'Gregorio Del Pilar', 'Return', 25, 750.00, 'Paid', '2024-06-12 15:40:56'),
(32, 15, 'Trisha Mae', 'Return', 25, 750.00, 'Refund', '2024-06-12 15:42:12'),
(33, 15, 'Henry Sy', 'Walk In', 25, 750.00, 'Paid', '2024-06-12 15:44:05'),
(34, 15, 'kkkkkkkkk', 'Walk In', 20, 600.00, 'Paid', '2024-06-12 15:45:16'),
(35, 21, 'Cherry Berry', 'Walk In', 250, 11250.00, 'Not Paid', '2024-06-12 16:10:53'),
(36, 14, 'Arnold Clavio', 'Walk In', 15, 600.00, 'Paid', '2024-06-12 17:53:57'),
(37, 15, 'Trisha Mae', 'Walk In', 10, 300.00, 'Paid', '2024-06-12 18:38:10'),
(38, 15, 'Sandara', 'Phone Order', 40, 1200.00, 'Paid', '2024-06-12 18:40:54');

-- --------------------------------------------------------

--
-- Table structure for table `sales_report`
--

CREATE TABLE `sales_report` (
  `report_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_report`
--

INSERT INTO `sales_report` (`report_id`, `sale_id`, `product_id`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 21, 15, 'Paymaya', '2024-06-11 23:26:07', '2024-06-12 06:34:01'),
(2, 22, 16, 'Gcash', '2024-06-12 06:36:10', '2024-06-12 13:36:22'),
(3, 23, 16, 'Paid', '2024-06-12 06:37:10', '2024-06-12 06:37:10'),
(4, 24, 21, 'Not Paid', '2024-06-12 06:44:30', '2024-06-12 06:44:30'),
(5, 25, 21, 'Not Paid', '2024-06-12 06:51:06', '2024-06-12 06:51:06'),
(6, 26, 21, 'Not Paid', '2024-06-12 06:52:39', '2024-06-12 06:52:39'),
(7, 27, 14, 'Not Paid', '2024-06-12 06:54:48', '2024-06-12 06:54:48'),
(8, 28, 15, 'Not Paid', '2024-06-12 06:58:53', '2024-06-12 06:58:53'),
(9, 29, 15, 'Not Paid', '2024-06-12 07:03:52', '2024-06-12 07:03:52'),
(10, 30, 21, 'Not Paid', '2024-06-12 07:07:30', '2024-06-12 07:07:30'),
(11, 31, 15, 'Not Paid', '2024-06-12 07:40:56', '2024-06-12 07:40:56'),
(12, 32, 15, 'Paid', '2024-06-12 07:42:12', '2024-06-12 07:42:12'),
(13, 33, 15, 'Paid', '2024-06-12 07:44:05', '2024-06-12 07:44:05'),
(14, 34, 15, 'Paid', '2024-06-12 07:45:16', '2024-06-12 07:45:16'),
(15, 35, 21, 'Not Paid', '2024-06-12 08:10:53', '2024-06-12 08:10:53'),
(16, 36, 14, 'Paid', '2024-06-12 09:53:57', '2024-06-12 09:53:57'),
(17, 37, 15, 'Paid', '2024-06-12 10:38:10', '2024-06-12 10:38:10'),
(18, 38, 15, 'Paid', '2024-06-12 10:40:54', '2024-06-12 10:40:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(5, 'Gilbert Naldoza', 'gilbertnaldoza@gmail.com', '$2y$10$blLtQzlIELdttEptDOjMMeJvP0G8fHLy5sMl8TEcW.9LX8dCIr9/K', 'admin'),
(6, 'Joci Naldoza', 'joci@gmail.com', '$2y$10$7cPL3lJ5tGPdfd06LIng7uSAh9TsTYx8jBn5i52E.zhMR6NAtxUCW', 'admin'),
(7, 'Gilbert Naldoza', 'gilbert@gmail.com', '$2y$10$VbZVk2RGdsAybS1HuRID6ONZOZKnTQoMACpML4oFOOPsvj2CDMzx.', 'customer'),
(8, 'Joci Naldoza', 'joci123@gmail.com', '$2y$10$2Kf/tZ9y7ASRnrsUD2iOMuwxWc20CgN.yexhetJQMFM.k8wpnmI3y', 'admin'),
(9, 'Gilbert Naldoza', 'gnaldoza@gmail.com', '$2y$10$RRz4PeJ2OgAKqTybutJeYOKl4MSyL.PxNvO.IIZmtZfHrmRE928US', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`sale_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `sales_report`
--
ALTER TABLE `sales_report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `sale_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `sales_report`
--
ALTER TABLE `sales_report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `sales_report`
--
ALTER TABLE `sales_report`
  ADD CONSTRAINT `sales_report_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`sale_id`),
  ADD CONSTRAINT `sales_report_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
