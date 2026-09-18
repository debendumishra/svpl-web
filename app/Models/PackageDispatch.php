<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Package Dispatch & Kit Fulfillment Model — 20-Point Solar Instruments Dispatch System
 */

namespace App\Models;

use App\Helpers\Database;
use App\Services\LeadPipelineService;

class PackageDispatch
{
    /**
     * Standard 20-Item Solar Rooftop Installation Kit & Instrument Bill of Materials (BOM)
     */
    public const STANDARD_INSTRUMENTS = [
        1  => ['item' => 'Solar PV Modules', 'spec' => '540Wp–550Wp Mono PERC / TOPCon Half-Cut Panels', 'qty' => 6, 'unit' => 'Nos.'],
        2  => ['item' => 'Solar Inverter', 'spec' => '3 kW On-Grid Tie String Inverter', 'qty' => 1, 'unit' => 'No.'],
        3  => ['item' => 'Module Mounting Structure (MMS)', 'spec' => 'Galvanized Iron (GI) 3-Panel Landscape Frame Kit (Legs, Rails, Braces)', 'qty' => 2, 'unit' => 'Sets'],
        4  => ['item' => 'Mid Clamps', 'spec' => 'Aluminum with SS Bolt & Nut', 'qty' => 8, 'unit' => 'Nos.'],
        5  => ['item' => 'End Clamps', 'spec' => 'Aluminum with SS Bolt & Nut', 'qty' => 8, 'unit' => 'Nos.'],
        6  => ['item' => 'Anchor Fasteners', 'spec' => 'M10 / M12 Mechanical Concrete Expansion Bolts', 'qty' => 16, 'unit' => 'Nos.'],
        7  => ['item' => 'Hardware Fasteners Set', 'spec' => 'SS 304 M8/M10 Bolts, Nuts, Flat & Spring Washers', 'qty' => 1, 'unit' => 'Lot'],
        8  => ['item' => 'DC Distribution Box (DCDB)', 'spec' => '1-In 1-Out IP65 Enclosure (with DC Fuse & SPD)', 'qty' => 1, 'unit' => 'No.'],
        9  => ['item' => 'AC Distribution Box (ACDB)', 'spec' => 'IP65 Enclosure (with 2-Pole 16A MCB & AC SPD)', 'qty' => 1, 'unit' => 'No.'],
        10 => ['item' => 'DC Solar Cable (Red)', 'spec' => '4 mm² UV-Resistant Copper Cable', 'qty' => 30, 'unit' => 'Meters'],
        11 => ['item' => 'DC Solar Cable (Black)', 'spec' => '4 mm² UV-Resistant Copper Cable', 'qty' => 30, 'unit' => 'Meters'],
        12 => ['item' => 'AC Output Cable', 'spec' => '3-Core 4 mm² Flexible Armored Cable', 'qty' => 20, 'unit' => 'Meters'],
        13 => ['item' => 'MC4 Connectors', 'spec' => 'IP68 Waterproof Pair (Male + Female)', 'qty' => 4, 'unit' => 'Pairs'],
        14 => ['item' => 'PVC Conduit Pipes & Fittings', 'spec' => '25mm Heavy Duty Rigid PVC Pipes with Bends & Saddles', 'qty' => 10, 'unit' => 'Lengths'],
        15 => ['item' => 'Earthing Chemical Electrodes', 'spec' => '2-Meter Chemical Copper Bonded Earthing Rods', 'qty' => 3, 'unit' => 'Nos.'],
        16 => ['item' => 'Earthing Compound', 'spec' => 'Soil Enhancement Backfill Compound (25 kg Bag)', 'qty' => 3, 'unit' => 'Bags'],
        17 => ['item' => 'Earthing Strip / Wire', 'spec' => '25x3 mm GI Strip or 8 SWG Copper Wire', 'qty' => 25, 'unit' => 'Meters'],
        18 => ['item' => 'Lightning Arrester (LA)', 'spec' => 'Early Streamer / Conventional Spike Arrester', 'qty' => 1, 'unit' => 'Set'],
        19 => ['item' => 'Cable Ties & Accessories', 'spec' => 'UV-Resistant Nylon Cable Ties & SS Clamps', 'qty' => 1, 'unit' => 'Packet'],
        20 => ['item' => 'Warning Labels & Tags', 'spec' => 'Solar Hazard & Net-Meter Identification Tags', 'qty' => 1, 'unit' => 'Set'],
    ];

