-- =====================================================================
-- VendorBid – Contractor Bidding & Project Award Management Portal
-- COMPLETE CONSOLIDATED DATABASE SCHEMA (Parts 1 + 2 + 3 + 4 merged)
-- =====================================================================
-- This single file creates the entire database from scratch, exactly
-- equivalent to running, in order:
--   sql/vendorbid.sql
--   sql/vendorbid_update_part2.sql
--   sql/vendorbid_update_part3.sql
--   sql/vendorbid_update_part4.sql
--
-- Use this file for a fresh installation. The four incremental files
-- are kept in this folder too, for reference and for anyone upgrading
-- an existing Part 1/2/3 database step by step.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `vendorbid` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `vendorbid`;

-- ---------------------------------------------------------------------
-- Table: users
-- Holds both Admin and Contractor accounts (role based access)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role`           ENUM('admin','contractor') NOT NULL DEFAULT 'contractor',
    `name`           VARCHAR(150) NOT NULL,
    `email`          VARCHAR(150) NOT NULL,
    `password`       VARCHAR(255) NOT NULL,
    `company_name`   VARCHAR(150) DEFAULT NULL,
    `phone`          VARCHAR(20)  DEFAULT NULL,
    `address`        VARCHAR(255) DEFAULT NULL,
    `status`         ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `created_at`     DATETIME DEFAULT NULL,
    `updated_at`     DATETIME DEFAULT NULL,
    `deleted_at`     DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: projects
-- Includes Part 2 fields (required_skills, location) already merged in.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`            VARCHAR(200) NOT NULL,
    `description`      TEXT DEFAULT NULL,
    `category`         VARCHAR(100) DEFAULT NULL,
    `required_skills`  TEXT NULL,
    `location`         VARCHAR(150) NULL,
    `budget`           DECIMAL(15,2) DEFAULT NULL,
    `deadline`         DATE DEFAULT NULL,
    `status`           ENUM('open','awarded','closed') NOT NULL DEFAULT 'open',
    `created_by`       INT UNSIGNED NOT NULL,
    `created_at`       DATETIME DEFAULT NULL,
    `updated_at`       DATETIME DEFAULT NULL,
    `deleted_at`       DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `projects_created_by_foreign` (`created_by`),
    CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: bids
-- Includes Part 3 fields (estimated_days, proposal_description,
-- previous_experience, document_path) and the one-bid-per-contractor-
-- per-project unique constraint, already merged in.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `bids`;
CREATE TABLE `bids` (
    `id`                     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `project_id`             INT UNSIGNED NOT NULL,
    `contractor_id`          INT UNSIGNED NOT NULL,
    `bid_amount`             DECIMAL(15,2) NOT NULL,
    `estimated_days`         INT UNSIGNED NULL,
    `proposal_description`   TEXT NULL,
    `previous_experience`    TEXT NULL,
    `document_path`          VARCHAR(255) NULL,
    `status`                 ENUM('pending','shortlisted','awarded','rejected') NOT NULL DEFAULT 'pending',
    `created_at`             DATETIME DEFAULT NULL,
    `updated_at`             DATETIME DEFAULT NULL,
    `deleted_at`             DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `bids_unique_contractor_project` (`project_id`, `contractor_id`),
    KEY `bids_project_id_foreign` (`project_id`),
    KEY `bids_contractor_id_foreign` (`contractor_id`),
    CONSTRAINT `bids_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bids_contractor_id_foreign` FOREIGN KEY (`contractor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: awards
-- One row per awarded project, recording which bid/contractor won.
-- (Part 4)
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
-- In-app notification feed for Admin and Contractor users. (Part 4)
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

-- =====================================================================
-- SEED DATA — required accounts (Admin + one sample Contractor)
-- Passwords are bcrypt-hashed for: Admin@123
-- =====================================================================

INSERT INTO `users` (`role`, `name`, `email`, `password`, `company_name`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
('admin', 'System Administrator', 'admin@vendorbid.com', '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa', NULL, '9999999999', 'VendorBid Head Office', 'active', NOW(), NOW()),
('contractor', 'John Contractor', 'contractor@vendorbid.com', '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa', 'BuildRight Constructions', '9876543210', '221B Baker Street, Nashik', 'active', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
