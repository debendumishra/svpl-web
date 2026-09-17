-- ==========================================================
-- Surya Vistaara Pvt. Ltd. (SVPL)
-- Master Seed Data (Admins, System Settings, Test Advisors & Pipeline Data)
-- ==========================================================

-- 1. Default Roles
INSERT INTO `roles` (`name`, `slug`, `description`) VALUES
('Super Administrator', 'SUPER_ADMIN', 'Full unrestricted platform access'),
('Administrator', 'ADMIN', 'Administrative management'),
('Accounts Manager', 'ACCOUNTS', 'Financials, commissions, payouts'),
('Operations Executive', 'OPERATIONS', 'Lead workflow, surveys, dispatches'),
('Solar Advisor', 'ADVISOR', 'Field advisor network'),
('Customer', 'CUSTOMER', 'Rooftop solar applicant')
ON DUPLICATE KEY UPDATE `description` = VALUES(`description`);

-- 2. Master System Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `category`, `data_type`, `description`) VALUES
('company_name', 'Surya Vistaara Pvt. Ltd.', 'company', 'string', 'Official Legal Entity Name'),
('company_short_name', 'SVPL', 'company', 'string', 'Short Acronym'),
('promoter_entity', 'Dhwajja Solar India Pvt. Ltd.', 'company', 'string', 'Promoter Company in Odisha'),
('support_email', 'dhwajjasolarsupport@gmail.com', 'contact', 'string', 'Customer & Advisor Support Email'),
('support_phone', '9040999899', 'contact', 'string', 'Central Helpline Number'),
('support_whatsapp', '9040999899', 'contact', 'string', 'WhatsApp Support Helpline'),
('head_office_address', 'MIG-84, Pokhariput, BDA Colony, Phase-1 Pokhariput, Bhubaneswar, Odisha - 751020', 'company', 'string', 'Official Registered Office Address'),
('gstin', '21AAMCD5948B1ZU', 'company', 'string', 'Official GST Number'),
('advisor_joining_fee', '1500.00', 'advisor', 'number', 'Advisor Registration / Induction Kit Fee in INR'),
('advisor_required_customers', '3', 'advisor', 'number', 'Number of completed customers needed to achieve QUALIFIED status'),
('max_genealogy_depth', '9', 'network', 'number', 'Maximum Multi-Level Hierarchy Depth'),
('direct_customer_bonus', '500.00', 'commission', 'number', 'Bonus earned per completed direct customer in INR'),
('default_subsidy_2kw_central', '60000.00', 'subsidy', 'number', 'PM Surya Ghar Central Subsidy for 2kW'),
('default_subsidy_2kw_state', '50000.00', 'subsidy', 'number', 'Odisha State Subsidy for 2kW'),
('default_subsidy_3kw_central', '78000.00', 'subsidy', 'number', 'PM Surya Ghar Central Subsidy for 3kW'),
('default_subsidy_3kw_state', '60000.00', 'subsidy', 'number', 'Odisha State Subsidy for 3kW'),
('solar_loan_interest_rate', '5.60', 'finance', 'number', 'Concessional Solar Loan Interest Rate (% p.a.)'),
('min_payout_threshold', '500.00', 'wallet', 'number', 'Minimum wallet balance required for withdrawal request'),
('tds_percentage', '5.00', 'tax', 'number', 'TDS deduction percentage on commission payout'),
('qr_verify_base_url', 'https://suryavistaara.com', 'company', 'string', 'Base Domain for ID Card & Document QR Verification')
ON DUPLICATE KEY UPDATE `setting_value` = VALUES(`setting_value`);

