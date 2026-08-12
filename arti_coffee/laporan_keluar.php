<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');

$query = mysqli_query($koneksi, "
    SELECT barang_keluar.*, barang.nama_barang, satuan.nama_satuan 
    FROM barang_keluar 
    JOIN barang ON barang_keluar.id_barang = barang.id_barang
    JOIN satuan ON barang.id_satuan = satuan.id
    WHERE tanggal BETWEEN '$tgl_mulai' AND '$tgl_selesai'
    ORDER BY tanggal ASC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        @media print {
            #sidebar-wrapper, .navbar, .btn-print, .card-header { display: none !important; }
            #page-content-wrapper { margin: 0 !important; padding: 0 !important; width: 100% !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body>

<div id="wrapper">
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">Laporan</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 ps-4">
                    <h5 class="fw-bold text-secondary">Filter Laporan Keluar</h5>
                </div>
                <div class="card-body p-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" class="form-control" value="<?php echo $tgl_mulai; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" class="form-control" value="<?php echo $tgl_selesai; ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-coffee w-100"><i class="fas fa-filter me-2"></i> Tampilkan</button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" onclick="window.print()" class="btn btn-outline-secondary w-100 btn-print">
                                <i class="fas fa-print me-2"></i> Cetak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-danger">Laporan Barang Keluar</h3>
                        <p class="text-muted">Periode: <?php echo date('d/m/Y', strtotime($tgl_mulai)); ?> s/d <?php echo date('d/m/Y', strtotime($tgl_selesai)); ?></p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>ID Transaksi</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Keperluan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no=1; 
                                if(mysqli_num_rows($query) > 0){
                                    while($row = mysqli_fetch_assoc($query)){ 
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center"><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                    <td class="text-center"><?php echo $row['id_transaksi']; ?></td>
                                    <td><?php echo $row['nama_barang']; ?></td>
                                    <td class="text-center text-danger fw-bold">- <?php echo $row['jumlah_keluar']; ?> <?php echo $row['nama_satuan']; ?></td>
                                    <td><?php echo $row['keterangan']; ?></td>
                                </tr>
                                <?php 
                                    } 
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-3'>Tidak ada data pada periode ini.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-5 btn-print">
                         <div class="col-4 offset-8 text-center">
                            <p>Dicetak oleh,</p>
                            <br><br><br>
                            <p class="fw-bold text-decoration-underline"><?php echo $_SESSION['username']; ?></p>
                            <p class="small text-muted"><?php echo $_SESSION['role']; ?></p>
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