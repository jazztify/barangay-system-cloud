<?php global $appConfig; $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Dashboard' ?> - <?= htmlspecialchars($appConfig['app_name']) ?></title>
    <link rel="stylesheet" href="<?= $appConfig['base_url'] ?>/public/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="app-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class='bx bxs-institution'></i>
                    <span>BIS Portal</span>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-label">Main Menu</li>
                <li><a href="<?= $appConfig['base_url'] ?>/dashboard" class="<?= strpos($currentUrl, 'dashboard') !== false || $currentUrl === '/' ? 'active' : '' ?>"><i class='bx bxs-dashboard'></i><span>Dashboard</span></a></li>
                <li><a href="<?= $appConfig['base_url'] ?>/residents" class="<?= strpos($currentUrl, 'residents') !== false ? 'active' : '' ?>"><i class='bx bxs-user-detail'></i><span>Residents (RBI)</span></a></li>
                <li><a href="<?= $appConfig['base_url'] ?>/households" class="<?= strpos($currentUrl, 'households') !== false ? 'active' : '' ?>"><i class='bx bxs-home-smile'></i><span>Households</span></a></li>
                <li class="menu-label">Services</li>
                <li><a href="<?= $appConfig['base_url'] ?>/blotters" class="<?= strpos($currentUrl, 'blotters') !== false ? 'active' : '' ?>"><i class='bx bxs-error-circle'></i><span>Blotter & Peace</span></a></li>
                <li><a href="<?= $appConfig['base_url'] ?>/documents" class="<?= strpos($currentUrl, 'documents') !== false ? 'active' : '' ?>"><i class='bx bxs-file-blank'></i><span>Issuances & Docs</span></a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <header class="top-header">
                <div class="header-left">
                    <button id="sidebar-toggle" class="btn-icon"><i class='bx bx-menu'></i></button>
                    <h2><?= $pageTitle ?? 'Dashboard' ?></h2>
                </div>
                <div class="header-right">
                    <span class="user-greeting">Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?></span>
                    <a href="<?= $appConfig['base_url'] ?>/logout" class="btn btn-outline btn-sm">Logout</a>
                </div>
            </header>
            <div class="content-wrapper">
