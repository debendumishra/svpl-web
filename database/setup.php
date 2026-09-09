<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Setup & Migration Runner for XAMPP / MySQL / SQLite
 */

class DatabaseSetup
{
    public static function run(): array
    {
        $results = [
            'status' => false,
            'messages' => [],
        ];

        try {
            $db = \App\Helpers\Database::getInstance();
            $driver = \App\Helpers\Database::getDriver();
            $schemaFile = __DIR__ . '/schema.sql';
            $seedersFile = __DIR__ . '/seeders.sql';

            if ($driver === 'mysql') {
                $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
            }

            // 1. Execute Master Schema
            if (file_exists($schemaFile)) {
                $schemaSql = file_get_contents($schemaFile);
                if (!empty($schemaSql)) {
                    if ($driver === 'mysql') {
                        // Split into individual CREATE / DROP statements
                        $queries = array_filter(array_map('trim', preg_split('/;\s*[\r\n]+/', $schemaSql)));
                        foreach ($queries as $q) {
                            if (!empty($q)) {
                                try {
                                    $db->exec($q);
                                } catch (\Throwable $t) {
                                    $results['messages'][] = "Schema Warning: " . $t->getMessage() . " on query: " . substr($q, 0, 80);
                                }
                            }
                        }
                    } else {
                        // Adapt SQL for SQLite
                        $cleanSql = preg_replace('/ENGINE=[A-Za-z0-9]+/i', '', $schemaSql);
                        $cleanSql = preg_replace('/DEFAULT CHARSET=[A-Za-z0-9]+/i', '', $cleanSql);
                        $cleanSql = preg_replace('/COLLATE=[A-Za-z0-9_]+/i', '', $cleanSql);
                        $cleanSql = preg_replace('/AUTO_INCREMENT/i', '', $cleanSql);
                        $cleanSql = preg_replace('/INT UNSIGNED AUTO_INCREMENT PRIMARY KEY/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $cleanSql);
                        $cleanSql = preg_replace('/INT UNSIGNED/i', 'INTEGER', $cleanSql);
                        $cleanSql = preg_replace('/TINYINT\([0-9]+\)/i', 'INTEGER', $cleanSql);
                        $cleanSql = preg_replace('/ENUM\([^)]+\)/i', 'VARCHAR(50)', $cleanSql);
                        $cleanSql = preg_replace('/ON UPDATE CURRENT_TIMESTAMP/i', '', $cleanSql);
                        $cleanSql = preg_replace('/,\s*INDEX\s+[a-zA-Z0-9_]+\s*\([^)]+\)/i', '', $cleanSql);
                        $cleanSql = preg_replace('/,\s*KEY\s+[a-zA-Z0-9_]+\s*\([^)]+\)/i', '', $cleanSql);
                        $cleanSql = preg_replace('/,\s*UNIQUE KEY\s+[a-zA-Z0-9_]+\s*\([^)]+\)/i', '', $cleanSql);
                        $cleanSql = preg_replace('/SET FOREIGN_KEY_CHECKS = [01];/i', '', $cleanSql);

                        $queries = array_filter(array_map('trim', explode(';', $cleanSql)));
                        foreach ($queries as $q) {
                            if (!empty($q)) {
                                try {
                                    $db->exec($q);
                                } catch (\Throwable $t) {
                                    $results['messages'][] = "Schema Warning: " . $t->getMessage();
                                }
                            }
                        }
                    }
                    $results['messages'][] = "Database schema executed successfully.";
                }
            }

            // 2. Execute Master Seeders
            if (file_exists($seedersFile)) {
                $seedSql = file_get_contents($seedersFile);
                if (!empty($seedSql)) {
                    if ($driver === 'mysql') {
                        $queries = array_filter(array_map('trim', preg_split('/;\s*[\r\n]+/', $seedSql)));
                        foreach ($queries as $q) {
                            if (!empty($q)) {
                                try {
                                    $db->exec($q);
                                } catch (\Throwable $t) {
                                    $results['messages'][] = "Seeder Warning: " . $t->getMessage() . " on query: " . substr($q, 0, 80);
                                }
                            }
                        }
                    } else {
                        $cleanSeedSql = preg_replace('/ON DUPLICATE KEY UPDATE.*$/mi', '', $seedSql);
                        $queries = array_filter(array_map('trim', explode(';', $cleanSeedSql)));
                        foreach ($queries as $q) {
                            if (!empty($q)) {
                                try {
                                    $db->exec($q);
                                } catch (\Throwable $t) {
                                    // Ignore duplicate inserts
                                }
                            }
                        }
                    }
                    $results['messages'][] = "Initial system seeders populated successfully.";
                }
            }

            if ($driver === 'mysql') {
                $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
            }

            $results['status'] = true;
            $results['messages'][] = "SVPL Database setup completed successfully!";
        } catch (\Throwable $e) {
            $results['status'] = false;
            $results['error'] = $e->getMessage();
            $results['messages'][] = "Error during database setup: " . $e->getMessage();
        }

        return $results;
    }
}
