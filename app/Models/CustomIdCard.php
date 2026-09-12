<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Custom / On-Demand ID Card Model
 */

namespace App\Models;

use App\Helpers\Database;

class CustomIdCard
{
    public static function create(array $data): int
    {
        $sql = "INSERT INTO custom_id_cards (
                    card_type, card_code, full_name, designation, jurisdiction,
                    blood_group, mobile, email, address, photo_url,
                    issue_date, valid_thru, emergency_contact, created_by_user_id,
                    print_count, last_printed_at, status, created_at, updated_at
                ) VALUES (
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, ?, NOW(), NOW()
                )";

        Database::execute($sql, [
            $data['card_type'] ?? 'BOE',
            $data['card_code'],
            $data['full_name'],
            $data['designation'],
            $data['jurisdiction'] ?? 'Headquarters / All Odisha',
            $data['blood_group'] ?? 'O+ve',
            $data['mobile'],
            $data['email'] ?? null,
            $data['address'] ?? null,
            $data['photo_url'] ?? null,
            $data['issue_date'] ?? date('Y-m-d'),
            $data['valid_thru'] ?? '31-12-2027',
            $data['emergency_contact'] ?? '9437000000',
            $data['created_by_user_id'] ?? null,
            (int)($data['print_count'] ?? 0),
            $data['last_printed_at'] ?? null,
            $data['status'] ?? 'ACTIVE'
        ]);

        return (int) Database::lastInsertId();
    }

    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM custom_id_cards WHERE id = ?", [$id]);
    }

    public static function findByCode(string $code): ?array
    {
        return Database::fetchOne("SELECT * FROM custom_id_cards WHERE card_code = ? AND status = 'ACTIVE' LIMIT 1", [$code]);
    }

    public static function getAll(int $limit = 100, int $offset = 0, ?string $search = null, ?string $type = null): array
    {
        $params = [];
        $where = "WHERE status = 'ACTIVE'";

        if (!empty($type) && $type !== 'ALL') {
            $where .= " AND card_type = ?";
            $params[] = $type;
        }

        if (!empty($search)) {
            $where .= " AND (card_code LIKE ? OR full_name LIKE ? OR mobile LIKE ? OR designation LIKE ? OR jurisdiction LIKE ?)";
            $term = "%{$search}%";
            $params = array_merge($params, [$term, $term, $term, $term, $term]);
        }

        $sql = "SELECT * FROM custom_id_cards {$where} ORDER BY id DESC LIMIT {$limit} OFFSET {$offset}";
        return Database::fetchAll($sql, $params);
    }

    public static function update(int $id, array $data): bool
    {
        $fields = [];
        $params = [];

        $allowed = [
            'card_type', 'card_code', 'full_name', 'designation', 'jurisdiction',
            'blood_group', 'mobile', 'email', 'address', 'photo_url',
            'issue_date', 'valid_thru', 'emergency_contact', 'status'
        ];

        foreach ($allowed as $f) {
            if (array_key_exists($f, $data)) {
                $fields[] = "{$f} = ?";
                $params[] = $data[$f];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE custom_id_cards SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
        return Database::execute($sql, $params);
    }

    public static function delete(int $id): bool
    {
        return Database::execute("DELETE FROM custom_id_cards WHERE id = ?", [$id]);
    }

    public static function incrementPrintCount(int $id): bool
    {
        return Database::execute(
            "UPDATE custom_id_cards SET print_count = print_count + 1, last_printed_at = NOW() WHERE id = ?",
            [$id]
        );
    }

    public static function getNextCode(string $cardType = 'BOE'): string
    {
        $prefix = 'SVPL-BOE-';
        if ($cardType === 'ADVISOR') {
            $prefix = 'SVPL-ADV-';
        } elseif ($cardType === 'OFFICER' || $cardType === 'EXECUTIVE') {
            $prefix = 'SVPL-OFF-';
        } elseif ($cardType === 'ENGINEER') {
            $prefix = 'SVPL-ENG-';
        }

        $last = Database::fetchOne(
            "SELECT card_code FROM custom_id_cards WHERE card_code LIKE ? ORDER BY id DESC LIMIT 1",
            ["{$prefix}%"]
        );

        if ($last && preg_match('/-(\d+)$/', $last['card_code'], $m)) {
            $nextNum = (int)$m[1] + 1;
        } else {
            $nextNum = 101;
        }

        return $prefix . $nextNum;
    }
}
