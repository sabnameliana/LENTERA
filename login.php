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

    <div style="display: flex; justify-content: center; align-items: center; min-vh-100; padding: 20px;">
        
        <div class="main-login-box">
            
            <div class="login-left-pane">
                <img src="assets/img/tari_logo.png" alt="Logo Sanggar">
                <h2>Sanggar</h2>
                <h2>Tari</h2>
            </div>

            <div class="login-right-pane">
                <h1>Login ke Akun Admin</h1>
                
                <form action="proses_login.php" method="POST">
                    
                    <div style="margin-bottom: 20px;">
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

        togglePassword.addEventListener("click", function () {
            // Ubah tipe input dari password ke text dan sebaliknya
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // Ubah icon mata dicoret jadi mata kebuka
            this.classList.toggle("fa-eye-slash");
            this.classList.toggle("fa-eye");
        });
    </script>
</body>
</html>