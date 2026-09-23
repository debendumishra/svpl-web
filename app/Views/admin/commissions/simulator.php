<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Commission Simulator & Audit Preview Desk
 */
$title = $pageTitle ?? 'Commission Simulator';
?>

<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">
                <i class="bi bi-calculator text-primary me-2"></i>Commission Engine Simulator
            </h1>
            <p class="text-muted small mb-0">Test & Preview Multi-Level Commission Distributions & Qualification Rules in Real-Time</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= url('/admin/commissions') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Desk
            </a>
            <a href="<?= url('/admin/commissions/settings') ?>" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-sliders me-1"></i>Edit Rules
            </a>
        </div>
    </div>

    <!-- Simulator Input Panel -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-gear me-2 text-primary"></i>Simulation Scenario Parameters</h6>
        </div>
        <div class="card-body p-4">
            <form id="simForm" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Direct Referring Advisor (Level 1)</label>
                    <select name="advisor_id" id="simAdvisor" class="form-select" required>
                        <option value="">-- Choose Referring Advisor --</option>
                        <?php foreach ($advisors as $adv): ?>
                            <option value="<?= $adv['id'] ?>">
                                <?= htmlspecialchars($adv['advisor_code']) ?> — <?= htmlspecialchars($adv['first_name'] . ' ' . $adv['last_name']) ?> (<?= (int)$adv['personal_customers'] ?> custs)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Solar System / Product</label>
                    <select name="product_name" id="simProduct" class="form-select">
                        <?php foreach ($products as $p): ?>
                            <option value="<?= htmlspecialchars($p['product_name']) ?>" data-capacity="<?= (float)$p['capacity_kw'] ?>" data-type="<?= htmlspecialchars($p['connection_type'] ?? 'On-Grid') ?>">
                                <?= htmlspecialchars($p['rule_name']) ?> (L1: ₹<?= number_format((float)($p['levels'][1] ?? $p['direct_commission']), 0) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold small">Connection Type</label>
                    <select name="connection_type" id="simConnType" class="form-select">
                        <option value="On-Grid">On-Grid</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Off-Grid">Off-Grid</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Company Account Credit Date</label>
                    <input type="date" name="credit_date" id="simCreditDate" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    <div class="form-text small">Determines the Commission Month</div>
                </div>
                <div class="col-12 text-end mt-3">
                    <button type="button" id="btnSimulate" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-play-fill me-1"></i>Run Live Simulation
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Simulation Results Box -->
    <div id="simResults" style="display: none;">
        <!-- Header summary -->
        <div class="card border-0 shadow-sm rounded-3 mb-4 border-start border-4 border-success">
            <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <span class="badge bg-success mb-1">Rule Matched</span>
                    <h4 class="fw-bold text-dark mb-1" id="resRuleMatched">3 kW On-Grid Solar System</h4>
                    <div class="text-muted small">
                        Commission Period: <strong class="text-dark" id="resPeriod">September 2026</strong> &bull; Credit Date: <span id="resCreditDate">2026-09-18</span>
                    </div>
                </div>
                <div class="text-end mt-2 mt-md-0">
                    <div class="text-muted small text-uppercase fw-semibold">Total Commission Liability</div>
                    <div class="h2 fw-bold text-success mb-0" id="resTotalLiability">₹0.00</div>
                </div>
            </div>
        </div>

        <!-- 9-Level Tree Breakdown Table -->
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-diagram-3 me-2 text-primary"></i>9-Level Network Commission Distribution & Qualification Breakdown</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="resTable">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th width="120">Level</th>
                            <th>Advisor</th>
                            <th class="text-center">Personal Customers</th>
                            <th class="text-center">Max Eligible Level</th>
                            <th class="text-end">Gross Comm</th>
                            <th class="text-end">Net (TDS Ded)</th>
                            <th>Qualification Status</th>
                            <th>Audit / Exclusion Reason</th>
                        </tr>
                    </thead>
                    <tbody class="small" id="resTableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#btnSimulate').on('click', function() {
        const advId = $('#simAdvisor').val();
        if (!advId) {
            alert('Please select a referring advisor');
            return;
        }

        const data = {
            advisor_id: advId,
            product_name: $('#simProduct').val(),
            connection_type: $('#simConnType').val(),
            credit_date: $('#simCreditDate').val()
        };

        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Calculating...');

        $.post('<?= url('/admin/commissions/simulator/ajax') ?>', data, function(res) {
            $('#btnSimulate').prop('disabled', false).html('<i class="bi bi-play-fill me-1"></i>Run Live Simulation');
            
            if (!res.status) {
                alert('Simulation Error: ' + res.message);
                return;
            }

            $('#resRuleMatched').text(res.rule_matched);
            const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $('#resPeriod').text(months[res.commission_month - 1] + ' ' + res.commission_year);
            $('#resCreditDate').text(res.credit_date);
            $('#resTotalLiability').text('₹' + Number(res.total_commission_liability).toLocaleString('en-IN', {minimumFractionDigits: 2}));

            let html = '';
            res.breakdown.forEach(function(row) {
                const isEligible = row.is_eligible;
                const rowClass = isEligible ? '' : (row.status === 'NO_ADVISOR' ? 'table-light text-muted' : 'table-warning text-muted');
                const badge = isEligible 
                    ? '<span class="badge bg-success">ELIGIBLE</span>' 
                    : (row.status === 'NO_ADVISOR' ? '<span class="badge bg-secondary">NO UPLINE</span>' : '<span class="badge bg-danger">EXCLUDED</span>');

                html += '<tr class="' + rowClass + '">';
                html += '<td><span class="badge bg-primary-subtle text-primary fw-bold font-monospace">' + row.level_label + '</span></td>';
                html += '<td>';
                if (row.advisor_id) {
                    html += '<div class="fw-bold text-dark">' + row.advisor_name + '</div>';
                    html += '<div class="text-muted small font-monospace">' + row.advisor_code + '</div>';
                } else {
                    html += '<span class="text-muted">—</span>';
                }
                html += '</td>';
                html += '<td class="text-center font-monospace">' + (row.advisor_id ? row.personal_customers : '—') + '</td>';
                html += '<td class="text-center font-monospace">' + (row.advisor_id ? 'Level ' + row.max_eligible_level : '—') + '</td>';
                html += '<td class="text-end fw-semibold ' + (isEligible ? 'text-dark' : 'text-muted text-decoration-line-through') + '">₹' + Number(row.gross_commission).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                html += '<td class="text-end fw-bold ' + (isEligible ? 'text-success fs-6' : 'text-muted') + '">₹' + Number(row.net_commission).toLocaleString('en-IN', {minimumFractionDigits: 2}) + '</td>';
                html += '<td>' + badge + '</td>';
                html += '<td class="small">' + row.reason + '</td>';
                html += '</tr>';
            });

            $('#resTableBody').html(html);
            $('#simResults').slideDown();
        }, 'json');
    });
});
</script>

