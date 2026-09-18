<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Setup & Seeder for DISCOM Providers & 30 Odisha District Mappings
 */

require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getInstance();
    echo "Setting up DISCOM tables...\n";

    // 1. Create `discom_providers` table
    $db->exec("CREATE TABLE IF NOT EXISTS `discom_providers` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `code` VARCHAR(50) NOT NULL UNIQUE,
        `name` VARCHAR(150) NOT NULL,
        `short_name` VARCHAR(100) NOT NULL,
        `headquarters` VARCHAR(150) NULL DEFAULT 'Odisha',
        `helpline` VARCHAR(100) NULL,
        `portal_url` VARCHAR(255) NULL,
        `coverage_summary` TEXT NULL,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 2. Create `district_discoms` mapping table
    $db->exec("CREATE TABLE IF NOT EXISTS `district_discoms` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `district_name` VARCHAR(100) NOT NULL UNIQUE,
        `discom_id` INT UNSIGNED NOT NULL,
        `discom_code` VARCHAR(50) NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (`district_name`),
        INDEX (`discom_code`),
        FOREIGN KEY (`discom_id`) REFERENCES `discom_providers` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    // 3. Seed DISCOM Providers
    $discoms = [
        [
            'code' => 'TPCODL',
            'name' => 'TP Central Odisha Distribution Limited (TPCODL)',
            'short_name' => 'TP Central Odisha',
            'headquarters' => 'Bhubaneswar, Odisha',
            'helpline' => '1912 / 1800-345-7122',
            'portal_url' => 'https://www.tpcentralodisha.com',
            'coverage_summary' => 'Angul, Cuttack, Dhenkanal, Jagatsinghpur, Kendrapara, Khurda, Nayagarh, Puri',
            'is_active' => 1
        ],
        [
            'code' => 'TPNODL',
            'name' => 'TP Northern Odisha Distribution Limited (TPNODL)',
            'short_name' => 'TP Northern Odisha',
            'headquarters' => 'Balasore, Odisha',
            'helpline' => '1912 / 1800-345-6718',
            'portal_url' => 'https://www.tpnodl.com',
            'coverage_summary' => 'Balasore, Bhadrak, Jajpur, Keonjhar, Mayurbhanj',
            'is_active' => 1
        ],
        [
            'code' => 'TPSODL',
            'name' => 'TP Southern Odisha Distribution Limited (TPSODL)',
            'short_name' => 'TP Southern Odisha',
            'headquarters' => 'Berhampur, Ganjam, Odisha',
            'helpline' => '1912 / 1800-345-6797',
            'portal_url' => 'https://www.tpsouthernodisha.com',
            'coverage_summary' => 'Gajapati, Ganjam, Kandhamal, Koraput, Malkangiri, Nabarangpur, Rayagada',
            'is_active' => 1
        ],
        [
            'code' => 'TPWODL',
            'name' => 'TP Western Odisha Distribution Limited (TPWODL)',
            'short_name' => 'TP Western Odisha',
            'headquarters' => 'Burla, Sambalpur, Odisha',
            'helpline' => '1912 / 1800-345-6798',
            'portal_url' => 'https://www.tpwesternodisha.com',
            'coverage_summary' => 'Bargarh, Bolangir, Baudh (Sonepur), Deogarh, Jharsuguda, Kalahandi, Nuapada, Sambalpur, Subarnapur, Sundargarh',
            'is_active' => 1
        ]
    ];

    $stmtDiscom = $db->prepare("INSERT INTO `discom_providers` 
        (`code`, `name`, `short_name`, `headquarters`, `helpline`, `portal_url`, `coverage_summary`, `is_active`)
        VALUES (:code, :name, :short_name, :headquarters, :helpline, :portal_url, :coverage_summary, :is_active)
        ON DUPLICATE KEY UPDATE 
            `name` = VALUES(`name`),
            `short_name` = VALUES(`short_name`),
            `headquarters` = VALUES(`headquarters`),
            `helpline` = VALUES(`helpline`),
            `portal_url` = VALUES(`portal_url`),
            `coverage_summary` = VALUES(`coverage_summary`),
            `is_active` = VALUES(`is_active`)");

    foreach ($discoms as $d) {
        $stmtDiscom->execute($d);
    }

    // Cache Discom IDs
    $discomIds = [];
    $rows = $db->query("SELECT id, code FROM `discom_providers`")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $discomIds[$r['code']] = (int)$r['id'];
    }

    // 4. Seed all 30 District Mappings provided by the user
    $mappings = [
        'Angul' => 'TPCODL',
        'Balasore' => 'TPNODL',
        'Bargarh' => 'TPWODL',
        'Bhadrak' => 'TPNODL',
        'Bolangir' => 'TPWODL',
        'Baudh (Sonepur)' => 'TPWODL',
        'Boudh' => 'TPWODL',
        'Cuttack' => 'TPCODL',
        'Deogarh' => 'TPWODL',
        'Dhenkanal' => 'TPCODL',
        'Gajapati' => 'TPSODL',
        'Ganjam' => 'TPSODL',
        'Jagatsinghpur' => 'TPCODL',
        'Jajpur' => 'TPNODL',
        'Jharsuguda' => 'TPWODL',
        'Kalahandi' => 'TPWODL',
        'Kandhamal' => 'TPSODL',
        'Kendrapara' => 'TPCODL',
        'Keonjhar' => 'TPNODL',
        'Khurda' => 'TPCODL',
        'Khordha' => 'TPCODL',
        'Koraput' => 'TPSODL',
        'Malkangiri' => 'TPSODL',
        'Mayurbhanj' => 'TPNODL',
        'Nabarangpur' => 'TPSODL',
        'Nayagarh' => 'TPCODL',
        'Nuapada' => 'TPWODL',
        'Puri' => 'TPCODL',
        'Rayagada' => 'TPSODL',
        'Sambalpur' => 'TPWODL',
        'Subarnapur' => 'TPWODL',
        'Sonepur' => 'TPWODL',
        'Sundargarh' => 'TPWODL',
    ];

    $stmtMap = $db->prepare("INSERT INTO `district_discoms` 
        (`district_name`, `discom_id`, `discom_code`)
        VALUES (:district_name, :discom_id, :discom_code)
        ON DUPLICATE KEY UPDATE 
            `discom_id` = VALUES(`discom_id`),
            `discom_code` = VALUES(`discom_code`)");

    $count = 0;
    foreach ($mappings as $dist => $code) {
        if (isset($discomIds[$code])) {
            $stmtMap->execute([
                ':district_name' => $dist,
                ':discom_id' => $discomIds[$code],
                ':discom_code' => $code
            ]);
            $count++;
        }
    }

    echo "Successfully setup DISCOM Providers and seeded {$count} district mappings!\n";

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
