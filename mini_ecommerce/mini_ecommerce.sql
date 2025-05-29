-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Bulan Mei 2025 pada 10.57
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_ecommerce`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `restock_product` (IN `p_product_id` INT, IN `p_qty` INT)   BEGIN
  UPDATE products
    SET stock = stock + p_qty
    WHERE product_id = p_product_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `added_at` datetime DEFAULT current_timestamp(),
  `status` enum('pending','approved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cart_items`
--

INSERT INTO `cart_items` (`cart_id`, `customer_id`, `product_id`, `qty`, `added_at`, `status`) VALUES
(3, 2, 1, 12, '2025-05-27 15:23:35', 'approved'),
(4, 2, 1, 12, '2025-05-27 15:24:43', 'approved'),
(5, 2, 2, 2, '2025-05-27 15:27:06', 'approved'),
(6, 2, 1, 1, '2025-05-27 15:39:04', ''),
(7, 2, 1, 1, '2025-05-27 15:44:25', ''),
(8, 2, 1, 1, '2025-05-27 15:49:01', ''),
(9, 2, 1, 1, '2025-05-27 15:49:24', ''),
(10, 2, 1, 1, '2025-05-27 15:53:30', ''),
(11, 2, 1, 1, '2025-05-27 15:54:07', ''),
(12, 2, 1, 1, '2025-05-27 15:56:31', '');

--
-- Trigger `cart_items`
--
DELIMITER $$
CREATE TRIGGER `log_keluar_transaksi` AFTER UPDATE ON `cart_items` FOR EACH ROW BEGIN
  IF NEW.status = 'approved' AND OLD.status = 'pending' THEN
    INSERT INTO product_logs (product_id, type, qty, description)
    VALUES (NEW.product_id, 'keluar', NEW.qty, CONCAT('Transaksi customer ID ', NEW.customer_id));
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `role` enum('customer','admin') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `email`, `phone`, `address`, `created_at`, `password`, `role`) VALUES
(1, 'bagas ', 'bagashungkul@gmail.com', '', '', '2025-05-26 11:35:21', '$2y$10$0Nvlgz4m5VmOGmY6dOaRS.3TcbPw8WoCV9lxDdq0Q0MFHsgRGsoWe', 'admin'),
(2, '1', '1@1', '', '', '2025-05-26 11:51:04', '$2y$10$T3NiBJlY6Y2qdxeh20s0R.YjA7lLzLB9MCzpXDKyapz24.ysSHo0y', 'customer'),
(3, 'cek', 'cek@gmail.com', '', '', '2025-05-26 11:51:40', '$2y$10$s/Op0h8VHni1..PMx0/zIuX5p7EE1F7VH46umLTf1yIx1rM7UfqHO', 'customer'),
(4, 'gas', 'gas@gas', NULL, NULL, '2025-05-27 14:34:36', '$2y$10$t/Mq2zM395QfLCs5n7SEGeFt0MCesWAdxrCEc2jWC2q8U7DfKlg.K', 'customer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`, `created_at`) VALUES
(1, 'sabun', 'ini biasa digunakan untuk mandi', 3000.00, 134, '2025-05-26 11:17:35'),
(2, 'ikan', 'berenang', 1000.00, 5, '2025-05-26 12:19:18'),
(3, 'galon', 'biasa', 24000.00, 12, '2025-05-27 14:47:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_logs`
--

CREATE TABLE `product_logs` (
  `log_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `type` enum('masuk','keluar') NOT NULL,
  `qty` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_logs`
--

INSERT INTO `product_logs` (`log_id`, `product_id`, `type`, `qty`, `description`, `created_at`) VALUES
(1, 1, 'masuk', 1, 'Penambahan stok manual oleh admin', '2025-05-27 08:08:01'),
(2, 1, 'masuk', 123, 'Penambahan stok manual oleh admin', '2025-05-27 08:08:03'),
(3, 1, 'keluar', 12, 'Transaksi customer ID 2', '2025-05-27 08:38:56'),
(4, 1, 'keluar', 12, 'Transaksi customer ID 2', '2025-05-27 08:38:56'),
(5, 2, 'keluar', 2, 'Transaksi customer ID 2', '2025-05-27 08:38:56');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indeks untuk tabel `product_logs`
--
ALTER TABLE `product_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `product_logs`
--
ALTER TABLE `product_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_logs`
--
ALTER TABLE `product_logs`
  ADD CONSTRAINT `product_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
