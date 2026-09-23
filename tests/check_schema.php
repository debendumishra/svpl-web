<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/Database.php';

use App\Helpers\Database;

echo "=== DOCUMENTS COLUMNS ===\n";
$cols = Database::fetchAll("SHOW COLUMNS FROM documents");
foreach ($cols as $c) {
    echo $c['Field'] . ' (' . $c['Type'] . ")\n";
}
