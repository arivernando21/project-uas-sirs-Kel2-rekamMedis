<?php
require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['admin']);

$u_stmt = $pdo->query("SELECT COUNT(*) FROM user");
$total_user = $u_stmt->fetchColumn();

$p_stmt = $pdo->query("SELECT COUNT(*) FROM pasien");
$total_pasien = $p_stmt->fetchColumn();

$r_stmt = $pdo->query("SELECT COUNT(*) FROM rekam_medis");
$total_rm = $r_stmt->fetchColumn();

$users = $pdo->query("SELECT id_user, username, role FROM user")->fetchAll();
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
            <h3>SIRS EMR</h3>
            <a class="active">Dashboard</a>
            <a href="manage_user.php">Pengguna</a>
            <a href="patients_list.php">Pasien</a>
            <div class="logout"><a href="../logout.php" style="color:white; text-decoration:none;">Logout</a></div>
        </div>
        <div class="main">
            <div class="topbar">
                <div>
                    <h2>Dashboard Admin</h2>
                    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?> 👋</p>
                </div>
                <div class="user"><span>Admin</span></div>
            </div>
            <div class="cards">
                <div class="card stat">
                    <p>Total Pengguna</p>
                    <h2><?= $total_user ?></h2>
                </div>
                <div class="card stat">
                    <p>Total Pasien</p>
                    <h2><?= $total_pasien ?></h2>
                </div>
                <div class="card stat">
                    <p>Rekam Medis</p>
                    <h2><?= $total_rm ?></h2>
                </div>
            </div>
            <div class="content">
                <div class="card table-card">
                    <h4>Pengguna Terdaftar</h4>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                        </tr>
                        <?php $no = 1; foreach ($users as $u): ?>
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
</body>
</html>