<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Self-Healing Database Auto-Migrator
 * Safely checks and adds any missing columns or tables without altering existing data.
 */

declare(strict_types=1);

namespace Database;

use App\Helpers\Database;
use PDO;

class AutoMigrator
{
    public static function run(): array
    {
        $messages = [];
        try {
            $db = Database::getInstance();
            $driver = Database::getDriver();

            if ($driver === 'mysql') {
                $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
            }

            // 1. Ensure Engineers table
            $db->exec("
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
            ");
            $messages[] = "Verified table: engineers";

            // 2. Ensure package_dispatches columns
            if (Database::tableExists('package_dispatches')) {
                $cols = Database::fetchAll("DESCRIBE `package_dispatches`");
                $colNames = array_column($cols, 'Field');

                if (!in_array('engineer_id', $colNames)) {
                    $db->exec("ALTER TABLE `package_dispatches` ADD COLUMN `engineer_id` INT UNSIGNED NULL AFTER `advisor_id`");
                    $messages[] = "Added column: package_dispatches.engineer_id";
                }
                if (!in_array('customer_acknowledged', $colNames)) {
                    $db->exec("ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledged` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`");
                    $messages[] = "Added column: package_dispatches.customer_acknowledged";
                }
                if (!in_array('customer_acknowledged_at', $colNames)) {
                    $db->exec("ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledged_at` DATETIME NULL AFTER `customer_acknowledged`");
                    $messages[] = "Added column: package_dispatches.customer_acknowledged_at";
                }
                if (!in_array('customer_acknowledgment_notes', $colNames)) {
                    $db->exec("ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledgment_notes` TEXT NULL AFTER `customer_acknowledged_at`");
                    $messages[] = "Added column: package_dispatches.customer_acknowledgment_notes";
                }
            }

            // 3. Ensure customers columns
            if (Database::tableExists('customers')) {
                $cols = Database::fetchAll("DESCRIBE `customers`");
                $colNames = array_column($cols, 'Field');

                if (!in_array('pm_surya_ghar_id', $colNames)) {
                    $db->exec("ALTER TABLE `customers` ADD COLUMN `pm_surya_ghar_id` VARCHAR(100) NULL AFTER `consumer_number`");
                    $messages[] = "Added column: customers.pm_surya_ghar_id";
                }
                if (!in_array('notification_number', $colNames)) {
                    $db->exec("ALTER TABLE `customers` ADD COLUMN `notification_number` VARCHAR(100) NULL AFTER `pm_surya_ghar_id`");
                    $messages[] = "Added column: customers.notification_number";
                }
                if (!in_array('customer_signature', $colNames)) {
                    $db->exec("ALTER TABLE `customers` ADD COLUMN `customer_signature` MEDIUMTEXT NULL AFTER `profile_photo`");
                    $messages[] = "Added column: customers.customer_signature";
                }
                if (!in_array('agreement_accepted', $colNames)) {
                    $db->exec("ALTER TABLE `customers` ADD COLUMN `agreement_accepted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `customer_signature`");
                    $messages[] = "Added column: customers.agreement_accepted";
                }
                if (!in_array('agreement_accepted_at', $colNames)) {
                    $db->exec("ALTER TABLE `customers` ADD COLUMN `agreement_accepted_at` DATETIME NULL AFTER `agreement_accepted`");
                    $messages[] = "Added column: customers.agreement_accepted_at";
                }
                if (!in_array('father_husband_name', $colNames)) {
                    $db->exec("ALTER TABLE `customers` ADD COLUMN `father_husband_name` VARCHAR(150) NULL AFTER `last_name`");
                    $messages[] = "Added column: customers.father_husband_name";
                }
            }

            // 4. Ensure company_ledger
            $db->exec("
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
            ");

            // 5. Ensure withdrawals
            $db->exec("
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
            ");

            // 6. Ensure instant_payouts
            $db->exec("
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
            ");

            // 7. Ensure commission tables
            $db->exec("
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
            ");

            $db->exec("
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
            ");

            $db->exec("
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
            ");

            $db->exec("
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
            ");

            $db->exec("
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
            ");

            $db->exec("
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
            ");

            $db->exec("
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
            ");

            if ($driver === 'mysql') {
                $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
            }

            return ['success' => true, 'messages' => $messages];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'messages' => $messages];
        }
    }
}
