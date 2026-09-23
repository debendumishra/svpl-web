<?php
/**
 * Dhwajja Solar India Pvt. Ltd. / Surya Vistaara Pvt. Ltd.
 * Official 2-Page Solar Brochure / Marketing Leaflet with Personalized Advisor Credentials & Scannable QR
 */

$companyName = "DHWAJJA SOLAR INDIA PVT. LTD.";
$companyGstin = "21AAMCD5948B1ZU";
$companyAddress = "MIG-84, Pokhariput, BDA Colony, Phase-1 Pokhariput, Bhubaneswar, Odisha - 751020";
$companyEmail = "dhwajjasolarsupport@gmail.com";
$companyPhone = "9040999899";

$advName = trim(($advisor['first_name'] ?? '') . ' ' . ($advisor['last_name'] ?? ''));
if (empty($advName)) $advName = $advisor['user_full_name'] ?? 'Authorized Solar Advisor';

$advCode = $advisor['advisor_code'] ?? ('SVPL-ADV-' . $advisor['id']);
$refCode = $advisor['referral_code'] ?? $advCode;
$advMobile = !empty($advisor['mobile']) ? $advisor['mobile'] : (!empty($advisor['user_mobile']) ? $advisor['user_mobile'] : $companyPhone);
$advEmail = !empty($advisor['email']) ? $advisor['email'] : (!empty($advisor['user_email']) ? $advisor['user_email'] : $companyEmail);
$advAddress = $advisor['address_line'] ?? ($advisor['village'] ? $advisor['village'] . ', ' . $advisor['block'] : ($advisor['district'] ?? 'Odisha'));
$advDistrict = $advisor['district'] ?? 'Odisha';
$advPin = $advisor['pincode'] ?? '751024';

