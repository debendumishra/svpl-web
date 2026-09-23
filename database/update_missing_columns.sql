-- ==========================================================
-- Surya Vistaara Pvt. Ltd. (SVPL)
-- Master Schema Update & Missing Column Patch for MySQL 8.x
-- Run this in phpMyAdmin for database: u230808862_svpl
-- ==========================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 0. CREATE withdrawal_requests TABLE
CREATE TABLE IF NOT EXISTS `withdrawal_requests` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `request_code` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` INT UNSIGNED NOT NULL,
  `advisor_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL,
  `tds_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `net_payable` DECIMAL(12,2) NOT NULL,
  `bank_name` VARCHAR(100) NULL,
  `bank_branch` VARCHAR(100) NULL,
  `account_holder` VARCHAR(150) NULL,
  `account_number` VARCHAR(50) NULL,
  `ifsc_code` VARCHAR(20) NULL,
  `status` ENUM('PENDING', 'APPROVED', 'PAID', 'REJECTED') NOT NULL DEFAULT 'PENDING',
  `admin_remarks` TEXT NULL,
  `processed_by_user_id` INT UNSIGNED NULL,
  `utr_number` VARCHAR(100) NULL,
  `requested_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_wr_user` (`user_id`),
  INDEX `idx_wr_advisor` (`advisor_id`),
  INDEX `idx_wr_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1. FIX package_dispatches TABLE (Add missing columns)
CREATE TABLE IF NOT EXISTS `package_dispatches` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `lead_id` INT UNSIGNED NULL,
  `advisor_id` INT UNSIGNED NULL,
  `engineer_id` INT UNSIGNED NULL,
  `dispatch_type` VARCHAR(50) NOT NULL DEFAULT 'SOLAR_EQUIPMENT',
  `tracking_number` VARCHAR(100) NULL,
  `courier_partner` VARCHAR(100) NULL DEFAULT 'SVPL Logistics Odisha',
  `dispatch_date` DATE NULL,
  `delivery_date` DATE NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Dispatched',
  `customer_acknowledged` TINYINT(1) NOT NULL DEFAULT 0,
  `customer_acknowledged_at` DATETIME NULL,
  `customer_acknowledgment_notes` TEXT NULL,
  `items_included` TEXT NULL,
  `delivery_address` TEXT NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_pd_lead` (`lead_id`),
  INDEX `idx_pd_advisor` (`advisor_id`),
  INDEX `idx_pd_engineer` (`engineer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add columns if package_dispatches already existed
SET @dbname = DATABASE();
SET @tablename = "package_dispatches";

-- engineer_id
SET @columnname = "engineer_id";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE package_dispatches ADD COLUMN `engineer_id` INT UNSIGNED NULL AFTER `advisor_id`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- customer_acknowledged
SET @columnname = "customer_acknowledged";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE package_dispatches ADD COLUMN `customer_acknowledged` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- customer_acknowledged_at
SET @columnname = "customer_acknowledged_at";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE package_dispatches ADD COLUMN `customer_acknowledged_at` DATETIME NULL AFTER `customer_acknowledged`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- customer_acknowledgment_notes
SET @columnname = "customer_acknowledgment_notes";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE package_dispatches ADD COLUMN `customer_acknowledgment_notes` TEXT NULL AFTER `customer_acknowledged_at`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;


-- 2. FIX customers TABLE (PM Surya Ghar & E-Sign Columns)
SET @tablename = "customers";

