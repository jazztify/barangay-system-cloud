<?php
namespace App\Controllers;

use App\Models\Household;
use App\Models\AuditTrail;

class HouseholdController {
    public function index() {
        global $appConfig;
        $search = $_GET['search'] ?? '';
        $households = Household::getAll($search);
        require __DIR__ . '/../../templates/households/index.php';
    }

    public function create() {
        global $appConfig;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = Household::create($_POST);
            AuditTrail::log($_SESSION['user_id'], 'Created Household', 'household', $id, $_POST['household_no']);
            header("Location: {$appConfig['base_url']}/households");
            exit;
        }
        require __DIR__ . '/../../templates/households/create.php';
    }

    public function edit($id) {
        global $appConfig;
        $household = Household::getById($id);
        if (!$household) { http_response_code(404); echo "Household not found"; return; }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Household::update($id, $_POST);
            AuditTrail::log($_SESSION['user_id'], 'Updated Household', 'household', $id, $_POST['household_no']);
            header("Location: {$appConfig['base_url']}/households");
            exit;
        }
        require __DIR__ . '/../../templates/households/edit.php';
    }

    public function view($id) {
        global $appConfig;
        $household = Household::getById($id);
        if (!$household) { http_response_code(404); echo "Household not found"; return; }
        $members = Household::getMembers($id);
        require __DIR__ . '/../../templates/households/view.php';
    }

    public function delete($id) {
        global $appConfig;
        Household::delete($id);
        AuditTrail::log($_SESSION['user_id'], 'Deleted Household', 'household', $id, '');
        header("Location: {$appConfig['base_url']}/households");
        exit;
    }
}
