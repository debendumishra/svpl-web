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
// 3. ADMIN / OPERATIONS / ACCOUNTS ROUTES
// ==========================================
Router::group(['middleware' => [AuthMiddleware::class, new RoleMiddleware('SUPER_ADMIN', 'ADMIN', 'ACCOUNTS', 'OPERATIONS')]], function() {
    Router::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Router::get('/admin/advisors', [AdminController::class, 'advisors']);
    Router::get('/admin/customers', [AdminController::class, 'customers']);
    Router::get('/admin/leads', [AdminController::class, 'leads']);
    Router::get('/admin/leads/{id}', [AdminController::class, 'leadDetail']);
    Router::get('/admin/lead/{id}', [AdminController::class, 'leadDetail']);
    Router::get('/admin/network-tree', [AdminController::class, 'networkTree']);
    Router::get('/admin/commissions', [AdminController::class, 'commissions']);
    Router::get('/admin/payments', [AdminController::class, 'payments']);
    Router::post('/admin/payments/confirm', [AdminController::class, 'confirmPayment']);
    Router::post('/admin/payments/reject', [AdminController::class, 'rejectPayment']);
    Router::get('/admin/dispatches', [AdminController::class, 'dispatches']);
    Router::post('/admin/dispatches/create', [AdminController::class, 'createDispatch']);
    Router::post('/admin/dispatches/update-status', [AdminController::class, 'updateDispatchStatus']);
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