-- pm_surya_ghar_id
SET @columnname = "pm_surya_ghar_id";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE customers ADD COLUMN `pm_surya_ghar_id` VARCHAR(100) NULL AFTER `consumer_number`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- notification_number
SET @columnname = "notification_number";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE customers ADD COLUMN `notification_number` VARCHAR(100) NULL AFTER `pm_surya_ghar_id`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- customer_signature
SET @columnname = "customer_signature";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE customers ADD COLUMN `customer_signature` MEDIUMTEXT NULL AFTER `profile_photo`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- agreement_accepted
SET @columnname = "agreement_accepted";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE customers ADD COLUMN `agreement_accepted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `customer_signature`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- agreement_accepted_at
SET @columnname = "agreement_accepted_at";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE customers ADD COLUMN `agreement_accepted_at` DATETIME NULL AFTER `agreement_accepted`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- father_husband_name
SET @columnname = "father_husband_name";
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = @dbname AND TABLE_NAME = @tablename AND COLUMN_NAME = @columnname) > 0,
  "SELECT 1",
  "ALTER TABLE customers ADD COLUMN `father_husband_name` VARCHAR(150) NULL AFTER `last_name`;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;


-- 3. CREATE engineers TABLE
CREATE TABLE IF NOT EXISTS `engineers` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NULL UNIQUE,
    `engineer_code` VARCHAR(30) NOT NULL UNIQUE,
    `full_name` VARCHAR(150) NOT NULL,
    `mobile` VARCHAR(20) NOT NULL,
    `alt_mobile` VARCHAR(20) NULL,
    `email` VARCHAR(150) NULL,
    `designation` VARCHAR(100) DEFAULT 'Solar Installation Project Engineer',
    `qualification` VARCHAR(150) DEFAULT 'B.Tech (Electrical / Mechanical) / Diploma Solar Tech',
    `assigned_districts` VARCHAR(255) DEFAULT 'Khordha, Cuttack, Puri',
    `aadhaar_number` VARCHAR(30) NULL,
    `experience_years` DECIMAL(4,1) DEFAULT 3.5,
    `photo_url` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_eng_code` (`engineer_code`),
    INDEX `idx_eng_mobile` (`mobile`),
    INDEX `idx_eng_status` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 4. CREATE company_ledger TABLE
CREATE TABLE IF NOT EXISTS `company_ledger` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `voucher_no` VARCHAR(50) NOT NULL UNIQUE,
  `entry_type` ENUM('RECEIPT', 'PAYMENT', 'CONTRA', 'JOURNAL') NOT NULL DEFAULT 'RECEIPT',
  `entry_date` DATE NOT NULL,
  `account_head` VARCHAR(100) NOT NULL,
  `party_type` ENUM('ADVISOR', 'CUSTOMER', 'VENDOR', 'INTERNAL', 'BANK', 'GOVERNMENT', 'OTHER') NOT NULL DEFAULT 'ADVISOR',
  `party_id` INT UNSIGNED NULL,
  `party_name` VARCHAR(150) NOT NULL,
  `party_identifier` VARCHAR(50) NULL,
  `payment_mode` ENUM('UPI', 'NEFT', 'IMPS', 'RTGS', 'CASH', 'CHEQUE', 'BANK_TRANSFER', 'WALLET_ADJUSTMENT') NOT NULL DEFAULT 'UPI',
  `reference_no` VARCHAR(100) NULL,
  `debit_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `credit_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `running_balance` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
  `narration` TEXT NULL,
  `supporting_doc_url` VARCHAR(255) NULL,
  `status` ENUM('CONFIRMED', 'PENDING', 'RECONCILED', 'CANCELLED') NOT NULL DEFAULT 'CONFIRMED',
  `created_by_user_id` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_ledger_date` (`entry_date`),
  INDEX `idx_ledger_party` (`party_type`, `party_id`),
  INDEX `idx_ledger_head` (`account_head`),
  INDEX `idx_ledger_voucher` (`voucher_no`),
  INDEX `idx_ledger_type` (`entry_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 5. CREATE instant_payouts TABLE
CREATE TABLE IF NOT EXISTS `instant_payouts` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `payout_ref` VARCHAR(50) NOT NULL UNIQUE,
  `advisor_id` INT UNSIGNED NOT NULL,
  `wallet_transaction_id` INT UNSIGNED NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `tds_deducted` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `admin_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `net_payout` DECIMAL(10,2) NOT NULL,
  `payout_mode` ENUM('IMPS', 'NEFT', 'UPI', 'MANUAL_BANK') NOT NULL DEFAULT 'IMPS',
  `bank_name` VARCHAR(100) NOT NULL,
  `account_number` VARCHAR(50) NOT NULL,
  `ifsc_code` VARCHAR(20) NOT NULL,
  `account_holder_name` VARCHAR(150) NOT NULL,
  `bank_rrn` VARCHAR(100) NULL,
  `status` ENUM('INITIATED', 'PROCESSING', 'SUCCESS', 'FAILED', 'REVERSED') NOT NULL DEFAULT 'INITIATED',
  `failure_reason` VARCHAR(255) NULL,
  `initiated_by` INT UNSIGNED NULL,
  `initiated_at` DATETIME NOT NULL,
  `processed_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_payout_advisor` (`advisor_id`),
  INDEX `idx_payout_status` (`status`),
  INDEX `idx_payout_ref` (`payout_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 6. CREATE withdrawals TABLE
