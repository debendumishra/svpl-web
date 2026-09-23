<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * CommissionController - Central Administration for Network Commissions, Settings, Trees, Simulator & Reports
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Helpers\Database;
use App\Services\AuthService;
use App\Services\CommissionRuleService;
use App\Services\CommissionCalculationService;
use App\Services\CommissionApprovalService;
use App\Services\PoolBonusService;
use App\Services\RewardService;
use App\Services\CommissionLedgerService;
use App\Models\Advisor;
use App\Models\Customer;

class CommissionController
{
    /**
     * Commission Management Desk - List, Filter, Batch Actions
     */
    public function index(): void
    {
        $user = AuthService::user();

        // Filters
        $month = isset($_GET['month']) && $_GET['month'] !== '' ? (int)$_GET['month'] : (int)date('n');
        $year = isset($_GET['year']) && $_GET['year'] !== '' ? (int)$_GET['year'] : (int)date('Y');
        $status = !empty($_GET['status']) ? trim($_GET['status']) : null;
        $type = !empty($_GET['type']) ? trim($_GET['type']) : null;
        $level = isset($_GET['level']) && $_GET['level'] !== '' ? (int)$_GET['level'] : null;
        $search = !empty($_GET['search']) ? trim($_GET['search']) : null;

        $where = ["1=1"];
        $params = [];

        if ($month > 0) {
            $where[] = "ct.commission_month = ?";
            $params[] = $month;
        }
        if ($year > 0) {
            $where[] = "ct.commission_year = ?";
            $params[] = $year;
        }
        if ($status) {
            $where[] = "ct.status = ?";
            $params[] = $status;
        }
        if ($type) {
            $where[] = "ct.commission_type = ?";
            $params[] = $type;
        }
        if ($level !== null) {
            $where[] = "ct.level = ?";
            $params[] = $level;
        }
        if ($search) {
            $where[] = "(a.advisor_code LIKE ? OR a.first_name LIKE ? OR a.last_name LIKE ? OR c.customer_code LIKE ? OR c.first_name LIKE ? OR ct.transaction_code LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        $whereClause = implode(" AND ", $where);

        $sql = "SELECT ct.*, 
                       a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile as advisor_mobile,
                       src_a.advisor_code as source_advisor_code, CONCAT(src_a.first_name, ' ', src_a.last_name) as source_advisor_name,
                       c.customer_code, CONCAT(c.first_name, ' ', c.last_name) as customer_name,
                       u.full_name as approved_by_name
                FROM commission_transactions ct
                JOIN advisors a ON ct.advisor_id = a.id
                LEFT JOIN advisors src_a ON ct.source_advisor_id = src_a.id
                LEFT JOIN customers c ON ct.customer_id = c.id
                LEFT JOIN users u ON ct.approved_by = u.id
                WHERE {$whereClause}
                ORDER BY ct.id DESC
                LIMIT 200";

        $transactions = Database::fetchAll($sql, $params);

        // Stats Summary
        $stats = Database::fetchOne(
            "SELECT 
                COALESCE(SUM(CASE WHEN status = 'PENDING' AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as pending_amount,
                COALESCE(SUM(CASE WHEN status IN ('APPROVED', 'CREDITED_TO_WALLET') AND is_reversal = 0 THEN net_amount ELSE 0 END), 0) as approved_amount,
                COALESCE(SUM(CASE WHEN status = 'REJECTED' THEN net_amount ELSE 0 END), 0) as rejected_amount,
                COALESCE(SUM(CASE WHEN is_reversal = 1 THEN gross_amount ELSE 0 END), 0) as reversed_amount,
                COUNT(CASE WHEN status = 'PENDING' AND is_reversal = 0 THEN 1 END) as pending_count,
                COUNT(CASE WHEN status IN ('APPROVED', 'CREDITED_TO_WALLET') AND is_reversal = 0 THEN 1 END) as approved_count
             FROM commission_transactions ct
             WHERE {$whereClause}",
            $params
        );

        $allAdvisors = Database::fetchAll("SELECT id, advisor_code, first_name, last_name FROM advisors WHERE status = 'ACTIVE' ORDER BY first_name ASC");

        Response::view('admin/commissions/index', [
            'pageTitle' => 'Commission Management Desk — SVPL',
            'user' => $user,
            'transactions' => $transactions,
            'stats' => $stats,
            'month' => $month,
            'year' => $year,
            'status' => $status,
            'type' => $type,
            'level' => $level,
            'search' => $search,
            'allAdvisors' => $allAdvisors
        ]);
    }

    /**
     * 12-Tab Settings Control Center
     */
    public function settings(): void
    {
        $user = AuthService::user();

        $joiningSettings = CommissionRuleService::getJoiningSettings();
        $productRules = CommissionRuleService::getAllProductRules();
        $allPackages = Database::fetchAll("SELECT id, package_code, brand, title, capacity_kw, system_type, total_price, estimated_subsidy, net_customer_cost, is_active FROM packages ORDER BY brand ASC, capacity_kw ASC, total_price ASC");
        $uplineQualifications = CommissionRuleService::getUplineQualifications();
        $monthlyBonusSlabs = CommissionRuleService::getMonthlyBonusSlabs();
        $poolBonusRule = CommissionRuleService::getPoolBonusRule();
        $rewardSlabs = CommissionRuleService::getRewardSlabs();

        $currentMonth = (int)date('n');
        $currentYear = (int)date('Y');
        $customerSpecialBonus = CommissionRuleService::getCustomerSpecialBonus($currentMonth, $currentYear);

        Response::view('admin/commissions/settings', [
            'pageTitle' => 'Commission Control Center — Rules & Settings',
            'user' => $user,
            'joiningSettings' => $joiningSettings,
            'productRules' => $productRules,
            'allPackages' => $allPackages,
            'uplineQualifications' => $uplineQualifications,
            'monthlyBonusSlabs' => $monthlyBonusSlabs,
            'poolBonusRule' => $poolBonusRule,
            'rewardSlabs' => $rewardSlabs,
            'customerSpecialBonus' => $customerSpecialBonus,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear
        ]);
    }

    /**
     * POST handler for general / joining settings
     */
    public function updateSettings(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;

        $tab = $_POST['tab'] ?? 'joining';

        if ($tab === 'joining' || $tab === 'wallet' || $tab === 'approval' || $tab === 'tax' || $tab === 'triggers') {
            CommissionRuleService::saveJoiningSettings($_POST, $adminId);
        } elseif ($tab === 'upline_matrix') {
            $rows = [];
            if (!empty($_POST['min_customers']) && is_array($_POST['min_customers'])) {
                foreach ($_POST['min_customers'] as $idx => $minCust) {
                    $rows[] = [
                        'min_personal_customers' => (int)$minCust,
                        'max_eligible_level' => (int)($_POST['max_level'][$idx] ?? 0),
                        'description' => $_POST['description'][$idx] ?? ''
                    ];
                }
                CommissionRuleService::saveUplineQualificationMatrix($rows, $adminId);
            }
        } elseif ($tab === 'monthly_bonus') {
            $slabs = [];
            if (!empty($_POST['min_customers']) && is_array($_POST['min_customers'])) {
                foreach ($_POST['min_customers'] as $idx => $minCust) {
                    $slabs[] = [
                        'min_customers' => (int)$minCust,
                        'max_customers' => !empty($_POST['max_customers'][$idx]) ? (int)$_POST['max_customers'][$idx] : null,
                        'bonus_amount' => (float)($_POST['bonus_amount'][$idx] ?? 0.0),
                        'calculation_mode' => $_POST['calculation_mode'] ?? 'HIGHEST_SLAB'
                    ];
                }
                CommissionRuleService::saveMonthlyBonusSlabs($slabs, $adminId);
            }
        } elseif ($tab === 'pool_bonus') {
            CommissionRuleService::savePoolBonusRule($_POST, $adminId);
        } elseif ($tab === 'rewards') {
            $slabs = [];
            if (!empty($_POST['reward_name']) && is_array($_POST['reward_name'])) {
                foreach ($_POST['reward_name'] as $idx => $name) {
                    $slabs[] = [
                        'reward_name' => $name,
                        'customer_target' => (int)($_POST['customer_target'][$idx] ?? 0),
                        'per_customer_amount' => (float)($_POST['per_customer_amount'][$idx] ?? 0.0),
                        'total_reward_value' => (float)($_POST['total_reward_value'][$idx] ?? 0.0),
                        'reward_type' => $_POST['reward_type'][$idx] ?? 'CASH_OR_PRODUCT',
                        'eligibility_type' => 'LIFETIME',
                        'is_repeatable' => 0
                    ];
                }
                CommissionRuleService::saveRewardSlabs($slabs, $adminId);
            }
        } elseif ($tab === 'customer_bonus') {
            $m = (int)($_POST['bonus_month'] ?? date('n'));
            $y = (int)($_POST['bonus_year'] ?? date('Y'));
            $amt = (float)($_POST['bonus_amount'] ?? 50000.0);
            $max = (float)($_POST['max_allowed_amount'] ?? 85000.0);
            $cond = $_POST['eligibility_conditions'] ?? null;
            CommissionRuleService::saveCustomerSpecialBonus($m, $y, $amt, $max, $cond, $adminId);
        }

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            Response::json(['status' => true, 'message' => 'Settings saved successfully']);
            return;
        }

        Response::redirect('/admin/commissions/settings?tab=' . urlencode($tab) . '&msg=saved');
    }

    /**
     * POST handler to Save or Update a Solar Product Rule
     */
    public function saveProductRule(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $ruleId = CommissionRuleService::saveProductRule($_POST, $adminId);

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            Response::json(['status' => true, 'message' => 'Product commission rule saved successfully', 'rule_id' => $ruleId]);
            return;
        }

        Response::redirect('/admin/commissions/settings?tab=product&msg=rule_saved');
    }

    /**
     * AJAX / POST to Approve a single commission
     */
    public function approve(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $id = (int)($_POST['transaction_id'] ?? 0);

        if ($id <= 0) {
            Response::json(['status' => false, 'message' => 'Invalid transaction ID']);
            return;
        }

        $res = CommissionApprovalService::approveCommission($id, $adminId);
        Response::json($res);
    }

    /**
     * AJAX / POST for Bulk Approval
     */
    public function bulkApprove(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $ids = $_POST['transaction_ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            Response::json(['status' => false, 'message' => 'No transactions selected for approval']);
            return;
        }

        $res = CommissionApprovalService::bulkApprove($ids, $adminId);
        Response::json($res);
    }

    /**
     * AJAX / POST to Reject a commission
     */
    public function reject(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $id = (int)($_POST['transaction_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? 'Rejected by Administrator');

        if ($id <= 0) {
            Response::json(['status' => false, 'message' => 'Invalid transaction ID']);
            return;
        }

        $res = CommissionApprovalService::rejectCommission($id, $reason, $adminId);
        Response::json($res);
    }

    /**
     * AJAX / POST to Hold a commission
     */
    public function hold(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $id = (int)($_POST['transaction_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? 'On hold for verification');

        $res = CommissionApprovalService::holdCommission($id, $reason, $adminId);
        Response::json($res);
    }

    /**
     * AJAX / POST to Reverse a commission
     */
    public function reverse(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $id = (int)($_POST['transaction_id'] ?? 0);
        $reason = trim($_POST['reason'] ?? 'Customer transaction cancelled/refunded');

        if ($id <= 0) {
            Response::json(['status' => false, 'message' => 'Invalid transaction ID']);
            return;
        }

        $res = CommissionLedgerService::reverseCommission($id, $reason, $adminId);
        Response::json($res);
    }

    /**
     * PB1–PB11+ Separate Pool Bonus Tree Visualizer
     */
    public function poolTree(): void
    {
        $user = AuthService::user();
        $poolMembers = PoolBonusService::getPoolTree();
        $nestedTree = PoolBonusService::getNestedPoolTree();
        $poolStats = PoolBonusService::getPoolStatistics();
        $rule = CommissionRuleService::getPoolBonusRule();

        Response::view('admin/commissions/pool_tree', [
            'pageTitle' => 'Pool Bonus Tree (PB1–PB11+) — SVPL Network',
            'user' => $user,
            'poolMembers' => $poolMembers,
            'nestedTree' => $nestedTree,
            'poolStats' => $poolStats,
            'rule' => $rule
        ]);
    }

    /**
     * 9-Level Advisor Genealogy Network Tree Visualizer
     */
    public function advisorTree(): void
    {
        $user = AuthService::user();
        $rootAdvisorId = !empty($_GET['advisor_id']) ? (int)$_GET['advisor_id'] : null;

        if (!$rootAdvisorId) {
            $firstAdvisor = Database::fetchOne("SELECT id FROM advisors WHERE sponsor_id IS NULL OR sponsor_id = 0 ORDER BY id ASC LIMIT 1");
            $rootAdvisorId = $firstAdvisor ? (int)$firstAdvisor['id'] : 1;
        }

        $rootAdvisor = Advisor::findById($rootAdvisorId);
        $downlines = \App\Models\Genealogy::getDownlines($rootAdvisorId, 9);
        $allAdvisors = Database::fetchAll("SELECT id, advisor_code, first_name, last_name FROM advisors ORDER BY first_name ASC");

        Response::view('admin/commissions/advisor_tree', [
            'pageTitle' => 'Advisor 9-Level Network Tree — SVPL',
            'user' => $user,
            'rootAdvisor' => $rootAdvisor,
            'downlines' => $downlines,
            'allAdvisors' => $allAdvisors
        ]);
    }

    /**
     * Commission Simulator Screen
     */
    public function simulator(): void
    {
        $user = AuthService::user();
        $advisors = Database::fetchAll("SELECT id, advisor_code, first_name, last_name, district, (SELECT COUNT(*) FROM customers c WHERE c.advisor_id = advisors.id) as personal_customers FROM advisors WHERE status = 'ACTIVE' ORDER BY first_name ASC");
        $products = CommissionRuleService::getAllProductRules();

        Response::view('admin/commissions/simulator', [
            'pageTitle' => 'Commission Simulator & Audit Preview — SVPL',
            'user' => $user,
            'advisors' => $advisors,
            'products' => $products
        ]);
    }

    /**
     * AJAX Endpoint for Simulator calculation
     */
    public function simulateAjax(): void
    {
        $advisorId = (int)($_POST['advisor_id'] ?? 0);
        $productName = trim($_POST['product_name'] ?? '');
        $capacityKw = !empty($_POST['capacity_kw']) ? (float)$_POST['capacity_kw'] : null;
        $connType = trim($_POST['connection_type'] ?? 'On-Grid');
        $creditDate = !empty($_POST['credit_date']) ? $_POST['credit_date'] : date('Y-m-d');

        if ($advisorId <= 0) {
            Response::json(['status' => false, 'message' => 'Please select a referring advisor']);
            return;
        }

        $result = CommissionCalculationService::simulateCustomerReferral(
            $advisorId,
            $productName,
            $capacityKw,
            $connType,
            $creditDate
        );

        Response::json($result);
    }

    /**
     * Monthly Cycles & Settlement Locking Screen
     */
    public function cycles(): void
    {
        $user = AuthService::user();
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        $summary = CommissionLedgerService::getMonthlyCycleSummary($month, $year);
        $allCycles = Database::fetchAll("SELECT * FROM commission_cycles ORDER BY cycle_year DESC, cycle_month DESC LIMIT 24");

        Response::view('admin/commissions/cycles', [
            'pageTitle' => 'Commission Cycles & Settlement Locking — SVPL',
            'user' => $user,
            'month' => $month,
            'year' => $year,
            'summary' => $summary,
            'allCycles' => $allCycles
        ]);
    }

    /**
     * POST to Lock Month Cycle
     */
    public function lockCycle(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $month = (int)($_POST['month'] ?? date('n'));
        $year = (int)($_POST['year'] ?? date('Y'));

        CommissionLedgerService::lockMonth($month, $year, $adminId);
        Response::redirect("/admin/commissions/cycles?month={$month}&year={$year}&msg=locked");
    }

    /**
     * POST to Unlock Month Cycle
     */
    public function unlockCycle(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $month = (int)($_POST['month'] ?? date('n'));
        $year = (int)($_POST['year'] ?? date('Y'));
        $reason = trim($_POST['reason'] ?? 'Unlocked by Super Admin');

        CommissionLedgerService::unlockMonth($month, $year, $reason, $adminId);
        Response::redirect("/admin/commissions/cycles?month={$month}&year={$year}&msg=unlocked");
    }

    /**
     * Advisor Performance Rewards Management Screen
     */
    public function rewards(): void
    {
        $user = AuthService::user();
        $status = $_GET['status'] ?? null;
        $claims = RewardService::getAllClaims($status);
        $rewardSlabs = CommissionRuleService::getRewardSlabs();

        Response::view('admin/commissions/rewards', [
            'pageTitle' => 'Advisor Lifetime Rewards Desk — SVPL',
            'user' => $user,
            'claims' => $claims,
            'rewardSlabs' => $rewardSlabs,
            'currentStatus' => $status
        ]);
    }

    /**
     * POST to Approve Reward Claim
     */
    public function approveReward(): void
    {
        $user = AuthService::user();
        $adminId = !empty($user['id']) ? (int)$user['id'] : 1;
        $claimId = (int)($_POST['claim_id'] ?? 0);
        $action = $_POST['action_type'] ?? 'CASH_PAYOUT';
        $deliveryRef = $_POST['delivery_ref'] ?? null;

        $res = RewardService::approveClaim($claimId, $action, $deliveryRef, $adminId);
        Response::json($res);
    }

    /**
     * Comprehensive Financial & Ledger Reports Screen
     */
    public function reports(): void
    {
        $user = AuthService::user();
        $reportType = $_GET['report'] ?? 'advisor_earnings';
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        $data = [];
        if ($reportType === 'advisor_earnings') {
            $sql = "SELECT a.id, a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile,
                           COALESCE(SUM(CASE WHEN ct.commission_type = 'JOINING_COMMISSION' AND ct.status IN ('APPROVED', 'CREDITED_TO_WALLET') THEN ct.net_amount ELSE 0 END), 0) as joining_earnings,
                           COALESCE(SUM(CASE WHEN ct.commission_type = 'CUSTOMER_REFERRAL' AND ct.level = 1 AND ct.status IN ('APPROVED', 'CREDITED_TO_WALLET') THEN ct.net_amount ELSE 0 END), 0) as direct_customer_earnings,
                           COALESCE(SUM(CASE WHEN ct.commission_type = 'CUSTOMER_REFERRAL' AND ct.level > 1 AND ct.status IN ('APPROVED', 'CREDITED_TO_WALLET') THEN ct.net_amount ELSE 0 END), 0) as upline_earnings,
                           COALESCE(SUM(CASE WHEN ct.commission_type = 'MONTHLY_SPECIAL_BONUS' AND ct.status IN ('APPROVED', 'CREDITED_TO_WALLET') THEN ct.net_amount ELSE 0 END), 0) as monthly_bonus_earnings,
                           COALESCE(SUM(CASE WHEN ct.commission_type = 'POOL_BONUS' AND ct.status IN ('APPROVED', 'CREDITED_TO_WALLET') THEN ct.net_amount ELSE 0 END), 0) as pool_earnings,
                           COALESCE(SUM(CASE WHEN ct.status IN ('APPROVED', 'CREDITED_TO_WALLET') THEN ct.net_amount ELSE 0 END), 0) as total_approved_earnings,
                           COALESCE(SUM(CASE WHEN ct.status = 'PENDING' THEN ct.net_amount ELSE 0 END), 0) as pending_earnings,
                           w.balance as current_wallet_balance
                    FROM advisors a
                    LEFT JOIN commission_transactions ct ON a.id = ct.advisor_id AND ct.commission_month = ? AND ct.commission_year = ? AND ct.is_reversal = 0
                    LEFT JOIN wallets w ON a.user_id = w.user_id
                    GROUP BY a.id
                    ORDER BY total_approved_earnings DESC";
            $data = Database::fetchAll($sql, [$month, $year]);
        } elseif ($reportType === 'audit_logs') {
            $data = Database::fetchAll("SELECT * FROM commission_audit_logs ORDER BY id DESC LIMIT 200");
        }

        // CSV Export Trigger
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            self::exportCsv($reportType, $data);
            return;
        }

        Response::view('admin/commissions/reports', [
            'pageTitle' => 'Commission & Financial Reports — SVPL',
            'user' => $user,
            'reportType' => $reportType,
            'month' => $month,
            'year' => $year,
            'data' => $data
        ]);
    }

    /**
     * CSV Export Helper
     */
    private static function exportCsv(string $filename, array $data): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$filename}_" . date('Ymd_His') . ".csv");
        $output = fopen('php://output', 'w');

        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit;
    }
}
