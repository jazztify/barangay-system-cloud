<?php
namespace App\Models;

use App\Database\DB;

class Resident {
    public static function getAll($search = '', $purok = '', $sector = '') {
        $pdo = DB::connect();
        $sql = "SELECT r.*, h.household_no, h.purok_sitio 
                FROM residents r 
                LEFT JOIN households h ON r.household_id = h.household_id 
                WHERE r.is_alive = TRUE";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (LOWER(r.last_name) LIKE LOWER(?) OR LOWER(r.first_name) LIKE LOWER(?) OR LOWER(r.middle_name) LIKE LOWER(?))";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        if (!empty($purok)) {
            $sql .= " AND h.purok_sitio = ?";
            $params[] = $purok;
        }
        if ($sector === 'pwd') $sql .= " AND r.pwd_flag = TRUE";
        if ($sector === 'senior') $sql .= " AND r.senior_citizen_flag = TRUE";
        if ($sector === 'solo_parent') $sql .= " AND r.solo_parent_flag = TRUE";
        if ($sector === 'voter') $sql .= " AND r.voter_status = TRUE";

        $sql .= " ORDER BY r.last_name, r.first_name";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT r.*, h.household_no, h.purok_sitio, h.address as household_address
                               FROM residents r 
                               LEFT JOIN households h ON r.household_id = h.household_id 
                               WHERE r.resident_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($data) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO residents (household_id, last_name, first_name, middle_name, suffix, date_of_birth, sex, civil_status, nationality, religion, occupation, educational_attainment, voter_status, philsys_card_no, contact_number, pwd_flag, senior_citizen_flag, solo_parent_flag) 
                                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) RETURNING resident_id");
        $stmt->execute([
            $data['household_id'] ?: null,
            strtoupper($data['last_name']),
            strtoupper($data['first_name']),
            strtoupper($data['middle_name'] ?? ''),
            $data['suffix'] ?? null,
            $data['date_of_birth'] ?? null,
            $data['sex'] ?? null,
            $data['civil_status'] ?? null,
            $data['nationality'] ?? 'Filipino',
            $data['religion'] ?? null,
            $data['occupation'] ?? null,
            $data['educational_attainment'] ?? null,
            isset($data['voter_status']) ? true : false,
            $data['philsys_card_no'] ?: null,
            $data['contact_number'] ?? null,
            isset($data['pwd_flag']) ? true : false,
            isset($data['senior_citizen_flag']) ? true : false,
            isset($data['solo_parent_flag']) ? true : false,
        ]);
        return $stmt->fetch()['resident_id'];
    }

    public static function update($id, $data) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE residents SET household_id=?, last_name=?, first_name=?, middle_name=?, suffix=?, date_of_birth=?, sex=?, civil_status=?, nationality=?, religion=?, occupation=?, educational_attainment=?, voter_status=?, philsys_card_no=?, contact_number=?, pwd_flag=?, senior_citizen_flag=?, solo_parent_flag=?, updated_at=CURRENT_TIMESTAMP WHERE resident_id=?");
        $stmt->execute([
            $data['household_id'] ?: null,
            strtoupper($data['last_name']),
            strtoupper($data['first_name']),
            strtoupper($data['middle_name'] ?? ''),
            $data['suffix'] ?? null,
            $data['date_of_birth'] ?? null,
            $data['sex'] ?? null,
            $data['civil_status'] ?? null,
            $data['nationality'] ?? 'Filipino',
            $data['religion'] ?? null,
            $data['occupation'] ?? null,
            $data['educational_attainment'] ?? null,
            isset($data['voter_status']) ? true : false,
            $data['philsys_card_no'] ?: null,
            $data['contact_number'] ?? null,
            isset($data['pwd_flag']) ? true : false,
            isset($data['senior_citizen_flag']) ? true : false,
            isset($data['solo_parent_flag']) ? true : false,
            $id
        ]);
    }

    public static function delete($id) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE residents SET is_alive = FALSE WHERE resident_id = ?");
        $stmt->execute([$id]);
    }

    public static function count() {
        $pdo = DB::connect();
        return $pdo->query("SELECT COUNT(*) FROM residents WHERE is_alive = TRUE")->fetchColumn();
    }

    public static function search($term) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT resident_id, last_name, first_name, middle_name, suffix, date_of_birth 
                               FROM residents WHERE is_alive = TRUE 
                               AND (LOWER(last_name) LIKE LOWER(?) OR LOWER(first_name) LIKE LOWER(?))
                               ORDER BY last_name, first_name LIMIT 20");
        $stmt->execute(["%$term%", "%$term%"]);
        return $stmt->fetchAll();
    }

    public static function getPuroks() {
        $pdo = DB::connect();
        return $pdo->query("SELECT DISTINCT purok_sitio FROM households ORDER BY purok_sitio")->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function getDemographics() {
        $pdo = DB::connect();
        $result = [];
        
        // Age bracket distribution
        $result['age_brackets'] = $pdo->query("
            SELECT 
                CASE 
                    WHEN EXTRACT(YEAR FROM AGE(date_of_birth)) BETWEEN 0 AND 14 THEN '0-14 yrs'
                    WHEN EXTRACT(YEAR FROM AGE(date_of_birth)) BETWEEN 15 AND 24 THEN '15-24 yrs'
                    WHEN EXTRACT(YEAR FROM AGE(date_of_birth)) BETWEEN 25 AND 54 THEN '25-54 yrs'
                    WHEN EXTRACT(YEAR FROM AGE(date_of_birth)) BETWEEN 55 AND 64 THEN '55-64 yrs'
                    ELSE '65+ yrs'
                END as bracket,
                COUNT(*) as count
            FROM residents WHERE is_alive = TRUE AND date_of_birth IS NOT NULL
            GROUP BY bracket ORDER BY bracket
        ")->fetchAll();

        // Sex distribution
        $result['sex'] = $pdo->query("SELECT sex, COUNT(*) as count FROM residents WHERE is_alive = TRUE GROUP BY sex")->fetchAll();

        // Sector counts
        $result['pwd'] = $pdo->query("SELECT COUNT(*) FROM residents WHERE is_alive = TRUE AND pwd_flag = TRUE")->fetchColumn();
        $result['senior'] = $pdo->query("SELECT COUNT(*) FROM residents WHERE is_alive = TRUE AND senior_citizen_flag = TRUE")->fetchColumn();
        $result['solo_parent'] = $pdo->query("SELECT COUNT(*) FROM residents WHERE is_alive = TRUE AND solo_parent_flag = TRUE")->fetchColumn();
        $result['voters'] = $pdo->query("SELECT COUNT(*) FROM residents WHERE is_alive = TRUE AND voter_status = TRUE")->fetchColumn();

        return $result;
    }
}
