<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// --- LOGIC CRUD (CREATE, UPDATE, DELETE) ---

// 1. Tambah Kategori
if(isset($_POST['simpan'])){
    $kategori = $_POST['kategori'];
    $query = mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$kategori')");
    if($query){
        echo "<script>alert('Kategori Berhasil Ditambah!'); window.location='kategori.php';</script>";
    }
}

// 2. Edit Kategori
if(isset($_POST['update'])){
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];
    $query = mysqli_query($koneksi, "UPDATE kategori SET nama_kategori='$kategori' WHERE id='$id'");
    if($query){
        echo "<script>alert('Kategori Berhasil Diubah!'); window.location='kategori.php';</script>";
    }
}

// 3. Hapus Kategori
if(isset($_GET['hapus'])){
    $id = $_GET['hapus'];
    // Cek dulu apakah kategori ini dipakai di barang?
    $cek = mysqli_query($koneksi, "SELECT * FROM barang WHERE id_kategori='$id'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Gagal! Kategori ini sedang dipakai oleh barang lain.'); window.location='kategori.php';</script>";
    } else {
        mysqli_query($koneksi, "DELETE FROM kategori WHERE id='$id'");
        echo "<script>alert('Kategori Berhasil Dihapus!'); window.location='kategori.php';</script>";
    }
}

$data_kategori = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori Barang - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Sedikit styling tambahan biar modalnya cantik */
        .modal-header-coffee { background-color: #4E342E; color: white; }
        .btn-kapsul { border-radius: 50px; padding: 8px 20px; font-weight: 600; width: auto !important;}
    </style>
</head>
<body>

<div id="wrapper">
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">Master Data</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="text-secondary fw-bold mb-0">Jenis Barang</h4>
                    <p class="text-muted small mb-0">Kelola kategori produk (Kopi, Susu, dll)</p>
                </div>
                
                <?php if($_SESSION['role'] == 'Owner' || $_SESSION['role'] == 'Staff F&B') { ?>
                <button type="button" class="btn btn-coffee btn-kapsul shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-2"></i> Tambah Baru
                </button>
                <?php } ?>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tabelKategori" class="table table-hover align-middle w-100">
                            <thead class="table-header-coffee">
                                <tr>
                                    <th width="10%" class="text-center">No.</th>
                                    <th>Nama Kategori</th>
                                    <?php if($_SESSION['role'] != 'Staff Keuangan') { ?>
                                    <th width="20%" class="text-center">Aksi</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no=1; while($row = mysqli_fetch_assoc($data_kategori)) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="fw-bold text-coffee"><?php echo $row['nama_kategori']; ?></td>
                                    
                                    <?php if($_SESSION['role'] != 'Staff Keuangan') { ?>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning text-white rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#modalEdit<?php echo $row['id']; ?>">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <a href="kategori.php?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Hapus kategori ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                    <?php } ?>
                                </tr>

                                <div class="modal fade" id="modalEdit<?php echo $row['id']; ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0">
                                            <div class="modal-header modal-header-coffee rounded-top-4">
                                                <h5 class="modal-title text-white">Edit Kategori</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST">
                                                <div class="modal-body p-4">
                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                    <label class="form-label fw-bold">Nama Kategori</label>
                                                    <input type="text" name="kategori" class="form-control" value="<?php echo $row['nama_kategori']; ?>" required>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" name="update" class="btn btn-coffee rounded-pill px-4">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header modal-header-coffee rounded-top-4">
                <h5 class="modal-title text-white">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <label class="form-label fw-bold">Nama Kategori</label>
                    <input type="text" name="kategori" class="form-control" placeholder="Contoh: Snack, Minuman..." required>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="simpan" class="btn btn-coffee rounded-pill px-4">Simpan</button>
                </div>
            </form>
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
    $(document).ready(function () { $('#tabelKategori').DataTable(); });
</script>
</body>
</html>