-- 3. Default Commission Plan Slabs (9 Levels for PM Surya Ghar)
INSERT INTO `commission_plans` (`plan_name`, `level`, `commission_amount`, `bonus_amount`, `description`) VALUES
('Level 1 Direct Sponsor', 1, 1000.00, 500.00, 'Direct sponsor commission for 1kW-3kW rooftop installations'),
('Level 2 Upline', 2, 400.00, 0.00, 'Level 2 hierarchy commission'),
('Level 3 Upline', 3, 250.00, 0.00, 'Level 3 hierarchy commission'),
('Level 4 Upline', 4, 150.00, 0.00, 'Level 4 hierarchy commission'),
('Level 5 Upline', 5, 100.00, 0.00, 'Level 5 hierarchy commission'),
('Level 6 Upline', 6, 75.00, 0.00, 'Level 6 hierarchy commission'),
('Level 7 Upline', 7, 50.00, 0.00, 'Level 7 hierarchy commission'),
('Level 8 Upline', 8, 40.00, 0.00, 'Level 8 hierarchy commission'),
('Level 9 Upline', 9, 35.00, 0.00, 'Level 9 hierarchy commission')
ON DUPLICATE KEY UPDATE `commission_amount` = VALUES(`commission_amount`);

-- 4. Sample Odisha Administrative Locations (Sample Blocks & Panchayats)
INSERT INTO `locations` (`state`, `district`, `subdivision`, `block`, `gram_panchayat`, `village`, `pincode`) VALUES
('Odisha', 'Khordha', 'Bhubaneswar', 'Bhubaneswar', 'Chandaka', 'Chandaka Village', '751024'),
('Odisha', 'Khordha', 'Bhubaneswar', 'Bhubaneswar', 'Mendhasala', 'Mendhasala', '752054'),
('Odisha', 'Khordha', 'Bhubaneswar', 'Jatni', 'Khurdha Road', 'Jatni Town', '752050'),
('Odisha', 'Khordha', 'Bhubaneswar', 'Balianta', 'Benupur', 'Balianta Village', '752101'),
('Odisha', 'Cuttack', 'Cuttack Sadar', 'Baranga', 'Naranpur', 'Baranga Bazar', '754005'),
('Odisha', 'Cuttack', 'Cuttack Sadar', 'Salepur', 'Bahugram', 'Salepur Market', '754202'),
('Odisha', 'Puri', 'Puri', 'Pipili', 'Dandamakundapur', 'Pipili Craft Village', '752104'),
('Odisha', 'Ganjam', 'Berhampur', 'Chhatrapur', 'Agasti Nuagaon', 'Chhatrapur Town', '761020'),
('Odisha', 'Sambalpur', 'Sambalpur', 'Maneswar', 'Dhankauda', 'Khetrajpur', '768001'),
('Odisha', 'Balasore', 'Balasore', 'Remuna', 'Remuna', 'Remuna Golei', '756019');

