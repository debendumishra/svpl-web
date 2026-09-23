<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Self-Healing Database Master Auto-Migrator
 * Safely creates all missing tables, adds missing columns, and seeds reference data without touching existing rows.
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

            // 1. Run Complete Master Schema Patch if available
            $masterPatchFile = __DIR__ . '/complete_master_schema_patch.sql';
            if (file_exists($masterPatchFile)) {
                $sqlContent = file_get_contents($masterPatchFile);
                if (!empty($sqlContent)) {
                    // Split SQL by semicolons safely
                    $queries = preg_split('/;\s*[\r\n]+/', $sqlContent);
                    foreach ($queries as $query) {
                        $query = trim($query);
                        if (!empty($query) && strpos($query, '--') !== 0) {
                            try {
                                $db->exec($query);
                            } catch (\Throwable $qe) {
                                // Ignore already existing column/table notices
                            }
                        }
                    }
                    $messages[] = "Master schema patch executed successfully.";
                }
            }

            // 2. Extra Safety: Ensure Customers Missing Columns
            if (Database::tableExists('customers')) {
                $cols = Database::fetchAll("DESCRIBE `customers`");
                $colNames = array_column($cols, 'Field');

                $customerAdds = [
                    'father_husband_name' => "ALTER TABLE `customers` ADD COLUMN `father_husband_name` VARCHAR(150) NULL AFTER `last_name`",
                    'pm_surya_ghar_id' => "ALTER TABLE `customers` ADD COLUMN `pm_surya_ghar_id` VARCHAR(100) NULL AFTER `consumer_number`",
                    'notification_number' => "ALTER TABLE `customers` ADD COLUMN `notification_number` VARCHAR(100) NULL AFTER `pm_surya_ghar_id`",
                    'customer_signature' => "ALTER TABLE `customers` ADD COLUMN `customer_signature` MEDIUMTEXT NULL AFTER `profile_photo`",
                    'agreement_accepted' => "ALTER TABLE `customers` ADD COLUMN `agreement_accepted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `customer_signature`",
                    'agreement_accepted_at' => "ALTER TABLE `customers` ADD COLUMN `agreement_accepted_at` DATETIME NULL AFTER `agreement_accepted`",
                    'district' => "ALTER TABLE `customers` ADD COLUMN `district` VARCHAR(80) NULL AFTER `city`",
                    'pincode' => "ALTER TABLE `customers` ADD COLUMN `pincode` VARCHAR(10) NULL AFTER `district`"
                ];

                foreach ($customerAdds as $col => $sql) {
                    if (!in_array($col, $colNames)) {
                        try {
                            $db->exec($sql);
                            $messages[] = "Added column: customers.{$col}";
                        } catch (\Throwable $e) {}
                    }
                }
            }

            // 3. Extra Safety: Ensure Package Dispatches Missing Columns
            if (Database::tableExists('package_dispatches')) {
                $cols = Database::fetchAll("DESCRIBE `package_dispatches`");
                $colNames = array_column($cols, 'Field');

                $dispatchAdds = [
                    'engineer_id' => "ALTER TABLE `package_dispatches` ADD COLUMN `engineer_id` INT UNSIGNED NULL AFTER `advisor_id`",
                    'customer_acknowledged' => "ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledged` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`",
                    'customer_acknowledged_at' => "ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledged_at` DATETIME NULL AFTER `customer_acknowledged`",
                    'customer_acknowledgment_notes' => "ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledgment_notes` TEXT NULL AFTER `customer_acknowledged_at`"
                ];

                foreach ($dispatchAdds as $col => $sql) {
                    if (!in_array($col, $colNames)) {
                        try {
                            $db->exec($sql);
                            $messages[] = "Added column: package_dispatches.{$col}";
                        } catch (\Throwable $e) {}
                    }
                }
            }

            // 4. Extra Safety: Ensure Advisors Missing Columns
            if (Database::tableExists('advisors')) {
                $cols = Database::fetchAll("DESCRIBE `advisors`");
                $colNames = array_column($cols, 'Field');

                $advisorAdds = [
                    'free_registration' => "ALTER TABLE `advisors` ADD COLUMN `free_registration` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`",
                    'id_card_generated' => "ALTER TABLE `advisors` ADD COLUMN `id_card_generated` TINYINT(1) NOT NULL DEFAULT 0 AFTER `free_registration`"
                ];

                foreach ($advisorAdds as $col => $sql) {
                    if (!in_array($col, $colNames)) {
                        try {
                            $db->exec($sql);
                            $messages[] = "Added column: advisors.{$col}";
                        } catch (\Throwable $e) {}
                    }
                }
            }

            if ($driver === 'mysql') {
                $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
            }

            return ['success' => true, 'messages' => $messages];
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'messages' => $messages];
        }
    }
}
