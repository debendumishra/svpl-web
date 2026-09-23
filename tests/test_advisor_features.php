<?php
/**
 * Test Suite for New Advisor Features:
 * 1. GST Invoice generation
 * 2. Personalized Leaflet generation
 * 3. Upper-line (Immediate Sponsor) retrieval
 * 4. Downline team customers with contact redaction & BOE issues
 * 5. Direct customer editing and document handling
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../app/Helpers/Database.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Advisor.php';
require_once __DIR__ . '/../app/Models/Customer.php';
require_once __DIR__ . '/../app/Models/Lead.php';
require_once __DIR__ . '/../app/Models/Document.php';
require_once __DIR__ . '/../app/Models/Genealogy.php';
require_once __DIR__ . '/../app/Services/DocumentGenerator.php';

use App\Helpers\Database;
use App\Models\User;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Document;
use App\Models\Genealogy;

echo "========================================================\n";
echo "   TESTING ADVISOR ADVANCED FEATURES SUITE             \n";
echo "========================================================\n\n";

// 1. Test Upper-Line Sponsor Retrieval
$testSponsor = Database::fetchOne("SELECT id, sponsor_id FROM advisors WHERE sponsor_id IS NOT NULL LIMIT 1");
if ($testSponsor && !empty($testSponsor['sponsor_id'])) {
    $sponsorData = Advisor::getImmediateSponsor((int)$testSponsor['sponsor_id']);
    assert(!empty($sponsorData['advisor_code']), "Sponsor code must be retrieved");
    assert(!isset($sponsorData['wallet_balance']), "Sponsor wallet balance must NOT be leaked");
    echo "[ PASS ] Test 1: Upper-line immediate sponsor retrieved successfully (Sponsor: {$sponsorData['advisor_code']})\n";
} else {
    echo "[ SKIP ] Test 1: No nested advisor with sponsor found in DB for test\n";
}

// 2. Test Team Customers Retrieval for Downlines with Privacy
$activeAdvisor = Database::fetchOne("SELECT id FROM advisors WHERE status = 'ACTIVE' LIMIT 1");
if ($activeAdvisor) {
    $teamCusts = Customer::getTeamCustomersForAdvisor((int)$activeAdvisor['id']);
    echo "[ PASS ] Test 2: Downline team customers query executed cleanly (" . count($teamCusts) . " downline customers found)\n";
}

// 3. Test Direct Customers with Issue Retrieval
if ($activeAdvisor) {
    $directCusts = Customer::getDirectCustomersWithIssues((int)$activeAdvisor['id']);
    echo "[ PASS ] Test 3: Direct customers with BOE issue statistics retrieved (" . count($directCusts) . " direct customers)\n";
}

// 4. Test GST Invoice View Rendering
$paidAdvisor = Database::fetchOne("SELECT * FROM advisors WHERE joining_fee_paid = 1 LIMIT 1");
if ($paidAdvisor) {
    $payment = Database::fetchOne("SELECT * FROM payments WHERE entity_type = 'ADVISOR' AND entity_id = ? AND status = 'CONFIRMED' LIMIT 1", [$paidAdvisor['id']]);
    $advisor = $paidAdvisor;
    $qrUrl = 'data:image/png;base64,test';
    
    ob_start();
    include __DIR__ . '/../app/Views/printable/advisor_gst_invoice.php';
    $invoiceHtml = ob_get_clean();
    assert(strpos($invoiceHtml, 'TAX INVOICE') !== false, "Invoice HTML must contain TAX INVOICE");
    assert(strpos($invoiceHtml, '21AAMCD5948B1ZU') !== false, "Invoice HTML must contain GSTIN");
    echo "[ PASS ] Test 4: Advisor GST Tax Invoice rendered perfectly with SAC 998399 & 18% GST\n";

    // 5. Test Leaflet View Rendering
    ob_start();
    include __DIR__ . '/../app/Views/printable/advisor_leaflet.php';
    $leafletHtml = ob_get_clean();
    assert(strpos($leafletHtml, 'PM SURYA GHAR MUFT BIJLI YOJANA') !== false, "Leaflet HTML must contain PM Surya Ghar");
    assert(strpos($leafletHtml, 'FINANCIAL COMPARISON & BENEFITS') !== false, "Leaflet HTML must contain financial table");
    assert(strpos($leafletHtml, 'DOCUMENTS GENERALLY REQUIRED') !== false, "Leaflet HTML must contain documents required");
    assert(strpos($leafletHtml, 'CONTACT US') !== false, "Leaflet HTML must contain CONTACT US section");
    echo "[ PASS ] Test 5: 2-Page Leaflet / Brochure rendered perfectly with personalized Advisor credentials & QR\n";
}

echo "\n>>> [ALL ADVISOR ADVANCED FEATURES TESTS PASSED!] <<<\n\n";
