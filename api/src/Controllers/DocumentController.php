<?php
namespace App\Controllers;

use App\Models\Resident;
use App\Models\Blotter;
use App\Models\Issuance;
use App\Models\AuditTrail;

class DocumentController {
    public function index() {
        global $appConfig;
        $search = $_GET['search'] ?? '';
        $issuances = Issuance::getAll($search);
        require __DIR__ . '/../../templates/documents/index.php';
    }

    public function request() {
        global $appConfig;
        $error = null;
        $warning = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $residentId = (int)$_POST['resident_id'];
            $docType = $_POST['document_type'];
            $purpose = $_POST['purpose'] ?? '';
            $orNumber = $_POST['or_number'] ?? '';
            $additionalData = [];

            $resident = Resident::getById($residentId);
            if (!$resident) {
                $error = "Resident not found.";
            } else {
                // Clearance check for Barangay Clearance
                if ($docType === 'Barangay Clearance') {
                    $unresolvedCount = Blotter::checkClearance($residentId);
                    if ($unresolvedCount > 0) {
                        $error = "⚠️ CLEARANCE DENIED: This resident has {$unresolvedCount} unresolved blotter case(s) as RESPONDENT. Resolve pending cases before issuing a clearance.";
                    }
                }

                // RA 11261 one-time availment check
                if ($docType === 'First-Time Job Seeker (RA 11261)') {
                    if (Issuance::hasAvailedRA11261($residentId)) {
                        $error = "⚠️ DENIED: This resident has already availed of the First-Time Job Seeker benefit under RA 11261. One-time availment only.";
                    }
                    $additionalData['oath_date'] = $_POST['oath_date'] ?? date('Y-m-d');
                }

                // Certificate of Indigency extra fields
                if ($docType === 'Certificate of Indigency') {
                    $additionalData['annual_income'] = $_POST['annual_income'] ?? '';
                    $additionalData['parents_name'] = $_POST['parents_name'] ?? '';
                    $additionalData['beneficiary_name'] = $_POST['beneficiary_name'] ?? '';
                }

                if (!$error) {
                    $controlNumber = Issuance::generateControlNumber($docType);
                    $token = bin2hex(random_bytes(16));
                    $validUntil = null;
                    if ($docType === 'First-Time Job Seeker (RA 11261)') {
                        $validUntil = date('Y-m-d H:i:s', strtotime('+1 year'));
                    }

                    $issuanceId = Issuance::create([
                        'resident_id' => $residentId,
                        'issued_by' => $_SESSION['user_id'],
                        'document_type' => $docType,
                        'control_number' => $controlNumber,
                        'or_number' => $orNumber,
                        'purpose' => $purpose,
                        'additional_data' => json_encode($additionalData),
                        'verification_token' => $token,
                        'valid_until' => $validUntil
                    ]);

                    AuditTrail::log($_SESSION['user_id'], "Issued $docType", 'issuance', $issuanceId, "To: {$resident['last_name']}, {$resident['first_name']} | Control#: $controlNumber");

                    header("Location: {$appConfig['base_url']}/documents/print/$issuanceId");
                    exit;
                }
            }
        }

        require __DIR__ . '/../../templates/documents/request.php';
    }

    public function print($issuanceId) {
        global $appConfig;
        $pdo = \App\Database\DB::connect();
        $stmt = $pdo->prepare("SELECT i.*, r.*, h.purok_sitio, h.address as household_address,
                               u.full_name as issuer_name
                               FROM issuances i
                               JOIN residents r ON i.resident_id = r.resident_id
                               LEFT JOIN households h ON r.household_id = h.household_id
                               LEFT JOIN users u ON i.issued_by = u.user_id
                               WHERE i.issuance_id = ?");
        $stmt->execute([$issuanceId]);
        $doc = $stmt->fetch();
        if (!$doc) { http_response_code(404); echo "Document not found"; return; }

        $additionalData = json_decode($doc['additional_data'] ?? '{}', true) ?: [];
        $templateFile = match($doc['document_type']) {
            'Barangay Clearance' => 'clearance.php',
            'Certificate of Residency' => 'residency.php',
            'Certificate of Indigency' => 'indigency.php',
            'First-Time Job Seeker (RA 11261)' => 'jobseeker.php',
            default => 'clearance.php'
        };

        require __DIR__ . '/../../templates/documents/print/' . $templateFile;
    }
}
