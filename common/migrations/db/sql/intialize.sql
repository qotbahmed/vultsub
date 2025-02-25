/*
 Navicat Premium Dump SQL

 Source Server         : LocalHost
 Source Server Type    : MySQL
 Source Server Version : 80400 (8.4.0)
 Source Host           : 127.0.0.1:3306
 Source Schema         : erp

 Target Server Type    : MySQL
 Target Server Version : 80400 (8.4.0)
 File Encoding         : 65001

 Date: 29/10/2024 13:19:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
                           `id` int NOT NULL AUTO_INCREMENT,
                           `slug` varchar(1024) NOT NULL,
                           `title` varchar(512) NOT NULL,
                           `body` text NOT NULL,
                           `view` varchar(255) DEFAULT NULL,
                           `category_id` int DEFAULT NULL,
                           `thumbnail_base_url` varchar(1024) DEFAULT NULL,
                           `thumbnail_path` varchar(1024) DEFAULT NULL,
                           `status` smallint NOT NULL DEFAULT '0',
                           `created_by` int DEFAULT NULL,
                           `updated_by` int DEFAULT NULL,
                           `published_at` int DEFAULT NULL,
                           `created_at` int DEFAULT NULL,
                           `updated_at` int DEFAULT NULL,
                           PRIMARY KEY (`id`),
                           KEY `fk_article_author` (`created_by`),
                           KEY `fk_article_updater` (`updated_by`),
                           KEY `fk_article_category` (`category_id`),
                           CONSTRAINT `fk_article_author` FOREIGN KEY (`created_by`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                           CONSTRAINT `fk_article_category` FOREIGN KEY (`category_id`) REFERENCES `article_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                           CONSTRAINT `fk_article_updater` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of article
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for article_attachment
-- ----------------------------
DROP TABLE IF EXISTS `article_attachment`;
CREATE TABLE `article_attachment` (
                                      `id` int NOT NULL AUTO_INCREMENT,
                                      `article_id` int NOT NULL,
                                      `path` varchar(255) NOT NULL,
                                      `base_url` varchar(255) DEFAULT NULL,
                                      `type` varchar(255) DEFAULT NULL,
                                      `size` int DEFAULT NULL,
                                      `name` varchar(255) DEFAULT NULL,
                                      `created_at` int DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `fk_article_attachment_article` (`article_id`),
                                      CONSTRAINT `fk_article_attachment_article` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of article_attachment
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for article_category
-- ----------------------------
DROP TABLE IF EXISTS `article_category`;
CREATE TABLE `article_category` (
                                    `id` int NOT NULL AUTO_INCREMENT,
                                    `slug` varchar(1024) NOT NULL,
                                    `title` varchar(512) NOT NULL,
                                    `body` text,
                                    `parent_id` int DEFAULT NULL,
                                    `status` smallint NOT NULL DEFAULT '0',
                                    `created_at` int DEFAULT NULL,
                                    `updated_at` int DEFAULT NULL,
                                    PRIMARY KEY (`id`),
                                    KEY `fk_article_category_section` (`parent_id`),
                                    CONSTRAINT `fk_article_category_section` FOREIGN KEY (`parent_id`) REFERENCES `article_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of article_category
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
                            `id` int unsigned NOT NULL AUTO_INCREMENT,
                            `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
                            `created_at` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
                            `updated_at` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
                            `created_by` int DEFAULT NULL,
                            `updated_by` int DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of category
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for contact_us
-- ----------------------------
DROP TABLE IF EXISTS `contact_us`;
CREATE TABLE `contact_us` (
                              `id` int unsigned NOT NULL AUTO_INCREMENT,
                              `name` varchar(255) NOT NULL,
                              `phone` varchar(255) DEFAULT NULL,
                              `email` varchar(255) DEFAULT NULL,
                              `title` varchar(255) DEFAULT NULL,
                              `message` text,
                              `created_at` varchar(255) DEFAULT NULL,
                              `updated_at` varchar(255) DEFAULT NULL,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of contact_us
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for faq
-- ----------------------------
DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
                       `id` int unsigned NOT NULL AUTO_INCREMENT,
                       `question` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
                       `answer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
                       `sort` int DEFAULT NULL,
                       `status` tinyint DEFAULT '0',
                       `category_id` int unsigned NOT NULL,
                       `created_at` int DEFAULT NULL,
                       `updated_at` int DEFAULT NULL,
                       `created_by` int DEFAULT NULL,
                       `updated_by` int DEFAULT NULL,
                       PRIMARY KEY (`id`),
                       KEY `category_id` (`category_id`),
                       CONSTRAINT `faq_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of faq
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for file_storage_item
-- ----------------------------
DROP TABLE IF EXISTS `file_storage_item`;
CREATE TABLE `file_storage_item` (
                                     `id` int NOT NULL AUTO_INCREMENT,
                                     `component` varchar(255) NOT NULL,
                                     `base_url` varchar(1024) NOT NULL,
                                     `path` varchar(1024) NOT NULL,
                                     `type` varchar(255) DEFAULT NULL,
                                     `size` int DEFAULT NULL,
                                     `name` varchar(255) DEFAULT NULL,
                                     `upload_ip` varchar(45) DEFAULT NULL,
                                     `created_at` int NOT NULL,
                                     PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of file_storage_item
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for gallery
-- ----------------------------
DROP TABLE IF EXISTS `gallery`;
CREATE TABLE `gallery` (
                           `id` int unsigned NOT NULL AUTO_INCREMENT,
                           `title` varchar(255) NOT NULL,
                           `sort` int DEFAULT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of gallery
-- ----------------------------
BEGIN;
INSERT INTO `gallery` (`id`, `title`, `sort`) VALUES (1, 'Home Page Gallery', NULL);
COMMIT;

-- ----------------------------
-- Table structure for gallery_photos
-- ----------------------------
DROP TABLE IF EXISTS `gallery_photos`;
CREATE TABLE `gallery_photos` (
                                  `id` int NOT NULL AUTO_INCREMENT,
                                  `gallery_id` int unsigned NOT NULL,
                                  `path` varchar(255) NOT NULL,
                                  `base_url` varchar(255) DEFAULT NULL,
                                  `type` varchar(255) DEFAULT NULL,
                                  `size` int DEFAULT NULL,
                                  `name` varchar(255) DEFAULT NULL,
                                  `created_at` varchar(255) DEFAULT NULL,
                                  `updated_at` varchar(255) DEFAULT NULL,
                                  `order` int DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `gallery_id` (`gallery_id`),
                                  CONSTRAINT `gallery_photos_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of gallery_photos
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for i18n_message
-- ----------------------------
DROP TABLE IF EXISTS `i18n_message`;
CREATE TABLE `i18n_message` (
                                `id` int NOT NULL,
                                `language` varchar(16) NOT NULL,
                                `translation` text,
                                PRIMARY KEY (`id`,`language`),
                                CONSTRAINT `fk_i18n_message_source_message` FOREIGN KEY (`id`) REFERENCES `i18n_source_message` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of i18n_message
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for i18n_source_message
-- ----------------------------
DROP TABLE IF EXISTS `i18n_source_message`;
CREATE TABLE `i18n_source_message` (
                                       `id` int NOT NULL AUTO_INCREMENT,
                                       `category` varchar(32) DEFAULT NULL,
                                       `message` text,
                                       PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of i18n_source_message
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for key_storage_item
-- ----------------------------
DROP TABLE IF EXISTS `key_storage_item`;
CREATE TABLE `key_storage_item` (
                                    `key` varchar(128) NOT NULL,
                                    `value` text NOT NULL,
                                    `comment` text,
                                    `updated_at` int DEFAULT NULL,
                                    `created_at` int DEFAULT NULL,
                                    PRIMARY KEY (`key`),
                                    UNIQUE KEY `idx_key_storage_item_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of key_storage_item
-- ----------------------------
BEGIN;
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.body-small-text', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.brand-small-text', '0', NULL, 1648544146, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.footer-fixed', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.footer-small-text', '0', NULL, 1648544146, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.navbar-fixed', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.navbar-small-text', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.no-navbar-border', '0', NULL, 1648544146, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-child-indent', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-collapsed', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-compact', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-fixed', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-flat', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-legacy', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-mini', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-no-expand', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('adminlte.sidebar-small-text', '0', NULL, 1648506973, 1648506973);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('backend.layout-boxed', '0', NULL, NULL, NULL);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('backend.layout-collapsed-sidebar', '0', NULL, NULL, NULL);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('backend.layout-fixed', '0', NULL, NULL, NULL);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('backend.theme-skin', 'skin-blue', 'skin-blue, skin-black, skin-purple, skin-green, skin-red, skin-yellow', NULL, NULL);
INSERT INTO `key_storage_item` (`key`, `value`, `comment`, `updated_at`, `created_at`) VALUES ('frontend.maintenance', 'disabled', 'Set it to \"enabled\" to turn on maintenance mode', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for nationality
-- ----------------------------
DROP TABLE IF EXISTS `nationality`;
CREATE TABLE `nationality` (
                               `id` int unsigned NOT NULL AUTO_INCREMENT,
                               `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
                               `created_at` int DEFAULT NULL,
                               `updated_at` int DEFAULT NULL,
                               `created_by` int DEFAULT NULL,
                               `updated_by` int DEFAULT NULL,
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- ----------------------------
-- Records of nationality
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
                                 `id` int unsigned NOT NULL AUTO_INCREMENT,
                                 `key_id` int DEFAULT NULL,
                                 `topic` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `from_id` int DEFAULT NULL,
                                 `to_id` int DEFAULT NULL,
                                 `module` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `module_id` int DEFAULT NULL,
                                 `title_ar` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `title_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `message_ar` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `message_en` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `action` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `seen` tinyint DEFAULT NULL,
                                 `payload` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin,
                                 `created_at` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `updated_at` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `created_by` int DEFAULT NULL,
                                 `updated_by` int DEFAULT NULL,
                                 `route` char(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                                 `request_id` int DEFAULT NULL,
                                 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;

-- ----------------------------
-- Records of notifications
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for page
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
                        `id` int NOT NULL AUTO_INCREMENT,
                        `slug` varchar(255) NOT NULL,
                        `title` varchar(255) NOT NULL,
                        `body` text NOT NULL,
                        `view` varchar(255) DEFAULT NULL,
                        `image_base_url` varchar(255) DEFAULT NULL,
                        `image_path` varchar(255) DEFAULT NULL,
                        `status` smallint NOT NULL,
                        `created_at` varchar(200) DEFAULT NULL,
                        `updated_at` varchar(200) DEFAULT NULL,
                        `created_by` int DEFAULT NULL,
                        `updated_by` int DEFAULT NULL,
                        PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of page
-- ----------------------------
BEGIN;
INSERT INTO `page` (`id`, `slug`, `title`, `body`, `view`, `image_base_url`, `image_path`, `status`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (2, 'about', 'About', 'about the app', NULL, '', '', 1, '2022-03-30 16:17:45', '2022-04-04 12:14:20', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for rbac_auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `rbac_auth_assignment`;
CREATE TABLE `rbac_auth_assignment` (
                                        `item_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                                        `user_id` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                                        `created_at` int DEFAULT NULL,
                                        PRIMARY KEY (`item_name`,`user_id`),
                                        CONSTRAINT `rbac_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `rbac_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of rbac_auth_assignment
-- ----------------------------
BEGIN;
INSERT INTO `rbac_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES ('administrator', '1', 1648506906);
INSERT INTO `rbac_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES ('manager', '2', 1648997010);
INSERT INTO `rbac_auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES ('user', '3', 1683568853);
COMMIT;

-- ----------------------------
-- Table structure for rbac_auth_item
-- ----------------------------
DROP TABLE IF EXISTS `rbac_auth_item`;
CREATE TABLE `rbac_auth_item` (
                                  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                                  `type` smallint NOT NULL,
                                  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
                                  `rule_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                                  `data` blob,
                                  `created_at` int DEFAULT NULL,
                                  `updated_at` int DEFAULT NULL,
                                  PRIMARY KEY (`name`),
                                  KEY `rule_name` (`rule_name`),
                                  KEY `idx-auth_item-type` (`type`),
                                  CONSTRAINT `rbac_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `rbac_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of rbac_auth_item
-- ----------------------------
BEGIN;
INSERT INTO `rbac_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('administrator', 1, NULL, NULL, NULL, 1648506906, 1648506906);
INSERT INTO `rbac_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('editOwnModel', 2, NULL, 'ownModelRule', NULL, 1648506906, 1648506906);
INSERT INTO `rbac_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('loginToBackend', 2, NULL, NULL, NULL, 1648506906, 1648506906);
INSERT INTO `rbac_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('manager', 1, NULL, NULL, NULL, 1648506906, 1648506906);
INSERT INTO `rbac_auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) VALUES ('user', 1, NULL, NULL, NULL, 1648506906, 1648506906);
COMMIT;

-- ----------------------------
-- Table structure for rbac_auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `rbac_auth_item_child`;
CREATE TABLE `rbac_auth_item_child` (
                                        `parent` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                                        `child` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                                        PRIMARY KEY (`parent`,`child`),
                                        KEY `child` (`child`),
                                        CONSTRAINT `rbac_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `rbac_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
                                        CONSTRAINT `rbac_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `rbac_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of rbac_auth_item_child
-- ----------------------------
BEGIN;
INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('user', 'editOwnModel');
INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('administrator', 'loginToBackend');
INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('manager', 'loginToBackend');
INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('administrator', 'manager');
INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('administrator', 'user');
INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('manager', 'user');
COMMIT;

-- ----------------------------
-- Table structure for rbac_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `rbac_auth_rule`;
CREATE TABLE `rbac_auth_rule` (
                                  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                                  `data` blob,
                                  `created_at` int DEFAULT NULL,
                                  `updated_at` int DEFAULT NULL,
                                  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Records of rbac_auth_rule
-- ----------------------------
BEGIN;
INSERT INTO `rbac_auth_rule` (`name`, `data`, `created_at`, `updated_at`) VALUES ('ownModelRule', 0x4F3A32393A22636F6D6D6F6E5C726261635C72756C655C4F776E4D6F64656C52756C65223A333A7B733A343A226E616D65223B733A31323A226F776E4D6F64656C52756C65223B733A393A22637265617465644174223B693A313634383530363930363B733A393A22757064617465644174223B693A313634383530363930363B7D, 1648506906, 1648506906);
COMMIT;

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
                            `id` int unsigned NOT NULL AUTO_INCREMENT,
                            `website_title` varchar(255) DEFAULT NULL,
                            `phone` varchar(255) DEFAULT NULL,
                            `email` varchar(255) DEFAULT NULL,
                            `notification_email` varchar(255) DEFAULT NULL,
                            `address` varchar(255) DEFAULT NULL,
                            `facebook` varchar(255) DEFAULT NULL,
                            `youtube` varchar(255) DEFAULT NULL,
                            `twitter` varchar(255) DEFAULT NULL,
                            `instagram` varchar(255) DEFAULT NULL,
                            `linkedin` varchar(255) DEFAULT NULL,
                            `whatsapp` varchar(255) DEFAULT NULL,
                            `app_ios` varchar(255) DEFAULT NULL,
                            `app_android` varchar(255) DEFAULT NULL,
                            `video_url` varchar(255) DEFAULT NULL,
                            `created_at` varchar(255) DEFAULT NULL,
                            `updated_at` varchar(255) DEFAULT NULL,
                            `created_by` int DEFAULT NULL,
                            `updated_by` int DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of settings
-- ----------------------------
BEGIN;
INSERT INTO `settings` (`id`, `website_title`, `phone`, `email`, `notification_email`, `address`, `facebook`, `youtube`, `twitter`, `instagram`, `linkedin`, `whatsapp`, `app_ios`, `app_android`, `video_url`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES (1, 'template', '', '', '', '', '', '', '', '', '', '', '', '', NULL, NULL, '2023-10-29 12:42:00', NULL, 1);
COMMIT;

-- ----------------------------
-- Table structure for sms_log
-- ----------------------------
DROP TABLE IF EXISTS `sms_log`;
CREATE TABLE `sms_log` (
                           `id` int unsigned NOT NULL AUTO_INCREMENT,
                           `mobile` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
                           `user_id` int DEFAULT NULL,
                           `otp` int DEFAULT NULL,
                           `type` tinyint DEFAULT '0' COMMENT '0  user register ',
                           `expire_at` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
                           `status` tinyint DEFAULT '0' COMMENT '0 new 1 registed',
                           `created_at` int DEFAULT NULL,
                           `updated_at` int DEFAULT NULL,
                           `created_by` int DEFAULT NULL,
                           `updated_by` int DEFAULT NULL,
                           PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;



-- ----------------------------
-- Table structure for system_db_migration
-- ----------------------------
DROP TABLE IF EXISTS `system_db_migration`;
CREATE TABLE `system_db_migration` (
                                       `version` varchar(180) NOT NULL,
                                       `apply_time` int DEFAULT NULL,
                                       PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--

-- ----------------------------
-- Table structure for system_log
-- ----------------------------
DROP TABLE IF EXISTS `system_log`;
CREATE TABLE `system_log` (
                              `id` bigint NOT NULL AUTO_INCREMENT,
                              `level` int DEFAULT NULL,
                              `category` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                              `log_time` double DEFAULT NULL,
                              `prefix` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
                              `message` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
                              PRIMARY KEY (`id`),
                              KEY `idx_log_level` (`level`),
                              KEY `idx_log_category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- ----------------------------
-- Table structure for system_rbac_migration
-- ----------------------------
DROP TABLE IF EXISTS `system_rbac_migration`;
CREATE TABLE `system_rbac_migration` (
                                         `version` varchar(180) NOT NULL,
                                         `apply_time` int DEFAULT NULL,
                                         PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of system_rbac_migration
-- ----------------------------
BEGIN;
INSERT INTO `system_rbac_migration` (`version`, `apply_time`) VALUES ('m000000_000000_base', 1648506904);
INSERT INTO `system_rbac_migration` (`version`, `apply_time`) VALUES ('m150625_214101_roles', 1648506906);
INSERT INTO `system_rbac_migration` (`version`, `apply_time`) VALUES ('m150625_215624_init_permissions', 1648506906);
INSERT INTO `system_rbac_migration` (`version`, `apply_time`) VALUES ('m151223_074604_edit_own_model', 1648506906);
COMMIT;

-- ----------------------------
-- Table structure for timeline_event
-- ----------------------------
DROP TABLE IF EXISTS `timeline_event`;
CREATE TABLE `timeline_event` (
                                  `id` int NOT NULL AUTO_INCREMENT,
                                  `application` varchar(64) NOT NULL,
                                  `category` varchar(64) NOT NULL,
                                  `event` varchar(64) NOT NULL,
                                  `data` text,
                                  `created_at` int NOT NULL,
                                  `user_id` int DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


-- ----------------------------
-- Table structure for translations_with_string
-- ----------------------------
DROP TABLE IF EXISTS `translations_with_string`;
CREATE TABLE `translations_with_string` (
                                            `id` int NOT NULL AUTO_INCREMENT,
                                            `table_name` varchar(100) NOT NULL,
                                            `model_id` int NOT NULL,
                                            `attribute` varchar(100) NOT NULL,
                                            `lang` varchar(6) NOT NULL,
                                            `value` varchar(255) NOT NULL,
                                            PRIMARY KEY (`id`),
                                            KEY `attribute` (`attribute`),
                                            KEY `table_name` (`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



-- ----------------------------
-- Table structure for translations_with_text
-- ----------------------------
DROP TABLE IF EXISTS `translations_with_text`;
CREATE TABLE `translations_with_text` (
                                          `id` int NOT NULL AUTO_INCREMENT,
                                          `table_name` varchar(100) NOT NULL,
                                          `model_id` int NOT NULL,
                                          `attribute` varchar(100) NOT NULL,
                                          `lang` varchar(6) NOT NULL,
                                          `value` text NOT NULL,
                                          PRIMARY KEY (`id`),
                                          KEY `attribute` (`attribute`),
                                          KEY `table_name` (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of translations_with_text
-- ----------------------------
BEGIN;
INSERT INTO `translations_with_text` (`id`, `table_name`, `model_id`, `attribute`, `lang`, `value`) VALUES (1, 'language', 2, 'name', 'ar', 'الانجليزيه');
INSERT INTO `translations_with_text` (`id`, `table_name`, `model_id`, `attribute`, `lang`, `value`) VALUES (2, 'language', 1, 'name', 'ar', 'العربيه');
COMMIT;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
                        `id` int NOT NULL AUTO_INCREMENT,
                        `parent_id` int DEFAULT NULL,
                        `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
                        `auth_key` varchar(32) NOT NULL,
                        `access_token` varchar(40) NOT NULL,
                        `password_hash` varchar(255) NOT NULL,
                        `oauth_client` varchar(255) DEFAULT NULL,
                        `oauth_client_user_id` varchar(255) DEFAULT NULL,
                        `email` varchar(255) NOT NULL,
                        `mobile` varchar(255) DEFAULT NULL,
                        `user_type` tinyint DEFAULT '0' COMMENT '0 customer  ',
                        `status` smallint NOT NULL DEFAULT '2',
                        `approval` tinyint DEFAULT NULL COMMENT '1 not approved, 2 approved',
                        `password_reset_token` varchar(255) DEFAULT NULL,
                        `firebase_token` varchar(500) DEFAULT NULL,
                        `wallet` decimal(10,2) DEFAULT '0.00',
                        `wallet_last_update` int DEFAULT NULL,
                        `created_at` int DEFAULT NULL,
                        `updated_at` int DEFAULT NULL,
                        `logged_at` int DEFAULT NULL,
                        `available_for_booking` tinyint(1) DEFAULT '0',
                        `rate_average` decimal(10,2) NOT NULL DEFAULT '0.00',
                        `roles` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
                        `rejected_status` int DEFAULT '0',
                        PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` (`id`, `parent_id`, `username`, `auth_key`, `access_token`, `password_hash`, `oauth_client`, `oauth_client_user_id`, `email`, `mobile`, `user_type`, `status`, `approval`, `password_reset_token`, `firebase_token`, `wallet`, `wallet_last_update`, `created_at`, `updated_at`, `logged_at`, `available_for_booking`, `rate_average`, `roles`, `rejected_status`) VALUES (1, NULL, 'admin', 'wLVhzaGMSOSPwsEc7qWJOKYDj--NFs4Y', 'raDxdphhsEkSYb6lJszw6IVXrWb0l3Tg0Jp74sGt', '$2y$13$DJGhLrdOGoA20TKuepcISOKJ02RP6OAlLoj5rB9VxOSSOK9Lg7fn6', NULL, NULL, 'webmaster@example.com', NULL, 0, 2, 0, NULL, NULL, 0.00, 1730193900, 1648506903, 1730193952, 1730194849, 0, 0.00, NULL, 0);
INSERT INTO `user` (`id`, `parent_id`, `username`, `auth_key`, `access_token`, `password_hash`, `oauth_client`, `oauth_client_user_id`, `email`, `mobile`, `user_type`, `status`, `approval`, `password_reset_token`, `firebase_token`, `wallet`, `wallet_last_update`, `created_at`, `updated_at`, `logged_at`, `available_for_booking`, `rate_average`, `roles`, `rejected_status`) VALUES (2, NULL, 'manager', 'oRzGIau3Q0T46tjRQHc_7pw-tGtHeaAy', '2mDOsLgI-Ykxx90lgEWAj9S-gVQAlB_2BjtKZ9LP', '$2y$13$0d/GUu8q.G1LY38fN4qRZ..pKiK4msnjs.wMVadwWuCqKNJRqil9G', NULL, NULL, 'manager@example.com', NULL, 0, 2, NULL, NULL, NULL, 0.00, NULL, 1648506903, 1648997010, NULL, 0, 0.00, NULL, 0);
INSERT INTO `user` (`id`, `parent_id`, `username`, `auth_key`, `access_token`, `password_hash`, `oauth_client`, `oauth_client_user_id`, `email`, `mobile`, `user_type`, `status`, `approval`, `password_reset_token`, `firebase_token`, `wallet`, `wallet_last_update`, `created_at`, `updated_at`, `logged_at`, `available_for_booking`, `rate_average`, `roles`, `rejected_status`) VALUES (3, NULL, 'user@test.com', 'nh_fzVpiUz3wiBcHSQ7Tdejt8QiBgcHu', '8P6cIZS9hcfeoQNhI01ttjWNo6331TYm83BrvoDJ', '$2y$13$nzRJW3JswiGNAsBAEBN5JOqsx5fLOPca5ZnJb0onzwW0bAXaryJCW', NULL, NULL, 'user@test.com', NULL, 0, 2, NULL, NULL, NULL, 0.00, NULL, 1683568814, 1683569299, 1683569299, 0, 0.00, NULL, 0);
COMMIT;

-- ----------------------------
-- Table structure for user_languge
-- ----------------------------
DROP TABLE IF EXISTS `user_languge`;
CREATE TABLE `user_languge` (
                                `id` int unsigned NOT NULL AUTO_INCREMENT,
                                `user_id` int NOT NULL,
                                `lang_id` int unsigned NOT NULL,
                                PRIMARY KEY (`id`),
                                KEY `user_id` (`user_id`) USING BTREE,
                                KEY `user_languge_ibfk_2` (`lang_id`),
                                CONSTRAINT `user_languge_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                                CONSTRAINT `user_languge_ibfk_2` FOREIGN KEY (`lang_id`) REFERENCES `language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin ROW_FORMAT=DYNAMIC;



-- ----------------------------
-- Table structure for user_profile
-- ----------------------------
DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
                                `user_id` int NOT NULL AUTO_INCREMENT,
                                `identification_number` int DEFAULT NULL,
                                `firstname` varchar(255) DEFAULT NULL,
                                `middlename` varchar(255) DEFAULT NULL,
                                `lastname` varchar(255) DEFAULT NULL,
                                `avatar_path` varchar(255) DEFAULT NULL,
                                `avatar_base_url` varchar(255) DEFAULT NULL,
                                `locale` varchar(32) NOT NULL,
                                `gender` smallint DEFAULT NULL,
                                `mobile` varchar(255) DEFAULT NULL,
                                `new_phone` varchar(255) DEFAULT NULL,
                                `new_phone_verified` tinyint(1) DEFAULT NULL,
                                `age` float DEFAULT NULL,
                                `education_level` int DEFAULT NULL,
                                `hour_rate` float DEFAULT NULL,
                                `preferred_age_from` int DEFAULT NULL,
                                `preferred_age_from_unit` int DEFAULT NULL,
                                `preferred_age_to` int DEFAULT NULL,
                                `preferred_age_to_unit` int DEFAULT NULL,
                                `from_days` int DEFAULT NULL,
                                `national_id_path` varchar(255) DEFAULT NULL,
                                `national_id_base_url` varchar(255) DEFAULT NULL,
                                `permit_path` varchar(255) DEFAULT NULL,
                                `permit_base_url` varchar(255) DEFAULT NULL,
                                `location_id` int DEFAULT NULL,
                                `address` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
                                `lat` varchar(255) DEFAULT NULL,
                                `lng` varchar(255) DEFAULT NULL,
                                `to_days` int DEFAULT NULL,
                                `available_for_booking` tinyint DEFAULT '1' COMMENT '0 no 1 yes',
                                `reward` int DEFAULT NULL,
                                `sport_id` int DEFAULT NULL,
                                `days` varchar(255) DEFAULT NULL,
                                `nationality` varchar(255) DEFAULT NULL,
                                `dob` date DEFAULT NULL,
                                `subscription_id` int DEFAULT NULL,
                                PRIMARY KEY (`user_id`),
                                UNIQUE KEY `identification_number` (`identification_number`),

                                CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of user_profile
-- ----------------------------
BEGIN;
INSERT INTO `user_profile` (`user_id`, `identification_number`, `firstname`, `middlename`, `lastname`, `avatar_path`, `avatar_base_url`, `locale`, `gender`, `mobile`, `new_phone`, `new_phone_verified`, `age`, `education_level`, `hour_rate`, `preferred_age_from`, `preferred_age_from_unit`, `preferred_age_to`, `preferred_age_to_unit`, `from_days`, `national_id_path`, `national_id_base_url`, `permit_path`, `permit_base_url`, `location_id`, `address`, `lat`, `lng`, `to_days`, `available_for_booking`, `reward`, `sport_id`, `days`, `nationality`, `dob`, `subscription_id`) VALUES (1, NULL, 'Admin', NULL, 'User', NULL, NULL, 'en-US', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'profile/IMG_1686498968.jpg', 'http://nanystorage.bytones.co/source/', 'profile/IMG_1686505763.jpg', 'http://nanystorage.bytones.co/source/', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user_profile` (`user_id`, `identification_number`, `firstname`, `middlename`, `lastname`, `avatar_path`, `avatar_base_url`, `locale`, `gender`, `mobile`, `new_phone`, `new_phone_verified`, `age`, `education_level`, `hour_rate`, `preferred_age_from`, `preferred_age_from_unit`, `preferred_age_to`, `preferred_age_to_unit`, `from_days`, `national_id_path`, `national_id_base_url`, `permit_path`, `permit_base_url`, `location_id`, `address`, `lat`, `lng`, `to_days`, `available_for_booking`, `reward`, `sport_id`, `days`, `nationality`, `dob`, `subscription_id`) VALUES (2, NULL, '', NULL, '', NULL, NULL, 'en-US', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'profile/IMG_1686498968.jpg', 'http://nanystorage.bytones.co/source/', 'profile/IMG_1686505763.jpg', 'http://nanystorage.bytones.co/source/', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `user_profile` (`user_id`, `identification_number`, `firstname`, `middlename`, `lastname`, `avatar_path`, `avatar_base_url`, `locale`, `gender`, `mobile`, `new_phone`, `new_phone_verified`, `age`, `education_level`, `hour_rate`, `preferred_age_from`, `preferred_age_from_unit`, `preferred_age_to`, `preferred_age_to_unit`, `from_days`, `national_id_path`, `national_id_base_url`, `permit_path`, `permit_base_url`, `location_id`, `address`, `lat`, `lng`, `to_days`, `available_for_booking`, `reward`, `sport_id`, `days`, `nationality`, `dob`, `subscription_id`) VALUES (3, NULL, 'Ahmed', NULL, 'GAd', NULL, NULL, 'en-US', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'profile/IMG_1686498968.jpg', 'http://nanystorage.bytones.co/source/', 'profile/IMG_1686505763.jpg', 'http://nanystorage.bytones.co/source/', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for user_skills
-- ----------------------------
DROP TABLE IF EXISTS `user_skills`;
CREATE TABLE `user_skills` (
                               `id` int unsigned NOT NULL AUTO_INCREMENT,
                               `user_id` int NOT NULL,
                               `skill_id` int unsigned NOT NULL,
                               PRIMARY KEY (`id`),
                               KEY `user_id` (`user_id`),
                               KEY `skill_id` (`skill_id`),
                               CONSTRAINT `user_skills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                               CONSTRAINT `user_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_bin;


-- ----------------------------
-- Table structure for user_token
-- ----------------------------
DROP TABLE IF EXISTS `user_token`;
CREATE TABLE `user_token` (
                              `id` int NOT NULL AUTO_INCREMENT,
                              `user_id` int NOT NULL,
                              `type` varchar(255) NOT NULL,
                              `token` varchar(40) NOT NULL,
                              `expire_at` int DEFAULT NULL,
                              `created_at` int DEFAULT NULL,
                              `updated_at` int DEFAULT NULL,
                              PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;



-- ----------------------------
-- Table structure for widget_carousel
-- ----------------------------
DROP TABLE IF EXISTS `widget_carousel`;
CREATE TABLE `widget_carousel` (
                                   `id` int NOT NULL AUTO_INCREMENT,
                                   `key` varchar(255) NOT NULL,
                                   `status` smallint DEFAULT '0',
                                   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- ----------------------------
-- Records of widget_carousel
-- ----------------------------
BEGIN;
INSERT INTO `widget_carousel` (`id`, `key`, `status`) VALUES (1, 'index', 1);
COMMIT;

-- ----------------------------
-- Table structure for widget_carousel_item
-- ----------------------------
DROP TABLE IF EXISTS `widget_carousel_item`;
CREATE TABLE `widget_carousel_item` (
                                        `id` int NOT NULL AUTO_INCREMENT,
                                        `carousel_id` int NOT NULL,
                                        `base_url` varchar(1024) DEFAULT NULL,
                                        `path` varchar(1024) DEFAULT NULL,
                                        `asset_url` varchar(1024) DEFAULT NULL,
                                        `type` varchar(255) DEFAULT NULL,
                                        `url` varchar(1024) DEFAULT NULL,
                                        `caption` varchar(1024) DEFAULT NULL,
                                        `status` smallint NOT NULL DEFAULT '0',
                                        `order` int DEFAULT '0',
                                        `created_at` int DEFAULT NULL,
                                        `updated_at` int DEFAULT NULL,
                                        PRIMARY KEY (`id`),
                                        KEY `fk_item_carousel` (`carousel_id`),
                                        CONSTRAINT `fk_item_carousel` FOREIGN KEY (`carousel_id`) REFERENCES `widget_carousel` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


-- ----------------------------
-- Table structure for widget_menu
-- ----------------------------
DROP TABLE IF EXISTS `widget_menu`;
CREATE TABLE `widget_menu` (
                               `id` int NOT NULL AUTO_INCREMENT,
                               `key` varchar(32) NOT NULL,
                               `title` varchar(255) NOT NULL,
                               `items` text NOT NULL,
                               `status` smallint NOT NULL DEFAULT '0',
                               PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


-- ----------------------------
-- Table structure for widget_text
-- ----------------------------
DROP TABLE IF EXISTS `widget_text`;
CREATE TABLE `widget_text` (
                               `id` int NOT NULL AUTO_INCREMENT,
                               `key` varchar(255) NOT NULL,
                               `title` varchar(255) NOT NULL,
                               `body` text NOT NULL,
                               `status` smallint DEFAULT NULL,
                               `created_at` int DEFAULT NULL,
                               `updated_at` int DEFAULT NULL,
                               PRIMARY KEY (`id`),
                               KEY `idx_widget_text_key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

