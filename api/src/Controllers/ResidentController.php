<?php
namespace App\Controllers;

use App\Database\DB;
use App\Models\Resident;
use App\Models\Household;
use App\Models\AuditTrail;

class ResidentController {
    public function index() {
        global $appConfig;
        $search = $_GET['search'] ?? '';
        $purok = $_GET['purok'] ?? '';
        $sector = $_GET['sector'] ?? '';
        $residents = Resident::getAll($search, $purok, $sector);
        $puroks = Resident::getPuroks();
        require __DIR__ . '/../../templates/residents/index.php';
    }

    public function create() {
        global $appConfig;
        $households = Household::getAllSimple();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = Resident::create($_POST);
            AuditTrail::log($_SESSION['user_id'], 'Created Resident', 'resident', $id, $_POST['last_name'] . ', ' . $_POST['first_name']);
            header("Location: {$appConfig['base_url']}/residents");
            exit;
        }
        require __DIR__ . '/../../templates/residents/create.php';
    }

    public function edit($id) {
        global $appConfig;
        $resident = Resident::getById($id);
        if (!$resident) { http_response_code(404); echo "Resident not found"; return; }
        $households = Household::getAllSimple();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Resident::update($id, $_POST);
            AuditTrail::log($_SESSION['user_id'], 'Updated Resident', 'resident', $id, $_POST['last_name'] . ', ' . $_POST['first_name']);
            header("Location: {$appConfig['base_url']}/residents/profile/$id");
            exit;
        }
        require __DIR__ . '/../../templates/residents/edit.php';
    }

    public function profile($id) {
        global $appConfig;
        $resident = Resident::getById($id);
        if (!$resident) { http_response_code(404); echo "Resident not found"; return; }
        
        $blotters = \App\Models\Blotter::getByResident($id);
        $issuances = \App\Models\Issuance::getByResident($id);
        require __DIR__ . '/../../templates/residents/profile.php';
    }

    public function delete($id) {
        global $appConfig;
        Resident::delete($id);
        AuditTrail::log($_SESSION['user_id'], 'Archived Resident', 'resident', $id, '');
        header("Location: {$appConfig['base_url']}/residents");
        exit;
    }

    public function apiSearch() {
        $term = $_GET['q'] ?? '';
        $results = Resident::search($term);
        header('Content-Type: application/json');
        echo json_encode($results);
        exit;
    }
}
