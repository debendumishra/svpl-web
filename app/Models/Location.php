<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Location Model - Odisha Administrative Hierarchy
 */

namespace App\Models;

use App\Helpers\Database;

class Location
{
    public static function getDistricts(string $state = 'Odisha'): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT district FROM locations WHERE state = ? ORDER BY district ASC", [$state]);
        if (empty($rows)) {
            return ['Khordha', 'Cuttack', 'Puri', 'Ganjam', 'Sambalpur', 'Balasore', 'Bhadrak', 'Mayurbhanj', 'Sundargarh', 'Angul', 'Dhenkanal'];
        }
        return array_column($rows, 'district');
    }

    public static function getBlocks(string $district): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT block FROM locations WHERE district = ? ORDER BY block ASC", [$district]);
        if (empty($rows)) {
            return [$district . ' Block 1', $district . ' Block 2', $district . ' Sadar'];
        }
        return array_column($rows, 'block');
    }

    public static function getGramPanchayats(string $block): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT gram_panchayat FROM locations WHERE block = ? ORDER BY gram_panchayat ASC", [$block]);
        if (empty($rows)) {
            return ['GP Central', 'GP North', 'GP South', 'GP East'];
        }
        return array_column($rows, 'gram_panchayat');
    }
}
