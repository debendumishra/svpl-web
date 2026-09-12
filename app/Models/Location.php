<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Location Model - Odisha Administrative Hierarchy (51,804 Villages & Pincodes)
 */

namespace App\Models;

use App\Helpers\Database;

class Location
{
    public static function getDistricts(string $state = 'Odisha'): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT district FROM locations WHERE state = ? ORDER BY district ASC", [$state]);
        if (empty($rows)) {
            return ['Angul', 'Balangir', 'Balasore', 'Bargarh', 'Bhadrak', 'Boudh', 'Cuttack', 'Deogarh', 'Dhenkanal', 'Gajapati', 'Ganjam', 'Jagatsinghpur', 'Jajpur', 'Jharsuguda', 'Kalahandi', 'Kandhamal', 'Kendrapara', 'Kendujhar', 'Khordha', 'Koraput', 'Malkangiri', 'Mayurbhanj', 'Nabarangpur', 'Nayagarh', 'Nuapada', 'Puri', 'Rayagada', 'Sambalpur', 'Subarnapur', 'Sundargarh'];
        }
        return array_column($rows, 'district');
    }

    public static function getBlocks(string $district): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT block FROM locations WHERE district = ? AND block IS NOT NULL AND block != '' ORDER BY block ASC", [$district]);
        return array_column($rows, 'block');
    }

    public static function getGramPanchayats(string $block): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT gram_panchayat FROM locations WHERE block = ? AND gram_panchayat IS NOT NULL AND gram_panchayat != '' ORDER BY gram_panchayat ASC", [$block]);
        return array_column($rows, 'gram_panchayat');
    }

    public static function getVillages(string $gramPanchayat, ?string $block = null): array
    {
        $sql = "SELECT DISTINCT village, pincode FROM locations WHERE gram_panchayat = ?";
        $params = [$gramPanchayat];
        if ($block !== null) {
            $sql .= " AND block = ?";
            $params[] = $block;
        }
        $sql .= " ORDER BY village ASC";
        return Database::fetchAll($sql, $params);
    }

    public static function getByPincode(string $pincode): array
    {
        return Database::fetchAll("SELECT * FROM locations WHERE pincode = ? ORDER BY village ASC", [$pincode]);
    }

    public static function search(string $query, int $limit = 25): array
    {
        $term = "%{$query}%";
        return Database::fetchAll(
            "SELECT * FROM locations WHERE village LIKE ? OR gram_panchayat LIKE ? OR block LIKE ? OR district LIKE ? OR pincode LIKE ? LIMIT {$limit}",
            [$term, $term, $term, $term, $term]
        );
    }
}
