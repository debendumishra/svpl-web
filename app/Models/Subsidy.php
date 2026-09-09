<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Subsidy Model - PM Surya Ghar DBT Claims
 */

namespace App\Models;

use App\Helpers\Database;

class Subsidy
{
    public static function createOrUpdate(array $data): int
    {
        $existing = Database::fetchOne("SELECT id FROM subsidies WHERE lead_id = ?", [$data['lead_id']]);
        if ($existing) {
            $sql = "UPDATE subsidies SET 
                        application_number = ?, claimed_amount = ?, approved_amount = ?,
                        disbursed_amount = ?, dbt_reference_number = ?, disbursement_date = ?,
                        status = ?, remarks = ?, updated_at = NOW()
                    WHERE id = ?";
            Database::execute($sql, [
                $data['application_number'] ?? null,
                $data['claimed_amount'] ?? 0,
                $data['approved_amount'] ?? 0,
                $data['disbursed_amount'] ?? 0,
                $data['dbt_reference_number'] ?? null,
                $data['disbursement_date'] ?? null,
                $data['status'] ?? 'Claimed',
                $data['remarks'] ?? null,
                $existing['id'],
            ]);
            return (int) $existing['id'];
        }

        $sql = "INSERT INTO subsidies (
                    lead_id, application_number, claimed_amount, approved_amount,
                    disbursed_amount, dbt_reference_number, disbursement_date,
                    status, remarks, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        Database::query($sql, [
            $data['lead_id'],
            $data['application_number'] ?? ('PMSG-DBT-' . time()),
            $data['claimed_amount'] ?? 0,
            $data['approved_amount'] ?? 0,
            $data['disbursed_amount'] ?? 0,
            $data['dbt_reference_number'] ?? null,
            $data['disbursement_date'] ?? null,
            $data['status'] ?? 'Claimed',
            $data['remarks'] ?? null,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function findByLeadId(int $leadId): ?array
    {
        return Database::fetchOne("SELECT * FROM subsidies WHERE lead_id = ? ORDER BY id DESC LIMIT 1", [$leadId]);
    }
}
