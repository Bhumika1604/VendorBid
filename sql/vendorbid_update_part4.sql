-- =====================================================================
-- VendorBid – Part 4 Database Update
-- Adds tables required by the Award Management Module and the
-- in-app Notification system.
-- Run this AFTER sql/vendorbid.sql, vendorbid_update_part2.sql and
-- vendorbid_update_part3.sql have already been imported.
-- =====================================================================

USE `vendorbid`;

-- ---------------------------------------------------------------------
-- Table: awards
-- One row per awarded project, recording which bid/contractor won.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `awards`;
CREATE TABLE `awards` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `project_id`      INT UNSIGNED NOT NULL,
    `bid_id`          INT UNSIGNED NOT NULL,
    `contractor_id`   INT UNSIGNED NOT NULL,
    `awarded_by`      INT UNSIGNED NOT NULL,
    `awarded_amount`  DECIMAL(15,2) NOT NULL,
    `remarks`         TEXT DEFAULT NULL,
    `created_at`      DATETIME DEFAULT NULL,
    `updated_at`      DATETIME DEFAULT NULL,
    `deleted_at`      DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `awards_project_unique` (`project_id`),
    KEY `awards_bid_id_foreign` (`bid_id`),
    KEY `awards_contractor_id_foreign` (`contractor_id`),
    KEY `awards_awarded_by_foreign` (`awarded_by`),
    CONSTRAINT `awards_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    CONSTRAINT `awards_bid_id_foreign` FOREIGN KEY (`bid_id`) REFERENCES `bids` (`id`) ON DELETE CASCADE,
    CONSTRAINT `awards_contractor_id_foreign` FOREIGN KEY (`contractor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `awards_awarded_by_foreign` FOREIGN KEY (`awarded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: notifications
-- Lightweight in-app notification feed for both Admin and Contractor
-- users (Bid Submitted, Project Awarded, Bid Rejected, etc.).
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`     INT UNSIGNED NOT NULL,
    `type`        VARCHAR(50) NOT NULL,
    `title`       VARCHAR(200) NOT NULL,
    `message`     VARCHAR(500) NOT NULL,
    `link`        VARCHAR(255) DEFAULT NULL,
    `is_read`     TINYINT(1) NOT NULL DEFAULT 0,
    `created_at`  DATETIME DEFAULT NULL,
    `updated_at`  DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `notifications_user_id_foreign` (`user_id`),
    CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
