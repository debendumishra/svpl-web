<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Setup & Seeder for All Solar Brand Packages
 */

require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getInstance();
    echo "Updating `packages` table schema...\n";

    // 1. Check existing columns
    $existingCols = [];
    $stmt = $db->query("DESCRIBE `packages`");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $existingCols[] = $row['Field'];
    }

    if (!in_array('brand', $existingCols)) {
        $db->exec("ALTER TABLE `packages` ADD COLUMN `brand` VARCHAR(100) NULL AFTER `package_code`");
        echo "Added column `brand`\n";
    }

    if (!in_array('system_type', $existingCols)) {
        $db->exec("ALTER TABLE `packages` ADD COLUMN `system_type` VARCHAR(50) NOT NULL DEFAULT 'On-Grid' AFTER `capacity_kw`");
        echo "Added column `system_type`\n";
    }

    if (!in_array('key_features', $existingCols)) {
        $db->exec("ALTER TABLE `packages` ADD COLUMN `key_features` TEXT NULL AFTER `inverter_type`");
        echo "Added column `key_features`\n";
    }

    $db->exec("UPDATE `packages` SET `brand` = 'Dhwajja Solar' WHERE `brand` IS NULL OR `brand` = ''");

    // 2. Package data from user's official table
    $packages = [
        // 1. Tata Power Solar
        [
            'package_code' => 'PKG-TATA-3KW-ONGRID',
            'brand' => 'Tata Power Solar',
            'title' => 'Tata Power Solar 3kW On-Grid Plant',
            'capacity_kw' => 3.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Mono PERC / DCR High-Efficiency Tier-1',
            'inverter_type' => '3kW Smart Grid-Tied Inverter (Wi-Fi)',
            'key_features' => 'Uses high-efficiency Mono PERC / DCR panels; tier-1 brand value and nationwide service network.',
            'battery_included' => 0,
            'total_price' => 230000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 92000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-TATA-5KW-ONGRID',
            'brand' => 'Tata Power Solar',
            'title' => 'Tata Power Solar 5kW On-Grid Plant',
            'capacity_kw' => 5.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Mono PERC / DCR High-Efficiency Tier-1',
            'inverter_type' => '5kW 3-Phase Grid-Tied Inverter (Wi-Fi)',
            'key_features' => 'Uses high-efficiency Mono PERC / DCR panels; tier-1 brand value and nationwide service network.',
            'battery_included' => 0,
            'total_price' => 360000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 222000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-TATA-10KW-ONGRID-HYBRID',
            'brand' => 'Tata Power Solar',
            'title' => 'Tata Power Solar 10kW On-Grid / Hybrid Plant',
            'capacity_kw' => 10.00,
            'system_type' => 'On-Grid / Hybrid',
            'panel_type' => 'Mono PERC / DCR High-Efficiency Tier-1',
            'inverter_type' => '10kW Dual MPPT 3-Phase Grid/Hybrid Inverter',
            'key_features' => 'Uses high-efficiency Mono PERC / DCR panels; tier-1 brand value and nationwide service network.',
            'battery_included' => 0,
            'total_price' => 620000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 482000.00,
            'is_active' => 1
        ],

        // 2. Waaree Energies
        [
            'package_code' => 'PKG-WAAREE-3KW-ONGRID',
            'brand' => 'Waaree Energies',
            'title' => 'Waaree Energies 3kW On-Grid Plant',
            'capacity_kw' => 3.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Mono PERC / TOPCon / Bifacial Tier-1',
            'inverter_type' => '3kW Smart Grid-Tied Inverter',
            'key_features' => 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.',
            'battery_included' => 0,
            'total_price' => 215000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 77000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-WAAREE-3KW-HYBRID',
            'brand' => 'Waaree Energies',
            'title' => 'Waaree Energies 3kW Hybrid Plant (Lithium LFP)',
            'capacity_kw' => 3.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Mono PERC / TOPCon / Bifacial Tier-1',
            'inverter_type' => '3kW Hybrid Inverter with Energy Management',
            'key_features' => 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.',
            'battery_included' => 1,
            'total_price' => 330000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 192000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-WAAREE-5KW-HYBRID',
            'brand' => 'Waaree Energies',
            'title' => 'Waaree Energies 5kW Hybrid Plant (Lithium LFP)',
            'capacity_kw' => 5.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Mono PERC / TOPCon / Bifacial Tier-1',
            'inverter_type' => '5kW Hybrid Inverter (Wi-Fi Enabled)',
            'key_features' => 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.',
            'battery_included' => 1,
            'total_price' => 500000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 362000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-WAAREE-10KW-HYBRID',
            'brand' => 'Waaree Energies',
            'title' => 'Waaree Energies 10kW Hybrid Plant (Lithium LFP)',
            'capacity_kw' => 10.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Mono PERC / TOPCon / Bifacial Tier-1',
            'inverter_type' => '10kW 3-Phase Commercial Hybrid Inverter',
            'key_features' => 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.',
            'battery_included' => 1,
            'total_price' => 1000000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 862000.00,
            'is_active' => 1
        ],

        // 3. Adani Solar
        [
            'package_code' => 'PKG-ADANI-3KW-ONGRID',
            'brand' => 'Adani Solar',
            'title' => 'Adani Solar 3kW On-Grid Plant',
            'capacity_kw' => 3.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'TOPCon / Mono PERC Half-Cut Technology',
            'inverter_type' => '3kW Smart Dual-MPPT Grid Inverter',
            'key_features' => 'Uses TOPCon / Mono PERC half-cut technology; widely used in residential and commercial projects.',
            'battery_included' => 0,
            'total_price' => 215000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 77000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-ADANI-5KW-ONGRID',
            'brand' => 'Adani Solar',
            'title' => 'Adani Solar 5kW On-Grid Plant',
            'capacity_kw' => 5.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'TOPCon / Mono PERC Half-Cut Technology',
            'inverter_type' => '5kW Dual-MPPT Grid-Tied Inverter',
            'key_features' => 'Uses TOPCon / Mono PERC half-cut technology; widely used in residential and commercial projects.',
            'battery_included' => 0,
            'total_price' => 340000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 202000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-ADANI-10KW-ONGRID-HYBRID',
            'brand' => 'Adani Solar',
            'title' => 'Adani Solar 10kW On-Grid / Hybrid Plant',
            'capacity_kw' => 10.00,
            'system_type' => 'On-Grid / Hybrid',
            'panel_type' => 'TOPCon / Mono PERC Half-Cut Technology',
            'inverter_type' => '10kW 3-Phase On-Grid / Hybrid Inverter',
            'key_features' => 'Uses TOPCon / Mono PERC half-cut technology; widely used in residential and commercial projects.',
            'battery_included' => 0,
            'total_price' => 800000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 662000.00,
            'is_active' => 1
        ],

        // 4. Loom Solar
        [
            'package_code' => 'PKG-LOOM-3KW-ONGRID',
            'brand' => 'Loom Solar',
            'title' => 'Loom Solar 3kW Shark On-Grid Plant',
            'capacity_kw' => 3.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Shark Series (Mono PERC / TOPCon Bi-facial)',
            'inverter_type' => '3kW High-Efficiency Grid-Tied Inverter',
            'key_features' => 'Specializes in high-efficiency Shark series (Mono PERC / TOPCon) and compact lithium battery integrations.',
            'battery_included' => 0,
            'total_price' => 240000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 102000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-LOOM-5KW-HYBRID',
            'brand' => 'Loom Solar',
            'title' => 'Loom Solar 5kW Shark Hybrid Plant (Lithium)',
            'capacity_kw' => 5.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Shark Series (Mono PERC / TOPCon Bi-facial)',
            'inverter_type' => '5kW Hybrid Inverter with Smart App',
            'key_features' => 'Specializes in high-efficiency Shark series (Mono PERC / TOPCon) and compact lithium battery integrations.',
            'battery_included' => 1,
            'total_price' => 550000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 412000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-LOOM-10KW-HYBRID',
            'brand' => 'Loom Solar',
            'title' => 'Loom Solar 10kW Shark Hybrid Plant (Lithium)',
            'capacity_kw' => 10.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Shark Series (Mono PERC / TOPCon Bi-facial)',
            'inverter_type' => '10kW 3-Phase Heavy-Duty Hybrid Inverter',
            'key_features' => 'Specializes in high-efficiency Shark series (Mono PERC / TOPCon) and compact lithium battery integrations.',
            'battery_included' => 1,
            'total_price' => 1200000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 1062000.00,
            'is_active' => 1
        ],

        // 5. UTL Solar / Luminous
        [
            'package_code' => 'PKG-UTL-LUMINOUS-3KW-ONGRID',
            'brand' => 'UTL Solar / Luminous',
            'title' => 'UTL Solar / Luminous 3kW On-Grid Plant',
            'capacity_kw' => 3.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Mono PERC Budget High-Yield Panels',
            'inverter_type' => '3kW Residential Grid-Tied Inverter',
            'key_features' => 'Budget-friendly residential setups; offers both lead-acid/tubular and lithium-ion battery options.',
            'battery_included' => 0,
            'total_price' => 200000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 62000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-UTL-LUMINOUS-5KW-OFFGRID',
            'brand' => 'UTL Solar / Luminous',
            'title' => 'UTL Solar / Luminous 5kW Off-Grid Plant',
            'capacity_kw' => 5.00,
            'system_type' => 'Off-Grid',
            'panel_type' => 'High Yield Mono PERC Solar Panels',
            'inverter_type' => '5kW MPPT Pure Sine Wave Off-Grid PCU',
            'key_features' => 'Budget-friendly residential setups; offers both lead-acid/tubular and lithium-ion battery options.',
            'battery_included' => 1,
            'total_price' => 350000.00,
            'estimated_subsidy' => 0.00,
            'net_customer_cost' => 350000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-UTL-LUMINOUS-5KW-HYBRID',
            'brand' => 'UTL Solar / Luminous',
            'title' => 'UTL Solar / Luminous 5kW Hybrid Plant',
            'capacity_kw' => 5.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'High Yield Mono PERC Solar Panels',
            'inverter_type' => '5kW Smart Hybrid Solar PCU / Inverter',
            'key_features' => 'Budget-friendly residential setups; offers both lead-acid/tubular and lithium-ion battery options.',
            'battery_included' => 1,
            'total_price' => 480000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 342000.00,
            'is_active' => 1
        ],

        // 6. IYRO Solar
        [
            'package_code' => 'PKG-IYRO-3KW-ONGRID',
            'brand' => 'IYRO Solar',
            'title' => 'IYRO Solar 3kW On-Grid Plant',
            'capacity_kw' => 3.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Mono PERC High Yield Panels',
            'inverter_type' => '3kW Grid-Tied Inverter',
            'key_features' => 'None',
            'battery_included' => 0,
            'total_price' => 200000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 62000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-IYRO-3KW-HYBRID',
            'brand' => 'IYRO Solar',
            'title' => 'IYRO Solar 3kW Hybrid Plant (2× Batteries)',
            'capacity_kw' => 3.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Mono PERC High Yield Panels',
            'inverter_type' => '3kW Smart Hybrid Inverter',
            'key_features' => '2× 150Ah / 200Ah C10 Tubular or Lithium',
            'battery_included' => 1,
            'total_price' => 290000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 152000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-IYRO-5KW-ONGRID',
            'brand' => 'IYRO Solar',
            'title' => 'IYRO Solar 5kW On-Grid Plant',
            'capacity_kw' => 5.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Mono PERC High Yield Panels',
            'inverter_type' => '5kW Grid-Tied Inverter',
            'key_features' => 'None',
            'battery_included' => 0,
            'total_price' => 280000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 142000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-IYRO-5KW-HYBRID',
            'brand' => 'IYRO Solar',
            'title' => 'IYRO Solar 5kW Hybrid Plant (4× Batteries)',
            'capacity_kw' => 5.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Mono PERC High Yield Panels',
            'inverter_type' => '5kW Smart Hybrid Inverter',
            'key_features' => '4× 150Ah / 200Ah Solar Batteries',
            'battery_included' => 1,
            'total_price' => 440000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 302000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-IYRO-10KW-ONGRID',
            'brand' => 'IYRO Solar',
            'title' => 'IYRO Solar 10kW On-Grid Plant',
            'capacity_kw' => 10.00,
            'system_type' => 'On-Grid',
            'panel_type' => 'Mono PERC Tier-1 High Yield Panels',
            'inverter_type' => '10kW 3-Phase Grid-Tied Inverter',
            'key_features' => 'None',
            'battery_included' => 0,
            'total_price' => 540000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 402000.00,
            'is_active' => 1
        ],
        [
            'package_code' => 'PKG-IYRO-10KW-HYBRID',
            'brand' => 'IYRO Solar',
            'title' => 'IYRO Solar 10kW Hybrid Plant (High-Capacity Battery Bank)',
            'capacity_kw' => 10.00,
            'system_type' => 'Hybrid',
            'panel_type' => 'Mono PERC Tier-1 High Yield Panels',
            'inverter_type' => '10kW 3-Phase Heavy-Duty Hybrid Inverter',
            'key_features' => 'High-capacity battery bank (Lithium/Tubular)',
            'battery_included' => 1,
            'total_price' => 780000.00,
            'estimated_subsidy' => 138000.00,
            'net_customer_cost' => 642000.00,
            'is_active' => 1
        ]
    ];

    $insertSql = "INSERT INTO `packages` 
        (`package_code`, `brand`, `title`, `capacity_kw`, `system_type`, `panel_type`, `inverter_type`, `key_features`, `battery_included`, `total_price`, `estimated_subsidy`, `net_customer_cost`, `is_active`)
        VALUES 
        (:package_code, :brand, :title, :capacity_kw, :system_type, :panel_type, :inverter_type, :key_features, :battery_included, :total_price, :estimated_subsidy, :net_customer_cost, :is_active)
        ON DUPLICATE KEY UPDATE
            `brand` = VALUES(`brand`),
            `title` = VALUES(`title`),
            `capacity_kw` = VALUES(`capacity_kw`),
            `system_type` = VALUES(`system_type`),
            `panel_type` = VALUES(`panel_type`),
            `inverter_type` = VALUES(`inverter_type`),
            `key_features` = VALUES(`key_features`),
            `battery_included` = VALUES(`battery_included`),
            `total_price` = VALUES(`total_price`),
            `estimated_subsidy` = VALUES(`estimated_subsidy`),
            `net_customer_cost` = VALUES(`net_customer_cost`),
            `is_active` = VALUES(`is_active`)";

    $stmt = $db->prepare($insertSql);

    $count = 0;
    foreach ($packages as $pkg) {
        $stmt->execute($pkg);
        $count++;
        echo "Saved: {$pkg['brand']} - {$pkg['capacity_kw']} kW ({$pkg['system_type']}) - ₹" . number_format($pkg['total_price']) . "\n";
    }

    echo "\nSuccessfully seeded {$count} packages into database!\n";

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
