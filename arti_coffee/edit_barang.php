<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_barang='$id'");
$data = mysqli_fetch_array($query);

// Logic Update Data (Sama seperti sebelumnya)
if(isset($_POST['update'])){
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $kategori = $_POST['kategori'];
    $satuan = $_POST['satuan'];
    $stok_min = $_POST['stok_min'];
    $foto_lama = $_POST['foto_lama'];

    if($_FILES['foto']['error'] === 4) {
        $fotobaru = $foto_lama;
    } else {
        $foto = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];
        $fotobaru = date('dmYHis').$foto;
        $path = "image/barang/".$fotobaru;
        move_uploaded_file($tmp, $path);
        if(file_exists("image/barang/".$foto_lama) && $foto_lama != "") unlink("image/barang/".$foto_lama);
    }

    $update = mysqli_query($koneksi, "UPDATE barang SET nama_barang='$nama', id_kategori='$kategori', id_satuan='$satuan', stok_minimal='$stok_min', foto='$fotobaru' WHERE id_barang='$id'");
    
    if($update){
         echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data barang berhasil diperbarui.',
                confirmButtonColor: '#C89F81'
            }).then((result) => {
                window.location.href = 'barang.php';
            });
         </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Barang - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div id="wrapper">
    <?php include 'sidebar.php'; ?>
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">Ubah Data Barang</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="text-secondary fw-bold">Form Ubah Data</h4>
                <a href="barang.php" class="btn btn-secondary rounded-pill px-4 shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Batal
                </a>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="hidden" name="foto_lama" value="<?php echo $data['foto']; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">ID Barang</label>
                                    <input type="text" class="form-control bg-light" value="<?php echo $data['id_barang']; ?>" readonly>
                                    <small class="text-muted">ID Barang tidak dapat diubah.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Barang <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_barang" class="form-control" value="<?php echo $data['nama_barang']; ?>" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Kategori <span class="text-danger">*</span></label>
                                        <select name="kategori" class="form-select">
                                            <?php
                                            $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                                            while($k = mysqli_fetch_array($kat)){
                                                $selected = ($k['id'] == $data['id_kategori']) ? "selected" : "";
                                                echo "<option value='".$k['id']."' $selected>".$k['nama_kategori']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Satuan <span class="text-danger">*</span></label>
                                        <select name="satuan" class="form-select">
                                            <?php
                                            $sat = mysqli_query($koneksi, "SELECT * FROM satuan");
                                            while($s = mysqli_fetch_array($sat)){
                                                $selected = ($s['id'] == $data['id_satuan']) ? "selected" : "";
                                                echo "<option value='".$s['id']."' $selected>".$s['nama_satuan']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Stok Minimum (Alert) <span class="text-danger">*</span></label>
                                    <input type="number" name="stok_min" class="form-control" value="<?php echo $data['stok_minimal']; ?>" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light border-0 h-100 p-3 d-flex justify-content-center align-items-center text-center">
                                    <label class="mb-3 fw-bold">Foto Produk</label>
                                    
                                    <?php if($data['foto'] != null && file_exists("image/barang/".$data['foto'])) { ?>
                                        <img id="preview" src="image/barang/<?php echo $data['foto']; ?>" class="img-fluid rounded mb-3 shadow-sm" style="max-height: 200px;">
                                    <?php } else { ?>
                                        <img id="preview" src="https://via.placeholder.com/200x200?text=No+Image" class="img-fluid rounded mb-3" style="max-height: 200px;">
                                    <?php } ?>
                                    
                                    <input type="file" name="foto" class="form-control" onchange="previewImage(this)">
                                    <small class="text-muted mt-2 d-block">Format: JPG/PNG, Max 2MB</small>
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <button type="submit" name="update" class="btn btn-coffee rounded-pill px-5 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
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

    // Script Preview Image Langsung Ganti saat Pilih File
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