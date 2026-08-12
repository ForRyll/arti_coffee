<?php
include 'koneksi.php';
$pesan_notifikasi = "";

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $role = $_POST['role']; // Ambil role dari dropdown

    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($cek_user) > 0) {
        $pesan_notifikasi = "<script>Swal.fire({icon: 'error', title: 'Gagal!', text: 'Username sudah terpakai!', confirmButtonColor: '#C89F81'});</script>";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // Simpan role juga
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password_hash', '$role')";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan_notifikasi = "<script>Swal.fire({icon: 'success', title: 'Sukses!', text: 'Akun berhasil dibuat.', confirmButtonColor: '#C89F81'}).then((result) => {window.location.href = 'login.php';});</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="card-custom">
        <img src="image/logo.jpg" alt="Logo" class="logo-img">
        <h5 class="mb-4 text-secondary">Daftar Staff Baru</h5>
        <form action="" method="POST">
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="fas fa-user text-muted"></i></span>
                <input type="text" class="form-control border-start-0 rounded-end-pill" placeholder="Username Baru" name="username" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="fas fa-user-tag text-muted"></i></span>
                <select name="role" class="form-select border-start-0 rounded-end-pill" required>
                    <option value="" disabled selected>Pilih Posisi/Jabatan</option>
                    <option value="Staff F&B">Staff F&B (Gudang)</option>
                    <option value="Staff Keuangan">Staff Keuangan (Transaksi)</option>
                </select>
            </div>
            <div class="input-group mb-4">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3"><i class="fas fa-lock text-muted"></i></span>
                <input type="password" class="form-control border-start-0 rounded-end-pill" placeholder="Password" name="password" required>
            </div>
            <button type="submit" name="register" class="btn btn-coffee mb-3">DAFTAR</button>
        </form>
        <div class="footer-text">Sudah punya akun? <br> <a href="login.php" style="text-decoration: none; color: #C89F81;">Login disini</a></div>
    </div>
    <?php echo $pesan_notifikasi; ?>
</body>
</html>