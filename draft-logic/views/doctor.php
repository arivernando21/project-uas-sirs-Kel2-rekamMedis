<?php
require_once '../config/database.php';
require_once '../functions/auth.php';
checkAccess(['dokter']);

$p_stmt = $pdo->query("SELECT COUNT(*) FROM pasien");
$total_pasien = $p_stmt->fetchColumn();

$q_stmt = $pdo->query("SELECT COUNT(*) FROM kunjungan WHERE status = 'Menunggu'");
$antrian = $q_stmt->fetchColumn();

$pasien_list = $pdo->query("SELECT k.id_kunjungan, p.nama, p.tanggal_lahir, k.status 
                            FROM kunjungan k 
                            JOIN pasien p ON k.id_pasien = p.id_pasien 
                            WHERE k.status != 'Selesai'
                            ORDER BY k.tanggal_kunjungan ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dokter</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="page-doctor">
    <div class="layout">
        <div class="sidebar">
            <!-- LOGO -->
            <div class="sidebar-logo">
                <div class="logo-row">
                    <h3>🏥 SIRS EMR</h3>
                </div>
                <p class="logo-sub">Sistem Informasi Rekam Medis</p>
            </div>
            
            <a class="active">
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
                    <h2>Dashboard Dokter</h2>
                    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?></p>
                </div>
                <div class="topbar-right">
                    <div class="date-box">
                        <span>📅</span> <?= date('d M Y') ?>
                    </div>
                    <div class="user-box">
                        <div class="user-info">
                            <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                            <small>Dokter</small>
                        </div>
                        <div class="avatar">👨‍⚕️</div>
                    </div>
                </div>
            </div>

            <div class="cards">
                <div class="card stat">
                    <p>Total Pasien</p>
                    <h2><?= $total_pasien ?></h2>
                </div>
                <div class="card stat">
                    <p>Antrian</p>
                    <h2><?= $antrian ?></h2>
                </div>
            </div>

            <div class="content">
                <div class="card table-card">
                    <h4>Antrian Pasien</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pasien_list)): ?>
                                <tr>
                                    <td colspan="4" class="table-empty">Tidak ada antrian aktif.</td>
                                </tr>
                            <?php endif; ?>
                            <?php $no = 1; foreach ($pasien_list as $pl): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($pl['nama']) ?></td>
                                <td>
                                    <span class="badge <?= $pl['status'] == 'Periksa' ? 'success' : 'warning' ?>">
                                        <?= $pl['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn-select js-pilih-pasien"
                                            data-id="<?= htmlspecialchars((string) $pl['id_kunjungan'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-nama="<?= htmlspecialchars($pl['nama'], ENT_QUOTES, 'UTF-8') ?>">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card form-card form-card--mr">
                    <h4>Form Input Lengkap Rekam Medis</h4>
                    <p id="label-pasien" class="mr-hint">
                        Pilih pasien dari antrian untuk memulai...
                    </p>

                    <form class="mr-form" action="../functions/medical_record.php" method="POST">
                        <div class="mr-form-row">
                            <div class="mr-field">
                                <label for="input_id_kunjungan">ID Kunjungan</label>
                                <input type="text" id="input_id_kunjungan" name="id_kunjungan" readonly required class="input-readonly">
                            </div>
                            <div class="mr-field">
                                <label for="input_id_user">ID Dokter (Pemeriksa)</label>
                                <input type="text" id="input_id_user" name="id_user" value="<?= $_SESSION['id_user'] ?>" readonly class="input-readonly">
                            </div>
                        </div>

                        <label for="textarea-keluhan">Keluhan Pasien</label>
                        <textarea id="textarea-keluhan" name="keluhan" class="mr-textarea mr-textarea--md" placeholder="Tulis keluhan utama pasien..." required></textarea>

                        <div class="mr-form-row mr-form-row--tight">
                            <div class="mr-field mr-field--narrow">
                                <label>Kode ICD-10</label>
                                <input type="text" name="icd10_code" placeholder="Contoh: A00.0">
                            </div>
                            <div class="mr-field mr-field--wide">
                                <label>Nama Diagnosa (ICD-10 Name)</label>
                                <input type="text" name="icd10_nama" placeholder="Nama penyakit sesuai kode">
                            </div>
                        </div>
                        <textarea name="diagnosa" class="mr-textarea mr-textarea--sm" placeholder="Catatan diagnosa tambahan..."></textarea>

                        <div class="mr-form-row mr-form-row--tight">
                            <div class="mr-field mr-field--narrow">
                                <label>Kode ICD-9</label>
                                <input type="text" name="icd9_code" placeholder="Contoh: 87.44">
                            </div>
                            <div class="mr-field mr-field--wide">
                                <label>Nama Tindakan (ICD-9 Name)</label>
                                <input type="text" name="icd9_nama" placeholder="Nama prosedur/tindakan">
                            </div>
                        </div>
                        <textarea name="tindakan" class="mr-textarea mr-textarea--sm" placeholder="Detail tindakan medis yang dilakukan..."></textarea>

                        <label for="textarea-resep">Resep Obat</label>
                        <textarea id="textarea-resep" name="resep" class="mr-textarea mr-textarea--md" placeholder="Contoh: Paracetamol 500mg 3x1 hari"></textarea>

                        <button type="submit" class="btn-primary btn-primary--block-mr">
                            Simpan & Selesaikan Pemeriksaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function pilihPasien(id, nama) {
        var inputKj = document.getElementById('input_id_kunjungan');
        var label = document.getElementById('label-pasien');
        inputKj.value = id;
        label.classList.add('mr-hint--active');
        label.textContent = '';
        var t = document.createTextNode('Memproses pasien: ');
        var s = document.createElement('strong');
        s.textContent = nama;
        label.appendChild(t);
        label.appendChild(s);
        inputKj.classList.add('input-readonly--selected');
    }
    document.querySelectorAll('.js-pilih-pasien').forEach(function (btn) {
        btn.addEventListener('click', function () {
            pilihPasien(this.getAttribute('data-id'), this.getAttribute('data-nama'));
        });
    });
    </script>
</body>
</html>