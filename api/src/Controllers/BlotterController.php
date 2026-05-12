<?php
namespace App\Controllers;

use App\Models\Blotter;
use App\Models\AuditTrail;

class BlotterController {
    public function index() {
        global $appConfig;
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';
        $blotters = Blotter::getAll($status, $search);
        require __DIR__ . '/../../templates/blotters/index.php';
    }

    public function create() {
        global $appConfig;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = Blotter::create($_POST);
            AuditTrail::log($_SESSION['user_id'], 'Filed Blotter', 'blotter', $id, $_POST['incident_type']);
            header("Location: {$appConfig['base_url']}/blotters/view/$id");
            exit;
        }
        require __DIR__ . '/../../templates/blotters/create.php';
    }

    public function view($id) {
        global $appConfig;
        $blotter = Blotter::getById($id);
        if (!$blotter) { http_response_code(404); echo "Blotter not found"; return; }
        $summons = Blotter::getSummons($id);
        require __DIR__ . '/../../templates/blotters/view.php';
    }

    public function settle($id) {
        global $appConfig;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'];
            $notes = $_POST['resolution_notes'] ?? '';
            $date = $_POST['resolution_date'] ?? date('Y-m-d');
            Blotter::updateStatus($id, $status, $notes, $date);
            AuditTrail::log($_SESSION['user_id'], 'Settled Blotter', 'blotter', $id, "Status: $status");
            header("Location: {$appConfig['base_url']}/blotters/view/$id");
            exit;
        }
    }

    public function summons($id) {
        global $appConfig;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Blotter::addSummons($id, $_POST);
            AuditTrail::log($_SESSION['user_id'], 'Issued Summons (Patawag)', 'blotter', $id, $_POST['summons_type'] ?? 'Patawag');
            header("Location: {$appConfig['base_url']}/blotters/view/$id");
            exit;
        }
    }
}
