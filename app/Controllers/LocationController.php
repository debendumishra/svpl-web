<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Location Controller - AJAX Cascading Dropdown APIs (51,804 Odisha Locations)
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Location;

class LocationController
{
    public function getDistricts(): void
    {
        $districts = Location::getDistricts();
        Response::json([
            'success' => true,
            'districts' => $districts,
            'count' => count($districts),
        ]);
    }

    public function getBlocks(): void
    {
        $district = trim($_GET['district'] ?? '');
        if (empty($district)) {
            Response::json(['success' => false, 'blocks' => [], 'message' => 'District parameter is required']);
            return;
        }
        $blocks = Location::getBlocks($district);
        Response::json([
            'success' => true,
            'district' => $district,
            'blocks' => $blocks,
            'count' => count($blocks),
        ]);
    }

    public function getGPs(): void
    {
        $block = trim($_GET['block'] ?? '');
        if (empty($block)) {
            Response::json(['success' => false, 'gps' => [], 'message' => 'Block parameter is required']);
            return;
        }
        $gps = Location::getGramPanchayats($block);
        Response::json([
            'success' => true,
            'block' => $block,
            'gps' => $gps,
            'count' => count($gps),
        ]);
    }

    public function getVillages(): void
    {
        $gp = trim($_GET['gp'] ?? ($_GET['gram_panchayat'] ?? ''));
        $block = !empty($_GET['block']) ? trim($_GET['block']) : null;
        if (empty($gp)) {
            Response::json(['success' => false, 'villages' => [], 'message' => 'Gram Panchayat parameter is required']);
            return;
        }
        $villages = Location::getVillages($gp, $block);
        Response::json([
            'success' => true,
            'gram_panchayat' => $gp,
            'villages' => $villages,
            'count' => count($villages),
        ]);
    }

    public function getByPincode(): void
    {
        $pincode = trim($_GET['pincode'] ?? ($_GET['pin'] ?? ''));
        if (empty($pincode)) {
            Response::json(['success' => false, 'records' => [], 'message' => 'Pincode parameter is required']);
            return;
        }
        $records = Location::getByPincode($pincode);
        Response::json([
            'success' => !empty($records),
            'pincode' => $pincode,
            'records' => $records,
            'count' => count($records),
        ]);
    }

    public function search(): void
    {
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) {
            Response::json(['success' => false, 'results' => [], 'message' => 'Search term must be at least 2 characters']);
            return;
        }
        $results = Location::search($q, 30);
        Response::json([
            'success' => true,
            'query' => $q,
            'results' => $results,
            'count' => count($results),
        ]);
    }
}
