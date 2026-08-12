<?php 
// Ambil data user terbaru (biar kalau ganti foto langsung berubah)
// Pastikan koneksi sudah di-include di file induk (dashboard, barang, dll)
$username_nav = $_SESSION['username'];
$query_nav = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username_nav'");
$data_nav = mysqli_fetch_assoc($query_nav);

$page = basename($_SERVER['PHP_SELF']); 
?>

<style>
    /* CSS Tambahan Khusus Sidebar Profile */
    .profile-link { text-decoration: none; display: block; transition: 0.3s; }
    .profile-link:hover .avatar-img { transform: scale(1.05); border: 2px solid #fff; }
    .avatar-wrapper { position: relative; display: inline-block; }
    .edit-icon {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: white;
        color: #4E342E;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
</style>

<div id="sidebar-wrapper">
    <div class="sidebar-heading text-center overflow-hidden">
        <i class="fas fa-coffee fa-2x text-white"></i> 
        <span class="brand-text ms-2">Art'i Coffee</span>
    </div>
    
    <div class="text-center py-4 profile-section">
        <a href="profil.php" class="profile-link" title="Edit Profil">
            <div class="avatar-wrapper mb-2">
                <?php if(!empty($data_nav['foto_profil']) && file_exists("image/users/".$data_nav['foto_profil'])) { ?>
                    <img src="image/users/<?php echo $data_nav['foto_profil']; ?>" class="rounded-circle shadow avatar-img" width="70" height="70" style="object-fit: cover;">
                <?php } else { ?>
                    <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=C89F81&color=fff&bold=true" class="rounded-circle shadow avatar-img" width="70">
                <?php } ?>
                
                <div class="edit-icon"><i class="fas fa-pen"></i></div>
            </div>
            
            <div class="profile-text">
                <div class="small text-light fw-bold">Hi, <?php echo $_SESSION['username']; ?></div>
                <div class="badge bg-white text-dark mt-1" style="font-size: 10px;"><?php echo $_SESSION['role']; ?></div>
            </div>
        </a>
    </div>

    <div class="list-group list-group-flush px-2">
        <a href="dashboard.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-home" style="width: 25px;"></i> <span>Dashboard</span>
        </a>
        
        <div class="text-muted small px-3 mt-3 mb-1 section-title">MASTER DATA</div>
        <a href="barang.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'barang.php' || $page == 'tambah_barang.php' || $page == 'edit_barang.php' || $page == 'detail_barang.php') ? 'active' : ''; ?>">
            <i class="fas fa-box" style="width: 25px;"></i> <span>Data Barang</span>
        </a>
        <a href="kategori.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'kategori.php') ? 'active' : ''; ?>">
            <i class="fas fa-tags" style="width: 25px;"></i> <span>Jenis Barang</span>
        </a>
        <a href="satuan.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'satuan.php') ? 'active' : ''; ?>">
            <i class="fas fa-ruler" style="width: 25px;"></i> <span>Satuan</span>
        </a>

        <div class="text-muted small px-3 mt-3 mb-1 section-title">TRANSAKSI</div>
        <a href="barang_masuk.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'barang_masuk.php' || $page == 'tambah_masuk.php') ? 'active' : ''; ?>">
            <i class="fas fa-dolly" style="width: 25px;"></i> <span>Barang Masuk</span>
        </a>
        <a href="barang_keluar.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'barang_keluar.php' || $page == 'tambah_keluar.php') ? 'active' : ''; ?>">
            <i class="fas fa-shipping-fast" style="width: 25px;"></i> <span>Barang Keluar</span>
        </a>

        <?php if($_SESSION['role'] == 'Owner' || $_SESSION['role'] == 'Staff Keuangan') { ?>
        <div class="text-muted small px-3 mt-3 mb-1 section-title">LAPORAN</div>
        <a href="laporan_masuk.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'laporan_masuk.php') ? 'active' : ''; ?>">
            <i class="fas fa-file-import" style="width: 25px;"></i> <span>Laporan Masuk</span>
        </a>
        <a href="laporan_keluar.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'laporan_keluar.php') ? 'active' : ''; ?>">
            <i class="fas fa-file-export" style="width: 25px;"></i> <span>Laporan Keluar</span>
        </a>
        <?php } ?>

        <?php if($_SESSION['role'] == 'Owner') { ?>
        <div class="text-muted small px-3 mt-3 mb-1 section-title">PENGATURAN</div>
        <a href="manajemen_user.php" class="list-group-item list-group-item-action rounded mb-1 <?php echo ($page == 'manajemen_user.php') ? 'active' : ''; ?>">
            <i class="fas fa-users-cog" style="width: 25px;"></i> <span>Manajemen User</span>
        </a>
        <?php } ?>

        <div class="text-muted small px-3 mt-3 mb-1 section-title">AKUN</div>
        <a href="logout.php" class="list-group-item list-group-item-action rounded text-danger">
            <i class="fas fa-sign-out-alt" style="width: 25px;"></i> <span>Logout</span>
        </a>
    </div>
</div>