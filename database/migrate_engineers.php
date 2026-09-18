<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Migration: Engineers Table, Package Dispatches Engineer & Acknowledgment Columns
 */

require_once 'd:/DKM/SVPL-Web/config/constants.php';
$appConfig = require 'd:/DKM/SVPL-Web/config/app.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = 'd:/DKM/SVPL-Web/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) require_once $file;
});

use App\Helpers\Database;

try {
    echo "=== 1. CREATING engineers TABLE ===\n";
    Database::query("
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
    echo "engineers table created/verified successfully.\n";

    echo "=== 2. ADDING engineer_id & ACKNOWLEDGMENT COLUMNS TO package_dispatches ===\n";
    $cols = Database::fetchAll("DESCRIBE package_dispatches");
    $colNames = array_column($cols, 'Field');

    if (!in_array('engineer_id', $colNames)) {
        Database::query("ALTER TABLE `package_dispatches` ADD COLUMN `engineer_id` INT UNSIGNED NULL AFTER `advisor_id`");
        Database::query("ALTER TABLE `package_dispatches` ADD INDEX `idx_dsp_engineer` (`engineer_id`)");
        echo "Added column engineer_id to package_dispatches.\n";
    }

    if (!in_array('customer_acknowledged', $colNames)) {
        Database::query("ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledged` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`");
        echo "Added column customer_acknowledged.\n";
    }

    if (!in_array('customer_acknowledged_at', $colNames)) {
        Database::query("ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledged_at` DATETIME NULL AFTER `customer_acknowledged`");
        echo "Added column customer_acknowledged_at.\n";
    }

    if (!in_array('customer_acknowledgment_notes', $colNames)) {
        Database::query("ALTER TABLE `package_dispatches` ADD COLUMN `customer_acknowledgment_notes` TEXT NULL AFTER `customer_acknowledged_at`");
        echo "Added column customer_acknowledgment_notes.\n";
    }

    echo "=== 3. SEEDING DEFAULT ACTIVE SOLAR ENGINEERS ===\n";
    $sampleEngineers = [
        [
            'code'        => 'SVPL-ENG-001',
            'username'    => 'engineer.alok',
            'full_name'   => 'Er. Alok Ranjan Mohapatra',
            'mobile'      => '9861012345',
            'email'       => 'alok.engineer@suryavistaara.com',
            'designation' => 'Lead Solar Project Engineer',
            'districts'   => 'Khordha, Cuttack, Puri, Nayagarh',
            'qual'        => 'B.Tech Electrical & Renewable Energy (MNRE Certified)'
        ],
        [
            'code'        => 'SVPL-ENG-002',
            'username'    => 'engineer.biswajit',
            'full_name'   => 'Er. Biswajit Sahoo',
            'mobile'      => '9437023456',
            'email'       => 'biswajit.engineer@suryavistaara.com',
            'designation' => 'Senior Site Commissioning Engineer',
            'districts'   => 'Ganjam, Gajapati, Rayagada, Koraput',
            'qual'        => 'B.Tech Mechanical & Solar PV Design'
        ],
        [
            'code'        => 'SVPL-ENG-003',
            'username'    => 'engineer.debashis',
            'full_name'   => 'Er. Debashis Nayak',
            'mobile'      => '9853034567',
            'email'       => 'debashis.engineer@suryavistaara.com',
            'designation' => 'Grid-Tie & Net-Metering Engineer',
            'districts'   => 'Balasore, Bhadrak, Mayurbhanj, Jajpur',
            'qual'        => 'Diploma Electrical Engineering & Surya Mitra certified'
        ],
        [
            'code'        => 'SVPL-ENG-004',
            'username'    => 'engineer.subrat',
            'full_name'   => 'Er. Subrat Kumar Jena',
            'mobile'      => '9777045678',
            'email'       => 'subrat.engineer@suryavistaara.com',
            'designation' => 'Field Installation Supervisor & Engineer',
            'districts'   => 'Sambalpur, Bargarh, Jharsuguda, Sundargarh',
            'qual'        => 'B.Tech Electrical & Electronics'
        ]
    ];

    foreach ($sampleEngineers as $engData) {
        $existingEng = Database::fetchOne("SELECT id FROM engineers WHERE engineer_code = ?", [$engData['code']]);
        if (!$existingEng) {
            // Check or create user account
            $user = Database::fetchOne("SELECT id FROM users WHERE email = ? OR mobile = ? OR employee_code = ?", [$engData['email'], $engData['mobile'], $engData['code']]);
            $userId = null;
            if (!$user) {
                $hash = password_hash('Engineer@123', PASSWORD_DEFAULT);
                Database::query("
                    INSERT INTO users (role, email, mobile, password_hash, full_name, employee_code, designation, is_active, created_at)
                    VALUES ('ENGINEER', ?, ?, ?, ?, ?, ?, 1, NOW())
                ", [
                    $engData['email'],
                    $engData['mobile'],
                    $hash,
                    $engData['full_name'],
                    $engData['code'],
                    $engData['designation']
                ]);
                $userId = (int) Database::lastInsertId();
            } else {
                $userId = (int) $user['id'];
                Database::query("UPDATE users SET role = 'ENGINEER', full_name = ?, designation = ? WHERE id = ?", [$engData['full_name'], $engData['designation'], $userId]);
            }

            Database::query("
                INSERT INTO engineers (user_id, engineer_code, full_name, mobile, email, designation, qualification, assigned_districts, is_active, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, NOW())
            ", [
                $userId,
                $engData['code'],
                $engData['full_name'],
                $engData['mobile'],
                $engData['email'],
                $engData['designation'],
                $engData['qual'],
                $engData['districts']
            ]);
            echo "Seeded engineer: {$engData['full_name']} ({$engData['code']})\n";
        }
    }

    echo "=== MIGRATION COMPLETE SUCCESSFULLY ===\n";
} catch (\Throwable $e) {
    echo "MIGRATION ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
