<?php
/**
 * Dhwajja Solar India Pvt. Ltd. / Surya Vistaara Pvt. Ltd.
 * Printable 4-Page PM Surya Ghar Consumer-Vendor Model Draft Agreement (Annexure 2)
 * Exact replica of the official PM Surya Ghar Scheme Model Draft Agreement.
 */

$companyName = function_exists('company_name') ? company_name() : "DHWAJJA SOLAR INDIA PVT LTD";
$companyAddress = function_exists('company_address') && company_address() ? company_address() : "MIG-84, Phokhariput, BDA Colony, Phase-1, Pokhariput, Bhubaneswar, Khordha, Odisha, 751020";
$companySignature = function_exists('company_signature_url') ? company_signature_url() : "/assets/images/authorised_signatory.png";

// Customer Details
$custName = strtoupper(trim(($customer['first_name'] ?? ($lead['first_name'] ?? '')) . ' ' . ($customer['last_name'] ?? ($lead['last_name'] ?? ''))));
if (empty($custName)) $custName = "..................................................................";

$careOf = trim($customer['father_husband_name'] ?? '');
if (empty($careOf)) $careOf = "..............................................................";

$atAddress = strtoupper(trim($customer['village'] ?? ($customer['address_line'] ?? '.........................................................')));
$poAddress = strtoupper(trim($customer['block'] ?? ($customer['gram_panchayat'] ?? '.........................................................')));
$distAddress = strtoupper(trim($customer['district'] ?? ($lead['district'] ?? '.......................................................')));
$pinAddress = trim($customer['pincode'] ?? ($lead['pincode'] ?? '....................'));

$consumerNo = trim($customer['consumer_number'] ?? ($lead['consumer_number'] ?? '..........................'));
$notificationNo = trim($customer['notification_number'] ?? ($customer['pm_surya_ghar_id'] ?? 'party/consumer/purchaser/owner of system.'));

$agreementDate = !empty($customer['agreement_accepted_at']) 
    ? date('d/m/Y', strtotime($customer['agreement_accepted_at'])) 
    : (!empty($customer['created_at']) ? date('d/m/Y', strtotime($customer['created_at'])) : date('d/m/Y'));

$executionDay = date('d', strtotime(str_replace('/', '-', $agreementDate)));
$executionMonth = date('m', strtotime(str_replace('/', '-', $agreementDate)));
$executionYear = date('Y', strtotime(str_replace('/', '-', $agreementDate)));