// Dynamic QR code for Advisor's direct customer referral / quotation link
$qrLink = url('/register-customer?ref=' . urlencode($refCode));
$qrCodeUrl = \App\Services\DocumentGenerator::getQrCodeUrl($qrLink);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solar Leaflet & Brochure — <?= htmlspecialchars($advName) ?> (<?= htmlspecialchars($advCode) ?>)</title>
    
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-navy: #092C4C;
            --brand-blue: #0266B3;
            --brand-green: #15803D;
            --brand-gold: #F59E0B;
            --brand-orange: #EA580C;
            --brand-dark: #0F172A;
            --brand-bg: #F8FAFC;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #E2E8F0;
            margin: 0;
            padding: 20px 0;
            color: #1E293B;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .leaflet-page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto 25px auto;
            background: #FFFFFF;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-after: always;
        }

        .font-heading {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ------------------ PAGE 1 STYLING ------------------ */
        .p1-header {
            padding: 10px 18px 6px 18px;
            border-bottom: 2px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .p1-logo-title {
            font-size: 22px;
            font-weight: 900;
            color: var(--brand-navy);
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        .p1-tagline {
            font-size: 8.5px;
            letter-spacing: 1.2px;
            font-weight: 700;
            color: var(--brand-blue);
            margin-top: 2px;
        }

        .p1-hero-headline {
            padding: 6px 18px 4px 18px;
            text-align: center;
        }
        .p1-hero-headline h2 {
            font-size: 22px;
            font-weight: 900;
            margin: 0;
            line-height: 1.15;
            letter-spacing: -0.5px;
        }
        .text-green-lead { color: #16A34A; }
        .text-orange-lead { color: #DC2626; }

        .p1-hero-banner {
            margin: 0 16px 8px 16px;
            border-radius: 12px;
            background: linear-gradient(135deg, #092C4C 0%, #0369A1 60%, #0D9488 100%);
            color: #FFFFFF;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(9, 44, 76, 0.2);
        }
        .p1-hero-badge-title {
            background: #FACC15;
            color: #0F172A;
            font-weight: 900;
            font-size: 13px;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .p1-section-title {
            font-size: 13px;
            font-weight: 900;
            text-align: center;
            color: var(--brand-navy);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 4px 0 6px 0;
            position: relative;
        }
        .p1-section-title::before, .p1-section-title::after {
            content: "";
            display: inline-block;
            width: 35px;
            height: 2px;
            background: #CBD5E1;
            vertical-align: middle;
            margin: 0 8px;
        }

        .why-solar-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 6px;
            padding: 0 16px 8px 16px;
            text-align: center;
        }
        .why-solar-item {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            padding: 8px 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .why-solar-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            margin-bottom: 4px;
        }
        .why-solar-label {
            font-size: 8.5px;
            font-weight: 700;
            line-height: 1.15;
            color: #334155;
        }

        /* Financial table */
        .leaflet-table {
            width: calc(100% - 32px);
            margin: 0 16px 6px 16px;
            border: 1px solid #0266B3;
            border-collapse: collapse;
            font-size: 9.5px;
        }
        .leaflet-table th {
            background: #0266B3;
            color: #FFFFFF;
            padding: 5px 6px;
            text-align: center;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 8.5px;
            border: 1px solid #0266B3;
        }
        .leaflet-table td {
            padding: 4px 6px;
            text-align: center;
            border: 1px solid #CBD5E1;
            font-weight: 600;
        }
        .leaflet-table tr:nth-child(even) {
            background: #F8FAFC;
        }

        /* Loan & EMI Box */
        .loan-emi-container {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 8px;
            padding: 0 16px 6px 16px;
        }
        .loan-card {
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            padding: 6px 8px;
            background: #FFFFFF;
            font-size: 9px;
        }
        .loan-header {
            background: #E0F2FE;
            color: #0369A1;
            font-weight: 800;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 9px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        /* Callout Footer */
        .p1-callout-footer {
            background: #092C4C;
            color: #FFFFFF;
            padding: 8px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ------------------ PAGE 2 STYLING ------------------ */
        .p2-top-banner {
            background: #092C4C;
            color: #FFFFFF;
            padding: 10px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .p2-top-title {
            font-size: 16px;
            font-weight: 900;
            color: #FACC15;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .p2-bullet-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 9px;
            line-height: 1.45;
        }
        .p2-bullet-list li::before {
            content: "✔";
            color: #22C55E;
            font-weight: 900;
            margin-right: 5px;
        }

        .p2-mid-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 6px 16px;
        }
        .p2-block-title {
            background: #0266B3;
            color: #FFFFFF;
            font-weight: 800;
            font-size: 10px;
            text-transform: uppercase;
            padding: 4px 8px;
            border-radius: 4px;
            margin-bottom: 6px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .doc-list-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px;
            font-size: 8.5px;
        }
        .doc-item {
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            border-radius: 4px;
            padding: 3px 5px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 600;
        }

        .solutions-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px;
            text-align: center;
        }
        .solution-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            padding: 5px 3px;
            font-size: 8px;
            font-weight: 700;
            color: #1E293B;
        }

        .why-choose-banner {
            background: #092C4C;
            color: #FFFFFF;
            margin: 0 16px 6px 16px;
            border-radius: 8px;
            padding: 8px 12px;
        }
        .why-choose-title {
            font-size: 11px;
            font-weight: 900;
            color: #FACC15;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .why-choose-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 10px;
            font-size: 8.5px;
        }

        .finance-available-bar {
            background: linear-gradient(90deg, #EA580C 0%, #D97706 100%);
            color: #FFFFFF;
            padding: 5px 16px;
            margin: 0 16px 6px 16px;
            border-radius: 6px;
            text-align: center;
            font-weight: 800;
            font-size: 10px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* CONTACT US SECTION WITH DYNAMIC ADVISOR DETAILS */
        .contact-us-section {
            margin: 0 16px 8px 16px;
            border: 2px solid #092C4C;
            border-radius: 10px;
            padding: 8px 12px;
            background: #FFFFFF;
        }
        .contact-us-header {
            font-size: 12px;
            font-weight: 900;
            color: #092C4C;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 6px;
            letter-spacing: 1px;
            border-bottom: 1px solid #E2E8F0;
            padding-bottom: 4px;
        }

        .p2-bottom-ribbon {
            background: #092C4C;
            color: #FACC15;
            font-size: 11px;
            font-weight: 900;
            text-align: center;
            padding: 6px 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        @media print {
            body {
                background: none !important;
                padding: 0 !important;
            }
            .leaflet-page {
                box-shadow: none !important;
                margin: 0 !important;
                width: 100% !important;
                height: 100% !important;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- ACTION CONTROLS (NO PRINT) -->
    <div class="container no-print mb-4 text-center" style="max-width: 210mm;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm border">
            <div class="text-start">
                <span class="badge bg-warning text-dark fw-bold me-2"><i class="bi bi-file-earmark-pdf-fill me-1"></i> A4 PRINTABLE BROCHURE</span>
                <strong class="text-dark">Official 2-Page Leaflet with Personalized Advisor Contact & QR</strong>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold px-3">
                    <i class="bi bi-printer-fill me-1"></i> Print / Save 2-Page PDF
                </button>
                <a href="<?= url('/advisor/dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- ==================== LEAFLET PAGE 1: FRONT ==================== -->
    <div class="leaflet-page">
        <div>
            <!-- Header with Company Logo -->
            <div class="p1-header">
                <div class="d-flex align-items-center gap-2">
                    <div style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); width: 38px; height: 38px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #061528; font-weight: 900; font-size: 1.4rem;">
                        ☀
                    </div>
                    <div>
                        <div class="p1-logo-title font-heading"><?= htmlspecialchars($companyName) ?></div>
                        <div class="p1-tagline font-heading">• CLEAN ENERGY • SMART CHOICE • BRIGHTER TOMORROW •</div>
                    </div>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary px-2 py-1" style="font-size: 8px; font-weight: 800;">ODISHA CHANNEL PARTNER</span>
                </div>
            </div>

            <!-- Big Headline -->
            <div class="p1-hero-headline font-heading">
                <h2>
                    <span class="text-green-lead">GO SOLAR. SAVE MORE.</span><br>
                    <span class="text-orange-lead">POWER YOUR FUTURE.</span>
                </h2>
            </div>

            <!-- PM Surya Ghar Overview Banner -->
            <div class="p1-hero-banner">
                <div style="flex: 1;">
                    <div class="p1-hero-badge-title">
                        <i class="bi bi-house-door-fill me-1"></i> PM SURYA GHAR MUFT BIJLI YOJANA
                    </div>
                    <div style="font-size: 9.5px; line-height: 1.4; color: #E2E8F0;">
                        Make your home energy-efficient with rooftop solar under the PM Surya Ghar Muft Bijli Yojana. Get eligible government subsidy support of up to <strong>₹1,38,000</strong> (Central ₹78k + Odisha ₹60k) and reduce your monthly electricity bill with clean solar energy.
                    </div>
                </div>
                <div style="width: 100px; text-align: center; flex-shrink: 0;" class="bg-white bg-opacity-10 p-2 rounded-3 border border-light border-opacity-25">
                    <div style="font-size: 8px; text-transform: uppercase; color: #FACC15; font-weight: 800;">Max Subsidy</div>
                    <div style="font-size: 15px; font-weight: 900; color: #FFFFFF; font-family: monospace;">₹1.38 Lakh</div>
                    <div style="font-size: 7.5px; color: #E2E8F0;">Direct to Bank</div>
                </div>
            </div>

            <!-- WHY GO SOLAR? 6 BADGES -->
            <div class="p1-section-title font-heading">WHY GO SOLAR?</div>
            <div class="why-solar-grid">
                <div class="why-solar-item">
                    <div class="why-solar-icon" style="background: #DCFCE7; color: #16A34A;"><i class="bi bi-cash-coin"></i></div>
                    <div class="why-solar-label">Reduce Electricity Bills</div>
                </div>
                <div class="why-solar-item">
                    <div class="why-solar-icon" style="background: #FEF3C7; color: #D97706;"><i class="bi bi-bank2"></i></div>
                    <div class="why-solar-label">Government Subsidy Support</div>
                </div>
                <div class="why-solar-item">
                    <div class="why-solar-icon" style="background: #CCFBF1; color: #0D9488;"><i class="bi bi-sun-fill"></i></div>
                    <div class="why-solar-label">Clean & Renewable Energy</div>
                </div>
                <div class="why-solar-item">
                    <div class="why-solar-icon" style="background: #DBEAFE; color: #2563EB;"><i class="bi bi-piggy-bank-fill"></i></div>
                    <div class="why-solar-label">Long-Term 25-Yr Savings</div>
                </div>
                <div class="why-solar-item">
                    <div class="why-solar-icon" style="background: #FEE2E2; color: #DC2626;"><i class="bi bi-lightning-charge-fill"></i></div>
                    <div class="why-solar-label">Energy Independence</div>
                </div>
                <div class="why-solar-item">
                    <div class="why-solar-icon" style="background: #F3E8FF; color: #9333EA;"><i class="bi bi-tools"></i></div>
                    <div class="why-solar-label">Professional Installation</div>
                </div>
            </div>

            <!-- FINANCIAL COMPARISON & BENEFITS TABLE -->
            <div class="p1-section-title font-heading">FINANCIAL COMPARISON & BENEFITS (INDICATIVE)</div>
            <table class="leaflet-table">
                <thead>
                    <tr>
                        <th>SOLAR PLANT</th>
                        <th>APPROX. ROOFTOP AREA</th>
                        <th>PROJECT COST (Approx.)</th>
                        <th>CENTRAL SUBSIDY (Approx.)</th>
                        <th>STATE SUBSIDY (Approx.)</th>
                        <th>NET INVESTMENT (Approx.)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold text-primary">2 KW</td>
                        <td>120 sq.ft.</td>
                        <td>₹1,60,000/-</td>
                        <td>₹60,000/-</td>
                        <td>₹50,000/-</td>
                        <td class="fw-bold text-success font-monospace">₹50,000/-</td>
                    </tr>
                    <tr style="background: #FEF9C3;">
                        <td class="fw-bold text-primary">3 KW <span class="badge bg-warning text-dark" style="font-size: 7px;">MOST POPULAR</span></td>
                        <td>180 sq.ft.</td>
                        <td>₹2,10,000/-</td>
                        <td>₹78,000/-</td>
                        <td>₹60,000/-</td>
                        <td class="fw-bold text-success font-monospace" style="font-size: 10.5px;">₹72,000/-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-primary">4 KW</td>
                        <td>210 sq.ft.</td>
                        <td>₹2,60,000/-</td>
                        <td>₹78,000/-</td>
                        <td>₹60,000/-</td>
                        <td class="fw-bold text-success font-monospace">₹1,22,000/-</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-primary">5 KW</td>
                        <td>270 sq.ft.</td>
                        <td>₹3,30,000/-</td>
                        <td>₹78,000/-</td>
                        <td>₹60,000/-</td>
                        <td class="fw-bold text-success font-monospace">₹1,92,000/-</td>
                    </tr>
                </tbody>
            </table>
            <div class="px-3 text-muted" style="font-size: 7.5px; line-height: 1.3; margin-top: -3px; margin-bottom: 6px;">
                <i class="bi bi-info-circle me-1"></i> Project cost, subsidy eligibility and net investment may vary according to applicable government guidelines, site conditions, DISCOM requirements and final quotation.
            </div>

            <!-- SOLAR LOAN & EMI CONTAINER -->
            <div class="loan-emi-container">
                <!-- Left: Loan features & illustrative 3kW example -->
                <div class="loan-card">
                    <div class="loan-header font-heading"><i class="bi bi-credit-card-2-front-fill me-1"></i> SOLAR LOAN & EASY EMI AVAILABLE</div>
                    <div class="text-secondary" style="font-size: 8px; line-height: 1.3; margin-bottom: 4px;">
                        Eligible customers can finance the applicable balance amount through available nationalized solar-loan facilities.
                    </div>
                    <table class="w-100 table table-sm table-borderless mb-0" style="font-size: 8px; line-height: 1.35;">
                        <tr class="border-bottom">
                            <td class="text-muted py-0">Net Loan Amount (3 kW):</td>
                            <td class="text-end fw-bold py-0 font-monospace">₹72,000</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted py-0">Illustrative Interest Rate:</td>
                            <td class="text-end fw-bold text-success py-0">5.6% p.a. (Subsidized)</td>
                        </tr>
                        <tr class="border-bottom">
                            <td class="text-muted py-0">Repayment Tenure:</td>
                            <td class="text-end fw-bold py-0">10 Years / 120 Months</td>
                        </tr>
                        <tr class="bg-warning bg-opacity-25">
                            <td class="fw-bold py-1 text-danger">Approx. Monthly EMI:</td>
                            <td class="text-end fw-bold py-1 text-danger font-monospace" style="font-size: 9.5px;">₹785 / Month</td>
                        </tr>
                        <tr>
                            <td class="text-muted py-0">Approx. Total Repayment:</td>
                            <td class="text-end font-monospace py-0">₹94,195</td>
                        </tr>
                    </table>
                </div>

                <!-- Right: EMI Slab Table -->
                <div class="loan-card">
                    <div class="loan-header font-heading text-center" style="background: #FEF3C7; color: #B45309;">
                        EMI SLABS (5.6% p.a., 10 YRS)
                    </div>
                    <table class="w-100 table table-sm table-bordered text-center mb-0" style="font-size: 8px; line-height: 1.25;">
                        <thead class="table-light">
                            <tr>
                                <th class="py-0">Loan Amount</th>
                                <th class="py-0">Approx. Monthly EMI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-0 font-monospace">₹50,000</td>
                                <td class="py-0 font-monospace fw-bold text-primary">₹545</td>
                            </tr>
                            <tr style="background: #FEF9C3;">
                                <td class="py-0 font-monospace fw-bold">₹72,000</td>
                                <td class="py-0 font-monospace fw-bold text-danger">₹785</td>
                            </tr>
                            <tr>
                                <td class="py-0 font-monospace">₹1,00,000</td>
                                <td class="py-0 font-monospace fw-bold text-primary">₹1,093</td>
                            </tr>
                            <tr>
                                <td class="py-0 font-monospace">₹1,25,000</td>
                                <td class="py-0 font-monospace fw-bold text-primary">₹1,366</td>
                            </tr>
                            <tr>
                                <td class="py-0 font-monospace">₹1,50,000</td>
                                <td class="py-0 font-monospace fw-bold text-primary">₹1,639</td>
                            </tr>
                            <tr>
                                <td class="py-0 font-monospace">₹2,00,000</td>
                                <td class="py-0 font-monospace fw-bold text-primary">₹2,185</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Callout Banner with Advisor Mobile -->
        <div class="p1-callout-footer">
            <div class="d-flex align-items-center gap-2">
                <div style="background: #16A34A; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="bi bi-telephone-fill"></i>
                </div>
                <div>
                    <div style="font-size: 10px; font-weight: 900; color: #FACC15; text-transform: uppercase;">CHECK YOUR SOLAR ELIGIBILITY & EMI TODAY</div>
                    <div style="font-size: 8px; color: #CBD5E1;">Call for a FREE consultation, site assessment & custom quotation</div>
                </div>
            </div>
            <div class="text-end">
                <div style="font-size: 8px; color: #CBD5E1; text-transform: uppercase;">Direct Advisor Contact:</div>
                <div style="font-size: 15px; font-weight: 900; color: #FACC15; font-family: monospace; letter-spacing: 0.5px;">
                    <?= htmlspecialchars($advMobile) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== LEAFLET PAGE 2: BACK ==================== -->
    <div class="leaflet-page">
        <div>
            <!-- Top Banner: Scheme Key Highlights -->
            <div class="p2-top-banner">
                <div style="flex: 1;">
                    <div class="p2-top-title font-heading">
                        <i class="bi bi-sun-fill text-warning me-1"></i> PM SURYA GHAR MUFT BIJLI YOJANA
                    </div>
                    <ul class="p2-bullet-list">
                        <li>Rooftop solar for residential households across all 30 Odisha districts.</li>
                        <li>Direct DBT government subsidy credited straight to beneficiary bank account.</li>
                        <li>Grid-connected solar electricity generation for household appliances & zero bills.</li>
                        <li>Substantial lifetime reduction in monthly electricity bills with 25-yr panel warranty.</li>
                        <li>Collateral-free subsidized solar loan & easy EMI financing options available.</li>
                    </ul>
                </div>
                <div style="width: 110px; text-align: center; flex-shrink: 0;" class="bg-white bg-opacity-10 p-2 rounded-3 border border-light border-opacity-25">
                    <div style="font-size: 9px; font-weight: 800; color: #FACC15;">FREE ELECTRICITY</div>
                    <div style="font-size: 18px; font-weight: 900; color: #FFFFFF;">300+ Units</div>
                    <div style="font-size: 7.5px; color: #E2E8F0;">Per Month (3 kW)</div>
                </div>
            </div>

            <!-- Documents Required & Solar Solutions Grid -->
            <div class="p2-mid-grid">
                <!-- Left: Documents Required -->
                <div>
                    <div class="p2-block-title font-heading"><i class="bi bi-folder-check me-1"></i> DOCUMENTS GENERALLY REQUIRED</div>
                    <div class="doc-list-grid">
                        <div class="doc-item"><i class="bi bi-person-vcard text-primary"></i> Aadhaar Card</div>
                        <div class="doc-item"><i class="bi bi-geo-alt text-primary"></i> Address with PIN</div>
                        <div class="doc-item"><i class="bi bi-receipt text-primary"></i> Electricity Bill</div>
                        <div class="doc-item"><i class="bi bi-phone text-primary"></i> Mobile Number</div>
                        <div class="doc-item"><i class="bi bi-bank text-primary"></i> Bank Passbook</div>
                        <div class="doc-item"><i class="bi bi-envelope text-primary"></i> Email ID</div>
                        <div class="doc-item"><i class="bi bi-credit-card text-primary"></i> PAN Card (if any)</div>
                        <div class="doc-item"><i class="bi bi-image text-primary"></i> Passport Photo</div>
                        <div class="doc-item"><i class="bi bi-house-check text-primary"></i> House Proof / RoR</div>
                        <div class="doc-item"><i class="bi bi-pin-map text-primary"></i> Site Location GPS</div>
                    </div>
                </div>

                <!-- Right: Our Solar Solutions -->
                <div>
                    <div class="p2-block-title font-heading" style="background: #0D9488;"><i class="bi bi-grid-3x3-gap-fill me-1"></i> OUR SOLAR SOLUTIONS</div>
                    <div class="solutions-grid">
                        <div class="solution-card">
                            <div style="font-size: 16px; color: #0284C7; margin-bottom: 2px;"><i class="bi bi-sun"></i></div>
                            <div>Tier-1 Solar PV Modules</div>
                        </div>
                        <div class="solution-card">
                            <div style="font-size: 16px; color: #16A34A; margin-bottom: 2px;"><i class="bi bi-cpu"></i></div>
                            <div>Smart On-Grid Inverter</div>
                        </div>
                        <div class="solution-card">
                            <div style="font-size: 16px; color: #D97706; margin-bottom: 2px;"><i class="bi bi-building"></i></div>
                            <div>HDG Solar Structure</div>
                        </div>
                        <div class="solution-card">
                            <div style="font-size: 16px; color: #9333EA; margin-bottom: 2px;"><i class="bi bi-tools"></i></div>
                            <div>Certified Installation</div>
                        </div>
                        <div class="solution-card">
                            <div style="font-size: 16px; color: #2563EB; margin-bottom: 2px;"><i class="bi bi-phone-flip"></i></div>
                            <div>App IoT Monitoring</div>
                        </div>
                        <div class="solution-card">
                            <div style="font-size: 16px; color: #DC2626; margin-bottom: 2px;"><i class="bi bi-shield-check"></i></div>
                            <div>5-Yr AMC & Warranty</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Why Choose Section -->
            <div class="why-choose-banner">
                <div class="why-choose-title font-heading"><i class="bi bi-award-fill me-1"></i> WHY CHOOSE DHWAJJA SOLAR INDIA PVT. LTD.?</div>
                <div class="why-choose-grid">
                    <div>✔ Professional & Certified Solar Installation</div>
                    <div>✔ Bank Solar Loan / EMI Application Guidance</div>
                    <div>✔ MNRE / ALMM Quality Solar Components</div>
                    <div>✔ Free On-Site Survey & System Planning</div>
                    <div>✔ 100% Transparent All-Inclusive Pricing</div>
                    <div>✔ Complete DISCOM Net Metering Support</div>
                    <div>✔ End-to-End Subsidy Portal Processing</div>
                    <div>✔ Dedicated Customer Support & AMC Assistance</div>
                </div>
            </div>

            <!-- Finance Available Strip -->
            <div class="finance-available-bar font-heading">
                <i class="bi bi-cash-stack me-1"></i> FINANCE AVAILABLE — EASY SOLAR LOAN / EMI OPTIONS FOR ELIGIBLE APPLICANTS
            </div>

            <!-- CONTACT US SECTION WITH PERSONALIZED ADVISOR DETAILS -->
            <div class="contact-us-section">
                <div class="contact-us-header font-heading">
                    <span>CONTACT US FOR ROOFTOP SOLAR CONSULTATION</span>
                </div>
                <div class="row align-items-center g-2">
                    <!-- Left: Company Info -->
                    <div class="col-5 border-end pe-2">
                        <div class="fw-bold text-navy" style="font-size: 11px;"><?= htmlspecialchars($companyName) ?></div>
                        <div class="text-secondary" style="font-size: 8px; line-height: 1.35; margin-top: 2px;">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($companyAddress) ?><br>
                            <i class="bi bi-envelope-fill text-primary me-1"></i><?= htmlspecialchars($companyEmail) ?><br>
                            <i class="bi bi-file-earmark-text text-secondary me-1"></i><strong>GSTIN:</strong> <span class="font-monospace fw-bold"><?= htmlspecialchars($companyGstin) ?></span>
                        </div>
                    </div>

                    <!-- Middle: Dynamic Authorized Solar Advisor Info -->
                    <div class="col-4 border-end px-2">
                        <div class="badge bg-warning text-dark fw-bold mb-1" style="font-size: 7.5px;">YOUR AUTHORIZED SOLAR ADVISOR</div>
                        <div class="fw-bold text-navy" style="font-size: 11px;"><?= htmlspecialchars($advName) ?></div>
                        <div class="text-secondary" style="font-size: 8px; line-height: 1.35; margin-top: 2px;">
                            <strong>Advisor Code:</strong> <span class="badge bg-dark font-monospace" style="font-size: 7.5px;"><?= htmlspecialchars($advCode) ?></span><br>
                            <i class="bi bi-telephone-fill text-success me-1"></i><strong>Mobile:</strong> <span class="text-navy font-monospace fw-bold"><?= htmlspecialchars($advMobile) ?></span><br>
                            <i class="bi bi-geo-fill text-muted me-1"></i><strong>Area:</strong> <?= htmlspecialchars($advDistrict) ?>, Odisha
                        </div>
                    </div>

                    <!-- Right: Dynamic Scannable QR Code -->
                    <div class="col-3 text-center">
                        <?php if (!empty($qrCodeUrl)): ?>
                            <img src="<?= htmlspecialchars($qrCodeUrl) ?>" style="width: 70px; height: 70px; border: 1px solid #CBD5E1; padding: 2px; border-radius: 4px;" alt="QR Code">
                        <?php else: ?>
                            <div style="width: 70px; height: 70px; border: 1px solid #CBD5E1; padding: 2px; display: inline-flex; align-items: center; justify-content: center; font-size: 8px; color: #64748B;">
                                [ SCAN QR ]
                            </div>
                        <?php endif; ?>
                        <div style="font-size: 7px; font-weight: 800; color: var(--brand-navy); margin-top: 2px; line-height: 1.1; text-transform: uppercase;">
                            SCAN TO GET QUOTE & APPLY
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Ribbon -->
        <div class="p2-bottom-ribbon font-heading">
            YOUR ROOF. YOUR POWER. YOUR SAVINGS.
        </div>
    </div>

</body>
</html>
