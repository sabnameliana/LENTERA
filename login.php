<?php
session_start();
include "config/koneksi.php";

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM t_admin WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);

        session_start();
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama_admin'] = $data['nama_admin'];
        $_SESSION['status'] = "login";

        header("location:index.php");
        exit();
    }
} else {
    $error = "Username atau Password salah!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Sanggar Lentera</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="login-page-wrapper">
        <div class="main-login-box">

            <div class="login-left-pane">
                <img src="assets/img/tari_logo.png" alt="Logo Sanggar">
                <h2>Sanggar</h2>
                <h2>Tari</h2>
            </div>

            <div class="login-right-pane">
                <h1>Login ke Akun Admin</h1>

                <?php if (isset($error)): ?>
                    <p style="color: #d9534f; background: #f2dede; padding: 10px; border-radius: 5px; font-size: 0.8rem; margin-bottom: 15px; border: 1px solid #ebccd1;">
                        <i class="fa-solid fa-circle-exclamation"></i> <?php echo $error; ?>
                    </p>
                <?php endif; ?>

                <form action="" method="POST" class="login-form-content">

                    <div style="margin-bottom: 18px;">
                        <label for="username" class="form-label">Username Admin</label>
                        <input type="text" class="form-control-custom" id="username" name="username" placeholder="Masukkan username" required>
                    </div>

                    <div>
                        <label for="password" class="form-label">Password</label>
                        <div class="password-input-group">
                            <input type="password" class="form-control-custom" id="password" name="password" placeholder="Masukkan password" required>
                            <i class="fa-regular fa-eye-slash toggle-password-icon" id="togglePassword"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-login">LOGIN</button>

                </form>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
            this.classList.toggle("fa-eye");
        });
    </script>

</body>

</html>