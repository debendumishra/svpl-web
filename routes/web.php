<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Application Route Definitions
 */

use App\Helpers\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Controllers\PublicController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AdvisorController;
use App\Controllers\CustomerController;
use App\Controllers\LeadController;
use App\Controllers\DocumentController;
use App\Controllers\ReportController;
use App\Controllers\LocationController;
use App\Controllers\BoeController;
use App\Controllers\DatabaseController;

// ==========================================
// 1. PUBLIC WEBSITE ROUTES
// ==========================================
Router::get('/', [PublicController::class, 'home']);
Router::get('/about', [PublicController::class, 'about']);
Router::get('/pm-surya-ghar', [PublicController::class, 'pmSuryaGhar']);
Router::get('/solar-solutions', [PublicController::class, 'solarSolutions']);
Router::get('/solar-calculator', [PublicController::class, 'solarSolutions']);
Router::get('/how-it-works', [PublicController::class, 'howItWorks']);
Router::get('/become-advisor', [PublicController::class, 'becomeAdvisor']);
Router::get('/business-opportunity', [PublicController::class, 'becomeAdvisor']);
Router::get('/benefits', [PublicController::class, 'becomeAdvisor']);
Router::get('/faq', [PublicController::class, 'faq']);
Router::get('/contact', [PublicController::class, 'contact']);
Router::post('/contact', [PublicController::class, 'contactSubmit']);
Router::get('/captcha', [\App\Helpers\Captcha::class, 'render']);
Router::get('/api/captcha', [\App\Helpers\Captcha::class, 'render']);
Router::get('/terms', [PublicController::class, 'terms']);
Router::get('/privacy', [PublicController::class, 'privacy']);
Router::get('/disclaimer', [PublicController::class, 'disclaimer']);
Router::get('/verify', [PublicController::class, 'verifyQr']);
Router::get('/install', [PublicController::class, 'install']);
Router::get('/dispatches', function() { \App\Helpers\Response::redirect('/admin/dispatches'); });
Router::get('/commissions', function() { \App\Helpers\Response::redirect('/admin/commissions'); });
Router::get('/payments', function() { \App\Helpers\Response::redirect('/admin/payments'); });
Router::get('/ledger', function() { \App\Helpers\Response::redirect('/admin/ledger'); });
Router::get('/accounts', function() { \App\Helpers\Response::redirect('/admin/ledger'); });

// ==========================================
// 2. AUTHENTICATION & ONBOARDING ROUTES
// ==========================================
Router::get('/login', [AuthController::class, 'showLogin']);
Router::post('/login', [AuthController::class, 'login']);
Router::get('/logout', [AuthController::class, 'logout']);

Router::get('/register-advisor', [AuthController::class, 'showRegisterAdvisor']);
Router::post('/register-advisor', [AuthController::class, 'registerAdvisor']);
Router::get('/register/advisor', [AuthController::class, 'showRegisterAdvisor']);
Router::post('/register/advisor', [AuthController::class, 'registerAdvisor']);

Router::get('/register-customer', [AuthController::class, 'showRegisterCustomer']);
Router::post('/register-customer', [AuthController::class, 'registerCustomer']);
Router::get('/register/customer', [AuthController::class, 'showRegisterCustomer']);
Router::post('/register/customer', [AuthController::class, 'registerCustomer']);

Router::get('/api/validate-referral', [AuthController::class, 'validateReferralCode']);
Router::post('/api/convert-customer', [AuthController::class, 'convertCustomer'], [AuthMiddleware::class]);

// Location Cascading API
Router::get('/api/locations/districts', [LocationController::class, 'getDistricts']);
Router::get('/api/locations/blocks', [LocationController::class, 'getBlocks']);
Router::get('/api/locations/gps', [LocationController::class, 'getGPs']);

