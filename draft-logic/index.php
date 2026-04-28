<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login EMR</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <!-- LEFT -->
        <div class="left">
            <div class="logo">
                <div class="icon">❤</div>
                <h1>SIRS EMR</h1>
                <p>Sistem Informasi Rekam Medis</p>
            </div>

            <p class="desc">
                Kelola data pasien, rekam medis,<br>
                dan informasi kesehatan dengan<br>
                mudah dan terintegrasi.
            </p>
        </div>

        <!-- RIGHT -->
        <div class="right">
            <div class="login-card">

                <h2>Login</h2>
                <p class="sub">Silakan login untuk melanjutkan</p>

                <form action="login_process.php" method="POST">

                    <input type="text" name="username" placeholder="Username" required>

                    <input type="password" name="password" placeholder="Password" required>

                    <button type="submit" class="btn-primary">Login</button>

                </form>

                <?php if (isset($_GET['pesan'])): ?>
                    <div class="divider">Login gagal</div>
                <?php endif; ?>

                <p class="footer">© 2024 SIRS EMR</p>

            </div>
        </div>

    </div>

</body>

</html>
