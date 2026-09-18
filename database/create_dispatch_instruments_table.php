<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Migration: Create `dispatch_instruments` Table & Seed 20 Standard Items
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

try {
    $pdo = Database::getInstance();
    echo "Connected to DB successfully.\n";

    // 1. Create table
    $sql = "CREATE TABLE IF NOT EXISTS `dispatch_instruments` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `item_code` VARCHAR(50) NULL UNIQUE,
        `item_name` VARCHAR(200) NOT NULL,
        `specifications` TEXT NOT NULL,
        `default_qty` DECIMAL(10,2) NOT NULL DEFAULT 1.00,
        `unit` VARCHAR(50) NOT NULL DEFAULT 'Nos.',
        `category` VARCHAR(100) NULL DEFAULT 'BOS & Hardware',
        `sort_order` INT NOT NULL DEFAULT 0,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX `idx_inst_active` (`is_active`),
        INDEX `idx_inst_sort` (`sort_order`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    
    $pdo->exec($sql);
    echo "Table `dispatch_instruments` verified/created successfully.\n";

    // 2. Check if items already exist
    $count = (int)$pdo->query("SELECT COUNT(*) FROM `dispatch_instruments`")->fetchColumn();
    if ($count === 0) {
        $standardItems = [
            1  => ['code' => 'INST-01', 'item' => 'Solar PV Modules', 'spec' => '540Wp–550Wp Mono PERC / TOPCon Half-Cut Panels', 'qty' => 6, 'unit' => 'Nos.', 'cat' => 'Generation & Panels'],
            2  => ['code' => 'INST-02', 'item' => 'Solar Inverter', 'spec' => '3 kW On-Grid Tie String Inverter', 'qty' => 1, 'unit' => 'No.', 'cat' => 'Power Conditioning'],
            3  => ['code' => 'INST-03', 'item' => 'Module Mounting Structure (MMS)', 'spec' => 'Galvanized Iron (GI) 3-Panel Landscape Frame Kit (Legs, Rails, Braces)', 'qty' => 2, 'unit' => 'Sets', 'cat' => 'Structural & Mounting'],
            4  => ['code' => 'INST-04', 'item' => 'Mid Clamps', 'spec' => 'Aluminum with SS Bolt & Nut', 'qty' => 8, 'unit' => 'Nos.', 'cat' => 'Structural Hardware'],
            5  => ['code' => 'INST-05', 'item' => 'End Clamps', 'spec' => 'Aluminum with SS Bolt & Nut', 'qty' => 8, 'unit' => 'Nos.', 'cat' => 'Structural Hardware'],
            6  => ['code' => 'INST-06', 'item' => 'Anchor Fasteners', 'spec' => 'M10 / M12 Mechanical Concrete Expansion Bolts', 'qty' => 16, 'unit' => 'Nos.', 'cat' => 'Fasteners'],
            7  => ['code' => 'INST-07', 'item' => 'Hardware Fasteners Set', 'spec' => 'SS 304 M8/M10 Bolts, Nuts, Flat & Spring Washers', 'qty' => 1, 'unit' => 'Lot', 'cat' => 'Fasteners'],
            8  => ['code' => 'INST-08', 'item' => 'DC Distribution Box (DCDB)', 'spec' => '1-In 1-Out IP65 Enclosure (with DC Fuse & SPD)', 'qty' => 1, 'unit' => 'No.', 'cat' => 'Electrical Protection'],
            9  => ['code' => 'INST-09', 'item' => 'AC Distribution Box (ACDB)', 'spec' => 'IP65 Enclosure (with 2-Pole 16A MCB & AC SPD)', 'qty' => 1, 'unit' => 'No.', 'cat' => 'Electrical Protection'],
            10 => ['code' => 'INST-10', 'item' => 'DC Solar Cable (Red)', 'spec' => '4 mm² UV-Resistant Copper Cable', 'qty' => 30, 'unit' => 'Meters', 'cat' => 'Cabling & Wiring'],
            11 => ['code' => 'INST-11', 'item' => 'DC Solar Cable (Black)', 'spec' => '4 mm² UV-Resistant Copper Cable', 'qty' => 30, 'unit' => 'Meters', 'cat' => 'Cabling & Wiring'],
            12 => ['code' => 'INST-12', 'item' => 'AC Output Cable', 'spec' => '3-Core 4 mm² Flexible Armored Cable', 'qty' => 20, 'unit' => 'Meters', 'cat' => 'Cabling & Wiring'],
            13 => ['code' => 'INST-13', 'item' => 'MC4 Connectors', 'spec' => 'IP68 Waterproof Pair (Male + Female)', 'qty' => 4, 'unit' => 'Pairs', 'cat' => 'Connectors'],
            14 => ['code' => 'INST-14', 'item' => 'PVC Conduit Pipes & Fittings', 'spec' => '25mm Heavy Duty Rigid PVC Pipes with Bends & Saddles', 'qty' => 10, 'unit' => 'Lengths', 'cat' => 'Conduits & Enclosures'],
            15 => ['code' => 'INST-15', 'item' => 'Earthing Chemical Electrodes', 'spec' => '2-Meter Chemical Copper Bonded Earthing Rods', 'qty' => 3, 'unit' => 'Nos.', 'cat' => 'Earthing & Safety'],
            16 => ['code' => 'INST-16', 'item' => 'Earthing Compound', 'spec' => 'Soil Enhancement Backfill Compound (25 kg Bag)', 'qty' => 3, 'unit' => 'Bags', 'cat' => 'Earthing & Safety'],
            17 => ['code' => 'INST-17', 'item' => 'Earthing Strip / Wire', 'spec' => '25x3 mm GI Strip or 8 SWG Copper Wire', 'qty' => 25, 'unit' => 'Meters', 'cat' => 'Earthing & Safety'],
            18 => ['code' => 'INST-18', 'item' => 'Lightning Arrester (LA)', 'spec' => 'Early Streamer / Conventional Spike Arrester', 'qty' => 1, 'unit' => 'Set', 'cat' => 'Earthing & Safety'],
            19 => ['code' => 'INST-19', 'item' => 'Cable Ties & Accessories', 'spec' => 'UV-Resistant Nylon Cable Ties & SS Clamps', 'qty' => 1, 'unit' => 'Packet', 'cat' => 'Accessories'],
            20 => ['code' => 'INST-20', 'item' => 'Warning Labels & Tags', 'spec' => 'Solar Hazard & Net-Meter Identification Tags', 'qty' => 1, 'unit' => 'Set', 'cat' => 'Signage & Safety'],
        ];

        $stmt = $pdo->prepare("INSERT INTO `dispatch_instruments` (`item_code`, `item_name`, `specifications`, `default_qty`, `unit`, `category`, `sort_order`, `is_active`) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");

        foreach ($standardItems as $sort => $it) {
            $stmt->execute([
                $it['code'],
                $it['item'],
                $it['spec'],
                $it['qty'],
                $it['unit'],
                $it['cat'],
                $sort
            ]);
        }
        echo "Seeded " . count($standardItems) . " standard instrument records successfully.\n";
    } else {
        echo "Table `dispatch_instruments` already contains {$count} records.\n";
    }

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
