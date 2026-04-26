<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'dokter') {
    header("Location: ../index.php?pesan=terlarang");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kunjungan = $_POST['id_kunjungan'];
    $id_user = $_SESSION['id_user'];
    $keluhan = $_POST['keluhan'];
    $icd10_code = $_POST['icd10_code'];
    $diagnosa = $_POST['diagnosa'];
    $icd9_code = $_POST['icd9_code'];
    $tindakan = $_POST['tindakan'];
    $resep = $_POST['resep'];

    try {
        $pdo->beginTransaction();

        $sql_rm = "INSERT INTO rekam_medis (id_kunjungan, id_user, keluhan, icd10_code, diagnosa, icd9_code, tindakan, resep) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_rm = $pdo->prepare($sql_rm);
        $stmt_rm->execute([
            $id_kunjungan, 
            $id_user, 
            $keluhan, 
            $icd10_code, 
            $diagnosa, 
            $icd9_code, 
            $tindakan, 
            $resep
        ]);

        $sql_kunjungan = "UPDATE kunjungan SET status = 'Selesai' WHERE id_kunjungan = ?";
        $stmt_kunjungan = $pdo->prepare($sql_kunjungan);
        $stmt_kunjungan->execute([$id_kunjungan]);

        $pdo->commit();

        header("Location: ../views/doctor.php?pesan=pemeriksaan_selesai");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        die("Gagal menyimpan rekam medis: " . $e->getMessage());
    }
}