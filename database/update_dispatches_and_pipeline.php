<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Migration — 15-Point Pipeline & Comprehensive Instrument Dispatch System
 */

require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getInstance();
    echo "Running migration: 15-point pipeline & dispatch expansion...\n";

    // 1. Update `package_dispatches` columns
    $cols = $db->query("DESCRIBE package_dispatches")->fetchAll(PDO::FETCH_COLUMN);

    $neededColumns = [
        'customer_id'       => "INT UNSIGNED NULL AFTER `lead_id`",
        'vehicle_number'    => "VARCHAR(50) NULL AFTER `tracking_number`",
        'driver_name'       => "VARCHAR(150) NULL AFTER `vehicle_number`",
        'driver_mobile'     => "VARCHAR(20) NULL AFTER `driver_name`",
        'vendor_name'       => "VARCHAR(150) NULL AFTER `driver_mobile`",
        'items_json'        => "LONGTEXT NULL AFTER `items_included`",
        'media_urls'        => "LONGTEXT NULL AFTER `items_json`",
    ];

    foreach ($neededColumns as $col => $def) {
        if (!in_array($col, $cols)) {
            $db->exec("ALTER TABLE `package_dispatches` ADD COLUMN `{$col}` {$def}");
            echo "- Added `{$col}` to `package_dispatches` table.\n";
        }
    }

    // 2. Add net_metering / mmg tracking table if needed or support in leads
    $leadCols = $db->query("DESCRIBE leads")->fetchAll(PDO::FETCH_COLUMN);
    $leadNeeded = [
        'net_meter_number'          => "VARCHAR(100) NULL",
        'net_meter_date'            => "DATE NULL",
        'mmg_intimation_date'       => "DATE NULL",
        'mmg_intimation_ref'        => "VARCHAR(100) NULL",
        'mmg_report_date'           => "DATE NULL",
        'mmg_report_number'         => "VARCHAR(100) NULL",
        'bank_second_inst_date'     => "DATE NULL",
        'bank_second_inst_amount'   => "DECIMAL(12,2) NULL",
        'bank_second_inst_utr'      => "VARCHAR(100) NULL",
    ];

    foreach ($leadNeeded as $col => $def) {
        if (!in_array($col, $leadCols)) {
            $db->exec("ALTER TABLE `leads` ADD COLUMN `{$col}` {$def}");
            echo "- Added `{$col}` to `leads` table.\n";
        }
    }

    echo "Migration completed successfully!\n";
} catch (\Throwable $e) {
    echo "Error running migration: " . $e->getMessage() . "\n";
}
