<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// QUERY DATA
$data_barang = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM barang"));
$data_kategori = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kategori"));
$data_satuan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM satuan"));
$data_user = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM users"));

$stok_minipis = mysqli_query($koneksi, "
    SELECT barang.*, kategori.nama_kategori, satuan.nama_satuan 
    FROM barang 
    JOIN kategori ON barang.id_kategori = kategori.id 
    JOIN satuan ON barang.id_satuan = satuan.id 
    WHERE stok < 15
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<div id="wrapper">
    <?php include 'sidebar.php'; ?>
    <div id="page-content-wrapper">
        
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">
                Selamat Datang, Admin
            </div>
        </nav>

        <div class="container-fluid">
            <h3 class="text-secondary fw-bold mt-3">Dashboard</h3>
            <p class="text-muted mb-4">Ringkasan data stok barang Art'i Coffee</p>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card-stat p-4 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Total Barang</p>
                            <h2 class="fw-bold text-dark mb-0"><?php echo $data_barang; ?></h2>
                        </div>
                        <div class="icon-box bg-coffee-light">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-stat p-4 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Barang Masuk</p>
                            <h2 class="fw-bold text-success mb-0">12</h2>
                        </div>
                        <div class="icon-box bg-green-light">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-stat p-4 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Barang Keluar</p>
                            <h2 class="fw-bold text-warning mb-0">5</h2>
                        </div>
                        <div class="icon-box bg-orange-light">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                     <div class="card-stat p-4 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Jenis Barang</p>
                            <h4 class="fw-bold mb-0"><?php echo $data_kategori; ?></h4>
                        </div>
                        <div class="icon-box bg-blue-light">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                     <div class="card-stat p-4 d-flex flex-row align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1 small">Total User</p>
                            <h4 class="fw-bold mb-0"><?php echo $data_user; ?></h4>
                        </div>
                        <div class="icon-box bg-coffee-light">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-5">
                <div class="card-header bg-white border-0 pt-4 ps-4">
                    <h6 class="fw-bold text-danger"><i class="fas fa-exclamation-circle me-1"></i> Stok Menipis</h6>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-header-coffee">
                                <tr>
                                    <th class="py-3 ps-3 rounded-start">ID</th>
                                    <th class="py-3">Nama Barang</th>
                                    <th class="py-3">Kategori</th>
                                    <th class="py-3">Stok</th>
                                    <th class="py-3 pe-3 rounded-end">Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($stok_minipis) > 0){ while($row = mysqli_fetch_assoc($stok_minipis)){ ?>
                                <tr>
                                    <td class="fw-bold text-secondary ps-3"><?php echo $row['id_barang']; ?></td>
                                    <td><?php echo $row['nama_barang']; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['nama_kategori']; ?></span></td>
                                    <td><span class="badge bg-warning text-dark"><?php echo $row['stok']; ?></span></td>
                                    <td class="pe-3"><?php echo $row['nama_satuan']; ?></td>
                                </tr>
                                <?php }} else { echo "<tr><td colspan='5' class='text-center text-muted'>Aman!</td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> </div> </div>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Debugging: Cek apakah tombol diklik di Console Browser (F12)
        console.log("Script Siap!");

        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            console.log("Tombol Hamburger Diklik!"); // Cek log ini
            
            // Aksi Toggle Class
            $("#wrapper").toggleClass("toggled");
        });
    });
</script>

</body>
</html>