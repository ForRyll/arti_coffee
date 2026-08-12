<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Query Data
$query = mysqli_query($koneksi, "
    SELECT barang.*, kategori.nama_kategori, satuan.nama_satuan 
    FROM barang 
    JOIN kategori ON barang.id_kategori = kategori.id 
    JOIN satuan ON barang.id_satuan = satuan.id
    ORDER BY barang.id_barang ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .breadcrumb-item a { text-decoration: none; color: #6c757d; }
        .breadcrumb-item a:hover { color: #C89F81; }
        .separator-line { border-left: 2px solid #ddd; height: 20px; margin: 0 15px; }
        
        /* --- PERBAIKAN TOMBOL DISINI --- */
        .btn-kapsul {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
            width: auto !important; /* KUNCI BIAR GA MELAR */
        }
    </style>
</head>
<body>

<div id="wrapper">
    
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">
                Manajemen Barang
            </div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                
                <div class="d-flex align-items-center">
                    <h4 class="text-secondary fw-bold mb-0">Data Barang</h4>
                    <div class="separator-line"></div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item text-muted">Master Data</li>
                            <li class="breadcrumb-item active text-muted" aria-current="page">Barang</li>
                        </ol>
                    </nav>
                </div>

                <div>
                    <?php if($_SESSION['role'] == 'Owner' || $_SESSION['role'] == 'Staff F&B') { ?>
                        <a href="tambah_barang.php" class="btn btn-coffee btn-kapsul shadow-sm">
                            <i class="fas fa-plus me-2"></i> Entri Data
                        </a>
                    <?php } ?>
                </div>

            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tabelBarang" class="table table-hover align-middle w-100">
                            <thead class="table-header-coffee">
                                <tr>
                                    <th class="text-center" width="5%">No.</th>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Satuan</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($row = mysqli_fetch_assoc($query)) { 
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="fw-bold text-secondary"><?php echo $row['id_barang']; ?></td>
                                    <td><?php echo $row['nama_barang']; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['nama_kategori']; ?></span></td>
                                    <td>
                                        <?php 
                                        $stok_min = $row['stok_minimal']; 
                                        if($row['stok'] <= $stok_min) {
                                            echo '<span class="badge bg-danger">'.$row['stok'].'</span>';
                                        } else {
                                            echo '<span class="badge bg-success">'.$row['stok'].'</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo $row['nama_satuan']; ?></td>
                                    <td class="text-center">
                                        <a href="detail_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-sm btn-info text-white rounded-circle me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if($_SESSION['role'] == 'Owner' || $_SESSION['role'] == 'Staff F&B') { ?>
                                            <a href="edit_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-sm btn-warning text-white rounded-circle me-1">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <a href="hapus_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-sm btn-danger rounded-circle ms-1" onclick="return confirm('Yakin mau hapus?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php } ?>
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
    toggleButton.onclick = function () {
        el.classList.toggle("toggled");
    };

    $(document).ready(function () {
        $('#tabelBarang').DataTable();
    });
</script>

</body>
</html>