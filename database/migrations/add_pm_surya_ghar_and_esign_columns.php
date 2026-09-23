<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $cols = Database::fetchAll("SHOW COLUMNS FROM customers");
    $existing = array_column($cols, 'Field');

    if (!in_array('pm_surya_ghar_id', $existing)) {
        Database::execute("ALTER TABLE customers ADD COLUMN pm_surya_ghar_id VARCHAR(100) NULL AFTER consumer_number");
        echo "[Added] pm_surya_ghar_id\n";
    }

    if (!in_array('notification_number', $existing)) {
        Database::execute("ALTER TABLE customers ADD COLUMN notification_number VARCHAR(100) NULL AFTER pm_surya_ghar_id");
        echo "[Added] notification_number\n";
    }

    if (!in_array('customer_signature', $existing)) {
        Database::execute("ALTER TABLE customers ADD COLUMN customer_signature VARCHAR(255) NULL AFTER photo_url");
        echo "[Added] customer_signature\n";
    }

    if (!in_array('agreement_accepted', $existing)) {
        Database::execute("ALTER TABLE customers ADD COLUMN agreement_accepted TINYINT(1) DEFAULT 0 AFTER status");
        echo "[Added] agreement_accepted\n";
    }

    if (!in_array('agreement_accepted_at', $existing)) {
        Database::execute("ALTER TABLE customers ADD COLUMN agreement_accepted_at DATETIME NULL AFTER agreement_accepted");
        echo "[Added] agreement_accepted_at\n";
    }

    echo "Migration completed successfully!\n";
} catch (\Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
}
