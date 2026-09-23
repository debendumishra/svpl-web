<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * CommissionRuleService - Central Rule Configuration & Versioning Engine
 */

namespace App\Services;

use App\Helpers\Database;
use App\Models\Setting;

class CommissionRuleService
{
    /**
     * Get Advisor Joining Fee Settings
     */
    public static function getJoiningSettings(): array
    {
        return [
            'joining_fee' => (float) Setting::get('advisor_joining_fee', 2700.00),
            'direct_commission' => (float) Setting::get('advisor_direct_joining_commission', 700.00),
            'upline_commission' => (float) Setting::get('advisor_upline_joining_commission', 0.00),
            'enabled' => (bool) Setting::get('joining_commission_enabled', 1),
            'trigger_event' => Setting::get('commission_trigger_event', 'COMPANY_CREDIT'),
            'approval_mode' => Setting::get('commission_approval_mode', 'MANUAL'),
            'tds_percentage' => (float) Setting::get('tds_percentage', 5.00),
            'admin_deduction_percentage' => (float) Setting::get('admin_deduction_percentage', 0.00),
            'min_withdrawal' => (float) Setting::get('min_wallet_withdrawal', 500.00),
        ];
    }

    /**
     * Save Advisor Joining Settings
     */
    public static function saveJoiningSettings(array $data, ?int $adminId = null): bool
    {
        if (isset($data['joining_fee'])) Setting::set('advisor_joining_fee', (string)$data['joining_fee'], 'commission');
        if (isset($data['direct_commission'])) Setting::set('advisor_direct_joining_commission', (string)$data['direct_commission'], 'commission');
        if (isset($data['upline_commission'])) Setting::set('advisor_upline_joining_commission', (string)$data['upline_commission'], 'commission');
        if (isset($data['enabled'])) Setting::set('joining_commission_enabled', (string)(int)$data['enabled'], 'commission');
        if (isset($data['trigger_event'])) Setting::set('commission_trigger_event', (string)$data['trigger_event'], 'commission');
        if (isset($data['approval_mode'])) Setting::set('commission_approval_mode', (string)$data['approval_mode'], 'commission');
        if (isset($data['tds_percentage'])) Setting::set('tds_percentage', (string)$data['tds_percentage'], 'commission');
        if (isset($data['admin_deduction_percentage'])) Setting::set('admin_deduction_percentage', (string)$data['admin_deduction_percentage'], 'commission');
        if (isset($data['min_withdrawal'])) Setting::set('min_wallet_withdrawal', (string)$data['min_withdrawal'], 'commission');

        self::logAudit($adminId, 'UPDATE_JOINING_SETTINGS', 'SETTINGS', null, null, json_encode($data), 'Updated joining commission settings');
        return true;
    }

