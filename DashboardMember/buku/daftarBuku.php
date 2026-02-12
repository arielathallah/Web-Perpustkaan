<?php
session_start();

if (!isset($_SESSION["sign_In"])) {
    header("Location: ../../sign_in/member/sign_in.php");
    exit;
}

require "../../config/config.php";
// query read semua buku
$buku = queryReadData("SELECT * FROM buku");
// search buku
if (isset($_POST["search"])) {
    $buku = search($_POST["keyword"]);
}
// read buku berdasarkan kategori
$categories = ["informatika", "bisnis", "filsafat", "novel", "sains"];
foreach ($categories as $category) {
    if (isset($_POST[$category])) {
        $buku = queryReadData("SELECT * FROM buku WHERE kategori = '$category'");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Daftar Buku || Member</title>
    <style>
        body {
            background-color: #f5f7fa;
        }

        .btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:active {
            transform: scale(0.95);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .layout-card-custom {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
        }

        .card-img-top {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05); /* Perbesar gambar sedikit */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan tipis */
        }

        .card-title {
            font-size: 1rem;
            font-weight: bold;
        }

        .card-category {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <nav class="navbar fixed-top bg-body-tertiary shadow-sm">
        <div class="container-fluid p-3">
            <a class="navbar-brand" href="#">
                <img src="../../assets/p.png" alt="logo" width="120px">
            </a>
            <a class="btn btn-tertiary" href="../dashboardMember.php">Dashboard</a>
        </div>
    </nav>
    
    <div class="p-4 mt-5">
        <!-- Btn filter data kategori buku -->
        <div class="d-flex gap-2 mt-5 justify-content-center">
            <form action="" method="post">
                <div class="layout-card-custom">
                    <button class="btn btn-primary" type="submit">Semua</button>
                    <?php foreach ($categories as $category): ?>
                        <button type="submit" name="<?= $category ?>" class="btn btn-outline-primary text-capitalize">
                            <?= $category ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            </form>
        </div>

        <form action="" method="post" class="mt-5">
            <div class="input-group d-flex justify-content-end mb-3">
                <input class="border p-2 rounded rounded-end-0 bg-tertiary" type="text" name="keyword" id="keyword" placeholder="cari buku">
                <button class="border border-start-0 bg-light rounded rounded-start-0" type="submit" name="search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </form>
        
        <!-- Card buku -->
        <div class="layout-card-custom">
            <?php foreach ($buku as $item): ?>
                <div class="card" style="width: 15rem;">
                    <!-- Gambar buku dengan tautan -->
                    <a href="detailBuku.php?id=<?= $item["id_buku"]; ?>">
                        <img src="../../img/<?= $item["cover"]; ?>" class="card-img-top" alt="coverBuku" height="300px">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title"><?= $item["judul"]; ?></h5>
                        <p class="card-category">kategori: <?= htmlspecialchars($item["kategori"]); ?></p>
                    </div>
                    <div class="card-body text-center">
                        <a class="btn btn-success w-100" href="detailBuku.php?id=<?= $item["id_buku"]; ?>">Detail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <footer class="shadow-lg bg-subtle p-3">
        <div class="container-fluid d-flex justify-content-between">
        <p class="mt-2">Created by <span class="text-primary"> Esa Unggul</span> © 2024</p>
        <p class="mt-2">versi 1.0</p>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
