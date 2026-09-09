<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Quotation Model
 */

namespace App\Models;

use App\Helpers\Database;

class Quotation
{
    public static function create(array $data): int
    {
        $sql = "INSERT INTO quotations (
                    lead_id, customer_id, quotation_number, system_capacity_kw,
                    total_project_cost, central_subsidy_amount, state_subsidy_amount,
                    net_customer_cost, valid_until, remarks, created_at
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, DATE_ADD(NOW(), INTERVAL 30 DAY), ?, NOW()
                )";

        Database::query($sql, [
            $data['lead_id'],
            $data['customer_id'],
            $data['quotation_number'],
            $data['system_capacity_kw'] ?? 2.0,
            $data['total_project_cost'],
            $data['central_subsidy_amount'] ?? 0,
            $data['state_subsidy_amount'] ?? 0,
            $data['net_customer_cost'],
            $data['remarks'] ?? 'Official PM Surya Ghar Rooftop Solar Proposal',
        ]);

        return (int) Database::lastInsertId();
    }

    public static function findByLeadId(int $leadId): ?array
    {
        return Database::fetchOne("SELECT * FROM quotations WHERE lead_id = ? ORDER BY id DESC LIMIT 1", [$leadId]);
    }

    public static function generateQuotationNumber(): string
    {
        $next = Database::fetchOne("SELECT COUNT(*) as cnt FROM quotations");
        $num = ($next ? (int)$next['cnt'] : 0) + 1001;
        return 'SVPL-QTN-' . date('Ymd') . '-' . $num;
    }
}
