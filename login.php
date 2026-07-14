<?php

session_start();

include 'koneksi.php';

 $pesan = "";

if (isset($_POST['btn_login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $query = "SELECT * FROM login WHERE username='$username' AND password='$password'";
    $hasil = mysqli_query($conn, $query);

    if (mysqli_num_rows($hasil) > 0) {
        // ambil data user
        $data = mysqli_fetch_assoc($hasil);

        $_SESSION['admin_id'] = $data['id'];
        $_SESSION['admin_nama'] = $data['name'];

        header("Location: dashboard.php");
        exit;
    } else {
        $pesan = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPK TOPSIS Plastik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="halaman-login">

    <div class="login-box">
        <div class="logo-login">
            <img src="logo1.png" alt="Logo" class="logo-img">
            <h3>SPK TOPSIS Plastik</h3>
            <p>Masuk untuk mengelola sistem</p>
        </div>

        <?php
        if ($pesan != "") {
            echo "<div class='pesan-error'><i class='bi bi-exclamation-circle me-2'></i>" . $pesan . "</div>";
        }
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-input" placeholder="Masukkan username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-input" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="btn_login" class="btn-simpan" style="width:100%; justify-content:center; margin-top:10px;">
                <i class="bi bi-box-arrow-in-right"></i> Masuk
            </button>
        </form>
    </div>

</body>
</html>