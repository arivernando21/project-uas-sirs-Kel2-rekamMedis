<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['dokter', 'admin', 'perawat']);

$dashboardLink = match ($_SESSION['role']) {
    'admin' => 'admin.php',
    'dokter' => 'doctor.php',
    'perawat' => 'nurse.php',
    default => '../index.php'
};

$patients = $pdo->query("SELECT * FROM pasien ORDER BY nama ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Daftar Pasien</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="layout">
        <div class="sidebar">
            <!-- LOGO -->
            <div class="sidebar-logo">

                <div class="logo-row">
                    <h3>🏥 SIRS EMR</h3>
                </div>

                <p class="logo-sub">Sistem Informasi Rekam Medis</p>

            </div>
            <a href="<?= $dashboardLink ?>">
                <span>🏠</span> Dashboard
            </a>

            <a class="active">
                <span>👤</span> Pasien
            </a>
            <div class="logout"><a href="../logout.php" style="color:white; text-decoration:none;">Logout</a></div>
        </div>
        <div class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <h2>Daftar Pasien</h2>
                    <p>Daftar seluruh pasien terdaftar dalam sistem.</p>
                </div>
                <div class="topbar-right">
                    <div class="date-box">
                        <span>📅</span> <?= date('d M Y') ?>
                    </div>
                    <div class="user-box">
                        <div class="user-info">
                            <strong><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong>
                            <small><?= ucfirst(htmlspecialchars($_SESSION['role'] ?? 'Guest')) ?></small>
                        </div>
                        <div class="avatar">👨‍⚕️</div>
                    </div>
                </div>
            </div>
            
            <div class="content">
                <div class="card table-card">
                    <h4>Data Seluruh Pasien</h4>
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tgl Lahir</th>
                        <th>No Telp</th>
                        <th>Aksi</th>
                    </tr>
                    <?php $no = 1;
                    foreach ($patients as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($p['nama']) ?></td>
                            <td><?= $p['tanggal_lahir'] ?></td>
                            <td><?= $p['no_telp'] ?></td>
                            <td>
                                <a href="patient_history.php?id=<?= $p['id_pasien'] ?>" class="badge warning"
                                    style="text-decoration:none;">Lihat Riwayat</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        </div>
    </div>
</body>

</html>
