<?php
include 'koneksi.php';
$id = $_GET['id'];

// 1. Ambil nama foto dulu sebelum dihapus row-nya
$query = mysqli_query($koneksi, "SELECT foto FROM barang WHERE id_barang='$id'");
$data = mysqli_fetch_array($query);
$foto = $data['foto'];

// 2. Hapus file fisik jika ada
if(file_exists("image/barang/".$foto) && $foto != ""){
    unlink("image/barang/".$foto);
}

// 3. Hapus data di database
$hapus = mysqli_query($koneksi, "DELETE FROM barang WHERE id_barang='$id'");

if($hapus){
    echo "<script>alert('Data Berhasil Dihapus!');window.location='barang.php';</script>";
}
?>