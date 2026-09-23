<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Comprehensive Commission Engine, Dual-Tree Network, Pool Bonus & Ledger Migration
 */

require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

echo "=== Running Commission Engine Database Migration ===\n";

$db = Database::getInstance();
$driver = Database::getDriver();

try {
    // Drop old empty/unaligned commission_rule_levels and commission_rules if needed or ensure new columns
    if ($driver !== 'sqlite') {
        $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
        $db->exec("DROP TABLE IF EXISTS `commission_rule_levels`;");
        $db->exec("DROP TABLE IF EXISTS `commission_rules`;");
        $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    } else {
        $db->exec("DROP TABLE IF EXISTS commission_rule_levels;");
        $db->exec("DROP TABLE IF EXISTS commission_rules;");
    }

    // 1. Commission Rules (Product / System / General)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS commission_rules (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            rule_code TEXT UNIQUE,
            rule_name TEXT NOT NULL,
            rule_type TEXT DEFAULT 'PRODUCT_REFERRAL',
            product_name TEXT NULL,
            capacity_kw REAL NULL,
            connection_type TEXT NULL,
            direct_commission REAL DEFAULT 0.00,
            version INTEGER DEFAULT 1,
            is_active INTEGER DEFAULT 1,
            effective_from TEXT NULL,
            effective_to TEXT NULL,
            notes TEXT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `commission_rules` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `rule_code` VARCHAR(60) UNIQUE,
            `rule_name` VARCHAR(150) NOT NULL,
            `rule_type` VARCHAR(40) DEFAULT 'PRODUCT_REFERRAL',
            `product_name` VARCHAR(100) NULL,
            `capacity_kw` DECIMAL(8,2) NULL,
            `connection_type` VARCHAR(50) NULL,
            `direct_commission` DECIMAL(15,2) DEFAULT 0.00,
            `version` INT UNSIGNED DEFAULT 1,
            `is_active` TINYINT(1) DEFAULT 1,
            `effective_from` DATE NULL,
            `effective_to` DATE NULL,
            `notes` TEXT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX `idx_rule_type` (`rule_type`),
            INDEX `idx_rule_active` (`is_active`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 2. Commission Rule Levels (Upline Level Amounts for each rule)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS commission_rule_levels (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            rule_id INTEGER NOT NULL,
            level INTEGER NOT NULL,
            commission_amount REAL DEFAULT 0.00,
            calculation_type TEXT DEFAULT 'FIXED',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (rule_id) REFERENCES commission_rules(id) ON DELETE CASCADE
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `commission_rule_levels` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `rule_id` INT UNSIGNED NOT NULL,
            `level` INT UNSIGNED NOT NULL,
            `commission_amount` DECIMAL(15,2) DEFAULT 0.00,
            `calculation_type` VARCHAR(20) DEFAULT 'FIXED',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`rule_id`) REFERENCES `commission_rules`(`id`) ON DELETE CASCADE,
            UNIQUE KEY `uk_rule_level` (`rule_id`, `level`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 3. Upline Qualification Matrix (Personal customer requirement mapping)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS commission_upline_qualifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            min_personal_customers INTEGER NOT NULL UNIQUE,
            max_eligible_level INTEGER NOT NULL,
            description TEXT NULL,
            is_active INTEGER DEFAULT 1,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `commission_upline_qualifications` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `min_personal_customers` INT UNSIGNED NOT NULL UNIQUE,
            `max_eligible_level` INT UNSIGNED NOT NULL,
            `description` VARCHAR(255) NULL,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 4. Customer Monthly Bonus Rules (Slabs)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS customer_monthly_bonus_rules (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            min_customers INTEGER NOT NULL,
            max_customers INTEGER NULL,
            bonus_amount REAL NOT NULL,
            calculation_mode TEXT DEFAULT 'HIGHEST_SLAB',
            is_active INTEGER DEFAULT 1,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `customer_monthly_bonus_rules` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `min_customers` INT UNSIGNED NOT NULL,
            `max_customers` INT UNSIGNED NULL,
            `bonus_amount` DECIMAL(15,2) NOT NULL,
            `calculation_mode` VARCHAR(30) DEFAULT 'HIGHEST_SLAB',
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 5. Pool Bonus Configuration Rule
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS pool_bonus_rules (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            min_direct_advisors INTEGER DEFAULT 3,
            min_personal_customers INTEGER DEFAULT 3,
            max_children_per_node INTEGER DEFAULT 3,
            max_pool_levels INTEGER DEFAULT 11,
            level_1_amount REAL DEFAULT 1000.00,
            subsequent_level_amount REAL DEFAULT 500.00,
            is_active INTEGER DEFAULT 1,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `pool_bonus_rules` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `min_direct_advisors` INT UNSIGNED DEFAULT 3,
            `min_personal_customers` INT UNSIGNED DEFAULT 3,
            `max_children_per_node` INT UNSIGNED DEFAULT 3,
            `max_pool_levels` INT UNSIGNED DEFAULT 11,
            `level_1_amount` DECIMAL(15,2) DEFAULT 1000.00,
            `subsequent_level_amount` DECIMAL(15,2) DEFAULT 500.00,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 6. Pool Members (Separate Pool Tree: PB1, PB2, PB3...)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS pool_members (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pool_number INTEGER UNIQUE NOT NULL,
            pool_label TEXT UNIQUE NOT NULL,
            advisor_id INTEGER NOT NULL UNIQUE,
            parent_pool_id INTEGER NULL,
            pool_level INTEGER DEFAULT 1,
            qualified_at TEXT DEFAULT CURRENT_TIMESTAMP,
            direct_pool_children_count INTEGER DEFAULT 0,
            status TEXT DEFAULT 'ACTIVE',
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (advisor_id) REFERENCES advisors(id)
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `pool_members` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `pool_number` INT UNSIGNED UNIQUE NOT NULL,
            `pool_label` VARCHAR(30) UNIQUE NOT NULL,
            `advisor_id` INT UNSIGNED NOT NULL UNIQUE,
            `parent_pool_id` INT UNSIGNED NULL,
            `pool_level` INT UNSIGNED DEFAULT 1,
            `qualified_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `direct_pool_children_count` INT UNSIGNED DEFAULT 0,
            `status` VARCHAR(20) DEFAULT 'ACTIVE',
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`advisor_id`) REFERENCES `advisors`(`id`),
            FOREIGN KEY (`parent_pool_id`) REFERENCES `pool_members`(`id`),
            INDEX `idx_pool_parent` (`parent_pool_id`),
            INDEX `idx_pool_number` (`pool_number`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 7. Pool Genealogy (Closure table for pool tree)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS pool_genealogy (
            ancestor_pool_id INTEGER NOT NULL,
            descendant_pool_id INTEGER NOT NULL,
            depth INTEGER NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (ancestor_pool_id, descendant_pool_id),
            FOREIGN KEY (ancestor_pool_id) REFERENCES pool_members(id),
            FOREIGN KEY (descendant_pool_id) REFERENCES pool_members(id)
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `pool_genealogy` (
            `ancestor_pool_id` INT UNSIGNED NOT NULL,
            `descendant_pool_id` INT UNSIGNED NOT NULL,
            `depth` INT UNSIGNED NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`ancestor_pool_id`, `descendant_pool_id`),
            FOREIGN KEY (`ancestor_pool_id`) REFERENCES `pool_members`(`id`),
            FOREIGN KEY (`descendant_pool_id`) REFERENCES `pool_members`(`id`),
            INDEX `idx_pool_depth` (`depth`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 8. Advisor Rewards Slabs (10 cust -> ₹5k, 50 -> ₹30k, etc.)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS advisor_rewards (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            reward_name TEXT NOT NULL,
            customer_target INTEGER NOT NULL UNIQUE,
            per_customer_amount REAL NOT NULL,
            total_reward_value REAL NOT NULL,
            reward_type TEXT DEFAULT 'CASH_OR_PRODUCT',
            eligibility_type TEXT DEFAULT 'LIFETIME',
            is_repeatable INTEGER DEFAULT 0,
            is_active INTEGER DEFAULT 1,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `advisor_rewards` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `reward_name` VARCHAR(150) NOT NULL,
            `customer_target` INT UNSIGNED NOT NULL UNIQUE,
            `per_customer_amount` DECIMAL(15,2) NOT NULL,
            `total_reward_value` DECIMAL(15,2) NOT NULL,
            `reward_type` VARCHAR(50) DEFAULT 'CASH_OR_PRODUCT',
            `eligibility_type` VARCHAR(30) DEFAULT 'LIFETIME',
            `is_repeatable` TINYINT(1) DEFAULT 0,
            `is_active` TINYINT(1) DEFAULT 1,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 9. Advisor Reward Claims
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS advisor_reward_claims (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            advisor_id INTEGER NOT NULL,
            reward_id INTEGER NOT NULL,
            customer_count_snapshot INTEGER NOT NULL,
            claim_type TEXT DEFAULT 'CASH',
            status TEXT DEFAULT 'ACHIEVED',
            approved_by INTEGER NULL,
            approved_at TEXT NULL,
            delivery_date TEXT NULL,
            delivery_ref TEXT NULL,
            remarks TEXT NULL,
            wallet_transaction_id INTEGER NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (advisor_id) REFERENCES advisors(id),
            FOREIGN KEY (reward_id) REFERENCES advisor_rewards(id)
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `advisor_reward_claims` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `advisor_id` INT UNSIGNED NOT NULL,
            `reward_id` INT UNSIGNED NOT NULL,
            `customer_count_snapshot` INT UNSIGNED NOT NULL,
            `claim_type` VARCHAR(30) DEFAULT 'CASH',
            `status` VARCHAR(30) DEFAULT 'ACHIEVED',
            `approved_by` INT UNSIGNED NULL,
            `approved_at` DATETIME NULL,
            `delivery_date` DATE NULL,
            `delivery_ref` VARCHAR(100) NULL,
            `remarks` TEXT NULL,
            `wallet_transaction_id` INT UNSIGNED NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`advisor_id`) REFERENCES `advisors`(`id`),
            FOREIGN KEY (`reward_id`) REFERENCES `advisor_rewards`(`id`),
            INDEX `idx_reward_advisor` (`advisor_id`),
            INDEX `idx_reward_status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 10. Customer Special Bonus Rules (Declared monthly by Admin)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS customer_special_bonus_rules (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            bonus_month INTEGER NOT NULL,
            bonus_year INTEGER NOT NULL,
            bonus_amount REAL NOT NULL,
            max_allowed_amount REAL DEFAULT 85000.00,
            eligibility_conditions TEXT NULL,
            is_active INTEGER DEFAULT 1,
            declared_by INTEGER NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(bonus_month, bonus_year)
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `customer_special_bonus_rules` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `bonus_month` TINYINT UNSIGNED NOT NULL,
            `bonus_year` SMALLINT UNSIGNED NOT NULL,
            `bonus_amount` DECIMAL(15,2) NOT NULL,
            `max_allowed_amount` DECIMAL(15,2) DEFAULT 85000.00,
            `eligibility_conditions` TEXT NULL,
            `is_active` TINYINT(1) DEFAULT 1,
            `declared_by` INT UNSIGNED NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY `uk_month_year` (`bonus_month`, `bonus_year`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 11. Enhanced Commission Transactions Table
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS commission_transactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            transaction_code TEXT UNIQUE NOT NULL,
            customer_id INTEGER NULL,
            payment_id INTEGER NULL,
            advisor_id INTEGER NOT NULL,
            source_advisor_id INTEGER NULL,
            level INTEGER DEFAULT 1,
            commission_type TEXT NOT NULL,
            rule_id INTEGER NULL,
            rule_version INTEGER DEFAULT 1,
            product_name TEXT NULL,
            payment_amount REAL DEFAULT 0.00,
            company_credit_date TEXT NULL,
            commission_month INTEGER NOT NULL,
            commission_year INTEGER NOT NULL,
            gross_amount REAL NOT NULL,
            tds_deducted REAL DEFAULT 0.00,
            admin_deducted REAL DEFAULT 0.00,
            net_amount REAL NOT NULL,
            qualification_status TEXT DEFAULT 'ELIGIBLE',
            qualification_notes TEXT NULL,
            rule_snapshot_json TEXT NULL,
            status TEXT DEFAULT 'PENDING',
            approved_by INTEGER NULL,
            approved_at TEXT NULL,
            is_reversal INTEGER DEFAULT 0,
            parent_transaction_id INTEGER NULL,
            reversal_reason TEXT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (advisor_id) REFERENCES advisors(id)
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `commission_transactions` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `transaction_code` VARCHAR(60) UNIQUE NOT NULL,
            `customer_id` INT UNSIGNED NULL,
            `payment_id` INT UNSIGNED NULL,
            `advisor_id` INT UNSIGNED NOT NULL,
            `source_advisor_id` INT UNSIGNED NULL,
            `level` INT UNSIGNED DEFAULT 1,
            `commission_type` VARCHAR(50) NOT NULL,
            `rule_id` INT UNSIGNED NULL,
            `rule_version` INT UNSIGNED DEFAULT 1,
            `product_name` VARCHAR(100) NULL,
            `payment_amount` DECIMAL(15,2) DEFAULT 0.00,
            `company_credit_date` DATE NULL,
            `commission_month` TINYINT UNSIGNED NOT NULL,
            `commission_year` SMALLINT UNSIGNED NOT NULL,
            `gross_amount` DECIMAL(15,2) NOT NULL,
            `tds_deducted` DECIMAL(15,2) DEFAULT 0.00,
            `admin_deducted` DECIMAL(15,2) DEFAULT 0.00,
            `net_amount` DECIMAL(15,2) NOT NULL,
            `qualification_status` VARCHAR(30) DEFAULT 'ELIGIBLE',
            `qualification_notes` TEXT NULL,
            `rule_snapshot_json` LONGTEXT NULL,
            `status` VARCHAR(30) DEFAULT 'PENDING',
            `approved_by` INT UNSIGNED NULL,
            `approved_at` DATETIME NULL,
            `is_reversal` TINYINT(1) DEFAULT 0,
            `parent_transaction_id` INT UNSIGNED NULL,
            `reversal_reason` TEXT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`advisor_id`) REFERENCES `advisors`(`id`),
            INDEX `idx_comm_status` (`status`),
            INDEX `idx_comm_advisor` (`advisor_id`),
            INDEX `idx_comm_payment` (`payment_id`),
            INDEX `idx_comm_period` (`commission_year`, `commission_month`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 12. Commission Cycles Table (Monthly summary & locking)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS commission_cycles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            cycle_month INTEGER NOT NULL,
            cycle_year INTEGER NOT NULL,
            total_business_amount REAL DEFAULT 0.00,
            total_commissions REAL DEFAULT 0.00,
            total_bonus REAL DEFAULT 0.00,
            total_pool REAL DEFAULT 0.00,
            total_rewards REAL DEFAULT 0.00,
            total_payable REAL DEFAULT 0.00,
            is_locked INTEGER DEFAULT 0,
            locked_by INTEGER NULL,
            locked_at TEXT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(cycle_month, cycle_year)
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `commission_cycles` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `cycle_month` TINYINT UNSIGNED NOT NULL,
            `cycle_year` SMALLINT UNSIGNED NOT NULL,
            `total_business_amount` DECIMAL(15,2) DEFAULT 0.00,
            `total_commissions` DECIMAL(15,2) DEFAULT 0.00,
            `total_bonus` DECIMAL(15,2) DEFAULT 0.00,
            `total_pool` DECIMAL(15,2) DEFAULT 0.00,
            `total_rewards` DECIMAL(15,2) DEFAULT 0.00,
            `total_payable` DECIMAL(15,2) DEFAULT 0.00,
            `is_locked` TINYINT(1) DEFAULT 0,
            `locked_by` INT UNSIGNED NULL,
            `locked_at` DATETIME NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY `uk_cycle_period` (`cycle_month`, `cycle_year`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 13. Advisor Wallet Ledger Transactions (Double-entry transaction safety)
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS advisor_wallet_transactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            transaction_ref TEXT UNIQUE NOT NULL,
            advisor_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            commission_id INTEGER NULL,
            transaction_type TEXT NOT NULL,
            credit_amount REAL DEFAULT 0.00,
            debit_amount REAL DEFAULT 0.00,
            balance_after REAL NOT NULL,
            description TEXT NOT NULL,
            reference_no TEXT NULL,
            created_by INTEGER NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (advisor_id) REFERENCES advisors(id)
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `advisor_wallet_transactions` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `transaction_ref` VARCHAR(60) UNIQUE NOT NULL,
            `advisor_id` INT UNSIGNED NOT NULL,
            `user_id` INT UNSIGNED NOT NULL,
            `commission_id` INT UNSIGNED NULL,
            `transaction_type` VARCHAR(50) NOT NULL,
            `credit_amount` DECIMAL(15,2) DEFAULT 0.00,
            `debit_amount` DECIMAL(15,2) DEFAULT 0.00,
            `balance_after` DECIMAL(15,2) NOT NULL,
            `description` VARCHAR(255) NOT NULL,
            `reference_no` VARCHAR(100) NULL,
            `created_by` INT UNSIGNED NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`advisor_id`) REFERENCES `advisors`(`id`),
            INDEX `idx_txn_advisor` (`advisor_id`),
            INDEX `idx_txn_type` (`transaction_type`),
            INDEX `idx_txn_comm` (`commission_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    // 14. Commission Audit Logs Table
    if ($driver === 'sqlite') {
        $db->exec("CREATE TABLE IF NOT EXISTS commission_audit_logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NULL,
            user_role TEXT NULL,
            action TEXT NOT NULL,
            entity_type TEXT NOT NULL,
            entity_id INTEGER NULL,
            old_value TEXT NULL,
            new_value TEXT NULL,
            reason TEXT NULL,
            ip_address TEXT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    } else {
        $db->exec("CREATE TABLE IF NOT EXISTS `commission_audit_logs` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT UNSIGNED NULL,
            `user_role` VARCHAR(50) NULL,
            `action` VARCHAR(100) NOT NULL,
            `entity_type` VARCHAR(60) NOT NULL,
            `entity_id` INT UNSIGNED NULL,
            `old_value` LONGTEXT NULL,
            `new_value` LONGTEXT NULL,
            `reason` TEXT NULL,
            `ip_address` VARCHAR(45) NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            INDEX `idx_audit_action` (`action`),
            INDEX `idx_audit_entity` (`entity_type`, `entity_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }

    echo "[✓] Tables created successfully.\n";

    // ------------------------------------------------------------------
    // SEED INITIAL DEFAULT CONFIGURATIONS (As strictly specified)
    // ------------------------------------------------------------------

    // A. Seed Product Rules
    $productRules = [
        [
            'code' => 'PROD_3KW_ONGRID',
            'name' => '3 kW On-Grid Solar System',
            'capacity' => 3.0,
            'type' => 'On-Grid',
            'direct' => 10000.00,
            'l2' => 1000.00,
            'l3_9' => 500.00,
        ],
        [
            'code' => 'PROD_3KW_HYBRID',
            'name' => '3 kW Hybrid Solar System',
            'capacity' => 3.0,
            'type' => 'Hybrid',
            'direct' => 15000.00,
            'l2' => 1000.00,
            'l3_9' => 500.00,
        ],
        [
            'code' => 'PROD_5KW_ONGRID',
            'name' => '5 kW On-Grid Solar System',
            'capacity' => 5.0,
            'type' => 'On-Grid',
            'direct' => 18000.00,
            'l2' => 1000.00,
            'l3_9' => 500.00,
        ],
        [
            'code' => 'PROD_DEFAULT',
            'name' => 'General Default Solar System',
            'capacity' => 0.0,
            'type' => 'Default',
            'direct' => 10000.00,
            'l2' => 1000.00,
            'l3_9' => 500.00,
        ]
    ];

    foreach ($productRules as $pr) {
        $existing = Database::fetchOne("SELECT id FROM commission_rules WHERE rule_code = ?", [$pr['code']]);
        if (!$existing) {
            Database::execute(
                "INSERT INTO commission_rules (rule_code, rule_name, rule_type, product_name, capacity_kw, connection_type, direct_commission, version, is_active, effective_from) 
                 VALUES (?, ?, 'PRODUCT_REFERRAL', ?, ?, ?, ?, 1, 1, ?)",
                [$pr['code'], $pr['name'], $pr['name'], $pr['capacity'], $pr['type'], $pr['direct'], date('Y-m-d')]
            );
            $ruleId = (int)Database::lastInsertId();

            // Insert Levels (L1 = direct, L2 = 1000, L3..9 = 500)
            Database::execute(
                "INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, 1, ?, 'FIXED')",
                [$ruleId, $pr['direct']]
            );
            Database::execute(
                "INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, 2, ?, 'FIXED')",
                [$ruleId, $pr['l2']]
            );
            for ($lvl = 3; $lvl <= 9; $lvl++) {
                Database::execute(
                    "INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, ?, ?, 'FIXED')",
                    [$ruleId, $lvl, $pr['l3_9']]
                );
            }
        }
    }
    echo "[✓] Product commission rules seeded.\n";

    // B. Seed Upline Qualification Matrix
    $matrix = [
        [0, 0, 'No personal customers: 0 upline commission levels'],
        [1, 1, '1 personal customer: Up to Level 1 upline commission'],
        [2, 2, '2 personal customers: Up to Level 2 upline commission'],
        [3, 9, '3 or more personal customers: Up to Level 9 upline commission'],
    ];

    foreach ($matrix as $m) {
        $exists = Database::fetchOne("SELECT id FROM commission_upline_qualifications WHERE min_personal_customers = ?", [$m[0]]);
        if (!$exists) {
            Database::execute(
                "INSERT INTO commission_upline_qualifications (min_personal_customers, max_eligible_level, description, is_active) VALUES (?, ?, ?, 1)",
                [$m[0], $m[1], $m[2]]
            );
        }
    }
    echo "[✓] Upline qualification matrix seeded.\n";

    // C. Seed Monthly Customer Bonus Rules
    $bonusSlabs = [
        [5, 9, 3000.00, 'HIGHEST_SLAB'],
        [10, 19, 10000.00, 'HIGHEST_SLAB'],
        [20, null, 30000.00, 'HIGHEST_SLAB'],
    ];

    foreach ($bonusSlabs as $bs) {
        $exists = Database::fetchOne("SELECT id FROM customer_monthly_bonus_rules WHERE min_customers = ?", [$bs[0]]);
        if (!$exists) {
            Database::execute(
                "INSERT INTO customer_monthly_bonus_rules (min_customers, max_customers, bonus_amount, calculation_mode, is_active) VALUES (?, ?, ?, ?, 1)",
                [$bs[0], $bs[1], $bs[2], $bs[3]]
            );
        }
    }
    echo "[✓] Customer monthly special bonus slabs seeded.\n";

    // D. Seed Pool Bonus Rule
    $poolRuleExists = Database::fetchOne("SELECT id FROM pool_bonus_rules LIMIT 1");
    if (!$poolRuleExists) {
        Database::execute(
            "INSERT INTO pool_bonus_rules (min_direct_advisors, min_personal_customers, max_children_per_node, max_pool_levels, level_1_amount, subsequent_level_amount, is_active) 
             VALUES (3, 3, 3, 11, 1000.00, 500.00, 1)"
        );
    }
    echo "[✓] Pool bonus rule seeded.\n";

    // E. Seed Advisor Rewards Slabs
    $rewardSlabs = [
        ['10 Customers Achievement', 10, 500.00, 5000.00, 'CASH_OR_PRODUCT'],
        ['50 Customers Achievement', 50, 600.00, 30000.00, 'CASH_OR_PRODUCT'],
        ['100 Customers Achievement', 100, 700.00, 70000.00, 'CASH_OR_PRODUCT'],
        ['300 Customers Achievement', 300, 800.00, 240000.00, 'CASH_OR_PRODUCT'],
        ['500 Customers Achievement', 500, 900.00, 450000.00, 'CASH_OR_PRODUCT'],
        ['1,000 Customers Milestone', 1000, 1000.00, 1000000.00, 'CASH_OR_VEHICLE'],
    ];

    foreach ($rewardSlabs as $rs) {
        $exists = Database::fetchOne("SELECT id FROM advisor_rewards WHERE customer_target = ?", [$rs[1]]);
        if (!$exists) {
            Database::execute(
                "INSERT INTO advisor_rewards (reward_name, customer_target, per_customer_amount, total_reward_value, reward_type, eligibility_type, is_repeatable, is_active) 
                 VALUES (?, ?, ?, ?, ?, 'LIFETIME', 0, 1)",
                [$rs[0], $rs[1], $rs[2], $rs[3], $rs[4]]
            );
        }
    }
    echo "[✓] Advisor lifetime reward slabs seeded.\n";

    // F. Seed Current Month Customer Special Bonus Default
    $currentMonth = (int)date('n');
    $currentYear = (int)date('Y');
    $custBonusExists = Database::fetchOne("SELECT id FROM customer_special_bonus_rules WHERE bonus_month = ? AND bonus_year = ?", [$currentMonth, $currentYear]);
    if (!$custBonusExists) {
        Database::execute(
            "INSERT INTO customer_special_bonus_rules (bonus_month, bonus_year, bonus_amount, max_allowed_amount, eligibility_conditions, is_active) 
             VALUES (?, ?, 50000.00, 85000.00, 'Applicable on verified company credit date within the calendar month', 1)",
            [$currentMonth, $currentYear]
        );
    }
    echo "[✓] Customer special monthly bonus initialized.\n";

    // G. System Settings for Joining Fee & Trigger
    require_once __DIR__ . '/../app/Models/Setting.php';
    $settingsDefaults = [
        ['advisor_joining_fee', '2700.00', 'Advisor Registration Joining Fee'],
        ['advisor_direct_joining_commission', '700.00', 'Direct Sponsor Joining Commission'],
        ['advisor_upline_joining_commission', '0.00', 'Upline Joining Commission (Level 2 to 9)'],
        ['joining_commission_enabled', '1', 'Enable direct joining commission'],
        ['commission_trigger_event', 'COMPANY_CREDIT', 'Commission trigger: COMPANY_CREDIT, LOAN_SANCTION, INSTALLATION'],
        ['commission_approval_mode', 'MANUAL', 'Commission approval: MANUAL or AUTO'],
        ['monthly_bonus_calculation_mode', 'HIGHEST_SLAB', 'HIGHEST_SLAB or CUMULATIVE'],
        ['tds_percentage', '5.00', 'TDS Deduction %'],
        ['admin_deduction_percentage', '0.00', 'Administrative Deduction %'],
        ['min_wallet_withdrawal', '500.00', 'Minimum Wallet Withdrawal Amount'],
    ];
    foreach ($settingsDefaults as $sd) {
        if (\App\Models\Setting::get($sd[0]) === null) {
            \App\Models\Setting::set($sd[0], $sd[1], 'commission');
        }
    }
    echo "[✓] System settings seeded.\n";

    echo "=== Commission Engine Database Migration Complete Successfully! ===\n";

} catch (\Throwable $e) {
    echo "ERROR during migration: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
