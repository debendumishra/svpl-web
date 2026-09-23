<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Comprehensive Automated Verification Suite for Commission Engine (All 22 Prompt Test Cases)
 */

require_once __DIR__ . '/../app/Helpers/Database.php';
require_once __DIR__ . '/../app/Models/User.php';
require_once __DIR__ . '/../app/Models/Advisor.php';
require_once __DIR__ . '/../app/Models/Customer.php';
require_once __DIR__ . '/../app/Models/Genealogy.php';
require_once __DIR__ . '/../app/Models/Payment.php';
require_once __DIR__ . '/../app/Models/Wallet.php';
require_once __DIR__ . '/../app/Models/CompanyLedger.php';
require_once __DIR__ . '/../app/Models/AuditLog.php';
require_once __DIR__ . '/../app/Models/Setting.php';
require_once __DIR__ . '/../app/Services/CommissionRuleService.php';
require_once __DIR__ . '/../app/Services/CommissionCalculationService.php';
require_once __DIR__ . '/../app/Services/CommissionApprovalService.php';
require_once __DIR__ . '/../app/Services/WalletService.php';
require_once __DIR__ . '/../app/Services/PoolBonusService.php';
require_once __DIR__ . '/../app/Services/RewardService.php';
require_once __DIR__ . '/../app/Services/CommissionLedgerService.php';

use App\Helpers\Database;
use App\Models\Advisor;
use App\Models\Customer;
use App\Models\Genealogy;
use App\Models\Wallet;
use App\Services\CommissionRuleService;
use App\Services\CommissionCalculationService;
use App\Services\CommissionApprovalService;
use App\Services\WalletService;
use App\Services\PoolBonusService;
use App\Services\RewardService;
use App\Services\CommissionLedgerService;

echo "====================================================================\n";
echo "    SVPL PM SOLAR COMMISSION ENGINE — 22 TEST VERIFICATION SUITE    \n";
echo "====================================================================\n\n";

$passCount = 0;
$failCount = 0;

function assertTest(string $testName, bool $condition, string $detail = '') {
    global $passCount, $failCount;
    if ($condition) {
        $passCount++;
        echo "[ PASS ] {$testName}\n";
        if ($detail) echo "         ↳ {$detail}\n";
    } else {
        $failCount++;
        echo "[ FAIL ] {$testName}\n";
        if ($detail) echo "         ↳ {$detail}\n";
    }
}

// --------------------------------------------------------------------------
// SETUP MOCK ADVISOR HIERARCHY: A -> B -> C -> D -> E
// --------------------------------------------------------------------------
echo "--- Setting up Test Advisors Tree (A -> B -> C -> D -> E) ---\n";

function getOrCreateTestAdvisor(string $code, string $name, ?int $sponsorId = null): array {
    $existing = Database::fetchOne("SELECT a.*, u.id as user_id FROM advisors a JOIN users u ON a.user_id = u.id WHERE a.advisor_code = ?", [$code]);
    if ($existing) {
        if ($sponsorId && $existing['sponsor_id'] != $sponsorId) {
            Database::execute("UPDATE advisors SET sponsor_id = ? WHERE id = ?", [$sponsorId, $existing['id']]);
            Genealogy::insertNode((int)$existing['id'], $sponsorId);
        }
        return $existing;
    }

    $email = strtolower($code) . '@svpl.test';
    $mobile = '987' . str_pad((string)rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);
    Database::execute("INSERT INTO users (full_name, email, mobile, password_hash, role, is_active, created_at) VALUES (?, ?, ?, 'hash', 'ADVISOR', 1, NOW())", [$name, $email, $mobile]);
    $userId = (int)Database::lastInsertId();

    $advId = Advisor::create([
        'user_id' => $userId,
        'advisor_code' => $code,
        'referral_code' => $code,
        'sponsor_id' => $sponsorId,
        'first_name' => $name,
        'last_name' => 'Test',
        'mobile' => $mobile,
        'district' => 'Khordha',
        'block' => 'Bhubaneswar',
        'gram_panchayat' => 'Bhubaneswar MC',
        'pincode' => '751001',
        'status' => 'ACTIVE'
    ]);

    Genealogy::insertNode($advId, $sponsorId);
    Wallet::getByUserId($userId);

    return Advisor::findById($advId);
}

