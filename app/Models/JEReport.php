<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Junior Engineer (JE) Site Inspection Report Model
 */

namespace App\Models;

use App\Helpers\Database;

class JEReport
{
    public static function create(array $data): int
    {
        $sql = "INSERT INTO je_reports (
                    lead_id, report_number, inspecting_officer_name, officer_designation,
                    inspection_date, discom_name, sanctioned_capacity_kw, installed_capacity_kw,
                    solar_meter_number, net_meter_tested, earthing_status,
                    overall_inspection_status, remarks, created_at
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?,
                    ?, ?, NOW()
                )";

        Database::query($sql, [
            $data['lead_id'],
            $data['report_number'] ?? ('JE-REP-' . time()),
            $data['inspecting_officer_name'] ?? 'DISCOM Junior Engineer',
            $data['officer_designation'] ?? 'Junior Engineer (Electrical)',
            $data['inspection_date'] ?? date('Y-m-d'),
            $data['discom_name'] ?? 'TPCODL',
            $data['sanctioned_capacity_kw'] ?? 2.0,
            $data['installed_capacity_kw'] ?? 2.0,
            $data['solar_meter_number'] ?? null,
            $data['net_meter_tested'] ?? 1,
            $data['earthing_status'] ?? 'Compliant',
            $data['overall_inspection_status'] ?? 'Approved',
            $data['remarks'] ?? 'Site inspection successfully verified for PM Surya Ghar grid synchronization.',
        ]);

        return (int) Database::lastInsertId();
    }

    public static function findByLeadId(int $leadId): ?array
    {
        return Database::fetchOne("SELECT * FROM je_reports WHERE lead_id = ? ORDER BY id DESC LIMIT 1", [$leadId]);
    }
}
