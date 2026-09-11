<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Withdrawal Request Model
 */

namespace App\Models;

use App\Helpers\Database;

class WithdrawalRequest
{
    public static function create(array $data): int
    {
        $requestCode = 'WD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 6));

        $sql = "INSERT INTO withdrawal_requests 
                (request_code, user_id, advisor_id, amount, tds_amount, net_payable, bank_name, bank_branch, account_holder, account_number, ifsc_code, status, requested_at, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', NOW(), NOW())";

        Database::execute($sql, [
            $requestCode,
            $data['user_id'],
            $data['advisor_id'],
            $data['amount'],
            $data['tds_amount'] ?? 0.00,
            $data['net_payable'],
            $data['bank_name'] ?? null,
            $data['bank_branch'] ?? null,
            $data['account_holder'] ?? null,
            $data['account_number'] ?? null,
            $data['ifsc_code'] ?? null
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getById(int $id): ?array
    {
        return Database::fetchOne(
            "SELECT wr.*, u.full_name as advisor_name, u.email as advisor_email, u.mobile as advisor_mobile, a.advisor_code 
             FROM withdrawal_requests wr
             JOIN users u ON wr.user_id = u.id
             JOIN advisors a ON wr.advisor_id = a.id
             WHERE wr.id = ?",
            [$id]
        );
    }

    public static function getByUserId(int $userId, int $limit = 50): array
    {
        return Database::fetchAll(
            "SELECT * FROM withdrawal_requests WHERE user_id = ? ORDER BY id DESC LIMIT {$limit}",
            [$userId]
        );
    }

    public static function getAll(string $statusFilter = 'ALL'): array
    {
        $sql = "SELECT wr.*, u.full_name as advisor_name, u.email as advisor_email, u.mobile as advisor_mobile, a.advisor_code 
                FROM withdrawal_requests wr
                JOIN users u ON wr.user_id = u.id
                JOIN advisors a ON wr.advisor_id = a.id";

        $params = [];
        if ($statusFilter !== 'ALL') {
            $sql .= " WHERE wr.status = ?";
            $params[] = $statusFilter;
        }

        $sql .= " ORDER BY wr.id DESC";

        return Database::fetchAll($sql, $params);
    }

    public static function approveAndPay(int $id, int $adminUserId, string $utrNumber, ?string $remarks = null): bool
    {
        $request = self::getById($id);
        if (!$request || $request['status'] !== 'PENDING') {
            return false;
        }

        Database::beginTransaction();
        try {
            // Update withdrawal request
            Database::execute(
                "UPDATE withdrawal_requests 
                 SET status = 'PAID', utr_number = ?, admin_remarks = ?, processed_by_user_id = ?, processed_at = NOW(), updated_at = NOW() 
                 WHERE id = ?",
                [$utrNumber, $remarks, $adminUserId, $id]
            );

            // Update wallet total_withdrawn
            Database::execute(
                "UPDATE wallets SET total_withdrawn = total_withdrawn + ?, updated_at = NOW() WHERE user_id = ?",
                [$request['amount'], $request['user_id']]
            );

            // Record in Company Ledger
            if (class_exists('\\App\\Models\\CompanyLedger')) {
                CompanyLedger::create([
                    'entry_type' => 'PAYMENT',
                    'entry_date' => date('Y-m-d'),
                    'account_head' => 'Commission Payout',
                    'party_type' => 'ADVISOR',
                    'party_id' => $request['advisor_id'],
                    'party_name' => $request['advisor_name'],
                    'party_identifier' => $request['advisor_code'] ?? null,
                    'payment_mode' => 'BANK_TRANSFER',
                    'reference_no' => $utrNumber,
                    'debit_amount' => (float)$request['net_payable'],
                    'credit_amount' => 0.00,
                    'narration' => "Bank Payout for Withdrawal Request #{$request['request_code']} (Net ₹{$request['net_payable']} after 5% TDS ₹{$request['tds_amount']})",
                    'status' => 'CONFIRMED',
                    'created_by_user_id' => $adminUserId
                ]);
            }

            // Record in Audit Log
            if (class_exists('\\App\\Models\\AuditLog')) {
                AuditLog::log($adminUserId, 'WITHDRAWAL_APPROVED_PAID', 'WITHDRAWAL', $id, "Approved & Paid withdrawal request {$request['request_code']} (UTR: {$utrNumber})");
            }

            Database::commit();
            return true;
        } catch (\Throwable $e) {
            Database::rollBack();
            return false;
        }
    }

    public static function reject(int $id, int $adminUserId, string $rejectionReason): bool
    {
        $request = self::getById($id);
        if (!$request || $request['status'] !== 'PENDING') {
            return false;
        }

        Database::beginTransaction();
        try {
            // Update withdrawal request status
            Database::execute(
                "UPDATE withdrawal_requests 
                 SET status = 'REJECTED', admin_remarks = ?, processed_by_user_id = ?, processed_at = NOW(), updated_at = NOW() 
                 WHERE id = ?",
                [$rejectionReason, $adminUserId, $id]
            );

            // Refund balance back to user's wallet
            Wallet::refund(
                (int)$request['user_id'],
                (float)$request['amount'],
                "Refund for Rejected Withdrawal Request #{$request['request_code']}: " . $rejectionReason,
                $id
            );

            // Record in Audit Log
            if (class_exists('\\App\\Models\\AuditLog')) {
                AuditLog::log($adminUserId, 'WITHDRAWAL_REJECTED', 'WITHDRAWAL', $id, "Rejected withdrawal request {$request['request_code']} Reason: {$rejectionReason}");
            }

            Database::commit();
            return true;
        } catch (\Throwable $e) {
            Database::rollBack();
            return false;
        }
    }
}