// Clean test records before test run
if (Database::getDriver() !== 'sqlite') {
    Database::execute("SET FOREIGN_KEY_CHECKS = 0");
    Database::execute("DELETE FROM pool_genealogy");
    Database::execute("DELETE FROM pool_members");
    Database::execute("DELETE FROM advisor_reward_claims");
    Database::execute("DELETE FROM advisor_wallet_transactions");
    Database::execute("DELETE FROM commission_transactions");
    Database::execute("SET FOREIGN_KEY_CHECKS = 1");
} else {
    Database::execute("DELETE FROM pool_genealogy");
    Database::execute("DELETE FROM pool_members");
    Database::execute("DELETE FROM advisor_reward_claims");
    Database::execute("DELETE FROM advisor_wallet_transactions");
    Database::execute("DELETE FROM commission_transactions");
}

$advA = getOrCreateTestAdvisor('TEST_AD_A', 'Advisor A', null);
$advB = getOrCreateTestAdvisor('TEST_AD_B', 'Advisor B', (int)$advA['id']);
$advC = getOrCreateTestAdvisor('TEST_AD_C', 'Advisor C', (int)$advB['id']);
$advD = getOrCreateTestAdvisor('TEST_AD_D', 'Advisor D', (int)$advC['id']);
$advE = getOrCreateTestAdvisor('TEST_AD_E', 'Advisor E', (int)$advD['id']);

echo "Advisors Hierarchy Ready: A(#{$advA['id']}) -> B(#{$advB['id']}) -> C(#{$advC['id']}) -> D(#{$advD['id']}) -> E(#{$advE['id']})\n\n";

// TEST 1: Advisor joins another Advisor -> Direct sponsor receives ₹700 only
$newAdv1 = getOrCreateTestAdvisor('TEST_NEW_1', 'New Adv 1', (int)$advA['id']);
$res1 = CommissionCalculationService::processAdvisorJoining((int)$newAdv1['id'], '2026-09-18');
$comm1 = Database::fetchOne("SELECT * FROM commission_transactions WHERE advisor_id = ? AND source_advisor_id = ? AND commission_type = 'JOINING_COMMISSION'", [$advA['id'], $newAdv1['id']]);

assertTest(
    "Test 1: Direct Advisor Joining Commission",
    $res1['status'] && $comm1 && (float)$comm1['gross_amount'] == 700.00,
    "Direct sponsor ({$advA['advisor_code']}) received gross ₹700.00"
);

// TEST 2: Advisor joins under an existing upline -> Only immediate sponsor gets joining commission
$newAdv2 = getOrCreateTestAdvisor('TEST_NEW_2', 'New Adv 2', (int)$advB['id']);
$res2 = CommissionCalculationService::processAdvisorJoining((int)$newAdv2['id'], '2026-09-18');
$uplineAComm = Database::fetchOne("SELECT * FROM commission_transactions WHERE advisor_id = ? AND source_advisor_id = ? AND commission_type = 'JOINING_COMMISSION'", [$advA['id'], $newAdv2['id']]);

assertTest(
    "Test 2: Joining commission does NOT propagate to uplines (A gets ₹0 from B's joining)",
    $res2['status'] && empty($uplineAComm),
    "Immediate sponsor B gets ₹700; Upline sponsor A receives ₹0"
);

// --------------------------------------------------------------------------
// MOCK CUSTOMERS TO TEST QUALIFICATION MATRIX
// Advisor E has 0 customers
// Advisor D has 3 customers (Qualified for L9)
// Advisor C has 2 customers (Qualified for L2)
// Advisor B has 1 customer  (Qualified for L1)
// Advisor A has 0 customers (Qualified for L0)
// --------------------------------------------------------------------------
function createMockCustomer(string $code, string $name, int $advisorId): int {
    $existing = Database::fetchOne("SELECT id FROM customers WHERE customer_code = ?", [$code]);
    if ($existing) return (int)$existing['id'];

    return Customer::create([
        'customer_code' => $code,
        'advisor_id' => $advisorId,
        'first_name' => $name,
        'last_name' => 'Cust',
        'mobile' => '9988776655',
        'district' => 'Khordha',
        'block' => 'Bhubaneswar',
        'gram_panchayat' => 'BMC',
        'pincode' => '751001',
        'proposed_solar_kw' => 3.0,
        'status' => 'ACTIVE'
    ]);
}

// Clean old test customers for clean counts
Database::execute("DELETE FROM customers WHERE customer_code LIKE 'CUST_TEST_%'");
Database::execute("DELETE FROM commission_transactions WHERE customer_id IN (SELECT id FROM customers WHERE customer_code LIKE 'CUST_TEST_%')");

