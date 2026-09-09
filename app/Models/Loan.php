<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Loan Application Model
 */

namespace App\Models;

use App\Helpers\Database;

class Loan
{
    public static function createOrUpdate(array $data): int
    {
        $existing = Database::fetchOne("SELECT id FROM loans WHERE lead_id = ?", [$data['lead_id']]);
        if ($existing) {
            $sql = "UPDATE loans SET 
                        bank_name = ?, loan_application_number = ?, loan_amount = ?,
                        interest_rate = ?, tenure_months = ?, emi_amount = ?,
                        status = ?, remarks = ?, updated_at = NOW()
                    WHERE id = ?";
            Database::execute($sql, [
                $data['bank_name'] ?? 'State Bank of India',
                $data['loan_application_number'] ?? null,
                $data['loan_amount'] ?? 0,
                $data['interest_rate'] ?? 7.0,
                $data['tenure_months'] ?? 60,
                $data['emi_amount'] ?? 0,
                $data['status'] ?? 'Applied',
                $data['remarks'] ?? null,
                $existing['id'],
            ]);
            return (int) $existing['id'];
        }

        $sql = "INSERT INTO loans (
                    lead_id, bank_name, loan_application_number, loan_amount,
                    interest_rate, tenure_months, emi_amount, status,
                    remarks, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        Database::query($sql, [
            $data['lead_id'],
            $data['bank_name'] ?? 'State Bank of India',
            $data['loan_application_number'] ?? null,
            $data['loan_amount'] ?? 0,
            $data['interest_rate'] ?? 7.0,
            $data['tenure_months'] ?? 60,
            $data['emi_amount'] ?? 0,
            $data['status'] ?? 'Applied',
            $data['remarks'] ?? null,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function findByLeadId(int $leadId): ?array
    {
        return Database::fetchOne("SELECT * FROM loans WHERE lead_id = ? ORDER BY id DESC LIMIT 1", [$leadId]);
    }
}
