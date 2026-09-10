<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Customer Quotation & Financial Proposal View (Solar Luminary Design System)
 */
$title = "Official Solar Proposal — SVPL";
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 animate-fade-in">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h3 class="font-heading fw-bold mb-0 text-navy">PM Surya Ghar Solar Proposal & Quotation</h3>
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">Dhwajja Turnkey Package</span>
        </div>
        <p class="text-secondary small mb-0">Official estimated cost, central + state government subsidy breakdown, and system specifications</p>
    </div>
    <?php if ($lead): ?>
        <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-svpl-navy btn-sm shadow-sm">
            <i class="bi bi-printer-fill me-1 text-warning"></i> Print Official Proposal
        </a>
    <?php endif; ?>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm animate-fade-in stagger-1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="font-heading fw-bold mb-0 text-navy">Turnkey Solar System Cost & Subsidy Statement</h5>
        <span class="badge bg-success-subtle text-success border border-success-subtle">MNRE & OREDA Empanelled</span>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr class="small text-uppercase text-secondary">
                    <th>Component / Description</th>
                    <th>Technical Specification</th>
                    <th class="text-end">Amount (INR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong class="text-navy">Solar PV Modules (Tier-1)</strong></td>
                    <td class="text-secondary">Dhwajja Bi-Facial / Mono PERC DCR 545W High-Efficiency Panels (25-Yr Warranty)</td>
                    <td class="text-end fw-bold text-navy">Included</td>
                </tr>
                <tr>
                    <td><strong class="text-navy">Grid-Tied Solar Inverter</strong></td>
                    <td class="text-secondary">Smart Dual-MPPT Inverter with Mobile Wi-Fi Monitoring (5-Yr Warranty)</td>
                    <td class="text-end fw-bold text-navy">Included</td>
                </tr>
                <tr>
                    <td><strong class="text-navy">Mounting Structure & BoS</strong></td>
                    <td class="text-secondary">HDG Aluminum/GI Structure, ACDB/DCDB, Lightning Arrestor, Dual Earthing</td>
                    <td class="text-end fw-bold text-navy">Included</td>
                </tr>
                <tr class="table-light">
                    <td colspan="2" class="fw-bold text-navy">Gross Dhwajja Solar Turnkey Project Cost:</td>
                    <td class="text-end fw-bold text-navy fs-6">₹<?= number_format((float)($lead['estimated_project_cost'] ?? 210000), 2) ?></td>
                </tr>
                <tr class="table-light">
                    <td colspan="2" class="text-success"><i class="bi bi-check-circle-fill text-success me-1"></i> PM Surya Ghar Direct Central Subsidy (DBT):</td>
                    <td class="text-end text-success fw-bold">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
                </tr>
                <tr class="table-light">
                    <td colspan="2" class="text-warning-emphasis"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Government Solar Subsidy:</td>
                    <td class="text-end text-success fw-bold">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
                </tr>
                <tr class="table-success">
                    <td colspan="2" class="fw-bold text-success"><i class="bi bi-gift-fill text-success me-1"></i> Total Combined Govt. Subsidy Benefit:</td>
                    <td class="text-end text-success fw-bold fs-6">- ₹<?= number_format((float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)), 2) ?></td>
                </tr>
                <tr class="table-primary">
                    <td colspan="2" class="fw-bold text-navy fs-6">Effective Net Customer Investment (After Dual Subsidy):</td>
                    <td class="text-end text-primary fw-bold fs-5">₹<?= number_format((float)($lead['customer_payable_amount'] ?? 72000), 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="alert alert-info py-2 px-3 small mt-2 mb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span><i class="bi bi-info-circle-fill me-1"></i> Concessional Solar Loan EMI @ 5.6% p.a. (10 Years): <strong class="text-success fs-6">₹785 / month</strong></span>
        <span class="text-secondary">Direct Bank Transfer directly into customer's Aadhaar-linked SBI bank account.</span>
    </div>
</div>
