<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Public Controller - Home, PM Surya Ghar, Solar Calculator, Contact, Verification & Installer
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Setting;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Lead;
use DatabaseSetup;

class PublicController
{
    public function home(): void
    {
        $settings = Setting::getAll();
        Response::view('public/home', [
            'pageTitle' => 'Surya Vistaara Pvt. Ltd. (SVPL) — PM Surya Ghar Odisha',
            'settings' => $settings,
        ]);
    }

    public function about(): void
    {
        Response::view('public/about', [
            'pageTitle' => 'About Us — Surya Vistaara Pvt. Ltd.',
        ]);
    }

    public function pmSuryaGhar(): void
    {
        Response::view('public/pmsuryaghar', [
            'pageTitle' => 'PM Surya Ghar: Muft Bijli Yojana — Odisha',
        ]);
    }

    public function howItWorks(): void
    {
        Response::view('public/how_it_works', [
            'pageTitle' => 'How It Works — SVPL Solar Network',
        ]);
    }

    public function faq(): void
    {
        Response::view('public/faq', [
            'pageTitle' => 'Frequently Asked Questions — SVPL',
        ]);
    }

    public function contact(): void
    {
        Response::view('public/contact', [
            'pageTitle' => 'Contact Us — Surya Vistaara Pvt. Ltd.',
        ]);
    }

    public function contactSubmit(): void
    {
        $post = $_POST;
        $name = trim($post['name'] ?? '');
        $mobile = trim($post['mobile'] ?? '');
        $district = trim($post['district'] ?? '');
        $message = trim($post['message'] ?? '');
        $captcha = trim($post['captcha'] ?? '');

        if (empty($name) || empty($mobile) || empty($message)) {
            Response::view('public/contact', [
                'pageTitle' => 'Contact Us — SVPL',
                'error' => 'Please fill in your Name, Mobile Number, and Message.',
                'post' => $post,
            ]);
            return;
        }

        if (!\App\Helpers\Captcha::verify($captcha)) {
            Response::view('public/contact', [
                'pageTitle' => 'Contact Us — SVPL',
                'error' => 'Invalid or expired Security CAPTCHA code. Please enter the characters shown in the image.',
                'post' => $post,
            ]);
            return;
        }

        // Log inquiry or process message
        \App\Models\AuditLog::log(null, 'CONTACT_INQUIRY', 'CONTACT', null, "Contact query from {$name} ({$mobile}) - {$district}: " . substr($message, 0, 100));

        Response::view('public/contact', [
            'pageTitle' => 'Contact Us — SVPL',
            'success' => 'Thank you! Your message has been received. Our Bhubaneswar support desk (9040999899) will contact you shortly.',
        ]);
    }

    public function terms(): void
    {
        Response::view('public/terms', [
            'pageTitle' => 'Terms & Conditions — SVPL',
        ]);
    }

    public function privacy(): void
    {
        Response::view('public/privacy', [
            'pageTitle' => 'Privacy Policy — SVPL',
        ]);
    }

    public function disclaimer(): void
    {
        Response::view('public/disclaimer', [
            'pageTitle' => 'Disclaimer — SVPL & PM Surya Ghar',
        ]);
    }

    public function verifyQr(): void
    {
        $type = $_GET['type'] ?? '';
        $code = $_GET['code'] ?? '';

        $record = null;
        if ($type === 'ADVISOR') {
            $record = Advisor::findByReferralCode($code);
        } elseif ($type === 'CUSTOMER') {
            $record = Customer::findById((int)$code);
        }

        Response::view('public/verify', [
            'pageTitle' => 'QR Verification — SVPL Portal',
            'type' => $type,
            'code' => $code,
            'record' => $record,
        ]);
    }

    public function install(): void
    {
        require_once dirname(__DIR__, 2) . '/database/setup.php';
        $res = DatabaseSetup::run();
        Response::view('public/install', [
            'pageTitle' => 'Database Installation & Health Check — SVPL',
            'setupResult' => $res,
        ]);
    }
}
