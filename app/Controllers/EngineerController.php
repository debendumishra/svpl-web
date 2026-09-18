<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Solar Field Engineer Portal Controller
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Models\Engineer;
use App\Models\PackageDispatch;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use App\Models\AuditLog;

class EngineerController
{
    private function authEngineer(): array
    {
        $user = AuthService::user();
        if (!$user || !in_array($user['role'], ['ENGINEER', 'ADMIN', 'SUPER_ADMIN', 'OPERATIONS'])) {
            Response::redirect('/login');
            exit;
        }

        // Fetch corresponding engineer record
        $engineer = Engineer::findByUserId((int)$user['id']);
        if (!$engineer && $user['role'] === 'ENGINEER') {
            // Check if engineer by mobile / email
            $allEngs = Engineer::getAll();
            foreach ($allEngs as $e) {
                if ($e['email'] === $user['email'] || $e['mobile'] === $user['mobile']) {
                    $engineer = $e;
                    break;
                }
            }
        }

        return [
            'user' => $user,
            'engineer' => $engineer
        ];
    }

    public function dashboard(): void
    {
        $auth = $this->authEngineer();
        $user = $auth['user'];
        $engineer = $auth['engineer'];

        $engineerId = (int)($engineer['id'] ?? 0);
        $dispatches = $engineerId > 0 ? Engineer::getAssignedDispatches($engineerId) : PackageDispatch::getAll();

        // Calculate statistics
        $stats = [
            'total_assigned' => count($dispatches),
            'in_transit' => 0,
            'acknowledged' => 0,
            'installation_active' => 0,
            'completed' => 0,
        ];

        foreach ($dispatches as $d) {
            $status = $d['status'] ?? '';
            $stage = $d['lead_stage'] ?? '';
            $ack = (int)($d['customer_acknowledged'] ?? 0);

            if ($ack === 1) {
                $stats['acknowledged']++;
            }
            if ($status === 'In Transit' || $status === 'Dispatched') {
                $stats['in_transit']++;
            }
            if ($stage === 'INSTALLATION_COMMENCED' || $status === 'Out for Delivery') {
                $stats['installation_active']++;
            }
            if ($stage === 'INSTALLATION_COMPLETED' || $stage === 'JE_REPORT' || $stage === 'SUBSIDY_APPLIED' || $status === 'Delivered') {
                $stats['completed']++;
            }
        }

        Response::view('engineer/dashboard', [
            'pageTitle' => 'Solar Engineer Command Center — SVPL',
            'user' => $user,
            'engineer' => $engineer,
            'dispatches' => $dispatches,
            'stats' => $stats,
            'recentDispatches' => array_slice($dispatches, 0, 10),
        ], 'engineer');
    }

    public function installations(): void
    {
        $auth = $this->authEngineer();
        $user = $auth['user'];
        $engineer = $auth['engineer'];

        $engineerId = (int)($engineer['id'] ?? 0);
        $dispatches = $engineerId > 0 ? Engineer::getAssignedDispatches($engineerId) : PackageDispatch::getAll();

        $search = trim($_GET['search'] ?? '');
        $statusFilter = trim($_GET['status'] ?? '');

        if ($search !== '' || $statusFilter !== '') {
            $dispatches = array_filter($dispatches, function($d) use ($search, $statusFilter) {
                $match = true;
                if ($search !== '') {
                    $haystack = strtolower(
                        ($d['customer_name'] ?? '') . ' ' .
                        ($d['cust_first'] ?? '') . ' ' .
                        ($d['cust_last'] ?? '') . ' ' .
                        ($d['cust_mobile'] ?? '') . ' ' .
                        ($d['cust_district'] ?? '') . ' ' .
                        ($d['tracking_number'] ?? '') . ' ' .
                        ($d['vehicle_number'] ?? '')
                    );
                    $match = strpos($haystack, strtolower($search)) !== false;
                }
                if ($match && $statusFilter !== '') {
                    $match = ($d['status'] ?? '') === $statusFilter || ($d['lead_stage'] ?? '') === $statusFilter;
                }
                return $match;
            });
        }

        Response::view('engineer/installations', [
            'pageTitle' => 'Assigned Solar Installations — SVPL Engineer',
            'user' => $user,
            'engineer' => $engineer,
            'dispatches' => $dispatches,
            'search' => $search,
            'statusFilter' => $statusFilter
        ], 'engineer');
    }