-- 5. Standard PM Surya Ghar Solar Rooftop Packages (Tier-1 Brand Catalog)
INSERT INTO `packages` (`package_code`, `brand`, `title`, `capacity_kw`, `system_type`, `panel_type`, `inverter_type`, `key_features`, `battery_included`, `total_price`, `estimated_subsidy`, `net_customer_cost`, `is_active`) VALUES
('PKG-TATA-3KW-ONGRID', 'Tata Power Solar', 'Tata Power Solar 3kW On-Grid Plant', 3.00, 'On-Grid', 'Mono PERC / DCR High-Efficiency Tier-1', '3kW Smart Grid-Tied Inverter (Wi-Fi)', 'Uses high-efficiency Mono PERC / DCR panels; tier-1 brand value and nationwide service network.', 0, 230000.00, 138000.00, 92000.00, 1),
('PKG-TATA-5KW-ONGRID', 'Tata Power Solar', 'Tata Power Solar 5kW On-Grid Plant', 5.00, 'On-Grid', 'Mono PERC / DCR High-Efficiency Tier-1', '5kW 3-Phase Grid-Tied Inverter (Wi-Fi)', 'Uses high-efficiency Mono PERC / DCR panels; tier-1 brand value and nationwide service network.', 0, 360000.00, 138000.00, 222000.00, 1),
('PKG-TATA-10KW-ONGRID-HYBRID', 'Tata Power Solar', 'Tata Power Solar 10kW On-Grid / Hybrid Plant', 10.00, 'On-Grid / Hybrid', 'Mono PERC / DCR High-Efficiency Tier-1', '10kW Dual MPPT 3-Phase Grid/Hybrid Inverter', 'Uses high-efficiency Mono PERC / DCR panels; tier-1 brand value and nationwide service network.', 0, 620000.00, 138000.00, 482000.00, 1),
('PKG-WAAREE-3KW-ONGRID', 'Waaree Energies', 'Waaree Energies 3kW On-Grid Plant', 3.00, 'On-Grid', 'Mono PERC / TOPCon / Bifacial Tier-1', '3kW Smart Grid-Tied Inverter', 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.', 0, 215000.00, 138000.00, 77000.00, 1),
('PKG-WAAREE-3KW-HYBRID', 'Waaree Energies', 'Waaree Energies 3kW Hybrid Plant (Lithium LFP)', 3.00, 'Hybrid', 'Mono PERC / TOPCon / Bifacial Tier-1', '3kW Hybrid Inverter with Energy Management', 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.', 1, 330000.00, 138000.00, 192000.00, 1),
('PKG-WAAREE-5KW-HYBRID', 'Waaree Energies', 'Waaree Energies 5kW Hybrid Plant (Lithium LFP)', 5.00, 'Hybrid', 'Mono PERC / TOPCon / Bifacial Tier-1', '5kW Hybrid Inverter (Wi-Fi Enabled)', 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.', 1, 500000.00, 138000.00, 362000.00, 1),
('PKG-WAAREE-10KW-HYBRID', 'Waaree Energies', 'Waaree Energies 10kW Hybrid Plant (Lithium LFP)', 10.00, 'Hybrid', 'Mono PERC / TOPCon / Bifacial Tier-1', '10kW 3-Phase Commercial Hybrid Inverter', 'Offers Mono PERC, TOPCon, and Bifacial panels. Hybrid setups include Lithium-ion (LFP) battery storage.', 1, 1000000.00, 138000.00, 862000.00, 1),
('PKG-ADANI-3KW-ONGRID', 'Adani Solar', 'Adani Solar 3kW On-Grid Plant', 3.00, 'On-Grid', 'TOPCon / Mono PERC Half-Cut Technology', '3kW Smart Dual-MPPT Grid Inverter', 'Uses TOPCon / Mono PERC half-cut technology; widely used in residential and commercial projects.', 0, 215000.00, 138000.00, 77000.00, 1),
('PKG-ADANI-5KW-ONGRID', 'Adani Solar', 'Adani Solar 5kW On-Grid Plant', 5.00, 'On-Grid', 'TOPCon / Mono PERC Half-Cut Technology', '5kW Dual-MPPT Grid-Tied Inverter', 'Uses TOPCon / Mono PERC half-cut technology; widely used in residential and commercial projects.', 0, 340000.00, 138000.00, 202000.00, 1),
('PKG-ADANI-10KW-ONGRID-HYBRID', 'Adani Solar', 'Adani Solar 10kW On-Grid / Hybrid Plant', 10.00, 'On-Grid / Hybrid', 'TOPCon / Mono PERC Half-Cut Technology', '10kW 3-Phase On-Grid / Hybrid Inverter', 'Uses TOPCon / Mono PERC half-cut technology; widely used in residential and commercial projects.', 0, 800000.00, 138000.00, 662000.00, 1),
('PKG-LOOM-3KW-ONGRID', 'Loom Solar', 'Loom Solar 3kW Shark On-Grid Plant', 3.00, 'On-Grid', 'Shark Series (Mono PERC / TOPCon Bi-facial)', '3kW High-Efficiency Grid-Tied Inverter', 'Specializes in high-efficiency Shark series (Mono PERC / TOPCon) and compact lithium battery integrations.', 0, 240000.00, 138000.00, 102000.00, 1),
('PKG-LOOM-5KW-HYBRID', 'Loom Solar', 'Loom Solar 5kW Shark Hybrid Plant (Lithium)', 5.00, 'Hybrid', 'Shark Series (Mono PERC / TOPCon Bi-facial)', '5kW Hybrid Inverter with Smart App', 'Specializes in high-efficiency Shark series (Mono PERC / TOPCon) and compact lithium battery integrations.', 1, 550000.00, 138000.00, 412000.00, 1),
('PKG-LOOM-10KW-HYBRID', 'Loom Solar', 'Loom Solar 10kW Shark Hybrid Plant (Lithium)', 10.00, 'Hybrid', 'Shark Series (Mono PERC / TOPCon Bi-facial)', '10kW 3-Phase Heavy-Duty Hybrid Inverter', 'Specializes in high-efficiency Shark series (Mono PERC / TOPCon) and compact lithium battery integrations.', 1, 1200000.00, 138000.00, 1062000.00, 1),
('PKG-UTL-LUMINOUS-3KW-ONGRID', 'UTL Solar / Luminous', 'UTL Solar / Luminous 3kW On-Grid Plant', 3.00, 'On-Grid', 'Mono PERC Budget High-Yield Panels', '3kW Residential Grid-Tied Inverter', 'Budget-friendly residential setups; offers both lead-acid/tubular and lithium-ion battery options.', 0, 200000.00, 138000.00, 62000.00, 1),
('PKG-UTL-LUMINOUS-5KW-OFFGRID', 'UTL Solar / Luminous', 'UTL Solar / Luminous 5kW Off-Grid Plant', 5.00, 'Off-Grid', 'High Yield Mono PERC Solar Panels', '5kW MPPT Pure Sine Wave Off-Grid PCU', 'Budget-friendly residential setups; offers both lead-acid/tubular and lithium-ion battery options.', 1, 350000.00, 0.00, 350000.00, 1),
('PKG-UTL-LUMINOUS-5KW-HYBRID', 'UTL Solar / Luminous', 'UTL Solar / Luminous 5kW Hybrid Plant', 5.00, 'Hybrid', 'High Yield Mono PERC Solar Panels', '5kW Smart Hybrid Solar PCU / Inverter', 'Budget-friendly residential setups; offers both lead-acid/tubular and lithium-ion battery options.', 1, 480000.00, 138000.00, 342000.00, 1),
('PKG-IYRO-3KW-ONGRID', 'IYRO Solar', 'IYRO Solar 3kW On-Grid Plant', 3.00, 'On-Grid', 'Mono PERC High Yield Panels', '3kW Grid-Tied Inverter', 'None', 0, 200000.00, 138000.00, 62000.00, 1),
('PKG-IYRO-3KW-HYBRID', 'IYRO Solar', 'IYRO Solar 3kW Hybrid Plant (2× Batteries)', 3.00, 'Hybrid', 'Mono PERC High Yield Panels', '3kW Smart Hybrid Inverter', '2× 150Ah / 200Ah C10 Tubular or Lithium', 1, 290000.00, 138000.00, 152000.00, 1),
('PKG-IYRO-5KW-ONGRID', 'IYRO Solar', 'IYRO Solar 5kW On-Grid Plant', 5.00, 'On-Grid', 'Mono PERC High Yield Panels', '5kW Grid-Tied Inverter', 'None', 0, 280000.00, 138000.00, 142000.00, 1),
('PKG-IYRO-5KW-HYBRID', 'IYRO Solar', 'IYRO Solar 5kW Hybrid Plant (4× Batteries)', 5.00, 'Hybrid', 'Mono PERC High Yield Panels', '5kW Smart Hybrid Inverter', '4× 150Ah / 200Ah Solar Batteries', 1, 440000.00, 138000.00, 302000.00, 1),
('PKG-IYRO-10KW-ONGRID', 'IYRO Solar', 'IYRO Solar 10kW On-Grid Plant', 10.00, 'On-Grid', 'Mono PERC Tier-1 High Yield Panels', '10kW 3-Phase Grid-Tied Inverter', 'None', 0, 540000.00, 138000.00, 402000.00, 1),
('PKG-IYRO-10KW-HYBRID', 'IYRO Solar', 'IYRO Solar 10kW Hybrid Plant (High-Capacity Battery Bank)', 10.00, 'Hybrid', 'Mono PERC Tier-1 High Yield Panels', '10kW 3-Phase Heavy-Duty Hybrid Inverter', 'High-capacity battery bank (Lithium/Tubular)', 1, 780000.00, 138000.00, 642000.00, 1)
ON DUPLICATE KEY UPDATE `brand` = VALUES(`brand`), `title` = VALUES(`title`), `capacity_kw` = VALUES(`capacity_kw`), `system_type` = VALUES(`system_type`), `panel_type` = VALUES(`panel_type`), `inverter_type` = VALUES(`inverter_type`), `key_features` = VALUES(`key_features`), `battery_included` = VALUES(`battery_included`), `total_price` = VALUES(`total_price`), `estimated_subsidy` = VALUES(`estimated_subsidy`), `net_customer_cost` = VALUES(`net_customer_cost`), `is_active` = VALUES(`is_active`);

-- 6. Default Admin & Demo Users (Password: Password@123 -> $2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW)
INSERT INTO `users` (`id`, `role`, `email`, `mobile`, `password_hash`, `full_name`, `is_active`) VALUES
(1, 'SUPER_ADMIN', 'admin@suryavistaara.com', '9876543210', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'SVPL Super Administrator', 1),
(2, 'ADMIN', 'manager@suryavistaara.com', '9876543211', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'Operations Manager', 1),
(3, 'ACCOUNTS', 'accounts@suryavistaara.com', '9876543212', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'Chief Accounts Officer', 1),
(4, 'ADVISOR', 'rajesh.mohanty@suryavistaara.com', '9437012345', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'Rajesh Mohanty', 1),
(5, 'ADVISOR', 'priya.patra@suryavistaara.com', '9437023456', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'Priya Patra', 1),
(6, 'ADVISOR', 'alok.jena@suryavistaara.com', '9437034567', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'Alok Jena', 1),
(7, 'CUSTOMER', 'debashis.sahoo@gmail.com', '9861011223', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'Debashis Sahoo', 1),
(8, 'CUSTOMER', 'manoj.tripathy@gmail.com', '9861022334', '$2y$10$XSXtOCLLsH2oGT23iBlx2.7KOyaw9gC8L4mNXMdT73ZnXlQjZKHEW', 'Manoj Tripathy', 1)
ON DUPLICATE KEY UPDATE `password_hash` = VALUES(`password_hash`), `full_name` = VALUES(`full_name`);

-- 7. Advisors Profile & Hierarchy Setup
-- Level 1: Rajesh Mohanty (Advisor 1 - Top Leader)
INSERT INTO `advisors` (`id`, `user_id`, `advisor_code`, `referral_code`, `sponsor_id`, `first_name`, `last_name`, `dob`, `gender`, `mobile`, `email`, `state`, `district`, `block`, `gram_panchayat`, `pincode`, `status`, `joining_fee_paid`, `direct_customer_count`, `qualified_at`) VALUES
(1, 4, 'SVPL-ADV-1001', 'SVPL1001', NULL, 'Rajesh', 'Mohanty', '1988-05-15', 'Male', '9437012345', 'rajesh.mohanty@suryavistaara.com', 'Odisha', 'Khordha', 'Bhubaneswar', 'Chandaka', '751024', 'QUALIFIED', 1, 3, NOW()),
-- Level 2: Priya Patra (Sponsored by Rajesh)
(2, 5, 'SVPL-ADV-1002', 'SVPL1002', 1, 'Priya', 'Patra', '1992-08-20', 'Female', '9437023456', 'priya.patra@suryavistaara.com', 'Odisha', 'Cuttack', 'Baranga', 'Naranpur', '754005', 'QUALIFIED', 1, 3, NOW()),
-- Level 3: Alok Jena (Sponsored by Priya)
(3, 6, 'SVPL-ADV-1003', 'SVPL1003', 2, 'Alok', 'Jena', '1995-11-10', 'Male', '9437034567', 'alok.jena@suryavistaara.com', 'Odisha', 'Puri', 'Pipili', 'Dandamakundapur', '752104', 'ACTIVE', 1, 1, NULL)
ON DUPLICATE KEY UPDATE `advisor_code` = VALUES(`advisor_code`);

-- 8. Advisor Genealogy Closure Table (9-Level Tree Structure)
INSERT INTO `advisor_genealogy` (`ancestor_id`, `descendant_id`, `depth`) VALUES
(1, 1, 0),
(2, 2, 0),
(3, 3, 0),
(1, 2, 1),
(2, 3, 1),
(1, 3, 2)
ON DUPLICATE KEY UPDATE `depth` = VALUES(`depth`);

-- 9. Wallets for Advisors
INSERT INTO `wallets` (`user_id`, `balance`, `total_earned`, `total_withdrawn`, `pending_clearance`) VALUES
(4, 3500.00, 3500.00, 0.00, 500.00),
(5, 1400.00, 1400.00, 0.00, 200.00),
(6, 500.00, 500.00, 0.00, 0.00)
ON DUPLICATE KEY UPDATE `balance` = VALUES(`balance`);

-- 10. Sample Customers
INSERT INTO `customers` (`id`, `user_id`, `customer_code`, `advisor_id`, `first_name`, `last_name`, `mobile`, `email`, `state`, `district`, `block`, `gram_panchayat`, `pincode`, `discom_name`, `consumer_number`, `sanctioned_load_kw`, `proposed_solar_kw`, `status`) VALUES
(1, 7, 'SVPL-CUST-2001', 1, 'Debashis', 'Sahoo', '9861011223', 'debashis.sahoo@gmail.com', 'Odisha', 'Khordha', 'Bhubaneswar', 'Chandaka', '751024', 'TPCODL', 'TPC-7890124', 3.00, 3.00, 'Installation Completed'),
(2, 8, 'SVPL-CUST-2002', 2, 'Manoj', 'Tripathy', '9861022334', 'manoj.tripathy@gmail.com', 'Odisha', 'Cuttack', 'Baranga', 'Naranpur', '754005', 'TPCODL', 'TPC-4567891', 2.00, 2.00, 'Loan Sanctioned')
ON DUPLICATE KEY UPDATE `customer_code` = VALUES(`customer_code`);

-- 11. Sample Leads Pipeline
INSERT INTO `leads` (`id`, `lead_code`, `customer_id`, `advisor_id`, `lead_source`, `first_name`, `last_name`, `mobile`, `email`, `state`, `district`, `block`, `gram_panchayat`, `pincode`, `discom_name`, `consumer_number`, `proposed_capacity_kw`, `package_id`, `stage`, `status`, `estimated_project_cost`, `subsidy_amount`, `state_subsidy`, `customer_payable_amount`) VALUES
(1, 'SVPL-LEAD-3001', 1, 1, 'Advisor Referral', 'Debashis', 'Sahoo', '9861011223', 'debashis.sahoo@gmail.com', 'Odisha', 'Khordha', 'Bhubaneswar', 'Chandaka', '751024', 'TPCODL', 'TPC-7890124', 3.00, 3, 'SUBSIDY_APPLIED', 'Subsidy Applied', 210000.00, 78000.00, 60000.00, 72000.00),
(2, 'SVPL-LEAD-3002', 2, 2, 'Advisor Referral', 'Manoj', 'Tripathy', '9861022334', 'manoj.tripathy@gmail.com', 'Odisha', 'Cuttack', 'Baranga', 'Naranpur', '754005', 'TPCODL', 'TPC-4567891', 2.00, 2, 'LOAN_SANCTIONED', 'Loan Sanctioned', 160000.00, 78000.00, 60000.00, 22000.00)
ON DUPLICATE KEY UPDATE `lead_code` = VALUES(`lead_code`), `estimated_project_cost` = VALUES(`estimated_project_cost`), `subsidy_amount` = VALUES(`subsidy_amount`), `state_subsidy` = VALUES(`state_subsidy`), `customer_payable_amount` = VALUES(`customer_payable_amount`);
