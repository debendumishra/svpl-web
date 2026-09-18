<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Lead Pipeline Service - 10 Stage Progression Workflow
 */

namespace App\Services;

use App\Models\Lead;
use App\Models\AuditLog;
use App\Services\CommissionEngine;
use App\Services\QualificationService;

class LeadPipelineService
{
    const STAGES = [
        'REGISTRATION'              => '1. Customer Registered',
        'DOCUMENTS'                 => '2. Documents Uploaded & Verified',
        'GOVT_PORTAL'               => '3. PM Surya Ghar Portal Submitted',
        'LOAN_APPLIED'              => '4. Bank Loan Applied',
        'LOAN_SANCTIONED'           => '5. Bank Loan Sanctioned',
        'INSTRUMENT_DESPATCHED'     => '6. Instrument Despatched',
        'INSTALLATION_COMMENCED'    => '7. Solar Installation Commenced',
        'INSTALLATION_COMPLETED'    => '8. Solar Installation Completed',
        'JE_REPORT'                 => '9. DISCOM JE Inspection Completed',
        'NET_METER'                 => '10. Net Meter Installed',
        'INTIMATION_TO_MMG'         => '11. Intimation to MMG',
        'MMG_METER_REPORT'          => '12. MMG Meter Change Report Completed',
        'BANK_SECOND_INSTALLMENT'   => '13. Bank Second Installment Released',
        'SUBSIDY_APPLIED'           => '14. Central Subsidy Applied (DBT)',
        'SUBSIDY_RECEIVED'          => '15. Central Subsidy Disbursed & Received',
    ];

    public static function advanceStage(int $leadId, string $newStage, string $status, ?string $notes = null): array
    {
        $lead = Lead::findById($leadId);
        if (!$lead) {
            return ['status' => false, 'message' => 'Lead not found'];
        }

        Lead::updateStage($leadId, $newStage, $status, $notes);
        AuditLog::log(null, 'LEAD_STAGE_UPDATED', 'LEAD', $leadId, "Stage advanced to {$newStage} ({$status})");

        // When installation is completed or subsidy received, process commissions and evaluate advisor qualification
        if ($newStage === 'INSTALLATION_COMPLETED' || $newStage === 'SUBSIDY_RECEIVED') {
            CommissionEngine::processLeadCommission($lead);
            if (!empty($lead['advisor_id'])) {
                QualificationService::checkAndUpgrade((int) $lead['advisor_id']);
            }
        }

        return ['status' => true, 'message' => "Lead stage advanced to {$newStage}"];
    }
}
