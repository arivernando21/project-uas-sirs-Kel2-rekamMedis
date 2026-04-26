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
    <style>
        .btn-select {
            padding: 5px 10px;
            background-color: #2A7FFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-select:hover {
            background-color: #1d5ed8;
        }
    </style>
</head>
<body>
    <div class="layout">
        <div class="sidebar">
            <h3>SIRS EMR</h3>
            <a class="active">Dashboard</a>
            <a href="patients_list.php">Pasien</a>
            <div class="logout">
                <a href="../logout.php" style="color:white; text-decoration:none;">Logout</a>
            </div>
        </div>

        <div class="main">
            <div class="topbar">
                <div>
                    <h2>Dashboard Dokter</h2>
                    <p>Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?></p>
                </div>
                <div class="user">Dokter</div>
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
                                    <td colspan="4" style="text-align:center;">Tidak ada antrian aktif.</td>
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
                                    <button type="button" class="btn-select" 
                                            onclick="pilihPasien('<?= $pl['id_kunjungan'] ?>', '<?= htmlspecialchars($pl['nama']) ?>')">
                                        Pilih
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card form-card" style="flex: 1.5;"> <h4>Form Input Lengkap Rekam Medis</h4>
                    <p id="label-pasien" style="font-size: 13px; color: #666; margin-bottom: 10px;">
                        Pilih pasien dari antrian untuk memulai...
                    </p>

                    <form action="../functions/medical_record.php" method="POST">
                        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                            <div style="flex: 1;">
                                <label>ID Kunjungan</label>
                                <input type="text" id="input_id_kunjungan" name="id_kunjungan" readonly required style="background: #eee;">
                            </div>
                            <div style="flex: 1;">
                                <label>ID Dokter (Pemeriksa)</label>
                                <input type="text" name="id_user" value="<?= $_SESSION['id_user'] ?>" readonly style="background: #eee;">
                            </div>
                        </div>

                        <label>Keluhan Pasien</label>
                        <textarea name="keluhan" placeholder="Tulis keluhan utama pasien..." required style="height: 50px;"></textarea>

                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <div style="flex: 1;">
                                <label>Kode ICD-10</label>
                                <input type="text" name="icd10_code" placeholder="Contoh: A00.0">
                            </div>
                            <div style="flex: 2;">
                                <label>Nama Diagnosa (ICD-10 Name)</label>
                                <input type="text" name="icd10_nama" placeholder="Nama penyakit sesuai kode">
                            </div>
                        </div>
                        <textarea name="diagnosa" placeholder="Catatan diagnosa tambahan..." style="height: 40px; margin-top: 5px;"></textarea>

                        <div style="display: flex; gap: 10px; margin-top: 10px;">
                            <div style="flex: 1;">
                                <label>Kode ICD-9</label>
                                <input type="text" name="icd9_code" placeholder="Contoh: 87.44">
                            </div>
                            <div style="flex: 2;">
                                <label>Nama Tindakan (ICD-9 Name)</label>
                                <input type="text" name="icd9_nama" placeholder="Nama prosedur/tindakan">
                            </div>
                        </div>
                        <textarea name="tindakan" placeholder="Detail tindakan medis yang dilakukan..." style="height: 40px; margin-top: 5px;"></textarea>

                        <label style="margin-top: 10px;">Resep Obat</label>
                        <textarea name="resep" placeholder="Contoh: Paracetamol 500mg 3x1 hari" style="height: 50px;"></textarea>

                        <button type="submit" class="btn-primary" style="margin-top: 15px; width: 100%;">
                            Simpan & Selesaikan Pemeriksaan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function pilihPasien(id, nama) {
        document.getElementById('input_id_kunjungan').value = id;
        
        document.getElementById('label-pasien').innerHTML = "Memproses pasien: <strong>" + nama + "</strong>";
        
        document.getElementById('input_id_kunjungan').style.backgroundColor = "#e9f1ff";
    }
    </script>
</body>
</html>