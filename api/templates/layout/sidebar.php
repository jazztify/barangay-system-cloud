<?php global $appConfig; ?>
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class='bx bxs-institution'></i>
            <span>BIS Portal</span>
        </div>
    </div>
    
    <ul class="sidebar-menu">
        <li class="menu-label">Main Menu</li>
        <li>
            <a href="<?= $appConfig['base_url'] ?>/dashboard" class="active">
                <i class='bx bxs-dashboard'></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= $appConfig['base_url'] ?>/residents">
                <i class='bx bxs-user-detail'></i>
                <span>Residents (RBI)</span>
            </a>
        </li>
        <li>
            <a href="<?= $appConfig['base_url'] ?>/households">
                <i class='bx bxs-home-smile'></i>
                <span>Households</span>
            </a>
        </li>
        
        <li class="menu-label">Services</li>
        <li>
            <a href="<?= $appConfig['base_url'] ?>/blotters">
                <i class='bx bxs-error-circle'></i>
                <span>Blotter & Peace</span>
            </a>
        </li>
        <li>
            <a href="<?= $appConfig['base_url'] ?>/documents">
                <i class='bx bxs-file-blank'></i>
                <span>Issuances & Docs</span>
            </a>
        </li>
    </ul>
</aside>
