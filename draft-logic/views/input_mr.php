<?php
require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['dokter']);

$id_kunjungan = $_GET['kunjungan'] ?? null;

if (!$id_kunjungan) {
    header("Location: doctor.php");
    exit;
}

$stmt = $pdo->prepare("SELECT k.id_kunjungan, p.nama, p.tanggal_lahir, p.jenis_kelamin 
                        FROM kunjungan k 
                        JOIN pasien p ON k.id_pasien = p.id_pasien 
                        WHERE k.id_kunjungan = ?");
$stmt->execute([$id_kunjungan]);
$data = $stmt->fetch();

if (!$data) {
    die("Data kunjungan tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Rekam Medis</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="layout">
        <div class="sidebar">
            <h3>SIRS EMR</h3>
            <a href="doctor.php">Kembali</a>
        </div>
        <div class="main">
            <div class="topbar">
                <h2>Pemeriksaan Pasien</h2>
                <p>Pasien: <strong><?= htmlspecialchars($data['nama']) ?></strong> (<?= $data['jenis_kelamin'] ?>)</p>
            </div>

            <div class="content">
                <div class="card form-card">
                    <h4>Form Rekam Medis Elektronik</h4>
                    <form action="../functions/medical_record.php" method="POST">
                        <input type="hidden" name="id_kunjungan" value="<?= $data['id_kunjungan'] ?>">
                        
                        <label>Keluhan Utama</label>
                        <textarea name="keluhan" required></textarea>

                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <label>Kode ICD-10 (Diagnosa)</label>
                                <input type="text" name="icd10_code" placeholder="Misal: A00.0">
                            </div>
                            <div style="flex: 2;">
                                <label>Nama Diagnosa</label>
                                <textarea name="diagnosa" required></textarea>
                            </div>
                        </div>

                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <label>Kode ICD-9 (Tindakan)</label>
                                <input type="text" name="icd9_code" placeholder="Misal: 87.44">
                            </div>
                            <div style="flex: 2;">
                                <label>Deskripsi Tindakan</label>
                                <textarea name="tindakan"></textarea>
                            </div>
                        </div>

                        <label>Resep Obat / Terapi</label>
                        <textarea name="resep"></textarea>

                        <button type="submit" class="btn-primary" style="margin-top: 20px;">Simpan & Selesaikan Pemeriksaan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>