<?php
session_start();
include 'koneksi.php';

if(isset($_POST['simpan'])){
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $kategori = $_POST['kategori'];
    $satuan = $_POST['satuan'];
    $stok = $_POST['stok'];
    $stok_min = $_POST['stok_min'];

    // Logic Upload Foto
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    $fotobaru = date('dmYHis').$foto; // Rename biar unik
    $path = "image/barang/".$fotobaru;

    if(move_uploaded_file($tmp, $path)){
        $query = "INSERT INTO barang VALUES ('$id_barang', '$nama_barang', '$kategori', '$satuan', '$stok', '$stok_min', '$fotobaru')";
        if(mysqli_query($koneksi, $query)){
            echo "<script>alert('Berhasil Disimpan!');window.location='barang.php';</script>";
        }
    } else {
         // Kalau user ga upload foto, simpan data tanpa foto (opsional)
         echo "<script>alert('Gagal Upload Foto!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Barang</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="wrapper">
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
             </nav>

        <div class="container-fluid p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary fw-bold">Tambah Data Barang</h4>
                <a href="barang.php" class="btn btn-secondary rounded-pill px-4"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data"> <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">ID Barang</label>
                                    <input type="text" name="id_barang" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kategori</label>
                                        <select name="kategori" class="form-select">
                                            <?php
                                            $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                                            while($k = mysqli_fetch_array($kat)){
                                                echo "<option value='".$k['id']."'>".$k['nama_kategori']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Satuan</label>
                                        <select name="satuan" class="form-select">
                                            <?php
                                            $sat = mysqli_query($koneksi, "SELECT * FROM satuan");
                                            while($s = mysqli_fetch_array($sat)){
                                                echo "<option value='".$s['id']."'>".$s['nama_satuan']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Stok Awal</label>
                                        <input type="number" name="stok" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Stok Minimum (Alert)</label>
                                        <input type="number" name="stok_min" class="form-control" value="10">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light border-0 h-100 d-flex justify-content-center align-items-center p-3">
                                    <div class="text-center w-100">
                                        <img id="preview" src="https://via.placeholder.com/150" class="img-fluid mb-3 rounded" style="max-height: 200px;">
                                        <label class="form-label d-block fw-bold">Foto Barang</label>
                                        <input type="file" name="foto" class="form-control" onchange="previewImage(this)">
                                        <small class="text-muted">Max 2MB (JPG/PNG)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <button type="submit" name="simpan" class="btn btn-coffee rounded-pill px-5">Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script untuk Preview Gambar sebelum upload
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>