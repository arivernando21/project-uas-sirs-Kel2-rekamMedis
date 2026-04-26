<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAccess($allowed_roles) {
    if (!isset($_SESSION['id_user'])) {
        header("Location: ../index.php?pesan=belum_login");
        exit;
    }

    $user_role = strtolower($_SESSION['role'] ?? '');

    $allowed_roles = array_map('strtolower', $allowed_roles);

    if (!in_array($user_role, $allowed_roles)) {
        header("Location: ../index.php?pesan=tidak_ada_akses");
        exit;
    }
}