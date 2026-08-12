<?php
include 'koneksi.php';
$id = $_GET['id'];

// 1. Ambil data dulu sebelum dihapus (kita butuh id_barang dan jumlahnya)
$cek = mysqli_query($koneksi, "SELECT * FROM barang_masuk WHERE id_transaksi='$id'");
$data = mysqli_fetch_array($cek);
$id_barang = $data['id_barang'];
$jumlah = $data['jumlah_masuk'];

// 2. Kurangi Stok di Tabel Barang (Balikin stok kayak semula)
mysqli_query($koneksi, "UPDATE barang SET stok = stok - $jumlah WHERE id_barang='$id_barang'");

// 3. Hapus Data Transaksi
$hapus = mysqli_query($koneksi, "DELETE FROM barang_masuk WHERE id_transaksi='$id'");

if($hapus){
    echo "<script>alert('Data Dihapus & Stok Dikembalikan!');window.location='barang_masuk.php';</script>";
}
?>