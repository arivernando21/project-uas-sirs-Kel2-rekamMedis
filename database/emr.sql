CREATE DATABASE emr;
USE emr;

-- TABEL USER
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin','dokter','perawat') NOT NULL
);

-- TABEL PASIEN
CREATE TABLE pasien (
    id_pasien INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L','P'),
    no_telp VARCHAR(20),
    alamat TEXT
);

-- TABEL KUNJUNGAN
CREATE TABLE kunjungan (
    id_kunjungan INT AUTO_INCREMENT PRIMARY KEY,
    id_pasien INT,
    tanggal_kunjungan DATE,
    status ENUM('menunggu','selesai') DEFAULT 'menunggu',

    FOREIGN KEY (id_pasien) REFERENCES pasien(id_pasien)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- TABEL REKAM MEDIS
CREATE TABLE rekam_medis (
    id_rm INT AUTO_INCREMENT PRIMARY KEY,
    id_kunjungan INT,
    id_dokter INT,

    tanggal_pemeriksaan DATETIME,

    keluhan TEXT,
    diagnosa TEXT,
    tindakan TEXT,

    icd10_code VARCHAR(10),
    icd10_nama VARCHAR(255),

    icd9_code VARCHAR(10),
    icd9_nama VARCHAR(255),

    resep TEXT,

    FOREIGN KEY (id_kunjungan) REFERENCES kunjungan(id_kunjungan)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    FOREIGN KEY (id_dokter) REFERENCES users(id_user)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
