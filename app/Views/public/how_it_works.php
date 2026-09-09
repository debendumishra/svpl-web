<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * How It Works — 10-Stage Pipeline Overview for Customers & Advisors
 */
$title = "How It Works — SVPL Solar Network";
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <span class="badge bg-warning text-dark px-3 py-2 fw-bold mb-2">END-TO-END WORKFLOW</span>
        <h1 class="fw-bold" style="color: #0B2545;">The 10-Stage Solar Journey with SVPL</h1>
        <p class="text-muted">From application submission to DISCOM net metering and direct DBT subsidy transfer.</p>
    </div>

    <div class="row g-4 justify-content-center">
        <?php
        $stages = [
            ['1', 'Application & Registration', 'Advisor or Customer submits application with electricity bill and roof capacity requirements.', 'bi-person-plus-fill', 'text-primary'],
            ['2', 'Document Verification', 'Electricity bill, Aadhaar, PAN, and rooftop photographs are verified by SVPL operations.', 'bi-file-earmark-check-fill', 'text-success'],
            ['3', 'Govt. Portal Submission', 'Application registered on the National PM Surya Ghar portal under Odisha DISCOM jurisdiction.', 'bi-cloud-arrow-up-fill', 'text-info'],
            ['4', 'Bank Loan Application', 'Collateral-free low-interest solar financing processed through partnered banks.', 'bi-bank2', 'text-warning'],
            ['5', 'Loan Sanction & Approval', 'Bank releases sanction letter and initial mobilization milestone payment.', 'bi-patch-check-fill', 'text-success'],
            ['6', 'Equipment Dispatch & Installation Commenced', 'Tier-1 Mono PERC panels, inverters, and mounting structures delivered on-site.', 'bi-truck', 'text-primary'],
            ['7', 'Solar Installation Completed', 'Certified technicians complete mounting, inverter wiring, and safety earthing.', 'bi-tools', 'text-danger'],
            ['8', 'DISCOM JE Inspection & Net Metering', 'Junior Engineer inspects system, synchronizes bi-directional net meter with grid.', 'bi-speedometer2', 'text-warning'],
            ['9', 'Central & State Subsidy Claims', 'Commissioning report submitted on National PM Surya Ghar portal and Odisha state renewable portal for DBT subsidy processing.', 'bi-cash-coin', 'text-success'],
            ['10', 'Subsidies Received & Commissions Released', 'Central DBT (up to ₹78,000) & Odisha State Subsidy (₹60,000) credited into customer bank; 9-level commissions released.', 'bi-trophy-fill', 'text-success'],
        ];
        foreach ($stages as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-svpl p-4 h-100 border-0 shadow-sm">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center fw-bold fs-5 text-navy border" style="width: 44px; height: 44px; min-width: 44px;">
                            <?= $s[0] ?>
                        </div>
                        <i class="bi <?= $s[3] ?> <?= $s[4] ?> fs-3"></i>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #0B2545;"><?= $s[1] ?></h5>
                    <p class="text-muted small mb-0"><?= $s[2] ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
