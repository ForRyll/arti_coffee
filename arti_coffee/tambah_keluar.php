<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if(isset($_POST['simpan'])){
    $id_transaksi = "TK-" . date("YmdHis"); // TK = Transaksi Keluar
    $tanggal = $_POST['tanggal'];
    $id_barang = $_POST['id_barang'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $id_user = "1";

    // 1. Cek Stok Dulu! Cukup gak?
    $cek_stok = mysqli_query($koneksi, "SELECT stok FROM barang WHERE id_barang = '$id_barang'");
    $data_stok = mysqli_fetch_array($cek_stok);
    $stok_sekarang = $data_stok['stok'];

    if($stok_sekarang >= $jumlah) {
        // Kalau Cukup, Lanjut Simpan
        $query_simpan = mysqli_query($koneksi, "INSERT INTO barang_keluar VALUES ('$id_transaksi', '$tanggal', '$id_barang', '$jumlah', '$keterangan', '$id_user')");

        if($query_simpan){
            // UPDATE stok (Dikurangi)
            mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang = '$id_barang'");
            
            echo "<script>
                alert('Barang Berhasil Dikeluarkan!');
                window.location='barang_keluar.php';
            </script>";
        }
    } else {
        // Kalau Stok Gak Cukup
        echo "<script>
            alert('Gagal! Stok barang tidak mencukupi. Sisa stok: $stok_sekarang');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Entri Barang Keluar - Art'i Coffee</title>
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
            <div class="ms-auto fw-bold text-secondary">Entri Barang Keluar</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary fw-bold">Form Barang Keluar</h4>
                <a href="barang_keluar.php" class="btn btn-secondary rounded-pill px-4 shadow-sm">
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
                                    <input type="text" class="form-control bg-light" value="Otomatis (TK-...)" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Keluar</label>
                                    <input type="date" name="tanggal" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Barang <span class="text-danger">*</span></label>
                                    <select name="id_barang" class="form-select" required>
                                        <option value="">-- Pilih Barang --</option>
                                        <?php
                                        $barang = mysqli_query($koneksi, "SELECT * FROM barang ORDER BY nama_barang ASC");
                                        while($b = mysqli_fetch_array($barang)){
                                            // Tampilkan Stok biar user tau
                                            echo "<option value='".$b['id_barang']."'>".$b['nama_barang']." (Sisa Stok: ".$b['stok'].")</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jumlah Keluar <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah" class="form-control" placeholder="0" required min="1">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tujuan / Keterangan</label>
                            <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Digunakan untuk order meja 5, atau barang rusak/expired..."></textarea>
                        </div>
                        <hr class="my-4">
                        <button type="submit" name="simpan" class="btn btn-coffee rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Transaksi
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
    toggleButton.onclick = function () { el.classList.toggle("toggled"); };
</script>
</body>
</html>