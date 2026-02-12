<?php 
session_start();

if (!isset($_SESSION["sign_In"])) {
    header("Location: ../../sign_in/member/sign_in.php");
    exit;
}

require "../../config/config.php";
$akunMember = $_SESSION["member"]["nisn"];

// Query untuk mengambil semua data peminjaman
$dataPinjam = queryReadData("
    SELECT 
        peminjaman.id_peminjaman, 
        peminjaman.id_buku, 
        buku.judul, 
        peminjaman.nisn, 
        member.nama, 
        peminjaman.tgl_peminjaman, 
        peminjaman.tgl_pengembalian,
        peminjaman.status
    FROM peminjaman
    INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
    INNER JOIN member ON peminjaman.nisn = member.nisn
    WHERE peminjaman.nisn = $akunMember
    ORDER BY peminjaman.tgl_peminjaman DESC
");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Transaksi Peminjaman Buku || Member</title>
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
        <div class="mt-5 alert alert-primary" role="alert">
            Riwayat transaksi Peminjaman Buku Anda - <span class="fw-bold text-capitalize"><?php echo htmlentities($_SESSION["member"]["nama"]); ?></span>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-striped table-hover table-bordered table-sm text-center" style="font-size: 0.875rem;">
                <thead>
                    <tr>
                        <th class="bg-primary text-light">ID Peminjaman</th>
                        <th class="bg-primary text-light">ID Buku</th>
                        <th class="bg-primary text-light">Judul Buku</th>
                        <th class="bg-primary text-light">NIM</th>
                        <th class="bg-primary text-light">Nama</th>
                        <th class="bg-primary text-light">Tanggal Peminjaman</th>
                        <th class="bg-primary text-light">Tanggal Pengembalian</th>
                        <th class="bg-primary text-light">Status</th>
                        <th class="bg-primary text-light">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (!empty($dataPinjam)) : ?>
        <?php foreach ($dataPinjam as $item) : ?>
            <tr>
                <td><?= htmlspecialchars($item["id_peminjaman"]); ?></td>
                <td><?= htmlspecialchars($item["id_buku"]); ?></td>
                <td><?= htmlspecialchars($item["judul"]); ?></td>
                <td><?= htmlspecialchars($item["nisn"]); ?></td>
                <td><?= htmlspecialchars($item["nama"]); ?></td>
                <td><?= htmlspecialchars($item["tgl_peminjaman"]); ?></td>
                <td><?= htmlspecialchars($item["tgl_pengembalian"]) ?: "-"; ?></td>
                <td>
                    <?php 
                    if ($item["status"] == 'terkembalikan') {
                        echo "<span class='text-success'>Terkembalikan</span>";
                    } else {
                        echo "<span class='text-warning'>Belum Kembali</span>";
                    }
                    ?>
                </td>
                <td>
                    <?php if ($item["status"] != 'terkembalikan'): ?>
                        <a class="btn btn-success btn-sm" href="pengembalianBuku.php?id=<?= htmlspecialchars($item["id_peminjaman"]); ?>">Kembalikan</a>
                    <?php else: ?>
                        <span class="text-muted">Sudah Kembali</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="9" class="text-center">Tidak ada data peminjaman</td>
        </tr>
    <?php endif; ?>
</tbody>

            </table>
        </div>
    </div>
</body>
</html>
