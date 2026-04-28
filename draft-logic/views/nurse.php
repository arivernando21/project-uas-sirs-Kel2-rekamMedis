<?php
require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['perawat']);

$p_stmt = $pdo->query("SELECT COUNT(*) FROM pasien");
$total_pasien = $p_stmt->fetchColumn();

$patients = $pdo->query("SELECT * FROM pasien ORDER BY id_pasien DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Perawat</title>
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
            
            <a href="nurse.php" class="active">
                <span>🏠</span> Dashboard
            </a>
            <a href="patients_list.php">
                <span>👤</span> Pasien
            </a>
            
            <div class="logout">
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        <div class="main">
            <div class="topbar">
                <div class="topbar-left">
                    <h2>Dashboard Perawat</h2>
                    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?></p>
                </div>
                <div class="topbar-right">
                    <div class="date-box">
                        <span>📅</span> <?= date('d M Y') ?>
                    </div>
                    <div class="user-box">
                        <div class="user-info">
                            <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                            <small>Perawat</small>
                        </div>
                        <div class="avatar">👩‍⚕️</div>
                    </div>
                </div>
            </div>

            <div class="cards">
                <div class="card stat">
                    <p>Total Pasien Terdaftar</p>
                    <h2><?= $total_pasien ?></h2>
                </div>
            </div>

            <div class="content">
                <div class="card form-card">
                    <h4>Pendaftaran Pasien Baru</h4>
                    <form action="../functions/patient.php" method="POST">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Nama sesuai KTP">

                        <label>Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" required>

                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" required>
                            <option value="">Pilih</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>

                        <label>No. Telp</label>
                        <input type="text" name="no_telp" placeholder="08xxx">

                        <label>Alamat</label>
                        <textarea name="alamat" placeholder="Alamat domisili saat ini"></textarea>

                        <div class="btn-group">
                            <button type="reset" class="btn-outline">Reset</button>
                            <button type="submit" name="tambah_pasien" class="btn-primary">Simpan Pasien</button>
                        </div>
                    </form>
                </div>

                <div class="card table-card">
                    <h4>Pasien Baru Didaftar</h4>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                        <?php $no = 1; foreach ($patients as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($p['nama']) ?></td>
                            <td>
                                <a href="tambah_kunjungan.php?id=<?= $p['id_pasien'] ?>" class="badge success" style="text-decoration:none;">
                                    + Daftar Berobat
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <br>
                    <a href="patients_list.php" style="font-size: 13px; color: #2A7FFF;">Lihat Semua Pasien →</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
