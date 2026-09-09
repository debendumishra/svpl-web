<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Quotation & Financial Proposal View
 */
$title = "My Solar Proposal — SVPL";
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0B2545;">PM Surya Ghar Solar Proposal</h3>
        <p class="text-muted small mb-0">Official estimated cost, central subsidy, and technical specifications</p>
    </div>
    <?php if ($lead): ?>
        <a href="<?= url('/print/quotation/' . $lead['id']) ?>" target="_blank" class="btn btn-svpl-navy btn-sm">
            <i class="bi bi-printer me-1"></i> Print Proposal
        </a>
    <?php endif; ?>
</div>

<div class="card card-svpl p-4 bg-white border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Component / Description</th>
                    <th>Specification</th>
                    <th class="text-end">Amount (INR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Solar PV Modules (Tier-1)</strong></td>
                    <td>Mono-PERC Half-Cut 540W / 550W High Efficiency Panels</td>
                    <td class="text-end">Included</td>
                </tr>
                <tr>
                    <td><strong>Grid-Tied Solar Inverter</strong></td>
                    <td>Smart Dual-MPPT Inverter with Mobile Wi-Fi Monitoring App</td>
                    <td class="text-end">Included</td>
                </tr>
                <tr>
                    <td><strong>Mounting Structure & Balance of System</strong></td>
                    <td>HDG Aluminum/GI Structure, ACDB/DCDB, Lightning Arrestor, Earthing</td>
                    <td class="text-end">Included</td>
                </tr>
                <tr class="table-light fw-bold">
                    <td colspan="2">Total Dhwajja Solar System Cost:</td>
                    <td class="text-end">₹<?= number_format((float)($lead['estimated_project_cost'] ?? 210000), 2) ?></td>
                </tr>
                <tr class="table-success text-success small">
                    <td colspan="2"><i class="bi bi-gift-fill me-1"></i> PM Surya Ghar Direct Central DBT Subsidy:</td>
                    <td class="text-end fw-bold">- ₹<?= number_format((float)($lead['subsidy_amount'] ?? 78000), 2) ?></td>
                </tr>
                <tr class="table-warning text-dark small fw-bold">
                    <td colspan="2"><i class="bi bi-patch-check-fill text-warning me-1"></i> Odisha State Government Solar Subsidy:</td>
                    <td class="text-end text-success fw-bold">- ₹<?= number_format((float)($lead['state_subsidy'] ?? 60000), 2) ?></td>
                </tr>
                <tr class="table-success small fw-bold">
                    <td colspan="2"><i class="bi bi-plus-circle-fill text-success me-1"></i> Total Combined Government Subsidy:</td>
                    <td class="text-end text-success fs-6">- ₹<?= number_format((float)(($lead['subsidy_amount'] ?? 78000) + ($lead['state_subsidy'] ?? 60000)), 2) ?></td>
                </tr>
                <tr class="table-primary fs-5 fw-bold" style="color: #0B2545;">
                    <td colspan="2">Net Customer Payable Investment:</td>
                    <td class="text-end text-primary">₹<?= number_format((float)($lead['customer_payable_amount'] ?? 72000), 2) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
