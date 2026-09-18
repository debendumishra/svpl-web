<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Migration — Add Electricity Bill Mobile & DOB to Customers and Leads tables
 */

require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $db = Database::getInstance();
    echo "Running migration: add electricity bill mobile & dob fields...\n";

    // 1. Add to customers table
    $customerCols = $db->query("DESCRIBE customers")->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('electricity_bill_mobile', $customerCols)) {
        $db->exec("ALTER TABLE `customers` ADD COLUMN `electricity_bill_mobile` VARCHAR(20) NULL AFTER `consumer_number`");
        echo "- Added electricity_bill_mobile to customers table.\n";
    }

    if (!in_array('electricity_bill_dob', $customerCols)) {
        $db->exec("ALTER TABLE `customers` ADD COLUMN `electricity_bill_dob` DATE NULL AFTER `electricity_bill_mobile`");
        echo "- Added electricity_bill_dob to customers table.\n";
    }

    if (!in_array('dob', $customerCols)) {
        $db->exec("ALTER TABLE `customers` ADD COLUMN `dob` DATE NULL AFTER `last_name`");
        echo "- Added dob to customers table.\n";
    }

    // 2. Add to leads table
    $leadCols = $db->query("DESCRIBE leads")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('electricity_bill_mobile', $leadCols)) {
        $db->exec("ALTER TABLE `leads` ADD COLUMN `electricity_bill_mobile` VARCHAR(20) NULL AFTER `consumer_number`");
        echo "- Added electricity_bill_mobile to leads table.\n";
    }

    if (!in_array('electricity_bill_dob', $leadCols)) {
        $db->exec("ALTER TABLE `leads` ADD COLUMN `electricity_bill_dob` DATE NULL AFTER `electricity_bill_mobile`");
        echo "- Added electricity_bill_dob to leads table.\n";
    }

    echo "Migration completed successfully!\n";
} catch (\Throwable $e) {
    echo "Error running migration: " . $e->getMessage() . "\n";
}
