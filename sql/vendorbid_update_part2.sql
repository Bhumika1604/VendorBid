-- =====================================================================
-- VendorBid – Part 2 Database Update
-- Adds fields required by the Project Management Module:
--   - required_skills
--   - location
-- Run this AFTER sql/vendorbid.sql (Part 1) has already been imported.
-- =====================================================================

USE `vendorbid`;

ALTER TABLE `projects`
    ADD COLUMN `required_skills` TEXT NULL AFTER `category`,
    ADD COLUMN `location` VARCHAR(150) NULL AFTER `required_skills`;
