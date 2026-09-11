<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Helper - Robust PDO Singleton with Auto-DB Creation for XAMPP
 */

namespace App\Helpers;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;
    private static string $driver = 'mysql';

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require dirname(__DIR__, 2) . '/config/database.php';
            $defaultConnection = $config['default'] ?? 'mysql';
            $connConfig = $config['connections'][$defaultConnection] ?? [];

            self::$driver = $connConfig['driver'] ?? 'mysql';

            try {
                if (self::$driver === 'sqlite') {
                    $dbFile = $connConfig['database'];
                    $dir = dirname($dbFile);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    self::$instance = new PDO("sqlite:" . $dbFile, null, null, $connConfig['options'] ?? []);
                    self::$instance->exec("PRAGMA foreign_keys = ON;");
                } else {
                    $host = $connConfig['host'] ?? '127.0.0.1';
                    $port = $connConfig['port'] ?? '3306';
                    $dbName = $connConfig['database'] ?? 'svpl_db';
                    $user = $connConfig['username'] ?? 'root';
                    $pass = $connConfig['password'] ?? '';

                    try {
                        // First attempt direct connection to the target database
                        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";
                        self::$instance = new PDO($dsn, $user, $pass, $connConfig['options'] ?? []);
                    } catch (PDOException $pe) {
                        // If database does not exist (SQLSTATE 1049), auto-create it in XAMPP
                        if ($pe->getCode() == 1049 || strpos($pe->getMessage(), 'Unknown database') !== false) {
                            $serverDsn = "mysql:host={$host};port={$port};charset=utf8mb4";
                            $serverPdo = new PDO($serverDsn, $user, $pass);
                            $serverPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                            
                            // Reconnect
                            self::$instance = new PDO($dsn, $user, $pass, $connConfig['options'] ?? []);
                        } else {
                            throw $pe;
                        }
                    }
                }
            } catch (PDOException $e) {
                // If MySQL is completely unavailable, fall back seamlessly to SQLite
                $sqliteFile = dirname(__DIR__, 2) . '/database/svpl_local.sqlite';
                $dir = dirname($sqliteFile);
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                self::$driver = 'sqlite';
                self::$instance = new PDO("sqlite:" . $sqliteFile);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                self::$instance->exec("PRAGMA foreign_keys = ON;");
            }
        }

        return self::$instance;
    }

    public static function getDriver(): string
    {
        self::getInstance();
        return self::$driver;
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $db = self::getInstance();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function execute(string $sql, array $params = []): bool
    {
        $stmt = self::query($sql, $params);
        return $stmt->rowCount() > 0 || $stmt !== false;
    }

    public static function lastInsertId(): string
    {
        return self::getInstance()->lastInsertId();
    }

    public static function beginTransaction(): bool
    {
        $db = self::getInstance();
        if (!$db->inTransaction()) {
            return $db->beginTransaction();
        }
        return true;
    }

    public static function commit(): bool
    {
        $db = self::getInstance();
        if ($db->inTransaction()) {
            return $db->commit();
        }
        return true;
    }

    public static function rollBack(): bool
    {
        if (self::getInstance()->inTransaction()) {
            return self::getInstance()->rollBack();
        }
        return false;
    }

    public static function tableExists(string $tableName): bool
    {
        $db = self::getInstance();
        if (self::$driver === 'sqlite') {
            $stmt = $db->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name = ?");
            $stmt->execute([$tableName]);
            return (bool)$stmt->fetchColumn();
        } else {
            $stmt = $db->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?");
            $stmt->execute([$tableName]);
            return ((int)$stmt->fetchColumn()) > 0;
        }
    }

    public static function columnExists(string $tableName, string $columnName): bool
    {
        $db = self::getInstance();
        try {
            if (self::$driver === 'sqlite') {
                $stmt = $db->prepare("PRAGMA table_info(`{$tableName}`)");
                $stmt->execute();
                $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($cols as $c) {
                    if (strcasecmp($c['name'], $columnName) === 0) return true;
                }
                return false;
            } else {
                $stmt = $db->prepare("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?");
                $stmt->execute([$tableName, $columnName]);
                return ((int)$stmt->fetchColumn()) > 0;
            }
        } catch (\Throwable $t) {
            return false;
        }
    }
}
