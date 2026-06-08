<?php
include "config/koneksi.php";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanggar Seni Lentera - Halaman Utama</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary: #8B5A2B;
            /* Warna cokelat khas Lentera */
            --secondary: #E6C280;
            --dark: #2C1E11;
            --light: #FDFBF7;
        }

        body {
            background-color: var(--light);
            color: var(--dark);
        }

        /* Navbar */
        nav {
            background-color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 8%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 1px;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        nav ul li a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: var(--primary);
        }

        .btn-login {
            background-color: var(--primary);
            color: white !important;
            padding: 8px 20px;
            border-radius: 20px;
        }

        /* Hero Section */
        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 80px 8%;
            gap: 50px;
            background: linear-gradient(135deg, #FFFDF9 0%, #F5EDE0 100%);
        }

        .hero-text h1 {
            font-size: 3rem;
            color: var(--dark);
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-text h1 span {
            color: var(--primary);
        }

        .hero-text p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-jelajah {
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(139, 90, 43, 0.3);
            transition: 0.3s;
        }

        .btn-jelajah:hover {
            transform: translateY(-3px);
        }

        .hero-img img {
            width: 100%;
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        /* Program Kelas Section */
        .program {
            padding: 80px 8%;
            text-align: center;
        }

        .program h2 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }

        .program p.sub {
            color: #777;
            margin-bottom: 50px;
        }

        .grid-kelas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .card-kelas {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.02);
            transition: 0.3s;
            border: 1px solid #f0f0f0;
        }

        .card-kelas:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(139, 90, 43, 0.1);
        }

        .card-kelas i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .card-kelas h3 {
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        /* Footer */
        footer {
            background-color: var(--dark);
            color: #b5a494;
            padding: 40px 8%;
            text-align: center;
            font-size: 0.9rem;
            border-top: 5px solid var(--primary);
        }
    </style>
</head>

<body>

    <nav>
        <div class="logo"><i class="fa-solid fa-lightbulb"></i> LENTERA</div>
        <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="#profil">Profil</a></li>
            <li><a href="#kelas">Kelas Seni</a></li>
            <li><a href="#kontak">Kontak</a></li>
            <li><a href="login.php" class="btn-login"><i class="fa-solid fa-right-to-bracket"></i> Login</a></li>
        </ul>
    </nav>

    <section class="hero">
        <div class="hero-text">
            <h1>Ciptakan Kreativitas Tanpa Batas di Sanggar <span>Lentera</span></h1>
            <p>Wadah terbaik untuk mengasah bakat seni, kreativitas, dan rasa percaya diri anak bangsa bersama instruktur berpengalaman.</p>
            <a href="#kelas" class="btn-jelajah">Lihat Kelas Latihan</a>
        </div>
        <div class="hero-img">
            <img src="assets/img/aktivitas_sanggar.jpg" alt="Aktivitas Sanggar">
        </div>
    </section>

    <section class="program" id="kelas">
        <h2>Kelas Pilihan Kami</h2>
        <p class="sub">Berbagai macam bimbingan seni latihan yang bisa kamu ikuti</p>

        <div class="grid-kelas">
            <?php
            $ambil_kelas = mysqli_query($conn, "SELECT * FROM t_kelas");
            if (mysqli_num_rows($ambil_kelas) > 0) {
                while ($k = mysqli_fetch_assoc($ambil_kelas)) {
            ?>
                    <div class="card-kelas">
                        <i class="fa-solid fa-palette"></i>
                        <h3><?php echo $k['nama_kelas']; ?></h3>
                        <p style="color: #666; font-size: 0.9rem;">Mari bergabung di kelas ini dan kembangkan potensi terbaikmu setiap minggunya.</p>
                    </div>
            <?php
                }
            } else {
                echo "<p style='grid-column: 1/-1;'>Belum ada data kelas latihan.</p>";
            }
            ?>
        </div>
    </section>

    <footer>
        <section class="kontak" id="kontak" style="padding: 80px 8%; background-color: white;">
            <h2 style="text-align: center; font-size: 2.2rem; margin-bottom: 10px;">Hubungi Kami</h2>
            <p style="text-align: center; color: #777; margin-bottom: 50px;">Punya pertanyaan seputar kelas atau pendaftaran? Silakan hubungi kami</p>

            <div style="display: flex; flex-wrap: wrap; gap: 40px; justify-content: space-between;">

                <div style="flex: 1; min-width: 280px;">
                    <h3 style="margin-bottom: 25px; color: #8B5A2B; font-size: 1.4rem;">Informasi Kontak</h3>

                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <i class="fa-solid fa-location-dot" style="font-size: 1.3rem; color: #8B5A2B; width: 25px;"></i>
                        <p style="color: #555; line-height: 1.5;">Dilem, Kertosari, Kec. Singorojo, Kabupaten Kendal, Jawa Tengah 51382</p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <i class="fa-brands fa-whatsapp" style="font-size: 1.3rem; color: #8B5A2B; width: 25px;"></i>
                        <p style="color: #555;">0882-1671-2557 (Admin Sanggar)</p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <i class="fa-solid fa-envelope" style="font-size: 1.3rem; color: #8B5A2B; width: 25px;"></i>
                        <p style="color: #555;">sanggarlentera@gmail.com</p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <i class="fa-brands fa-instagram" style="font-size: 1.3rem; color: #8B5A2B; width: 25px;"></i>
                        <p style="color: #555;">@sanggar_lentera</p>
                    </div>
                </div>

                <div style="flex: 1; min-width: 300px; height: 250px; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3959.544139976722!2d110.2185542!3d-7.062725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e70776b2bfb73cb%3A0x600ef2a2977461c3!2sDilem%2C%20Kertosari%2C%20Kec.%20Singorojo%2C%20Kabupaten%20Kendal%2C%20Jawa%20Tengah!5e0!3m2!1sid!2sid!4v1717845600000!5m2!1sid!2sid"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </section>
        <p>&copy; 2026 Sanggar Seni Lentera. RPL Project. All Rights Reserved.</p>
    </footer>

</body>
<a href="https://wa.me/6288216712557?text=Halo%20Admin%20Sanggar%20Lentera,%20saya%20ingin%20tanya%20seputar%20pendaftaran%20kelas%20seni."
    target="_blank"
    style="position: fixed; bottom: 30px; right: 30px; background-color: #25D366; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; box-shadow: 2px 5px 15px rgba(0,0,0,0.2); z-index: 9999; text-decoration: none; transition: 0.3s;"
    onmouseover="this.style.transform='scale(1.1)'"
    onmouseout="this.style.transform='scale(1)'">
    <i class="fa-brands fa-whatsapp"></i>
</a>

</html>