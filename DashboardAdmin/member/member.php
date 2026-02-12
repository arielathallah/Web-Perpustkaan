    <?php
    session_start();

    if(!isset($_SESSION["signIn"]) ) {
    header("Location: ../../sign_in/admin/sign_in.php");
    exit;
    }
    require "../../config/config.php";

    $member = queryReadData("SELECT * FROM member");

    if(isset($_POST["search"]) ) {
    $member = searchMember($_POST["keyword"]);
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
        <title>Member terdaftar</title>
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
        <!--search engine --->
        <form action="" method="post" class="mt-5">
        <div class="input-group d-flex justify-content-end mb-3">
            <input class="border p-2 rounded rounded-end-0 bg-tertiary" type="text" name="keyword" id="keyword" placeholder="cari data member...">
            <button class="border border-start-0 bg-light rounded rounded-start-0" type="submit" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
        </div>
        </form>
        
        <caption>List of Member</caption>
        <div class="table-responsive mt-3">
        <table class="table table-striped table-hover table-bordered">
    <thead class="text-center">
        <tr>
            <th class="bg-primary text-light">NIM</th>
            <th class="bg-primary text-light">Nama</th>
            <th class="bg-primary text-light">Jenis Kelamin</th>
            <th class="bg-primary text-light">Fakultas</th>
            <th class="bg-primary text-light">Jurusan</th>
            <th class="bg-primary text-light">No Telepon</th>
            <th class="bg-primary text-light">Pendaftaran</th>
            <th class="bg-primary text-light">Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($member as $item) : ?>
        <tr>
            <td><?= htmlspecialchars($item["nisn"]); ?></td>
            <td><?= htmlspecialchars($item["nama"]); ?></td>
            <td><?= htmlspecialchars($item["jenis_kelamin"]); ?></td>
            <td><?= htmlspecialchars($item["Fakultas"]); ?></td>
            <td><?= htmlspecialchars($item["jurusan"]); ?></td>
            <td><?= htmlspecialchars($item["no_tlp"]); ?></td>
            <td><?= htmlspecialchars($item["tgl_pendaftaran"]); ?></td>
            <td>
                <a href="deleteMember.php?id=<?= $item["nisn"]; ?>" 
                    class="btn btn-danger btn-sm" 
                    onclick="return confirm('Yakin ingin menghapus data member ?');">
                    <i class="fa-solid fa-trash"></i> Hapus
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

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