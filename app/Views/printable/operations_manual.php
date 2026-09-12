<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India (P) Ltd.
 * Master Corporate Operational Manual (PDF-Ready Print Document)
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Operational Manual — Surya Vistaara (SVPL) & Dhwajja Solar India</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Space Grotesk -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        .font-mono {
            font-family: 'Space Grotesk', monospace;
        }

        /* Screen Toolbar */
        .manual-toolbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #0f2d59;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .btn-action {
            background: #f59e0b;
            color: #0f2d59;
            font-weight: 800;
            padding: 8px 18px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-action:hover {
            background: #d97706;
            color: #ffffff;
            transform: translateY(-1px);
        }
        .btn-outline {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.4);
            padding: 7px 16px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: #ffffff;
            color: #ffffff;
        }

        /* Container Document */
        .manual-wrapper {
            max-width: 960px;
            margin: 32px auto;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .manual-page {
            padding: 56px 48px;
            border-bottom: 2px dashed #cbd5e1;
            position: relative;
        }
        .manual-page:last-child {
            border-bottom: none;
        }

        /* Cover Page */
        .cover-page {
            background: linear-gradient(135deg, #0f2d59 0%, #1e3a8a 60%, #0369a1 100%);
            color: #ffffff;
            min-height: 800px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 64px 48px;
        }
        .cover-badge {
            display: inline-block;
            background: rgba(245, 158, 11, 0.2);
            border: 1px solid #f59e0b;
            color: #fcd34d;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 9999px;
            margin-bottom: 24px;
        }
        .cover-title {
            font-size: 2.8rem;
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
            color: #ffffff;
        }
        .cover-subtitle {
            font-size: 1.25rem;
            font-weight: 500;
            color: #93c5fd;
            max-width: 680px;
            line-height: 1.5;
            margin-bottom: 32px;
        }

        /* Chapter Headings */
        .chapter-header {
            border-bottom: 3px solid #0f2d59;
            padding-bottom: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: baseline;
            justify-content: space-between;
        }
        .chapter-number {
            font-size: 0.85rem;
            font-weight: 800;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .chapter-title {
            font-size: 1.8rem;
            font-weight: 900;
            color: #0f2d59;
            letter-spacing: -0.02em;
        }

        /* Callout Box */
        .callout-box {
            background: #f0fdf4;
            border-left: 4px solid #16a34a;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
            font-size: 0.95rem;
        }
        .callout-warning {
            background: #fffbeb;
            border-left-color: #f59e0b;
        }
        .callout-info {
            background: #f0f9ff;
            border-left-color: #0284c7;
        }

        /* Tables */
        .manual-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9rem;
        }
        .manual-table th {
            background: #0f2d59;
            color: #ffffff;
            padding: 10px 14px;
            text-align: left;
            font-weight: 800;
            font-size: 0.85rem;
            letter-spacing: 0.04em;
        }
        .manual-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }
        .manual-table tr:nth-child(even) {
            background: #f8fafc;
        }

        /* Step Blocks */
        .step-block {
            display: flex;
            gap: 16px;
            margin-bottom: 20px;
        }
        .step-num {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #0f2d59;
            color: #ffffff;
            font-weight: 900;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .step-content {
            flex: 1;
        }
        .step-content h4 {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f2d59;
            margin-bottom: 4px;
        }
        .step-content p {
            color: #475569;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        /* Badge Matrix */
        .badge-pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
        }
        .badge-green { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-blue { background: #e0f2fe; color: #075985; border: 1px solid #7dd3fc; }
        .badge-amber { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

        @media print {
            body {
                background: #ffffff;
                color: #000000;
            }
            .manual-toolbar {
                display: none !important;
            }
            .manual-wrapper {
                max-width: 100%;
                margin: 0;
                box-shadow: none;
                border: none;
                border-radius: 0;
            }
            .manual-page {
                padding: 30mm 20mm;
                page-break-after: always;
                border-bottom: none;
            }
            .cover-page {
                min-height: 100vh;
                page-break-after: always;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Toolbar (Screen Only) -->
    <div class="manual-toolbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="font-weight: 900; font-size: 1.1rem; color: #f59e0b;">
                <i class="bi bi-book-half me-1"></i> SVPL Operations Manual
            </div>
            <span style="font-size: 0.8rem; background: rgba(255,255,255,0.15); padding: 2px 8px; border-radius: 4px;">v2.4 Official</span>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <a href="<?= url('/operations-guide') ?>" class="btn-outline">
                <i class="bi bi-play-circle-fill text-warning"></i> Video & Voice Guide
            </a>
            <a href="<?= url('/login') ?>" class="btn-outline">
                <i class="bi bi-box-arrow-in-right"></i> Login Portal
            </a>
            <button onclick="window.print()" class="btn-action">
                <i class="bi bi-printer-fill"></i> Print / Save as PDF
            </button>
        </div>
    </div>

    <!-- Manual Document Container -->
    <div class="manual-wrapper">

        <!-- ==================== COVER PAGE ==================== -->
        <div class="cover-page">
            <div>
                <span class="cover-badge"><i class="bi bi-shield-fill-check me-1"></i> Official Client & Operations Manual</span>
                <h1 class="cover-title">SURYA VISTAARA<br>& DHWAJJA SOLAR INDIA</h1>
                <p class="cover-subtitle">
                    Standard Operating Procedures (SOP), Administrative Workflows, Back Office Execution, 
                    Advisor 9-Level Network Governance, CR80 ID Card Systems, and Financial Accounting.
                </p>
            </div>

            <div style="background: rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; border: 1px solid rgba(255,255,255,0.15); margin: 40px 0;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 0.9rem;">
                    <div>
                        <span style="color: #93c5fd; display: block; font-size: 0.75rem; text-transform: uppercase;">Company</span>
                        <strong style="color: #ffffff;"><?= htmlspecialchars(company_name()) ?></strong>
                    </div>
                    <div>
                        <span style="color: #93c5fd; display: block; font-size: 0.75rem; text-transform: uppercase;">Scheme Coverage</span>
                        <strong style="color: #ffffff;">PM Surya Ghar Muft Bijli Yojana (All Odisha)</strong>
                    </div>
                    <div>
                        <span style="color: #93c5fd; display: block; font-size: 0.75rem; text-transform: uppercase;">Headquarters</span>
                        <strong style="color: #ffffff;"><?= htmlspecialchars(company_address()) ?></strong>
                    </div>
                    <div>
                        <span style="color: #93c5fd; display: block; font-size: 0.75rem; text-transform: uppercase;">Helpline & Support</span>
                        <strong style="color: #ffffff;"><?= htmlspecialchars(company_phone()) ?> • <?= htmlspecialchars(company_email()) ?></strong>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: flex-end; font-size: 0.85rem; color: #93c5fd; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 16px;">
                <div>Confidential • For Internal & Client Deployment Only</div>
                <div>Document Ref: SVPL-SOP-2026-V2</div>
            </div>
        </div>

        <!-- ==================== TABLE OF CONTENTS ==================== -->
        <div class="manual-page">
            <div class="chapter-header">
                <div>
                    <span class="chapter-number">DOCUMENT OUTLINE</span>
                    <h2 class="chapter-title">Table of Contents</h2>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 0.95rem;">
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 1: Platform Overview & Roles</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">Architecture, Super Admin, BOE, Advisor, Customer logins.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 2: Super Admin & Management</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">System parameters, Fee controls, Dispatches, and MIS.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 3: Back Office Executive (BOE)</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">Queue processing, KYC scrutiny, replacing & requesting files.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 4: Solar Advisor Lifecycle</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">Registration, ₹2,700 UTR verification, 9-level commissions.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 5: 10-Stage Customer Pipeline</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">Lead entry to DISCOM feasibility, loan, install & DBT subsidy.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 6: CR80 PVC ID Card Generator</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">Form, automatic generation, photo compression & QR code scan.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 7: Double-Entry Company Ledger</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">Daily cash register, Party statements, UTR records, and CSVs.</p>
                </div>
                <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <strong style="color: #0f2d59;">Chapter 8: Location Master & Cascading</strong>
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">51,804 Odisha records with AJAX live auto-fill & PIN search.</p>
                </div>
            </div>
        </div>

        <!-- ==================== CHAPTER 1 ==================== -->
        <div class="manual-page">
            <div class="chapter-header">
                <div>
                    <span class="chapter-number">CHAPTER 1</span>
                    <h2 class="chapter-title">Platform Architecture & Role System</h2>
                </div>
            </div>

            <p style="margin-bottom: 16px;">
                The Surya Vistaara platform is a unified cloud enterprise web system engineered to orchestrate solar rooftop installations under the 
                <strong>PM Surya Ghar Muft Bijli Yojana</strong> across Odisha. It serves five distinct stakeholder tiers:
            </p>

            <table class="manual-table">
                <thead>
                    <tr>
                        <th>User Role</th>
                        <th>Login ID Format</th>
                        <th>Primary Capabilities</th>
                        <th>Default Access URL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Super Admin / Admin</strong></td>
                        <td>`admin@suryavistaara.com` / `DSI-HO-101`</td>
                        <td>Full governance, ₹2,700 fee confirmation, 9-level commissions, ledger & settings</td>
                        <td>`/admin/dashboard`</td>
                    </tr>
                    <tr>
                        <td><strong>Manager</strong></td>
                        <td>`manager@suryavistaara.com`</td>
                        <td>Team scrutiny, lead approvals, dispatch creation, ID card management</td>
                        <td>`/manager/dashboard`</td>
                    </tr>
                    <tr>
                        <td><strong>BOE (Back Office Executive)</strong></td>
                        <td>`boe@suryavistaara.com` / `SVPL-BOE-101`</td>
                        <td>Daily customer document verification, stage advancement, customer tracking</td>
                        <td>`/boe/dashboard`</td>
                    </tr>
                    <tr>
                        <td><strong>Solar Advisor</strong></td>
                        <td>`adv.odisha@suryavistaara.com` / `ADV-SVPL-101`</td>
                        <td>Customer registration, direct sponsor tree, commission wallet, ID card & QR</td>
                        <td>`/advisor/dashboard`</td>
                    </tr>
                    <tr>
                        <td><strong>Customer (Citizen)</strong></td>
                        <td>`customer@suryavistaara.com` / `SVPL-CUST-101`</td>
                        <td>Live 10-stage solar installation tracking, quotations, subsidy disbursement ledger</td>
                        <td>`/customer/dashboard`</td>
                    </tr>
                </tbody>
            </table>

            <div class="callout-box callout-info">
                <strong><i class="bi bi-info-circle-fill me-1"></i> Multi-Credential Login Support:</strong>
                All roles can log in seamlessly using either their <strong>Registered Email Address</strong>, <strong>10-digit Mobile Number</strong>, or <strong>Official Unique Identifier</strong> (Employee Code, Advisor Code, or Customer Code) with password protection and security CAPTCHA.
            </div>
        </div>

        <!-- ==================== CHAPTER 2 ==================== -->
        <div class="manual-page">
            <div class="chapter-header">
                <div>
                    <span class="chapter-number">CHAPTER 2</span>
                    <h2 class="chapter-title">Back Office Executive (BOE) Daily SOP</h2>
                </div>
            </div>

            <p style="margin-bottom: 16px;">
                The Back Office Executive (BOE) desk is the operational heartbeat for reviewing customer applications, verifying mandatory documents, and liaising with DISCOMs (TPCODL, TPNODL, TPSODL, TPWODL) and financing banks.
            </p>

            <div class="step-block">
                <div class="step-num">1</div>
                <div class="step-content">
                    <h4>Accessing Daily Queue (`/boe/customers`)</h4>
                    <p>Log in with your BOE credentials. The dashboard shows pending applications color-coded by urgency. Click <strong>"Scrutinize Application"</strong> on any pending lead.</p>
                </div>
            </div>

            <div class="step-block">
                <div class="step-num">2</div>
                <div class="step-content">
                    <h4>Document Inspection Checklist</h4>
                    <p>Verify that the following 6 core digital documents are clear and valid:</p>
                    <ul style="margin: 6px 0 0 18px; color: #475569; font-size: 0.9rem;">
                        <li><strong>Electricity Bill:</strong> Consumer number, tariff category (Domestic), sanctioned load (kW).</li>
                        <li><strong>Aadhaar Card:</strong> Citizen name and address match the applicant profile.</li>
                        <li><strong>PAN Card:</strong> Verified for income tax and bank subsidy credit.</li>
                        <li><strong>Land Patta / Holding Tax Receipt:</strong> Validates rooftop ownership rights.</li>
                        <li><strong>Bank Passbook / Cancelled Cheque:</strong> Required for dual DBT subsidy transfers (₹78,000 Central + ₹60,000 State = ₹1,38,000).</li>
                        <li><strong>Rooftop Site Photo:</strong> Shadow-free area calculation (min 100 sq.ft per kW).</li>
                    </ul>
                </div>
            </div>

            <div class="step-block">
                <div class="step-num">3</div>
                <div class="step-content">
                    <h4>Stage Advancement & Document Actions</h4>
                    <p>If documents are valid, select the next stage from the dropdown and click <strong>"Update Status"</strong>. If a document is blurry or incorrect, use <strong>"Replace Document"</strong> or <strong>"Request Fresh Upload"</strong> to send an instant alert to the sponsoring Advisor.</p>
                </div>
            </div>
        </div>

        <!-- ==================== CHAPTER 3 ==================== -->
        <div class="manual-page">
            <div class="chapter-header">
                <div>
                    <span class="chapter-number">CHAPTER 3</span>
                    <h2 class="chapter-title">10-Stage Customer Solar Pipeline</h2>
                </div>
            </div>

            <p style="margin-bottom: 16px;">
                Every solar installation follows a strict 10-stage milestone pipeline tracked in real time across the Admin, BOE, Advisor, and Customer portals:
            </p>

            <table class="manual-table">
                <thead>
                    <tr>
                        <th>Stage #</th>
                        <th>Milestone Name</th>
                        <th>Key Responsibility</th>
                        <th>Prerequisite Document / Trigger</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>1</strong></td>
                        <td><span class="badge-pill badge-blue">1. Registration</span></td>
                        <td>Advisor / Customer</td>
                        <td>Initial lead entry with consumer bill and location.</td>
                    </tr>
                    <tr>
                        <td><strong>2</strong></td>
                        <td><span class="badge-pill badge-amber">2. KYC & Doc Verification</span></td>
                        <td>BOE Scrutiny Team</td>
                        <td>Electricity Bill, Aadhaar, PAN, Land Patta verified.</td>
                    </tr>
                    <tr>
                        <td><strong>3</strong></td>
                        <td><span class="badge-pill badge-blue">3. Site Feasibility (DISCOM)</span></td>
                        <td>SVPL Solar Engineer</td>
                        <td>Shadow analysis, roof structure & grid transformer capacity.</td>
                    </tr>
                    <tr>
                        <td><strong>4</strong></td>
                        <td><span class="badge-pill badge-amber">4. Bank Loan Applied</span></td>
                        <td>Finance Team / Bank</td>
                        <td>PMSG National Portal application submitted for loan.</td>
                    </tr>
                    <tr>
                        <td><strong>5</strong></td>
                        <td><span class="badge-pill badge-blue">5. Loan Sanctioned</span></td>
                        <td>Partner Bank (SBI, PNB, etc.)</td>
                        <td>Bank sanction letter uploaded; down payment confirmed.</td>
                    </tr>
                    <tr>
                        <td><strong>6</strong></td>
                        <td><span class="badge-pill badge-blue">6. Material Dispatched</span></td>
                        <td>SVPL Logistics & Supply</td>
                        <td>Dhwajja 550W Panels, On-Grid Inverter dispatched with UTR.</td>
                    </tr>
                    <tr>
                        <td><strong>7</strong></td>
                        <td><span class="badge-pill badge-green">7. Installation Completed</span></td>
                        <td>Technical Installation Crew</td>
                        <td>Structure mounted, DC/AC cabling, earthing completed. <em>(Triggers Level 1 Commission)</em></td>
                    </tr>
                    <tr>
                        <td><strong>8</strong></td>
                        <td><span class="badge-pill badge-blue">8. Net Meter Inspection</span></td>
                        <td>DISCOM JE / Inspector</td>
                        <td>Bi-directional smart net-meter installed & synchronised.</td>
                    </tr>
                    <tr>
                        <td><strong>9</strong></td>
                        <td><span class="badge-pill badge-blue">9. Grid Synchronised</span></td>
                        <td>DISCOM / SVPL</td>
                        <td>Clean solar power generation begins; meter test report filed.</td>
                    </tr>
                    <tr>
                        <td><strong>10</strong></td>
                        <td><span class="badge-pill badge-green">10. Subsidy Received (Active)</span></td>
                        <td>MNRE / Odisha Govt</td>
                        <td>₹1,38,000 DBT credited to customer bank account; customer status turns 🟢 GREEN.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ==================== CHAPTER 4 ==================== -->
        <div class="manual-page">
            <div class="chapter-header">
                <div>
                    <span class="chapter-number">CHAPTER 4</span>
                    <h2 class="chapter-title">CR80 Duplex Identity Card System</h2>
                </div>
            </div>

            <p style="margin-bottom: 16px;">
                SVPL features a centralized CR80 PVC identity card generation system for Back Office Executives, Certified Solar Advisors, and Custom Staff. All identity cards strictly adhere to the finalized golden reference:
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0;">
                <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    <h4 style="color: #0f2d59; font-weight: 800; margin-bottom: 8px;"><i class="bi bi-card-heading text-primary me-1"></i> Front Side Standards</h4>
                    <ul style="color: #475569; font-size: 0.88rem; line-height: 1.6; margin-left: 16px;">
                        <li><strong>Dimensions:</strong> CR80 Standard 54 × 85.6 mm (320 × 507 px).</li>
                        <li><strong>Header:</strong> Full-width header with company name (16px, 900 wt) and tagline badge.</li>
                        <li><strong>Photo Box:</strong> 120px × 144px with 4-corner brackets and dashed border.</li>
                        <li><strong>Typography:</strong> Name (18px, 900 wt), Role badge (12px), Phone (16px), Address (12px, red icon).</li>
                        <li><strong>Badges:</strong> Code & Blood Group (16px bold values).</li>
                        <li><strong>Banner:</strong> 68px flowing wave gradient with gold pinstripe.</li>
                    </ul>
                </div>
                <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #cbd5e1;">
                    <h4 style="color: #0f2d59; font-weight: 800; margin-bottom: 8px;"><i class="bi bi-qr-code text-success me-1"></i> Back Side Standards</h4>
                    <ul style="color: #475569; font-size: 0.88rem; line-height: 1.6; margin-left: 16px;">
                        <li><strong>Corporate Block:</strong> Head Office address, Helpline, Support Email.</li>
                        <li><strong>Role Badge:</strong> 12px pill badge indicating specific role.</li>
                        <li><strong>Terms Box:</strong> Non-transferable legal advisory.</li>
                        <li><strong>Live Verification QR Code:</strong> 58px × 58px dynamic QR linking to <code>https://suryavistaara.com/verify?type=...</code>.</li>
                        <li><strong>Authorized Signatory:</strong> 55px official company seal and signature aligned horizontally with QR.</li>
                    </ul>
                </div>
            </div>

            <div class="callout-box callout-warning">
                <strong><i class="bi bi-magic me-1"></i> Client Photo Optimizer:</strong>
                When creating or printing a Custom ID Card in <code>/admin/id-cards</code>, the form automatically optimizes uploaded photos on the client side using HTML5 Canvas, ensuring instant uploads in under 200KB without quality loss.
            </div>
        </div>

        <!-- ==================== CHAPTER 5 ==================== -->
        <div class="manual-page">
            <div class="chapter-header">
                <div>
                    <span class="chapter-number">CHAPTER 5</span>
                    <h2 class="chapter-title">Company Account Books & Double-Entry Ledger</h2>
                </div>
            </div>

            <p style="margin-bottom: 16px;">
                The platform contains a financial ledger located at <code>/admin/ledger</code> and <code>/manager/ledger</code> to maintain daily cash flow, party balances, and audit compliance.
            </p>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin: 20px 0;">
                <div style="background: #ecfdf5; padding: 16px; border-radius: 8px; border: 1px solid #a7f3d0;">
                    <strong style="color: #065f46; display: block; font-size: 0.85rem; text-transform: uppercase;">1. Daily Cash & Bank Register</strong>
                    <p style="color: #047857; font-size: 0.85rem; margin-top: 4px;">Chronological journal with Debits, Credits, and continuous running treasury balance.</p>
                </div>
                <div style="background: #eff6ff; padding: 16px; border-radius: 8px; border: 1px solid #bfdbfe;">
                    <strong style="color: #1e40af; display: block; font-size: 0.85rem; text-transform: uppercase;">2. Party-wise Statement</strong>
                    <p style="color: #1d4ed8; font-size: 0.85rem; margin-top: 4px;">Filter complete transaction history and net balance for any Advisor, Customer, or Vendor.</p>
                </div>
                <div style="background: #faf5ff; padding: 16px; border-radius: 8px; border: 1px solid #e9d5ff;">
                    <strong style="color: #6b21a8; display: block; font-size: 0.85rem; text-transform: uppercase;">3. Category Summary & CSV</strong>
                    <p style="color: #7e22ce; font-size: 0.85rem; margin-top: 4px;">Revenue vs expense totals by account head with one-click Excel/CSV export.</p>
                </div>
            </div>

            <h4 style="font-weight: 800; color: #0f2d59; margin-top: 24px;">Automatic Auto-Posting Events:</h4>
            <ul style="margin: 8px 0 0 20px; color: #475569; font-size: 0.92rem; line-height: 1.6;">
                <li><strong>Advisor Onboarding Fee:</strong> When Admin confirms an advisor's ₹2,700 UTR in <code>/admin/payments</code>, a verified <code>RECEIPT</code> voucher is auto-posted to the ledger.</li>
                <li><strong>Commission Disbursements:</strong> Approved withdrawal payouts automatically log a <code>PAYMENT</code> voucher linked to the advisor's ledger.</li>
                <li><strong>Customer Solar Installments:</strong> Milestones and booking deposits are registered under customer account heads.</li>
            </ul>
        </div>

        <!-- ==================== CHAPTER 6 ==================== -->
        <div class="manual-page">
            <div class="chapter-header">
                <div>
                    <span class="chapter-number">CHAPTER 6</span>
                    <h2 class="chapter-title">Location Cascading & Master Database</h2>
                </div>
            </div>

            <p style="margin-bottom: 16px;">
                The platform is pre-loaded with <strong>51,804 records</strong> covering every district, block, gram panchayat, and village across Odisha in the <code>locations</code> database table.
            </p>

            <div class="step-block">
                <div class="step-num"><i class="bi bi-diagram-3"></i></div>
                <div class="step-content">
                    <h4>Dynamic AJAX Cascading</h4>
                    <p>Selecting a <strong>District</strong> automatically triggers an asynchronous AJAX request to fetch all registered <strong>Blocks</strong>. Selecting a block fetches its <strong>Gram Panchayats (GPs)</strong>, which in turn fetches all <strong>Villages and Postal PIN Codes</strong>.</p>
                </div>
            </div>

            <div class="step-block">
                <div class="step-num"><i class="bi bi-geo"></i></div>
                <div class="step-content">
                    <h4>Reverse PIN Code Resolution</h4>
                    <p>Entering any 6-digit Odisha PIN code (e.g. <code>751020</code>, <code>752054</code>) instantly reverse-resolves and auto-selects the corresponding District, Block, and Gram Panchayat without requiring manual searching.</p>
                </div>
            </div>
        </div>

        <!-- ==================== BACK COVER / SIGN OFF ==================== -->
        <div class="manual-page" style="background: #0f2d59; color: #ffffff; text-align: center; padding: 64px 48px;">
            <div style="font-size: 2.2rem; font-weight: 900; color: #f59e0b; margin-bottom: 12px;">
                ☀ SURYA VISTAARA PVT. LTD.
            </div>
            <p style="font-size: 1.1rem; color: #93c5fd; max-width: 600px; margin: 0 auto 32px auto;">
                Empowering Clean Energy & Rooftop Solar Independence Across Odisha
            </p>
            <div style="display: inline-block; background: rgba(255,255,255,0.1); padding: 16px 32px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 0.9rem; color: #cbd5e1;">Corporate Support Desk</div>
                <div style="font-size: 1.2rem; font-weight: 800; color: #ffffff; margin-top: 4px;"><?= htmlspecialchars(company_phone()) ?> • <?= htmlspecialchars(company_email()) ?></div>
                <div style="font-size: 0.85rem; color: #93c5fd; margin-top: 4px;"><?= htmlspecialchars(company_address()) ?></div>
            </div>
            <div style="margin-top: 40px; font-size: 0.8rem; color: #64748b;">
                © <?= date('Y') ?> Surya Vistaara Pvt. Ltd. (SVPL) / Dhwajja Solar India (P) Ltd. All Rights Reserved.
            </div>
        </div>

    </div>
</body>
</html>
