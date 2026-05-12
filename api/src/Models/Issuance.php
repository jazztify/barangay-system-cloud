<?php
namespace App\Models;

use App\Database\DB;

class Issuance {
    public static function getAll($search = '') {
        $pdo = DB::connect();
        $sql = "SELECT i.*, r.last_name || ', ' || r.first_name as resident_name, u.full_name as issued_by_name
                FROM issuances i
                LEFT JOIN residents r ON i.resident_id = r.resident_id
                LEFT JOIN users u ON i.issued_by = u.user_id
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (LOWER(i.control_number) LIKE LOWER(?) OR LOWER(r.last_name) LIKE LOWER(?) OR LOWER(i.document_type) LIKE LOWER(?))";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY i.issued_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO issuances (resident_id, issued_by, document_type, control_number, or_number, purpose, additional_data, verification_token, valid_until) 
                                VALUES (?,?,?,?,?,?,?,?,?) RETURNING issuance_id");
        $stmt->execute([
            $data['resident_id'],
            $data['issued_by'],
            $data['document_type'],
            $data['control_number'],
            $data['or_number'] ?? null,
            $data['purpose'] ?? null,
            $data['additional_data'] ?? null,
            $data['verification_token'] ?? null,
            $data['valid_until'] ?? null
        ]);
        return $stmt->fetch()['issuance_id'];
    }

    public static function generateControlNumber($docType) {
        $pdo = DB::connect();
        $year = date('Y');
        $prefix = match($docType) {
            'Barangay Clearance' => 'BC',
            'Certificate of Residency' => 'CR',
            'Certificate of Indigency' => 'CI',
            'First-Time Job Seeker (RA 11261)' => 'FJ',
            'Certificate of Good Moral Character' => 'GM',
            'Business Clearance' => 'BZ',
            default => 'DC'
        };
        
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM issuances WHERE document_type = ? AND EXTRACT(YEAR FROM issued_at) = ?");
        $stmt->execute([$docType, $year]);
        $count = (int)$stmt->fetchColumn() + 1;
        
        return $prefix . '-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public static function hasAvailedRA11261($residentId) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM issuances WHERE resident_id = ? AND document_type = 'First-Time Job Seeker (RA 11261)'");
        $stmt->execute([$residentId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public static function countThisMonth() {
        $pdo = DB::connect();
        return $pdo->query("SELECT COUNT(*) FROM issuances WHERE EXTRACT(MONTH FROM issued_at) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR FROM issued_at) = EXTRACT(YEAR FROM CURRENT_DATE)")->fetchColumn();
    }

    public static function getByResident($residentId) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT i.*, u.full_name as issued_by_name FROM issuances i LEFT JOIN users u ON i.issued_by = u.user_id WHERE i.resident_id = ? ORDER BY i.issued_at DESC");
        $stmt->execute([$residentId]);
        return $stmt->fetchAll();
    }
}
