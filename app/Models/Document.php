<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Document Model
 */

namespace App\Models;

use App\Helpers\Database;

class Document
{
    public static function create(array $data): int
    {
        $sql = "INSERT INTO documents (
                    entity_type, entity_id, lead_id, document_type,
                    document_title, file_path, file_size, mime_type,
                    status, remarks, created_at
                ) VALUES (
                    ?, ?, ?, ?,
                    ?, ?, ?, ?,
                    ?, ?, NOW()
                )";

        Database::query($sql, [
            $data['entity_type'] ?? 'CUSTOMER',
            $data['entity_id'] ?? null,
            $data['lead_id'] ?? null,
            $data['document_type'],
            $data['document_title'] ?? $data['document_type'],
            $data['file_path'],
            $data['file_size'] ?? 0,
            $data['mime_type'] ?? 'application/pdf',
            $data['status'] ?? 'Uploaded',
            $data['remarks'] ?? null,
        ]);

        return (int) Database::lastInsertId();
    }

    public static function getByLeadId(int $leadId): array
    {
        return Database::fetchAll("SELECT * FROM documents WHERE lead_id = ? ORDER BY id DESC", [$leadId]);
    }

    public static function getByCustomerId(int $customerId): array
    {
        return Database::fetchAll("SELECT * FROM documents WHERE entity_type = 'CUSTOMER' AND entity_id = ? ORDER BY id DESC", [$customerId]);
    }

    public static function findById(int $docId): ?array
    {
        return Database::fetchOne("SELECT * FROM documents WHERE id = ?", [$docId]);
    }

    public static function updateStatus(int $docId, string $status, ?string $remarks = null): bool
    {
        return Database::execute(
            "UPDATE documents SET status = ?, remarks = ?, updated_at = NOW() WHERE id = ?",
            [$status, $remarks, $docId]
        );
    }

    public static function replaceDocument(int $docId, array $newData): bool
    {
        return Database::execute(
            "UPDATE documents SET file_path = ?, file_size = ?, mime_type = ?, status = 'Uploaded', remarks = ?, updated_at = NOW() WHERE id = ?",
            [
                $newData['file_path'],
                $newData['file_size'] ?? 0,
                $newData['mime_type'] ?? 'application/pdf',
                $newData['remarks'] ?? 'Document replaced',
                $docId
            ]
        );
    }
}
