<?php
if (session_status()===PHP_SESSION_NONE) session_start();
$activePage = $activePage ?? '';
$userName = $_SESSION['username'] ?? 'User';
$csrf = $_SESSION['csrf_token'] ?? bin2hex(random_bytes(32));
$_SESSION['csrf_token']=$csrf;
?>
<!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($pageTitle ?? 'NID Administration') ?></title>
<link rel="icon" href="assets/images/logos/psalogo.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/css/cooladmin.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head><body>
<div class="app-shell">
<aside class="sidebar" id="sidebar">
<a class="brand" href="index.php"><img src="assets/images/logos/psalogo.png" alt="PSA"><span>NID Administration</span></a>
<div class="nav-section">Workspace</div>
<a class="side-link <?= $activePage==='dashboard'?'active':'' ?>" href="index.php"><i class="bi bi-grid-1x2"></i> Dashboard</a>
<a class="side-link <?= $activePage==='personnel'?'active':'' ?>" href="personnel_information.php"><i class="bi bi-people"></i> Personnel</a>
<a class="side-link <?= $activePage==='kits'?'active':'' ?>" href="registration_kits.php"><i class="bi bi-box-seam"></i> Registration Kits</a>
<a class="side-link" href="registration_kit_monitoring.php"><i class="bi bi-activity"></i> Kit Monitoring</a>
<a class="side-link" href="nid_inventory.php"><i class="bi bi-cpu"></i> NID Inventory</a>
<div class="nav-section">Operations</div>
<a class="side-link" href="borrowers.php"><i class="bi bi-clipboard-check"></i> Borrowers</a>
<a class="side-link" href="monitoring_daily_packets.php"><i class="bi bi-bar-chart"></i> Packet Monitoring</a>
<div class="nav-section">Account</div>
<a class="side-link" href="change_password.php"><i class="bi bi-shield-lock"></i> Change Password</a>
<a class="side-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
</aside>
<main class="main">
<header class="topbar"><div class="d-flex align-items-center gap-3"><button class="btn btn-light mobile-toggle" id="menuBtn"><i class="bi bi-list"></i></button><div><div class="page-title"><?= htmlspecialchars($pageTitle ?? 'NID Administration') ?></div><div class="small muted"><?= htmlspecialchars($pageSubtitle ?? 'Management console') ?></div></div></div><div class="user-chip"><div class="user-dot"><i class="bi bi-person"></i></div><div class="d-none d-sm-block"><div class="fw-semibold small"><?= htmlspecialchars($userName) ?></div><div class="muted" style="font-size:.7rem">Administrator</div></div></div></header>
<section class="content">
