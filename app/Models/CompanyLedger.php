<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Company Account Ledger Model - Double-Entry Financial Books & Cash/Bank Register
 */

namespace App\Models;

use App\Helpers\Database;

class CompanyLedger
{
    /**
     * Standard Account Heads
     */
    public const ACCOUNT_HEADS = [
        'INFLOW' => [
            'Advisor Joining / Induction Fee',
            'Customer Solar Project Payment',
            'Government DBT / Subsidy Receipt',
            'Capital / Equity Reserve',
            'Bank Interest & Other Inflow',
            'Miscellaneous Receipt'
        ],
        'OUTFLOW' => [
            'Commission Payout',
            'Solar Hardware Procurement',
            'Marketing & Promotional Kits',
            'Logistics & Courier Delivery',
            'Office Rent & Admin Overhead',
            'Staff Salaries & Field Allowances',
            'Statutory TDS Remittance',
            'Bank & Transaction Charges',
            'Miscellaneous Expense'
        ]
    ];

    /**
     * Fetch all ledger entries with flexible filters
     */
    public static function getAll(array $filters = [], int $limit = 250): array
    {
        $where = ["1=1"];
        $params = [];

        if (!empty($filters['start_date'])) {
            $where[] = "l.entry_date >= ?";
            $params[] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $where[] = "l.entry_date <= ?";
            $params[] = $filters['end_date'];
        }

        if (!empty($filters['entry_type']) && in_array($filters['entry_type'], ['RECEIPT', 'PAYMENT', 'CONTRA', 'JOURNAL'])) {
            $where[] = "l.entry_type = ?";
            $params[] = $filters['entry_type'];
        }

        if (!empty($filters['party_type'])) {
            $where[] = "l.party_type = ?";
            $params[] = $filters['party_type'];
        }

        if (!empty($filters['account_head'])) {
            $where[] = "l.account_head = ?";
            $params[] = $filters['account_head'];
        }

        if (!empty($filters['payment_mode'])) {
            $where[] = "l.payment_mode = ?";
            $params[] = $filters['payment_mode'];
        }

        if (!empty($filters['search'])) {
            $searchTerm = '%' . trim($filters['search']) . '%';
            $where[] = "(l.voucher_no LIKE ? OR l.party_name LIKE ? OR l.party_identifier LIKE ? OR l.reference_no LIKE ? OR l.narration LIKE ?)";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        $whereClause = implode(" AND ", $where);
        $sql = "SELECT l.*, u.full_name as created_by_name, u.role as created_by_role
                FROM company_ledger l
                LEFT JOIN users u ON l.created_by_user_id = u.id
                WHERE {$whereClause}
                ORDER BY l.entry_date DESC, l.id DESC
                LIMIT {$limit}";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Create a new ledger transaction and compute running balance
     */
    public static function create(array $data): int
    {
        $entryType = strtoupper($data['entry_type'] ?? 'RECEIPT');
        $voucherNo = $data['voucher_no'] ?? self::generateVoucherNo($entryType);
        $entryDate = $data['entry_date'] ?? date('Y-m-d');
        $accountHead = trim($data['account_head'] ?? 'Miscellaneous Receipt');
        $partyType = $data['party_type'] ?? 'OTHER';
        $partyId = !empty($data['party_id']) ? (int)$data['party_id'] : null;
        $partyName = trim($data['party_name'] ?? 'General Party');
        $partyIdentifier = !empty($data['party_identifier']) ? trim($data['party_identifier']) : null;
        $paymentMode = $data['payment_mode'] ?? 'UPI';
        $referenceNo = !empty($data['reference_no']) ? trim($data['reference_no']) : null;
        $debitAmount = isset($data['debit_amount']) ? (float)$data['debit_amount'] : 0.00;
        $creditAmount = isset($data['credit_amount']) ? (float)$data['credit_amount'] : 0.00;
        $narration = !empty($data['narration']) ? trim($data['narration']) : null;
        $status = $data['status'] ?? 'CONFIRMED';
        $createdBy = !empty($data['created_by_user_id']) ? (int)$data['created_by_user_id'] : 1;

        // Auto calculate running balance based on latest balance
        $latest = Database::fetchOne("SELECT running_balance FROM company_ledger ORDER BY id DESC LIMIT 1");
        $prevBalance = $latest ? (float)$latest['running_balance'] : 0.00;
        $newRunningBalance = $prevBalance + $creditAmount - $debitAmount;

        $sql = "INSERT INTO company_ledger (
                    voucher_no, entry_type, entry_date, account_head, party_type, party_id,
                    party_name, party_identifier, payment_mode, reference_no, debit_amount,
                    credit_amount, running_balance, narration, status, created_by_user_id, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        Database::query($sql, [
            $voucherNo,
            $entryType,
            $entryDate,
            $accountHead,
            $partyType,
            $partyId,
            $partyName,
            $partyIdentifier,
            $paymentMode,
            $referenceNo,
            $debitAmount,
            $creditAmount,
            $newRunningBalance,
            $narration,
            $status,
            $createdBy
        ]);

        return (int) Database::lastInsertId();
    }

    /**
     * Get financial metrics summary (Inflow, Outflow, Net Balance, Today)
     */
    public static function getFinancialSummary(): array
    {
        $totals = Database::fetchOne("SELECT 
            COALESCE(SUM(credit_amount), 0) as total_receipts,
            COALESCE(SUM(debit_amount), 0) as total_payments,
            COALESCE(SUM(credit_amount) - SUM(debit_amount), 0) as net_balance
        FROM company_ledger WHERE status = 'CONFIRMED'");

        $today = date('Y-m-d');
        $todayMetrics = Database::fetchOne("SELECT 
            COALESCE(SUM(credit_amount), 0) as today_receipts,
            COALESCE(SUM(debit_amount), 0) as today_payments
        FROM company_ledger WHERE entry_date = ? AND status = 'CONFIRMED'", [$today]);

        $monthStart = date('Y-m-01');
        $monthMetrics = Database::fetchOne("SELECT 
            COALESCE(SUM(credit_amount), 0) as month_receipts,
            COALESCE(SUM(debit_amount), 0) as month_payments
        FROM company_ledger WHERE entry_date >= ? AND status = 'CONFIRMED'", [$monthStart]);

        $totalTransactions = Database::fetchOne("SELECT COUNT(*) as c FROM company_ledger")['c'] ?? 0;

        return [
            'total_receipts' => (float)($totals['total_receipts'] ?? 0),
            'total_payments' => (float)($totals['total_payments'] ?? 0),
            'net_balance' => (float)($totals['net_balance'] ?? 0),
            'today_receipts' => (float)($todayMetrics['today_receipts'] ?? 0),
            'today_payments' => (float)($todayMetrics['today_payments'] ?? 0),
            'month_receipts' => (float)($monthMetrics['month_receipts'] ?? 0),
            'month_payments' => (float)($monthMetrics['month_payments'] ?? 0),
            'total_transactions' => (int)$totalTransactions
        ];
    }

    /**
     * Get statement for a specific party
     */
    public static function getPartyStatement(string $partySearch, ?string $startDate = null, ?string $endDate = null): array
    {
        $where = ["(party_name LIKE ? OR party_identifier LIKE ?)"];
        $params = ['%' . $partySearch . '%', '%' . $partySearch . '%'];

        if (!empty($startDate)) {
            $where[] = "entry_date >= ?";
            $params[] = $startDate;
        }
        if (!empty($endDate)) {
            $where[] = "entry_date <= ?";
            $params[] = $endDate;
        }

        $whereClause = implode(" AND ", $where);
        $transactions = Database::fetchAll("SELECT * FROM company_ledger WHERE {$whereClause} ORDER BY entry_date ASC, id ASC", $params);

        $totalCredits = 0.0;
        $totalDebits = 0.0;
        foreach ($transactions as $t) {
            $totalCredits += (float)$t['credit_amount'];
            $totalDebits += (float)$t['debit_amount'];
        }

        return [
            'transactions' => $transactions,
            'total_credits' => $totalCredits,
            'total_debits' => $totalDebits,
            'net_party_balance' => $totalCredits - $totalDebits
        ];
    }

    /**
     * Group transactions by Account Head
     */
    public static function getAccountHeadSummary(?string $startDate = null, ?string $endDate = null): array
    {
        $where = ["1=1"];
        $params = [];

        if (!empty($startDate)) {
            $where[] = "entry_date >= ?";
            $params[] = $startDate;
        }
        if (!empty($endDate)) {
            $where[] = "entry_date <= ?";
            $params[] = $endDate;
        }

        $whereClause = implode(" AND ", $where);
        $sql = "SELECT account_head, entry_type,
                       COUNT(*) as txn_count,
                       COALESCE(SUM(credit_amount), 0) as total_credit,
                       COALESCE(SUM(debit_amount), 0) as total_debit
                FROM company_ledger
                WHERE {$whereClause}
                GROUP BY account_head, entry_type
                ORDER BY (SUM(credit_amount) + SUM(debit_amount)) DESC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get distinct parties for autocomplete / selection
     */
    public static function getDistinctParties(): array
    {
        $sql = "SELECT DISTINCT party_type, party_name, party_identifier 
                FROM company_ledger 
                WHERE party_name IS NOT NULL AND party_name != ''
                ORDER BY party_name ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Generate sequential voucher number
     */
    public static function generateVoucherNo(string $entryType = 'RECEIPT'): string
    {
        $prefix = ($entryType === 'PAYMENT') ? 'PMT' : (($entryType === 'CONTRA') ? 'CTR' : 'RCPT');
        $ym = date('Ym');
        $countRow = Database::fetchOne("SELECT COUNT(*) as cnt FROM company_ledger WHERE voucher_no LIKE ?", ["{$prefix}-{$ym}-%"]);
        $seq = ($countRow ? (int)$countRow['cnt'] : 0) + 1;
        return sprintf("%s-%s-%04d", $prefix, $ym, $seq);
    }

    /**
     * Auto-post Advisor Joining Fee Receipt upon admin confirmation
     */
    public static function autoPostAdvisorFee(int $advisorId, float $amount, string $paymentMode, string $referenceNo, int $adminUserId): int
    {
        $adv = Advisor::findById($advisorId);
        if (!$adv) return 0;

        $advName = trim(($adv['first_name'] ?? '') . ' ' . ($adv['last_name'] ?? ''));
        $advCode = $adv['advisor_code'] ?? "ADV-{$advisorId}";

        return self::create([
            'entry_type' => 'RECEIPT',
            'entry_date' => date('Y-m-d'),
            'account_head' => 'Advisor Joining / Induction Fee',
            'party_type' => 'ADVISOR',
            'party_id' => $advisorId,
            'party_name' => $advName,
            'party_identifier' => $advCode,
            'payment_mode' => $paymentMode ?: 'UPI',
            'reference_no' => $referenceNo,
            'credit_amount' => $amount,
            'debit_amount' => 0.00,
            'narration' => "Advisor onboarding & welcome kit fee verified (Advisor Code: {$advCode}, District: {$adv['district']}).",
            'status' => 'CONFIRMED',
            'created_by_user_id' => $adminUserId
        ]);
    }
}
