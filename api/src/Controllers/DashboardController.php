<?php
namespace App\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\Blotter;
use App\Models\Issuance;
use App\Models\AuditTrail;

class DashboardController {
    public function index() {
        global $appConfig;
        $stats = [
            'total_residents' => Resident::count(),
            'total_households' => Household::count(),
            'active_blotters' => Blotter::countActive(),
            'docs_this_month' => Issuance::countThisMonth()
        ];
        $demographics = Resident::getDemographics();
        $recentActivity = AuditTrail::getRecent(10);
        require __DIR__ . '/../../templates/dashboard/index.php';
    }
}