    /**
     * Get list of leads currently at 'LOAN_SANCTIONED' stage (ready for Instrument Despatch)
     */
    public static function getLoanSanctionedLeads(): array
    {
        $sql = "SELECT l.*, c.customer_code, c.first_name as cust_first, c.last_name as cust_last, c.mobile as cust_mobile,
                       c.address_line, c.district as cust_dist, c.block as cust_block, c.gram_panchayat as cust_gp, c.village as cust_vil, c.pincode as cust_pin,
                       c.discom_name as cust_discom, c.consumer_number as cust_ca,
                       a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                       p.brand as package_brand, p.title as package_title, p.capacity_kw as pkg_cap
                FROM leads l
                LEFT JOIN customers c ON l.customer_id = c.id
                LEFT JOIN advisors a ON l.advisor_id = a.id
                LEFT JOIN packages p ON l.package_id = p.id
                WHERE l.stage = 'LOAN_SANCTIONED' OR l.stage = 'INSTRUMENT_DESPATCHED'
                ORDER BY (l.stage = 'LOAN_SANCTIONED') DESC, l.id DESC";
        return Database::fetchAll($sql);
    }

    /**
     * Create a new Package / Instrument Dispatch record
     */
    public static function create(array $data): int
    {
        $leadId = !empty($data['lead_id']) ? (int)$data['lead_id'] : null;
        $customerId = !empty($data['customer_id']) ? (int)$data['customer_id'] : null;
        $advisorId = !empty($data['advisor_id']) ? (int)$data['advisor_id'] : null;

        if ($leadId && !$customerId) {
            $lead = Database::fetchOne("SELECT customer_id, advisor_id FROM leads WHERE id = ?", [$leadId]);
            if ($lead) {
                $customerId = $lead['customer_id'];
                if (!$advisorId) $advisorId = $lead['advisor_id'];
            }
        }

        $tracking = !empty($data['tracking_number']) ? trim($data['tracking_number']) : ('DSP-' . date('Ymd') . '-' . rand(1000, 9999));
        $courier = !empty($data['courier_partner']) ? trim($data['courier_partner']) : 'SVPL Logistics & Transport Desk';
        $vehicleNo = trim($data['vehicle_number'] ?? $data['vehicle_no'] ?? '');
        $driverName = trim($data['driver_name'] ?? $data['person_with_vehicle'] ?? '');
        $driverMobile = trim($data['driver_mobile'] ?? '');
        $vendorName = trim($data['vendor_name'] ?? $data['supplier_vendor'] ?? 'Dhwajja Solar & Tier-1 OEMs');
        $dispatchDate = !empty($data['dispatch_date']) ? trim($data['dispatch_date']) : date('Y-m-d');
        $status = !empty($data['status']) ? trim($data['status']) : 'Dispatched';
        $address = trim($data['delivery_address'] ?? '');
        $remarks = trim($data['remarks'] ?? '');

        // Format items JSON
        $itemsJson = null;
        $itemsSummary = '';
        if (!empty($data['items'])) {
            $itemsJson = is_array($data['items']) ? json_encode($data['items']) : $data['items'];
            $itemsList = is_array($data['items']) ? $data['items'] : json_decode($data['items'], true);
            $names = [];
            if (is_array($itemsList)) {
                foreach ($itemsList as $it) {
                    if (!empty($it['selected']) || !isset($it['selected'])) {
                        $names[] = ($it['item'] ?? '') . ' (' . ($it['qty'] ?? 1) . ' ' . ($it['unit'] ?? '') . ')';
                    }
                }
            }
            $itemsSummary = implode(', ', array_slice($names, 0, 6));
            if (count($names) > 6) {
                $itemsSummary .= ' + ' . (count($names) - 6) . ' more items';
            }
        } else {
            $itemsSummary = trim($data['items_included'] ?? 'Complete 3kW Solar Rooftop Installation Instruments & Fasteners');
        }

        // Media URLs (JSON array of uploaded photos / LR docs)
        $mediaUrls = null;
        if (!empty($data['media_urls'])) {
            $mediaUrls = is_array($data['media_urls']) ? json_encode($data['media_urls']) : $data['media_urls'];
        }

        $engineerId = !empty($data['engineer_id']) ? (int)$data['engineer_id'] : null;

        $sql = "INSERT INTO package_dispatches (
                    lead_id, customer_id, advisor_id, engineer_id, dispatch_type, tracking_number,
                    vehicle_number, driver_name, driver_mobile, vendor_name,
                    courier_partner, dispatch_date, delivery_date,
                    status, items_included, items_json, media_urls, delivery_address, remarks, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        Database::query($sql, [
            $leadId,
            $customerId,
            $advisorId,
            $engineerId,
            $data['dispatch_type'] ?? 'SOLAR_EQUIPMENT',
            $tracking,
            $vehicleNo,
            $driverName,
            $driverMobile,
            $vendorName,
            $courier,
            $dispatchDate,
            $data['delivery_date'] ?? null,
            $status,
            $itemsSummary,
            $itemsJson,
            $mediaUrls,
            $address,
            $remarks
        ]);

        $dispatchId = (int) Database::lastInsertId();

        // Automatically advance the Lead Pipeline stage to 'INSTRUMENT_DESPATCHED' (Stage 6)
        if ($leadId) {
            $note = "Instrument Despatched via Vehicle {$vehicleNo} (Driver: {$driverName}, Mob: {$driverMobile}, Vendor: {$vendorName}, Tracking: {$tracking})";
            LeadPipelineService::advanceStage($leadId, 'INSTRUMENT_DESPATCHED', 'Instrument Despatched', $note);
        }

        return $dispatchId;
    }