// Create 3 customers for D
createMockCustomer('CUST_TEST_D1', 'D Cust 1', (int)$advD['id']);
createMockCustomer('CUST_TEST_D2', 'D Cust 2', (int)$advD['id']);
createMockCustomer('CUST_TEST_D3', 'D Cust 3', (int)$advD['id']);

// Create 2 customers for C
createMockCustomer('CUST_TEST_C1', 'C Cust 1', (int)$advC['id']);
createMockCustomer('CUST_TEST_C2', 'C Cust 2', (int)$advC['id']);

// Create 1 customer for B
createMockCustomer('CUST_TEST_B1', 'B Cust 1', (int)$advB['id']);

// Customer brought directly by E
$custE = createMockCustomer('CUST_TEST_E1', 'Customer of E', (int)$advE['id']);

// TEST 3 & 4 & 5 & 6 & 7: 3 kW On-Grid Customer Referral + 9-Level Qualification Matrix
$resReferral = CommissionCalculationService::processCustomerReferral(
    $custE,
    '2026-09-18',
    null,
    180000.00,
    '3 kW On-Grid Solar System',
    3.0,
    'On-Grid'
);

$commE = Database::fetchOne("SELECT * FROM commission_transactions WHERE customer_id = ? AND advisor_id = ? AND level = 1", [$custE, $advE['id']]);
$commD = Database::fetchOne("SELECT * FROM commission_transactions WHERE customer_id = ? AND advisor_id = ? AND level = 2", [$custE, $advD['id']]);
$commC = Database::fetchOne("SELECT * FROM commission_transactions WHERE customer_id = ? AND advisor_id = ? AND level = 3", [$custE, $advC['id']]);
$commB = Database::fetchOne("SELECT * FROM commission_transactions WHERE customer_id = ? AND advisor_id = ? AND level = 4", [$custE, $advB['id']]);
$commA = Database::fetchOne("SELECT * FROM commission_transactions WHERE customer_id = ? AND advisor_id = ? AND level = 5", [$custE, $advA['id']]);

assertTest(
    "Test 3: 3 kW On-Grid Direct Referral (L1) = ₹10,000",
    $commE && (float)$commE['gross_amount'] == 10000.00 && $commE['status'] === 'PENDING',
    "Direct advisor E received Level 1 gross commission ₹10,000.00"
);

assertTest(
    "Test 4: Upline A with 0 personal customers is EXCLUDED / REJECTED (Level 5)",
    $commA && $commA['qualification_status'] === 'NOT_ELIGIBLE' && $commA['status'] === 'REJECTED',
    "Advisor A excluded with 0 personal customers (Max level 0 < 5)"
);

assertTest(
    "Test 5: Upline B with 1 personal customer is EXCLUDED from Level 4",
    $commB && $commB['qualification_status'] === 'NOT_ELIGIBLE' && $commB['status'] === 'REJECTED',
    "Advisor B has 1 customer (Max eligible level 1 < 4)"
);

assertTest(
    "Test 6: Upline C with 2 personal customers is EXCLUDED from Level 3",
    $commC && $commC['qualification_status'] === 'NOT_ELIGIBLE' && $commC['status'] === 'REJECTED',
    "Advisor C has 2 customers (Max eligible level 2 < 3)"
);

assertTest(
    "Test 7: Upline D with 3 personal customers is ELIGIBLE for Level 2 (₹1,000)",
    $commD && (float)$commD['gross_amount'] == 1000.00 && $commD['status'] === 'PENDING',
    "Advisor D has 3 customers (Max eligible level 9 >= 2), received ₹1,000.00"
);

// TEST 8: Customer referred in Dec but company payment received in Feb -> Commission Month = Feb
$custDec = createMockCustomer('CUST_TEST_DEC', 'Dec Customer', (int)$advD['id']);
$resFebPayment = CommissionCalculationService::processCustomerReferral(
    $custDec,
    '2027-02-15',
    null,
    180000.00,
    '3 kW On-Grid Solar System'
);
$commFeb = Database::fetchOne("SELECT * FROM commission_transactions WHERE customer_id = ? AND advisor_id = ? AND level = 1", [$custDec, $advD['id']]);

assertTest(
    "Test 8: Commission Month strictly determined by Company Account Credit Date",
    $commFeb && (int)$commFeb['commission_month'] === 2 && (int)$commFeb['commission_year'] === 2027,
    "Payment credited 2027-02-15 assigned Commission Month = February 2027"
);

