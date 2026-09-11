<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Setup Migration for Bank Withdrawal Requests
 */

require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getInstance();
    $driver = Database::getDriver();

    if ($driver === 'mysql') {
        $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
    }

    $sql = "CREATE TABLE IF NOT EXISTS `withdrawal_requests` (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $db->exec($sql);

    if ($driver === 'mysql') {
        $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    }

    echo "Table 'withdrawal_requests' setup completed successfully!\n";
} catch (\Throwable $e) {
    echo "Migration Error: " . $e->getMessage() . "\n";
}