$customerSignature = $customer['customer_signature'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annexure 2 - Consumer Agreement - <?= htmlspecialchars($customer['customer_code'] ?? 'SVPL') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 13.5px;
            color: #000000;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px 0;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .agreement-page {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            height: 297mm;
            margin: 0 auto 20px auto;
            padding: 24mm 22mm 18mm 22mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-after: always;
        }
        .agreement-page:last-child {
            page-break-after: auto;
        }
        .page-content {
            flex-grow: 1;
        }
        .page-footer {
            border-top: 1px solid #94a3b8;
            padding-top: 6px;
            font-size: 11px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            line-height: 1.3;
        }
        .esign-stamp {
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            border: 1px dashed #0284c7;
            background: #f0f9ff;
            padding: 3px 6px;
            border-radius: 4px;
        }
        .esign-stamp img {
            max-height: 36px;
            max-width: 120px;
            object-fit: contain;
        }
        .text-justify {
            text-align: justify;
            text-justify: inter-word;
        }
        ol, ul {
            padding-left: 20px;
            margin-bottom: 8px;
        }
        p {
            margin-bottom: 8px;
        }
        
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }
            .agreement-page {
                width: 100% !important;
                height: 100vh !important;
                min-height: 100vh !important;
                margin: 0 !important;
                padding: 20mm 20mm 15mm 20mm !important;
                box-shadow: none !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- ACTION BAR -->
    <div class="container no-print mb-3 text-center" style="max-width: 210mm;">
        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm border">
            <div>
                <h5 class="fw-bold mb-0 text-dark text-start" style="font-family: sans-serif;">PM Surya Ghar Model Draft Agreement (Annexure 2)</h5>
                <span class="text-secondary small" style="font-family: sans-serif;">Consumer: <?= htmlspecialchars($custName) ?> (<?= htmlspecialchars($customer['customer_code'] ?? '') ?>)</span>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold px-4" style="font-family: sans-serif;">
                    <i class="bi bi-printer-fill me-1"></i> Print / Save as PDF
                </button>
                <button onclick="window.history.back()" class="btn btn-outline-secondary btn-sm" style="font-family: sans-serif;">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PAGE 1: ANNEXURE 2 - TITLE & PARTIES -->
    <!-- ========================================================================= -->
    <div class="agreement-page">
        <div class="page-content">
            
            <div class="text-center mb-4 pt-3">
                <h5 class="fw-bold mb-3" style="font-size: 16px;">Annexure 2</h5>
                <h5 class="fw-bold text-uppercase px-3 mb-0" style="font-size: 14.5px; line-height: 1.45;">
                    Model Draft Agreement between Consumer & Vendor for installation of grid<br>connected rooftop solar (RTS) project under PM Surya Ghar: Muft Bijli<br>Yojana
                </h5>
            </div>

            <div class="text-justify my-4" style="font-size: 14px; line-height: 1.65;">
                This agreement is executed on <strong><?= $executionDay ?></strong>/<strong><?= $executionMonth ?></strong>/<strong><?= $executionYear ?></strong> for design, supply, installation, commissioning and 5-year comprehensive maintenance of RTS project/system along with warranty under PM Surya Ghar: Muft Bijli Yojana.
            </div>

            <div class="text-center fw-bold my-4" style="font-size: 14.5px;">Between</div>

            <div class="my-4" style="font-size: 14px; line-height: 1.85;">
                <div><strong><?= htmlspecialchars($custName) ?></strong> /O - <strong><?= htmlspecialchars($careOf) ?></strong></div>
                <div>AT - <strong><?= htmlspecialchars($atAddress) ?></strong> PO - <strong><?= htmlspecialchars($poAddress) ?></strong></div>
                <div>DIST - <strong><?= htmlspecialchars($distAddress) ?></strong> PINCODE - <strong><?= htmlspecialchars($pinAddress) ?></strong> ODISHA</div>
                <div>(Herein after called as 'The eligible consumer') Existing consumer number - <strong><?= htmlspecialchars($consumerNo) ?></strong></div>
                <div>& Notification number: <strong><?= htmlspecialchars($notificationNo) ?></strong></div>
            </div>

            <div class="text-center fw-bold my-4" style="font-size: 14.5px;">AND</div>

            <div class="my-4 text-justify" style="font-size: 14px; line-height: 1.65;">
                COMPANY - <strong><?= htmlspecialchars(strtoupper($companyName)) ?></strong>, <?= htmlspecialchars($companyAddress) ?> (Hereinafter referred to as Second Party i.e. Vendor/Contractor/System Integrator).
            </div>

        </div>

        <!-- Footer Page 1 with Customer E-Sign Stamp -->
        <div class="page-footer">
            <div>
                Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>
                Central Financial Assistance to Residential
            </div>
            <div class="text-end">
                <?php if (!empty($customerSignature)): ?>
                    <div class="esign-stamp mb-1">
                        <img src="<?= htmlspecialchars($customerSignature) ?>" alt="Consumer Signature">
                        <span style="font-size: 8px; color: #0284c7; font-weight: bold; font-family: sans-serif;">CONSUMER E-SIGN</span>
                    </div>
                <?php endif; ?>
                <div class="fw-bold" style="font-size: 12px;">1</div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PAGE 2: UNDERTAKINGS FIRST & SECOND PARTY -->
    <!-- ========================================================================= -->
    <div class="agreement-page">
        <div class="page-content">
            
            <div class="mb-3">
                <div class="fw-bold mb-1">Whereas</div>
                <div class="text-justify">
                    First Party wishes to install a Grid Connected Rooftop Solar Plant on the rooftop of the residential building of the consumer under PM Surya Ghar: Muft Bijli Yojana;
                </div>
            </div>

            <div class="mb-3">
                <div class="fw-bold mb-1">And whereas</div>
                <div class="text-justify">
                    Second Party has verified availability of appropriate roof and found it feasible to install a Grid Connected Rooftop Solar Plant and that the Second Party is willing to design, supply, install, test, commission and carry out operation and maintenance of the Rooftop Solar Plant for 5 year period. On this day, the First Party and Second Party agree to the following:
                </div>
            </div>

            <div class="mb-3">
                <div class="fw-bold mb-1">The First Party hereby undertakes to perform the following activities:</div>
                <div class="text-justify" style="line-height: 1.5;">
                    <p class="mb-2">1. Submission of online application on the National Portal for installation of RTS project/system, submission of application for Net-Metering and system inspection and upload of the relevant documents on the National Portal of the scheme.</p>
                    <p class="mb-2">2. Provide secure storage for the materials of the RTS plant delivered at the premises till handover of the system.</p>
                    <p class="mb-2">3. Provide access to the roof top during installation of the plant, operation and maintenance, testing of the plant and equipment and for meter reading from solar meter, inverter etc.</p>
                    <p class="mb-2">4. Provide electricity during plant installation and water for cleaning of the panels.</p>
                    <p class="mb-2">5. Report any malfunctioning of the plant to the vendor during warranty period.</p>
                    <p class="mb-2">6. Pay the amount as per the payment schedule as mutually agreed with the vendor, including any additional amount to the second party for any additional work/customization required depending upon the building condition.</p>
                </div>
            </div>

            <div class="mb-2">
                <div class="fw-bold mb-1">The Second Party hereby undertakes to perform the following activities:</div>
                <div class="text-justify" style="line-height: 1.5;">
                    <p class="mb-2">1. The Vendor must follow all the standards and safety guidelines prescribed under state regulations and technical standards prescribed by MNRE for RTS projects, failing which the vendor is liable for blacklisting from participation in the govt. project/scheme and other penal actions in accordance with the law. The responsibility of supply, installation and commissioning of the rooftop solar project/system in complete compliance with MNRE scheme guidelines lies with the vendor.</p>
                    <p class="mb-2">2. Site Survey: Site visit, survey and development of detailed project report for installation of RTS system. This also includes feasibility study of roof, strength of roof and shadow free area. If any additional work or customization is involved for the plant installation as per site condition and requirement of the consumer building, the vendor shall prepare an estimate and can raise separate invoice including GST in addition to the amount towards standard plant cost. The consumer shall pay the amount for such additional work directly to the vendor.</p>
                    <p class="mb-2">3. Design and Engineering: Design of plant along with drawings and selection of components as per standard provided by the DISCOM/SERC/MNRE for best performance and safety of the plant.</p>
                    <p class="mb-1">4. Module and Inverter: The solar modules, including the solar cells, should be ...</p>
                </div>
            </div>

        </div>

        <!-- Footer Page 2 with Customer E-Sign Stamp -->
        <div class="page-footer">
            <div>
                Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>
                Central Financial Assistance to Residential
            </div>
            <div class="text-end">
                <?php if (!empty($customerSignature)): ?>
                    <div class="esign-stamp mb-1">
                        <img src="<?= htmlspecialchars($customerSignature) ?>" alt="Consumer Signature">
                        <span style="font-size: 8px; color: #0284c7; font-weight: bold; font-family: sans-serif;">CONSUMER E-SIGN</span>
                    </div>
                <?php endif; ?>
                <div class="fw-bold" style="font-size: 12px;">2</div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PAGE 3: TECHNICAL STANDARDS & SPECIFICATIONS (POINTS 5 TO 17) -->
    <!-- ========================================================================= -->
    <div class="agreement-page">
        <div class="page-content text-justify" style="font-size: 13px; line-height: 1.48;">
            
            <p class="mb-2">manufactured in India. Both the solar modules and inverters shall conform to the relevant standards and specifications prescribed by MNRE. Any other requirements, viz. star labelling (solar modules), quality control orders and standards and labelling (inverters) etc. shall also be complied.</p>

            <p class="mb-2">5. Procurement and Supply: Procurement of complete system as per BIS/IS/IEC standard (whatever applicable) and safety guidelines for installation of rooftop solar plants. The supplied materials should comply with all MNRE standards for release of subsidy.</p>

            <p class="mb-2">6. Installation and Civil work: Complete civil work, structure work and electrical work (including drawings) following all the safety and relevant BIS standards.</p>

            <p class="mb-2">7. Documentation (Technical Catalogues/Warranty Certificates/BIS Certificates/other test reports etc.): All such documents shall be provided to the consumer for online uploading and submission of technical specifications, IEC/BIS report, Serial Numbers, Warranty card of Solar Panel and Inverter, Layout and Electrical SLD, Structure Design and Drawing, Cable and other detailed documents.</p>

            <p class="mb-2">8. Project Completion Report (PCR): Assisting the consumer in filling and uploading of signed documents (Consumer and Vendor) on the National Portal.</p>

            <p class="mb-2">9. Warranty: System warranty certificates should be provided to the consumer. The complete system should be warranted for 5 years from the date of commissioning by DISCOM. Individual component warranty documents provided by the manufacturer shall be provided to the consumer and all possible assistance should be extended to the consumer for claiming the warranty from the manufacturer.</p>

            <p class="mb-2">10. Net Meter and Grid Connectivity: Net meter supply/procurement, testing and approvals shall be in the scope of vendor. Grid connection of the plant shall be in the scope of the vendor.</p>

            <p class="mb-2">11. Testing and Commissioning: The vendor shall be present at the time of testing and commissioning by the DISCOM.</p>

            <p class="mb-2">12. Operation and Maintenance: Five (5) years comprehensive operation and maintenance including overhauling, wear and tear and regular checking of healthiness of system at proper interval shall be in the scope of the vendor. The vendor shall also educate the consumer on best practices for cleaning of the modules and system maintenance.</p>

            <p class="mb-2">13. Insurance: Any insurance cost pertaining to material transfer/storage before commissioning of the system shall be in the scope of the vendor.</p>

            <p class="mb-2">14. Applicable Standard: The system must meet the technical standards and specifications notified by MNRE. The vendor is solely responsible to supply component and service which meets the technical standards and specification prescribed by MNRE and state DISCOMs.</p>

            <p class="mb-2">15. Project/System Cost and Payment Terms: The cost of the plant and payment schedule should be mutually discussed and decided between the vendor and consumer. The consumer may opt for milestone-based payment to the vendor and the same shall be included in the agreement.</p>

            <p class="mb-2">16. Dispute: In case of any dispute between consumer and vendor (in supply/installation/maintenance of system or payment terms), both parties must settle the same mutually or as per law. MNRE/DISCOM shall not be liable for, and would not be a party to any dispute arising between vendor and consumer.</p>

            <p class="mb-0">17. Subsidy/Project Related Documents: Vendor must provide all the documents to consumer and help in uploading the same on the National Portal for smooth release of subsidy.</p>

        </div>

        <!-- Footer Page 3 with Customer E-Sign Stamp -->
        <div class="page-footer">
            <div>
                Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>
                Central Financial Assistance to Residential
            </div>
            <div class="text-end">
                <?php if (!empty($customerSignature)): ?>
                    <div class="esign-stamp mb-1">
                        <img src="<?= htmlspecialchars($customerSignature) ?>" alt="Consumer Signature">
                        <span style="font-size: 8px; color: #0284c7; font-weight: bold; font-family: sans-serif;">CONSUMER E-SIGN</span>
                    </div>
                <?php endif; ?>
                <div class="fw-bold" style="font-size: 12px;">3</div>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- PAGE 4: PERFORMANCE, PAYMENT TERMS & SIGNATURE BLOCKS -->
    <!-- ========================================================================= -->
    <div class="agreement-page">
        <div class="page-content">
            
            <p class="text-justify mb-3" style="font-size: 13.5px;">
                consumer and help in uploading the same on the National Portal for smooth release of subsidy.
            </p>

            <p class="text-justify mb-4" style="font-size: 13.5px; line-height: 1.55;">
                <strong>18. Performance of Plant:</strong> The Performance Ratio (PR) of plant must be 75% at the time of commissioning of the project by DISCOM or its authorized agency. Vendor must provide (returnable basis) radiation sensor with valid calibration certificate of any NABL/International Laboratory at the time of commissioning/testing of the plant. Vendor must maintain the PR of the plant till warranty of project i.e. 5 years from the date of commissioning.
            </p>

            <div class="mb-4" style="font-size: 13.5px;">
                <p class="fw-bold mb-2">19. Mutually Agreed Terms of Payment:</p>
                <p class="mb-2 ms-3">a. After Supply of materials – 90% of Project Cost</p>
                <p class="mb-0 ms-3">b. After Installation and Commissioning of Project – 10% of Final Value</p>
            </div>

            <!-- Signatures Table -->
            <div class="row g-0 border border-dark mb-4 mt-4">
                
                <!-- First Party (Customer) -->
                <div class="col-6 p-3 border-end border-dark d-flex flex-column justify-content-between" style="min-height: 220px; font-size: 13px; line-height: 1.55;">
                    <div>
                        <div class="fw-bold mb-2">First Party</div>
                        <div>Name: <strong><?= htmlspecialchars($custName) ?></strong></div>
                        <div class="mt-1">Address: <strong><?= htmlspecialchars($atAddress) ?>, <?= htmlspecialchars($poAddress) ?>, <?= htmlspecialchars($distAddress) ?></strong></div>
                        <div class="mt-1">PIN - <strong><?= htmlspecialchars($pinAddress) ?> (ODISHA)</strong></div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span>Sign:</span>
                            <?php if (!empty($customerSignature)): ?>
                                <img src="<?= htmlspecialchars($customerSignature) ?>" alt="Customer Signature" style="max-height: 48px; max-width: 150px; object-fit: contain;">
                            <?php else: ?>
                                <span>____________________________</span>
                            <?php endif; ?>
                        </div>
                        <div>Date: <strong><?= $agreementDate ?></strong></div>
                    </div>
                </div>

                <!-- Second Party (Vendor / Dhwajja Solar) -->
                <div class="col-6 p-3 d-flex flex-column justify-content-between" style="min-height: 220px; font-size: 13px; line-height: 1.55;">
                    <div>
                        <div class="fw-bold mb-2">Second Party</div>
                        <div>Name: <strong><?= htmlspecialchars(strtoupper($companyName)) ?></strong></div>
                        <div class="mt-1">MIG-84, POKHARIPUT, BDA COLONY</div>
                        <div>PHASE-1-POKHARIPUT,</div>
                        <div>BHUBANESWAR, KHORDHA</div>
                        <div class="mt-1">PIN - 751020 (ODISHA)</div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span>Sign:</span>
                            <img src="<?= htmlspecialchars($companySignature) ?>" alt="Authorised Signatory" style="max-height: 48px; max-width: 150px; object-fit: contain;">
                        </div>
                        <div>Date: <strong><?= $agreementDate ?></strong></div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Footer Page 4 with Customer E-Sign Stamp -->
        <div class="page-footer">
            <div>
                Guidelines for PM-Surya Ghar: Muft Bijli Yojana<br>
                Central Financial Assistance to Residential
            </div>
            <div class="text-end">
                <?php if (!empty($customerSignature)): ?>
                    <div class="esign-stamp mb-1">
                        <img src="<?= htmlspecialchars($customerSignature) ?>" alt="Consumer Signature">
                        <span style="font-size: 8px; color: #0284c7; font-weight: bold; font-family: sans-serif;">CONSUMER E-SIGN</span>
                    </div>
                <?php endif; ?>
                <div class="fw-bold" style="font-size: 12px;">4</div>
            </div>
        </div>
    </div>

</body>
</html>