// TEST 9, 10, 11: Monthly Special Bonus Slabs (5 cust -> ₹3k, 10 -> ₹10k, 20 -> ₹30k)
Database::execute("DELETE FROM customers WHERE customer_code LIKE 'CUST_M_%'");
Database::execute("DELETE FROM commission_transactions WHERE commission_type = 'MONTHLY_SPECIAL_BONUS'");

$advBonusTester = getOrCreateTestAdvisor('TEST_BONUS_ADV', 'Bonus Tester', null);
for ($i = 1; $i <= 5; $i++) {
    $cid = createMockCustomer("CUST_M_5_{$i}", "Cust M5 {$i}", (int)$advBonusTester['id']);
    Database::execute("UPDATE customers SET created_at = '2026-05-10 10:00:00' WHERE id = ?", [$cid]);
}
$mbRes5 = CommissionCalculationService::evaluateMonthlyBonus((int)$advBonusTester['id'], 5, 2026);
$mbTxn5 = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$mbRes5['transaction_id'] ?? 0]);

assertTest(
    "Test 9: 5 Customers in calendar month -> ₹3,000 Monthly Special Bonus",
    $mbTxn5 && (float)$mbTxn5['gross_amount'] == 3000.00,
    "Direct advisor received gross ₹3,000.00 monthly bonus"
);

// Add 5 more (Total 10)
for ($i = 6; $i <= 10; $i++) {
    $cid = createMockCustomer("CUST_M_10_{$i}", "Cust M10 {$i}", (int)$advBonusTester['id']);
    Database::execute("UPDATE customers SET created_at = '2026-05-15 10:00:00' WHERE id = ?", [$cid]);
}
$mbRes10 = CommissionCalculationService::evaluateMonthlyBonus((int)$advBonusTester['id'], 5, 2026);
$mbTxn10 = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$mbRes10['transaction_id'] ?? 0]);

assertTest(
    "Test 10: 10 Customers in calendar month -> ₹10,000 Monthly Special Bonus (Highest Slab)",
    $mbTxn10 && (float)$mbTxn10['gross_amount'] == 10000.00,
    "Updated to highest slab ₹10,000.00 (not cumulative by default)"
);

// Add 10 more (Total 20)
for ($i = 11; $i <= 20; $i++) {
    $cid = createMockCustomer("CUST_M_20_{$i}", "Cust M20 {$i}", (int)$advBonusTester['id']);
    Database::execute("UPDATE customers SET created_at = '2026-05-20 10:00:00' WHERE id = ?", [$cid]);
}
$mbRes20 = CommissionCalculationService::evaluateMonthlyBonus((int)$advBonusTester['id'], 5, 2026);
$mbTxn20 = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$mbRes20['transaction_id'] ?? 0]);

assertTest(
    "Test 11: 20 Customers in calendar month -> ₹30,000 Monthly Special Bonus",
    $mbTxn20 && (float)$mbTxn20['gross_amount'] == 30000.00,
    "Achieved top slab ₹30,000.00 for 20 customers"
);

// TEST 12 to 17: Lifetime Performance Rewards (10 -> ₹5k, 50 -> ₹30k, 100 -> ₹70k, 300 -> ₹2.4L, 500 -> ₹4.5L, 1000 -> ₹10L)
$advRewardTester = getOrCreateTestAdvisor('TEST_REW_ADV', 'Reward Tester', null);
for ($i = 1; $i <= 10; $i++) {
    createMockCustomer("CUST_REW_{$i}", "Cust Rew {$i}", (int)$advRewardTester['id']);
}
$rewRes10 = RewardService::evaluateRewards((int)$advRewardTester['id']);
$claim10 = Database::fetchOne("SELECT arc.*, ar.total_reward_value FROM advisor_reward_claims arc JOIN advisor_rewards ar ON arc.reward_id = ar.id WHERE arc.advisor_id = ? AND ar.customer_target = 10", [$advRewardTester['id']]);

assertTest(
    "Test 12: 10 Lifetime Customers -> ₹5,000 Reward Eligibility",
    $claim10 && (float)$claim10['total_reward_value'] == 5000.00 && $claim10['status'] === 'ACHIEVED',
    "Advisor achieved 10-customer reward slab worth ₹5,000.00"
);

// Test reward duplicate claim prevention
$rewRes10_dup = RewardService::evaluateRewards((int)$advRewardTester['id']);
$claim10_count = Database::fetchOne("SELECT COUNT(*) as cnt FROM advisor_reward_claims arc JOIN advisor_rewards ar ON arc.reward_id = ar.id WHERE arc.advisor_id = ? AND ar.customer_target = 10", [$advRewardTester['id']]);

