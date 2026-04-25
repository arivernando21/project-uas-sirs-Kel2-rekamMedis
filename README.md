# 🏥 Sistem Informasi Rekam Medis Elektronik (EMR)

## 📌 Deskripsi
Sistem Informasi Rekam Medis Elektronik (Electronic Medical Record - EMR) merupakan aplikasi berbasis web yang dirancang untuk membantu pengelolaan data pasien dan rekam medis secara digital. Sistem ini memungkinkan integrasi antar unit layanan seperti dokter, perawat, dan admin dalam satu platform.

---

## 🎯 Tujuan
- Mempermudah pencatatan rekam medis pasien  
- Mengurangi kesalahan pencatatan manual  
- Meningkatkan efisiensi pelayanan kesehatan  
- Menyediakan sistem yang terintegrasi antar pengguna  

---

## 👥 Role Pengguna

### 🛠️ Admin
- Mengelola data user (dokter & perawat)  
- Melihat laporan sistem  
- Monitoring data pasien  

### 🩺 Dokter
- Melihat data pasien  
- Menginput rekam medis (keluhan, diagnosa, tindakan, resep)  
- Melihat riwayat pasien  

### 🧑‍⚕️ Perawat
- Menginput data pasien  
- Mengelola data pasien  
- Membantu administrasi pasien  

---

## 🧩 Fitur Utama
- Login multi-role (Admin, Dokter, Perawat)  
- Manajemen data pasien  
- Input rekam medis  
- Dashboard sesuai role  
- Tampilan UI berbasis web (HTML + CSS + Bootstrap)  

---

## 🗂️ Struktur Project
/project-emr 
│├── login.html 
├── admin.html 
├── dokter.html 
├── perawat.html 
│├── style.css 
│├── koneksi.php 
├── login.php 
├── tambah_pasien.php 
├── tampil_pasien.php
├── tambah_rekam_medis.php 
│ └── database.sql

---

## 🛠️ Teknologi yang Digunakan

### Frontend:
- HTML  
- CSS  
- Bootstrap  

### Backend:
- PHP  

### Database:
- MySQL (phpMyAdmin)  

---

## 🗄️ Struktur Database

### Tabel User
- id  
- username  
- password  
- role  

### Tabel Pasien
- id_pasien  
- nama  
- tanggal_lahir  
- jenis_kelamin  
- no_telp  
- alamat  

### Tabel Rekam Medis
- id_rm  
- id_pasien  
- keluhan  
- diagnosa  
- tindakan  
- resep  

---

## ▶️ Cara Menjalankan Project

1. Install XAMPP / Laragon  
2. Jalankan Apache & MySQL  
3. Import database ke phpMyAdmin  
4. Letakkan project di folder `htdocs`  
5. Akses melalui browser:

---

## 📸 Tampilan Sistem
- Login Page  
- Dashboard Admin  
- Dashboard Dokter  
- Dashboard Perawat  

---

## ⚠️ Catatan
- Sistem ini merupakan prototype untuk keperluan pembelajaran  
- Belum dilengkapi dengan keamanan tingkat lanjut  

---

## 👨‍💻 Pengembang
Kelompok 2 – Sistem Informasi Rekam Medis  
1. Ari Vernando         09021282429101
2. M. Radittyo Kalin		09021282429041
3. Raihan Agasi			    09021182429003
4. Muanai K. Revindo		09021282328115
5. Dea Sucinta	Marisa	09021182429035

---

## 📄 Lisensi
Digunakan untuk keperluan akademik / pembelajaran
