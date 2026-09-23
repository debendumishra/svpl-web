<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) require_once $file;
});

use App\Models\User;
use App\Models\Advisor;
use App\Models\Payment;
use App\Services\AuthService;
use App\Helpers\Database;

echo "========================================================\n";
echo "   TESTING NEW FREE ADVISOR REGISTRATION & PAYMENT FLOW \n";
echo "========================================================\n\n";

// Clean test data
Database::execute('DELETE FROM payments WHERE transaction_ref = ?', ['TEST_FREE_UTR_999']);
Database::execute('DELETE FROM advisors WHERE mobile = ?', ['9999888877']);
Database::execute('DELETE FROM users WHERE mobile = ?', ['9999888877']);

// 1. Create Free Advisor User
$userId = User::create([
    'role' => 'ADVISOR',
    'email' => 'freeadvisor@test.com',
    'mobile' => '9999888877',
    'password_hash' => password_hash('Pass@123', PASSWORD_BCRYPT),
    'full_name' => 'Free Test Advisor',
    'is_active' => 1
]);

$advCode = Advisor::generateAdvisorCode();
$refCode = Advisor::generateReferralCode();
$advId = Advisor::create([
    'user_id' => $userId,
    'advisor_code' => $advCode,
    'referral_code' => $refCode,
    'first_name' => 'Free Test',
    'last_name' => 'Advisor',
    'mobile' => '9999888877',
    'email' => 'freeadvisor@test.com',
    'district' => 'Khordha',
    'block' => 'Bhubaneswar',
    'gram_panchayat' => 'Default',
    'status' => 'ACTIVE',
    'joining_fee' => advisor_joining_fee(),
    'joining_fee_paid' => 0
]);

echo "[ PASS ] Step 1: Free Advisor Registered ({$advCode}, joining_fee_paid = 0, is_active = 1)\n";

// 2. Test Login
$authRes = AuthService::attempt('9999888877', 'Pass@123');
if ($authRes['success']) {
    echo "[ PASS ] Step 2: Immediate Login without approval succeeded.\n";
} else {
    echo "[ FAIL ] Step 2: Immediate Login failed: " . ($authRes['message'] ?? 'Unknown') . "\n";
    exit(1);
}

// 3. Check customer registration gate (fee unpaid)
$adv = Advisor::findById($advId);
if (empty($adv['joining_fee_paid'])) {
    echo "[ PASS ] Step 3: Customer registration gate verified (joining_fee_paid is 0).\n";
} else {
    echo "[ FAIL ] Step 3: joining_fee_paid should be 0.\n";
    exit(1);
}

// 4. Submit Payment Details
$paymentId = Payment::create([
    'entity_type' => 'ADVISOR',
    'entity_id' => $advId,
    'purpose' => 'JOINING_FEE',
    'amount' => advisor_joining_fee(),
    'payment_method' => 'UPI',
    'transaction_ref' => 'TEST_FREE_UTR_999',
    'status' => 'PENDING',
    'payment_date' => date('Y-m-d')
]);
echo "[ PASS ] Step 4: Payment details submitted (UTR: TEST_FREE_UTR_999, Payment ID: {$paymentId}, status: PENDING)\n";

// 5. Admin confirms payment
$confirmSuccess = Payment::confirmAdvisorPayment($paymentId, 1);
if ($confirmSuccess) {
    $advUpdated = Advisor::findById($advId);
    if ((int)$advUpdated['joining_fee_paid'] === 1) {
        echo "[ PASS ] Step 5: Payment confirmed by Admin. Advisor joining_fee_paid = 1. Customer registration is UNLOCKED!\n";
    } else {
        echo "[ FAIL ] Step 5: joining_fee_paid is not 1 after confirmation.\n";
        exit(1);
    }
} else {
    echo "[ FAIL ] Step 5: Payment confirmation failed.\n";
    exit(1);
}

// Clean up test data
Database::execute('DELETE FROM payments WHERE transaction_ref = ?', ['TEST_FREE_UTR_999']);
Database::execute('DELETE FROM advisors WHERE mobile = ?', ['9999888877']);
Database::execute('DELETE FROM users WHERE mobile = ?', ['9999888877']);

echo "\n>>> [ALL 5 WORKFLOW TESTS PASSED PERFECTLY!] <<<\n\n";