    /**
     * Find dispatch by ID with full lead, package, customer & engineer details
     */
    public static function findById(int $id): ?array
    {
        $sql = "SELECT pd.*, l.lead_code, l.proposed_capacity_kw, l.stage as lead_stage,
                       l.estimated_project_cost, l.subsidy_amount, l.customer_payable_amount,
                       c.customer_code, c.first_name as cust_first, c.last_name as cust_last, c.mobile as cust_mobile,
                       c.district as cust_dist, c.block as cust_block, c.gram_panchayat as cust_gp, c.village as cust_vil,
                       c.pincode as cust_pin, c.address_line as cust_address,
                       c.discom_name as cust_discom, c.consumer_number as cust_ca,
                       a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name, a.mobile as advisor_mobile,
                       e.engineer_code, e.full_name as engineer_name, e.mobile as engineer_mobile, e.designation as engineer_designation,
                       p.title as package_title, p.brand as package_brand, p.capacity_kw as pkg_cap, p.total_price as package_price
                FROM package_dispatches pd
                LEFT JOIN leads l ON pd.lead_id = l.id
                LEFT JOIN customers c ON (pd.customer_id = c.id OR l.customer_id = c.id)
                LEFT JOIN advisors a ON (pd.advisor_id = a.id OR l.advisor_id = a.id)
                LEFT JOIN engineers e ON pd.engineer_id = e.id
                LEFT JOIN packages p ON l.package_id = p.id
                WHERE pd.id = ?";
        return Database::fetchOne($sql, [$id]);
    }

