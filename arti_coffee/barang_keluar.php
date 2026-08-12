<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Query Data Barang Keluar
$query = mysqli_query($koneksi, "
    SELECT barang_keluar.*, barang.nama_barang, satuan.nama_satuan 
    FROM barang_keluar 
    JOIN barang ON barang_keluar.id_barang = barang.id_barang
    JOIN satuan ON barang.id_satuan = satuan.id
    ORDER BY barang_keluar.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Barang Keluar - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .breadcrumb-item a { text-decoration: none; color: #6c757d; }
        .breadcrumb-item a:hover { color: #C89F81; }
        .separator-line { border-left: 2px solid #ddd; height: 20px; margin: 0 15px; }
        .btn-kapsul { 
            border-radius: 50px; 
            padding: 8px 20px; 
            font-weight: 600; 
            font-size: 14px; 
            white-space: nowrap;
            width: auto !important; 
        }
        /* Warna Header Modal Keluar agak merah dikit biar beda */
        .modal-header-coffee { background-color: #C0392B; color: white; } 
    </style>
</head>
<body>

<div id="wrapper">
<?php include 'sidebar.php'; ?>
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">Transaksi Keluar</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <h4 class="text-secondary fw-bold mb-0">Barang Keluar</h4>
                    <div class="separator-line"></div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="dashboard.php"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item text-muted">Transaksi</li>
                            <li class="breadcrumb-item active text-muted" aria-current="page">Riwayat Keluar</li>
                        </ol>
                    </nav>
                </div>
                <div>
                <?php if($_SESSION['role'] == 'Owner' || $_SESSION['role'] == 'Staff Keuangan') { ?>
                    <a href="tambah_keluar.php" class="btn btn-coffee btn-kapsul shadow-sm">
                        <i class="fas fa-minus me-2"></i> Entri Data
                    </a>
                <?php } ?>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table id="tabelKeluar" class="table table-hover align-middle w-100">
                            <thead class="table-header-coffee">
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Barang</th>
                                    <th>Jumlah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($row = mysqli_fetch_assoc($query)) { 
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="fw-bold text-coffee"><?php echo $row['id_transaksi']; ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                    <td><?php echo $row['nama_barang']; ?></td>
                                    <td>
                                        <span class="badge bg-danger rounded-pill bg-opacity-75">
                                            <i class="fas fa-arrow-up me-1"></i> <?php echo $row['jumlah_keluar']; ?> <?php echo $row['nama_satuan']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info text-white rounded-circle me-1" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $row['id_transaksi']; ?>">
                                        <i class="fas fa-eye"></i>
                                    </button> 
                                    <?php if($_SESSION['role'] == 'Owner' || $_SESSION['role'] == 'Staff Keuangan') { ?>
                                        <a href="hapus_keluar.php?id=<?php echo $row['id_transaksi']; ?>" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Batalkan transaksi?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php } ?>
                                        <div class="modal fade text-start" id="modalDetail<?php echo $row['id_transaksi']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content rounded-4 border-0">
                                                    <div class="modal-header modal-header-coffee rounded-top-4">
                                                        <h5 class="modal-title fw-bold text-white"><i class="fas fa-info-circle me-2"></i> Detail Barang Keluar</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <table class="table table-borderless">
                                                            <tr>
                                                                <td class="text-muted" width="35%">ID Transaksi</td>
                                                                <td class="fw-bold">: <?php echo $row['id_transaksi']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted">Tanggal</td>
                                                                <td class="fw-bold">: <?php echo date('d F Y', strtotime($row['tanggal'])); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted">Barang</td>
                                                                <td class="fw-bold text-coffee">: <?php echo $row['nama_barang']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-muted">Jumlah Keluar</td>
                                                                <td class="fw-bold text-danger">: - <?php echo $row['jumlah_keluar']; ?> <?php echo $row['nama_satuan']; ?></td>
                                                            </tr>
                                                            <tr><td colspan="2"><hr></td></tr>
                                                            <tr>
                                                                <td class="text-muted">Tujuan/Ket</td>
                                                                <td>
                                                                    <?php 
                                                                    if(!empty($row['keterangan'])){
                                                                        echo '<div class="p-2 bg-light rounded border">'.$row['keterangan'].'</div>';
                                                                    } else { echo '<span class="text-muted fst-italic">-</span>'; }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
    $(document).ready(function () { $('#tabelKeluar').DataTable(); });
</script>
</body>
</html>