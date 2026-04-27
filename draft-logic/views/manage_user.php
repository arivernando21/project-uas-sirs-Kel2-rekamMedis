<?php
require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['admin']);

$user_to_edit = null;
if (isset($_GET['edit_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = ?");
    $stmt->execute([$_GET['edit_id']]);
    $user_to_edit = $stmt->fetch();
}

$users = $pdo->query("SELECT * FROM user ORDER BY role ASC, username ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Pengguna - SIRS EMR</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>
    <div class="layout">
        
        <div class="sidebar">
            <!-- LOGO -->
            <div class="sidebar-logo">

                <div class="logo-row">
                    <div class="logo-icon-box">
                        <img src="logo.png" alt="logo">
                    </div>
                    <h3>SIRS EMR</h3>
                </div>

                <p class="logo-sub">Sistem Informasi Rekam Medis</p>

            </div>

            <a href="admin.php">
                <span>🏠</span> Dashboard
            </a>

            <a href="manage_user.php" class="active">
                <span>👥</span> Manajemen User
            </a>
            <div class="logout"><a href="../logout.php" style="color:white; text-decoration:none;">Logout</a></div>
        </div>

        <div class="main">
            <div class="topbar">
                <div>
                    <h2>Manajemen Pengguna</h2>
                    <p><?= $user_to_edit ? "Edit data pengguna: " . htmlspecialchars($user_to_edit['username']) : "Kelola akses dokter, perawat, dan administrator." ?>
                    </p>
                </div>
            </div>

            <div class="content">
                <div class="card form-card">
                    <h4><?= $user_to_edit ? "Update Pengguna" : "Tambah Pengguna Baru" ?></h4>
                    <form action="../functions/user_action.php" method="POST">
                        <?php if ($user_to_edit): ?>
                            <input type="hidden" name="id_user" value="<?= $user_to_edit['id_user'] ?>">
                        <?php endif; ?>

                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="<?= $user_to_edit['nama_lengkap'] ?? '' ?>"
                            required>

                        <label>Username</label>
                        <input type="text" name="username" value="<?= $user_to_edit['username'] ?? '' ?>" required>

                        <label>Password
                            <?= $user_to_edit ? "<small>(Kosongkan jika tidak ingin diganti)</small>" : "" ?></label>
                        <input type="password" name="password" <?= $user_to_edit ? "" : "required" ?>>

                        <label>Role</label>
                        <select name="role" required>
                            <option value="admin" <?= (isset($user_to_edit) && $user_to_edit['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="dokter" <?= (isset($user_to_edit) && $user_to_edit['role'] == 'dokter') ? 'selected' : '' ?>>Dokter</option>
                            <option value="perawat" <?= (isset($user_to_edit) && $user_to_edit['role'] == 'perawat') ? 'selected' : '' ?>>Perawat</option>
                        </select>

                        <div style="margin-top: 15px; display: flex; gap: 10px;">
                            <button type="submit" name="<?= $user_to_edit ? 'update_user' : 'add_user' ?>"
                                class="btn-primary">
                                <?= $user_to_edit ? "Update Data" : "Simpan Pengguna" ?>
                            </button>
                            <?php if ($user_to_edit): ?>
                                <a href="manage_user.php" class="btn-outline"
                                    style="text-decoration: none; text-align: center; line-height: 35px; height: 35px;">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <div class="card table-card">
                    <h4>Daftar Pengguna Sistem</h4>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                        <?php $no = 1;
                        foreach ($users as $u): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($u['nama_lengkap']) ?></td>
                                <td><?= htmlspecialchars($u['username']) ?></td>
                                <td><span class="badge success"><?= ucfirst($u['role']) ?></span></td>
                                <td>
                                    <a href="manage_user.php?edit_id=<?= $u['id_user'] ?>"
                                        style="color: blue; text-decoration: none; font-size: 12px; margin-right: 10px;">Edit</a>
                                    <a href="../functions/user_action.php?delete_id=<?= $u['id_user'] ?>"
                                        onclick="return confirm('Hapus user ini?')"
                                        style="color: red; text-decoration: none; font-size: 12px;">Hapus</a>
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
