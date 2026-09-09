/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Global Interactive JS Helper (Cascading Locations, Solar Calculator, Live Referral Validation)
 */

$(document).ready(function () {
    // Dynamic Base Path Resolver for AJAX
    window.SVPL_BASE = $('meta[name="base-url"]').attr('content') || '';

    // Solar Cost & Subsidy Calculator (Official Dhwajja Solar PM Surya Ghar + Odisha State Subsidy)
    const solarPlans = {
        2: {
            cost: 160000,
            centralSubsidy: 60000,
            stateSubsidy: 50000,
            netInvestment: 50000,
            area: '120 sq.ft.',
            emi: '₹545 / month',
            units: 240,
            savings: 1560
        },
        3: {
            cost: 210000,
            centralSubsidy: 78000,
            stateSubsidy: 60000,
            netInvestment: 72000,
            area: '180 sq.ft.',
            emi: '₹785 / month',
            units: 360,
            savings: 2340
        },
        4: {
            cost: 260000,
            centralSubsidy: 78000,
            stateSubsidy: 60000,
            netInvestment: 122000,
            area: '210 sq.ft.',
            emi: '₹1,333 / month',
            units: 480,
            savings: 3120
        },
        5: {
            cost: 330000,
            centralSubsidy: 78000,
            stateSubsidy: 60000,
            netInvestment: 192000,
            area: '270 sq.ft.',
            emi: '₹2,098 / month',
            units: 600,
            savings: 3900
        }
    };

    function calculateSolar() {
        const capacity = parseInt($('#calcCapacity').val(), 10) || 3;
        const plan = solarPlans[capacity] || solarPlans[3];

        const totalSubsidy = plan.centralSubsidy + plan.stateSubsidy;

        $('#calcTotalCost').text('₹' + plan.cost.toLocaleString('en-IN') + '/-');
        $('#calcCentralSubsidy').text('- ₹' + plan.centralSubsidy.toLocaleString('en-IN') + '/-');
        $('#calcStateSubsidy').text('- ₹' + plan.stateSubsidy.toLocaleString('en-IN') + '/-');
        $('#calcSubsidy').text('₹' + totalSubsidy.toLocaleString('en-IN'));
        $('#calcTotalSubsidyDisplay').text('- ₹' + totalSubsidy.toLocaleString('en-IN') + '/-');
        $('#calcNetCost').text('₹' + plan.netInvestment.toLocaleString('en-IN') + '/-');
        $('#calcArea').text(plan.area);
        $('#calcEmi').text(plan.emi);
        $('#calcMonthlySavings').text('₹' + plan.savings.toLocaleString('en-IN') + ' / month');
        $('#calcUnits').text(plan.units + ' Units/mo');
    }

    if ($('#calcCapacity').length) {
        $('#calcCapacity').on('input change', calculateSolar);
        calculateSolar();
    }

    // Cascading Location Dropdowns (District -> Block -> GP)
    $('#selectDistrict').on('change', function () {
        const district = $(this).val();
        const blockSelect = $('#selectBlock');
        blockSelect.html('<option value="">Loading blocks...</option>');

        if (district) {
            $.getJSON(window.SVPL_BASE + '/api/locations/blocks?district=' + encodeURIComponent(district), function (data) {
                blockSelect.html('<option value="">Select Block</option>');
                $.each(data.blocks, function (i, b) {
                    blockSelect.append($('<option>', { value: b, text: b }));
                });
            });
        }
    });

    $('#selectBlock').on('change', function () {
        const block = $(this).val();
        const gpSelect = $('#selectGP');
        if (gpSelect.length && block) {
            gpSelect.html('<option value="">Loading GPs...</option>');
            $.getJSON(window.SVPL_BASE + '/api/locations/gps?block=' + encodeURIComponent(block), function (data) {
                gpSelect.html('<option value="">Select Gram Panchayat</option>');
                $.each(data.gps, function (i, g) {
                    gpSelect.append($('<option>', { value: g, text: g }));
                });
            });
        }
    });

    // Real-Time Referral Code Validation
    $('#inputReferralCode').on('blur change', function () {
        const code = $(this).val().trim();
        const feedback = $('#referralFeedback');
        if (code.length >= 4) {
            feedback.html('<span class="text-muted"><i class="bi bi-arrow-repeat spin"></i> Verifying...</span>');
            $.getJSON(window.SVPL_BASE + '/api/validate-referral?code=' + encodeURIComponent(code), function (res) {
                if (res.valid) {
                    feedback.html('<span class="text-success"><i class="bi bi-check-circle-fill"></i> Verified: <strong>' + res.advisor.name + '</strong> (' + res.advisor.district + ')</span>');
                } else {
                    feedback.html('<span class="text-danger"><i class="bi bi-x-circle-fill"></i> ' + res.message + '</span>');
                }
            });
        } else {
            feedback.empty();
        }
    });
});
