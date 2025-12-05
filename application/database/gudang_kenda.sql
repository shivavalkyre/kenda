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

 Date: 05/12/2025 16:24:25
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
  `size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `net_weight` decimal(10, 3) NULL DEFAULT NULL,
  `gross_weight` decimal(10, 3) NULL DEFAULT NULL,
  `no_po` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `item_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kenda_size` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `part_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `cfr` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `kode_barang`(`kode_barang` ASC) USING BTREE,
  INDEX `kategori`(`kategori` ASC) USING BTREE,
  INDEX `status`(`status` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of barang
-- ----------------------------
INSERT INTO `barang` VALUES (1, 'TUB001', 'Tube Standard 17\"', 'Tube', 350, 50, 'PCS', 'Tube untuk ban 17 inch', 'aktif', '2025-12-01 11:05:19', '2025-12-05 11:12:40', '20\" X 1.75', 'BLACK', 15.200, 15.600, '251000528', 'TRERBBKBKK0120175K924', NULL, NULL, NULL, '1.8CFT');
INSERT INTO `barang` VALUES (2, 'TIR001', 'Tire Radial 205/55/R16', 'Tire', 150, 21, 'PCS', 'Ban radial ukuran 205/55/R16', 'aktif', '2025-12-01 11:05:19', '2025-12-05 11:12:29', '20\" X 1.75', 'BLACK', 15.200, 15.600, '251000528', 'TRERBBKBKK0120175K924', NULL, NULL, NULL, '1.8CFT');
INSERT INTO `barang` VALUES (3, 'TUB002', 'Tube Heavy Duty 19\"', 'Tube', 17, 16, 'PCS', 'Tube heavy duty untuk truck', 'aktif', '2025-12-01 11:05:19', '2025-12-05 11:12:32', '20\" X 1.75', 'BLACK', 15.200, 15.600, '251000528', 'TRERBBKBKK0120175K924', NULL, NULL, NULL, '1.8CFT');
INSERT INTO `barang` VALUES (4, 'TIR002', 'Tire Offroad 265/70/R16', 'Tire', 10, 6, 'PCS', 'Ban offroad ukuran 265/70/R16', 'aktif', '2025-12-01 11:05:19', '2025-12-05 14:40:07', '20\" X 1.75', 'BLACK', 15.200, 15.600, '251000528', 'TRERBBKBKK0120175K924', NULL, NULL, NULL, '1.8CFT');
INSERT INTO `barang` VALUES (5, 'TUB003', 'Tube Racing 15\"', 'Tube', 45, 10, 'PCS', 'Tube racing untuk mobil sport', 'aktif', '2025-12-01 11:05:19', '2025-12-05 11:12:37', '20\" X 1.75', 'BLACK', 15.200, 15.600, '251000528', 'TRERBBKBKK0120175K924', NULL, NULL, NULL, '1.8CFT');
INSERT INTO `barang` VALUES (8, 'TIR003', 'Tire 22\"', 'Tire', 1, 5, 'PCS', '', 'aktif', '2025-12-05 16:02:38', '2025-12-05 16:03:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

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
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` int NULL DEFAULT NULL,
  `scan_time` datetime NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_label_id`(`label_id` ASC) USING BTREE,
  INDEX `idx_scan_time`(`scan_time` ASC) USING BTREE,
  CONSTRAINT `fk_label_scan_logs_label_id` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of label_scan_logs
-- ----------------------------

-- ----------------------------
-- Table structure for labels
-- ----------------------------
DROP TABLE IF EXISTS `labels`;
CREATE TABLE `labels`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `label_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `packing_id` int NOT NULL,
  `label_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'single',
  `parent_label_id` int NULL DEFAULT NULL,
  `label_format` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'kenda',
  `bale_number` int NULL DEFAULT 1,
  `total_bales` int NULL DEFAULT 1,
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'active',
  `printed_at` datetime NULL DEFAULT NULL,
  `scanned_out_at` datetime NULL DEFAULT NULL,
  `scanned_in_at` datetime NULL DEFAULT NULL,
  `completed_at` datetime NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT current_timestamp,
  `updated_at` datetime NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `label_code`(`label_code` ASC) USING BTREE,
  INDEX `idx_packing_id`(`packing_id` ASC) USING BTREE,
  INDEX `idx_label_code`(`label_code` ASC) USING BTREE,
  INDEX `idx_parent_id`(`parent_label_id` ASC) USING BTREE,
  INDEX `idx_status`(`status` ASC) USING BTREE,
  CONSTRAINT `fk_labels_packing_id` FOREIGN KEY (`packing_id`) REFERENCES `packing_list` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 59 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of labels
-- ----------------------------
INSERT INTO `labels` VALUES (1, 'ML202512040005000', 5, 'master', NULL, 'btg', 1, 25, 'active', NULL, NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (2, 'CL202512040005001', 5, 'child', 1, 'btg', 1, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (3, 'CL202512040005002', 5, 'child', 1, 'btg', 2, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (4, 'CL202512040005003', 5, 'child', 1, 'btg', 3, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (5, 'CL202512040005004', 5, 'child', 1, 'btg', 4, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (6, 'CL202512040005005', 5, 'child', 1, 'btg', 5, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (7, 'CL202512040005006', 5, 'child', 1, 'btg', 6, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (8, 'CL202512040005007', 5, 'child', 1, 'btg', 7, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (9, 'CL202512040005008', 5, 'child', 1, 'btg', 8, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (10, 'CL202512040005009', 5, 'child', 1, 'btg', 9, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (11, 'CL202512040005010', 5, 'child', 1, 'btg', 10, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (12, 'CL202512040005011', 5, 'child', 1, 'btg', 11, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (13, 'CL202512040005012', 5, 'child', 1, 'btg', 12, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (14, 'CL202512040005013', 5, 'child', 1, 'btg', 13, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (15, 'CL202512040005014', 5, 'child', 1, 'btg', 14, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (16, 'CL202512040005015', 5, 'child', 1, 'btg', 15, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (17, 'CL202512040005016', 5, 'child', 1, 'btg', 16, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (18, 'CL202512040005017', 5, 'child', 1, 'btg', 17, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (19, 'CL202512040005018', 5, 'child', 1, 'btg', 18, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (20, 'CL202512040005019', 5, 'child', 1, 'btg', 19, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (21, 'CL202512040005020', 5, 'child', 1, 'btg', 20, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (22, 'CL202512040005021', 5, 'child', 1, 'btg', 21, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (23, 'CL202512040005022', 5, 'child', 1, 'btg', 22, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (24, 'CL202512040005023', 5, 'child', 1, 'btg', 23, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (25, 'CL202512040005024', 5, 'child', 1, 'btg', 24, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (26, 'CL202512040005025', 5, 'child', 1, 'btg', 25, 25, 'printed', '2025-12-04 15:20:21', NULL, NULL, NULL, '2025-12-04 15:20:21', '2025-12-04 15:20:21');
INSERT INTO `labels` VALUES (27, 'LBL202512040005001', 5, 'single', NULL, 'standard', 1, 1, 'printed', '2025-12-04 15:25:35', NULL, NULL, NULL, '2025-12-04 15:25:35', '2025-12-04 15:25:35');
INSERT INTO `labels` VALUES (28, 'ML202512040005000951', 5, 'master', NULL, 'kenda', 1, 25, 'active', NULL, NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (29, 'CL202512040005001102', 5, 'child', 28, 'kenda', 1, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (30, 'CL202512040005002517', 5, 'child', 28, 'kenda', 2, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (31, 'CL202512040005003357', 5, 'child', 28, 'kenda', 3, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (32, 'CL202512040005004116', 5, 'child', 28, 'kenda', 4, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (33, 'CL202512040005005276', 5, 'child', 28, 'kenda', 5, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (34, 'CL202512040005006121', 5, 'child', 28, 'kenda', 6, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (35, 'CL202512040005007817', 5, 'child', 28, 'kenda', 7, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (36, 'CL202512040005008730', 5, 'child', 28, 'kenda', 8, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (37, 'CL202512040005009800', 5, 'child', 28, 'kenda', 9, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (38, 'CL202512040005010493', 5, 'child', 28, 'kenda', 10, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (39, 'CL202512040005011670', 5, 'child', 28, 'kenda', 11, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (40, 'CL202512040005012426', 5, 'child', 28, 'kenda', 12, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (41, 'CL202512040005013527', 5, 'child', 28, 'kenda', 13, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (42, 'CL202512040005014723', 5, 'child', 28, 'kenda', 14, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (43, 'CL202512040005015786', 5, 'child', 28, 'kenda', 15, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (44, 'CL202512040005016438', 5, 'child', 28, 'kenda', 16, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (45, 'CL202512040005017492', 5, 'child', 28, 'kenda', 17, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (46, 'CL202512040005018422', 5, 'child', 28, 'kenda', 18, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (47, 'CL202512040005019245', 5, 'child', 28, 'kenda', 19, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (48, 'CL202512040005020929', 5, 'child', 28, 'kenda', 20, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (49, 'CL202512040005021651', 5, 'child', 28, 'kenda', 21, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (50, 'CL202512040005022306', 5, 'child', 28, 'kenda', 22, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (51, 'CL202512040005023231', 5, 'child', 28, 'kenda', 23, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (52, 'CL202512040005024913', 5, 'child', 28, 'kenda', 24, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (53, 'CL202512040005025454', 5, 'child', 28, 'kenda', 25, 25, 'printed', '2025-12-04 15:26:02', NULL, NULL, NULL, '2025-12-04 15:26:02', '2025-12-04 15:26:02');
INSERT INTO `labels` VALUES (54, 'LBL202512040005001963', 5, 'single', NULL, 'kenda', 1, 1, 'printed', '2025-12-04 15:26:41', NULL, NULL, NULL, '2025-12-04 15:26:41', '2025-12-04 15:26:41');
INSERT INTO `labels` VALUES (55, 'LBL202512040005001854', 5, 'single', NULL, 'kenda', 1, 1, 'printed', '2025-12-04 15:27:33', NULL, NULL, NULL, '2025-12-04 15:27:33', '2025-12-04 15:27:33');
INSERT INTO `labels` VALUES (56, 'LBL202512040005001824', 5, 'single', NULL, 'kenda', 1, 1, 'printed', '2025-12-04 16:03:49', NULL, NULL, NULL, '2025-12-04 16:03:49', '2025-12-04 16:03:49');
INSERT INTO `labels` VALUES (57, 'LBL202512040005001413', 5, 'single', NULL, 'standard', 1, 1, 'printed', '2025-12-04 16:12:57', NULL, NULL, NULL, '2025-12-04 16:12:57', '2025-12-04 16:12:57');
INSERT INTO `labels` VALUES (58, 'LBL202512040005001911', 5, 'single', NULL, 'kenda', 1, 1, 'printed', '2025-12-04 16:13:09', NULL, NULL, NULL, '2025-12-04 16:13:09', '2025-12-04 16:13:09');

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
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of log_stok
-- ----------------------------
INSERT INTO `log_stok` VALUES (1, 'TIR002', 'stok_awal', 10, NULL, NULL, NULL, NULL, NULL, NULL, 'Stok awal barang', '2025-12-02', '2025-12-02 05:46:38');
INSERT INTO `log_stok` VALUES (3, 'TUB002', 'masuk', 9, NULL, NULL, '', NULL, '', NULL, 'Barang masuk', '2025-12-02', '2025-12-02 07:03:09');
INSERT INTO `log_stok` VALUES (4, 'TUB002', 'keluar', 1, NULL, NULL, NULL, '', NULL, 'SJ001', 'Barang keluar', '2025-12-02', '2025-12-02 07:03:36');
INSERT INTO `log_stok` VALUES (5, 'TUB002', 'masuk', 1, NULL, NULL, '', NULL, '', NULL, 'Barang masuk', '2025-12-02', '2025-12-02 07:03:51');
INSERT INTO `log_stok` VALUES (6, 'TIR002', 'stok_awal', 10, NULL, NULL, NULL, NULL, NULL, NULL, 'Stok awal barang', '2025-12-05', '2025-12-05 11:13:46');
INSERT INTO `log_stok` VALUES (7, 'TIR002', 'masuk', 1, NULL, NULL, '', NULL, '', NULL, 'Barang masuk', '2025-12-05', '2025-12-05 11:14:07');
INSERT INTO `log_stok` VALUES (8, 'TIR002', 'keluar', 1, NULL, NULL, NULL, '', NULL, 'SJ001', 'Barang keluar', '2025-12-05', '2025-12-05 11:14:20');
INSERT INTO `log_stok` VALUES (9, 'TIR002', 'adjustment', -5, 10, 5, NULL, NULL, NULL, NULL, 'Lainnya', '2025-12-05', '2025-12-05 14:39:44');
INSERT INTO `log_stok` VALUES (10, 'TIR002', 'adjustment', 5, 5, 10, NULL, NULL, NULL, NULL, 'Lainnya', '2025-12-05', '2025-12-05 14:40:07');
INSERT INTO `log_stok` VALUES (11, 'TIR003', 'masuk', 1, NULL, NULL, '', NULL, '', NULL, 'Barang masuk', '2025-12-05', '2025-12-05 16:03:02');

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
INSERT INTO `packing_list` VALUES (1, 'PL001', '2024-03-20', 'Customer A', 'Jl. Customer A No. 123', NULL, 50, 'printed', 'completed', NULL, NULL, '2025-12-01 11:05:19', '2025-12-04 08:38:43', NULL, 'single');
INSERT INTO `packing_list` VALUES (2, 'PL002', '2024-03-20', 'Customer B', 'Jl. Customer B No. 456', NULL, 30, 'printed', 'pending', NULL, NULL, '2025-12-01 11:05:19', '2025-12-01 11:05:19', NULL, 'single');
INSERT INTO `packing_list` VALUES (3, 'PL003', '2024-03-19', 'Customer C', 'Jl. Customer C No. 789', NULL, 25, 'printed', 'pending', NULL, NULL, '2025-12-01 11:05:19', '2025-12-04 06:02:48', NULL, 'single');
INSERT INTO `packing_list` VALUES (4, 'PL004', '2024-03-19', 'Customer D', 'Jl. Customer D No. 101', NULL, 30, 'printed', 'scanned_in', NULL, NULL, '2025-12-01 11:05:19', '2025-12-04 08:49:27', NULL, 'single');
INSERT INTO `packing_list` VALUES (5, 'PL005', '2025-12-03', 'PT Wimcycle Indonesia', '-', '', 5, 'printed', 'pending', NULL, NULL, '2025-12-03 04:59:48', '2025-12-04 16:13:09', NULL, 'single');

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
