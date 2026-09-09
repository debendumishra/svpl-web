<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Advisor Controller - Advisor Portal, Downline Network, Customers, Leads, ID Card & Wallet
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Genealogy;
use App\Models\Wallet;
use App\Models\Commission;
use App\Services\GenealogyService;
use App\Services\DocumentGenerator;

class AdvisorController
{
    public function dashboard(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);

        if (!$advisor) {
            Response::redirect('/login');
            return;
        }

        $wallet = Wallet::getByUserId((int) $user['id']);
        $customers = Customer::getByAdvisorId((int) $advisor['id']);
        $leads = Lead::getByAdvisorId((int) $advisor['id']);
        $networkStats = GenealogyService::getNetworkStats((int) $advisor['id']);
        $recentCommissions = Commission::getByAdvisorId((int) $advisor['id']);

        Response::view('advisor/dashboard', [
            'pageTitle' => 'Advisor Dashboard — Surya Vistaara',
            'advisor' => $advisor,
            'wallet' => $wallet,
            'customers' => $customers,
            'leads' => $leads,
            'networkStats' => $networkStats,
            'recentCommissions' => $recentCommissions,
        ]);
    }

    public function myNetwork(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $downlines = Genealogy::getDownlines((int) $advisor['id'], 9);
        $stats = GenealogyService::getNetworkStats((int) $advisor['id']);

        Response::view('advisor/my_network', [
            'pageTitle' => 'My 9-Level Downline Network — SVPL',
            'advisor' => $advisor,
            'downlines' => $downlines,
            'stats' => $stats,
        ]);
    }

    public function myCustomers(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $customers = Customer::getByAdvisorId((int) $advisor['id']);

        Response::view('advisor/my_customers', [
            'pageTitle' => 'My Customer Installations — SVPL',
            'advisor' => $advisor,
            'customers' => $customers,
        ]);
    }

    public function leads(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $leads = Lead::getByAdvisorId((int) $advisor['id']);

        Response::view('advisor/leads', [
            'pageTitle' => 'My Rooftop Solar Leads — SVPL',
            'advisor' => $advisor,
            'leads' => $leads,
        ]);
    }

    public function wallet(): void
    {
        $user = AuthService::user();
        $wallet = Wallet::getByUserId((int) $user['id']);
        $transactions = Wallet::getTransactions((int) $user['id'], 50);

        Response::view('advisor/wallet', [
            'pageTitle' => 'My Wallet & Earnings — SVPL',
            'wallet' => $wallet,
            'transactions' => $transactions,
        ]);
    }

    public function idCard(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $qrUrl = DocumentGenerator::getQrCodeUrl(DocumentGenerator::getVerificationUrl('ADVISOR', $advisor['referral_code']));

        Response::view('advisor/id_card', [
            'pageTitle' => 'My Official Advisor ID Card — SVPL',
            'advisor' => $advisor,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function qrCode(): void
    {
        $user = AuthService::user();
        $advisor = Advisor::findByUserId((int) $user['id']);
        $refUrl = url('/register-customer?ref=' . $advisor['referral_code']);
        $qrUrl = DocumentGenerator::getQrCodeUrl($refUrl);

        Response::view('advisor/qr_code', [
            'pageTitle' => 'My Customer Referral QR Code — SVPL',
            'advisor' => $advisor,
            'refUrl' => $refUrl,
            'qrUrl' => $qrUrl,
        ]);
    }
}
