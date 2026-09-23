-- =========================================================================
-- Surya Vistaara Pvt. Ltd. (SVPL) - Complete Master Database Schema Patch
-- Target Database: u230808862_svpl / svpl_db
-- Generated: 2026-09-23 16:49:17
-- All 48 Tables with Safe IF NOT EXISTS (No AFTER clauses for 100% compatibility)
-- =========================================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";

-- ---------------------------------------------------------
-- Table structure for table `advisor_genealogy`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `advisor_genealogy` (
  `ancestor_id` int(10) unsigned NOT NULL,
  `descendant_id` int(10) unsigned NOT NULL,
  `depth` tinyint(3) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`ancestor_id`,`descendant_id`),
  KEY `idx_gen_descendant_depth` (`descendant_id`,`depth`),
  KEY `idx_gen_ancestor_depth` (`ancestor_id`,`depth`),
  CONSTRAINT `advisor_genealogy_ibfk_1` FOREIGN KEY (`ancestor_id`) REFERENCES `advisors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `advisor_genealogy_ibfk_2` FOREIGN KEY (`descendant_id`) REFERENCES `advisors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `advisor_reward_claims`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `advisor_reward_claims` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `advisor_id` int(10) unsigned NOT NULL,
  `reward_id` int(10) unsigned NOT NULL,
  `customer_count_snapshot` int(10) unsigned NOT NULL,
  `claim_type` varchar(30) DEFAULT 'CASH',
  `status` varchar(30) DEFAULT 'ACHIEVED',
  `approved_by` int(10) unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `delivery_ref` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `wallet_transaction_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reward_id` (`reward_id`),
  KEY `idx_reward_advisor` (`advisor_id`),
  KEY `idx_reward_status` (`status`),
  CONSTRAINT `advisor_reward_claims_ibfk_1` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`),
  CONSTRAINT `advisor_reward_claims_ibfk_2` FOREIGN KEY (`reward_id`) REFERENCES `advisor_rewards` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `advisor_rewards`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `advisor_rewards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reward_name` varchar(150) NOT NULL,
  `customer_target` int(10) unsigned NOT NULL,
  `per_customer_amount` decimal(15,2) NOT NULL,
  `total_reward_value` decimal(15,2) NOT NULL,
  `reward_type` varchar(50) DEFAULT 'CASH_OR_PRODUCT',
  `eligibility_type` varchar(30) DEFAULT 'LIFETIME',
  `is_repeatable` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_target` (`customer_target`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `advisor_wallet_transactions`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `advisor_wallet_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_ref` varchar(60) NOT NULL,
  `advisor_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `commission_id` int(10) unsigned DEFAULT NULL,
  `transaction_type` varchar(50) NOT NULL,
  `credit_amount` decimal(15,2) DEFAULT 0.00,
  `debit_amount` decimal(15,2) DEFAULT 0.00,
  `balance_after` decimal(15,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_ref` (`transaction_ref`),
  KEY `idx_txn_advisor` (`advisor_id`),
  KEY `idx_txn_type` (`transaction_type`),
  KEY `idx_txn_comm` (`commission_id`),
  CONSTRAINT `advisor_wallet_transactions_ibfk_1` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `advisors`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `advisors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `advisor_code` varchar(30) NOT NULL,
  `referral_code` varchar(30) NOT NULL,
  `sponsor_id` int(10) unsigned DEFAULT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `father_spouse_name` varchar(150) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT 'Male',
  `mobile` varchar(20) NOT NULL,
  `alt_mobile` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `state` varchar(50) NOT NULL DEFAULT 'Odisha',
  `district` varchar(80) NOT NULL,
  `subdivision` varchar(80) DEFAULT NULL,
  `block` varchar(80) NOT NULL,
  `gram_panchayat` varchar(80) NOT NULL,
  `village` varchar(80) DEFAULT NULL,
  `pincode` varchar(10) NOT NULL,
  `address_line` text DEFAULT NULL,
  `aadhaar_number` varchar(30) DEFAULT NULL,
  `pan_number` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_branch` varchar(100) DEFAULT NULL,
  `account_holder` varchar(150) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `passbook_doc_id` int(10) unsigned DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'ACTIVE',
  `free_registration` tinyint(1) NOT NULL DEFAULT 0,
  `id_card_generated` tinyint(1) NOT NULL DEFAULT 0,
  `qualification_status` enum('NEW','ACTIVE','QUALIFIED','PROMOTED','INACTIVE','SUSPENDED') NOT NULL DEFAULT 'NEW',
  `current_level` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `customer_count` int(10) unsigned NOT NULL DEFAULT 0,
  `direct_customer_count` int(10) unsigned NOT NULL DEFAULT 0,
  `direct_advisor_count` int(10) unsigned NOT NULL DEFAULT 0,
  `total_team_count` int(10) unsigned NOT NULL DEFAULT 0,
  `joining_fee` decimal(10,2) NOT NULL DEFAULT 1500.00,
  `joining_fee_paid` tinyint(1) NOT NULL DEFAULT 0,
  `joining_date` date DEFAULT NULL,
  `qualified_at` datetime DEFAULT NULL,
  `id_card_number` varchar(50) DEFAULT NULL,
  `appointment_letter_no` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `advisor_code` (`advisor_code`),
  UNIQUE KEY `referral_code` (`referral_code`),
  UNIQUE KEY `id_card_number` (`id_card_number`),
  UNIQUE KEY `appointment_letter_no` (`appointment_letter_no`),
  KEY `sponsor_id` (`sponsor_id`),
  KEY `idx_adv_code` (`advisor_code`),
  KEY `idx_adv_referral` (`referral_code`),
  KEY `idx_adv_status` (`status`),
  KEY `idx_adv_qual_status` (`qualification_status`),
  KEY `idx_adv_district` (`district`),
  CONSTRAINT `advisors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `advisors_ibfk_2` FOREIGN KEY (`sponsor_id`) REFERENCES `advisors` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `audit_logs`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `user_role` varchar(30) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(80) NOT NULL,
  `record_id` varchar(50) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit_module` (`module`),
  KEY `idx_audit_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commission_audit_logs`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commission_audit_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(60) NOT NULL,
  `entity_id` int(10) unsigned DEFAULT NULL,
  `old_value` longtext DEFAULT NULL,
  `new_value` longtext DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_audit_action` (`action`),
  KEY `idx_audit_entity` (`entity_type`,`entity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=316 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commission_cycles`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commission_cycles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cycle_month` tinyint(3) unsigned NOT NULL,
  `cycle_year` smallint(5) unsigned NOT NULL,
  `total_business_amount` decimal(15,2) DEFAULT 0.00,
  `total_commissions` decimal(15,2) DEFAULT 0.00,
  `total_bonus` decimal(15,2) DEFAULT 0.00,
  `total_pool` decimal(15,2) DEFAULT 0.00,
  `total_rewards` decimal(15,2) DEFAULT 0.00,
  `total_payable` decimal(15,2) DEFAULT 0.00,
  `is_locked` tinyint(1) DEFAULT 0,
  `locked_by` int(10) unsigned DEFAULT NULL,
  `locked_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cycle_period` (`cycle_month`,`cycle_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commission_plans`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commission_plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(100) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonus_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commission_rule_levels`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commission_rule_levels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rule_id` int(10) unsigned NOT NULL,
  `level` int(10) unsigned NOT NULL,
  `commission_amount` decimal(15,2) DEFAULT 0.00,
  `calculation_type` varchar(20) DEFAULT 'FIXED',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rule_level` (`rule_id`,`level`),
  CONSTRAINT `commission_rule_levels_ibfk_1` FOREIGN KEY (`rule_id`) REFERENCES `commission_rules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=271 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commission_rules`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commission_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int(10) unsigned DEFAULT NULL,
  `rule_code` varchar(60) DEFAULT NULL,
  `rule_name` varchar(150) NOT NULL,
  `rule_type` varchar(40) DEFAULT 'PRODUCT_REFERRAL',
  `product_name` varchar(100) DEFAULT NULL,
  `capacity_kw` decimal(8,2) DEFAULT NULL,
  `connection_type` varchar(50) DEFAULT NULL,
  `direct_commission` decimal(15,2) DEFAULT 0.00,
  `version` int(10) unsigned DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `effective_from` date DEFAULT NULL,
  `effective_to` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `rule_code` (`rule_code`),
  KEY `idx_rule_type` (`rule_type`),
  KEY `idx_rule_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commission_transactions`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commission_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `transaction_code` varchar(60) NOT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `payment_id` int(10) unsigned DEFAULT NULL,
  `advisor_id` int(10) unsigned NOT NULL,
  `source_advisor_id` int(10) unsigned DEFAULT NULL,
  `level` int(10) unsigned DEFAULT 1,
  `commission_type` varchar(50) NOT NULL,
  `rule_id` int(10) unsigned DEFAULT NULL,
  `rule_version` int(10) unsigned DEFAULT 1,
  `product_name` varchar(100) DEFAULT NULL,
  `payment_amount` decimal(15,2) DEFAULT 0.00,
  `company_credit_date` date DEFAULT NULL,
  `commission_month` tinyint(3) unsigned NOT NULL,
  `commission_year` smallint(5) unsigned NOT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `tds_deducted` decimal(15,2) DEFAULT 0.00,
  `admin_deducted` decimal(15,2) DEFAULT 0.00,
  `net_amount` decimal(15,2) NOT NULL,
  `qualification_status` varchar(30) DEFAULT 'ELIGIBLE',
  `qualification_notes` text DEFAULT NULL,
  `rule_snapshot_json` longtext DEFAULT NULL,
  `status` varchar(30) DEFAULT 'PENDING',
  `approved_by` int(10) unsigned DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `is_reversal` tinyint(1) DEFAULT 0,
  `parent_transaction_id` int(10) unsigned DEFAULT NULL,
  `reversal_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_code` (`transaction_code`),
  KEY `idx_comm_status` (`status`),
  KEY `idx_comm_advisor` (`advisor_id`),
  KEY `idx_comm_payment` (`payment_id`),
  KEY `idx_comm_period` (`commission_year`,`commission_month`),
  CONSTRAINT `commission_transactions_ibfk_1` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=712 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commission_upline_qualifications`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commission_upline_qualifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `min_personal_customers` int(10) unsigned NOT NULL,
  `max_eligible_level` int(10) unsigned NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `min_personal_customers` (`min_personal_customers`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `commissions`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `commissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `advisor_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `lead_id` int(10) unsigned DEFAULT NULL,
  `source_advisor_id` int(10) unsigned DEFAULT NULL,
  `level` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `commission_type` varchar(50) NOT NULL DEFAULT 'FLAT',
  `base_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `commission_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bonus_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tds_deducted` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(50) NOT NULL DEFAULT 'APPROVED',
  `generated_at` datetime DEFAULT current_timestamp(),
  `approved_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `calculation_notes` text DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `lead_id` (`lead_id`),
  KEY `idx_comm_advisor` (`advisor_id`),
  KEY `idx_comm_status` (`status`),
  CONSTRAINT `commissions_ibfk_1` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commissions_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `commissions_ibfk_3` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `company_ledger`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `company_ledger` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `voucher_no` varchar(50) NOT NULL,
  `entry_type` enum('RECEIPT','PAYMENT','CONTRA','JOURNAL') NOT NULL DEFAULT 'RECEIPT',
  `entry_date` date NOT NULL,
  `account_head` varchar(100) NOT NULL,
  `party_type` enum('ADVISOR','CUSTOMER','VENDOR','INTERNAL','BANK','GOVERNMENT','OTHER') NOT NULL DEFAULT 'ADVISOR',
  `party_id` int(10) unsigned DEFAULT NULL,
  `party_name` varchar(150) NOT NULL,
  `party_identifier` varchar(50) DEFAULT NULL,
  `payment_mode` enum('UPI','NEFT','IMPS','RTGS','CASH','CHEQUE','BANK_TRANSFER','WALLET_ADJUSTMENT') NOT NULL DEFAULT 'UPI',
  `reference_no` varchar(100) DEFAULT NULL,
  `debit_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `credit_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `running_balance` decimal(14,2) NOT NULL DEFAULT 0.00,
  `narration` text DEFAULT NULL,
  `supporting_doc_url` varchar(255) DEFAULT NULL,
  `status` enum('CONFIRMED','PENDING','RECONCILED','CANCELLED') NOT NULL DEFAULT 'CONFIRMED',
  `created_by_user_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `voucher_no` (`voucher_no`),
  KEY `idx_ledger_date` (`entry_date`),
  KEY `idx_ledger_party` (`party_type`,`party_id`),
  KEY `idx_ledger_head` (`account_head`),
  KEY `idx_ledger_voucher` (`voucher_no`),
  KEY `idx_ledger_type` (`entry_type`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `custom_id_cards`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `custom_id_cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `card_type` varchar(50) NOT NULL DEFAULT 'BOE',
  `card_code` varchar(50) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `jurisdiction` varchar(150) DEFAULT NULL,
  `blood_group` varchar(20) NOT NULL DEFAULT 'O+ve',
  `mobile` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `valid_thru` varchar(50) DEFAULT '31-12-2027',
  `emergency_contact` varchar(50) DEFAULT NULL,
  `created_by_user_id` int(10) unsigned DEFAULT NULL,
  `print_count` int(10) unsigned NOT NULL DEFAULT 0,
  `last_printed_at` datetime DEFAULT NULL,
  `status` enum('ACTIVE','ARCHIVED') NOT NULL DEFAULT 'ACTIVE',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_card_code` (`card_code`),
  KEY `idx_card_type` (`card_type`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `customer_bonuses`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer_bonuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `bonus_amount` decimal(10,2) NOT NULL DEFAULT 500.00,
  `status` enum('PENDING','APPROVED','PAYABLE','PAID') NOT NULL DEFAULT 'PENDING',
  `approved_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `customer_bonuses_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `customer_monthly_bonus_rules`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer_monthly_bonus_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `min_customers` int(10) unsigned NOT NULL,
  `max_customers` int(10) unsigned DEFAULT NULL,
  `bonus_amount` decimal(15,2) NOT NULL,
  `calculation_mode` varchar(30) DEFAULT 'HIGHEST_SLAB',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `customer_special_bonus_rules`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer_special_bonus_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bonus_month` tinyint(3) unsigned NOT NULL,
  `bonus_year` smallint(5) unsigned NOT NULL,
  `bonus_amount` decimal(15,2) NOT NULL,
  `max_allowed_amount` decimal(15,2) DEFAULT 85000.00,
  `eligibility_conditions` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `declared_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_month_year` (`bonus_month`,`bonus_year`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `customer_status_history`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customer_status_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `lead_id` int(10) unsigned DEFAULT NULL,
  `from_stage` varchar(50) DEFAULT NULL,
  `to_stage` varchar(50) DEFAULT NULL,
  `from_status` varchar(50) DEFAULT NULL,
  `to_status` varchar(50) DEFAULT NULL,
  `changed_by_user_id` int(10) unsigned DEFAULT NULL,
  `user_code` varchar(50) DEFAULT NULL,
  `user_name` varchar(150) DEFAULT NULL,
  `user_designation` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_csh_customer` (`customer_id`),
  KEY `idx_csh_user` (`changed_by_user_id`),
  CONSTRAINT `customer_status_history_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_status_history_ibfk_2` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `customers`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `customer_code` varchar(30) NOT NULL,
  `advisor_id` int(10) unsigned DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `father_husband_name` varchar(150) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT 'Male',
  `mobile` varchar(20) NOT NULL,
  `alt_mobile` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `customer_signature` varchar(255) DEFAULT NULL,
  `state` varchar(50) NOT NULL DEFAULT 'Odisha',
  `district` varchar(80) NOT NULL,
  `block` varchar(80) NOT NULL,
  `gram_panchayat` varchar(80) NOT NULL,
  `village` varchar(80) DEFAULT NULL,
  `house_address` text DEFAULT NULL,
  `address_line` text DEFAULT NULL,
  `pincode` varchar(10) NOT NULL,
  `electricity_consumer_name` varchar(150) DEFAULT NULL,
  `electricity_consumer_no` varchar(50) DEFAULT NULL,
  `discom` varchar(50) DEFAULT NULL,
  `discom_name` varchar(50) DEFAULT NULL,
  `consumer_number` varchar(50) DEFAULT NULL,
  `pm_surya_ghar_id` varchar(100) DEFAULT NULL,
  `notification_number` varchar(100) DEFAULT NULL,
  `electricity_bill_mobile` varchar(20) DEFAULT NULL,
  `electricity_bill_dob` date DEFAULT NULL,
  `monthly_bill` decimal(10,2) DEFAULT NULL,
  `monthly_avg_bill` decimal(10,2) DEFAULT NULL,
  `sanctioned_load_kw` decimal(5,2) DEFAULT NULL,
  `proposed_solar_kw` decimal(5,2) DEFAULT NULL,
  `rooftop_type` varchar(50) DEFAULT NULL,
  `roof_type` varchar(50) DEFAULT NULL,
  `roof_ownership` enum('Owned','Rented','Shared') DEFAULT 'Owned',
  `approx_roof_area_sqft` decimal(10,2) DEFAULT NULL,
  `roof_area_sqft` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'New',
  `agreement_accepted` tinyint(1) DEFAULT 0,
  `agreement_accepted_at` datetime DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_branch` varchar(100) DEFAULT NULL,
  `account_holder` varchar(150) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `joining_bonus_status` enum('PENDING','APPROVED','PAID') DEFAULT 'PENDING',
  `joining_bonus_amount` decimal(10,2) DEFAULT 500.00,
  `converted_to_advisor` tinyint(1) NOT NULL DEFAULT 0,
  `converted_advisor_id` int(10) unsigned DEFAULT NULL,
  `assigned_boe_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_code` (`customer_code`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `idx_cus_code` (`customer_code`),
  KEY `idx_cus_advisor` (`advisor_id`),
  KEY `idx_cus_mobile` (`mobile`),
  KEY `idx_cus_district` (`district`),
  KEY `fk_cus_assigned_boe` (`assigned_boe_id`),
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `customers_ibfk_2` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_cus_assigned_boe` FOREIGN KEY (`assigned_boe_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=1595 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `discom_providers`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `discom_providers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `short_name` varchar(100) NOT NULL,
  `headquarters` varchar(150) DEFAULT 'Odisha',
  `helpline` varchar(100) DEFAULT NULL,
  `portal_url` varchar(255) DEFAULT NULL,
  `coverage_summary` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `dispatch_instruments`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `dispatch_instruments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(50) DEFAULT NULL,
  `item_name` varchar(200) NOT NULL,
  `specifications` text NOT NULL,
  `default_qty` decimal(10,2) NOT NULL DEFAULT 1.00,
  `unit` varchar(50) NOT NULL DEFAULT 'Nos.',
  `category` varchar(100) DEFAULT 'BOS & Hardware',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `item_code` (`item_code`),
  KEY `idx_inst_active` (`is_active`),
  KEY `idx_inst_sort` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `district_discoms`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `district_discoms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `district_name` varchar(100) NOT NULL,
  `discom_id` int(10) unsigned NOT NULL,
  `discom_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `district_name` (`district_name`),
  KEY `district_name_2` (`district_name`),
  KEY `discom_code` (`discom_code`),
  KEY `discom_id` (`discom_id`),
  CONSTRAINT `district_discoms_ibfk_1` FOREIGN KEY (`discom_id`) REFERENCES `discom_providers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `documents`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entity_type` varchar(50) NOT NULL DEFAULT 'CUSTOMER',
  `entity_id` int(10) unsigned DEFAULT NULL,
  `lead_id` int(10) unsigned DEFAULT NULL,
  `document_type` varchar(50) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `document_title` varchar(150) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_hash` varchar(64) DEFAULT NULL,
  `mime_type` varchar(100) NOT NULL DEFAULT 'application/pdf',
  `file_size` int(10) unsigned NOT NULL DEFAULT 0,
  `masked_identifier` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Uploaded',
  `remarks` text DEFAULT NULL,
  `verified_by_user_id` int(10) unsigned DEFAULT NULL,
  `verified_at` datetime DEFAULT NULL,
  `rejection_remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_doc_entity` (`entity_type`,`entity_id`),
  KEY `idx_doc_lead` (`lead_id`),
  KEY `idx_doc_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `engineers`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `engineers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `engineer_code` varchar(30) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `alt_mobile` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_text` varchar(100) DEFAULT 'Engineer@123',
  `designation` varchar(100) DEFAULT 'Solar Installation Project Engineer',
  `qualification` varchar(150) DEFAULT 'B.Tech (Electrical / Mechanical) / Diploma Solar Tech',
  `assigned_districts` varchar(255) DEFAULT 'Khordha, Cuttack, Puri',
  `aadhaar_number` varchar(30) DEFAULT NULL,
  `experience_years` decimal(4,1) DEFAULT 3.5,
  `photo_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `engineer_code` (`engineer_code`),
  UNIQUE KEY `user_id` (`user_id`),
  KEY `idx_eng_code` (`engineer_code`),
  KEY `idx_eng_mobile` (`mobile`),
  KEY `idx_eng_status` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `je_reports`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `je_reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_number` varchar(50) NOT NULL,
  `lead_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `consumer_number` varchar(50) NOT NULL,
  `installed_capacity_kw` decimal(5,2) NOT NULL,
  `panel_make` varchar(100) NOT NULL,
  `panel_serial_numbers` text DEFAULT NULL,
  `inverter_make` varchar(100) NOT NULL,
  `inverter_serial_number` varchar(100) DEFAULT NULL,
  `net_meter_number` varchar(100) DEFAULT NULL,
  `installation_date` date NOT NULL,
  `inspection_date` date NOT NULL,
  `je_name` varchar(100) NOT NULL,
  `je_designation` varchar(100) NOT NULL DEFAULT 'Junior Engineer (Electrical)',
  `discom_division` varchar(100) NOT NULL,
  `inspection_status` enum('PASSED','DEFECT_FOUND','PENDING') NOT NULL DEFAULT 'PASSED',
  `remarks` text DEFAULT NULL,
  `report_file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_number` (`report_number`),
  UNIQUE KEY `lead_id` (`lead_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `je_reports_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `je_reports_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `lead_stage_history`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lead_stage_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `stage` varchar(50) NOT NULL,
  `from_stage` varchar(50) DEFAULT NULL,
  `to_stage` varchar(50) DEFAULT NULL,
  `status_notes` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `changed_by_user_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `lead_id` (`lead_id`),
  KEY `changed_by_user_id` (`changed_by_user_id`),
  CONSTRAINT `lead_stage_history_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lead_stage_history_ibfk_2` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `leads`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `leads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_code` varchar(30) NOT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `advisor_id` int(10) unsigned DEFAULT NULL,
  `package_id` int(10) unsigned DEFAULT NULL,
  `lead_source` varchar(100) DEFAULT 'Advisor Referral',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `state` varchar(100) DEFAULT 'Odisha',
  `district` varchar(100) DEFAULT NULL,
  `block` varchar(100) DEFAULT NULL,
  `gram_panchayat` varchar(100) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `discom_name` varchar(50) DEFAULT 'TPCODL',
  `consumer_number` varchar(50) DEFAULT NULL,
  `electricity_bill_mobile` varchar(20) DEFAULT NULL,
  `electricity_bill_dob` date DEFAULT NULL,
  `proposed_capacity_kw` decimal(5,2) NOT NULL DEFAULT 3.00,
  `stage` varchar(50) NOT NULL DEFAULT 'REGISTRATION',
  `status` varchar(50) NOT NULL DEFAULT 'New',
  `estimated_project_cost` decimal(12,2) NOT NULL DEFAULT 210000.00,
  `subsidy_amount` decimal(10,2) NOT NULL DEFAULT 78000.00,
  `state_subsidy` decimal(10,2) NOT NULL DEFAULT 60000.00,
  `customer_payable_amount` decimal(12,2) NOT NULL DEFAULT 72000.00,
  `assigned_to_user_id` int(10) unsigned DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `next_action` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `net_meter_number` varchar(100) DEFAULT NULL,
  `net_meter_date` date DEFAULT NULL,
  `mmg_intimation_date` date DEFAULT NULL,
  `mmg_intimation_ref` varchar(100) DEFAULT NULL,
  `mmg_report_date` date DEFAULT NULL,
  `mmg_report_number` varchar(100) DEFAULT NULL,
  `bank_second_inst_date` date DEFAULT NULL,
  `bank_second_inst_amount` decimal(12,2) DEFAULT NULL,
  `bank_second_inst_utr` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lead_code` (`lead_code`),
  KEY `customer_id` (`customer_id`),
  KEY `advisor_id` (`advisor_id`),
  KEY `assigned_to_user_id` (`assigned_to_user_id`),
  KEY `idx_lead_stage` (`stage`),
  KEY `idx_lead_status` (`status`),
  CONSTRAINT `leads_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_ibfk_2` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leads_ibfk_3` FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `loans`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `loans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `application_number` varchar(50) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `requested_amount` decimal(12,2) NOT NULL,
  `sanctioned_amount` decimal(12,2) DEFAULT NULL,
  `interest_rate` decimal(5,2) DEFAULT 7.00,
  `tenure_months` int(11) DEFAULT 60,
  `emi_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Not Applied','Documents Pending','Applied','Under Processing','Sanctioned','Rejected','Disbursed') DEFAULT 'Not Applied',
  `sanction_date` date DEFAULT NULL,
  `disbursement_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `lead_id` (`lead_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `locations`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state` varchar(50) NOT NULL DEFAULT 'Odisha',
  `district` varchar(80) NOT NULL,
  `subdivision` varchar(80) DEFAULT NULL,
  `block` varchar(80) NOT NULL,
  `gram_panchayat` varchar(80) NOT NULL,
  `village` varchar(80) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_loc_district` (`district`),
  KEY `idx_loc_block` (`block`)
) ENGINE=InnoDB AUTO_INCREMENT=51805 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `notifications`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `type` enum('INFO','SUCCESS','WARNING','COMMISSION','LEAD') NOT NULL DEFAULT 'INFO',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `link_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notif_user_unread` (`user_id`,`is_read`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `package_dispatches`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `package_dispatches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned DEFAULT NULL,
  `customer_id` int(10) unsigned DEFAULT NULL,
  `advisor_id` int(10) unsigned DEFAULT NULL,
  `engineer_id` int(10) unsigned DEFAULT NULL,
  `dispatch_type` varchar(50) NOT NULL DEFAULT 'SOLAR_EQUIPMENT',
  `tracking_number` varchar(100) DEFAULT NULL,
  `vehicle_number` varchar(50) DEFAULT NULL,
  `driver_name` varchar(150) DEFAULT NULL,
  `driver_mobile` varchar(20) DEFAULT NULL,
  `vendor_name` varchar(150) DEFAULT NULL,
  `courier_partner` varchar(100) DEFAULT 'SVPL Logistics Odisha',
  `dispatch_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Dispatched',
  `customer_acknowledged` tinyint(1) NOT NULL DEFAULT 0,
  `customer_acknowledged_at` datetime DEFAULT NULL,
  `customer_acknowledgment_notes` text DEFAULT NULL,
  `items_included` text DEFAULT NULL,
  `items_json` longtext DEFAULT NULL,
  `media_urls` longtext DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_dsp_engineer` (`engineer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `packages`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `package_code` varchar(50) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `capacity_kw` decimal(5,2) NOT NULL,
  `system_type` varchar(50) NOT NULL DEFAULT 'On-Grid',
  `panel_type` varchar(150) NOT NULL,
  `inverter_type` varchar(150) NOT NULL,
  `key_features` text DEFAULT NULL,
  `battery_included` tinyint(1) NOT NULL DEFAULT 0,
  `total_price` decimal(12,2) NOT NULL,
  `estimated_subsidy` decimal(12,2) NOT NULL,
  `net_customer_cost` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `package_code` (`package_code`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `payments`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_code` varchar(40) NOT NULL,
  `entity_type` enum('ADVISOR','CUSTOMER') NOT NULL,
  `entity_id` int(10) unsigned NOT NULL,
  `purpose` enum('JOINING_FEE','BOOKING_AMOUNT','ADVANCE','BALANCE','OTHER') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('UPI','CASH','BANK_TRANSFER','CARD','OTHER') NOT NULL DEFAULT 'UPI',
  `transaction_ref` varchar(100) DEFAULT NULL,
  `status` enum('PENDING','SUCCESS','FAILED','REFUNDED') NOT NULL DEFAULT 'SUCCESS',
  `receipt_number` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_code` (`payment_code`),
  UNIQUE KEY `receipt_number` (`receipt_number`),
  KEY `idx_pay_entity` (`entity_type`,`entity_id`),
  KEY `idx_pay_receipt` (`receipt_number`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `permissions`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_module_action` (`module`,`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `pool_bonus_rules`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pool_bonus_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `min_direct_advisors` int(10) unsigned DEFAULT 3,
  `min_personal_customers` int(10) unsigned DEFAULT 3,
  `max_children_per_node` int(10) unsigned DEFAULT 3,
  `max_pool_levels` int(10) unsigned DEFAULT 11,
  `level_1_amount` decimal(15,2) DEFAULT 1000.00,
  `subsequent_level_amount` decimal(15,2) DEFAULT 500.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `pool_genealogy`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pool_genealogy` (
  `ancestor_pool_id` int(10) unsigned NOT NULL,
  `descendant_pool_id` int(10) unsigned NOT NULL,
  `depth` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`ancestor_pool_id`,`descendant_pool_id`),
  KEY `descendant_pool_id` (`descendant_pool_id`),
  KEY `idx_pool_depth` (`depth`),
  CONSTRAINT `pool_genealogy_ibfk_1` FOREIGN KEY (`ancestor_pool_id`) REFERENCES `pool_members` (`id`),
  CONSTRAINT `pool_genealogy_ibfk_2` FOREIGN KEY (`descendant_pool_id`) REFERENCES `pool_members` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `pool_members`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pool_members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pool_number` int(10) unsigned NOT NULL,
  `pool_label` varchar(30) NOT NULL,
  `advisor_id` int(10) unsigned NOT NULL,
  `parent_pool_id` int(10) unsigned DEFAULT NULL,
  `pool_level` int(10) unsigned DEFAULT 1,
  `qualified_at` datetime DEFAULT current_timestamp(),
  `direct_pool_children_count` int(10) unsigned DEFAULT 0,
  `status` varchar(20) DEFAULT 'ACTIVE',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pool_number` (`pool_number`),
  UNIQUE KEY `pool_label` (`pool_label`),
  UNIQUE KEY `advisor_id` (`advisor_id`),
  KEY `idx_pool_parent` (`parent_pool_id`),
  KEY `idx_pool_number` (`pool_number`),
  CONSTRAINT `pool_members_ibfk_1` FOREIGN KEY (`advisor_id`) REFERENCES `advisors` (`id`),
  CONSTRAINT `pool_members_ibfk_2` FOREIGN KEY (`parent_pool_id`) REFERENCES `pool_members` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `quotations`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `quotations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_number` varchar(50) NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `lead_id` int(10) unsigned NOT NULL,
  `solar_capacity_kw` decimal(5,2) NOT NULL,
  `panel_brand` varchar(100) NOT NULL DEFAULT 'Dhwajja Mono PERC Tier 1',
  `panel_wattage` int(11) NOT NULL DEFAULT 550,
  `panel_quantity` int(11) NOT NULL DEFAULT 6,
  `inverter_brand` varchar(100) NOT NULL DEFAULT 'Dhwajja On-Grid String Inverter',
  `inverter_capacity_kw` decimal(5,2) NOT NULL DEFAULT 3.00,
  `structure_type` varchar(100) NOT NULL DEFAULT 'Elevated GI Pre-Galvanized Structure',
  `total_cost` decimal(12,2) NOT NULL,
  `central_subsidy` decimal(10,2) NOT NULL DEFAULT 78000.00,
  `state_subsidy` decimal(10,2) NOT NULL DEFAULT 0.00,
  `customer_net_cost` decimal(12,2) NOT NULL,
  `estimated_monthly_savings` decimal(10,2) NOT NULL DEFAULT 2400.00,
  `loan_amount` decimal(12,2) DEFAULT NULL,
  `emi_estimate` decimal(10,2) DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `valid_until` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotation_number` (`quotation_number`),
  KEY `customer_id` (`customer_id`),
  KEY `lead_id` (`lead_id`),
  CONSTRAINT `quotations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quotations_ibfk_2` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `role_permissions`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `permission_id` (`permission_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `roles`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `settings`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `setting_key` varchar(80) NOT NULL,
  `setting_value` text NOT NULL,
  `data_type` varchar(30) NOT NULL DEFAULT 'string',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=333 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `subsidies`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `subsidies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lead_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `national_portal_app_no` varchar(50) DEFAULT NULL,
  `subsidy_slab_kw` decimal(5,2) NOT NULL,
  `eligible_subsidy_amount` decimal(10,2) NOT NULL DEFAULT 78000.00,
  `application_date` date DEFAULT NULL,
  `status` enum('Not Applied','Application Prepared','Applied','Under Processing','Approved','Rejected','Subsidy Received') DEFAULT 'Not Applied',
  `sanctioned_date` date DEFAULT NULL,
  `disbursed_date` date DEFAULT NULL,
  `bank_utr_no` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `lead_id` (`lead_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `subsidies_ibfk_1` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subsidies_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `users`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL DEFAULT 'CUSTOMER',
  `email` varchar(150) DEFAULT NULL,
  `mobile` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `designation` varchar(100) DEFAULT 'Back Office Executive',
  `jurisdiction` varchar(255) DEFAULT 'Odisha Operations',
  `blood_group` varchar(10) DEFAULT 'O+ve',
  `photo_url` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile` (`mobile`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `employee_code` (`employee_code`),
  KEY `idx_user_role` (`role`),
  KEY `idx_user_mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `wallet_transactions`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wallet_transactions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `advisor_id` int(10) unsigned DEFAULT NULL,
  `txn_type` varchar(50) NOT NULL DEFAULT 'COMMISSION',
  `type` varchar(50) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` int(10) unsigned DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `wallet_id` (`wallet_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `wallet_transactions_ibfk_1` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wallet_transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `wallets`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `wallets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `advisor_id` int(10) unsigned DEFAULT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `available_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `pending_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `pending_clearance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_earned` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_withdrawn` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- Table structure for table `withdrawal_requests`
-- ---------------------------------------------------------
CREATE TABLE IF NOT EXISTS `withdrawal_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_code` varchar(50) NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `advisor_id` int(10) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `tds_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `net_payable` decimal(12,2) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_branch` varchar(100) DEFAULT NULL,
  `account_holder` varchar(150) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `status` enum('PENDING','APPROVED','PAID','REJECTED') NOT NULL DEFAULT 'PENDING',
  `admin_remarks` text DEFAULT NULL,
  `processed_by_user_id` int(10) unsigned DEFAULT NULL,
  `utr_number` varchar(100) DEFAULT NULL,
  `requested_at` datetime NOT NULL DEFAULT current_timestamp(),
  `processed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_code` (`request_code`),
  KEY `idx_wr_user` (`user_id`),
  KEY `idx_wr_advisor` (`advisor_id`),
  KEY `idx_wr_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================
-- Ensure Missing Columns on Pre-existing Tables (Safe ALTER)
-- =========================================================

-- Customers table columns
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `father_husband_name` VARCHAR(150) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `profile_photo` VARCHAR(255) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `consumer_number` VARCHAR(50) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `pm_surya_ghar_id` VARCHAR(100) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `notification_number` VARCHAR(100) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `customer_signature` MEDIUMTEXT NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `agreement_accepted` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `agreement_accepted_at` DATETIME NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `city` VARCHAR(100) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `district` VARCHAR(80) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `state` VARCHAR(50) DEFAULT 'Odisha';
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `pincode` VARCHAR(10) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `sanctioned_load_kw` DECIMAL(5,2) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `proposed_system_capacity_kw` DECIMAL(5,2) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `total_project_cost` DECIMAL(12,2) NULL;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `central_subsidy` DECIMAL(10,2) NULL DEFAULT 78000.00;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `state_subsidy` DECIMAL(10,2) NULL DEFAULT 60000.00;
ALTER TABLE `customers` ADD COLUMN IF NOT EXISTS `customer_share` DECIMAL(12,2) NULL;

-- Package dispatches table columns
ALTER TABLE `package_dispatches` ADD COLUMN IF NOT EXISTS `engineer_id` INT UNSIGNED NULL;
ALTER TABLE `package_dispatches` ADD COLUMN IF NOT EXISTS `customer_acknowledged` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `package_dispatches` ADD COLUMN IF NOT EXISTS `customer_acknowledged_at` DATETIME NULL;
ALTER TABLE `package_dispatches` ADD COLUMN IF NOT EXISTS `customer_acknowledgment_notes` TEXT NULL;
ALTER TABLE `package_dispatches` ADD COLUMN IF NOT EXISTS `items_included` TEXT NULL;
ALTER TABLE `package_dispatches` ADD COLUMN IF NOT EXISTS `delivery_address` TEXT NULL;
ALTER TABLE `package_dispatches` ADD COLUMN IF NOT EXISTS `remarks` TEXT NULL;

-- Advisors table columns
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `free_registration` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `id_card_generated` TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `profile_photo` VARCHAR(255) NULL;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `aadhaar_number` VARCHAR(30) NULL;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `pan_number` VARCHAR(30) NULL;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `bank_name` VARCHAR(100) NULL;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `account_number` VARCHAR(50) NULL;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `ifsc_code` VARCHAR(20) NULL;
ALTER TABLE `advisors` ADD COLUMN IF NOT EXISTS `upi_id` VARCHAR(100) NULL;

-- Leads table columns
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `consumer_number` VARCHAR(50) NULL;
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `father_husband_name` VARCHAR(150) NULL;
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `sanctioned_load_kw` DECIMAL(5,2) NULL;
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `proposed_capacity_kw` DECIMAL(5,2) NULL;
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `estimated_project_cost` DECIMAL(12,2) NULL;
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `central_subsidy` DECIMAL(10,2) NULL DEFAULT 78000.00;
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `state_subsidy` DECIMAL(10,2) NULL DEFAULT 60000.00;
ALTER TABLE `leads` ADD COLUMN IF NOT EXISTS `net_cost` DECIMAL(12,2) NULL;

-- =========================================================
-- Default Seed Data (DISCOM Providers & Districts)
-- =========================================================

INSERT IGNORE INTO `discom_providers` (`id`, `code`, `name`, `full_name`, `headquarters`, `portal_url`, `is_active`) VALUES
(1, 'TPCODL', 'TP Central Odisha Distribution Ltd', 'TP Central Odisha Distribution Limited (TPCODL)', 'Bhubaneswar, Odisha', 'https://www.tpcentralodisha.com', 1),
(2, 'TPNODL', 'TP Northern Odisha Distribution Ltd', 'TP Northern Odisha Distribution Limited (TPNODL)', 'Balasore, Odisha', 'https://www.tpnodl.com', 1),
(3, 'TPSODL', 'TP Southern Odisha Distribution Ltd', 'TP Southern Odisha Distribution Limited (TPSODL)', 'Berhampur, Odisha', 'https://www.tpsouthodisha.com', 1),
(4, 'TPWODL', 'TP Western Odisha Distribution Ltd', 'TP Western Odisha Distribution Limited (TPWODL)', 'Burla, Sambalpur, Odisha', 'https://www.tpwesternodisha.com', 1);

INSERT IGNORE INTO `district_discoms` (`district`, `discom_id`, `discom_code`) VALUES
('Angul', 1, 'TPCODL'),
('Balasore', 2, 'TPNODL'),
('Baleswar', 2, 'TPNODL'),
('Bargarh', 4, 'TPWODL'),
('Bhadrak', 2, 'TPNODL'),
('Bolangir', 4, 'TPWODL'),
('Balangir', 4, 'TPWODL'),
('Boudh', 3, 'TPSODL'),
('Cuttack', 1, 'TPCODL'),
('Deogarh', 4, 'TPWODL'),
('Debagarh', 4, 'TPWODL'),
('Dhenkanal', 1, 'TPCODL'),
('Gajapati', 3, 'TPSODL'),
('Ganjam', 3, 'TPSODL'),
('Jagatsinghapur', 1, 'TPCODL'),
('Jagatsinghpur', 1, 'TPCODL'),
('Jajpur', 2, 'TPNODL'),
('Jharsuguda', 4, 'TPWODL'),
('Kalahandi', 4, 'TPWODL'),
('Kandhamal', 3, 'TPSODL'),
('Kendrapara', 1, 'TPCODL'),
('Kendujhar', 2, 'TPNODL'),
('Keonjhar', 2, 'TPNODL'),
('Khordha', 1, 'TPCODL'),
('Khurda', 1, 'TPCODL'),
('Koraput', 3, 'TPSODL'),
('Malkangiri', 3, 'TPSODL'),
('Mayurbhanj', 2, 'TPNODL'),
('Nabarangpur', 3, 'TPSODL'),
('Nayagarh', 1, 'TPCODL'),
('Nuapada', 4, 'TPWODL'),
('Puri', 1, 'TPCODL'),
('Rayagada', 3, 'TPSODL'),
('Sambalpur', 4, 'TPWODL'),
('Subarnapur', 4, 'TPWODL'),
('Sonepur', 4, 'TPWODL'),
('Sundargarh', 4, 'TPWODL');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
