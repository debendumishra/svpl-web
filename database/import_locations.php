<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Locations Importer & SQL Generator from CSV
 * Reads village_list_with_pincodes.csv, populates locations table, and generates locations_seed.sql
 */

ini_set('auto_detect_line_endings', true);
ini_set('memory_limit', '512M');
set_time_limit(300);

require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/app/Helpers/Database.php';

use App\Helpers\Database;

$csvFile = __DIR__ . '/village_list_with_pincodes.csv';
if (!file_exists($csvFile)) {
    echo "[ERROR] CSV file not found: {$csvFile}\n";
    exit(1);
}

$sqlOutputFile = __DIR__ . '/locations_seed.sql';

echo "Reading CSV and generating locations_seed.sql...\n";

$handle = fopen($csvFile, 'r');
if (!$handle) {
    echo "[ERROR] Could not open CSV file.\n";
    exit(1);
}

$header = fgetcsv($handle);
// Expected: State,District,Subdivision,Block,Panchayat,Village,Pincode

$sqlHandle = fopen($sqlOutputFile, 'w');
fwrite($sqlHandle, "-- ==========================================================\n");
fwrite($sqlHandle, "-- Surya Vistaara Pvt. Ltd. (SVPL)\n");
fwrite($sqlHandle, "-- Master Odisha Locations, Panchayats, Villages & Pincodes\n");
fwrite($sqlHandle, "-- Auto-generated from village_list_with_pincodes.csv\n");
fwrite($sqlHandle, "-- Total Records: 51,804\n");
fwrite($sqlHandle, "-- ==========================================================\n\n");
fwrite($sqlHandle, "TRUNCATE TABLE `locations`;\n\n");

$pdo = Database::getInstance();
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
$pdo->exec("TRUNCATE TABLE `locations`;");

$batchSize = 500;
$batch = [];
$totalRows = 0;

$insertSqlPrefix = "INSERT INTO `locations` (`state`, `district`, `subdivision`, `block`, `gram_panchayat`, `village`, `pincode`) VALUES \n";

$pdo->beginTransaction();

while (($row = fgetcsv($handle, 2000, ',')) !== false) {
    if (empty($row) || (count($row) === 1 && empty($row[0]))) {
        continue;
    }

    $state = !empty($row[0]) ? trim($row[0]) : 'Odisha';
    $district = !empty($row[1]) ? trim($row[1]) : 'Unknown';
    $subdivision = !empty($row[2]) ? trim($row[2]) : null;
    $block = !empty($row[3]) ? trim($row[3]) : ($subdivision ?: $district);
    $panchayat = !empty($row[4]) ? trim($row[4]) : ($block ?: 'N/A');
    $village = !empty($row[5]) ? trim($row[5]) : ($panchayat ?: null);
    $pincode = !empty($row[6]) ? trim($row[6]) : null;

    $totalRows++;

    // Escape for SQL
    $escState = addslashes($state);
    $escDistrict = addslashes($district);
    $escSubdiv = $subdivision !== null ? "'" . addslashes($subdivision) . "'" : "NULL";
    $escBlock = addslashes($block);
    $escPanchayat = addslashes($panchayat);
    $escVillage = $village !== null ? "'" . addslashes($village) . "'" : "NULL";
    $escPin = $pincode !== null ? "'" . addslashes($pincode) . "'" : "NULL";

    $batch[] = "('{$escState}', '{$escDistrict}', {$escSubdiv}, '{$escBlock}', '{$escPanchayat}', {$escVillage}, {$escPin})";

    if (count($batch) >= $batchSize) {
        $chunkSql = $insertSqlPrefix . implode(",\n", $batch) . ";\n\n";
        fwrite($sqlHandle, $chunkSql);
        $pdo->exec($chunkSql);
        $batch = [];
        if ($totalRows % 5000 === 0) {
            echo "Imported {$totalRows} records...\n";
        }
    }
}

if (!empty($batch)) {
    $chunkSql = $insertSqlPrefix . implode(",\n", $batch) . ";\n\n";
    fwrite($sqlHandle, $chunkSql);
    $pdo->exec($chunkSql);
}

$pdo->commit();
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

fclose($handle);
fclose($sqlHandle);

echo "\n[SUCCESS] Successfully imported {$totalRows} location records into database table `locations`!\n";
echo "[SUCCESS] Generated SQL master seed file: {$sqlOutputFile} (" . round(filesize($sqlOutputFile) / (1024 * 1024), 2) . " MB)\n";
