/*
 Navicat Premium Data Transfer

 Source Server         : Emdeki
 Source Server Type    : MySQL
 Source Server Version : 100408 (10.4.8-MariaDB)
 Source Host           : 172.17.4.11:3306
 Source Schema         : gudang_kenda

 Target Server Type    : MySQL
 Target Server Version : 100408 (10.4.8-MariaDB)
 File Encoding         : 65001

 Date: 03/12/2025 16:05:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for barang
-- ----------------------------
DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('Tube','Tire') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stok` int NOT NULL DEFAULT 0,
  `stok_minimum` int NOT NULL DEFAULT 0,
  `satuan` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'PCS',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_barang`(`kode_barang` ASC) USING BTREE,
  INDEX `kategori`(`kategori` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of barang
-- ----------------------------
INSERT INTO `barang` VALUES (1, 'TUB001', 'Tube Standard 17\"', 'Tube', 350, 50, 'PCS', 'Tube untuk ban 17 inch', 'aktif', '2025-12-01 11:05:19', '2025-12-01 10:00:49');
INSERT INTO `barang` VALUES (2, 'TIR001', 'Tire Radial 205/55/R16', 'Tire', 150, 21, 'PCS', 'Ban radial ukuran 205/55/R16', 'aktif', '2025-12-01 11:05:19', '2025-12-02 01:43:11');
INSERT INTO `barang` VALUES (3, 'TUB002', 'Tube Heavy Duty 19\"', 'Tube', 17, 16, 'PCS', 'Tube heavy duty untuk truck', 'aktif', '2025-12-01 11:05:19', '2025-12-02 07:03:51');
INSERT INTO `barang` VALUES (4, 'TIR002', 'Tire Offroad 265/70/R16', 'Tire', 10, 6, 'PCS', 'Ban offroad ukuran 265/70/R16', 'aktif', '2025-12-01 11:05:19', '2025-12-02 05:46:38');
INSERT INTO `barang` VALUES (5, 'TUB003', 'Tube Racing 15\"', 'Tube', 45, 10, 'PCS', 'Tube racing untuk mobil sport', 'aktif', '2025-12-01 11:05:19', '2025-12-01 11:05:19');
INSERT INTO `barang` VALUES (6, 'TUB004', 'Tube Heavy Duty 22\"', 'Tube', 10, 5, 'PCS', 'Testing', 'nonaktif', '2025-12-02 06:17:14', '2025-12-03 04:56:29');

-- ----------------------------
-- Table structure for kategori
-- ----------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_kategori` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_kategori`(`kode_kategori` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori
-- ----------------------------
INSERT INTO `kategori` VALUES (1, 'TUB', 'Tube', 'Kategori untuk berbagai jenis tube ban', 'active', '2025-12-01 11:05:19', '2025-12-01 11:05:19');
INSERT INTO `kategori` VALUES (2, 'TIR', 'Tire', 'Kategori untuk berbagai jenis ban', 'active', '2025-12-01 11:05:19', '2025-12-01 11:05:19');

-- ----------------------------
-- Table structure for label_scan_logs
-- ----------------------------
DROP TABLE IF EXISTS `label_scan_logs`;
CREATE TABLE `label_scan_logs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `label_id` int NOT NULL,
  `action` enum('print','scan_out','scan_in','complete','void') CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `user_id` int NULL DEFAULT NULL,
  `scan_time` datetime NULL DEFAULT current_timestamp,
  `notes` text CHARACTER SET utf32 COLLATE utf32_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `label_id`(`label_id` ASC) USING BTREE,
  CONSTRAINT `label_scan_logs_ibfk_1` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf32 COLLATE = utf32_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of label_scan_logs
-- ----------------------------

-- ----------------------------
-- Table structure for labels
-- ----------------------------
DROP TABLE IF EXISTS `labels`;
CREATE TABLE `labels`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `label_code` varchar(50) CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL,
  `packing_id` int NOT NULL,
  `label_type` enum('single','master','child') CHARACTER SET utf32 COLLATE utf32_general_ci NULL DEFAULT 'single',
  `parent_label_id` int NULL DEFAULT NULL,
  `qr_code` text CHARACTER SET utf32 COLLATE utf32_general_ci NULL,
  `status` enum('active','printed','scanned_out','scanned_in','completed','void') CHARACTER SET utf32 COLLATE utf32_general_ci NULL DEFAULT 'active',
  `printed_at` datetime NULL DEFAULT NULL,
  `scanned_out_at` datetime NULL DEFAULT NULL,
  `scanned_in_at` datetime NULL DEFAULT NULL,
  `completed_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT current_timestamp,
  `updated_at` datetime NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `label_code`(`label_code` ASC) USING BTREE,
  INDEX `packing_id`(`packing_id` ASC) USING BTREE,
  INDEX `parent_label_id`(`parent_label_id` ASC) USING BTREE,
  CONSTRAINT `labels_ibfk_1` FOREIGN KEY (`packing_id`) REFERENCES `packing_list` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `labels_ibfk_2` FOREIGN KEY (`parent_label_id`) REFERENCES `labels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf32 COLLATE = utf32_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of labels
-- ----------------------------

-- ----------------------------
-- Table structure for log_stok
-- ----------------------------
DROP TABLE IF EXISTS `log_stok`;
CREATE TABLE `log_stok`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` enum('stok_awal','masuk','keluar','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int NOT NULL DEFAULT 0,
  `stok_sebelum` int NULL DEFAULT NULL,
  `stok_sesudah` int NULL DEFAULT NULL,
  `supplier` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `customer` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_po` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_sj` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `tanggal` date NOT NULL,
  `created_at` datetime NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_kode_barang`(`kode_barang` ASC) USING BTREE,
  INDEX `idx_tanggal`(`tanggal` ASC) USING BTREE,
  INDEX `idx_jenis`(`jenis` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log_stok
-- ----------------------------
INSERT INTO `log_stok` VALUES (1, 'TIR002', 'stok_awal', 10, NULL, NULL, NULL, NULL, NULL, NULL, 'Stok awal barang', '2025-12-02', '2025-12-02 05:46:38');
INSERT INTO `log_stok` VALUES (2, 'TUB004', 'stok_awal', 10, NULL, NULL, NULL, NULL, NULL, NULL, 'Stok awal barang', '2025-12-02', '2025-12-02 07:02:01');
INSERT INTO `log_stok` VALUES (3, 'TUB002', 'masuk', 9, NULL, NULL, '', NULL, '', NULL, 'Barang masuk', '2025-12-02', '2025-12-02 07:03:09');
INSERT INTO `log_stok` VALUES (4, 'TUB002', 'keluar', 1, NULL, NULL, NULL, '', NULL, 'SJ001', 'Barang keluar', '2025-12-02', '2025-12-02 07:03:36');
INSERT INTO `log_stok` VALUES (5, 'TUB002', 'masuk', 1, NULL, NULL, '', NULL, '', NULL, 'Barang masuk', '2025-12-02', '2025-12-02 07:03:51');

-- ----------------------------
-- Table structure for packing_items
-- ----------------------------
DROP TABLE IF EXISTS `packing_items`;
CREATE TABLE `packing_items`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `packing_id` int NOT NULL,
  `kode_barang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('Tube','Tire') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `packing_id`(`packing_id` ASC) USING BTREE,
  INDEX `kode_barang`(`kode_barang` ASC) USING BTREE,
  CONSTRAINT `packing_items_ibfk_1` FOREIGN KEY (`packing_id`) REFERENCES `packing_list` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `packing_items_ibfk_2` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of packing_items
-- ----------------------------
INSERT INTO `packing_items` VALUES (1, 1, 'TUB001', 'Tube Standard 17\"', 'Tube', 15, '2025-12-01 11:05:19');
INSERT INTO `packing_items` VALUES (2, 1, 'TIR001', 'Tire Radial 205/55/R16', 'Tire', 35, '2025-12-01 11:05:19');
INSERT INTO `packing_items` VALUES (3, 2, 'TUB001', 'Tube Standard 17\"', 'Tube', 30, '2025-12-01 11:05:19');
INSERT INTO `packing_items` VALUES (4, 3, 'TIR001', 'Tire Radial 205/55/R16', 'Tire', 25, '2025-12-01 11:05:19');
INSERT INTO `packing_items` VALUES (5, 4, 'TUB001', 'Tube Standard 17\"', 'Tube', 20, '2025-12-01 11:05:19');
INSERT INTO `packing_items` VALUES (6, 4, 'TIR001', 'Tire Radial 205/55/R16', 'Tire', 10, '2025-12-01 11:05:19');
INSERT INTO `packing_items` VALUES (14, 5, 'TIR002', 'Tire Offroad 265/70/R16', 'Tire', 2, '2025-12-03 08:29:43');
INSERT INTO `packing_items` VALUES (15, 5, 'TIR001', 'Tire Radial 205/55/R16', 'Tire', 1, '2025-12-03 08:29:43');
INSERT INTO `packing_items` VALUES (16, 5, 'TUB003', 'Tube Racing 15\"', 'Tube', 1, '2025-12-03 08:29:43');
INSERT INTO `packing_items` VALUES (17, 5, 'TUB001', 'Tube Standard 17\"', 'Tube', 1, '2025-12-03 08:29:43');

-- ----------------------------
-- Table structure for packing_list
-- ----------------------------
DROP TABLE IF EXISTS `packing_list`;
CREATE TABLE `packing_list`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_packing` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `customer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `jumlah_item` int NOT NULL DEFAULT 0,
  `status_scan_out` enum('draft','printed','scanned_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'printed',
  `status_scan_in` enum('pending','scanned_in','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `scan_out_time` datetime NULL DEFAULT NULL,
  `scan_in_time` datetime NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  `label_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `label_type` enum('single','multiple') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'single',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `no_packing`(`no_packing` ASC) USING BTREE,
  INDEX `status_scan_out`(`status_scan_out` ASC) USING BTREE,
  INDEX `status_scan_in`(`status_scan_in` ASC) USING BTREE,
  INDEX `tanggal`(`tanggal` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of packing_list
-- ----------------------------
INSERT INTO `packing_list` VALUES (1, 'PL001', '2024-03-20', 'Customer A', 'Jl. Customer A No. 123', NULL, 50, 'scanned_out', 'completed', NULL, NULL, '2025-12-01 11:05:19', '2025-12-01 11:05:19', NULL, 'single');
INSERT INTO `packing_list` VALUES (2, 'PL002', '2024-03-20', 'Customer B', 'Jl. Customer B No. 456', NULL, 30, 'printed', 'pending', NULL, NULL, '2025-12-01 11:05:19', '2025-12-01 11:05:19', NULL, 'single');
INSERT INTO `packing_list` VALUES (3, 'PL003', '2024-03-19', 'Customer C', 'Jl. Customer C No. 789', NULL, 25, 'printed', 'pending', NULL, NULL, '2025-12-01 11:05:19', '2025-12-01 11:05:19', NULL, 'single');
INSERT INTO `packing_list` VALUES (4, 'PL004', '2024-03-19', 'Customer D', 'Jl. Customer D No. 101', NULL, 30, 'scanned_out', 'scanned_in', NULL, NULL, '2025-12-01 11:05:19', '2025-12-01 11:05:19', NULL, 'single');
INSERT INTO `packing_list` VALUES (5, 'PL005', '2025-12-03', 'PT Wimcycle Indonesia', '-', '', 5, 'printed', 'pending', NULL, NULL, '2025-12-03 04:59:48', '2025-12-03 08:29:43', NULL, 'single');

-- ----------------------------
-- Table structure for stok_mutations
-- ----------------------------
DROP TABLE IF EXISTS `stok_mutations`;
CREATE TABLE `stok_mutations`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` enum('masuk','keluar','opname') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int NOT NULL,
  `stok_sebelum` int NOT NULL,
  `stok_sesudah` int NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `referensi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `kode_barang`(`kode_barang` ASC) USING BTREE,
  INDEX `jenis`(`jenis` ASC) USING BTREE,
  CONSTRAINT `stok_mutations_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stok_mutations
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `last_login` datetime NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `username`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 'active', NULL, '2025-12-01 11:05:19');

SET FOREIGN_KEY_CHECKS = 1;
