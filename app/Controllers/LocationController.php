<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Location Controller - AJAX Cascading Dropdown APIs
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Models\Location;

class LocationController
{
    public function getDistricts(): void
    {
        $districts = Location::getDistricts();
        Response::json(['districts' => $districts]);
    }

    public function getBlocks(): void
    {
        $district = $_GET['district'] ?? 'Khordha';
        $blocks = Location::getBlocks($district);
        Response::json(['blocks' => $blocks]);
    }

    public function getGPs(): void
    {
        $block = $_GET['block'] ?? 'Bhubaneswar';
        $gps = Location::getGramPanchayats($block);
        Response::json(['gps' => $gps]);
    }
}
