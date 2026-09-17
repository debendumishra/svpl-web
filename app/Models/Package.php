<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Package Model — Tier-1 Solar Rooftop & Brand Packages
 */

namespace App\Models;

use App\Helpers\Database;

class Package
{
    /**
     * Get all active packages ordered by brand and capacity
     */
    public static function getAllActive(): array
    {
        return Database::fetchAll(
            "SELECT * FROM packages WHERE is_active = 1 ORDER BY brand ASC, capacity_kw ASC, total_price ASC"
        );
    }

    /**
     * Find package by ID
     */
    public static function findById(int $id): ?array
    {
        return Database::fetchOne("SELECT * FROM packages WHERE id = ?", [$id]);
    }

    /**
     * Find package by package code
     */
    public static function findByCode(string $code): ?array
    {
        return Database::fetchOne("SELECT * FROM packages WHERE package_code = ?", [$code]);
    }

    /**
     * Get packages by Brand / Vendor
     */
    public static function getByBrand(string $brand): array
    {
        return Database::fetchAll(
            "SELECT * FROM packages WHERE brand = ? AND is_active = 1 ORDER BY capacity_kw ASC, total_price ASC",
            [$brand]
        );
    }

    /**
     * Get packages by System Type (On-Grid, Hybrid, Off-Grid)
     */
    public static function getBySystemType(string $systemType): array
    {
        return Database::fetchAll(
            "SELECT * FROM packages WHERE (system_type = ? OR system_type LIKE ?) AND is_active = 1 ORDER BY capacity_kw ASC, total_price ASC",
            [$systemType, "%{$systemType}%"]
        );
    }

    /**
     * Get distinct list of available Brands
     */
    public static function getBrands(): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT brand FROM packages WHERE brand IS NOT NULL AND is_active = 1 ORDER BY brand ASC");
        return array_column($rows, 'brand');
    }

    /**
     * Get distinct list of System Types
     */
    public static function getSystemTypes(): array
    {
        $rows = Database::fetchAll("SELECT DISTINCT system_type FROM packages WHERE system_type IS NOT NULL AND is_active = 1 ORDER BY system_type ASC");
        return array_column($rows, 'system_type');
    }
}
