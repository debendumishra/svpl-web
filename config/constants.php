<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * System Constants, Business Rules & Universal URL/Asset Helpers
 */

// User Roles
define('ROLE_SUPER_ADMIN', 'SUPER_ADMIN');
define('ROLE_ADMIN', 'ADMIN');
define('ROLE_ACCOUNTS', 'ACCOUNTS');
define('ROLE_OPERATIONS', 'OPERATIONS');
define('ROLE_ADVISOR', 'ADVISOR');
define('ROLE_CUSTOMER', 'CUSTOMER');

// Advisor Qualification Statuses
define('ADVISOR_STATUS_NEW', 'NEW');
define('ADVISOR_STATUS_ACTIVE', 'ACTIVE');
define('ADVISOR_STATUS_QUALIFIED', 'QUALIFIED');
define('ADVISOR_STATUS_PROMOTED', 'PROMOTED');
define('ADVISOR_STATUS_INACTIVE', 'INACTIVE');
define('ADVISOR_STATUS_SUSPENDED', 'SUSPENDED');

// Number of customers required to reach QUALIFIED status
define('DEFAULT_REQUIRED_CUSTOMERS', 3);
define('DEFAULT_NETWORK_LEVELS', 9);
define('DEFAULT_JOINING_FEE', 1500.00);
define('DEFAULT_CUSTOMER_BONUS', 500.00);

// Lead Pipeline Stages (10 standard stages)
define('LEAD_STAGE_REGISTRATION', 'REGISTRATION');
define('LEAD_STAGE_DOCUMENTS', 'DOCUMENTS');
define('LEAD_STAGE_GOVT_PORTAL', 'GOVT_PORTAL');
define('LEAD_STAGE_LOAN_APPLIED', 'LOAN_APPLIED');
define('LEAD_STAGE_LOAN_SANCTIONED', 'LOAN_SANCTIONED');
define('LEAD_STAGE_INSTALLATION_COMMENCED', 'INSTALLATION_COMMENCED');
define('LEAD_STAGE_INSTALLATION_COMPLETED', 'INSTALLATION_COMPLETED');
define('LEAD_STAGE_JE_REPORT', 'JE_REPORT');
define('LEAD_STAGE_SUBSIDY_APPLIED', 'SUBSIDY_APPLIED');
define('LEAD_STAGE_SUBSIDY_RECEIVED', 'SUBSIDY_RECEIVED');

// Lead Overall Statuses
define('LEAD_STATUS_NEW', 'New');
define('LEAD_STATUS_CONTACTED', 'Contacted');
define('LEAD_STATUS_INTERESTED', 'Interested');
define('LEAD_STATUS_DOCS_PENDING', 'Documents Pending');
define('LEAD_STATUS_DOCS_VERIFIED', 'Documents Verified');
define('LEAD_STATUS_APP_SUBMITTED', 'Application Submitted');
define('LEAD_STATUS_LOAN_PROCESSING', 'Loan Processing');
define('LEAD_STATUS_LOAN_SANCTIONED', 'Loan Sanctioned');
define('LEAD_STATUS_INSTALLATION_SCHEDULED', 'Installation Scheduled');
define('LEAD_STATUS_INSTALLATION_IN_PROGRESS', 'Installation in Progress');
define('LEAD_STATUS_INSTALLATION_COMPLETED', 'Installation Completed');
define('LEAD_STATUS_JE_PENDING', 'JE Pending');
define('LEAD_STATUS_JE_COMPLETED', 'JE Completed');
define('LEAD_STATUS_SUBSIDY_APPLIED', 'Subsidy Applied');
define('LEAD_STATUS_SUBSIDY_RECEIVED', 'Subsidy Received');
define('LEAD_STATUS_CLOSED', 'Closed');
define('LEAD_STATUS_REJECTED', 'Rejected');
define('LEAD_STATUS_CANCELLED', 'Cancelled');

