<?php
/**
 * Test Suite: PM Surya Ghar 2-Page Quotation, 4-Page Agreement (Annexure 2), BOE ID & Doc Management
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../app/Helpers/Database.php';
require_once __DIR__ . '/../app/Models/Customer.php';
require_once __DIR__ . '/../app/Models/Lead.php';
require_once __DIR__ . '/../app/Models/Document.php';

use App\Helpers\Database;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Document;

echo "========================================================\n";
echo "   TESTING QUOTATION, AGREEMENT & BOE WORKFLOW SUITE   \n";
echo "========================================================\n\n";

$passCount = 0;

// Test 1: Customer Creation with Touch E-Signature & Agreement Acceptance
$testCustCode = 'SVPL-TEST-CUST-' . rand(1000, 9999);
$custId = Customer::create([
    'customer_code' => $testCustCode,
    'first_name' => 'Dambaru',
    'last_name' => 'Baka',
    'father_husband_name' => 'Late Arjun Baka',
    'dob' => '1988-05-15',
    'mobile' => '9439' . rand(100000, 999999),
    'email' => 'dambaru.test@example.com',
    'district' => 'Malkangiri',
    'block' => 'Nalkaguda',
    'gram_panchayat' => 'Chalaguda',
    'village' => 'Nayakguda',
    'pincode' => '764044',
    'address_line' => 'At- Nayakguda, Chalanguda, P O- Nalkaguda, Dist- Malkangiri',
    'discom_name' => 'TPWODL',
    'consumer_number' => 'TPWODL-MAL-998812',
    'notification_number' => 'NOTIF/2026/DSI/TEST01',
    'proposed_solar_kw' => 3.0,
    'customer_signature' => '/assets/images/authorised_signatory.png',
    'agreement_accepted' => 1,
    'agreement_accepted_at' => date('Y-m-d H:i:s'),
    'status' => 'New'
]);

$cust = Customer::findById($custId);
if ($cust && (int)$cust['agreement_accepted'] === 1 && !empty($cust['customer_signature'])) {
    echo "[ PASS ] Test 1: Customer created with E-Signature and Agreement (Annexure 2) acceptance.\n";
    $passCount++;
} else {
    echo "[ FAIL ] Test 1: Customer creation with agreement failed.\n";
}

// Test 2: BOE Updates PM Surya Ghar Unique ID
Customer::updatePmSuryaGharId($custId, 'PMSGY-OD-2026-MAL-0099', 'NOTIF/2026/MAL/0099');
$updatedCust = Customer::findById($custId);
if ($updatedCust && $updatedCust['pm_surya_ghar_id'] === 'PMSGY-OD-2026-MAL-0099' && $updatedCust['notification_number'] === 'NOTIF/2026/MAL/0099') {
    echo "[ PASS ] Test 2: BOE successfully updated PM Surya Ghar Unique ID & Notification Number.\n";
    $passCount++;
} else {
    echo "[ FAIL ] Test 2: PM Surya Ghar ID update failed.\n";
}

// Test 3: Upload PM Surya Ghar Auto-Generated Scheme Documents
$pmDocTypes = [
    'FEASIBILITY_REPORT' => 'DISCOM Technical Feasibility Report',
    'BANK_CONGRATULATION_LETTER' => 'Bank Sanction & Congratulation Letter',
    'PM_SURYA_GHAR_CONGRATS' => 'PM Surya Ghar National Portal Congratulation Page',
    'APPLICATION_ACKNOWLEDGEMENT' => 'Acknowledgement of Application',
];

$docCountBefore = count(Document::getByCustomerId($custId));
foreach ($pmDocTypes as $dType => $dTitle) {
    Document::create([
        'entity_type' => 'CUSTOMER',
        'entity_id' => $custId,
        'document_type' => $dType,
        'document_title' => $dTitle,
        'file_path' => 'uploads/documents/test_' . strtolower($dType) . '.pdf',
        'file_size' => 10240,
        'mime_type' => 'application/pdf',
        'status' => 'Verified',
        'remarks' => 'Auto-generated scheme document'
    ]);
}
$custDocs = Document::getByCustomerId($custId);
if (count($custDocs) === ($docCountBefore + 4)) {
    echo "[ PASS ] Test 3: 4 PM Surya Ghar auto-generated documents (Feasibility, Sanction, Congrats, Ack) uploaded.\n";
    $passCount++;
} else {
    echo "[ FAIL ] Test 3: PM document creation failed.\n";
}

// Test 4: Render Printable 2-Page Quotation
ob_start();
$lead = [
    'id' => 999,
    'first_name' => 'Dambaru',
    'last_name' => 'Baka',
    'proposed_capacity_kw' => 3.0,
    'estimated_project_cost' => 222200.00,
    'subsidy_amount' => 78000.00,
    'state_subsidy' => 60000.00,
    'customer_payable_amount' => 84200.00,
    'district' => 'Malkangiri',
    'pincode' => '764044',
];
$customer = $updatedCust;
$quotation = ['quotation_number' => 'DSI/2026-27/0034', 'created_at' => date('Y-m-d')];
include __DIR__ . '/../app/Views/printable/quotation.php';
$quotationHtml = ob_get_clean();

if (strpos($quotationHtml, 'QUOTATION') !== false &&
    strpos($quotationHtml, 'DSI/2026-27/0034') !== false &&
    strpos($quotationHtml, 'BANK DETAILS:-') !== false &&
    strpos($quotationHtml, '222,200') !== false) {
    echo "[ PASS ] Test 4: 2-Page Official Technical Quotation rendered with live company details & signature.\n";
    $passCount++;
} else {
    echo "[ FAIL ] Test 4: Quotation HTML rendering mismatch.\n";
}

// Test 5: Render Printable 4-Page Consumer Agreement (Annexure 2)
ob_start();
include __DIR__ . '/../app/Views/printable/consumer_agreement.php';
$agreementHtml = ob_get_clean();

$esignMatches = substr_count($agreementHtml, 'CONSUMER E-SIGN');
if (strpos($agreementHtml, 'Annexure 2') !== false &&
    strpos($agreementHtml, 'Model Draft Agreement between Consumer & Vendor') !== false &&
    (strpos($agreementHtml, 'NOTIF/2026/MAL/0099') !== false || strpos($agreementHtml, 'PMSGY-OD-2026-MAL-0099') !== false) &&
    $esignMatches >= 4) {
    echo "[ PASS ] Test 5: 4-Page Consumer Agreement (Annexure 2) rendered with E-Sign stamped on ALL 4 pages.\n";
    $passCount++;
} else {
    echo "[ FAIL ] Test 5: Agreement HTML rendering mismatch (E-Sign stamps found: {$esignMatches}).\n";
}

// Test 6: Verify Agreement Modal in Customer Layout & Portal Views
ob_start();
$_SESSION['user_name'] = 'Dambaru Baka';
$_SESSION['customer_code'] = $testCustCode;
$content = '<div>Portal Content</div>';
include __DIR__ . '/../app/Views/layouts/customer.php';
$layoutHtml = ob_get_clean();

ob_start();
$documents = $custDocs;
include __DIR__ . '/../app/Views/customer/documents.php';
$docsViewHtml = ob_get_clean();

if (strpos($layoutHtml, 'id="modalConsumerAgreement"') !== false &&
    strpos($layoutHtml, 'modal-title') !== false &&
    strpos($layoutHtml, 'agreementIframe') !== false &&
    strpos($docsViewHtml, 'data-bs-target="#modalConsumerAgreement"') !== false) {
    echo "[ PASS ] Test 6: Agreement Modal Window is embedded and triggerable across Customer Layout & Documents Locker.\n";
    $passCount++;
} else {
    echo "[ FAIL ] Test 6: Agreement Modal Window missing in Customer views.\n";
}

echo "\n========================================================\n";
echo "SUMMARY: {$passCount}/6 Tests Passed Successfully!\n";
echo "========================================================\n";