    public function updateStage(): void
    {
        $auth = $this->authEngineer();
        $user = $auth['user'];
        $engineer = $auth['engineer'];

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::redirect('/engineer/dashboard');
            return;
        }

        $dispatchId = (int)($_POST['dispatch_id'] ?? 0);
        $leadId = (int)($_POST['lead_id'] ?? 0);
        $newStage = trim($_POST['stage'] ?? '');
        $notes = trim($_POST['notes'] ?? '');
        $dispatchStatus = trim($_POST['dispatch_status'] ?? '');

        if ($dispatchId <= 0) {
            $_SESSION['error_msg'] = "Invalid dispatch record selected.";
            Response::redirect('/engineer/dashboard');
            return;
        }

        $dispatch = PackageDispatch::findById($dispatchId);
        if (!$dispatch) {
            $_SESSION['error_msg'] = "Dispatch record not found.";
            Response::redirect('/engineer/dashboard');
            return;
        }

        // Update dispatch status if provided
        if ($dispatchStatus !== '') {
            PackageDispatch::updateStatus($dispatchId, $dispatchStatus, ($dispatchStatus === 'Delivered' ? date('Y-m-d') : null), $notes);
        }

        // Advance Lead Stage if applicable
        if ($leadId > 0 && in_array($newStage, ['INSTALLATION_COMMENCED', 'INSTALLATION_COMPLETED', 'JE_REPORT'])) {
            $stageMap = [
                'INSTALLATION_COMMENCED' => 7,
                'INSTALLATION_COMPLETED' => 8,
                'JE_REPORT' => 9
            ];
            $stageNumber = $stageMap[$newStage] ?? 7;
            Lead::updateStage($leadId, $newStage, $stageNumber, ($notes ? ("Engineer Update: " . $notes) : null));
        }

        AuditLog::log(
            $user['id'],
            'ENGINEER_STAGE_UPDATE',
            'package_dispatches',
            $dispatchId,
            "Engineer {$engineer['engineer_code']} updated dispatch status to '{$dispatchStatus}' and lead stage to '{$newStage}'"
        );

        $_SESSION['success_msg'] = "Installation progress successfully recorded!";
        Response::redirect($_SERVER['HTTP_REFERER'] ?? '/engineer/dashboard');
    }

    public function profile(): void
    {
        $auth = $this->authEngineer();
        $user = $auth['user'];
        $engineer = $auth['engineer'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mobile = trim($_POST['mobile'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $qualification = trim($_POST['qualification'] ?? '');
            $experience = (int)($_POST['experience_years'] ?? 0);
            $newPassword = trim($_POST['new_password'] ?? '');

            if ($engineer) {
                Engineer::update((int)$engineer['id'], [
                    'mobile' => $mobile,
                    'email' => $email,
                    'qualification' => $qualification,
                    'experience_years' => $experience,
                ]);
            }

            if (!empty($newPassword) && strlen($newPassword) >= 6) {
                User::updatePassword((int)$user['id'], $newPassword);
                $_SESSION['success_msg'] = "Profile and password updated successfully!";
            } else {
                $_SESSION['success_msg'] = "Profile details updated successfully!";
            }

            Response::redirect('/engineer/profile');
            return;
        }

        Response::view('engineer/profile', [
            'pageTitle' => 'Engineer Profile & Credentials — SVPL',
            'user' => $user,
            'engineer' => $engineer,
        ], 'engineer');
    }

    public function idCard(): void
    {
        $auth = $this->authEngineer();
        $user = $auth['user'];
        $engineer = $auth['engineer'];

        Response::view('engineer/id_card', [
            'pageTitle' => 'Field Engineer Identity Card — SVPL',
            'user' => $user,
            'engineer' => $engineer,
        ], 'blank');
    }
}
