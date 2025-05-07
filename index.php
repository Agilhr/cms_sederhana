<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header('Location: login.php');
    exit();
}

// Ambil info user login
$userInfo = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Statistik
$stmt = $pdo->query("SELECT COUNT(*) FROM pages");
$pageCount = $stmt->fetchColumn();
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$userCount = $stmt->fetchColumn();

// 5 halaman terbaru
$stmt = $pdo->query("SELECT * FROM pages ORDER BY created_at DESC LIMIT 5");
$recentPages = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 5 user terbaru
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 5");
$recentUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMS Sederhana</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <style>
        .main-header, .content-header, .card-header.bg-primary, .small-box.bg-info, .small-box.bg-success {
            background: linear-gradient(90deg, #007bff 60%, #0056b3 100%) !important;
            color: #fff !important;
        }
        .main-header .navbar-nav .nav-link, .main-header .navbar-nav .nav-link i {
            color: #fff !important;
        }
        .small-box.bg-info, .small-box.bg-success {
            background: linear-gradient(135deg, #007bff 60%, #00c6ff 100%) !important;
            color: #fff !important;
        }
        .small-box .icon i {
            color: #e3eaff !important;
        }
        .card-header.bg-primary {
            background: #007bff !important;
        }
        .btn-primary {
            background-color: #007bff !important;
            border-color: #007bff !important;
        }
        .alert-info {
            background: linear-gradient(90deg, #007bff 60%, #00c6ff 100%) !important;
            color: #fff !important;
            border: none;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-link">
            <span class="brand-text font-weight-light">CMS Sederhana</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages.php" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>Pages</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <!-- Info user login -->
                <div class="alert alert-info">
                    Selamat datang, <b><?php echo htmlspecialchars($userInfo['username']); ?></b>! Anda login sebagai admin.
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $pageCount; ?></h3>
                                <p>Total Pages</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-file"></i>
                            </div>
                            <a href="pages.php" class="small-box-footer">Lihat Semua <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $userCount; ?></h3>
                                <p>Total Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="users.php" class="small-box-footer">Lihat Semua <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">5 Halaman Terbaru</div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($recentPages as $page): ?>
                                        <li class="list-group-item">
                                            <b><?php echo htmlspecialchars($page['title']); ?></b>
                                            <span class="float-right text-muted" style="font-size:12px"><?php echo $page['created_at']; ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                    <?php if (count($recentPages) == 0): ?>
                                        <li class="list-group-item text-center text-muted">Belum ada halaman.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">5 User Terbaru</div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($recentUsers as $user): ?>
                                        <li class="list-group-item">
                                            <b><?php echo htmlspecialchars($user['username']); ?></b>
                                            <span class="float-right text-muted" style="font-size:12px"><?php echo $user['created_at']; ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                    <?php if (count($recentUsers) == 0): ?>
                                        <li class="list-group-item text-center text-muted">Belum ada user.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2024 CMS Sederhana.</strong>
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html> 