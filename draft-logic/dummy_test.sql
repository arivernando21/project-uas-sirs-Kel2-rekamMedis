CREATE TABLE user (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    role ENUM('admin', 'dokter', 'perawat') NOT NULL
);

CREATE TABLE pasien (
    id_pasien INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir DATE,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan'),
    no_telp VARCHAR(15),
    alamat TEXT
);

CREATE TABLE kunjungan (
    id_kunjungan INT PRIMARY KEY AUTO_INCREMENT,
    id_pasien INT,
    tanggal_kunjungan DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Menunggu', 'Periksa', 'Selesai'),
    FOREIGN KEY (id_pasien) REFERENCES pasien(id_pasien) ON DELETE CASCADE
);

CREATE TABLE rekam_medis (
    id_rm INT PRIMARY KEY AUTO_INCREMENT,
    id_kunjungan INT,
    id_user INT,
    tanggal_pemeriksaan DATETIME DEFAULT CURRENT_TIMESTAMP,
    keluhan TEXT,
    diagnosa TEXT,
    tindakan TEXT,
    icd10_code VARCHAR(10),
    icd10_nama VARCHAR(255),
    icd9_code VARCHAR(10),
    icd9_nama VARCHAR(255),
    resep TEXT,
    FOREIGN KEY (id_kunjungan) REFERENCES kunjungan(id_kunjungan) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE SET NULL
);

INSERT INTO user (username, password, nama_lengkap, role) 
VALUES ('admin', '$2y$10$BQkSNjPcXd6vBcRfUgDqK.DlNWoYEEG9dAM7OXiqLJ7y8SgVqtpVi', 'Administrator Utama', 'admin');