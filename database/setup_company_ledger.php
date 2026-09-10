<?php
/**
 * Setup Script for company_ledger Table
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $sql = "CREATE TABLE IF NOT EXISTS `company_ledger` (
      `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
      `voucher_no` VARCHAR(50) NOT NULL UNIQUE,
      `entry_type` ENUM('RECEIPT', 'PAYMENT', 'CONTRA', 'JOURNAL') NOT NULL DEFAULT 'RECEIPT',
      `entry_date` DATE NOT NULL,
      `account_head` VARCHAR(100) NOT NULL,
      `party_type` ENUM('ADVISOR', 'CUSTOMER', 'VENDOR', 'INTERNAL', 'BANK', 'GOVERNMENT', 'OTHER') NOT NULL DEFAULT 'ADVISOR',
      `party_id` INT UNSIGNED NULL,
      `party_name` VARCHAR(150) NOT NULL,
      `party_identifier` VARCHAR(50) NULL,
      `payment_mode` ENUM('UPI', 'NEFT', 'IMPS', 'RTGS', 'CASH', 'CHEQUE', 'BANK_TRANSFER', 'WALLET_ADJUSTMENT') NOT NULL DEFAULT 'UPI',
      `reference_no` VARCHAR(100) NULL,
      `debit_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      `credit_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
      `running_balance` DECIMAL(14,2) NOT NULL DEFAULT 0.00,
      `narration` TEXT NULL,
      `supporting_doc_url` VARCHAR(255) NULL,
      `status` ENUM('CONFIRMED', 'PENDING', 'RECONCILED', 'CANCELLED') NOT NULL DEFAULT 'CONFIRMED',
      `created_by_user_id` INT UNSIGNED NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      INDEX `idx_ledger_date` (`entry_date`),
      INDEX `idx_ledger_party` (`party_type`, `party_id`),
      INDEX `idx_ledger_head` (`account_head`),
      INDEX `idx_ledger_voucher` (`voucher_no`),
      INDEX `idx_ledger_type` (`entry_type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    Database::execute($sql);
    echo "company_ledger table created successfully!\n";

    // Check count
    $cnt = Database::fetchOne("SELECT COUNT(*) as c FROM company_ledger")['c'] ?? 0;
    echo "Current entries: {$cnt}\n";

    // If empty, let's seed realistic initial records (Advisor joining fees, solar installations, kits, commissions)
    if ($cnt == 0) {
        $sampleEntries = [
            [
                'voucher_no' => 'RCPT-202609-0001',
                'entry_type' => 'RECEIPT',
                'entry_date' => '2026-09-01',
                'account_head' => 'Advisor Joining / Induction Fee',
                'party_type' => 'ADVISOR',
                'party_id' => 1,
                'party_name' => 'Debendu Kumar Mishra',
                'party_identifier' => 'ADV-OD-2026-001',
                'payment_mode' => 'UPI',
                'reference_no' => 'UPI/260901/77889911',
                'debit_amount' => 0.00,
                'credit_amount' => 2700.00,
                'running_balance' => 2700.00,
                'narration' => 'Advisor onboarding fee (₹2,700) verified upon registration.',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'PMT-202609-0001',
                'entry_type' => 'PAYMENT',
                'entry_date' => '2026-09-02',
                'account_head' => 'Marketing & Promotional Kits',
                'party_type' => 'VENDOR',
                'party_id' => NULL,
                'party_name' => 'Odisha Print & Media Hub',
                'party_identifier' => 'VEND-OPM-77',
                'payment_mode' => 'NEFT',
                'reference_no' => 'NEFT-ODISHA-990123',
                'debit_amount' => 8500.00,
                'credit_amount' => 0.00,
                'running_balance' => -5800.00,
                'narration' => 'Printing of 50 Solar Advisor Induction Kits (ID Cards, Bags, Appointment Folios, Canopies).',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'RCPT-202609-0002',
                'entry_type' => 'RECEIPT',
                'entry_date' => '2026-09-03',
                'account_head' => 'Advisor Joining / Induction Fee',
                'party_type' => 'ADVISOR',
                'party_id' => 2,
                'party_name' => 'Ramesh Chandra Sahoo',
                'party_identifier' => 'ADV-OD-2026-002',
                'payment_mode' => 'UPI',
                'reference_no' => 'UPI/260903/12345678',
                'debit_amount' => 0.00,
                'credit_amount' => 2700.00,
                'running_balance' => -3100.00,
                'narration' => 'Advisor onboarding fee verified for Cuttack district network.',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'RCPT-202609-0003',
                'entry_type' => 'RECEIPT',
                'entry_date' => '2026-09-04',
                'account_head' => 'Customer Solar Project Payment',
                'party_type' => 'CUSTOMER',
                'party_id' => 1,
                'party_name' => 'Prakash Mohapatra',
                'party_identifier' => 'CUS-OD-0001',
                'payment_mode' => 'BANK_TRANSFER',
                'reference_no' => 'HDFC-IMPS-88776655',
                'debit_amount' => 0.00,
                'credit_amount' => 72000.00,
                'running_balance' => 68900.00,
                'narration' => 'Net customer payable amount for 3kW Rooftop Solar Installation under PM Surya Ghar.',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'PMT-202609-0002',
                'entry_type' => 'PAYMENT',
                'entry_date' => '2026-09-05',
                'account_head' => 'Solar Hardware Procurement',
                'party_type' => 'VENDOR',
                'party_id' => NULL,
                'party_name' => 'Dhwajja Solar India PV Ltd',
                'party_identifier' => 'VEND-DHW-01',
                'payment_mode' => 'RTGS',
                'reference_no' => 'RTGS-SBIN-20260905-01',
                'debit_amount' => 145000.00,
                'credit_amount' => 0.00,
                'running_balance' => -76100.00,
                'narration' => 'Procurement of 6x 550W Mono PERC Panels, 3kW Dhwajja String Inverter, and Structure Kits.',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'RCPT-202609-0004',
                'entry_type' => 'RECEIPT',
                'entry_date' => '2026-09-06',
                'account_head' => 'Customer Solar Project Payment',
                'party_type' => 'CUSTOMER',
                'party_id' => 2,
                'party_name' => 'Santosh Kumar Nayak',
                'party_identifier' => 'CUS-OD-0002',
                'payment_mode' => 'UPI',
                'reference_no' => 'UPI/260906/99881122',
                'debit_amount' => 0.00,
                'credit_amount' => 72000.00,
                'running_balance' => -4100.00,
                'narration' => 'Booking and project advance for 3kW Solar Installation (TPCODL Bhubaneswar).',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'PMT-202609-0003',
                'entry_type' => 'PAYMENT',
                'entry_date' => '2026-09-07',
                'account_head' => 'Commission Payout',
                'party_type' => 'ADVISOR',
                'party_id' => 1,
                'party_name' => 'Debendu Kumar Mishra',
                'party_identifier' => 'ADV-OD-2026-001',
                'payment_mode' => 'IMPS',
                'reference_no' => 'IMPS-SVPL-COMM-101',
                'debit_amount' => 7600.00,
                'credit_amount' => 0.00,
                'running_balance' => -11700.00,
                'narration' => 'Commission payout for Level 1 Direct Customer project completion (Net of 5% TDS ₹400).',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'PMT-202609-0004',
                'entry_type' => 'PAYMENT',
                'entry_date' => '2026-09-08',
                'account_head' => 'Logistics & Courier Delivery',
                'party_type' => 'VENDOR',
                'party_id' => NULL,
                'party_name' => 'DTDC Express Odisha',
                'party_identifier' => 'VEND-DTDC-01',
                'payment_mode' => 'UPI',
                'reference_no' => 'UPI/260908/33445566',
                'debit_amount' => 1250.00,
                'credit_amount' => 0.00,
                'running_balance' => -12950.00,
                'narration' => 'Courier and road transit charges for Advisor Kits to Balasore and Puri divisions.',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ],
            [
                'voucher_no' => 'RCPT-202609-0005',
                'entry_type' => 'RECEIPT',
                'entry_date' => '2026-09-10',
                'account_head' => 'Advisor Joining / Induction Fee',
                'party_type' => 'ADVISOR',
                'party_id' => 3,
                'party_name' => 'Bijay Kumar Das',
                'party_identifier' => 'ADV-OD-2026-003',
                'payment_mode' => 'UPI',
                'reference_no' => 'UPI/260910/98765432',
                'debit_amount' => 0.00,
                'credit_amount' => 2700.00,
                'running_balance' => -10250.00,
                'narration' => 'Advisor onboarding fee verified for Khordha region.',
                'status' => 'CONFIRMED',
                'created_by_user_id' => 1
            ]
        ];

        // Calculate continuous running balance
        $bal = 100000.00; // Starting Company Capital
        // Insert opening balance entry
        Database::query("INSERT INTO company_ledger (
            voucher_no, entry_type, entry_date, account_head, party_type, party_name,
            party_identifier, payment_mode, reference_no, debit_amount, credit_amount,
            running_balance, narration, status, created_by_user_id, created_at
        ) VALUES (
            'VCH-202609-OPENING', 'RECEIPT', '2026-09-01', 'Capital / Equity Reserve', 'INTERNAL', 'Surya Vistaara Pvt Ltd Treasury',
            'SVPL-TREASURY', 'BANK_TRANSFER', 'OPENING-BALANCE-2026', 0.00, 100000.00, 100000.00,
            'Company Treasury Opening Capital Balance for FY 2026-27 operations.', 'CONFIRMED', 1, NOW()
        )");

        $running = 100000.00;
        foreach ($sampleEntries as $entry) {
            $running = $running + $entry['credit_amount'] - $entry['debit_amount'];
            Database::query("INSERT INTO company_ledger (
                voucher_no, entry_type, entry_date, account_head, party_type, party_id,
                party_name, party_identifier, payment_mode, reference_no, debit_amount,
                credit_amount, running_balance, narration, status, created_by_user_id, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())", [
                $entry['voucher_no'],
                $entry['entry_type'],
                $entry['entry_date'],
                $entry['account_head'],
                $entry['party_type'],
                $entry['party_id'],
                $entry['party_name'],
                $entry['party_identifier'],
                $entry['payment_mode'],
                $entry['reference_no'],
                $entry['debit_amount'],
                $entry['credit_amount'],
                $running,
                $entry['narration'],
                $entry['status'],
                $entry['created_by_user_id']
            ]);
        }
        echo "Seeded " . count($sampleEntries) . " sample ledger transactions with accurate running balances!\n";
    }

} catch (\Throwable $t) {
    echo "Error: " . $t->getMessage() . "\n" . $t->getTraceAsString();
}
