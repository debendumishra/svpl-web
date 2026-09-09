-- ==========================================================
-- Surya Vistaara Pvt. Ltd. (SVPL)
-- PM Surya Ghar Network & Lead Management Platform
-- Master MySQL 8.x Database Schema (XAMPP & Production)
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. USERS
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `role` VARCHAR(30) NOT NULL DEFAULT 'CUSTOMER',
  `email` VARCHAR(150) NULL UNIQUE,
  `mobile` VARCHAR(20) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(150) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `last_login` DATETIME NULL,
  `remember_token` VARCHAR(100) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_user_role` (`role`),
  INDEX `idx_user_mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ROLES & PERMISSIONS
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `slug` VARCHAR(50) NOT NULL UNIQUE,
  `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `module` VARCHAR(50) NOT NULL,
  `action` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NULL,
  UNIQUE KEY `uk_module_action` (`module`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. LOCATIONS (ODISHA ADMINISTRATIVE STRUCTURE)
DROP TABLE IF EXISTS `locations`;
CREATE TABLE `locations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `state` VARCHAR(50) NOT NULL DEFAULT 'Odisha',
  `district` VARCHAR(80) NOT NULL,
  `subdivision` VARCHAR(80) NULL,
  `block` VARCHAR(80) NOT NULL,
  `gram_panchayat` VARCHAR(80) NOT NULL,
  `village` VARCHAR(80) NULL,
  `pincode` VARCHAR(10) NULL,
  INDEX `idx_loc_district` (`district`),
  INDEX `idx_loc_block` (`block`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. ADVISORS
DROP TABLE IF EXISTS `advisors`;
CREATE TABLE `advisors` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL UNIQUE,
  `advisor_code` VARCHAR(30) NOT NULL UNIQUE,
  `referral_code` VARCHAR(30) NOT NULL UNIQUE,
  `sponsor_id` INT UNSIGNED NULL,
  `first_name` VARCHAR(80) NOT NULL,
  `last_name` VARCHAR(80) NOT NULL,
  `father_spouse_name` VARCHAR(150) NULL,
  `dob` DATE NULL,
  `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
  `mobile` VARCHAR(20) NOT NULL,
  `alt_mobile` VARCHAR(20) NULL,
  `email` VARCHAR(150) NULL,
  `photo_url` VARCHAR(255) NULL,
  `blood_group` VARCHAR(10) NULL,
  `state` VARCHAR(50) NOT NULL DEFAULT 'Odisha',
  `district` VARCHAR(80) NOT NULL,
  `subdivision` VARCHAR(80) NULL,
  `block` VARCHAR(80) NOT NULL,
  `gram_panchayat` VARCHAR(80) NOT NULL,
  `village` VARCHAR(80) NULL,
  `pincode` VARCHAR(10) NOT NULL,
  `address_line` TEXT NULL,
  `aadhaar_number` VARCHAR(30) NULL,
  `pan_number` VARCHAR(20) NULL,
  `bank_name` VARCHAR(100) NULL,
  `bank_branch` VARCHAR(100) NULL,
  `account_holder` VARCHAR(150) NULL,
  `account_number` VARCHAR(50) NULL,
  `ifsc_code` VARCHAR(20) NULL,
  `passbook_doc_id` INT UNSIGNED NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'ACTIVE',
  `qualification_status` ENUM('NEW', 'ACTIVE', 'QUALIFIED', 'PROMOTED', 'INACTIVE', 'SUSPENDED') NOT NULL DEFAULT 'NEW',
  `current_level` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `customer_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `direct_customer_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `direct_advisor_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `total_team_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `joining_fee` DECIMAL(10,2) NOT NULL DEFAULT 1500.00,
  `joining_fee_paid` TINYINT(1) NOT NULL DEFAULT 0,
  `joining_date` DATE NULL,
  `qualified_at` DATETIME NULL,
  `id_card_number` VARCHAR(50) NULL UNIQUE,
  `appointment_letter_no` VARCHAR(50) NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`sponsor_id`) REFERENCES `advisors` (`id`) ON DELETE SET NULL,
  INDEX `idx_adv_code` (`advisor_code`),
  INDEX `idx_adv_referral` (`referral_code`),
  INDEX `idx_adv_status` (`status`),
  INDEX `idx_adv_qual_status` (`qualification_status`),
  INDEX `idx_adv_district` (`district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ADVISOR GENEALOGY (CLOSURE TABLE FOR 9-LEVEL HIERARCHY)
DROP TABLE IF EXISTS `advisor_genealogy`;
CREATE TABLE `advisor_genealogy` (
  `ancestor_id` INT UNSIGNED NOT NULL,
  `descendant_id` INT UNSIGNED NOT NULL,
  `depth` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`ancestor_id`, `descendant_id`),
  FOREIGN KEY (`ancestor_id`) REFERENCES `advisors` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`descendant_id`) REFERENCES `advisors` (`id`) ON DELETE CASCADE,
  INDEX `idx_gen_descendant_depth` (`descendant_id`, `depth`),
  INDEX `idx_gen_ancestor_depth` (`ancestor_id`, `depth`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. CUSTOMERS
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL UNIQUE,
  `customer_code` VARCHAR(30) NOT NULL UNIQUE,
  `advisor_id` INT UNSIGNED NULL,
  `first_name` VARCHAR(100) NULL,
  `last_name` VARCHAR(100) NULL,
  `full_name` VARCHAR(150) NULL,
  `father_husband_name` VARCHAR(150) NULL,
  `dob` DATE NULL,
  `gender` ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
  `mobile` VARCHAR(20) NOT NULL,
  `alt_mobile` VARCHAR(20) NULL,
  `email` VARCHAR(150) NULL,
  `photo_url` VARCHAR(255) NULL,
  `state` VARCHAR(50) NOT NULL DEFAULT 'Odisha',
  `district` VARCHAR(80) NOT NULL,
  `block` VARCHAR(80) NOT NULL,
  `gram_panchayat` VARCHAR(80) NOT NULL,
  `village` VARCHAR(80) NULL,
  `house_address` TEXT NULL,
  `address_line` TEXT NULL,
  `pincode` VARCHAR(10) NOT NULL,
  `electricity_consumer_name` VARCHAR(150) NULL,
  `electricity_consumer_no` VARCHAR(50) NULL,
  `discom` VARCHAR(50) NULL,
  `discom_name` VARCHAR(50) NULL,
  `consumer_number` VARCHAR(50) NULL,
  `monthly_bill` DECIMAL(10,2) NULL,
  `monthly_avg_bill` DECIMAL(10,2) NULL,
  `sanctioned_load_kw` DECIMAL(5,2) NULL,
  `proposed_solar_kw` DECIMAL(5,2) NULL,
  `rooftop_type` VARCHAR(50) NULL,
  `roof_type` VARCHAR(50) NULL,
  `roof_ownership` ENUM('Owned', 'Rented', 'Shared') DEFAULT 'Owned',
  `approx_roof_area_sqft` DECIMAL(10,2) NULL,
  `roof_area_sqft` DECIMAL(10,2) NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'New',
  `bank_name` VARCHAR(100) NULL,
  `bank_branch` VARCHAR(100) NULL,
  `account_holder` VARCHAR(150) NULL,
  `account_number` VARCHAR(50) NULL,
  `ifsc_code` VARCHAR(20) NULL,
  `joining_bonus_status` ENUM('PENDING', 'APPROVED', 'PAID') DEFAULT 'PENDING',
  `joining_bonus_amount` DECIMAL(10,2) DEFAULT 500.00,
  `converted_to_advisor` TINYINT(1) NOT NULL DEFAULT 0,
  `converted_advisor_id` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`) ON DELETE SET NULL,
  INDEX `idx_cus_code` (`customer_code`),
  INDEX `idx_cus_advisor` (`advisor_id`),
  INDEX `idx_cus_mobile` (`mobile`),
  INDEX `idx_cus_district` (`district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. LEADS (10-STAGE SOLAR LIFECYCLE PIPELINE)
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_code` VARCHAR(30) NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NULL,
  `advisor_id` INT UNSIGNED NULL,
  `package_id` INT UNSIGNED NULL,
  `lead_source` VARCHAR(100) DEFAULT 'Advisor Referral',
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `email` VARCHAR(150) NULL,
  `state` VARCHAR(100) DEFAULT 'Odisha',
  `district` VARCHAR(100) NULL,
  `block` VARCHAR(100) NULL,
  `gram_panchayat` VARCHAR(100) NULL,
  `village` VARCHAR(100) NULL,
  `pincode` VARCHAR(10) NULL,
  `discom_name` VARCHAR(50) DEFAULT 'TPCODL',
  `consumer_number` VARCHAR(50) NULL,
  `proposed_capacity_kw` DECIMAL(5,2) NOT NULL DEFAULT 3.00,
  `stage` VARCHAR(50) NOT NULL DEFAULT 'REGISTRATION',
  `status` VARCHAR(50) NOT NULL DEFAULT 'New',
  `estimated_project_cost` DECIMAL(12,2) NOT NULL DEFAULT 210000.00,
  `subsidy_amount` DECIMAL(10,2) NOT NULL DEFAULT 78000.00,
  `state_subsidy` DECIMAL(10,2) NOT NULL DEFAULT 60000.00,
  `customer_payable_amount` DECIMAL(12,2) NOT NULL DEFAULT 72000.00,
  `assigned_to_user_id` INT UNSIGNED NULL,
  `follow_up_date` DATE NULL,
  `next_action` VARCHAR(255) NULL,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  INDEX `idx_lead_stage` (`stage`),
  INDEX `idx_lead_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. LEAD STAGE HISTORY
DROP TABLE IF EXISTS `lead_stage_history`;
CREATE TABLE `lead_stage_history` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL,
  `stage` VARCHAR(50) NOT NULL,
  `from_stage` VARCHAR(50) NULL,
  `to_stage` VARCHAR(50) NULL,
  `status_notes` TEXT NULL,
  `remarks` TEXT NULL,
  `changed_by_user_id` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. DOCUMENTS
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `entity_type` VARCHAR(50) NOT NULL DEFAULT 'CUSTOMER',
  `entity_id` INT UNSIGNED NULL,
  `lead_id` INT UNSIGNED NULL,
  `document_type` VARCHAR(50) NOT NULL,
  `title` VARCHAR(150) NULL,
  `document_title` VARCHAR(150) NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_hash` VARCHAR(64) NULL,
  `mime_type` VARCHAR(100) NOT NULL DEFAULT 'application/pdf',
  `file_size` INT UNSIGNED NOT NULL DEFAULT 0,
  `masked_identifier` VARCHAR(50) NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Uploaded',
  `remarks` TEXT NULL,
  `verified_by_user_id` INT UNSIGNED NULL,
  `verified_at` DATETIME NULL,
  `rejection_remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_doc_entity` (`entity_type`, `entity_id`),
  INDEX `idx_doc_lead` (`lead_id`),
  INDEX `idx_doc_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. COMMISSION PLANS & COMMISSIONS
DROP TABLE IF EXISTS `commission_plans`;
CREATE TABLE `commission_plans` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `plan_name` VARCHAR(100) NOT NULL,
  `level` TINYINT UNSIGNED NOT NULL UNIQUE,
  `commission_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `bonus_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `description` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `commission_rules`;
CREATE TABLE `commission_rules` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `level` TINYINT UNSIGNED NOT NULL,
  `trigger_stage` VARCHAR(50) NOT NULL DEFAULT 'INSTALLATION_COMPLETED',
  `commission_type` ENUM('PERCENTAGE', 'FLAT') NOT NULL DEFAULT 'FLAT',
  `commission_value` DECIMAL(10,2) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `description` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `uk_level_stage` (`level`, `trigger_stage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `commissions`;
CREATE TABLE `commissions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `advisor_id` INT UNSIGNED NOT NULL,
  `customer_id` INT UNSIGNED NULL,
  `lead_id` INT UNSIGNED NULL,
  `source_advisor_id` INT UNSIGNED NULL,
  `level` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `commission_type` VARCHAR(50) NOT NULL DEFAULT 'FLAT',
  `base_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `rate` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `commission_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `bonus_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `tds_deducted` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` VARCHAR(50) NOT NULL DEFAULT 'APPROVED',
  `generated_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
  `approved_at` DATETIME NULL,
  `paid_at` DATETIME NULL,
  `transaction_id` VARCHAR(100) NULL,
  `calculation_notes` TEXT NULL,
  `notes` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  INDEX `idx_comm_advisor` (`advisor_id`),
  INDEX `idx_comm_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. WALLETS & WALLET TRANSACTIONS
DROP TABLE IF EXISTS `wallets`;
CREATE TABLE `wallets` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL UNIQUE,
  `advisor_id` INT UNSIGNED NULL,
  `balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `available_balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `pending_balance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `pending_clearance` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_earned` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_withdrawn` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `wallet_transactions`;
CREATE TABLE `wallet_transactions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `wallet_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `advisor_id` INT UNSIGNED NULL,
  `txn_type` VARCHAR(50) NOT NULL DEFAULT 'COMMISSION',
  `type` VARCHAR(50) NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `balance_after` DECIMAL(12,2) NOT NULL,
  `reference_type` VARCHAR(50) NULL,
  `reference_id` INT UNSIGNED NULL,
  `description` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. CUSTOMER BONUSES
DROP TABLE IF EXISTS `customer_bonuses`;
CREATE TABLE `customer_bonuses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT UNSIGNED NOT NULL,
  `bonus_amount` DECIMAL(10,2) NOT NULL DEFAULT 500.00,
  `status` ENUM('PENDING', 'APPROVED', 'PAYABLE', 'PAID') NOT NULL DEFAULT 'PENDING',
  `approved_at` DATETIME NULL,
  `paid_at` DATETIME NULL,
  `remarks` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. PAYMENTS & RECEIPTS
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `payment_code` VARCHAR(40) NOT NULL UNIQUE,
  `entity_type` ENUM('ADVISOR', 'CUSTOMER') NOT NULL,
  `entity_id` INT UNSIGNED NOT NULL,
  `purpose` ENUM('JOINING_FEE', 'BOOKING_AMOUNT', 'ADVANCE', 'BALANCE', 'OTHER') NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `payment_method` ENUM('UPI', 'CASH', 'BANK_TRANSFER', 'CARD', 'OTHER') NOT NULL DEFAULT 'UPI',
  `transaction_ref` VARCHAR(100) NULL,
  `status` ENUM('PENDING', 'SUCCESS', 'FAILED', 'REFUNDED') NOT NULL DEFAULT 'SUCCESS',
  `receipt_number` VARCHAR(50) NOT NULL UNIQUE,
  `payment_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_pay_entity` (`entity_type`, `entity_id`),
  INDEX `idx_pay_receipt` (`receipt_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. QUOTATIONS
DROP TABLE IF EXISTS `quotations`;
CREATE TABLE `quotations` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `quotation_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NOT NULL,
  `lead_id` INT UNSIGNED NOT NULL,
  `solar_capacity_kw` DECIMAL(5,2) NOT NULL,
  `panel_brand` VARCHAR(100) NOT NULL DEFAULT 'Dhwajja Mono PERC Tier 1',
  `panel_wattage` INT NOT NULL DEFAULT 550,
  `panel_quantity` INT NOT NULL DEFAULT 6,
  `inverter_brand` VARCHAR(100) NOT NULL DEFAULT 'Dhwajja On-Grid String Inverter',
  `inverter_capacity_kw` DECIMAL(5,2) NOT NULL DEFAULT 3.00,
  `structure_type` VARCHAR(100) NOT NULL DEFAULT 'Elevated GI Pre-Galvanized Structure',
  `total_cost` DECIMAL(12,2) NOT NULL,
  `central_subsidy` DECIMAL(10,2) NOT NULL DEFAULT 78000.00,
  `state_subsidy` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `customer_net_cost` DECIMAL(12,2) NOT NULL,
  `estimated_monthly_savings` DECIMAL(10,2) NOT NULL DEFAULT 2400.00,
  `loan_amount` DECIMAL(12,2) NULL,
  `emi_estimate` DECIMAL(10,2) NULL,
  `terms` TEXT NULL,
  `valid_until` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. BANK LOANS
DROP TABLE IF EXISTS `loans`;
CREATE TABLE `loans` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NOT NULL,
  `bank_name` VARCHAR(100) NOT NULL,
  `branch` VARCHAR(100) NULL,
  `application_number` VARCHAR(50) NULL,
  `application_date` DATE NULL,
  `requested_amount` DECIMAL(12,2) NOT NULL,
  `sanctioned_amount` DECIMAL(12,2) NULL,
  `interest_rate` DECIMAL(5,2) DEFAULT 7.00,
  `tenure_months` INT DEFAULT 60,
  `emi_amount` DECIMAL(10,2) NULL,
  `status` ENUM('Not Applied', 'Documents Pending', 'Applied', 'Under Processing', 'Sanctioned', 'Rejected', 'Disbursed') DEFAULT 'Not Applied',
  `sanction_date` DATE NULL,
  `disbursement_date` DATE NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. JE (JUNIOR ENGINEER) REPORTS
DROP TABLE IF EXISTS `je_reports`;
CREATE TABLE `je_reports` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `report_number` VARCHAR(50) NOT NULL UNIQUE,
  `lead_id` INT UNSIGNED NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NOT NULL,
  `consumer_number` VARCHAR(50) NOT NULL,
  `installed_capacity_kw` DECIMAL(5,2) NOT NULL,
  `panel_make` VARCHAR(100) NOT NULL,
  `panel_serial_numbers` TEXT NULL,
  `inverter_make` VARCHAR(100) NOT NULL,
  `inverter_serial_number` VARCHAR(100) NULL,
  `net_meter_number` VARCHAR(100) NULL,
  `installation_date` DATE NOT NULL,
  `inspection_date` DATE NOT NULL,
  `je_name` VARCHAR(100) NOT NULL,
  `je_designation` VARCHAR(100) NOT NULL DEFAULT 'Junior Engineer (Electrical)',
  `discom_division` VARCHAR(100) NOT NULL,
  `inspection_status` ENUM('PASSED', 'DEFECT_FOUND', 'PENDING') NOT NULL DEFAULT 'PASSED',
  `remarks` TEXT NULL,
  `report_file_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. SUBSIDY TRACKING
DROP TABLE IF EXISTS `subsidies`;
CREATE TABLE `subsidies` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NOT NULL UNIQUE,
  `customer_id` INT UNSIGNED NOT NULL,
  `national_portal_app_no` VARCHAR(50) NULL,
  `subsidy_slab_kw` DECIMAL(5,2) NOT NULL,
  `eligible_subsidy_amount` DECIMAL(10,2) NOT NULL DEFAULT 78000.00,
  `application_date` DATE NULL,
  `status` ENUM('Not Applied', 'Application Prepared', 'Applied', 'Under Processing', 'Approved', 'Rejected', 'Subsidy Received') DEFAULT 'Not Applied',
  `sanctioned_date` DATE NULL,
  `disbursed_date` DATE NULL,
  `bank_utr_no` VARCHAR(100) NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. SOLAR PACKAGES (PM SURYA GHAR PRODUCTS)
DROP TABLE IF EXISTS `packages`;
CREATE TABLE `packages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `package_code` VARCHAR(50) NOT NULL UNIQUE,
  `title` VARCHAR(150) NOT NULL,
  `capacity_kw` DECIMAL(5,2) NOT NULL,
  `panel_type` VARCHAR(150) NOT NULL,
  `inverter_type` VARCHAR(150) NOT NULL,
  `battery_included` TINYINT(1) NOT NULL DEFAULT 0,
  `total_price` DECIMAL(12,2) NOT NULL,
  `estimated_subsidy` DECIMAL(12,2) NOT NULL,
  `net_customer_cost` DECIMAL(12,2) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18B. PACKAGE DISPATCHES (EQUIPMENT & INDUCTION KITS)
DROP TABLE IF EXISTS `package_dispatches`;
CREATE TABLE `package_dispatches` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NULL,
  `advisor_id` INT UNSIGNED NULL,
  `dispatch_type` VARCHAR(50) NOT NULL DEFAULT 'SOLAR_EQUIPMENT',
  `tracking_number` VARCHAR(100) NULL,
  `courier_partner` VARCHAR(100) NULL DEFAULT 'SVPL Logistics Odisha',
  `dispatch_date` DATE NULL,
  `delivery_date` DATE NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Dispatched',
  `items_included` TEXT NULL,
  `delivery_address` TEXT NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. NOTIFICATIONS
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `type` ENUM('INFO', 'SUCCESS', 'WARNING', 'COMMISSION', 'LEAD') NOT NULL DEFAULT 'INFO',
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `link_url` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  INDEX `idx_notif_user_unread` (`user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. SETTINGS
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category` VARCHAR(50) NOT NULL DEFAULT 'general',
  `setting_key` VARCHAR(80) NOT NULL UNIQUE,
  `setting_value` TEXT NOT NULL,
  `data_type` VARCHAR(30) NOT NULL DEFAULT 'string',
  `description` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. AUDIT LOGS
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NULL,
  `user_role` VARCHAR(30) NULL,
  `action` VARCHAR(100) NOT NULL,
  `module` VARCHAR(80) NOT NULL,
  `record_id` VARCHAR(50) NULL,
  `ip_address` VARCHAR(50) NULL,
  `user_agent` TEXT NULL,
  `details` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_audit_module` (`module`),
  INDEX `idx_audit_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
