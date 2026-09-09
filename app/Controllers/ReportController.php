<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Report Controller & Location Controller
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Database;
use App\Models\Location;

class ReportController
{
    public function exportCsv(): void
    {
        $type = $_GET['type'] ?? 'leads';
        $filename = 'SVPL_' . ucfirst($type) . '_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $out = fopen('php://output', 'w');

        if ($type === 'advisors') {
            fputcsv($out, ['ID', 'Advisor Code', 'Name', 'Mobile', 'District', 'Block', 'Status', 'Direct Customers', 'Joined Date']);
            $rows = Database::fetchAll("SELECT id, advisor_code, CONCAT(first_name, ' ', last_name) as name, mobile, district, block, status, direct_customer_count, created_at FROM advisors ORDER BY id DESC");
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        } elseif ($type === 'commissions') {
            fputcsv($out, ['ID', 'Advisor ID', 'Lead ID', 'Level', 'Gross Amount', 'Bonus', 'TDS', 'Net Amount', 'Status', 'Date']);
            $rows = Database::fetchAll("SELECT id, advisor_id, lead_id, level, commission_amount, bonus_amount, tds_deducted, net_amount, status, created_at FROM commissions ORDER BY id DESC");
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        } else {
            // Default Leads
            fputcsv($out, ['Lead Code', 'Customer Name', 'Mobile', 'District', 'Capacity (kW)', 'Stage', 'Status', 'Cost (INR)', 'Subsidy (INR)', 'Net Payable (INR)', 'Created At']);
            $rows = Database::fetchAll("SELECT lead_code, CONCAT(first_name, ' ', last_name) as name, mobile, district, proposed_capacity_kw, stage, status, estimated_project_cost, subsidy_amount, customer_payable_amount, created_at FROM leads ORDER BY id DESC");
            foreach ($rows as $r) {
                fputcsv($out, $r);
            }
        }

        fclose($out);
        exit;
    }
}
