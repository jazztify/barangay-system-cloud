<?php
namespace App\Models;

use App\Database\DB;

class Blotter {
    public static function getAll($status = '', $search = '') {
        $pdo = DB::connect();
        $sql = "SELECT b.*, 
                       c.last_name || ', ' || c.first_name as complainant_name,
                       r.last_name || ', ' || r.first_name as respondent_name
                FROM blotter_records b
                LEFT JOIN residents c ON b.complainant_id = c.resident_id
                LEFT JOIN residents r ON b.respondent_id = r.resident_id
                WHERE 1=1";
        $params = [];

        if (!empty($status)) {
            $sql .= " AND b.status = ?::blotter_status_enum";
            $params[] = $status;
        }
        if (!empty($search)) {
            $sql .= " AND (LOWER(b.incident_type) LIKE LOWER(?) OR LOWER(b.narrative) LIKE LOWER(?) 
                       OR LOWER(c.last_name) LIKE LOWER(?) OR LOWER(r.last_name) LIKE LOWER(?))";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY b.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT b.*, 
                       c.last_name || ', ' || c.first_name || COALESCE(' ' || c.middle_name, '') as complainant_name,
                       c.contact_number as complainant_contact,
                       r.last_name || ', ' || r.first_name || COALESCE(' ' || r.middle_name, '') as respondent_name,
                       r.contact_number as respondent_contact
                FROM blotter_records b
                LEFT JOIN residents c ON b.complainant_id = c.resident_id
                LEFT JOIN residents r ON b.respondent_id = r.resident_id
                WHERE b.blotter_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO blotter_records (complainant_id, respondent_id, incident_type, incident_date, narrative) 
                                VALUES (?,?,?,?,?) RETURNING blotter_id");
        $stmt->execute([
            $data['complainant_id'],
            $data['respondent_id'],
            $data['incident_type'],
            $data['incident_date'],
            $data['narrative']
        ]);
        return $stmt->fetch()['blotter_id'];
    }

    public static function updateStatus($id, $status, $notes = '', $date = null) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE blotter_records SET status=?::blotter_status_enum, resolution_notes=?, resolution_date=? WHERE blotter_id=?");
        $stmt->execute([$status, $notes, $date, $id]);
    }

    public static function countActive() {
        $pdo = DB::connect();
        return $pdo->query("SELECT COUNT(*) FROM blotter_records WHERE status = 'Unresolved'")->fetchColumn();
    }

    public static function checkClearance($residentId) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM blotter_records WHERE respondent_id = ? AND status = 'Unresolved'");
        $stmt->execute([$residentId]);
        return (int)$stmt->fetchColumn();
    }

    public static function getByResident($residentId) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT b.*, 
                       c.last_name || ', ' || c.first_name as complainant_name,
                       r.last_name || ', ' || r.first_name as respondent_name
                FROM blotter_records b
                LEFT JOIN residents c ON b.complainant_id = c.resident_id
                LEFT JOIN residents r ON b.respondent_id = r.resident_id
                WHERE b.complainant_id = ? OR b.respondent_id = ?
                ORDER BY b.created_at DESC");
        $stmt->execute([$residentId, $residentId]);
        return $stmt->fetchAll();
    }

    public static function getSummons($blotterId) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM blotter_summons WHERE blotter_id = ? ORDER BY summons_date DESC");
        $stmt->execute([$blotterId]);
        return $stmt->fetchAll();
    }

    public static function addSummons($blotterId, $data) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO blotter_summons (blotter_id, summons_date, summons_type, notes) VALUES (?,?,?,?)");
        $stmt->execute([$blotterId, $data['summons_date'], $data['summons_type'] ?? 'Patawag', $data['notes'] ?? '']);
    }
}
