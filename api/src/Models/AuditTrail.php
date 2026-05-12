<?php
namespace App\Models;

use App\Database\DB;

class AuditTrail {
    public static function log($userId, $action, $entityType, $entityId = null, $details = '') {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("INSERT INTO audit_trail (user_id, action, entity_type, entity_id, details, ip_address) VALUES (?,?,?,?,?,?)");
        $stmt->execute([
            $userId,
            $action,
            $entityType,
            $entityId,
            $details,
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
        ]);
    }

    public static function getRecent($limit = 50) {
        $pdo = DB::connect();
        $stmt = $pdo->prepare("SELECT a.*, u.full_name as user_name FROM audit_trail a LEFT JOIN users u ON a.user_id = u.user_id ORDER BY a.created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
