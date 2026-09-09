<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Admin Controller - Executive Dashboard, Advisors, Customers, Leads, Commissions, Settings & Audits
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Database;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Commission;
use App\Models\PackageDispatch;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Quotation;
use App\Models\JEReport;
use App\Models\Loan;
use App\Models\Subsidy;

class AdminController
{
    public function dashboard(): void
    {
        // Platform Metric Statistics
        $totalAdvisors = Database::fetchOne("SELECT COUNT(*) as cnt FROM advisors")['cnt'] ?? 0;
        $totalCustomers = Database::fetchOne("SELECT COUNT(*) as cnt FROM customers")['cnt'] ?? 0;
        $totalLeads = Database::fetchOne("SELECT COUNT(*) as cnt FROM leads")['cnt'] ?? 0;
        $activeInstallations = Database::fetchOne("SELECT COUNT(*) as cnt FROM leads WHERE stage IN ('INSTALLATION_COMMENCED', 'INSTALLATION_COMPLETED')")['cnt'] ?? 0;
        $totalCommissions = Database::fetchOne("SELECT COALESCE(SUM(commission_amount), 0) as total FROM commissions")['total'] ?? 0;
        $totalSubsidies = Database::fetchOne("SELECT COALESCE(SUM(subsidy_amount), 0) as total FROM leads WHERE stage = 'SUBSIDY_RECEIVED'")['total'] ?? 0;

        // Stage breakdown
        $stageStats = Database::fetchAll("SELECT stage, COUNT(*) as count FROM leads GROUP BY stage");

        // Recent Leads & Advisors
        $recentLeads = Lead::getAll(10);
        $recentAdvisors = Advisor::getAll(10);

        Response::view('admin/dashboard', [
            'pageTitle' => 'Executive Dashboard — SVPL Admin',
            'totalAdvisors' => (int) $totalAdvisors,
            'totalCustomers' => (int) $totalCustomers,
            'totalLeads' => (int) $totalLeads,
            'activeInstallations' => (int) $activeInstallations,
            'totalCommissions' => (float) $totalCommissions,
            'totalSubsidies' => (float) $totalSubsidies,
            'stageStats' => $stageStats,
            'recentLeads' => $recentLeads,
            'recentAdvisors' => $recentAdvisors,
        ]);
    }

    public function advisors(): void
    {
        $search = $_GET['q'] ?? null;
        $advisors = Advisor::getAll(100, 0, $search);
        Response::view('admin/advisors', [
            'pageTitle' => 'Advisor Management — SVPL Admin',
            'advisors' => $advisors,
            'search' => $search,
        ]);
    }

    public function customers(): void
    {
        $search = $_GET['q'] ?? null;
        $customers = Customer::getAll(100, 0, $search);
        Response::view('admin/customers', [
            'pageTitle' => 'Customer Registry — SVPL Admin',
            'customers' => $customers,
            'search' => $search,
        ]);
    }

    public function leads(): void
    {
        $stage = $_GET['stage'] ?? null;
        $search = $_GET['q'] ?? null;
        $leads = Lead::getAll(100, 0, $stage, $search);

        Response::view('admin/leads', [
            'pageTitle' => 'Lead Pipeline — SVPL Admin',
            'leads' => $leads,
            'currentStage' => $stage,
            'search' => $search,
        ]);
    }

    public function leadDetail(string $id): void
    {
        $lead = Lead::findById((int) $id);
        if (!$lead) {
            Response::notFound("Lead record #{$id} not found.");
            return;
        }

        $documents = Document::getByLeadId((int) $id);
        $history = Lead::getHistory((int) $id);
        $quotation = Quotation::findByLeadId((int) $id);
        $jeReport = JEReport::findByLeadId((int) $id);
        $loan = Loan::findByLeadId((int) $id);
        $subsidy = Subsidy::findByLeadId((int) $id);

        Response::view('admin/lead_detail', [
            'pageTitle' => "Lead Details: {$lead['lead_code']} — SVPL",
            'lead' => $lead,
            'documents' => $documents,
            'history' => $history,
            'quotation' => $quotation,
            'jeReport' => $jeReport,
            'loan' => $loan,
            'subsidy' => $subsidy,
        ]);
    }

    public function networkTree(): void
    {
        $rootId = (int) ($_GET['root_id'] ?? 1);
        $rootAdvisor = Advisor::findById($rootId);
        if (!$rootAdvisor) {
            $rootAdvisor = Advisor::findById(1);
        }

        Response::view('admin/network_tree', [
            'pageTitle' => 'Multi-Level Network Genealogy Visualizer — SVPL',
            'rootAdvisor' => $rootAdvisor,
        ]);
    }

    public function commissions(): void
    {
        $commissions = Commission::getAll(100);
        $slabs = Commission::getPlanSlabs();

        Response::view('admin/commissions', [
            'pageTitle' => 'Commission & Hierarchy Payouts — SVPL Admin',
            'commissions' => $commissions,
            'slabs' => $slabs,
        ]);
    }

    public function payments(): void
    {
        $transactions = Database::fetchAll("SELECT wt.*, u.full_name, u.role, u.mobile FROM wallet_transactions wt JOIN users u ON wt.user_id = u.id ORDER BY wt.id DESC LIMIT 100");
        Response::view('admin/payments', [
            'pageTitle' => 'Wallet Balances & Payments — SVPL Admin',
            'transactions' => $transactions,
        ]);
    }

    public function dispatches(): void
    {
        $dispatches = PackageDispatch::getAll(100);
        Response::view('admin/dispatches', [
            'pageTitle' => 'Solar Equipment & Kit Dispatches — SVPL Admin',
            'dispatches' => $dispatches,
        ]);
    }

    public function reports(): void
    {
        Response::view('admin/reports', [
            'pageTitle' => 'Analytics & Export Reports — SVPL Admin',
        ]);
    }

    public function settings(): void
    {
        $settings = Setting::getAll();
        Response::view('admin/settings', [
            'pageTitle' => 'System Settings & Business Rules — SVPL Admin',
            'settings' => $settings,
        ]);
    }

    public function updateSettings(): void
    {
        foreach ($_POST as $key => $val) {
            if ($key !== '_csrf') {
                Setting::set($key, (string)$val);
            }
        }
        Response::redirect('/admin/settings?saved=1');
    }

    public function auditLogs(): void
    {
        $logs = AuditLog::getRecent(100);
        Response::view('admin/audit_logs', [
            'pageTitle' => 'Security Audit Logs — SVPL Admin',
            'logs' => $logs,
        ]);
    }
}
