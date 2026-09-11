<?php
require_once dirname(__DIR__) . '/config/constants.php';
$dbConfig = require dirname(__DIR__) . '/config/database.php';

$pdo = new PDO(
    "mysql:host={$dbConfig['connections']['mysql']['host']};dbname={$dbConfig['connections']['mysql']['database']};charset={$dbConfig['connections']['mysql']['charset']}",
    $dbConfig['connections']['mysql']['username'],
    $dbConfig['connections']['mysql']['password'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// Add columns to users table if not exist
$columnsToAdd = [
    'jurisdiction' => "VARCHAR(255) NULL DEFAULT 'Odisha Operations' AFTER designation",
    'blood_group'  => "VARCHAR(10) NULL DEFAULT 'O+ve' AFTER jurisdiction",
    'photo_url'    => "VARCHAR(255) NULL AFTER blood_group",
    'address'      => "TEXT NULL AFTER photo_url"
];

$stmt = $pdo->query("SHOW COLUMNS FROM users");
$existingCols = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($columnsToAdd as $col => $definition) {
    if (!in_array($col, $existingCols)) {
        $pdo->exec("ALTER TABLE users ADD COLUMN {$col} {$definition}");
        echo " -> Added {$col} column to users table.\n";
    } else {
        echo " -> Column {$col} already exists in users table.\n";
    }
}

echo "Users table update completed successfully!\n";