    /**
     * Find latest dispatch by Customer ID
     */
    public static function findByCustomerId(int $customerId): ?array
    {
        $sql = "SELECT pd.*, l.lead_code, l.proposed_capacity_kw, l.stage as lead_stage,
                       l.estimated_project_cost, l.customer_payable_amount,
                       c.customer_code, c.first_name as cust_first, c.last_name as cust_last, c.mobile as cust_mobile,
                       c.district as cust_dist, c.block as cust_block, c.discom_name as cust_discom, c.consumer_number as cust_ca,
                       e.engineer_code, e.full_name as engineer_name, e.mobile as engineer_mobile, e.designation as engineer_designation,
                       p.title as package_title, p.brand as package_brand, p.capacity_kw as pkg_cap
                FROM package_dispatches pd
                LEFT JOIN leads l ON pd.lead_id = l.id
                LEFT JOIN customers c ON (pd.customer_id = c.id OR l.customer_id = c.id)
                LEFT JOIN engineers e ON pd.engineer_id = e.id
                LEFT JOIN packages p ON l.package_id = p.id
                WHERE pd.customer_id = ? OR l.customer_id = ?
                ORDER BY pd.id DESC LIMIT 1";
        return Database::fetchOne($sql, [$customerId, $customerId]);
    }

    /**
     * Get all dispatches
     */
    public static function getAll(int $limit = 100): array
    {
        $sql = "SELECT pd.*, l.lead_code, l.stage as lead_stage,
                       c.customer_code, CONCAT(c.first_name, ' ', c.last_name) as customer_name, c.mobile as cust_mobile, c.district as cust_district,
                       a.advisor_code, CONCAT(a.first_name, ' ', a.last_name) as advisor_name,
                       e.engineer_code, e.full_name as engineer_name, e.mobile as engineer_mobile
                FROM package_dispatches pd
                LEFT JOIN leads l ON pd.lead_id = l.id
                LEFT JOIN customers c ON (pd.customer_id = c.id OR l.customer_id = c.id)
                LEFT JOIN advisors a ON pd.advisor_id = a.id
                LEFT JOIN engineers e ON pd.engineer_id = e.id
                ORDER BY pd.id DESC LIMIT {$limit}";
        return Database::fetchAll($sql);
    }

    /**
     * Customer Delivery Receipt Acknowledgment
     */
    public static function acknowledgeReceipt(int $dispatchId, ?string $notes = null): bool
    {
        $res = Database::execute(
            "UPDATE package_dispatches SET 
                customer_acknowledged = 1, 
                customer_acknowledged_at = NOW(), 
                customer_acknowledgment_notes = ?,
                status = 'Delivered',
                delivery_date = COALESCE(delivery_date, CURDATE())
             WHERE id = ?",
            [$notes, $dispatchId]
        );

        if ($res) {
            $dsp = self::findById($dispatchId);
            if (!empty($dsp['lead_id'])) {
                $lead = Database::fetchOne("SELECT stage FROM leads WHERE id = ?", [$dsp['lead_id']]);
                if ($lead && in_array($lead['stage'], ['LOAN_SANCTIONED', 'INSTRUMENT_DESPATCHED'])) {
                    LeadPipelineService::advanceStage(
                        (int)$dsp['lead_id'],
                        'INSTALLATION_COMMENCED',
                        'Solar Installation Commenced',
                        'Customer acknowledged receipt of all 20 solar instruments on site. Installation commenced by assigned engineer.'
                    );
                }
            }
        }

        return $res;
    }

    /**
     * Update dispatch delivery status
     */
    public static function updateStatus(int $id, string $status, ?string $deliveryDate = null): bool
    {
        if ($status === 'Delivered' && empty($deliveryDate)) {
            $deliveryDate = date('Y-m-d');
        }
        $res = Database::execute(
            "UPDATE package_dispatches SET status = ?, delivery_date = COALESCE(?, delivery_date) WHERE id = ?",
            [$status, $deliveryDate, $id]
        );

        if ($status === 'Delivered') {
            $dsp = self::findById($id);
            if (!empty($dsp['lead_id'])) {
                // When delivered, advance to Installation Commenced if currently at Instrument Despatched
                $lead = Database::fetchOne("SELECT stage FROM leads WHERE id = ?", [$dsp['lead_id']]);
                if ($lead && in_array($lead['stage'], ['LOAN_SANCTIONED', 'INSTRUMENT_DESPATCHED'])) {
                    LeadPipelineService::advanceStage((int)$dsp['lead_id'], 'INSTALLATION_COMMENCED', 'Solar Installation Commenced', 'Materials delivered on site.');
                }
            }
        }

        return $res;
    }
}