// Document Types
define('DOC_TYPE_AADHAAR', 'AADHAAR');
define('DOC_TYPE_PAN', 'PAN');
define('DOC_TYPE_ELECTRICITY_BILL', 'ELECTRICITY_BILL');
define('DOC_TYPE_ROOFTOP_PHOTO', 'ROOFTOP_PHOTO');
define('DOC_TYPE_BANK_PASSBOOK', 'BANK_PASSBOOK');
define('DOC_TYPE_LAND_RECORD', 'LAND_RECORD');
define('DOC_TYPE_PASSPORT_PHOTO', 'PASSPORT_PHOTO');
define('DOC_TYPE_AGREEMENT', 'AGREEMENT');
define('DOC_TYPE_OTHER', 'OTHER');

// Document Verification Statuses
define('DOC_STATUS_PENDING', 'Pending');
define('DOC_STATUS_UPLOADED', 'Uploaded');
define('DOC_STATUS_UNDER_VERIFICATION', 'Under Verification');
define('DOC_STATUS_VERIFIED', 'Verified');
define('DOC_STATUS_REJECTED', 'Rejected');

// Commission Statuses
define('COMM_STATUS_PENDING', 'PENDING');
define('COMM_STATUS_CALCULATED', 'CALCULATED');
define('COMM_STATUS_APPROVED', 'APPROVED');
define('COMM_STATUS_PAYABLE', 'PAYABLE');
define('COMM_STATUS_PAID', 'PAID');
define('COMM_STATUS_REVERSED', 'REVERSED');

// Wallet Transaction Types
define('WALLET_TXN_COMMISSION', 'COMMISSION');
define('WALLET_TXN_BONUS', 'BONUS');
define('WALLET_TXN_WITHDRAWAL', 'WITHDRAWAL');
define('WALLET_TXN_ADJUSTMENT', 'ADJUSTMENT');
define('WALLET_TXN_REVERSAL', 'REVERSAL');

// Package & Dispatch Statuses
define('DISPATCH_STATUS_PENDING', 'Pending');
define('DISPATCH_STATUS_PREPARED', 'Prepared');
define('DISPATCH_STATUS_DISPATCHED', 'Dispatched');
define('DISPATCH_STATUS_IN_TRANSIT', 'In Transit');
define('DISPATCH_STATUS_DELIVERED', 'Delivered');
define('DISPATCH_STATUS_RETURNED', 'Returned');

// Default Odisha DISCOMs
define('ODISHA_DISCOMS', [
    'TPCODL' => 'TP Central Odisha Distribution Limited (TPCODL)',
    'TPNODL' => 'TP Northern Odisha Distribution Limited (TPNODL)',
    'TPSODL' => 'TP Southern Odisha Distribution Limited (TPSODL)',
    'TPWODL' => 'TP Western Odisha Distribution Limited (TPWODL)',
]);

// Dynamic Base Path Resolver for XAMPP Subdirectories
if (!function_exists('base_path_url')) {
    function base_path_url(): string {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        
        // 1. Detect subfolder from REQUEST_URI (e.g. /svpl-web or /SVPL-Web)
        if (preg_match('#^/(svpl[-_]?web)(?:/.*)?$#i', $uri, $matches)) {
            return '/' . $matches[1];
        }

        // 2. Detect from SCRIPT_NAME
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir === '/' || $scriptDir === '.') {
            $scriptDir = '';
        }
        if (substr($scriptDir, -7) === '/public') {
            $scriptDir = substr($scriptDir, 0, -7);
        }

        return rtrim($scriptDir, '/');
    }
}

// Universal URL generator
if (!function_exists('url')) {
    function url(string $path = ''): string {
        $base = base_path_url();
        $cleanPath = '/' . ltrim($path, '/');
        return $base . $cleanPath;
    }
}

// Universal Asset generator
if (!function_exists('asset')) {
    function asset(string $path = ''): string {
        $base = base_path_url();
        $cleanPath = '/' . ltrim($path, '/');
        return $base . $cleanPath;
    }
}

// Universal CSRF Helper Functions
if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        $token = csrf_token();
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars($token) . '"><input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}

