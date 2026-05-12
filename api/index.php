<?php

session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Database/DB.php';
require_once __DIR__ . '/src/Models/Resident.php';
require_once __DIR__ . '/src/Models/Household.php';
require_once __DIR__ . '/src/Models/Blotter.php';
require_once __DIR__ . '/src/Models/Issuance.php';
require_once __DIR__ . '/src/Models/AuditTrail.php';

$appConfig = require __DIR__ . '/config/app.php';
date_default_timezone_set($appConfig['timezone']);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = trim($requestUri, '/');
$url = !empty($requestUri) ? $requestUri : 'dashboard';

// Auth check helper
function requireAuth() {
    global $appConfig;
    if (!isset($_SESSION['user_id'])) {
        header("Location: {$appConfig['base_url']}/login");
        exit;
    }
}

// Router
$parts = explode('/', $url);
$module = $parts[0] ?? '';
$action = $parts[1] ?? '';
$id = $parts[2] ?? '';

switch ($module) {
    case 'login':
        require __DIR__ . '/src/Controllers/AuthController.php';
        (new \App\Controllers\AuthController())->login();
        break;

    case 'logout':
        require __DIR__ . '/src/Controllers/AuthController.php';
        (new \App\Controllers\AuthController())->logout();
        break;

    case 'dashboard':
        requireAuth();
        require __DIR__ . '/src/Controllers/DashboardController.php';
        (new \App\Controllers\DashboardController())->index();
        break;

    case 'residents':
        requireAuth();
        require __DIR__ . '/src/Controllers/ResidentController.php';
        $ctrl = new \App\Controllers\ResidentController();
        switch ($action) {
            case 'create': $ctrl->create(); break;
            case 'edit': $ctrl->edit((int)$id); break;
            case 'profile': $ctrl->profile((int)$id); break;
            case 'delete': $ctrl->delete((int)$id); break;
            case 'api-search': $ctrl->apiSearch(); break;
            default: $ctrl->index(); break;
        }
        break;

    case 'households':
        requireAuth();
        require __DIR__ . '/src/Controllers/HouseholdController.php';
        $ctrl = new \App\Controllers\HouseholdController();
        switch ($action) {
            case 'create': $ctrl->create(); break;
            case 'edit': $ctrl->edit((int)$id); break;
            case 'view': $ctrl->view((int)$id); break;
            case 'delete': $ctrl->delete((int)$id); break;
            default: $ctrl->index(); break;
        }
        break;

    case 'blotters':
        requireAuth();
        require __DIR__ . '/src/Controllers/BlotterController.php';
        $ctrl = new \App\Controllers\BlotterController();
        switch ($action) {
            case 'create': $ctrl->create(); break;
            case 'view': $ctrl->view((int)$id); break;
            case 'settle': $ctrl->settle((int)$id); break;
            case 'summons': $ctrl->summons((int)$id); break;
            default: $ctrl->index(); break;
        }
        break;

    case 'documents':
        requireAuth();
        require __DIR__ . '/src/Controllers/DocumentController.php';
        $ctrl = new \App\Controllers\DocumentController();
        switch ($action) {
            case 'request': $ctrl->request(); break;
            case 'print': $ctrl->print((int)$id); break;
            default: $ctrl->index(); break;
        }
        break;

    default:
        if (empty($module) || $module === 'index.php') {
            requireAuth();
            require __DIR__ . '/src/Controllers/DashboardController.php';
            (new \App\Controllers\DashboardController())->index();
        } else {
            http_response_code(404);
            echo "<h1>404 Not Found</h1><p>The page '$url' does not exist.</p>";
        }
        break;
}
