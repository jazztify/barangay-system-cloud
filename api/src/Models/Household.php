<?php
namespace App\Models;

use App\Database\DB;

class Household {
    public static function getAll($search = '') {
        $pdo = DB::connect();
        $sql = "SELECT h.*, COUNT(r.resident_id) as member_count 
                FROM households h 
                LEFT JOIN residents r ON h.household_id = r.household_id AND r.is_alive = TRUE
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (LOWER(h.household_no) LIKE LOWER(?) OR LOWER(h.purok_sitio) LIKE LOWER(?) OR LOWER(h.address) LIKE LOWER(?))";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        $sql .= " GROUP BY h.household_id ORDER BY h.household_no";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getById($id) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM households WHERE household_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function getMembers($id) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT * FROM residents WHERE household_id = ? AND is_alive = TRUE ORDER BY last_name, first_name");
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }

    public static function create($data) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO households (household_no, purok_sitio, address) VALUES (?,?,?) RETURNING household_id");
        $stmt->execute([$data['household_no'], $data['purok_sitio'], $data['address'] ?? '']);
        return $stmt->fetch()['household_id'];
    }

    public static function update($id, $data) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("UPDATE households SET household_no=?, purok_sitio=?, address=? WHERE household_id=?");
        $stmt->execute([$data['household_no'], $data['purok_sitio'], $data['address'] ?? '', $id]);
    }

    public static function delete($id) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("DELETE FROM households WHERE household_id = ?");
        $stmt->execute([$id]);
    }

    public static function count() {
        $pdo = DB::connect();
        return $pdo->query("SELECT COUNT(*) FROM households")->fetchColumn();
    }

    public static function getAllSimple() {
        $pdo = DB::connect();
        return $pdo->query("SELECT household_id, household_no, purok_sitio FROM households ORDER BY household_no")->fetchAll();
    }
}
