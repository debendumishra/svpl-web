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
('tds_percentage', '5.00', 'tax', 'number', 'TDS deduction percentage on commission payout')
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

-- 5. Standard PM Surya Ghar Solar Rooftop Packages (Dhwajja Solar Official)
INSERT INTO `packages` (`id`, `package_code`, `title`, `capacity_kw`, `panel_type`, `inverter_type`, `battery_included`, `total_price`, `estimated_subsidy`, `net_customer_cost`, `is_active`) VALUES
(2, 'PKG-2KW-DHWAJJA', 'Dhwajja Solar 2kW Plant (120 sq.ft. Roof)', 2.00, 'Mono-PERC Half-Cut 540W Tier-1', '2kW Grid-Tied Inverter with Smart Wi-Fi Monitoring', 0, 160000.00, 110000.00, 50000.00, 1),
(3, 'PKG-3KW-DHWAJJA', 'Dhwajja Solar 3kW Plant (180 sq.ft. Roof)', 3.00, 'Bi-facial / Mono-PERC 550W Tier-1', '3kW Dual-MPPT Smart Inverter with Remote App', 0, 210000.00, 138000.00, 72000.00, 1),
(4, 'PKG-4KW-DHWAJJA', 'Dhwajja Solar 4kW Plant (210 sq.ft. Roof)', 4.00, 'Mono-PERC High Yield 550W Tier-1', '4kW Dual-MPPT Smart Grid Inverter', 0, 260000.00, 138000.00, 122000.00, 1),
(5, 'PKG-5KW-DHWAJJA', 'Dhwajja Solar 5kW Plant (270 sq.ft. Roof)', 5.00, 'Bi-facial 550W Tier-1 High Yield', '5kW 3-Phase Smart Grid-Tied Inverter', 0, 330000.00, 138000.00, 192000.00, 1)
ON DUPLICATE KEY UPDATE `total_price` = VALUES(`total_price`), `estimated_subsidy` = VALUES(`estimated_subsidy`), `net_customer_cost` = VALUES(`net_customer_cost`);

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
