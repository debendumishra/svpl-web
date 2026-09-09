<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Package Dispatch & Kit Fulfillment Model
 */

namespace App\Models;

use App\Helpers\Database;

class PackageDispatch
{
    public static function create(array $data): int
    {
        $sql = "INSERT INTO package_dispatches (
                    lead_id, advisor_id, dispatch_type, tracking_number,
                    courier_partner, dispatch_date, delivery_date,
                    status, items_included, delivery_address, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        Database::query($sql, [
            $data['lead_id'] ?? null,
            $data['advisor_id'] ?? null,
            $data['dispatch_type'] ?? 'SOLAR_EQUIPMENT',
            $data['tracking_number'] ?? ('TRK-' . strtoupper(substr(uniqid(), -8))),
            $data['courier_partner'] ?? 'SVPL Logistics Odisha',
            $data['dispatch_date'] ?? date('Y-m-d'),
            $data['delivery_date'] ?? null,
            $data['status'] ?? 'Dispatched',
            $data['items_included'] ?? 'Solar Panels, Inverter, Mounting Structure, ACDB/DCDB',
            $data['delivery_address'] ?? null,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getAll(int $limit = 50): array
    {
        $sql = "SELECT pd.*, l.lead_code, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name
                FROM package_dispatches pd
                LEFT JOIN leads l ON pd.lead_id = l.id
                LEFT JOIN advisors a ON pd.advisor_id = a.id
                ORDER BY pd.id DESC LIMIT {$limit}";
        return Database::fetchAll($sql);
    }
}