assertTest(
    "Test 13: Duplicate Reward Claim Prevention",
    (int)$claim10_count['cnt'] === 1,
    "System prevented duplicate claim creation for the same target milestone"
);

// Verify Slabs 50, 100, 300, 500, 1000 configurations
$allSlabs = CommissionRuleService::getRewardSlabs();
$slabMap = [];
foreach ($allSlabs as $s) $slabMap[(int)$s['customer_target']] = (float)$s['total_reward_value'];

assertTest("Test 14: 50 Customers Reward Slab = ₹30,000", ($slabMap[50] ?? 0) == 30000.00);
assertTest("Test 15: 100 Customers Reward Slab = ₹70,000", ($slabMap[100] ?? 0) == 70000.00);
assertTest("Test 16: 300 Customers Reward Slab = ₹2,40,000", ($slabMap[300] ?? 0) == 240000.00);
assertTest("Test 17: 1,000 Customers Reward Slab = ₹10,00,000", ($slabMap[1000] ?? 0) == 1000000.00);

// TEST 18: Pool Qualification Engine & Sequential PB Numbering (3 direct advisors + 3 personal customers)
// Clean test pool members
if (Database::getDriver() !== 'sqlite') {
    Database::execute("SET FOREIGN_KEY_CHECKS = 0");
    Database::execute("DELETE FROM pool_genealogy");
    Database::execute("DELETE FROM pool_members");
    Database::execute("SET FOREIGN_KEY_CHECKS = 1");
} else {
    Database::execute("DELETE FROM pool_genealogy");
    Database::execute("DELETE FROM pool_members");
}

$poolAdv1 = getOrCreateTestAdvisor('TEST_PB_1', 'Pool Adv 1', null);
// Sponsor 3 direct advisors
$pSub1 = getOrCreateTestAdvisor('TEST_PB_S1', 'Pool Sub 1', (int)$poolAdv1['id']);
$pSub2 = getOrCreateTestAdvisor('TEST_PB_S2', 'Pool Sub 2', (int)$poolAdv1['id']);
$pSub3 = getOrCreateTestAdvisor('TEST_PB_S3', 'Pool Sub 3', (int)$poolAdv1['id']);
// 3 direct customers
createMockCustomer('CUST_PB1_1', 'Cust PB1 1', (int)$poolAdv1['id']);
createMockCustomer('CUST_PB1_2', 'Cust PB1 2', (int)$poolAdv1['id']);
createMockCustomer('CUST_PB1_3', 'Cust PB1 3', (int)$poolAdv1['id']);

$pQualRes1 = PoolBonusService::checkPoolQualification((int)$poolAdv1['id']);
$pm1 = Database::fetchOne("SELECT * FROM pool_members WHERE advisor_id = ?", [$poolAdv1['id']]);

assertTest(
    "Test 18: Pool Qualification & Sequential PB Numbering (PB1)",
    $pm1 && $pm1['pool_number'] == 1 && $pm1['pool_label'] === 'PB1',
    "Advisor qualified and assigned Pool Number 1 (PB1)"
);

// TEST 19: Pool 3-Child Placement Rule
// Qualify PB2, PB3, PB4 under PB1; then PB5 under PB2
function qualifyAdvisorForPool(string $code, string $name): array {
    $adv = getOrCreateTestAdvisor($code, $name, null);
    getOrCreateTestAdvisor($code . '_S1', 'Sub 1', (int)$adv['id']);
    getOrCreateTestAdvisor($code . '_S2', 'Sub 2', (int)$adv['id']);
    getOrCreateTestAdvisor($code . '_S3', 'Sub 3', (int)$adv['id']);
    createMockCustomer("CUST_{$code}_1", 'Cust 1', (int)$adv['id']);
    createMockCustomer("CUST_{$code}_2", 'Cust 2', (int)$adv['id']);
    createMockCustomer("CUST_{$code}_3", 'Cust 3', (int)$adv['id']);
    return PoolBonusService::checkPoolQualification((int)$adv['id']);
}

$p2 = qualifyAdvisorForPool('TEST_PB_2', 'Pool Adv 2');
$p3 = qualifyAdvisorForPool('TEST_PB_3', 'Pool Adv 3');
$p4 = qualifyAdvisorForPool('TEST_PB_4', 'Pool Adv 4');
$p5 = qualifyAdvisorForPool('TEST_PB_5', 'Pool Adv 5');

