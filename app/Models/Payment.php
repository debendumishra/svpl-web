<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Payment Model - Advisor Onboarding Fees & Customer Solar Payments
 */

namespace App\Models;

use App\Helpers\Database;

class Payment
{
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM payments WHERE id = ?", [$id]);
    }

    public static function findByReceipt(string $receiptNo): ?array
    {
        return Database::fetchOne("SELECT * FROM payments WHERE receipt_number = ?", [$receiptNo]);
    }

    public static function create(array $data): int
    {
        $code = $data['payment_code'] ?? self::generatePaymentCode();
        $receipt = $data['receipt_number'] ?? self::generateReceiptNumber();

        $sql = "INSERT INTO payments (
                    payment_code, entity_type, entity_id, purpose,
                    amount, payment_method, transaction_ref, status,
                    receipt_number, payment_date, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        Database::query($sql, [
            $code,
            $data['entity_type'],
            $data['entity_id'],
            $data['purpose'] ?? 'JOINING_FEE',
            $data['amount'] ?? (function_exists('advisor_joining_fee') ? advisor_joining_fee() : 2700.00),
            $data['payment_method'] ?? 'UPI',
            $data['transaction_ref'] ?? null,
            $data['status'] ?? 'PENDING',
            $receipt,
            $data['payment_date'] ?? date('Y-m-d')
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getPendingAdvisorPayments(): array
    {
        $sql = "SELECT p.*, a.id as advisor_id, a.advisor_code, a.referral_code, a.first_name, a.last_name,
                       a.mobile, a.district, a.block, a.created_at as registered_at,
                       u.email as user_email, u.id as user_id
                FROM payments p
                JOIN advisors a ON p.entity_id = a.id
                JOIN users u ON a.user_id = u.id
                WHERE p.entity_type = 'ADVISOR'
                  AND p.purpose = 'JOINING_FEE'
                  AND p.status = 'PENDING'
                ORDER BY p.id DESC";

        return Database::fetchAll($sql);
    }

    public static function getRecentPayments(int $limit = 50): array
    {
        $sql = "SELECT p.*, 
                       CASE 
                           WHEN p.entity_type = 'ADVISOR' THEN CONCAT(a.first_name, ' ', a.last_name)
                           WHEN p.entity_type = 'CUSTOMER' THEN CONCAT(c.first_name, ' ', c.last_name)
                           ELSE 'Unknown'
                       END as entity_name,
                       CASE 
                           WHEN p.entity_type = 'ADVISOR' THEN a.advisor_code
                           WHEN p.entity_type = 'CUSTOMER' THEN c.customer_code
                           ELSE ''
                       END as entity_code,
                       CASE 
                           WHEN p.entity_type = 'ADVISOR' THEN a.mobile
                           WHEN p.entity_type = 'CUSTOMER' THEN c.mobile
                           ELSE ''
                       END as entity_mobile
                FROM payments p
                LEFT JOIN advisors a ON p.entity_type = 'ADVISOR' AND p.entity_id = a.id
                LEFT JOIN customers c ON p.entity_type = 'CUSTOMER' AND p.entity_id = c.id
                ORDER BY p.id DESC
                LIMIT {$limit}";

        return Database::fetchAll($sql);
    }

    public static function confirmAdvisorPayment(int $paymentId, int $adminUserId): bool
    {
        $payment = self::findById($paymentId);
        if (!$payment || $payment['entity_type'] !== 'ADVISOR') {
            return false;
        }

        $advisorId = (int) $payment['entity_id'];
        $advisor = Advisor::findById($advisorId);
        if (!$advisor) {
            return false;
        }

        $userId = (int) $advisor['user_id'];

        Database::beginTransaction();
        try {
            // 1. Mark payment as SUCCESS
            Database::execute("UPDATE payments SET status = 'SUCCESS' WHERE id = ?", [$paymentId]);

            // 2. Activate Advisor & mark joining fee paid
            Database::execute("UPDATE advisors 
                               SET status = 'ACTIVE', 
                                   joining_fee_paid = 1, 
                                   joining_date = CURDATE(), 
                                   updated_at = NOW() 
                               WHERE id = ?", [$advisorId]);

            // 3. Promote User login role to ADVISOR
            Database::execute("UPDATE users SET role = 'ADVISOR', is_active = 1, updated_at = NOW() WHERE id = ?", [$userId]);

            // 4. Update linked Customer record if converted
            Database::execute(
                "UPDATE customers SET converted_to_advisor = 1, converted_advisor_id = ?, updated_at = NOW() WHERE user_id = ? OR converted_advisor_id = ?",
                [$advisorId, $userId, $advisorId]
            );

            // 5. Auto-post to Company Account Ledger
            $confirmedAmount = (float) ($payment['amount'] ?? (function_exists('advisor_joining_fee') ? advisor_joining_fee() : 2700.00));
            CompanyLedger::autoPostAdvisorFee(
                $advisorId,
                $confirmedAmount,
                $payment['payment_method'] ?? 'UPI',
                $payment['transaction_ref'] ?? 'N/A',
                $adminUserId
            );

            // 6. Trigger Direct Advisor Joining Commission Calculation
            \App\Services\CommissionCalculationService::processAdvisorJoining(
                $advisorId,
                $payment['payment_date'] ?? date('Y-m-d'),
                $paymentId
            );

            // 7. Log Audit
            AuditLog::log(
                $adminUserId,
                'PAYMENT_CONFIRMED',
                'ADVISOR',
                $advisorId,
                "Onboarding fee ₹" . number_format($confirmedAmount, 2) . " confirmed for Advisor {$advisor['advisor_code']} (UTR: {$payment['transaction_ref']})"
            );

            Database::commit();
            return true;
        } catch (\Throwable $e) {
            Database::rollBack();
            return false;
        }
    }

    public static function confirmCustomerPayment(int $paymentId, int $adminUserId, ?string $creditDate = null): bool
    {
        $payment = self::findById($paymentId);
        if (!$payment || $payment['entity_type'] !== 'CUSTOMER') {
            return false;
        }

        $customerId = (int) $payment['entity_id'];
        $customer = Customer::findById($customerId);
        if (!$customer) {
            return false;
        }

        $creditDate = $creditDate ?: ($payment['payment_date'] ?? date('Y-m-d'));

        Database::beginTransaction();
        try {
            Database::execute("UPDATE payments SET status = 'CONFIRMED', payment_date = ? WHERE id = ?", [$creditDate, $paymentId]);

            // Auto-post to Company Account Ledger
            $amount = (float) ($payment['amount'] ?? 0.00);
            CompanyLedger::create([
                'entry_type' => 'CREDIT',
                'category' => 'CUSTOMER_PAYMENT',
                'amount' => $amount,
                'entity_type' => 'CUSTOMER',
                'entity_id' => $customerId,
                'payment_method' => $payment['payment_method'] ?? 'BANK_TRANSFER',
                'reference_no' => $payment['transaction_ref'] ?? 'N/A',
                'description' => "Customer payment confirmed for {$customer['customer_code']}",
                'created_by' => $adminUserId
            ]);

            // Trigger 9-Level Customer Referral Commission Calculation
            \App\Services\CommissionCalculationService::processCustomerReferral(
                $customerId,
                $creditDate,
                $paymentId,
                $amount,
                $customer['proposed_solar_kw'] ? $customer['proposed_solar_kw'] . ' kW System' : null,
                (float)($customer['proposed_solar_kw'] ?? 3.0),
                'On-Grid'
            );

            AuditLog::log(
                $adminUserId,
                'CUSTOMER_PAYMENT_CONFIRMED',
                'CUSTOMER',
                $customerId,
                "Payment of ₹" . number_format($amount, 2) . " confirmed on {$creditDate} for Customer {$customer['customer_code']}"
            );

            Database::commit();
            return true;
        } catch (\Throwable $e) {
            Database::rollBack();
            return false;
        }
    }

    public static function rejectAdvisorPayment(int $paymentId, int $adminUserId, string $reason = ''): bool
    {
        $payment = self::findById($paymentId);
        if (!$payment) {
            return false;
        }

        $advisorId = (int) $payment['entity_id'];
        $advisor = Advisor::findById($advisorId);

        Database::beginTransaction();
        try {
            Database::execute("UPDATE payments SET status = 'FAILED' WHERE id = ?", [$paymentId]);
            
            if ($advisor) {
                $userId = (int) $advisor['user_id'];

                Database::execute(
                    "UPDATE advisors SET status = 'PAYMENT_REJECTED', joining_fee_paid = 0, updated_at = NOW() WHERE id = ?",
                    [$advisorId]
                );

                // Check if this advisor was converted from a customer or has a linked customer profile
                $customer = Database::fetchOne(
                    "SELECT * FROM customers WHERE user_id = ? OR converted_advisor_id = ? LIMIT 1",
                    [$userId, $advisorId]
                );

                if ($customer) {
                    // 1. Revert customer flags
                    Database::execute(
                        "UPDATE customers SET converted_to_advisor = 0, converted_advisor_id = NULL, updated_at = NOW() WHERE id = ?",
                        [$customer['id']]
                    );

                    // 2. Revert User login role back to CUSTOMER
                    Database::execute(
                        "UPDATE users SET role = 'CUSTOMER', is_active = 1, updated_at = NOW() WHERE id = ?",
                        [$userId]
                    );

                    // 3. Remove unapproved genealogy node
                    Database::execute(
                        "DELETE FROM advisor_genealogy WHERE descendant_id = ?",
                        [$advisorId]
                    );
                }
            }

            AuditLog::log(
                $adminUserId,
                'PAYMENT_REJECTED',
                'ADVISOR',
                $advisorId,
                "Onboarding payment rejected for Advisor " . ($advisor['advisor_code'] ?? "#{$advisorId}") . ". Reason: " . ($reason ?: 'Invalid UTR / Payment not received') . (isset($customer) && $customer ? " (Reverted to Customer {$customer['customer_code']})" : "")
            );

            Database::commit();
            return true;
        } catch (\Throwable $e) {
            Database::rollBack();
            return false;
        }
    }

    public static function countPendingAdvisorPayments(): int
    {
        $row = Database::fetchOne("SELECT COUNT(*) as cnt FROM payments WHERE entity_type = 'ADVISOR' AND purpose = 'JOINING_FEE' AND status = 'PENDING'");
        return $row ? (int)$row['cnt'] : 0;
    }

    public static function generatePaymentCode(): string
    {
        $row = Database::fetchOne("SELECT COUNT(*) as cnt FROM payments");
        $num = ($row ? (int)$row['cnt'] : 0) + 5001;
        return 'PAY-SVPL-' . date('Ym') . '-' . $num;
    }

    public static function generateReceiptNumber(): string
    {
        $row = Database::fetchOne("SELECT COUNT(*) as cnt FROM payments");
        $num = ($row ? (int)$row['cnt'] : 0) + 1001;
        return 'REC-SVPL-' . date('Y') . '-' . $num;
    }
}
