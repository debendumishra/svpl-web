/**
 * Surya Vistaara Pvt. Ltd. (SVPL) - Dhwajja Solar India
 * Global Interactive JS Helper (Auto-cycling Carousel, Solar Calculator, Live Referral Validation)
 */

$(document).ready(function () {
    // Dynamic Base Path Resolver for AJAX
    window.SVPL_BASE = $('meta[name="base-url"]').attr('content') || '';

    // Automated Hero Carousel Initialization & Continuous Auto-Play
    const heroCarouselEl = document.querySelector('#heroSolarCarousel');
    if (heroCarouselEl && typeof bootstrap !== 'undefined') {
        const carouselInstance = new bootstrap.Carousel(heroCarouselEl, {
            interval: 4000,
            ride: 'carousel',
            wrap: true,
            pause: false,
            keyboard: true
        });
        carouselInstance.cycle();

        // Fallback auto-timer in case browser tab visibility delays Bootstrap cycle
        let autoSlideTimer = setInterval(function() {
            if (document.visibilityState === 'visible') {
                carouselInstance.next();
            }
        }, 4000);
    }

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

    // Copy to Clipboard Utility
    $(document).on('click', '.btn-copy', function () {
        const text = $(this).data('copy') || $(this).text().trim();
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(function () {
                alert('Copied to clipboard: ' + text);
            });
        } else {
            const input = $('<input>').val(text).appendTo('body').select();
            document.execCommand('copy');
            input.remove();
            alert('Copied: ' + text);
        }
    });

    // Sidebar Toggle (Desktop Collapse & Expand)
    $(document).on('click', '#sidebarToggleBtn', function (e) {
        e.preventDefault();
        $('#appSidebar').toggleClass('collapsed');
        if ($('#appSidebar').hasClass('collapsed')) {
            localStorage.setItem('svpl_sidebar_collapsed', '1');
        } else {
            localStorage.removeItem('svpl_sidebar_collapsed');
        }
    });

    // Restore sidebar state preference
    if (localStorage.getItem('svpl_sidebar_collapsed') === '1') {
        $('#appSidebar').addClass('collapsed');
    }
});
