<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$username_sess = $_SESSION['username'];
// Ambil data user terbaru
$cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username_sess'");
$row = mysqli_fetch_assoc($cek);

// LOGIC 1: GANTI PASSWORD
if(isset($_POST['ganti_password'])){
    $pass_lama = $_POST['pass_lama'];
    $pass_baru = $_POST['pass_baru'];
    $konfirmasi = $_POST['konfirmasi'];

    if(password_verify($pass_lama, $row['password'])){
        if($pass_baru == $konfirmasi){
            $hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);
            $update = mysqli_query($koneksi, "UPDATE users SET password='$hash_baru' WHERE username='$username_sess'");
            if($update){
                echo "<script>Swal.fire({icon: 'success', title: 'Berhasil!', text: 'Password berhasil diubah.', confirmButtonColor: '#C89F81'});</script>";
            }
        } else {
            echo "<script>Swal.fire({icon: 'error', title: 'Gagal', text: 'Konfirmasi password tidak cocok!', confirmButtonColor: '#C89F81'});</script>";
        }
    } else {
        echo "<script>Swal.fire({icon: 'error', title: 'Gagal', text: 'Password lama salah!', confirmButtonColor: '#C89F81'});</script>";
    }
}

// LOGIC 2: GANTI FOTO PROFIL
if(isset($_POST['upload_foto'])){
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    
    if(!empty($foto)){
        $fotobaru = date('dmYHis')."_".$foto;
        $path = "image/users/".$fotobaru;

        if(move_uploaded_file($tmp, $path)){
            // Hapus foto lama kalau ada (biar ga numpuk sampah)
            if(!empty($row['foto_profil']) && file_exists("image/users/".$row['foto_profil'])){
                unlink("image/users/".$row['foto_profil']);
            }
            
            // Update DB
            mysqli_query($koneksi, "UPDATE users SET foto_profil='$fotobaru' WHERE username='$username_sess'");
            
            echo "<script>
                Swal.fire({icon: 'success', title: 'Berhasil!', text: 'Foto profil diperbarui.', confirmButtonColor: '#C89F81'})
                .then(() => { window.location.href = 'profil.php'; });
            </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .btn-kapsul { border-radius: 50px; padding: 8px 20px; font-weight: 600; width: auto !important; }
        .nav-pills .nav-link.active { background-color: #C89F81; }
        .nav-pills .nav-link { color: #4E342E; }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include 'sidebar.php'; ?>

    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg navbar-top border-bottom">
            <button class="btn btn-light border" id="menu-toggle"><i class="fas fa-bars"></i></button>
            <div class="ms-auto fw-bold text-secondary">Pengaturan Akun</div>
        </nav>

        <div class="container-fluid p-4">
            
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-4 text-center">
                            <?php if(!empty($row['foto_profil']) && file_exists("image/users/".$row['foto_profil'])) { ?>
                                <img src="image/users/<?php echo $row['foto_profil']; ?>" class="rounded-circle shadow mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                            <?php } else { ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&background=C89F81&color=fff&size=128" class="rounded-circle shadow mb-3">
                            <?php } ?>
                            
                            <h4 class="fw-bold text-secondary mb-0"><?php echo $_SESSION['username']; ?></h4>
                            <span class="badge bg-coffee-light text-dark px-3 py-2 rounded-pill"><?php echo $_SESSION['role']; ?></span>
                        </div>

                        <div class="card-body p-4">
                            <ul class="nav nav-pills mb-4 justify-content-center" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fw-bold" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password" type="button">Ganti Password</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fw-bold" id="pills-foto-tab" data-bs-toggle="pill" data-bs-target="#pills-foto" type="button">Ganti Foto</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-password" role="tabpanel">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Password Lama</label>
                                            <input type="password" name="pass_lama" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password Baru</label>
                                            <input type="password" name="pass_baru" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Ulangi Password Baru</label>
                                            <input type="password" name="konfirmasi" class="form-control" required>
                                        </div>
                                        <button type="submit" name="ganti_password" class="btn btn-coffee btn-kapsul w-100 mt-2">Simpan Password Baru</button>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="pills-foto" role="tabpanel">
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="text-center mb-4">
                                            <div class="alert alert-light border">
                                                <small>Format: JPG/PNG. Max: 2MB. <br> Disarankan foto rasio 1:1 (kotak).</small>
                                            </div>
                                            <input type="file" name="foto" class="form-control mb-3" required>
                                        </div>
                                        <button type="submit" name="upload_foto" class="btn btn-coffee btn-kapsul w-100">Upload Foto Baru</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    toggleButton.onclick = function () { el.classList.toggle("toggled"); };
</script>
</body>
</html>