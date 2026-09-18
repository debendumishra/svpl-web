<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * DISCOM Model — Distribution Company Providers & District Mappings
 */

namespace App\Models;

use App\Helpers\Database;

class Discom
{
    /**
     * Get all DISCOM providers with assigned district counts
     */
    public static function getAll(): array
    {
        $sql = "SELECT d.*, 
                       (SELECT COUNT(*) FROM district_discoms dd WHERE dd.discom_id = d.id) as district_count
                FROM discom_providers d
                ORDER BY d.code ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Get all active DISCOM providers
     */
    public static function getAllActive(): array
    {
        return Database::fetchAll(
            "SELECT * FROM discom_providers WHERE is_active = 1 ORDER BY code ASC"
        );
    }

    /**
     * Find DISCOM by ID
     */
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM discom_providers WHERE id = ?", [$id]);
    }

    /**
     * Find DISCOM by Code
     */
    public static function findByCode(string $code): ?array
    {
        return Database::fetchOne("SELECT * FROM discom_providers WHERE code = ?", [strtoupper(trim($code))]);
    }

    /**
     * Get all district to DISCOM mappings
     */
    public static function getDistrictMappings(): array
    {
        $sql = "SELECT dd.*, d.name as discom_full_name, d.code as provider_code, d.short_name, d.is_active as provider_active
                FROM district_discoms dd
                LEFT JOIN discom_providers d ON dd.discom_id = d.id
                ORDER BY dd.district_name ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Get DISCOM mapping dictionary [ 'Angul' => 'TPCODL', ... ] for rapid JS lookup
     */
    public static function getDistrictLookupMap(): array
    {
        $mappings = self::getDistrictMappings();
        $map = [];
        foreach ($mappings as $m) {
            $map[$m['district_name']] = [
                'discom_id' => $m['discom_id'],
                'discom_code' => $m['discom_code'],
                'discom_name' => $m['discom_full_name'] ?? $m['discom_code'],
                'short_name' => $m['short_name'] ?? $m['discom_code']
            ];
        }
        return $map;
    }

    /**
     * Get DISCOM assigned to a specific District
     */
    public static function getDiscomByDistrict(string $district): ?array
    {
        $district = trim($district);
        if (empty($district)) return null;

        $sql = "SELECT dd.*, d.name as discom_full_name, d.code as discom_code_official, d.short_name, d.helpline, d.portal_url, d.is_active
                FROM district_discoms dd
                LEFT JOIN discom_providers d ON dd.discom_id = d.id
                WHERE LOWER(dd.district_name) = LOWER(?) OR LOWER(dd.district_name) LIKE LOWER(?)
                LIMIT 1";
        return Database::fetchOne($sql, [$district, "%{$district}%"]);
    }

    /**
     * Create a new DISCOM Provider
     */
    public static function create(array $data): int
    {
        $code = strtoupper(trim($data['code'] ?? ''));
        $name = trim($data['name'] ?? '');
        $shortName = trim($data['short_name'] ?? $code);
        $hq = trim($data['headquarters'] ?? '');
        $helpline = trim($data['helpline'] ?? '');
        $portalUrl = trim($data['portal_url'] ?? '');
        $coverage = trim($data['coverage_summary'] ?? '');
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        $sql = "INSERT INTO discom_providers (code, name, short_name, headquarters, helpline, portal_url, coverage_summary, is_active, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        Database::query($sql, [$code, $name, $shortName, $hq, $helpline, $portalUrl, $coverage, $isActive]);
        return (int) Database::lastInsertId();
    }

    /**
     * Update an existing DISCOM Provider
     */
    public static function update(int $id, array $data): bool
    {
        $code = strtoupper(trim($data['code'] ?? ''));
        $name = trim($data['name'] ?? '');
        $shortName = trim($data['short_name'] ?? $code);
        $hq = trim($data['headquarters'] ?? '');
        $helpline = trim($data['helpline'] ?? '');
        $portalUrl = trim($data['portal_url'] ?? '');
        $coverage = trim($data['coverage_summary'] ?? '');
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        $sql = "UPDATE discom_providers SET
                    code = ?,
                    name = ?,
                    short_name = ?,
                    headquarters = ?,
                    helpline = ?,
                    portal_url = ?,
                    coverage_summary = ?,
                    is_active = ?
                WHERE id = ?";

        Database::query($sql, [$code, $name, $shortName, $hq, $helpline, $portalUrl, $coverage, $isActive, $id]);

        // Keep discom_code updated in district_discoms
        Database::query("UPDATE district_discoms SET discom_code = ? WHERE discom_id = ?", [$code, $id]);

        return true;
    }

    /**
     * Toggle active/inactive status
     */
    public static function toggleStatus(int $id): bool
    {
        $provider = self::findById($id);
        if (!$provider) return false;

        $newStatus = $provider['is_active'] == 1 ? 0 : 1;
        Database::query("UPDATE discom_providers SET is_active = ? WHERE id = ?", [$newStatus, $id]);
        return true;
    }

    /**
     * Delete DISCOM Provider
     */
    public static function delete(int $id): bool
    {
        // Unlink districts or reassign
        Database::query("DELETE FROM district_discoms WHERE discom_id = ?", [$id]);
        Database::query("DELETE FROM discom_providers WHERE id = ?", [$id]);
        return true;
    }

    /**
     * Assign or reassign a District to a DISCOM
     */
    public static function assignDistrict(string $district, int $discomId): bool
    {
        $district = trim($district);
        $provider = self::findById($discomId);
        if (!$provider || empty($district)) return false;

        $existing = Database::fetchOne("SELECT id FROM district_discoms WHERE LOWER(district_name) = LOWER(?)", [$district]);
        if ($existing) {
            Database::query(
                "UPDATE district_discoms SET discom_id = ?, discom_code = ? WHERE id = ?",
                [$provider['id'], $provider['code'], $existing['id']]
            );
        } else {
            Database::query(
                "INSERT INTO district_discoms (district_name, discom_id, discom_code) VALUES (?, ?, ?)",
                [$district, $provider['id'], $provider['code']]
            );
        }
        return true;
    }

    /**
     * Delete a District mapping
     */
    public static function deleteDistrictMapping(int $mappingId): bool
    {
        Database::query("DELETE FROM district_discoms WHERE id = ?", [$mappingId]);
        return true;
    }

    /**
     * Get DISCOM statistics for Admin / Manager
     */
    public static function getStats(): array
    {
        $totalProviders = Database::fetchOne("SELECT COUNT(*) as cnt FROM discom_providers")['cnt'] ?? 0;
        $activeProviders = Database::fetchOne("SELECT COUNT(*) as cnt FROM discom_providers WHERE is_active = 1")['cnt'] ?? 0;
        $mappedDistricts = Database::fetchOne("SELECT COUNT(*) as cnt FROM district_discoms")['cnt'] ?? 0;

        return [
            'total_providers' => (int)$totalProviders,
            'active_providers' => (int)$activeProviders,
            'mapped_districts' => (int)$mappedDistricts,
        ];
    }
}
