<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Package Model — Tier-1 Solar Rooftop & Brand Packages
 */

namespace App\Models;

use App\Helpers\Database;

class Package
{
    /**
     * Get all packages with optional filtering
     */
    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT p.*, 
                       (SELECT COUNT(*) FROM leads l WHERE l.package_id = p.id) as leads_count 
                FROM packages p WHERE 1=1";
        $params = [];

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'ACTIVE') {
                $sql .= " AND p.is_active = 1";
            } elseif ($filters['status'] === 'INACTIVE') {
                $sql .= " AND p.is_active = 0";
            }
        }

        if (!empty($filters['brand']) && $filters['brand'] !== 'ALL') {
            $sql .= " AND p.brand = ?";
            $params[] = $filters['brand'];
        }

        if (!empty($filters['system_type']) && $filters['system_type'] !== 'ALL') {
            $sql .= " AND (p.system_type = ? OR p.system_type LIKE ?)";
            $params[] = $filters['system_type'];
            $params[] = "%" . $filters['system_type'] . "%";
        }

        if (!empty($filters['search'])) {
            $term = "%" . trim($filters['search']) . "%";
            $sql .= " AND (p.title LIKE ? OR p.package_code LIKE ? OR p.brand LIKE ? OR p.panel_type LIKE ? OR p.inverter_type LIKE ? OR p.key_features LIKE ?)";
            $params = array_merge($params, [$term, $term, $term, $term, $term, $term]);
        }

        $sql .= " ORDER BY p.is_active DESC, p.brand ASC, p.capacity_kw ASC, p.total_price ASC";

        return Database::fetchAll($sql, $params);
    }

    /**
     * Get all active packages ordered by brand and capacity
     */
    public static function getAllActive(): array
    {
        return Database::fetchAll(
            "SELECT * FROM packages WHERE is_active = 1 ORDER BY brand ASC, capacity_kw ASC, total_price ASC"
        );
    }

    /**
     * Find package by ID
     */
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM packages WHERE id = ?", [$id]);
    }

    /**
     * Find package by package code
     */
    public static function findByCode(string $code): ?array
    {
        return Database::fetchOne("SELECT * FROM packages WHERE package_code = ?", [$code]);
    }

    /**
     * Get packages by Brand / Vendor
     */
    public static function getByBrand(string $brand): array
    {
        return Database::fetchAll(
            "SELECT * FROM packages WHERE brand = ? AND is_active = 1 ORDER BY capacity_kw ASC, total_price ASC",
            [$brand]
        );
    }

    /**
     * Get packages by System Type (On-Grid, Hybrid, Off-Grid)
     */
    public static function getBySystemType(string $systemType): array
    {
        return Database::fetchAll(
            "SELECT * FROM packages WHERE (system_type = ? OR system_type LIKE ?) AND is_active = 1 ORDER BY capacity_kw ASC, total_price ASC",
            [$systemType, "%{$systemType}%"]
        );
    }

    /**
     * Get distinct list of available Brands
     */
    public static function getBrands(): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT brand FROM packages WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC");
        return array_column($rows, 'brand');
    }

    /**
     * Get distinct list of System Types
     */
    public static function getSystemTypes(): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT system_type FROM packages WHERE system_type IS NOT NULL AND system_type != '' ORDER BY system_type ASC");
        return array_column($rows, 'system_type');
    }

    /**
     * Create a new package
     */
    public static function create(array $data): int
    {
        $sql = "INSERT INTO packages (
                    package_code, brand, title, capacity_kw, system_type,
                    panel_type, inverter_type, key_features, battery_included,
                    total_price, estimated_subsidy, net_customer_cost, is_active, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, ?, NOW()
                )";

        $packageCode = trim($data['package_code'] ?? '');
        if (empty($packageCode)) {
            $brandSlug = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $data['brand'] ?? 'SOLAR'));
            $capSlug = str_replace('.', '', (string)($data['capacity_kw'] ?? '3')) . 'KW';
            $typeSlug = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $data['system_type'] ?? 'ONGRID'));
            $packageCode = 'PKG-' . $brandSlug . '-' . $capSlug . '-' . $typeSlug . '-' . rand(100, 999);
        }

        $totalPrice = (float)($data['total_price'] ?? 0);
        $subsidy = (float)($data['estimated_subsidy'] ?? 0);
        $netCost = isset($data['net_customer_cost']) ? (float)$data['net_customer_cost'] : max(0, $totalPrice - $subsidy);

        Database::query($sql, [
            $packageCode,
            trim($data['brand'] ?? 'Dhwajja Solar'),
            trim($data['title'] ?? 'Custom Solar Plant'),
            (float)($data['capacity_kw'] ?? 3.0),
            trim($data['system_type'] ?? 'On-Grid'),
            trim($data['panel_type'] ?? 'Mono PERC / DCR Tier-1'),
            trim($data['inverter_type'] ?? 'Smart Grid Inverter'),
            trim($data['key_features'] ?? ''),
            !empty($data['battery_included']) ? 1 : 0,
            $totalPrice,
            $subsidy,
            $netCost,
            isset($data['is_active']) ? (int)$data['is_active'] : 1
        ]);

        return (int) Database::lastInsertId();
    }

    /**
     * Update an existing package
     */
    public static function update(int $id, array $data): bool
    {
        $totalPrice = (float)($data['total_price'] ?? 0);
        $subsidy = (float)($data['estimated_subsidy'] ?? 0);
        $netCost = isset($data['net_customer_cost']) ? (float)$data['net_customer_cost'] : max(0, $totalPrice - $subsidy);

        $sql = "UPDATE packages SET
                    package_code = ?,
                    brand = ?,
                    title = ?,
                    capacity_kw = ?,
                    system_type = ?,
                    panel_type = ?,
                    inverter_type = ?,
                    key_features = ?,
                    battery_included = ?,
                    total_price = ?,
                    estimated_subsidy = ?,
                    net_customer_cost = ?,
                    is_active = ?
                WHERE id = ?";

        Database::query($sql, [
            trim($data['package_code'] ?? ''),
            trim($data['brand'] ?? 'Dhwajja Solar'),
            trim($data['title'] ?? 'Custom Solar Plant'),
            (float)($data['capacity_kw'] ?? 3.0),
            trim($data['system_type'] ?? 'On-Grid'),
            trim($data['panel_type'] ?? 'Mono PERC / DCR Tier-1'),
            trim($data['inverter_type'] ?? 'Smart Grid Inverter'),
            trim($data['key_features'] ?? ''),
            !empty($data['battery_included']) ? 1 : 0,
            $totalPrice,
            $subsidy,
            $netCost,
            isset($data['is_active']) ? (int)$data['is_active'] : 1,
            $id
        ]);

        return true;
    }

    /**
     * Toggle active/inactive status
     */
    public static function toggleStatus(int $id): bool
    {
        $pkg = self::findById($id);
        if (!$pkg) {
            return false;
        }

        $newStatus = $pkg['is_active'] == 1 ? 0 : 1;
        Database::query("UPDATE packages SET is_active = ? WHERE id = ?", [$newStatus, $id]);
        return true;
    }

    /**
     * Delete package
     */
    public static function delete(int $id): bool
    {
        // Check if package is referenced in any leads
        if (self::isReferencedInLeads($id)) {
            // Soft-deactivate if referenced
            Database::query("UPDATE packages SET is_active = 0 WHERE id = ?", [$id]);
            return false;
        }

        Database::query("DELETE FROM packages WHERE id = ?", [$id]);
        return true;
    }

    /**
     * Check if package is used by any leads
     */
    public static function isReferencedInLeads(int $id): bool
    {
        $res = Database::fetchOne("SELECT COUNT(*) as cnt FROM leads WHERE package_id = ?", [$id]);
        return ($res ? (int)$res['cnt'] : 0) > 0;
    }

    /**
     * Get aggregate metrics for Admin Dashboard
     */
    public static function getStats(): array
    {
        $total = Database::fetchOne("SELECT COUNT(*) as cnt FROM packages")['cnt'] ?? 0;
        $active = Database::fetchOne("SELECT COUNT(*) as cnt FROM packages WHERE is_active = 1")['cnt'] ?? 0;
        $inactive = Database::fetchOne("SELECT COUNT(*) as cnt FROM packages WHERE is_active = 0")['cnt'] ?? 0;
        $brands = Database::fetchOne("SELECT COUNT(DISTINCT brand) as cnt FROM packages WHERE brand IS NOT NULL AND brand != ''")['cnt'] ?? 0;
        $avgPrice = Database::fetchOne("SELECT AVG(total_price) as avg_p FROM packages WHERE is_active = 1")['avg_p'] ?? 0;

        return [
            'total' => (int)$total,
            'active' => (int)$active,
            'inactive' => (int)$inactive,
            'brands_count' => (int)$brands,
            'avg_price' => (float)$avgPrice,
        ];
    }
}
