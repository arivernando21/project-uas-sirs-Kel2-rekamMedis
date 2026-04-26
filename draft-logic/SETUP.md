## Setup & Instalasi
**Laragon** atau XAMPP dengan **PHP 8.0+**.

### 1. Konfigurasi Project Directory (Junction)

Jalankan di **Command Prompt (Run as Administrator)**:

```bash
mklink /J "C:\laragon\www\sirs-project" "C:\Workspace\projects\web\project-uas-sirs-Kel2-rekamMedis"
```

Akses di browser:
```bash
http://localhost/sirs-project/draft-logic/
```

### 2. Setup Database

Buka phpMyAdmin, buat database:
```bash
db_emr
```
Sesuaikan konfigurasi di:
```bash
draft-logic/config/database.php
```
```bash
$db   = 'db_emr';
$user = 'root';
$pass = '';
```

### 3. Import Database

Gunakan file:
```bash
dummy_test.sql
```
Cara import:
- Buka phpMyAdmin
- Pilih database db_emr
- Klik tab Import
- Upload file dummy_test.sql
- Klik Go / Import

### 4. Seeder

Jalankan:
```bash
http://localhost/sirs-project/draft-logic/dummy_seeder.php
```