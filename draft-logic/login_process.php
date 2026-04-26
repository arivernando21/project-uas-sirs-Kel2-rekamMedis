<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        header("Location: index.php?pesan=kosong");
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['id_user']  = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = strtolower(trim($user['role']));
        $_SESSION['nama']     = $user['nama_lengkap'];

        session_regenerate_id(true);

        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: views/admin.php");
                break;
            case 'dokter':
                header("Location: views/doctor.php");
                break;
            case 'perawat':
                header("Location: views/nurse.php");
                break;
            default:
                session_destroy();
                header("Location: index.php?pesan=role_tidak_valid");
                break;
        }
        exit;

    } else {
        header("Location: index.php?pesan=gagal");
        exit;
    }
}