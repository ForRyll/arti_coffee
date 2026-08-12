<?php
include 'koneksi.php';
$id = $_GET['id'];

// 1. Ambil data dulu (butuh id_barang & jumlah)
$cek = mysqli_query($koneksi, "SELECT * FROM barang_keluar WHERE id_transaksi='$id'");
$data = mysqli_fetch_array($cek);
$id_barang = $data['id_barang'];
$jumlah = $data['jumlah_keluar'];

// 2. KEMBALIKAN STOK (Stok + Jumlah)
mysqli_query($koneksi, "UPDATE barang SET stok = stok + $jumlah WHERE id_barang='$id_barang'");

// 3. Hapus Data
$hapus = mysqli_query($koneksi, "DELETE FROM barang_keluar WHERE id_transaksi='$id'");

if($hapus){
    echo "<script>alert('Transaksi Dibatalkan. Stok dikembalikan!');window.location='barang_keluar.php';</script>";
}
?>