// ==========================================
// 3. ADMIN / MANAGER / ACCOUNTS / OPERATIONS ROUTES
// ==========================================
Router::group(['middleware' => [AuthMiddleware::class, new RoleMiddleware('SUPER_ADMIN', 'ADMIN', 'MANAGER', 'ACCOUNTS', 'OPERATIONS')]], function() {
    Router::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Router::get('/manager/dashboard', [AdminController::class, 'dashboard']);
    Router::get('/admin/advisors', [AdminController::class, 'advisors']);
    Router::get('/manager/advisors', [AdminController::class, 'advisors']);
    Router::get('/admin/advisors/{id}/edit', [AdminController::class, 'editAdvisor']);
    Router::post('/admin/advisors/{id}/edit', [AdminController::class, 'updateAdvisor']);
    Router::get('/manager/advisors/{id}/edit', [AdminController::class, 'editAdvisor']);
    Router::post('/manager/advisors/{id}/edit', [AdminController::class, 'updateAdvisor']);

    Router::get('/admin/customers', [AdminController::class, 'customers']);
    Router::get('/manager/customers', [AdminController::class, 'customers']);
    Router::get('/admin/customers/{id}/edit', [AdminController::class, 'editCustomer']);
    Router::post('/admin/customers/{id}/edit', [AdminController::class, 'updateCustomer']);
    Router::get('/manager/customers/{id}/edit', [AdminController::class, 'editCustomer']);
    Router::post('/manager/customers/{id}/edit', [AdminController::class, 'updateCustomer']);
    Router::get('/admin/leads', [AdminController::class, 'leads']);
    Router::get('/manager/leads', [AdminController::class, 'leads']);
    Router::get('/admin/leads/{id}', [AdminController::class, 'leadDetail']);
    Router::get('/admin/lead/{id}', [AdminController::class, 'leadDetail']);
    Router::get('/admin/network-tree', [AdminController::class, 'networkTree']);
    Router::get('/admin/commissions', [AdminController::class, 'commissions']);
    Router::get('/admin/payments', [AdminController::class, 'payments']);
    Router::post('/admin/payments/confirm', [AdminController::class, 'confirmPayment']);
    Router::post('/admin/payments/reject', [AdminController::class, 'rejectPayment']);
    
    // Dispatches & Kits
    Router::get('/admin/dispatches', [AdminController::class, 'dispatches']);
    Router::post('/admin/dispatches/create', [AdminController::class, 'createDispatch']);
    Router::post('/admin/dispatches/update-status', [AdminController::class, 'updateDispatchStatus']);

    // Company Financial Books & Account Ledger
    Router::get('/admin/ledger', [AdminController::class, 'ledger']);
    Router::get('/admin/accounts', [AdminController::class, 'ledger']);
    Router::get('/manager/ledger', [AdminController::class, 'ledger']);
    Router::get('/manager/accounts', [AdminController::class, 'ledger']);
    Router::post('/admin/ledger/create', [AdminController::class, 'createLedgerEntry']);
    Router::get('/admin/ledger/export', [AdminController::class, 'exportLedgerCsv']);

    Router::get('/admin/reports', [AdminController::class, 'reports']);
    Router::get('/admin/settings', [AdminController::class, 'settings']);
    Router::post('/admin/settings', [AdminController::class, 'updateSettings']);
    Router::get('/admin/audit-logs', [AdminController::class, 'auditLogs']);

    // Lead Lifecycle Actions
    Router::post('/admin/leads/update-stage', [LeadController::class, 'updateStage']);
    Router::post('/admin/leads/je-report', [LeadController::class, 'saveJeReport']);
    Router::post('/admin/leads/loan', [LeadController::class, 'saveLoan']);
    Router::post('/admin/leads/subsidy', [LeadController::class, 'saveSubsidy']);

    // Reports Export
    Router::get('/admin/export/csv', [ReportController::class, 'exportCsv']);

    // Back Office Executive (BOE) Management (Admin & Manager)
    Router::get('/admin/boe', [AdminController::class, 'boeManagement']);
    Router::get('/manager/boe', [AdminController::class, 'boeManagement']);
    Router::post('/admin/boe/create', [AdminController::class, 'createBoe']);
    Router::post('/manager/boe/create', [AdminController::class, 'createBoe']);
    Router::get('/admin/boe/toggle/{id}', [AdminController::class, 'toggleBoeStatus']);
    Router::get('/manager/boe/toggle/{id}', [AdminController::class, 'toggleBoeStatus']);
    Router::get('/admin/boe/reports', [AdminController::class, 'boeReports']);
    Router::get('/manager/boe/reports', [AdminController::class, 'boeReports']);
    Router::post('/admin/boe/reassign', [AdminController::class, 'reassignBoe']);
    Router::post('/manager/boe/reassign', [AdminController::class, 'reassignBoe']);

    // Database Maintenance, Backup, Restore, Purge & Demo Seeding (Admin & Manager)
    Router::get('/admin/database', [DatabaseController::class, 'index']);
    Router::get('/manager/database', [DatabaseController::class, 'index']);
    Router::get('/admin/database/backup', [DatabaseController::class, 'backup']);
    Router::get('/manager/database/backup', [DatabaseController::class, 'backup']);
    Router::post('/admin/database/restore', [DatabaseController::class, 'restore']);
    Router::post('/manager/database/restore', [DatabaseController::class, 'restore']);
    Router::post('/admin/database/purge', [DatabaseController::class, 'purgeData']);
    Router::post('/manager/database/purge', [DatabaseController::class, 'purgeData']);
    Router::post('/admin/database/seed-demo', [DatabaseController::class, 'seedDemoData']);
    Router::post('/manager/database/seed-demo', [DatabaseController::class, 'seedDemoData']);

    // Withdrawal Requests Management (Admin & Manager)
    Router::get('/admin/withdrawals', [AdminController::class, 'withdrawals']);
    Router::get('/manager/withdrawals', [AdminController::class, 'withdrawals']);
    Router::post('/admin/withdrawals/approve/{id}', [AdminController::class, 'approveWithdrawal']);
    Router::post('/manager/withdrawals/approve/{id}', [AdminController::class, 'approveWithdrawal']);
    Router::post('/admin/withdrawals/reject/{id}', [AdminController::class, 'rejectWithdrawal']);
    Router::post('/manager/withdrawals/reject/{id}', [AdminController::class, 'rejectWithdrawal']);
});

