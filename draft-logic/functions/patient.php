<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['id_user']) || !in_array($_SESSION['role'], ['admin', 'perawat'])) {
    header("Location: ../index.php?pesan=terlarang");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_pasien'])) {
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];

    try {
        $sql = "INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, no_telp, alamat) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nama, $tanggal_lahir, $jenis_kelamin, $no_telp, $alamat]);

        header("Location: ../views/nurse.php?pesan=tambah_pasien_berhasil");
        exit;
    } catch (PDOException $e) {
        die("Error saat menyimpan pasien: " . $e->getMessage());
    }
}