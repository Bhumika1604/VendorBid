-- =====================================================================
-- VendorBid – Contractor Bidding & Project Award Management Portal
-- Database Schema
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
-- (Schema prepared now; full CRUD module arrives in Part 2)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`          VARCHAR(200) NOT NULL,
    `description`    TEXT DEFAULT NULL,
    `category`       VARCHAR(100) DEFAULT NULL,
    `budget`         DECIMAL(15,2) DEFAULT NULL,
    `deadline`       DATE DEFAULT NULL,
    `status`         ENUM('open','awarded','closed') NOT NULL DEFAULT 'open',
    `created_by`     INT UNSIGNED NOT NULL,
    `created_at`     DATETIME DEFAULT NULL,
    `updated_at`     DATETIME DEFAULT NULL,
    `deleted_at`     DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `projects_created_by_foreign` (`created_by`),
    CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Table: bids
-- (Schema prepared now; full Bid module arrives in Part 3)
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `bids`;
CREATE TABLE `bids` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `project_id`     INT UNSIGNED NOT NULL,
    `contractor_id`  INT UNSIGNED NOT NULL,
    `bid_amount`     DECIMAL(15,2) NOT NULL,
    `remarks`        TEXT DEFAULT NULL,
    `status`         ENUM('pending','shortlisted','awarded','rejected') NOT NULL DEFAULT 'pending',
    `created_at`     DATETIME DEFAULT NULL,
    `updated_at`     DATETIME DEFAULT NULL,
    `deleted_at`     DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `bids_project_id_foreign` (`project_id`),
    KEY `bids_contractor_id_foreign` (`contractor_id`),
    CONSTRAINT `bids_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
    CONSTRAINT `bids_contractor_id_foreign` FOREIGN KEY (`contractor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------
-- Seed data: default Admin account
-- Email:    admin@vendorbid.com
-- Password: Admin@123
-- ---------------------------------------------------------------------
INSERT INTO `users` (`role`, `name`, `email`, `password`, `company_name`, `phone`, `address`, `status`, `created_at`, `updated_at`)
VALUES (
    'admin',
    'System Administrator',
    'admin@vendorbid.com',
    '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa',
    NULL,
    '9999999999',
    'VendorBid Head Office',
    'active',
    NOW(),
    NOW()
);

-- ---------------------------------------------------------------------
-- Seed data: sample contractor account (for testing login)
-- Email:    contractor@vendorbid.com
-- Password: Admin@123
-- ---------------------------------------------------------------------
INSERT INTO `users` (`role`, `name`, `email`, `password`, `company_name`, `phone`, `address`, `status`, `created_at`, `updated_at`)
VALUES (
    'contractor',
    'John Contractor',
    'contractor@vendorbid.com',
    '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa',
    'BuildRight Constructions',
    '9876543210',
    '221B Baker Street, Nashik',
    'active',
    NOW(),
    NOW()
);

SET FOREIGN_KEY_CHECKS = 1;
