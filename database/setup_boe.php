<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Migration Script for BOE Role, Customer Assignments & Audit History
 */

require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getInstance();

    echo "Running BOE Schema Migration...\n";

    // 1. Add employee_code & designation to users if not present
    try {
        if (!Database::columnExists('users', 'employee_code')) {
            $db->exec("ALTER TABLE users ADD COLUMN employee_code VARCHAR(50) NULL UNIQUE AFTER full_name;");
            echo " -> Added employee_code column to users table.\n";
        }
    } catch (\Throwable $t) {}

    try {
        if (!Database::columnExists('users', 'designation')) {
            $db->exec("ALTER TABLE users ADD COLUMN designation VARCHAR(100) NULL DEFAULT 'Back Office Executive' AFTER employee_code;");
            echo " -> Added designation column to users table.\n";
        }
    } catch (\Throwable $t) {}

    // 2. Add assigned_boe_id to customers if not present
    try {
        if (!Database::columnExists('customers', 'assigned_boe_id')) {
            $db->exec("ALTER TABLE customers ADD COLUMN assigned_boe_id INT UNSIGNED NULL AFTER converted_advisor_id;");
            try {
                $db->exec("ALTER TABLE customers ADD CONSTRAINT fk_cus_assigned_boe FOREIGN KEY (assigned_boe_id) REFERENCES users(id) ON DELETE SET NULL;");
            } catch (\Throwable $t) {}
            echo " -> Added assigned_boe_id column to customers table.\n";
        }
    } catch (\Throwable $t) {}

    // 3. Create customer_status_history table if not present
    if (!Database::tableExists('customer_status_history')) {
        $sql = "CREATE TABLE IF NOT EXISTS `customer_status_history` (
          `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          `customer_id` INT UNSIGNED NOT NULL,
          `lead_id` INT UNSIGNED NULL,
          `from_stage` VARCHAR(50) NULL,
          `to_stage` VARCHAR(50) NULL,
          `from_status` VARCHAR(50) NULL,
          `to_status` VARCHAR(50) NULL,
          `changed_by_user_id` INT UNSIGNED NULL,
          `user_code` VARCHAR(50) NULL,
          `user_name` VARCHAR(150) NULL,
          `user_designation` VARCHAR(100) NULL,
          `remarks` TEXT NULL,
          `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
          FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
          FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
          INDEX `idx_csh_customer` (`customer_id`),
          INDEX `idx_csh_user` (`changed_by_user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $db->exec($sql);
        echo " -> Created customer_status_history table.\n";
    }

    // 4. Seed demo BOE Accounts if none exist
    $existingBoe = Database::fetchOne("SELECT id FROM users WHERE role = 'BOE'");
    if (!$existingBoe) {
        $passHash = password_hash('Password@123', PASSWORD_BCRYPT);
        
        $stmt = $db->prepare("INSERT INTO users (role, email, mobile, password_hash, full_name, employee_code, designation, is_active, created_at) VALUES (:role, :email, :mobile, :pass, :name, :code, :desg, 1, NOW())");
        
        $stmt->execute([
            ':role' => 'BOE',
            ':email' => 'boe1@suryavistaara.com',
            ':mobile' => '9861000111',
            ':pass' => $passHash,
            ':name' => 'Rajesh Kumar Swain',
            ':code' => 'SVPL-BOE-101',
            ':desg' => 'Senior Back Office Executive'
        ]);

        $stmt->execute([
            ':role' => 'BOE',
            ':email' => 'boe2@suryavistaara.com',
            ':mobile' => '9861000222',
            ':pass' => $passHash,
            ':name' => 'Priya Darshini Panda',
            ':code' => 'SVPL-BOE-102',
            ':desg' => 'Back Office Executive'
        ]);

        echo " -> Seeded 2 demo Back Office Executive (BOE) accounts:\n";
        echo "    1) boe1@suryavistaara.com / 9861000111 (Pass: Password@123)\n";
        echo "    2) boe2@suryavistaara.com / 9861000222 (Pass: Password@123)\n";
    }

    echo "BOE Migration finished successfully!\n";
} catch (\Throwable $e) {
    echo "BOE Migration Error: " . $e->getMessage() . "\n";
}
