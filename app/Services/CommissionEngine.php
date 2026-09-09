<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Commission Engine - 9-Level Automated Commission & Bonus Calculation
 */

namespace App\Services;

use App\Models\Advisor;
use App\Models\Genealogy;
use App\Models\Commission;
use App\Models\Wallet;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Helpers\Database;

class CommissionEngine
{
    /**
     * Triggered when a Lead reaches 'INSTALLATION_COMPLETED' or 'SUBSIDY_RECEIVED'
     */
    public static function processLeadCommission(array $lead): array
    {
        $advisorId = (int) ($lead['advisor_id'] ?? 0);
        if (!$advisorId) {
            return ['status' => false, 'message' => 'No direct advisor linked to lead'];
        }

        $directAdvisor = Advisor::findById($advisorId);
        if (!$directAdvisor) {
            return ['status' => false, 'message' => 'Direct advisor not found'];
        }

        $slabs = Database::fetchAll("SELECT * FROM commission_plans ORDER BY level ASC");
        $slabByLevel = [];
        foreach ($slabs as $s) {
            $slabByLevel[(int)$s['level']] = $s;
        }

        $tdsRate = (float) Setting::get('tds_percentage', 5.0);
        $customerBonus = (float) Setting::get('direct_customer_bonus', 500.0);

        $results = [];

        // 1. Process Direct Advisor (Level 1)
        $level1Slab = $slabByLevel[1] ?? ['commission_amount' => 1000.00, 'bonus_amount' => 500.00];
        $l1Gross = (float) $level1Slab['commission_amount'];
        $l1Bonus = $customerBonus;
        $l1Total = $l1Gross + $l1Bonus;
        $l1Tds = round(($l1Total * $tdsRate) / 100, 2);
        $l1Net = $l1Total - $l1Tds;

        $commId = Commission::create([
            'lead_id' => $lead['id'],
            'customer_id' => $lead['customer_id'] ?? null,
            'advisor_id' => $directAdvisor['id'],
            'source_advisor_id' => $directAdvisor['id'],
            'level' => 1,
            'commission_amount' => $l1Gross,
            'bonus_amount' => $l1Bonus,
            'tds_deducted' => $l1Tds,
            'net_amount' => $l1Net,
            'status' => 'APPROVED',
            'calculation_notes' => "Direct sponsor commission for Lead #{$lead['lead_code']}",
        ]);

        // Credit Direct Advisor Wallet
        Wallet::credit(
            (int) $directAdvisor['user_id'],
            $l1Net,
            'COMMISSION',
            "Commission + Bonus for Lead #{$lead['lead_code']}",
            $commId
        );

        $results[] = [
            'advisor_id' => $directAdvisor['id'],
            'level' => 1,
            'net' => $l1Net,
        ];

        // 2. Process Uplines (Levels 2 to 9)
        $uplines = Genealogy::getUplines($directAdvisor['id'], 9);
        foreach ($uplines as $upline) {
            $level = (int) $upline['depth'];
            if ($level < 2 || $level > 9) continue;

            $slab = $slabByLevel[$level] ?? null;
            if (!$slab) continue;

            $gross = (float) $slab['commission_amount'];
            if ($gross <= 0) continue;

            $tds = round(($gross * $tdsRate) / 100, 2);
            $net = $gross - $tds;

            $uCommId = Commission::create([
                'lead_id' => $lead['id'],
                'customer_id' => $lead['customer_id'] ?? null,
                'advisor_id' => $upline['id'],
                'source_advisor_id' => $directAdvisor['id'],
                'level' => $level,
                'commission_amount' => $gross,
                'bonus_amount' => 0,
                'tds_deducted' => $tds,
                'net_amount' => $net,
                'status' => 'APPROVED',
                'calculation_notes' => "Level {$level} upline commission from direct advisor {$directAdvisor['advisor_code']} for Lead #{$lead['lead_code']}",
            ]);

            Wallet::credit(
                (int) $upline['user_id'],
                $net,
                'COMMISSION',
                "Level {$level} upline commission for Lead #{$lead['lead_code']}",
                $uCommId
            );

            $results[] = [
                'advisor_id' => $upline['id'],
                'level' => $level,
                'net' => $net,
            ];
        }

        AuditLog::log(null, 'COMMISSION_PROCESSED', 'LEAD', (int)$lead['id'], "Processed 9-level commissions for Lead #{$lead['lead_code']}");

        return ['status' => true, 'distributions' => $results];
    }
}
