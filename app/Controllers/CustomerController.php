<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Customer Controller - Customer Portal, Live Solar Progress, Quotation, Document Uploads, Dispatch Receipt
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\AuthService;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\Document;
use App\Models\Quotation;
use App\Models\PackageDispatch;
use App\Models\AuditLog;

class CustomerController
{
    public function dashboard(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);

        if (!$customer) {
            Response::redirect('/login');
            return;
        }

        $lead = Lead::findByCustomerCode($customer['customer_code']);
        $quotation = $lead ? Quotation::findByLeadId((int) $lead['id']) : null;
        $documents = Document::getByCustomerId((int) $customer['id']);
        
        // Fetch latest equipment dispatch for this customer
        $dispatch = PackageDispatch::findByCustomerId((int) $customer['id']);

        Response::view('customer/dashboard', [
            'pageTitle' => 'My Solar Portal — SVPL Customer',
            'customer' => $customer,
            'lead' => $lead,
            'quotation' => $quotation,
            'documents' => $documents,
            'dispatch' => $dispatch,
        ]);
    }

    public function acknowledgeDispatch(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);

        if (!$customer || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::redirect('/customer/dashboard');
            return;
        }

        $dispatchId = (int)($_POST['dispatch_id'] ?? 0);
        $notes = trim($_POST['notes'] ?? 'Received and verified by customer');

        if ($dispatchId <= 0) {
            $_SESSION['error_msg'] = "Invalid dispatch selected.";
            Response::redirect('/customer/dashboard');
            return;
        }

        $dispatch = PackageDispatch::findById($dispatchId);
        if (!$dispatch || (int)$dispatch['customer_id'] !== (int)$customer['id']) {
            $_SESSION['error_msg'] = "Unauthorized or dispatch record not found.";
            Response::redirect('/customer/dashboard');
            return;
        }

        // Acknowledge receipt
        PackageDispatch::acknowledgeReceipt($dispatchId, $notes);

        // Advance Lead stage if at Stage 6 (INSTRUMENT_DISPATCHED)
        $lead = Lead::findByCustomerCode($customer['customer_code']);
        if ($lead && in_array($lead['lead_stage'] ?? '', ['INSTRUMENT_DISPATCHED', 'FEASIBILITY_APPROVED', 'LOAN_APPROVED'])) {
            Lead::updateStage(
                (int)$lead['id'],
                'INSTALLATION_COMMENCED',
                7,
                'Customer acknowledged delivery of solar equipment. Ready for rooftop installation.'
            );
        }

        AuditLog::log(
            $user['id'],
            'CUSTOMER_ACKNOWLEDGE_RECEIPT',
            'package_dispatches',
            $dispatchId,
            "Customer {$customer['customer_code']} acknowledged receipt of solar equipment (LR: {$dispatch['tracking_number']})"
        );

        $_SESSION['success_msg'] = "Thank you! Equipment receipt successfully confirmed. Your assigned solar engineer has been notified to commence rooftop installation!";
        Response::redirect('/customer/dashboard');
    }

    public function quotation(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);
        $lead = Lead::findByCustomerCode($customer['customer_code']);
        $quotation = $lead ? Quotation::findByLeadId((int) $lead['id']) : null;

        Response::view('customer/quotation', [
            'pageTitle' => 'PM Surya Ghar Solar Quotation — SVPL',
            'customer' => $customer,
            'lead' => $lead,
            'quotation' => $quotation,
        ]);
    }

    public function agreement(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);
        if (!$customer) {
            Response::redirect('/login');
            return;
        }

        $lead = Lead::findByCustomerCode($customer['customer_code']);

        Response::view('printable/consumer_agreement', [
            'pageTitle' => 'PM Surya Ghar Consumer Agreement — ' . $customer['customer_code'],
            'customer' => $customer,
            'lead' => $lead,
        ]);
    }

    public function documents(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);
        $lead = Lead::findByCustomerCode($customer['customer_code']);
        $documents = Document::getByCustomerId((int) $customer['id']);

        Response::view('customer/documents', [
            'pageTitle' => 'My Uploaded Documents — SVPL',
            'customer' => $customer,
            'lead' => $lead,
            'documents' => $documents,
        ]);
    }

    public function uploadDocument(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);
        $docType = $_POST['document_type'] ?? 'OTHER';

        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            Response::redirect('/customer/documents?error=upload_failed');
            return;
        }

        $uploadDir = dirname(__DIR__, 2) . '/storage/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $fileName = 'DOC_' . $customer['id'] . '_' . $docType . '_' . time() . '.' . $ext;
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            \App\Helpers\ImageCompressor::compressIfNeeded($targetFile);
            $lead = Lead::findByCustomerCode($customer['customer_code']);
            Document::create([
                'entity_type' => 'CUSTOMER',
                'entity_id' => $customer['id'],
                'lead_id' => $lead['id'] ?? null,
                'document_type' => $docType,
                'document_title' => ucwords(str_replace('_', ' ', $docType)),
                'file_path' => 'storage/documents/' . $fileName,
                'file_size' => $_FILES['file']['size'],
                'mime_type' => $_FILES['file']['type'],
                'status' => 'Uploaded',
            ]);

            Response::redirect('/customer/documents?success=1');
        } else {
            Response::redirect('/customer/documents?error=save_failed');
        }
    }

    public function showBecomeAdvisor(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);

        if (!$customer) {
            Response::redirect('/login');
            return;
        }

        // If already converted, redirect to advisor dashboard
        if (!empty($customer['converted_to_advisor']) && (int)$customer['converted_to_advisor'] === 1 && !empty($customer['converted_advisor_id'])) {
            Response::redirect('/advisor/dashboard');
            return;
        }

        Response::view('customer/upgrade_advisor', [
            'pageTitle' => 'Become a Certified Solar Advisor — ' . company_name(),
            'customer' => $customer,
            'user' => $user,
        ]);
    }

    public function convertToAdvisor(): void
    {
        $user = AuthService::user();
        $customer = Customer::findByUserId((int) $user['id']);

        if (!$customer || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::redirect('/customer/dashboard');
            return;
        }

        // If customer is already converted to active advisor
        if (!empty($customer['converted_to_advisor']) && (int)$customer['converted_to_advisor'] === 1 && !empty($customer['converted_advisor_id'])) {
            $_SESSION['error_msg'] = "You have already converted to an Advisor account.";
            Response::redirect('/customer/dashboard');
            return;
        }

        $post = $_POST;
        $transactionRef = trim($post['transaction_ref'] ?? '');
        $paymentMethod = trim($post['payment_method'] ?? 'UPI');
        $paymentDate = trim($post['payment_date'] ?? date('Y-m-d'));
        $bankName = trim($post['bank_name'] ?? '');
        $accountHolder = trim($post['account_holder'] ?? ($customer['first_name'] . ' ' . $customer['last_name']));
        $accountNumber = trim($post['account_number'] ?? '');
        $ifscCode = trim($post['ifsc_code'] ?? '');
        $aadhaarNumber = trim($post['aadhaar_number'] ?? ($customer['aadhaar_number'] ?? ''));
        $panNumber = trim($post['pan_number'] ?? '');

        if (empty($transactionRef)) {
            $_SESSION['error_msg'] = "Please provide the ₹" . number_format(advisor_joining_fee()) . " Joining Fee Bank / UPI Transaction Reference (UTR) number.";
            Response::redirect('/customer/become-advisor');
            return;
        }

        // Photo Upload / Live Webcam Capture
        $photoUrl = $customer['photo_url'] ?? null;
        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/advisors/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (!empty($post['advisor_photo_base64']) && preg_match('#^data:image/(\w+);base64,#i', $post['advisor_photo_base64'], $typeMatch)) {
            $imageType = strtolower($typeMatch[1]);
            $base64Data = substr($post['advisor_photo_base64'], strpos($post['advisor_photo_base64'], ',') + 1);
            $decodedData = base64_decode($base64Data);
            if ($decodedData !== false) {
                $photoFileName = 'adv_photo_' . time() . '_' . rand(1000, 9999) . '.' . ($imageType === 'png' ? 'png' : 'jpg');
                if (file_put_contents($uploadDir . $photoFileName, $decodedData)) {
                    $photoUrl = 'uploads/advisors/' . $photoFileName;
                }
            }
        } elseif (!empty($_FILES['advisor_photo']['name']) && $_FILES['advisor_photo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['advisor_photo']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $photoFileName = 'adv_photo_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                if (move_uploaded_file($_FILES['advisor_photo']['tmp_name'], $uploadDir . $photoFileName)) {
                    $photoUrl = 'uploads/advisors/' . $photoFileName;
                }
            }
        }

        \App\Helpers\Database::beginTransaction();
        try {
            $joiningFee = advisor_joining_fee();

            // 1. Create or Update Advisor profile (Pending approval until admin verifies UTR)
            $existingAdv = \App\Models\Advisor::findByUserId((int) $user['id']);
            if ($existingAdv) {
                $advId = (int) $existingAdv['id'];
                $advCode = $existingAdv['advisor_code'];
                \App\Models\Advisor::update($advId, [
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'father_spouse_name' => $customer['father_spouse_name'] ?? null,
                    'dob' => $customer['dob'] ?? null,
                    'gender' => $customer['gender'] ?? 'Male',
                    'photo_url' => $photoUrl ?: ($existingAdv['photo_url'] ?? null),
                    'blood_group' => $post['blood_group'] ?? ($existingAdv['blood_group'] ?? 'O+ve'),
                    'mobile' => $customer['mobile'],
                    'alt_mobile' => $customer['alt_mobile'] ?? null,
                    'email' => $customer['email'] ?? null,
                    'state' => $customer['state'] ?? 'Odisha',
                    'district' => $customer['district'],
                    'block' => $customer['block'],
                    'gram_panchayat' => $customer['gram_panchayat'] ?? '',
                    'village' => $customer['village'] ?? '',
                    'pincode' => $customer['pincode'] ?? '751024',
                    'address_line' => $customer['address_line'] ?? ($customer['house_address'] ?? ''),
                    'aadhaar_number' => $aadhaarNumber ?: ($existingAdv['aadhaar_number'] ?? null),
                    'pan_number' => $panNumber ?: ($existingAdv['pan_number'] ?? null),
                    'bank_name' => $bankName ?: ($customer['bank_name'] ?? ($existingAdv['bank_name'] ?? null)),
                    'bank_branch' => $post['bank_branch'] ?? ($customer['bank_branch'] ?? ($existingAdv['bank_branch'] ?? null)),
                    'account_holder' => $accountHolder,
                    'account_number' => $accountNumber ?: ($customer['account_number'] ?? ($existingAdv['account_number'] ?? null)),
                    'ifsc_code' => $ifscCode ?: ($customer['ifsc_code'] ?? ($existingAdv['ifsc_code'] ?? null)),
                    'status' => 'PENDING_APPROVAL',
                    'joining_fee' => $joiningFee,
                    'joining_fee_paid' => 0,
                    'sponsor_id' => $customer['advisor_id'] ?? ($existingAdv['sponsor_id'] ?? null),
                ]);
            } else {
                $advCode = \App\Models\Advisor::generateAdvisorCode();
                $newRefCode = \App\Models\Advisor::generateReferralCode();
                $advId = \App\Models\Advisor::create([
                    'user_id' => $user['id'],
                    'advisor_code' => $advCode,
                    'referral_code' => $newRefCode,
                    'sponsor_id' => $customer['advisor_id'] ?? null,
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'father_spouse_name' => $customer['father_spouse_name'] ?? null,
                    'dob' => $customer['dob'] ?? null,
                    'gender' => $customer['gender'] ?? 'Male',
                    'photo_url' => $photoUrl,
                    'blood_group' => $post['blood_group'] ?? 'O+ve',
                    'mobile' => $customer['mobile'],
                    'alt_mobile' => $customer['alt_mobile'] ?? null,
                    'email' => $customer['email'] ?? null,
                    'state' => $customer['state'] ?? 'Odisha',
                    'district' => $customer['district'],
                    'block' => $customer['block'],
                    'gram_panchayat' => $customer['gram_panchayat'] ?? '',
                    'village' => $customer['village'] ?? '',
                    'pincode' => $customer['pincode'] ?? '751024',
                    'address_line' => $customer['address_line'] ?? ($customer['house_address'] ?? ''),
                    'aadhaar_number' => $aadhaarNumber ?: null,
                    'pan_number' => $panNumber ?: null,
                    'bank_name' => $bankName ?: ($customer['bank_name'] ?? null),
                    'bank_branch' => $post['bank_branch'] ?? ($customer['bank_branch'] ?? null),
                    'account_holder' => $accountHolder,
                    'account_number' => $accountNumber ?: ($customer['account_number'] ?? null),
                    'ifsc_code' => $ifscCode ?: ($customer['ifsc_code'] ?? null),
                    'status' => 'PENDING_APPROVAL',
                    'joining_fee' => $joiningFee,
                    'joining_fee_paid' => 0,
                ]);
            }

            // 2. Create Payment record for Admin Verification
            \App\Models\Payment::create([
                'entity_type' => 'ADVISOR',
                'entity_id' => $advId,
                'purpose' => 'JOINING_FEE',
                'amount' => $joiningFee,
                'payment_method' => $paymentMethod,
                'transaction_ref' => $transactionRef,
                'status' => 'PENDING',
                'payment_date' => $paymentDate,
            ]);

            // 3. 9-Level MLM Genealogy Closure Linkage (clean any existing linkage first)
            \App\Helpers\Database::execute("DELETE FROM advisor_genealogy WHERE descendant_id = ?", [$advId]);
            \App\Models\Genealogy::addAdvisor($advId, $customer['advisor_id'] ?? null);

            // 4. Initialize Wallet if not exists
            $existingWallet = \App\Models\Wallet::getByUserId((int) $user['id']);
            if (!$existingWallet) {
                \App\Models\Wallet::create([
                    'user_id' => $user['id'],
                    'balance' => 0.00,
                    'total_earned' => 0.00,
                    'total_withdrawn' => 0.00,
                    'pending_clearance' => 0.00,
                ]);
            }

            // 5. Update Customer Record with pending advisor ID
            \App\Helpers\Database::execute(
                "UPDATE customers SET converted_to_advisor = 2, converted_advisor_id = ?, updated_at = NOW() WHERE id = ?",
                [$advId, $customer['id']]
            );

            // Note: User role remains CUSTOMER until Admin confirms payment in /admin/payments
            AuditLog::log(
                $user['id'],
                'CUSTOMER_CONVERT_ADVISOR_SUBMITTED',
                'advisors',
                $advId,
                "Customer {$customer['customer_code']} submitted Advisor application {$advCode} with UTR {$transactionRef} (₹{$joiningFee})"
            );

            \App\Helpers\Database::commit();

            $_SESSION['success_msg'] = "Your request to become a Solar Advisor (Application Ref: {$advCode}, UTR: {$transactionRef}) has been submitted! Your ₹" . number_format($joiningFee) . " onboarding payment is currently under Accounts verification. You can continue tracking your rooftop solar installation here in the meantime.";
            Response::redirect('/customer/dashboard');
        } catch (\Throwable $t) {
            \App\Helpers\Database::rollBack();
            $_SESSION['error_msg'] = "Failed to submit advisor application: " . $t->getMessage();
            Response::redirect('/customer/become-advisor');
        }
    }
}
