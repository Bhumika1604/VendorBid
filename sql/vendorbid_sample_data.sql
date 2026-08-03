-- =====================================================================
-- VendorBid – Sample Test Data
-- =====================================================================
-- OPTIONAL. Import this AFTER sql/vendorbid_full.sql on a FRESH
-- database to populate the app with realistic demo content: extra
-- contractors, a spread of projects in every status, competing bids,
-- two completed awards, and a few notifications.
--
-- Because this relies on predictable AUTO_INCREMENT ids (continuing
-- on from the 2 seed users already created by vendorbid_full.sql), it
-- must be run on a database that was just freshly created — do not
-- run this against a database you've already been using/testing in,
-- or the ids below will not line up with what actually gets inserted.
--
-- All sample contractor accounts use the password: Admin@123
-- =====================================================================

USE `vendorbid`;

SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- Additional contractors (ids 3–6, continuing after the seed contractor
-- with id 2 from vendorbid_full.sql)
-- ---------------------------------------------------------------------
INSERT INTO `users` (`id`, `role`, `name`, `email`, `password`, `company_name`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
(3, 'contractor', 'Priya Sharma', 'priya@sharmaconstructions.com', '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa', 'Sharma Constructions', '9812345601', 'Plot 14, MIDC, Pune', 'active', NOW(), NOW()),
(4, 'contractor', 'Arjun Mehta', 'arjun@mehtabuilders.com', '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa', 'Mehta Builders', '9812345602', '45 Ring Road, Nagpur', 'active', NOW(), NOW()),
(5, 'contractor', 'Kavita Rao', 'kavita@raointeriors.com', '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa', 'Rao Interiors', '9812345603', '7 Lake View, Nashik', 'active', NOW(), NOW()),
(6, 'contractor', 'Deepak Nair', 'deepak@nairelectricals.com', '$2y$12$7x74BkVbD0AJJnSZShR08Oc9/hWZPn7lT.pcbtfqHyAazT9F0djZa', 'Nair Electricals', '9812345604', '22 Industrial Area, Aurangabad', 'inactive', NOW(), NOW());

-- ---------------------------------------------------------------------
-- Projects (ids 1–6), created by the admin (id 1)
-- ---------------------------------------------------------------------
INSERT INTO `projects` (`id`, `title`, `description`, `category`, `required_skills`, `location`, `budget`, `deadline`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Community Health Center Construction', 'Construction of a new 8,000 sqft community health center including outpatient wards, a pharmacy, and a diagnostics wing, built to state healthcare-facility standards.', 'Construction', 'Civil Engineering, Structural Design, Project Management', 'Nashik, Maharashtra', 9500000.00, '2027-02-28', 'open', 1, NOW(), NOW()),
(2, 'Government School Building Renovation', 'Full renovation of a 40-year-old government school building: new roofing, electrical rewiring, accessible ramps, and repainting across 12 classrooms.', 'Construction', 'Civil Engineering, Electrical Work, Renovation', 'Pune, Maharashtra', 3200000.00, '2026-10-15', 'awarded', 1, NOW(), NOW()),
(3, 'Municipal E-Commerce Portal Development', 'Design and development of a municipal e-commerce portal for local artisans to sell handicrafts online, including payment gateway integration and an admin dashboard.', 'IT & Software', 'Web Development, PHP, Payment Gateway Integration, UI/UX Design', 'Mumbai, Maharashtra', 650000.00, '2026-12-20', 'open', 1, NOW(), NOW()),
(4, 'Corporate Office Interior Design', 'Complete interior design and fit-out for a 3-floor corporate headquarters, including workstations, meeting rooms, a cafeteria, and a reception lobby.', 'Interior Design', 'Interior Design, Space Planning, Furniture Procurement', 'Pune, Maharashtra', 4100000.00, '2026-09-30', 'closed', 1, NOW(), NOW()),
(5, 'Solar Panel Installation — Municipal Buildings', 'Design and installation of rooftop solar panel systems across 5 municipal buildings, targeting a combined 250kW capacity with grid-tie inverters.', 'Electrical', 'Solar Installation, Electrical Engineering, Grid Integration', 'Nagpur, Maharashtra', 5800000.00, '2027-01-31', 'open', 1, NOW(), NOW()),
(6, 'Public Park Landscaping & Renovation', 'Landscaping renovation of a 2-acre public park including new walking paths, a children\'s play area, native plantation, and irrigation system upgrades.', 'Landscaping', 'Landscaping, Irrigation Systems, Horticulture', 'Nashik, Maharashtra', 1800000.00, '2026-11-30', 'awarded', 1, NOW(), NOW());

-- ---------------------------------------------------------------------
-- Bids
-- Project 1 (open): 2 pending bids
-- Project 2 (awarded): 3 bids — 1 awarded, 2 rejected
-- Project 3 (open): 2 pending bids
-- Project 5 (open): 1 pending bid
-- Project 6 (awarded): 2 bids — 1 awarded, 1 rejected
-- ---------------------------------------------------------------------
INSERT INTO `bids` (`id`, `project_id`, `contractor_id`, `bid_amount`, `estimated_days`, `proposal_description`, `previous_experience`, `document_path`, `status`, `created_at`, `updated_at`) VALUES
-- Project 1 — open
(1, 1, 2, 9200000.00, 150, 'We propose a phased construction approach starting with the foundation and structural work, followed by MEP installation and interior finishing, ensuring minimal disruption to the surrounding area.', 'Completed 6 healthcare facility projects across Maharashtra in the last 10 years, including 2 district hospitals.', 'sample_placeholder.pdf', 'pending', NOW(), NOW()),
(2, 1, 3, 9350000.00, 140, 'Our proposal focuses on accelerated delivery using prefabricated structural components without compromising on healthcare-grade finishing standards.', 'Sharma Constructions has delivered 15+ public infrastructure projects, including 3 primary health centers.', 'sample_placeholder.pdf', 'pending', NOW(), NOW()),

-- Project 2 — awarded (bid id 4 wins)
(3, 2, 2, 3150000.00, 75, 'Renovation plan covering roofing replacement, electrical rewiring to current safety code, and accessibility ramps completed in a single monsoon-safe phase.', 'Renovated 9 government school buildings across Maharashtra since 2016.', 'sample_placeholder.pdf', 'rejected', NOW(), NOW()),
(4, 2, 4, 3050000.00, 70, 'Cost-efficient renovation plan using durable, low-maintenance materials, with weekend-only work scheduling to avoid disrupting the school calendar.', 'Mehta Builders specializes in public school renovations, having completed 12 such projects in Nagpur and Pune divisions.', 'sample_placeholder.pdf', 'awarded', NOW(), NOW()),
(5, 2, 5, 3190000.00, 80, 'Comprehensive renovation including modernized classroom lighting and improved ventilation, alongside the requested structural repairs.', 'Rao Interiors has 8 years of experience in institutional renovation projects across Maharashtra.', 'sample_placeholder.pdf', 'rejected', NOW(), NOW()),

-- Project 3 — open
(6, 3, 5, 610000.00, 60, 'Full-stack development proposal using a modern PHP framework with a responsive design system, integrated payment gateway, and an intuitive admin dashboard for municipal staff.', 'Delivered 4 e-commerce platforms for local government and NGO clients over the past 5 years.', 'sample_placeholder.pdf', 'pending', NOW(), NOW()),
(7, 3, 3, 640000.00, 65, 'Proposal includes a mobile-responsive storefront, secure payment integration, and an artisan onboarding workflow with photo/video product uploads.', 'Sharma Constructions recently expanded into digital services, delivering 2 municipal web portals in the past 2 years.', 'sample_placeholder.pdf', 'pending', NOW(), NOW()),

-- Project 5 — open
(8, 5, 6, 5650000.00, 100, 'Turnkey solar installation proposal covering site assessment, panel procurement, grid-tie inverter setup, and a 5-year maintenance package.', 'Nair Electricals has installed over 1.2MW of solar capacity across commercial and municipal sites in Maharashtra.', 'sample_placeholder.pdf', 'pending', NOW(), NOW()),

-- Project 6 — awarded (bid id 10 wins)
(9, 6, 3, 1750000.00, 55, 'Landscaping renovation plan featuring native, drought-resistant plantation, a modern children\'s play area, and an automated drip irrigation system.', 'Sharma Constructions has completed 5 public park renovation projects across Nashik district.', 'sample_placeholder.pdf', 'rejected', NOW(), NOW()),
(10, 6, 5, 1690000.00, 50, 'Comprehensive park renovation with accessible walking paths, native landscaping, and a low-maintenance irrigation system designed for long-term cost savings.', 'Rao Interiors has delivered 3 public landscaping projects in Nashik and Pune over the past 4 years.', 'sample_placeholder.pdf', 'awarded', NOW(), NOW());

-- ---------------------------------------------------------------------
-- Awards (matching the 'awarded' bids/projects above)
-- ---------------------------------------------------------------------
INSERT INTO `awards` (`id`, `project_id`, `bid_id`, `contractor_id`, `awarded_by`, `awarded_amount`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 4, 1, 3050000.00, 'Lowest bid with strong track record in school renovation projects and a construction schedule that avoids disrupting the academic calendar.', NOW(), NOW()),
(2, 6, 10, 5, 1, 1690000.00, 'Best balance of cost and long-term maintenance value; strong regional landscaping experience.', NOW(), NOW());

-- ---------------------------------------------------------------------
-- Notifications (a representative sample — the app also generates
-- these automatically going forward as bids/awards happen)
-- ---------------------------------------------------------------------
INSERT INTO `notifications` (`user_id`, `type`, `title`, `message`, `link`, `is_read`, `created_at`, `updated_at`) VALUES
(4, 'project_awarded', 'Project Awarded to You! 🏆', 'Congratulations! You have been awarded "Government School Building Renovation" for ₹30,50,000.', '/contractor/bids/view/4', 0, NOW(), NOW()),
(2, 'bid_rejected', 'Bid Not Selected', 'Your bid for "Government School Building Renovation" was not selected this time.', '/contractor/bids/view/3', 0, NOW(), NOW()),
(5, 'bid_rejected', 'Bid Not Selected', 'Your bid for "Government School Building Renovation" was not selected this time.', '/contractor/bids/view/5', 0, NOW(), NOW()),
(5, 'project_awarded', 'Project Awarded to You! 🏆', 'Congratulations! You have been awarded "Public Park Landscaping & Renovation" for ₹16,90,000.', '/contractor/bids/view/10', 0, NOW(), NOW()),
(3, 'bid_rejected', 'Bid Not Selected', 'Your bid for "Public Park Landscaping & Renovation" was not selected this time.', '/contractor/bids/view/9', 0, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
