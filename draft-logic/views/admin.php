<?php
require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['admin']);

date_default_timezone_set('Asia/Jakarta');


$u_stmt = $pdo->query("SELECT COUNT(*) FROM user");
$total_user = $u_stmt->fetchColumn();

$p_stmt = $pdo->query("SELECT COUNT(*) FROM pasien");
$total_pasien = $p_stmt->fetchColumn();

$r_stmt = $pdo->query("SELECT COUNT(*) FROM rekam_medis");
$total_rm = $r_stmt->fetchColumn();

$users = $pdo->query("SELECT id_user, username, role FROM user")->fetchAll();

// DATA CHART 7 HARI
$stat_stmt = $pdo->query("
    SELECT DATE(tanggal_pemeriksaan) as tanggal, COUNT(*) as total
    FROM rekam_medis
    WHERE tanggal_pemeriksaan >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(tanggal_pemeriksaan)
    ORDER BY tanggal ASC
");
$data_chart = $stat_stmt->fetchAll();

// TOTAL 7 HARI
$total_stmt = $pdo->query("
    SELECT COUNT(*) FROM rekam_medis
    WHERE tanggal_pemeriksaan >= CURDATE() - INTERVAL 6 DAY
");
$total_7hari = $total_stmt->fetchColumn();

// RATA-RATA PER HARI
$rata_per_hari = $total_7hari > 0 ? round($total_7hari / 7) : 0;

// PASIEN BARU (7 HARI)
$pasien_baru_stmt = $pdo->query("
    SELECT COUNT(DISTINCT id_pasien)
    FROM kunjungan
    WHERE tanggal_kunjungan >= CURDATE() - INTERVAL 6 DAY
");
$pasien_baru = $pasien_baru_stmt->fetchColumn();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
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

            <!-- MENU -->
            <a class="active">
                <span>🏠</span> Dashboard
            </a>

            <a href="manage_user.php">
                <span>👥</span> Manajemen User
            </a>

            <a href="patients_list.php">
                <span>👤</span> Pasien
            </a>

            <!-- LOGOUT -->
            <div class="logout">
                <a href="../logout.php">Logout</a>
            </div>

        </div>
        <div class="main">
            <div class="topbar">

                <!-- LEFT -->
                <div class="topbar-left">
                    <h2>Dashboard Admin</h2>
                    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?> 👋</p>
                </div>

                <!-- RIGHT -->
                <div class="topbar-right">
                    <div class="date-box">
                        <span>📅</span> <?= date('d M Y | H:i') ?>
                    </div>
                    <div class="user-box">
                        <div class="user-info">
                            <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                            <small>Administrator</small>
                        </div>
                        <div class="avatar">👨‍💻</div>
                    </div>
                </div>

            </div>

            <div class="cards">

                <!-- TOTAL USER -->
                <div class="card stat-card">
                    <div class="stat-top">
                        <div class="icon-circle blue">👥</div>
                        <div>
                            <p>Total Pengguna</p>
                            <h2><?= $total_user ?></h2>
                            <small>Akun terdaftar</small>
                        </div>
                    </div>

                    <!-- OPTIONAL: scroll ke tabel -->
                    <a href="#tabel-user" class="detail-link">Lihat detail →</a>
                </div>

                <!-- TOTAL PASIEN -->
                <div class="card stat-card">
                    <div class="stat-top">
                        <div class="icon-circle green">👤</div>
                        <div>
                            <p>Total Pasien</p>
                            <h2><?= $total_pasien ?></h2>
                            <small>Pasien terdaftar</small>
                        </div>
                    </div>

                    <a href="patients_list.php" class="detail-link">Lihat detail →</a>
                </div>

                <!-- REKAM MEDIS -->
                <div class="card stat-card">
                    <div class="stat-top">
                        <div class="icon-circle purple">📋</div>
                        <div>
                            <p>Rekam Medis</p>
                            <h2><?= $total_rm ?></h2>
                            <small>Total rekam medis</small>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card dashboard-wrapper">
                <div class="dashboard-grid">

                    <!-- LEFT: STATISTIK -->
                    <div class="statistik-card">
                        <div class="card statistik-card">
                            <div class="stat-header">
                                <h4>Statistik Rekam Medis</h4>
                                <select>
                                    <option>7 Hari Terakhir</option>
                                    <option>30 Hari</option>
                                </select>
                            </div>

                            <!-- TEMP CHART -->
                            <canvas id="chartRM"></canvas>

                            <!-- INFO BAWAH -->
                            <div class="stat-summary-card">

                                <div class="summary-item">
                                    <p>Rekam Medis Dibuat</p>
                                    <h3><?= $total_7hari ?></h3>
                                    <small>7 hari terakhir</small>
                                </div>

                                <div class="summary-item">
                                    <p>Rata-rata per Hari</p>
                                    <h3><?= $rata_per_hari ?></h3>
                                    <small>Rekam medis/hari</small>
                                </div>

                                <div class="summary-item">
                                    <p>Pasien Baru</p>
                                    <h3><?= $pasien_baru ?></h3>
                                    <small>7 hari terakhir</small>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="table-card">
                        <div class="card table-card" id="tabel-user">
                            <h4>Pengguna Terdaftar</h4>
                            <table>
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                                <?php $no = 1;
                                foreach ($users as $u): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($u['username']) ?></td>
                                        <td><?= ucfirst($u['role']) ?></td>
                                        <td><span class="badge success">Aktif</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    </div>
    <script>
        const chartLabels = [
            <?php foreach ($data_chart as $d): ?>
                            "<?= date('d M', strtotime($d['tanggal'])) ?>",
            <?php endforeach; ?>
        ];

        const chartData = [
            <?php foreach ($data_chart as $d): ?>
                            <?= $d['total'] ?>,
            <?php endforeach; ?>
        ];
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartRM');

        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Rekam Medis',
                        data: chartData,
                        borderColor: '#2A7FFF',
                        backgroundColor: 'rgba(42,127,255,0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    </script>
</body>

</html>
