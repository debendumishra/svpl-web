<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Mail Service - Transactional & System Email Notifications
 */

namespace App\Services;

use App\Models\Setting;

class MailService
{
    /**
     * Send email using PHP mail() with proper HTML headers and fallback
     */
    public static function send(string $toEmail, string $subject, string $htmlBody, string $textBody = ''): bool
    {
        $toEmail = trim($toEmail);
        if (empty($toEmail) || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $fromName = company_name();
        $fromEmail = (string) Setting::get('support_email', 'noreply@suryavistaara.com');
        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $fromEmail = 'noreply@suryavistaara.com';
        }

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . sprintf('=?UTF-8?B?%s?=', base64_encode($fromName)) . " <{$fromEmail}>",
            'Reply-To: ' . $fromEmail,
            'X-Mailer: PHP/' . phpversion(),
            'X-Company: ' . company_short_name(),
        ];

        $headersStr = implode("\r\n", $headers);

        try {
            // Send email via PHP standard mail transport
            $sent = @mail($toEmail, '=?UTF-8?B?' . base64_encode($subject) . '?=', $htmlBody, $headersStr);
            return (bool) $sent;
        } catch (\Throwable $e) {
            error_log("SVPL MailService Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send login credentials to newly registered advisor
     */
    public static function sendAdvisorCredentials(
        string $email,
        string $advisorName,
        string $advisorCode,
        string $mobile,
        string $password,
        string $referralCode = ''
    ): bool {
        if (empty($email)) {
            return false;
        }

        $companyName = company_name();
        $shortName = company_short_name();
        $loginUrl = url('/login');
        $regFee = number_format(advisor_joining_fee());
        $supportPhone = company_phone();
        $supportEmail = company_email();

        $subject = "Welcome to {$companyName} — Your Advisor Login Credentials";

        $html = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$subject}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; margin: 0; padding: 20px; color: #1e293b; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        .header { background: #0B2545; padding: 28px 24px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 22px; font-weight: 700; }
        .header p { margin: 6px 0 0 0; color: #94a3b8; font-size: 13px; }
        .badge { display: inline-block; background: #F59E0B; color: #000; font-weight: 700; font-size: 11px; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; margin-bottom: 8px; }
        .content { padding: 30px 24px; line-height: 1.6; }
        .welcome-title { font-size: 18px; font-weight: 700; color: #0B2545; margin-bottom: 12px; }
        .credentials-card { background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #F59E0B; border-radius: 8px; padding: 18px; margin: 20px 0; }
        .cred-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e2e8f0; font-size: 14px; }
        .cred-row:last-child { border-bottom: none; }
        .cred-label { color: #64748b; font-weight: 600; }
        .cred-val { font-family: monospace; font-weight: 700; color: #0B2545; }
        .btn-login { display: block; width: 220px; margin: 24px auto; background: #0B2545; color: #ffffff !important; text-decoration: none; text-align: center; padding: 12px 20px; border-radius: 8px; font-weight: 700; font-size: 15px; }
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 14px; font-size: 13px; color: #1e40af; margin-top: 20px; }
        .footer { background: #f8fafc; padding: 20px 24px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="badge">SOLAR ADVISOR NETWORK</span>
            <h1>{$companyName}</h1>
            <p>PM Surya Ghar Odisha Rooftop Solar Partner Network</p>
        </div>
        <div class="content">
            <div class="welcome-title">Welcome to the Network, {$advisorName}!</div>
            <p>Your free Solar Advisor registration has been successfully created. You can now log in immediately to access your advisor portal, view your referral link, and start building your 9-level solar downline network.</p>

            <div class="credentials-card">
                <div class="cred-row">
                    <span class="cred-label">Advisor ID / User ID:</span>
                    <span class="cred-val">{$advisorCode}</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Registered Mobile:</span>
                    <span class="cred-val">{$mobile}</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Login Email:</span>
                    <span class="cred-val">{$email}</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Account Password:</span>
                    <span class="cred-val">{$password}</span>
                </div>
                <div class="cred-row">
                    <span class="cred-label">Your Referral Code:</span>
                    <span class="cred-val">{$referralCode}</span>
                </div>
            </div>

            <a href="{$loginUrl}" class="btn-login">Log In to Advisor Portal</a>

            <div class="info-box">
                <strong>Next Steps to Activate Customer Registration:</strong><br>
                1. <strong>Log In:</strong> Use your Advisor ID, Mobile, or Email and the password above.<br>
                2. <strong>Build Your Network (Free):</strong> Share your referral link to onboard new advisors into your downline.<br>
                3. <strong>Complete Registration Fee (₹{$regFee}):</strong> Pay the one-time registration fee inside your dashboard and submit your UTR to unlock Customer Registrations and earn project commissions.
            </div>
        </div>
        <div class="footer">
            <p>For support, reach out to {$supportEmail} or call {$supportPhone}.</p>
            <p>&copy; 2026 {$companyName}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;

        return self::send($email, $subject, $html);
    }
}
