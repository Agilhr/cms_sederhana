<?php
session_start();
require_once 'config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Inisialisasi variabel
$title = '';
$content = '';
$isEdit = false;

// Jika edit, ambil data lama
if (isset($_GET['id'])) {
    $isEdit = true;
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
    $stmt->execute([$id]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($page) {
        $title = $page['title'];
        $content = $page['content'];
    } else {
        die('Halaman tidak ditemukan!');
    }
}

// Proses submit form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    if ($isEdit) {
        // Update
        $stmt = $pdo->prepare("UPDATE pages SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);
        header('Location: pages.php');
        exit();
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO pages (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);
        header('Location: pages.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $isEdit ? 'Edit' : 'Tambah'; ?> Page | CMS Sederhana</title>
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
                        <h1 class="m-0"><?php echo $isEdit ? 'Edit' : 'Tambah'; ?> Page</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="pages.php" class="btn btn-secondary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Judul</label>
                                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Konten</label>
                                <textarea name="content" class="form-control" rows="8" required><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><?php echo $isEdit ? 'Update' : 'Tambah'; ?></button>
                        </form>
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