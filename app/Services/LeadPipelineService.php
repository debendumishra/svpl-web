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
        'REGISTRATION'              => 'Customer Registered',
        'DOCUMENTS'                 => 'Documents Uploaded & Verified',
        'GOVT_PORTAL'               => 'PM Surya Ghar Portal Submitted',
        'LOAN_APPLIED'              => 'Bank Loan Applied',
        'LOAN_SANCTIONED'           => 'Bank Loan Sanctioned',
        'INSTALLATION_COMMENCED'    => 'Solar Installation Commenced',
        'INSTALLATION_COMPLETED'    => 'Solar Installation Completed',
        'JE_REPORT'                 => 'DISCOM JE Inspection Completed',
        'SUBSIDY_APPLIED'           => 'Central Subsidy Applied (DBT)',
        'SUBSIDY_RECEIVED'          => 'Central Subsidy Disbursed & Received',
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
