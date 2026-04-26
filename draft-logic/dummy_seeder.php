<?php
require_once 'config/database.php';

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE rekam_medis");
    $pdo->exec("TRUNCATE TABLE kunjungan");
    $pdo->exec("TRUNCATE TABLE pasien");
    $pdo->exec("TRUNCATE TABLE user");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $pass = password_hash('password123', PASSWORD_DEFAULT);
    
    $users = [
        ['admin', $pass, 'Andi Administrator', 'admin'],
        ['dr_budi', $pass, 'dr. Budi Santoso', 'dokter'],
        ['dr_siti', $pass, 'dr. Siti Aminah', 'dokter'],
        ['nurse_ani', $pass, 'Ani Suryani', 'perawat']
    ];

    $stmtUser = $pdo->prepare("INSERT INTO user (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
    foreach ($users as $u) $stmtUser->execute($u);

    $pasiens = [
        ['Budi Raharjo', '1985-05-20', 'Laki-laki', '08123456789', 'Jl. Merdeka No. 10'],
        ['Siti Maemunah', '1992-11-12', 'Perempuan', '08529876543', 'Jl. Melati No. 5'],
        ['Eko Prasetyo', '1978-02-28', 'Laki-laki', '08134455667', 'Jl. Sudirman No. 22'],
        ['Dewi Lestari', '2000-08-15', 'Perempuan', '08998877665', 'Jl. Mawar No. 3'],
        ['Rizky Fauzi', '1995-12-30', 'Laki-laki', '08112233445', 'Jl. Gatot Subroto No. 45']
    ];

    $stmtPasien = $pdo->prepare("INSERT INTO pasien (nama, tanggal_lahir, jenis_kelamin, no_telp, alamat) VALUES (?, ?, ?, ?, ?)");
    foreach ($pasiens as $p) $stmtPasien->execute($p);

    $stmtKunjungan = $pdo->prepare("INSERT INTO kunjungan (id_pasien, status) VALUES (?, ?)");
    $stmtKunjungan->execute([1, 'Selesai']);
    $stmtKunjungan->execute([2, 'Periksa']);
    $stmtKunjungan->execute([3, 'Menunggu']);

    $stmtRM = $pdo->prepare("INSERT INTO rekam_medis (id_kunjungan, id_user, keluhan, diagnosa, tindakan, icd10_code, icd10_nama, resep) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtRM->execute([
        1, 
        2, 
        'Demam tinggi dan pusing', 
        'Influenza Akut', 
        'Pemberian antipiretik', 
        'J11', 
        'Influenza, virus not identified', 
        'Paracetamol 500mg 3x1, Vitamin C 1x1'
    ]);

    echo "Data dummy berhasil disuntikkan ke sistem.";

} catch (PDOException $e) {
    die("Gagal menyemai data: " . $e->getMessage());
}