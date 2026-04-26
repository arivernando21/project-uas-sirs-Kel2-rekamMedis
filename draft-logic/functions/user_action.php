<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php?pesan=terlarang");
    exit;
}

if (isset($_POST['add_user'])) {
    $nama = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    try {
        $sql = "INSERT INTO user (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $password, $nama, $role]);

        header("Location: ../views/manage_user.php?pesan=tambah_berhasil");
        exit;
    } catch (PDOException $e) {
        die("Gagal menambah user: " . $e->getMessage());
    }
}

if (isset($_POST['update_user'])) {
    $id = $_POST['id_user'];
    $nama = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    
    try {
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE user SET username = ?, password = ?, nama_lengkap = ?, role = ? WHERE id_user = ?";
            $params = [$username, $password, $nama, $role, $id];
        } else {
            $sql = "UPDATE user SET username = ?, nama_lengkap = ?, role = ? WHERE id_user = ?";
            $params = [$username, $nama, $role, $id];
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header("Location: ../views/manage_user.php?pesan=update_berhasil");
        exit;
    } catch (PDOException $e) {
        die("Gagal update user: " . $e->getMessage());
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    if ($id == $_SESSION['id_user']) {
        die("Anda tidak bisa menghapus diri sendiri!");
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM user WHERE id_user = ?");
        $stmt->execute([$id]);

        header("Location: ../views/manage_user.php?pesan=hapus_berhasil");
        exit;
    } catch (PDOException $e) {
        die("Gagal menghapus user: " . $e->getMessage());
    }
}