<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Genealogy Service - Hierarchy & Network Visualization Data Builder
 */

namespace App\Services;

use App\Models\Genealogy;
use App\Models\Advisor;
use App\Helpers\Database;

class GenealogyService
{
    /**
     * Build nested JSON tree structure for interactive org-chart visualizer
     */
    public static function getTreeData(int $rootAdvisorId, int $maxDepth = 5): array
    {
        $root = Advisor::findById($rootAdvisorId);
        if (!$root) {
            return [];
        }

        return self::buildSubtree($root, 0, $maxDepth);
    }

    private static function buildSubtree(array $advisor, int $currentDepth, int $maxDepth): array
    {
        $node = [
            'id' => (int) $advisor['id'],
            'code' => $advisor['advisor_code'],
            'name' => $advisor['first_name'] . ' ' . $advisor['last_name'],
            'status' => $advisor['status'],
            'customer_count' => (int) ($advisor['direct_customer_count'] ?? 0),
            'district' => $advisor['district'] ?? '',
            'depth' => $currentDepth,
            'children' => [],
        ];

        if ($currentDepth < $maxDepth) {
            $directs = Genealogy::getDirectDownlines((int) $advisor['id']);
            foreach ($directs as $child) {
                $node['children'][] = self::buildSubtree($child, $currentDepth + 1, $maxDepth);
            }
        }

        return $node;
    }

    /**
     * Get Complete 9-Level Downline Statistics for an Advisor
     */
    public static function getNetworkStats(int $advisorId): array
    {
        $counts = Genealogy::getDownlineCountByLevel($advisorId);
        $totalDownline = 0;
        $levelMap = array_fill(1, 9, 0);

        foreach ($counts as $row) {
            $lvl = (int) $row['depth'];
            if ($lvl <= 9) {
                $levelMap[$lvl] = (int) $row['count'];
                $totalDownline += (int) $row['count'];
            }
        }

        return [
            'total_downline' => $totalDownline,
            'levels' => $levelMap,
        ];
    }
}
