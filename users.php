<?php
session_start();
require_once 'config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Proses hapus user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
    header('Location: users.php');
    exit();
}

// Ambil data user
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Users | CMS Sederhana</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper" style="margin-left:0">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar User/Admin</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $i => $user): ?>
                                    <tr>
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo $user['created_at']; ?></td>
                                        <td>
                                            <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <a href="users.php?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?');"><i class="fas fa-trash"></i> Hapus</a>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Sedang Login</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (count($users) == 0): ?>
                                    <tr><td colspan="4" class="text-center">Belum ada user.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html> 