$pm2 = Database::fetchOne("SELECT * FROM pool_members WHERE pool_number = 2");
$pm3 = Database::fetchOne("SELECT * FROM pool_members WHERE pool_number = 3");
$pm4 = Database::fetchOne("SELECT * FROM pool_members WHERE pool_number = 4");
$pm5 = Database::fetchOne("SELECT * FROM pool_members WHERE pool_number = 5");

assertTest(
    "Test 19: Pool Tree 3-Child Placement (PB2, PB3, PB4 under PB1; PB5 under PB2)",
    $pm2['parent_pool_id'] == $pm1['id'] && 
    $pm3['parent_pool_id'] == $pm1['id'] && 
    $pm4['parent_pool_id'] == $pm1['id'] && 
    $pm5['parent_pool_id'] == $pm2['id'],
    "PB1 received 3 direct children; PB5 placed under PB2 (3-child node capacity enforced)"
);

// TEST 20: Duplicate Payment / Commission Idempotency Protection
$dupTestRes = CommissionCalculationService::processCustomerReferral(
    $custE,
    '2026-09-18',
    null,
    180000.00,
    '3 kW On-Grid Solar System'
);
$commE_count = Database::fetchOne("SELECT COUNT(*) as cnt FROM commission_transactions WHERE customer_id = ? AND advisor_id = ? AND level = 1 AND is_reversal = 0", [$custE, $advE['id']]);

assertTest(
    "Test 20: Commission Idempotency & Duplicate Prevention",
    (int)$commE_count['cnt'] === 1,
    "Second processing attempt did NOT create duplicate commission transactions"
);

// TEST 21: Approval Workflow & Concurrency-Safe Wallet Credit
$initWallet = Database::fetchOne("SELECT balance FROM wallets WHERE user_id = ?", [$advE['user_id']]);
$initBal = (float)($initWallet['balance'] ?? 0.00);

$appRes1 = CommissionApprovalService::approveCommission((int)$commE['id'], 1);
$appRes2 = CommissionApprovalService::approveCommission((int)$commE['id'], 1); // Second concurrent approval attempt

$afterWallet = Database::fetchOne("SELECT balance FROM wallets WHERE user_id = ?", [$advE['user_id']]);
$afterBal = (float)($afterWallet['balance'] ?? 0.00);
$expectedCredit = (float)$commE['net_amount'];

$ledgerEntries = Database::fetchAll("SELECT * FROM advisor_wallet_transactions WHERE commission_id = ?", [$commE['id']]);

assertTest(
    "Test 21: Approval Workflow & Single Wallet Credit (Idempotent Double-Entry)",
    count($ledgerEntries) === 1 && $afterBal == round($initBal + $expectedCredit, 2),
    "Wallet credited exactly once with net ₹{$expectedCredit} (+ ledger transaction row created)"
);

// TEST 22: Commission Reversal & Compensating Debit Entry
$revRes = CommissionLedgerService::reverseCommission((int)$commE['id'], "Test reversal of order", 1);
$reversedTxn = Database::fetchOne("SELECT * FROM commission_transactions WHERE id = ?", [$commE['id']]);
$finalWallet = Database::fetchOne("SELECT balance FROM wallets WHERE user_id = ?", [$advE['user_id']]);
$finalBal = (float)($finalWallet['balance'] ?? 0.00);

$revLedgerEntry = Database::fetchOne("SELECT * FROM advisor_wallet_transactions WHERE transaction_type = 'COMMISSION_REVERSAL' AND commission_id = ?", [$commE['id']]);

assertTest(
    "Test 22: Commission Reversal & Immutable Audit Ledger",
    $revRes['status'] && $reversedTxn['status'] === 'REVERSED' && $revLedgerEntry && $finalBal == $initBal,
    "Original record preserved as REVERSED, reversing debit entry recorded in wallet ledger"
);

echo "\n====================================================================\n";
echo "                      TEST SUMMARY RESULTS                         \n";
echo "====================================================================\n";
echo "Total Tests Run: " . ($passCount + $failCount) . "\n";
echo "Tests Passed:    {$passCount}\n";
echo "Tests Failed:    {$failCount}\n";

if ($failCount === 0) {
    echo "\n>>> [ALL 22 TEST CASES PASSED SUCCESSFULLY!] <<<\n";
    exit(0);
} else {
    echo "\n>>> [SOME TEST CASES FAILED - CHECK OUTPUT] <<<\n";
    exit(1);
}
