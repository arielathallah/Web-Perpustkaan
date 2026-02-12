
    <?php 
    //Halaman pengelolaan pengembalian Buku Perustakaaan
    require "../../config/config.php";
    $dataPeminjam = queryReadData("SELECT pengembalian.id_pengembalian, pengembalian.id_buku, buku.judul, buku.kategori, pengembalian.nisn, member.nama, member.Fakultas, member.jurusan, pengembalian.buku_kembali, pengembalian.keterlambatan, pengembalian.denda
    FROM pengembalian
    INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
    INNER JOIN member ON pengembalian.nisn = member.nisn")
    
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
        <title>Kelola pengembalian buku || admin</title>
    </head>
    <body>
        
        <nav class="navbar fixed-top bg-body-tertiary shadow-sm">
        <div class="container-fluid p-3">
            <a class="navbar-brand" href="#">
            <img src="../../assets/p.png" alt="logo" width="120px">
            </a>
            
            <a class="btn btn-tertiary" href="../dashboardAdmin.php">Dashboard</a>
        </div>
        </nav>
        
        <div class="p-4 mt-5">
        <div class="mt-5">
        <caption>List of pengembalian</caption>
        <div class="table-responsive mt-3">
        <table class="table table-striped table-hover table-bordered table-sm text-center" style="font-size: 0.875rem;">
    <thead>
        <tr>
            <th class="bg-primary text-light">ID Pengembalian</th>
            <th class="bg-primary text-light">ID Buku</th>
            <th class="bg-primary text-light">Judul Buku</th>
            <th class="bg-primary text-light">Kategori</th>
            <th class="bg-primary text-light">NIM</th>
            <th class="bg-primary text-light">Nama Mahasiswa</th>
            <th class="bg-primary text-light">Fakultas</th>
            <th class="bg-primary text-light">Jurusan</th>
            <th class="bg-primary text-light">Tanggal Pengembalian</th>
            <th class="bg-primary text-light">Keterlambatan</th>
            <th class="bg-primary text-light">Denda</th>
            <th class="bg-primary text-light">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dataPeminjam as $item) : ?>
        <tr>
            <td><?= htmlspecialchars($item["id_pengembalian"]); ?></td>
            <td><?= htmlspecialchars($item["id_buku"]); ?></td>
            <td><?= htmlspecialchars($item["judul"]); ?></td>
            <td><?= htmlspecialchars($item["kategori"]); ?></td>
            <td><?= htmlspecialchars($item["nisn"]); ?></td>
            <td><?= htmlspecialchars($item["nama"]); ?></td>
            <td><?= htmlspecialchars($item["Fakultas"]); ?></td>
            <td><?= htmlspecialchars($item["jurusan"]); ?></td>
            <td><?= htmlspecialchars($item["buku_kembali"]); ?></td>
            <td><?= htmlspecialchars($item["keterlambatan"]); ?></td>
            <td><?= htmlspecialchars($item["denda"]); ?></td>
            <td>
                <div class="action">
                    <a href="deletePengembalian.php?id=<?= htmlspecialchars($item["id_pengembalian"]); ?>" 
                        class="btn btn-danger btn-sm" 
                        onclick="return confirm('Yakin ingin menghapus data ?');">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    </div>
    </div>
    </div>
        
    <footer class="fixed-bottom shadow-lg bg-subtle p-3">
        <div class="container-fluid d-flex justify-content-between">
        <p class="mt-2">Created by <span class="text-primary"> Esa Unggul</span> © 2024</p>
        <p class="mt-2">versi 1.0</p>
        </div>
        </footer>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
    </html>
