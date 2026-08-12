<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Logic Simpan Data
if(isset($_POST['simpan'])){
    // ID Transaksi Otomatis: TM + TahunBulanTanggalJamMenitDetik (Biar Unik)
    $id_transaksi = "TM-" . date("YmdHis");
    $tanggal = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $id_user = "1"; // Default user ID 1 dulu

    // 1. Simpan ke tabel barang_masuk
    $query_simpan = mysqli_query($koneksi, "INSERT INTO barang_masuk VALUES ('$id_transaksi', '$tanggal', '$id_barang', '$jumlah', '$keterangan', '$id_user')");

    // 2. UPDATE stok di tabel barang (Stok Bertambah)
    if($query_simpan){
        mysqli_query($koneksi, "UPDATE barang SET stok = stok + $jumlah WHERE id_barang = '$id_barang'");
        
        echo "<script>
            // Pakai SweetAlert biar keren
            alert('Stok Berhasil Ditambahkan!');
            window.location='barang_masuk.php';
        </script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entri Barang Masuk - Art'i Coffee</title>
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
            <div class="ms-auto fw-bold text-secondary">
                Entri Barang Masuk
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary fw-bold">Form Barang Masuk</h4>
                <a href="barang_masuk.php" class="btn btn-secondary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ID Transaksi</label>
                                    <input type="text" class="form-control bg-light" value="Otomatis by Sistem" readonly>
                                    <small class="text-muted">ID akan dibuat otomatis saat disimpan.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Masuk</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Barang <span class="text-danger">*</span></label>
                                    <select name="id_barang" class="form-select" required>
                                        <option value="">-- Pilih Barang --</option>
                                        <?php
                                        // Ambil data barang buat dropdown
                                        $barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
                                        while($b = mysqli_fetch_array($barang)){
                                            echo "<option value='".$b['id_barang']."'>".$b['nama_barang']." (Stok saat ini: ".$b['stok'].")</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jumlah Masuk <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah" class="form-control" placeholder="0" required min="1">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Kulakan dari Toko A, atau Bonus Supplier..."></textarea>
                        </div>

                        <hr class="my-4">
                        
                        <button type="submit" name="simpan" class="btn btn-coffee rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan & Tambah Stok
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    toggleButton.onclick = function () {
        el.classList.toggle("toggled");
    };
</script>

</body>
</html>