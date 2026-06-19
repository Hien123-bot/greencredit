-- phpMyAdmin SQL Dump
-- Database: `green_credit`

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `points` int(11) DEFAULT 0,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp DEFAULT current_timestamp(),
  `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `qr_codes`
--
CREATE TABLE `qr_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 50,
  `location` varchar(255) DEFAULT 'Trạm Xanh',
  `is_used` tinyint(1) DEFAULT 0,
  `used_by` int(11) DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `used_by` (`used_by`),
  CONSTRAINT `fk_qr_user` FOREIGN KEY (`used_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `history`
--
CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action_type` enum('earn','spend') NOT NULL,
  `points` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products` (Cho tính năng đổi quà tương lai)
--
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price_vnd` int(11) NOT NULL,
  `max_pts` int(11) NOT NULL DEFAULT 0,
  `category` varchar(50) DEFAULT 'Khác',
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 100,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders` (Cho tính năng đổi quà tương lai)
--
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subtotal_vnd` int(11) NOT NULL,
  `discount_vnd` int(11) DEFAULT 0,
  `points_used` int(11) DEFAULT 0,
  `total_vnd` int(11) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_order_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price_vnd` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_oi_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_oi_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--
CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_ci_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ci_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tasks`
--
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `points` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT 'eco',
  `expire_hours` int(11) DEFAULT 24,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_tasks`
--
CREATE TABLE `user_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `task_id` (`task_id`),
  CONSTRAINT `fk_ut_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ut_task` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Dữ liệu mẫu (Seeding)
--

-- Mật khẩu mẫu là '123456'
INSERT INTO `users` (`username`, `email`, `password`, `full_name`, `points`, `role`) VALUES
('admin', 'admin@greencredit.vn', '$2y$10$w8Ld.08t4G1Sj5qCq23xQ.N3G.q0o19/3n0t1/05o0N5a0N0o0t0', 'Admin Quản Trị', 99999, 'admin'),
('nguyenvana', 'vana@gmail.com', '$2y$10$w8Ld.08t4G1Sj5qCq23xQ.N3G.q0o19/3n0t1/05o0N5a0N0o0t0', 'Nguyễn Văn A', 2450, 'user');

INSERT INTO `qr_codes` (`code`, `points`, `location`, `is_used`) VALUES
('GREEN_START', 100, 'Trạm Xanh Quận 1', 0),
('GREEN_GIFT_2026', 500, 'Trạm Tái Chế Quận 3', 0),
('GREEN2026', 150, 'Trạm Xe Đạp Công Cộng', 0),
('RECYCLE_HERO', 200, 'Siêu Thị Co.opmart', 0);

INSERT INTO `history` (`user_id`, `action_type`, `points`, `description`) VALUES
(2, 'earn', 1000, 'Đăng ký tài khoản mới'),
(2, 'earn', 1450, 'Quét mã QR chiến dịch tháng Xanh');

INSERT INTO `products` (`name`, `price_vnd`, `max_pts`, `category`, `image`) VALUES
('Bình nước tre tự nhiên', 120000, 50, 'Gia dụng', 'assets/images/products/bamboo_bottle.png'),
('Túi tote canvas hữu cơ', 85000, 30, 'Thời trang', 'assets/images/products/tote_bag.png'),
('Bộ ống hút inox 304', 45000, 20, 'Gia dụng', 'assets/images/products/steel_straws.png');

INSERT INTO `tasks` (`title`, `description`, `points`, `icon`, `expire_hours`) VALUES
('Phân loại rác tại nguồn', 'Hãy chụp ảnh hoặc quét mã tại trạm tái chế sau khi bạn đã phân loại ít nhất 5 chai nhựa.', 50, 'recycling', 12),
('Di chuyển xanh', 'Sử dụng phương tiện công cộng hoặc xe đạp cho quãng đường ít nhất 3km trong ngày.', 100, 'directions_bike', 8);

COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
