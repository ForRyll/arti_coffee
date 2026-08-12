-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Nov 2025 pada 04.31
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arti_coffee`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `stok_minimal` int(11) DEFAULT 10,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `id_kategori`, `id_satuan`, `stok`, `stok_minimal`, `foto`) VALUES
('5', 'ultramilk', 2, 2, 100, 5, '17112025051815images.jpeg'),
('6', 'creamer', 2, 2, 5, 5, '17112025055333images.jpeg'),
('B001', 'Arabika Gayo Tes', 1, 1, 50, 10, '17112025033405arabica.png'),
('B002', 'Susu Full Cream', 2, 2, 10, 10, '16112025092910arti.jpg'),
('B004', 'Cup Plastik 14oz', 4, 4, 100, 5, '16112025092852arti.jpg'),
('tes', 'tes', 1, 1, 402, 5, '16112025093019arti.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_transaksi` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `id_barang` varchar(10) NOT NULL,
  `jumlah_keluar` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_transaksi`, `tanggal`, `id_barang`, `jumlah_keluar`, `keterangan`, `id_user`) VALUES
('TK-20251116101537', '2025-11-16', 'tes', 101, 'kadaluarsa', 1),
('TK-20251117033828', '2025-11-17', 'tes', 192, 'kadaluarsa', 1),
('TK-20251117050659', '2025-11-17', 'tes', 100, 'suplay cabang kedua', 1),
('TK-20251117055625', '2025-11-17', '6', 55, 'suplay cabang ke 10', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_transaksi` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `id_barang` varchar(10) NOT NULL,
  `jumlah_masuk` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_transaksi`, `tanggal`, `id_barang`, `jumlah_masuk`, `keterangan`, `id_user`) VALUES
('TM-20251116094451', '2025-11-16', 'tes', 100, 'Beli di toko oren', 1),
('TM-20251117021837', '2025-11-17', 'tes', 190, 'beli di toko ijo', 1),
('TM-20251117050600', '2025-11-17', 'tes', 500, 'toko afdolhama', 1),
('TM-20251117055533', '2025-11-17', '6', 10, 'toko abang afdol', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Kopi Biji'),
(2, 'Susu'),
(3, 'Sirup'),
(4, 'Packaging'),
(5, 'lain-lain');

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan`
--

CREATE TABLE `satuan` (
  `id` int(11) NOT NULL,
  `nama_satuan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `satuan`
--

INSERT INTO `satuan` (`id`, `nama_satuan`) VALUES
(1, 'Kg'),
(2, 'Liter'),
(3, 'Botol'),
(4, 'Pcs'),
(5, 'ton');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('Owner','Staff F&B','Staff Keuangan') DEFAULT 'Staff F&B',
  `foto_profil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`, `foto_profil`) VALUES
(1, 'admin2', 'admin2', '2025-11-16 07:25:47', 'Owner', '16112025132551_arti.jpg'),
(2, 'stafkeutes', '$2y$10$eQJOvEwyLVvz9Runx1XuGOU5OH1H936z6..jSJeMCm/AkZfSSoulm', '2025-11-16 11:20:33', 'Staff Keuangan', NULL),
(3, 'staffb', '$2y$10$GsZSRA5fJxPbn527tooh6.Jg/z3IEuApJVeD7euqX5lQZPElQHL1O', '2025-11-16 12:07:38', 'Staff F&B', NULL),
(4, 'boskopi', '$2y$10$cMyPv2C0Ddh0u8kC8PLkHuofIz5TKYOnLCrO1pSLm8OM9bVuS88C6', '2025-11-17 01:26:18', 'Owner', NULL),
(5, 'adminkopi', '$2y$10$njjpjeezaFVzXtVGdSI6oevWQHOp.MqyD0jFQKJoiQeU0N1FVcKLK', '2025-11-17 01:53:01', 'Owner', '17112025033727_favicon.png'),
(6, 'ilham', '$2y$10$utMfGiba1bqADapGRteVXeHTvPqNag7uCwzfvmqvsxKRNXs86eCSS', '2025-11-17 04:02:51', 'Staff Keuangan', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_satuan` (`id_satuan`);

--
-- Indeks untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`),
  ADD CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`id_satuan`) REFERENCES `satuan` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
