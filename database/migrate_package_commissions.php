<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Migration: Link Solar Packages to Commission Rules Table
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getInstance();
    $driver = Database::getDriver();

    echo "Running Package Commission Rule Migration...\n";

    // 1. Ensure package_id column in commission_rules
    if ($driver !== 'sqlite') {
        $cols = Database::fetchAll("SHOW COLUMNS FROM `commission_rules` LIKE 'package_id'");
        if (empty($cols)) {
            $db->exec("ALTER TABLE `commission_rules` ADD COLUMN `package_id` INT UNSIGNED NULL AFTER `id`");
            echo "Added `package_id` to commission_rules (MySQL)\n";
        }
    } else {
        $cols = Database::fetchAll("PRAGMA table_info(commission_rules)");
        $hasCol = false;
        foreach ($cols as $c) {
            if ($c['name'] === 'package_id') {
                $hasCol = true;
                break;
            }
        }
        if (!$hasCol) {
            $db->exec("ALTER TABLE commission_rules ADD COLUMN package_id INTEGER NULL");
            echo "Added `package_id` to commission_rules (SQLite)\n";
        }
    }

    // 2. Fetch all packages from packages table
    $packages = Database::fetchAll("SELECT * FROM packages ORDER BY id ASC");
    echo "Found " . count($packages) . " packages in `packages` table.\n";

    foreach ($packages as $pkg) {
        $pkgId = (int)$pkg['id'];
        $pkgCode = $pkg['package_code'];
        $ruleCode = 'RULE_' . $pkgCode;
        $title = $pkg['title'];
        $brand = $pkg['brand'] ?? 'Solar';
        $cap = (float)($pkg['capacity_kw'] ?? 3.0);
        $sysType = $pkg['system_type'] ?? 'On-Grid';

        // Calculate default direct commission based on capacity & system type
        if ($cap <= 3.0) {
            $directComm = ($sysType === 'Hybrid') ? 15000.00 : 10000.00;
        } elseif ($cap <= 5.0) {
            $directComm = ($sysType === 'Hybrid') ? 20000.00 : 15000.00;
        } else {
            $directComm = ($sysType === 'Hybrid') ? 30000.00 : 25000.00;
        }

        // Check if rule already exists for this package_id or rule_code
        $existing = Database::fetchOne(
            "SELECT id FROM commission_rules WHERE package_id = ? OR rule_code = ?",
            [$pkgId, $ruleCode]
        );

        if (!$existing) {
            Database::execute(
                "INSERT INTO commission_rules (package_id, rule_code, rule_name, rule_type, product_name, capacity_kw, connection_type, direct_commission, version, is_active, effective_from) 
                 VALUES (?, ?, ?, 'PRODUCT_REFERRAL', ?, ?, ?, ?, 1, 1, ?)",
                [$pkgId, $ruleCode, $title, $title, $cap, $sysType, $directComm, date('Y-m-d')]
            );
            $ruleId = (int)Database::lastInsertId();

            // Insert Level Slabs (L1 to L9)
            Database::execute(
                "INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, 1, ?, 'FIXED')",
                [$ruleId, $directComm]
            );
            Database::execute(
                "INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, 2, 1000.00, 'FIXED')",
                [$ruleId]
            );
            for ($lvl = 3; $lvl <= 9; $lvl++) {
                Database::execute(
                    "INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, ?, 500.00, 'FIXED')",
                    [$ruleId, $lvl]
                );
            }
            echo "Created Rule for Package #{$pkgId}: {$title} (L1: ₹{$directComm})\n";
        } else {
            // Update package_id if not set
            Database::execute(
                "UPDATE commission_rules SET package_id = ?, product_name = ?, capacity_kw = ?, connection_type = ? WHERE id = ?",
                [$pkgId, $title, $cap, $sysType, $existing['id']]
            );
            echo "Updated existing Rule #{$existing['id']} for Package #{$pkgId}: {$title}\n";
        }
    }

    echo "Migration completed successfully!\n";

} catch (\Throwable $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
