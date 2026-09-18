<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Dispatch Instruments & BOS Inventory Items Management Model
 */

namespace App\Models;

use App\Helpers\Database;
use PDO;

class DispatchInstrument
{
    /**
     * Get all instruments ordered by sort order
     */
    public static function getAll(): array
    {
        $sql = "SELECT * FROM `dispatch_instruments` ORDER BY `sort_order` ASC, `id` ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Get only active instruments for dispatch generation
     */
    public static function getActive(): array
    {
        $sql = "SELECT * FROM `dispatch_instruments` WHERE `is_active` = 1 ORDER BY `sort_order` ASC, `id` ASC";
        $rows = Database::fetchAll($sql);
        
        if (empty($rows)) {
            // Fallback to static standard instruments if DB is empty
            $standard = PackageDispatch::STANDARD_INSTRUMENTS;
            $res = [];
            foreach ($standard as $idx => $it) {
                $res[] = [
                    'id' => $idx,
                    'item_code' => 'INST-' . str_pad($idx, 2, '0', STR_PAD_LEFT),
                    'item_name' => $it['item'],
                    'specifications' => $it['spec'],
                    'default_qty' => $it['qty'],
                    'unit' => $it['unit'],
                    'category' => 'BOS & Hardware',
                    'sort_order' => $idx,
                    'is_active' => 1
                ];
            }
            return $res;
        }
        
        return $rows;
    }

    /**
     * Find single instrument by ID
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT * FROM `dispatch_instruments` WHERE `id` = ? LIMIT 1";
        return Database::fetchOne($sql, [$id]);
    }

    /**
     * Create a new instrument / BOS kit item
     */
    public static function create(array $data): int
    {
        $itemName = trim($data['item_name'] ?? '');
        $specifications = trim($data['specifications'] ?? '');
        $defaultQty = (float)($data['default_qty'] ?? 1.0);
        $unit = trim($data['unit'] ?? 'Nos.');
        $category = trim($data['category'] ?? 'BOS & Hardware');
        $sortOrder = (int)($data['sort_order'] ?? 0);
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;
        $itemCode = !empty($data['item_code']) ? trim($data['item_code']) : null;

        if (empty($itemCode)) {
            $nextId = (int)Database::fetchValue("SELECT MAX(id) FROM `dispatch_instruments`") + 1;
            $itemCode = 'INST-' . str_pad($nextId, 2, '0', STR_PAD_LEFT);
        }

        if ($sortOrder === 0) {
            $sortOrder = (int)Database::fetchValue("SELECT MAX(sort_order) FROM `dispatch_instruments`") + 1;
        }

        $sql = "INSERT INTO `dispatch_instruments` 
                (`item_code`, `item_name`, `specifications`, `default_qty`, `unit`, `category`, `sort_order`, `is_active`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $itemCode,
            $itemName,
            $specifications,
            $defaultQty,
            $unit,
            $category,
            $sortOrder,
            $isActive
        ]);

        return (int)$pdo->lastInsertId();
    }

    /**
     * Update an existing instrument / BOS kit item
     */
    public static function update(int $id, array $data): bool
    {
        $itemName = trim($data['item_name'] ?? '');
        $specifications = trim($data['specifications'] ?? '');
        $defaultQty = (float)($data['default_qty'] ?? 1.0);
        $unit = trim($data['unit'] ?? 'Nos.');
        $category = trim($data['category'] ?? 'BOS & Hardware');
        $sortOrder = (int)($data['sort_order'] ?? 0);
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;
        $itemCode = !empty($data['item_code']) ? trim($data['item_code']) : null;

        $sql = "UPDATE `dispatch_instruments` SET 
                `item_code` = ?,
                `item_name` = ?,
                `specifications` = ?,
                `default_qty` = ?,
                `unit` = ?,
                `category` = ?,
                `sort_order` = ?,
                `is_active` = ?
                WHERE `id` = ?";

        $pdo = Database::getInstance();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            $itemCode,
            $itemName,
            $specifications,
            $defaultQty,
            $unit,
            $category,
            $sortOrder,
            $isActive,
            $id
        ]);
    }

    /**
     * Delete an instrument by ID
     */
    public static function delete(int $id): bool
    {
        $sql = "DELETE FROM `dispatch_instruments` WHERE `id` = ?";
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    /**
     * Toggle active / inactive status
     */
    public static function toggleStatus(int $id): bool
    {
        $sql = "UPDATE `dispatch_instruments` SET `is_active` = (1 - `is_active`) WHERE `id` = ?";
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
}
