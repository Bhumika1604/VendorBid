-- =====================================================================
-- VendorBid – Part 3 Database Update
-- Adds fields required by the Bid Management Module:
--   - estimated_days
--   - proposal_description
--   - previous_experience
--   - document_path
-- Also adds a unique constraint so a contractor can never place more
-- than one bid on the same project (defense in depth alongside the
-- application-level check in BidController).
-- Run this AFTER sql/vendorbid.sql (Part 1) and
-- sql/vendorbid_update_part2.sql (Part 2) have already been imported.
-- =====================================================================

USE `vendorbid`;

ALTER TABLE `bids`
    ADD COLUMN `estimated_days` INT UNSIGNED NULL AFTER `bid_amount`,
    ADD COLUMN `proposal_description` TEXT NULL AFTER `estimated_days`,
    ADD COLUMN `previous_experience` TEXT NULL AFTER `proposal_description`,
    ADD COLUMN `document_path` VARCHAR(255) NULL AFTER `previous_experience`;

ALTER TABLE `bids`
    ADD UNIQUE KEY `bids_unique_contractor_project` (`project_id`, `contractor_id`);