    /**
     * Find best matching active commission rule for a solar product / capacity / connection type / package_id
     */
    public static function matchProductRule(?string $productName = null, ?float $capacityKw = null, ?string $connectionType = null, ?int $packageId = null): array
    {
        $allRules = Database::fetchAll("SELECT * FROM commission_rules WHERE is_active = 1 ORDER BY version DESC, id DESC");
        $matched = null;

        // Priority 1: Match by package_id
        if ($packageId) {
            foreach ($allRules as $r) {
                if (!empty($r['package_id']) && (int)$r['package_id'] === $packageId) {
                    $matched = $r;
                    break;
                }
            }
        }

        // Priority 2: Exact product name match
        if (!$matched && $productName) {
            foreach ($allRules as $r) {
                if (!empty($r['product_name']) && (strcasecmp(trim($r['product_name']), trim($productName)) === 0 || stripos(trim($r['product_name']), trim($productName)) !== false || stripos(trim($productName), trim($r['product_name'])) !== false)) {
                    $matched = $r;
                    break;
                }
            }
        }

        // Priority 3: Capacity + Connection type match
        if (!$matched && $capacityKw && $connectionType) {
            foreach ($allRules as $r) {
                if ((float)$r['capacity_kw'] == (float)$capacityKw && strcasecmp(trim($r['connection_type'] ?? ''), trim($connectionType)) === 0) {
                    $matched = $r;
                    break;
                }
            }
        }

        // Priority 4: Connection type match
        if (!$matched && $connectionType) {
            foreach ($allRules as $r) {
                if (strcasecmp(trim($r['connection_type'] ?? ''), trim($connectionType)) === 0) {
                    $matched = $r;
                    break;
                }
            }
        }

        // Priority 5: Capacity match
        if (!$matched && $capacityKw) {
            foreach ($allRules as $r) {
                if ((float)$r['capacity_kw'] == (float)$capacityKw) {
                    $matched = $r;
                    break;
                }
            }
        }

        // Priority 6: Fallback rule
        if (!$matched && !empty($allRules)) {
            $matched = $allRules[0];
        }

        if (!$matched) {
            // Safe in-memory fallback
            return [
                'id' => 0,
                'package_id' => null,
                'rule_code' => 'FALLBACK_DEFAULT',
                'rule_name' => 'Default 3 kW Rule',
                'direct_commission' => 10000.00,
                'version' => 1,
                'levels' => [
                    1 => 10000.00,
                    2 => 1000.00,
                    3 => 500.00, 4 => 500.00, 5 => 500.00,
                    6 => 500.00, 7 => 500.00, 8 => 500.00, 9 => 500.00
                ]
            ];
        }

        // Fetch Level Slabs
        $levels = Database::fetchAll("SELECT level, commission_amount FROM commission_rule_levels WHERE rule_id = ? ORDER BY level ASC", [$matched['id']]);
        $levelMap = [];
        foreach ($levels as $l) {
            $levelMap[(int)$l['level']] = (float)$l['commission_amount'];
        }

        // Ensure defaults if missing
        if (!isset($levelMap[1])) $levelMap[1] = (float)$matched['direct_commission'];
        if (!isset($levelMap[2])) $levelMap[2] = 1000.00;
        for ($lvl = 3; $lvl <= 9; $lvl++) {
            if (!isset($levelMap[$lvl])) $levelMap[$lvl] = 500.00;
        }

        $matched['levels'] = $levelMap;
        return $matched;
    }

    /**
     * Get all active Product Rules directly mapped from packages table
     */
    public static function getAllProductRules(): array
    {
        $sql = "SELECT p.id as package_id, p.package_code, p.brand, p.title as package_title, 
                       p.capacity_kw, p.system_type, p.total_price, p.estimated_subsidy, p.net_customer_cost, p.is_active as package_is_active,
                       cr.id as rule_id, cr.rule_code, cr.rule_name, cr.direct_commission, cr.version, cr.is_active as rule_is_active, cr.effective_from, cr.notes
                FROM packages p
                LEFT JOIN commission_rules cr ON (cr.package_id = p.id OR (cr.package_id IS NULL AND (cr.rule_code = CONCAT('RULE_', p.package_code) OR (cr.capacity_kw = p.capacity_kw AND cr.connection_type = p.system_type))))
                ORDER BY p.brand ASC, p.capacity_kw ASC, p.total_price ASC";
        
        $rows = Database::fetchAll($sql);
        $results = [];

        foreach ($rows as $r) {
            $cap = (float)($r['capacity_kw'] ?? 3.0);
            $sysType = $r['system_type'] ?? 'On-Grid';
            
            // Standard default tiered rates
            if ($cap <= 3.0) {
                $defaultDirect = ($sysType === 'Hybrid') ? 15000.00 : 10000.00;
            } elseif ($cap <= 5.0) {
                $defaultDirect = ($sysType === 'Hybrid') ? 20000.00 : 15000.00;
            } else {
                $defaultDirect = ($sysType === 'Hybrid') ? 30000.00 : 25000.00;
            }

            $directComm = !empty($r['direct_commission']) ? (float)$r['direct_commission'] : $defaultDirect;
            $levelMap = [
                1 => $directComm,
                2 => 1000.00,
                3 => 500.00, 4 => 500.00, 5 => 500.00,
                6 => 500.00, 7 => 500.00, 8 => 500.00, 9 => 500.00
            ];

            if (!empty($r['rule_id'])) {
                $levels = Database::fetchAll(
                    "SELECT level, commission_amount FROM commission_rule_levels WHERE rule_id = ? ORDER BY level ASC",
                    [$r['rule_id']]
                );
                foreach ($levels as $l) {
                    $levelMap[(int)$l['level']] = (float)$l['commission_amount'];
                }
            }

            $r['id'] = $r['rule_id'] ?? 0;
            $r['direct_commission'] = $levelMap[1] ?? $directComm;
            $r['version'] = $r['version'] ?? 1;
            $r['is_active'] = isset($r['rule_is_active']) ? (int)$r['rule_is_active'] : (int)$r['package_is_active'];
            $r['connection_type'] = $sysType;
            $r['product_name'] = $r['package_title'];
            $r['rule_name'] = $r['rule_name'] ?: $r['package_title'];
            $r['levels'] = $levelMap;

            $results[] = $r;
        }

        return $results;
    }

