<?php
require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['dokter', 'admin']);

$id_pasien = $_GET['id'] ?? null;

if (!$id_pasien) {
    die("ID Pasien tidak ditemukan.");
}

$stmt_pasien = $pdo->prepare("SELECT * FROM pasien WHERE id_pasien = ?");
$stmt_pasien->execute([$id_pasien]);
$pasien = $stmt_pasien->fetch();

if (!$pasien) {
    die("Pasien tidak terdaftar.");
}

$sql_history = "SELECT rm.*, k.tanggal_kunjungan, u.username as nama_dokter 
                FROM rekam_medis rm
                JOIN kunjungan k ON rm.id_kunjungan = k.id_kunjungan
                JOIN user u ON rm.id_user = u.id_user
                WHERE k.id_pasien = ?
                ORDER BY rm.tanggal_pemeriksaan DESC";
$stmt_history = $pdo->prepare($sql_history);
$stmt_history->execute([$id_pasien]);
$riwayat = $stmt_history->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Medis - <?= htmlspecialchars($pasien['nama']) ?></title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .timeline-card {
            border-left: 4px solid #2A7FFF;
            margin-bottom: 20px;
            padding-left: 15px;
        }
        .icd-badge {
            background: #f0f0f0;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="layout">
        <div class="sidebar">
            <h3>SIRS EMR</h3>
            <a href="patients_list.php">Kembali ke Daftar</a>
        </div>
        <div class="main">
            <div class="topbar">
                <h2>Riwayat Medis Pasien</h2>
                <div class="user"><?= htmlspecialchars($pasien['nama']) ?></div>
            </div>

            <div class="content">
                <div class="card" style="margin-bottom: 20px; background: #f9f9f9;">
                    <p><strong>Nama:</strong> <?= htmlspecialchars($pasien['nama']) ?> | 
                       <strong>Tgl Lahir:</strong> <?= $pasien['tanggal_lahir'] ?> | 
                       <strong>No. Telp:</strong> <?= $pasien['no_telp'] ?></p>
                </div>

                <?php if (empty($riwayat)): ?>
                    <div class="card">
                        <p style="text-align:center; color: #999;">Belum ada catatan rekam medis untuk pasien ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($riwayat as $r): ?>
                    <div class="card timeline-card">
                        <div style="display: flex; justify-content: space-between;">
                            <h4 style="color: #2A7FFF;"><?= date('d M Y - H:i', strtotime($r['tanggal_pemeriksaan'])) ?></h4>
                            <small>Pemeriksa: <strong>dr. <?= htmlspecialchars($r['nama_dokter']) ?></strong></small>
                        </div>
                        <hr style="border: 0; border-top: 1px solid #eee; margin: 10px 0;">
                        
                        <p><strong>Keluhan:</strong><br><?= nl2br(htmlspecialchars($r['keluhan'])) ?></p>
                        
                        <div style="margin-top: 10px;">
                            <strong>Diagnosa:</strong> 
                            <span class="icd-badge"><?= $r['icd10_code'] ?></span> <?= htmlspecialchars($r['icd10_nama']) ?>
                            <p style="margin-top: 5px; font-style: italic; color: #555;"><?= nl2br(htmlspecialchars($r['diagnosa'])) ?></p>
                        </div>

                        <div style="margin-top: 10px;">
                            <strong>Tindakan:</strong> 
                            <span class="icd-badge"><?= $r['icd9_code'] ?></span> <?= htmlspecialchars($r['icd9_nama']) ?>
                            <p style="margin-top: 5px; font-style: italic; color: #555;"><?= nl2br(htmlspecialchars($r['tindakan'])) ?></p>
                        </div>

                        <div style="margin-top: 10px; padding: 10px; background: #fffde7; border-radius: 5px;">
                            <strong>Resep:</strong><br>
                            <?= nl2br(htmlspecialchars($r['resep'])) ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>