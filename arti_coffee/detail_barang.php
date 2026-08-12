<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
// Ambil data barang + nama kategori + nama satuan
$query = mysqli_query($koneksi, "
    SELECT barang.*, kategori.nama_kategori, satuan.nama_satuan 
    FROM barang 
    JOIN kategori ON barang.id_kategori = kategori.id 
    JOIN satuan ON barang.id_satuan = satuan.id 
    WHERE id_barang='$id'
");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Barang - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div id="wrapper">
    <?php include 'sidebar.php'; ?>
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">Detail Barang</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary fw-bold">Detail Informasi</h4>
                <a href="barang.php" class="btn btn-secondary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4" style="color: #4E342E;">Spesifikasi Produk</h5>
                            <table class="table table-borderless align-middle">
                                <tr>
                                    <td width="30%" class="text-muted">ID Barang</td>
                                    <td class="fw-bold text-dark">: <?php echo $data['id_barang']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nama Barang</td>
                                    <td class="fw-bold fs-5">: <?php echo $data['nama_barang']; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Kategori</td>
                                    <td>: <span class="badge bg-info text-dark bg-opacity-25 border border-info px-3 py-2 rounded-pill"><?php echo $data['nama_kategori']; ?></span></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Stok Saat Ini</td>
                                    <td>: 
                                        <span class="fs-4 fw-bold" style="color: #C89F81;"><?php echo $data['stok']; ?></span> 
                                        <span class="text-muted small"><?php echo $data['nama_satuan']; ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Stok Minimum</td>
                                    <td>: <span class="text-danger fw-bold"><?php echo $data['stok_minimal']; ?></span> <small class="text-muted">(Batas Alert)</small></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center text-center">
                            <p class="fw-bold text-muted mb-3">Foto Produk</p>
                            <?php if($data['foto'] != null && file_exists("image/barang/".$data['foto'])) { ?>
                                <img src="image/barang/<?php echo $data['foto']; ?>" class="img-fluid rounded-3 shadow-sm" style="max-height: 300px; object-fit: cover;">
                            <?php } else { ?>
                                <div class="p-5 bg-light rounded-3 text-muted">
                                    <i class="fas fa-image fa-3x mb-3"></i><br>
                                    Tidak ada foto
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    toggleButton.onclick = function () { el.classList.toggle("toggled"); };
</script>
</body>
</html>