    /**
     * Save / Update a Product Rule linked to Packages table
     */
    public static function saveProductRule(array $data, ?int $adminId = null): int
    {
        $packageId = !empty($data['package_id']) ? (int)$data['package_id'] : null;
        $ruleId = !empty($data['id']) ? (int)$data['id'] : 0;
        
        $pkg = $packageId ? Database::fetchOne("SELECT * FROM packages WHERE id = ?", [$packageId]) : null;

        $name = trim($data['rule_name'] ?? ($pkg['title'] ?? 'Solar Product Rule'));
        $code = $pkg ? ('RULE_' . $pkg['package_code']) : ('PROD_' . strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $name)));
        $product = $pkg ? $pkg['title'] : trim($data['product_name'] ?? $name);
        $capacity = $pkg ? (float)$pkg['capacity_kw'] : (!empty($data['capacity_kw']) ? (float)$data['capacity_kw'] : 3.0);
        $connType = $pkg ? $pkg['system_type'] : trim($data['connection_type'] ?? 'On-Grid');
        $directComm = !empty($data['direct_commission']) ? (float)$data['direct_commission'] : 10000.00;
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;
        $effectiveFrom = !empty($data['effective_from']) ? $data['effective_from'] : date('Y-m-d');
        $notes = $data['notes'] ?? '';

        // If ruleId is not passed but package_id is passed, find existing rule for package_id
        if ($ruleId <= 0 && $packageId > 0) {
            $existingRule = Database::fetchOne("SELECT id FROM commission_rules WHERE package_id = ? OR rule_code = ?", [$packageId, $code]);
            if ($existingRule) {
                $ruleId = (int)$existingRule['id'];
            }
        }

        if ($ruleId > 0) {
            $oldRule = Database::fetchOne("SELECT * FROM commission_rules WHERE id = ?", [$ruleId]);
            $newVersion = ($oldRule['version'] ?? 1) + 1;

            Database::execute(
                "UPDATE commission_rules SET 
                    package_id = ?, rule_code = ?, rule_name = ?, product_name = ?, capacity_kw = ?, connection_type = ?, 
                    direct_commission = ?, version = ?, is_active = ?, effective_from = ?, notes = ?, updated_at = NOW() 
                 WHERE id = ?",
                [$packageId, $code, $name, $product, $capacity, $connType, $directComm, $newVersion, $isActive, $effectiveFrom, $notes, $ruleId]
            );

            self::logAudit($adminId, 'UPDATE_PRODUCT_RULE', 'COMMISSION_RULE', $ruleId, json_encode($oldRule), json_encode($data), "Updated product commission rule for package #{$packageId}");
        } else {
            Database::execute(
                "INSERT INTO commission_rules (package_id, rule_code, rule_name, rule_type, product_name, capacity_kw, connection_type, direct_commission, version, is_active, effective_from, notes, created_at) 
                 VALUES (?, ?, ?, 'PRODUCT_REFERRAL', ?, ?, ?, ?, 1, ?, ?, ?, NOW())",
                [$packageId, $code, $name, $product, $capacity, $connType, $directComm, $isActive, $effectiveFrom, $notes]
            );
            $ruleId = (int)Database::lastInsertId();
            self::logAudit($adminId, 'CREATE_PRODUCT_RULE', 'COMMISSION_RULE', $ruleId, null, json_encode($data), "Created product commission rule for package #{$packageId}");
        }

        // Update Level Slabs
        Database::execute("DELETE FROM commission_rule_levels WHERE rule_id = ?", [$ruleId]);
        
        // Level 1 = Direct Commission
        Database::execute("INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, 1, ?, 'FIXED')", [$ruleId, $directComm]);

        // Level 2
        $l2 = isset($data['level_2']) ? (float)$data['level_2'] : 1000.00;
        Database::execute("INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, 2, ?, 'FIXED')", [$ruleId, $l2]);

        // Levels 3..9
        for ($lvl = 3; $lvl <= 9; $lvl++) {
            $amt = isset($data['level_' . $lvl]) ? (float)$data['level_' . $lvl] : (isset($data['level_3_9']) ? (float)$data['level_3_9'] : 500.00);
            Database::execute("INSERT INTO commission_rule_levels (rule_id, level, commission_amount, calculation_type) VALUES (?, ?, ?, 'FIXED')", [$ruleId, $lvl, $amt]);
        }

        return $ruleId;
    }

    /**
     * Get Upline Qualification Matrix
     */
    public static function getUplineQualifications(): array
    {
        return Database::fetchAll("SELECT * FROM commission_upline_qualifications WHERE is_active = 1 ORDER BY min_personal_customers ASC");
    }

    /**
     * Determine maximum eligible upline level based on personal lifetime customers
     */
    public static function getMaxEligibleLevel(int $personalCustomerCount): int
    {
        $matrix = self::getUplineQualifications();
        $maxLevel = 0;
        foreach ($matrix as $row) {
            if ($personalCustomerCount >= (int)$row['min_personal_customers']) {
                $maxLevel = max($maxLevel, (int)$row['max_eligible_level']);
            }
        }
        return $maxLevel;
    }

    /**
     * Save Upline Qualification Matrix
     */
    public static function saveUplineQualificationMatrix(array $rows, ?int $adminId = null): bool
    {
        Database::execute("DELETE FROM commission_upline_qualifications");
        foreach ($rows as $r) {
            Database::execute(
                "INSERT INTO commission_upline_qualifications (min_personal_customers, max_eligible_level, description, is_active) VALUES (?, ?, ?, 1)",
                [(int)$r['min_personal_customers'], (int)$r['max_eligible_level'], $r['description'] ?? '']
            );
        }
        self::logAudit($adminId, 'UPDATE_UPLINE_QUALIFICATION_MATRIX', 'QUALIFICATION_MATRIX', null, null, json_encode($rows), 'Updated upline qualification matrix');
        return true;
    }

    /**
     * Get Customer Monthly Special Bonus Slabs
     */
    public static function getMonthlyBonusSlabs(): array
    {
        return Database::fetchAll("SELECT * FROM customer_monthly_bonus_rules WHERE is_active = 1 ORDER BY min_customers ASC");
    }

    /**
     * Save Monthly Bonus Slabs
     */
    public static function saveMonthlyBonusSlabs(array $slabs, ?int $adminId = null): bool
    {
        Database::execute("DELETE FROM customer_monthly_bonus_rules");
        foreach ($slabs as $s) {
            Database::execute(
                "INSERT INTO customer_monthly_bonus_rules (min_customers, max_customers, bonus_amount, calculation_mode, is_active) VALUES (?, ?, ?, ?, 1)",
                [(int)$s['min_customers'], !empty($s['max_customers']) ? (int)$s['max_customers'] : null, (float)$s['bonus_amount'], $s['calculation_mode'] ?? 'HIGHEST_SLAB']
            );
        }
        self::logAudit($adminId, 'UPDATE_MONTHLY_BONUS_SLABS', 'MONTHLY_BONUS_RULES', null, null, json_encode($slabs), 'Updated monthly customer bonus slabs');
        return true;
    }

    /**
     * Get Pool Bonus Configuration
     */
    public static function getPoolBonusRule(): array
    {
        $rule = Database::fetchOne("SELECT * FROM pool_bonus_rules WHERE is_active = 1 ORDER BY id DESC LIMIT 1");
        if (!$rule) {
            return [
                'min_direct_advisors' => 3,
                'min_personal_customers' => 3,
                'max_children_per_node' => 3,
                'max_pool_levels' => 11,
                'level_1_amount' => 1000.00,
                'subsequent_level_amount' => 500.00,
                'is_active' => 1
            ];
        }
        return $rule;
    }

    /**
     * Save Pool Bonus Configuration
     */
    public static function savePoolBonusRule(array $data, ?int $adminId = null): bool
    {
        $exists = Database::fetchOne("SELECT id FROM pool_bonus_rules LIMIT 1");
        if ($exists) {
            Database::execute(
                "UPDATE pool_bonus_rules SET 
                    min_direct_advisors = ?, min_personal_customers = ?, max_children_per_node = ?, 
                    max_pool_levels = ?, level_1_amount = ?, subsequent_level_amount = ?, is_active = ?, updated_at = NOW() 
                 WHERE id = ?",
                [
                    (int)($data['min_direct_advisors'] ?? 3),
                    (int)($data['min_personal_customers'] ?? 3),
                    (int)($data['max_children_per_node'] ?? 3),
                    (int)($data['max_pool_levels'] ?? 11),
                    (float)($data['level_1_amount'] ?? 1000.00),
                    (float)($data['subsequent_level_amount'] ?? 500.00),
                    (int)($data['is_active'] ?? 1),
                    $exists['id']
                ]
            );
        } else {
            Database::execute(
                "INSERT INTO pool_bonus_rules (min_direct_advisors, min_personal_customers, max_children_per_node, max_pool_levels, level_1_amount, subsequent_level_amount, is_active) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)",
                [
                    (int)($data['min_direct_advisors'] ?? 3),
                    (int)($data['min_personal_customers'] ?? 3),
                    (int)($data['max_children_per_node'] ?? 3),
                    (int)($data['max_pool_levels'] ?? 11),
                    (float)($data['level_1_amount'] ?? 1000.00),
                    (float)($data['subsequent_level_amount'] ?? 500.00),
                    (int)($data['is_active'] ?? 1)
                ]
            );
        }
        self::logAudit($adminId, 'UPDATE_POOL_RULE', 'POOL_RULE', null, null, json_encode($data), 'Updated pool bonus rule');
        return true;
    }

    /**
     * Get Advisor Lifetime Rewards Slabs
     */
    public static function getRewardSlabs(): array
    {
        return Database::fetchAll("SELECT * FROM advisor_rewards WHERE is_active = 1 ORDER BY customer_target ASC");
    }

    /**
     * Save Reward Slabs
     */
    public static function saveRewardSlabs(array $slabs, ?int $adminId = null): bool
    {
        Database::execute("DELETE FROM advisor_rewards");
        foreach ($slabs as $s) {
            Database::execute(
                "INSERT INTO advisor_rewards (reward_name, customer_target, per_customer_amount, total_reward_value, reward_type, eligibility_type, is_repeatable, is_active) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, 1)",
                [
                    $s['reward_name'],
                    (int)$s['customer_target'],
                    (float)$s['per_customer_amount'],
                    (float)$s['total_reward_value'],
                    $s['reward_type'] ?? 'CASH_OR_PRODUCT',
                    $s['eligibility_type'] ?? 'LIFETIME',
                    (int)($s['is_repeatable'] ?? 0)
                ]
            );
        }
        self::logAudit($adminId, 'UPDATE_REWARD_SLABS', 'REWARDS', null, null, json_encode($slabs), 'Updated advisor reward slabs');
        return true;
    }

    /**
     * Get Customer Special Monthly Bonus for a specific month/year
     */
    public static function getCustomerSpecialBonus(int $month, int $year): ?array
    {
        return Database::fetchOne("SELECT * FROM customer_special_bonus_rules WHERE bonus_month = ? AND bonus_year = ?", [$month, $year]);
    }

    /**
     * Declare / Save Customer Special Bonus for month/year
     */
    public static function saveCustomerSpecialBonus(int $month, int $year, float $amount, float $maxAmount = 85000.00, ?string $conditions = null, ?int $adminId = null): bool
    {
        $existing = self::getCustomerSpecialBonus($month, $year);
        if ($existing) {
            Database::execute(
                "UPDATE customer_special_bonus_rules SET bonus_amount = ?, max_allowed_amount = ?, eligibility_conditions = ?, declared_by = ?, updated_at = NOW() 
                 WHERE id = ?",
                [$amount, $maxAmount, $conditions, $adminId, $existing['id']]
            );
        } else {
            Database::execute(
                "INSERT INTO customer_special_bonus_rules (bonus_month, bonus_year, bonus_amount, max_allowed_amount, eligibility_conditions, declared_by) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$month, $year, $amount, $maxAmount, $conditions, $adminId]
            );
        }
        self::logAudit($adminId, 'SET_CUSTOMER_SPECIAL_BONUS', 'CUSTOMER_BONUS', null, null, json_encode(['month' => $month, 'year' => $year, 'amount' => $amount]), "Declared Customer Special Bonus for {$month}/{$year}");
        return true;
    }

    /**
     * Internal Immutable Audit Logger
     */
    public static function logAudit(?int $userId, string $action, string $entityType, ?int $entityId, ?string $oldVal, ?string $newVal, ?string $reason = null): void
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $role = $_SESSION['user_role'] ?? 'ADMIN';
        try {
            Database::execute(
                "INSERT INTO commission_audit_logs (user_id, user_role, action, entity_type, entity_id, old_value, new_value, reason, ip_address, created_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())",
                [$userId, $role, $action, $entityType, $entityId, $oldVal, $newVal, $reason, $ip]
            );
        } catch (\Throwable $t) {
            // Ignore audit log error if any
        }
    }
}
