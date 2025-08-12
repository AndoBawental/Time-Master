<?php include 'includes/db.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeMaster - Manajemen Waktu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        /* Navbar */
        .navbar {
            transition: all 0.3s;
        }
        .navbar.scrolled {
            background-color: rgba(0, 123, 255, 0.9);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        /* Hero Section */
        .hero {
            background: linear-gradient(to right, rgba(0, 123, 255, 0.8), rgba(0, 123, 255, 0.6)), url('assets/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }
        .hero h2 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        /* Fitur */
        .feature-box {
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        /* Footer */
        footer {
            background-color: #007bff;
            color: white;
            padding: 15px 0;
        }
        .footer-links a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }
        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <img src="assets/logo.png" alt="TimeMaster Logo" width="50"> TimeMaster
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#tentangModal">Tentang Kami</a></li>
                    <li class="nav-item"><a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#kontakModal">Kontak</a></li>
                    <li class="nav-item"><button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button></li>
                    <li class="nav-item"><button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#registerModal">Daftar</button></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero text-center">
        <div class="container">
            <h2 class="fw-bold">Kelola Waktu dan Prioritas Anda dengan Mudah!</h2>
            <p class="lead">Gunakan TimeMaster untuk meningkatkan produktivitas dan mengatur jadwal Anda secara efisien.</p>
            <button class="btn btn-light btn-lg">Mulai Sekarang</button>
        </div>
    </section>

    <!-- Fitur -->
    <section class="container py-5">
        <h3 class="text-center mb-4">Fitur Utama</h3>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="feature-box p-4 border rounded shadow-sm">
                    <h4>📝 Manajemen Tugas</h4>
                    <p>Buat, edit, dan hapus tugas dengan mudah.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4 border rounded shadow-sm">
                    <h4>⏰ Pengingat</h4>
                    <p>Dapatkan notifikasi tugas sebelum deadline.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box p-4 border rounded shadow-sm">
                    <h4>📊 Analisis Waktu</h4>
                    <p>Lihat laporan penggunaan waktu untuk meningkatkan efisiensi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <p>© 2025 TimeMaster. Semua hak dilindungi.</p>
        <div class="footer-links">
            <a href="#">Facebook</a>
            <a href="#">Twitter</a>
            <a href="#">Instagram</a>
        </div>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="tentangModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tentang TimeMaster</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>TimeMaster adalah aplikasi yang dirancang untuk membantu pengguna mengelola waktu dan meningkatkan produktivitas.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kontakModal" tabindex="-1" aria-labelledby="kontakModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kontakModalLabel">Kontak Kami</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Email: support@timemaster.com</p>
                    <p>Telepon: +62 812-3456-7890</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login TimeMaster</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="auth/login.php" method="POST">
                        <input type="email" name="email" class="form-control mb-2" placeholder="Masukkan email Anda" required>
                        <input type="password" name="password" class="form-control mb-2" placeholder="Masukkan password Anda" required>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

     <!-- Modal Registrasi -->
     <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Daftar TimeMaster</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="auth/register.php" method="POST">
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password Anda" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Efek navbar saat di-scroll
        window.addEventListener("scroll", function() {
            let navbar = document.querySelector(".navbar");
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });

        // Cek parameter error di URL untuk menangani login/registrasi gagal
        const urlParams = new URLSearchParams(window.location.search);
        let errorType = urlParams.get("error");

        if (errorType) {
            let targetModal;

            if (errorType === "not_registered" || errorType === "email_exists" || errorType === "empty_fields") {
                targetModal = new bootstrap.Modal(document.getElementById('registerModal'));
            } else if (errorType === "password") {
                targetModal = new bootstrap.Modal(document.getElementById('loginModal'));
            }

            if (targetModal) {
                targetModal.show(); // Buka modal yang sesuai berdasarkan error
            }
        }
    });
</script>



</body>
</html>