// ==========================================
// 3B. BACK OFFICE EXECUTIVE (BOE) PORTAL ROUTES
// ==========================================
Router::group(['middleware' => [AuthMiddleware::class, new RoleMiddleware('BOE', 'ADMIN', 'SUPER_ADMIN')]], function() {
    Router::get('/boe/dashboard', [BoeController::class, 'dashboard']);
    Router::get('/boe/customers', [BoeController::class, 'customers']);
    Router::get('/boe/customers/{id}', [BoeController::class, 'customerDetail']);
    Router::post('/boe/update-status', [BoeController::class, 'updateStatus']);
    Router::post('/boe/upload-document', [BoeController::class, 'uploadDocument']);
    Router::post('/boe/replace-document', [BoeController::class, 'replaceDocument']);
    Router::post('/boe/request-document', [BoeController::class, 'requestDocument']);
    Router::get('/boe/reports', [BoeController::class, 'reports']);
});

// ==========================================
// 4. ADVISOR PORTAL ROUTES
// ==========================================
Router::group(['middleware' => [AuthMiddleware::class, new RoleMiddleware('ADVISOR')]], function() {
    Router::get('/advisor/dashboard', [AdvisorController::class, 'dashboard']);
    Router::get('/advisor/register-customer', [AdvisorController::class, 'showRegisterCustomer']);
    Router::post('/advisor/register-customer', [AdvisorController::class, 'registerCustomer']);
    Router::get('/advisor/network', [AdvisorController::class, 'myNetwork']);
    Router::get('/advisor/customers', [AdvisorController::class, 'myCustomers']);
    Router::get('/advisor/leads', [AdvisorController::class, 'leads']);
    Router::get('/advisor/wallet', [AdvisorController::class, 'wallet']);
    Router::post('/advisor/wallet/request-withdrawal', [AdvisorController::class, 'requestWithdrawal']);
    Router::get('/advisor/id-card', [AdvisorController::class, 'idCard']);
    Router::get('/advisor/qr-code', [AdvisorController::class, 'qrCode']);
});

// ==========================================
// 5. CUSTOMER PORTAL ROUTES
// ==========================================
Router::group(['middleware' => [AuthMiddleware::class, new RoleMiddleware('CUSTOMER')]], function() {
    Router::get('/customer/dashboard', [CustomerController::class, 'dashboard']);
    Router::get('/customer/quotation', [CustomerController::class, 'quotation']);
    Router::get('/customer/documents', [CustomerController::class, 'documents']);
    Router::post('/customer/upload-document', [CustomerController::class, 'uploadDocument']);
});

// ==========================================
// 6. PRINTABLE DOCUMENTS & VERIFIED DOWNLOADS
// ==========================================
Router::get('/print/id-card/{id}', [DocumentController::class, 'printIdCard']);
Router::get('/print/appointment/{id}', [DocumentController::class, 'printAppointment']);
Router::get('/print/receipt/{id}', [DocumentController::class, 'printReceipt']);
Router::get('/print/quotation/{id}', [DocumentController::class, 'printQuotation']);
Router::get('/print/je-report/{id}', [DocumentController::class, 'printJeReport']);
Router::get('/document/download', [DocumentController::class, 'download'], [AuthMiddleware::class]);
