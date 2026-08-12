<?php
session_start();
include 'koneksi.php';

$pesan_notifikasi = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Cari user berdasarkan username
    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");

    // Jika user ketemu (Cek jumlah baris 1x saja)
    if (mysqli_num_rows($result) === 1) {
        
        // Ambil datanya (Cukup 1x saja)
        $row = mysqli_fetch_assoc($result);
        
        // Cek password
        if (password_verify($password, $row['password'])) {
            
            // Set Session LENGKAP
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = isset($row['role']) ? $row['role'] : 'Staff F&B'; 

            // --- INI PERBAIKANNYA (Tanda Petik dan Koma) ---
            $pesan_notifikasi = "
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil!',
                    text: 'Hi, " . $username . " Selamat Datang di Sistem Pengelolaan Stok Barang Art\\'i Coffee',
                    timer: 2000, 
                    showConfirmButton: false
                }).then(() => { window.location.href = 'dashboard.php'; });
            </script>";
            // --- BATAS PERBAIKAN ---

        } else {
            // Password salah
            $pesan_notifikasi = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Password salah!',
                    confirmButtonColor: '#C89F81'
                });
            </script>";
        }
    } else {
        // Username tidak ditemukan
        $pesan_notifikasi = "
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Username tidak terdaftar!',
                    confirmButtonColor: '#C89F81'
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
    <title>Login - Art'i Coffee</title>
    <link rel="icon" type="image/png" href="image/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="card-custom">
        <img src="image/logo.jpg" alt="Logo Art'i Coffee" class="logo-img">
        
        <h5 class="mb-4 text-secondary">Sistem Pengelolaan Stok<br>Art'i Coffee</h5>

        <form action="" method="POST">
            <div class="input-group mb-3">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                    <i class="fas fa-user text-muted"></i>
                </span>
                <input type="text" class="form-control border-start-0 rounded-end-pill" placeholder="Username" name="username" required>
            </div>

            <div class="input-group mb-4">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                    <i class="fas fa-key text-muted"></i>
                </span>
                <input type="password" class="form-control border-start-0 border-end-0" placeholder="Password" name="password" id="password" required>
                <span class="input-group-text bg-white border-start-0 rounded-end-pill pe-3" onclick="togglePassword()">
                    <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                </span>
            </div>

            <button type="submit" name="login" class="btn btn-coffee mb-3">LOGIN</button>
        </form>

        <div class="footer-text">
            @2025 - Sipetok <br>
            <a href="register.php" style="text-decoration: none; color: #C89F81;">Belum punya akun? Daftar</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            var icon = document.getElementById("eyeIcon");
            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                x.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
    
    <?php echo $pesan_notifikasi; ?>

</body>
</html>