<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Lead Controller - Stage Transitions, JE Reports, Bank Loans, Subsidies
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\LeadPipelineService;
use App\Models\Lead;
use App\Models\JEReport;
use App\Models\Loan;
use App\Models\Subsidy;

class LeadController
{
    public function updateStage(): void
    {
        $leadId = (int) ($_POST['lead_id'] ?? 0);
        $stage = $_POST['stage'] ?? '';
        $status = $_POST['status'] ?? $stage;
        $notes = $_POST['notes'] ?? '';

        if (!$leadId || empty($stage)) {
            Response::json(['status' => false, 'message' => 'Invalid parameters']);
            return;
        }

        $res = LeadPipelineService::advanceStage($leadId, $stage, $status, $notes);
        Response::json($res);
    }

    public function saveJeReport(): void
    {
        $post = $_POST;
        $leadId = (int) ($post['lead_id'] ?? 0);

        if (!$leadId) {
            Response::json(['status' => false, 'message' => 'Invalid Lead ID']);
            return;
        }

        JEReport::create([
            'lead_id' => $leadId,
            'report_number' => 'JE-ODISHA-' . time(),
            'inspecting_officer_name' => $post['officer_name'] ?? 'DISCOM Junior Engineer',
            'officer_designation' => $post['officer_designation'] ?? 'Junior Engineer',
            'inspection_date' => $post['inspection_date'] ?? date('Y-m-d'),
            'discom_name' => $post['discom_name'] ?? 'TPCODL',
            'sanctioned_capacity_kw' => (float) ($post['sanctioned_capacity'] ?? 2.0),
            'installed_capacity_kw' => (float) ($post['installed_capacity'] ?? 2.0),
            'solar_meter_number' => $post['solar_meter_number'] ?? null,
            'net_meter_tested' => 1,
            'overall_inspection_status' => 'Approved',
            'remarks' => $post['remarks'] ?? 'Verified and passed site inspection.',
        ]);

        LeadPipelineService::advanceStage($leadId, 'JE_REPORT', 'DISCOM JE Inspection Completed', 'Inspection report uploaded.');
        Response::json(['status' => true, 'message' => 'JE Report saved successfully.']);
    }

    public function saveLoan(): void
    {
        $post = $_POST;
        $leadId = (int) ($post['lead_id'] ?? 0);

        Loan::createOrUpdate([
            'lead_id' => $leadId,
            'bank_name' => $post['bank_name'] ?? 'State Bank of India',
            'loan_application_number' => $post['loan_app_no'] ?? null,
            'loan_amount' => (float) ($post['loan_amount'] ?? 0),
            'interest_rate' => (float) ($post['interest_rate'] ?? 7.0),
            'tenure_months' => (int) ($post['tenure_months'] ?? 60),
            'emi_amount' => (float) ($post['emi_amount'] ?? 0),
            'status' => $post['status'] ?? 'Sanctioned',
            'remarks' => $post['remarks'] ?? null,
        ]);

        if (($post['status'] ?? '') === 'Sanctioned') {
            LeadPipelineService::advanceStage($leadId, 'LOAN_SANCTIONED', 'Bank Loan Sanctioned', 'Loan sanction received.');
        }

        Response::json(['status' => true, 'message' => 'Loan record updated successfully.']);
    }

    public function saveSubsidy(): void
    {
        $post = $_POST;
        $leadId = (int) ($post['lead_id'] ?? 0);

        Subsidy::createOrUpdate([
            'lead_id' => $leadId,
            'application_number' => $post['app_number'] ?? null,
            'claimed_amount' => (float) ($post['claimed_amount'] ?? 0),
            'approved_amount' => (float) ($post['approved_amount'] ?? 0),
            'disbursed_amount' => (float) ($post['disbursed_amount'] ?? 0),
            'dbt_reference_number' => $post['dbt_ref'] ?? null,
            'disbursement_date' => $post['disbursed_date'] ?? date('Y-m-d'),
            'status' => $post['status'] ?? 'Disbursed',
            'remarks' => $post['remarks'] ?? null,
        ]);

        if (($post['status'] ?? '') === 'Disbursed') {
            LeadPipelineService::advanceStage($leadId, 'SUBSIDY_RECEIVED', 'Subsidy Received', 'Subsidy received in customer account.');
        }

        Response::json(['status' => true, 'message' => 'Subsidy record updated successfully.']);
    }
}
