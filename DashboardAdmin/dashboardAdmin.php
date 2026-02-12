<?php
session_start();

// Periksa session login
if (!isset($_SESSION["signIn"])) {
    header("Location: ../sign_in/admin/sign_in.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Admin Dashboard</title>
    <style>
        @media screen and (max-width: 600px) {
            .d-flex.flex-wrap.gap-2.cardImg a img {
                width: 200px;
            }
        }
        .cardImg {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .cardImg:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        .cardImg a:active {
            transform: scale(0.95);
        }
        .clock {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <nav class="navbar fixed-top bg-body-tertiary shadow-sm">
        <div class="container-fluid p-3">
            <a class="navbar-brand" href="#">
                <img src="../assets/p.png" alt="logo" width="120px">
            </a>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../assets/adminLogo.png" alt="adminLogo" width="40px">
                </button>
                <ul style="margin-left: -7rem;" class="dropdown-menu position-absolute mt-2 p-2">
                    <li>
                        <a class="dropdown-item text-center" href="#">
                            <img src="../assets/adminLogo.png" alt="adminLogo" width="30px">
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item text-center text-secondary" href="#">
                            <span class="text-capitalize"><?php echo $_SESSION['admin']['nama_admin']; ?></span>
                        </a>
                    </li>
                    <hr>
                    <li>
                        <a class="dropdown-item text-center mb-2" href="#">Akun Terverifikasi <span class="text-primary"><i class="fa-solid fa-circle-check"></i></span></a>
                    </li>
                    <li>
                        <a class="dropdown-item text-center p-2 bg-danger text-light rounded" href="signOut.php">Sign Out <i class="fa-solid fa-right-to-bracket"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="mt-5 p-4">
        <?php
        $day = date('l');
        $dayOfMonth = date('d');
        $month = date('F');
        $year = date('Y');
        ?>
        
        <h1 class="mt-5 fw-bold">Dashboard - <span class="fs-4 text-secondary"><?php echo $day . " " . $dayOfMonth . " " . $month . " " . $year; ?></span></h1>
        
        <!-- Menambahkan waktu di Dashboard Admin -->
        <span class="clock" id="realTimeClock"></span>
        
        <div class="alert alert-success" role="alert">Selamat datang admin - <span class="fw-bold text-capitalize"><?php echo $_SESSION['admin']['nama_admin']; ?></span> di Dashboard RielBooks</div>
        
        <div class="mt-3 p-3">
            <div class="mt-2 mb-4">
                <h3 class="mb-3">Menu Admin</h3>
            </div>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <div class="cardImg">
                    <a href="member/member.php">
                        <img src="../assets/Kartu_member/member.png" alt="daftar member" style="max-width: 100%;" width="450px">
                    </a>
                </div>
                <div class="cardImg">
                    <a href="buku/daftarBuku.php">
                        <img src="../assets/Kartu_member/bukuAdmin.png" alt="daftar buku" style="max-width: 100%;" width="450px">
                    </a>
                </div>
                <div class="cardImg">
                    <a href="peminjaman/peminjamanBuku.php">
                        <img src="../assets/Kartu_member/peminjaman.png" alt="peminjaman buku" style="max-width: 100%;" width="450px">
                    </a>
                </div>
                <div class="cardImg">
                    <a href="pengembalian/pengembalianBuku.php">
                        <img src="../assets/Kartu_member/pengembalianAdmin.png" alt="pengembalian buku" style="max-width: 100%;" width="450px">
                    </a>
                </div>
                <div class="cardImg">
                    <a href="denda/daftarDenda.php">
                        <img src="../assets/Kartu_member/denda.png" alt="daftar denda" style="max-width: 100%;" width="450px">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="shadow-lg bg-subtle p-3">
        <div class="container-fluid d-flex justify-content-between">
            <p class="mt-2">Created by <span class="text-primary">Esa Unggul</span> © 2024</p>
            <p class="mt-2">versi 1.0</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Script untuk memperbarui waktu secara real-time -->
    <script>
        // JavaScript untuk memperbarui waktu secara real-time
        function updateClock() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const formattedTime = now.toLocaleTimeString('en-US', options);
            document.getElementById('realTimeClock').textContent = formattedTime;
        }
        setInterval(updateClock, 1000);
        updateClock(); // Panggil langsung untuk menampilkan waktu segera setelah halaman dimuat
    </script>
</body>
</html>
