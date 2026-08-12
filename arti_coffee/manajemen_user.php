<?php
session_start();
include 'koneksi.php';

// PROTEKSI: Hanya Owner yang boleh masuk sini
if (!isset($_SESSION['login']) || $_SESSION['role'] != 'Owner') {
    echo "<script>alert('Akses Ditolak! Hanya Owner yang boleh masuk.'); window.location='dashboard.php';</script>";
    exit;
}

// Logic Hapus User
if(isset($_GET['hapus'])){
    $id_user = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM users WHERE id='$id_user'");
    echo "<script>alert('User dihapus!'); window.location='manajemen_user.php';</script>";
}

$query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen User - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .btn-kapsul {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 14px;
            width: auto !important;
            white-space: nowrap;
        }
    </style>
</head>
<body>
<div id="wrapper">
    
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">Manajemen Pengguna</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4">
                <i class="fas fa-crown me-2"></i> Halaman ini hanya dapat diakses oleh <b>Owner</b>.
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 ps-4 pe-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-secondary mb-0">Daftar Pengguna</h5>
                    
                    <a href="register.php" class="btn btn-coffee btn-kapsul shadow-sm">
                        <i class="fas fa-plus me-2"></i> Tambah User
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tabelUser" class="table table-hover align-middle w-100">
                            <thead class="table-header-coffee">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Username</th>
                                    <th>Role / Jabatan</th>
                                    <th>Terdaftar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; while($row = mysqli_fetch_assoc($query)){ ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="fw-bold"><?php echo $row['username']; ?></td>
                                    <td>
                                        <?php 
                                        if($row['role'] == 'Owner') echo '<span class="badge bg-dark">Owner</span>';
                                        elseif($row['role'] == 'Staff F&B') echo '<span class="badge bg-success">Staff F&B</span>';
                                        else echo '<span class="badge bg-info text-dark">Staff Keuangan</span>';
                                        ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                    <td class="text-center">
                                        <?php if($row['username'] != $_SESSION['username']) { // Ga boleh hapus diri sendiri ?>
                                        <a href="manajemen_user.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Hapus user ini?')"><i class="fas fa-trash"></i></a>
                                        <?php } else { echo '<span class="text-muted small">Akun Anda</span>'; } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    toggleButton.onclick = function () { el.classList.toggle("toggled"); };
    $(document).ready(function () { $('#tabelUser').DataTable(); });
</script>
</body>
</html>