CREATE TABLE IF NOT EXISTS `withdrawals` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `advisor_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('PENDING', 'APPROVED', 'PROCESSING', 'REJECTED', 'COMPLETED') NOT NULL DEFAULT 'PENDING',
  `bank_name` VARCHAR(100) NULL,
  `account_number` VARCHAR(50) NULL,
  `ifsc_code` VARCHAR(20) NULL,
  `account_holder_name` VARCHAR(150) NULL,
  `upi_id` VARCHAR(100) NULL,
  `transaction_reference` VARCHAR(100) NULL,
  `admin_notes` TEXT NULL,
  `requested_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `processed_at` DATETIME NULL,
  `processed_by` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_wd_advisor` (`advisor_id`),
  INDEX `idx_wd_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 7. COMMISSION ENGINE TABLES
CREATE TABLE IF NOT EXISTS `commission_rules` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `rule_type` VARCHAR(50) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `capacity_kw_min` DECIMAL(5,2) DEFAULT 0.00,
  `capacity_kw_max` DECIMAL(5,2) DEFAULT 999.99,
  `level_1_percent` DECIMAL(5,2) DEFAULT 0.00,
  `level_2_percent` DECIMAL(5,2) DEFAULT 0.00,
  `level_3_percent` DECIMAL(5,2) DEFAULT 0.00,
  `level_4_percent` DECIMAL(5,2) DEFAULT 0.00,
  `level_5_percent` DECIMAL(5,2) DEFAULT 0.00,
  `flat_amount` DECIMAL(10,2) DEFAULT 0.00,
  `tds_percent` DECIMAL(5,2) DEFAULT 5.00,
  `admin_charge_percent` DECIMAL(5,2) DEFAULT 5.00,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `commission_ledgers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `advisor_id` INT UNSIGNED NOT NULL,
  `lead_id` INT UNSIGNED NULL,
  `customer_id` INT UNSIGNED NULL,
  `commission_type` VARCHAR(50) NOT NULL,
  `level` INT UNSIGNED DEFAULT 1,
  `gross_amount` DECIMAL(10,2) NOT NULL,
  `tds_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `admin_charge` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('PENDING', 'APPROVED', 'DISBURSED', 'REJECTED', 'HOLD') NOT NULL DEFAULT 'PENDING',
  `approval_id` INT UNSIGNED NULL,
  `payout_cycle_id` INT UNSIGNED NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_cl_advisor` (`advisor_id`),
  INDEX `idx_cl_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `commission_approvals` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `batch_ref` VARCHAR(50) NOT NULL UNIQUE,
  `total_advisors` INT UNSIGNED NOT NULL DEFAULT 0,
  `total_gross` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_tds` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_admin_charge` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_net` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED', 'PROCESSED') NOT NULL DEFAULT 'DRAFT',
  `approved_by` INT UNSIGNED NULL,
  `approved_at` DATETIME NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `commission_payout_cycles` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `cycle_code` VARCHAR(50) NOT NULL UNIQUE,
  `cycle_name` VARCHAR(100) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `total_records` INT UNSIGNED NOT NULL DEFAULT 0,
  `status` ENUM('OPEN', 'PROCESSING', 'CLOSED', 'PAID') NOT NULL DEFAULT 'OPEN',
  `closed_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `pool_bonus_records` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `advisor_id` INT UNSIGNED NOT NULL,
  `pool_name` VARCHAR(100) NOT NULL,
  `month` VARCHAR(7) NOT NULL,
  `qualifying_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `bonus_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` ENUM('CALCULATED', 'APPROVED', 'DISBURSED') NOT NULL DEFAULT 'CALCULATED',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_pool_advisor` (`advisor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `monthly_rewards` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(150) NOT NULL,
  `description` TEXT NULL,
  `target_customers` INT UNSIGNED NOT NULL DEFAULT 5,
  `reward_type` VARCHAR(50) NOT NULL DEFAULT 'CASH',
  `reward_value` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `image_url` VARCHAR(255) NULL,
  `valid_from` DATE NOT NULL,
  `valid_to` DATE NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `advisor_reward_claims` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `advisor_id` INT UNSIGNED NOT NULL,
  `reward_id` INT UNSIGNED NOT NULL,
  `achievement_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `status` ENUM('ELIGIBLE', 'CLAIMED', 'APPROVED', 'DELIVERED', 'REJECTED') NOT NULL DEFAULT 'ELIGIBLE',
  `claimed_at` DATETIME NULL,
  `approved_at` DATETIME NULL,
  `remarks` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_claim_advisor` (`advisor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
