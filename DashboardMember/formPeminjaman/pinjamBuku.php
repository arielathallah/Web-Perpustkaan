<?php 
    session_start();

    if(!isset($_SESSION["sign_In"]) ) {
    header("Location: ../../sign_in/member/sign_in.php");
    exit;
    }
    require "../../config/config.php";
    // Tangkap id buku dari URL (GET)
    $idBuku = $_GET["id"];
    $query = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");
    //Menampilkan data siswa yg sedang login
    $nisnSiswa = $_SESSION["member"]["nisn"];
    $dataSiswa = queryReadData("SELECT * FROM member WHERE nisn = $nisnSiswa");

    // Peminjaman 
    if (isset($_POST["pinjam"])) {
        // Validasi: Cek apakah user memiliki denda yang belum dibayar
        $cekDenda = queryReadData("SELECT * FROM pengembalian WHERE nisn = '$nisnSiswa' AND denda > 0");
        if (count($cekDenda) > 0) {
            echo "<script>
            alert('Anda tidak dapat meminjam buku karena memiliki denda yang belum dibayar!');
            </script>";
            exit;
        }
    
        // Validasi: Cek apakah user sudah meminjam 2 buku
        $cekJumlahPinjaman = queryReadData("SELECT COUNT(*) AS jumlah FROM peminjaman WHERE nisn = '$nisnSiswa' AND status = 'dipinjam'");
        if ($cekJumlahPinjaman[0]['jumlah'] >= 2) {
            echo "<script>
            alert('Anda hanya dapat meminjam maksimal 2 buku!');
            </script>";
            exit;
        }
    
        // Jika lolos validasi, lanjutkan proses peminjaman
        if (pinjamBuku($_POST) > 0) {
            echo "<script>
            alert('Buku berhasil dipinjam');
            window.location.href = '../dashboardMember.php';
            </script>";
        } else {
            echo "<script>
            alert('Buku gagal dipinjam!');
            </script>";
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
        <title>Form pinjam Buku || Member</title>
    </head>
    <style>
        .layout-card-custom {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 1.5rem;
        }
    </style>
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
        <h2 class="mt-5">Form peminjaman Buku</h2>
        <div class="card">
        <h5 class="card-header">Data Lengkap buku</h5>
        <div class="card-body d-flex flex-wrap gap-5 justify-content-center">
            <?php foreach ($query as $item) : ?>
            <p class="card-text"><img src="../../img/<?= $item["cover"]; ?>" width="180px" height="240px" style="border-radius: 5px;"></p>
            <form action="" method="post">
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Id Buku</span>
                <input type="text" class="form-control" placeholder="id buku" aria-label="Username" aria-describedby="basic-addon1" value="<?= $item["id_buku"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Kategori</span>
                <input type="text" class="form-control" placeholder="kategori" aria-label="kategori" aria-describedby="basic-addon1" value="<?= $item["kategori"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Judul</span>
                <input type="text" class="form-control" placeholder="judul" aria-label="judul" aria-describedby="basic-addon1" value="<?= $item["judul"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Pengarang</span>
                <input type="text" class="form-control" placeholder="pengarang" aria-label="pengarang" aria-describedby="basic-addon1" value="<?= $item["pengarang"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Penerbit</span>
                <input type="text" class="form-control" placeholder="penerbit" aria-label="penerbit" aria-describedby="basic-addon1" value="<?= $item["penerbit"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Tahun Terbit</span>
                <input type="date" class="form-control" placeholder="tahun_terbit" aria-label="tahun_terbit" aria-describedby="basic-addon1" value="<?= $item["tahun_terbit"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Jumlah Halaman</span>
                <input type="number" class="form-control" placeholder="jumlah halaman" aria-label="jumlah halaman" aria-describedby="basic-addon1" value="<?= $item["jumlah_halaman"]; ?>" readonly>
                </div>
            <div class="form-floating">
                <textarea class="form-control" placeholder="deskripsi singkat buku" id="floatingTextarea2" style="height: 100px" readonly><?= $item["buku_deskripsi"]; ?></textarea>
                <label for="floatingTextarea2">Deskripsi Buku</label>
                </div>
            <?php endforeach; ?>
            </form>
        </div>
        </div>
        
        <div class="card mt-4">
        <h5 class="card-header">Data lengkap Siswa</h5>
        <div class="card-body d-flex flex-wrap gap-4 justify-content-center">
            <p><img src="../../assets/memberLogo.png" width="150px"></p>
            <form action="" method="post">
            <?php foreach ($dataSiswa as $item) : ?>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">NIM</span>
                <input type="number" class="form-control" placeholder="nisn" aria-label="nisn" aria-describedby="basic-addon1" value="<?= $item["nisn"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Nama</span>
                <input type="text" class="form-control" placeholder="nama" aria-label="nama" aria-describedby="basic-addon1" value="<?= $item["nama"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Jenis Kelamin</span>
                <input type="text" class="form-control" placeholder="jenis kelamin" aria-label="jenis kelamin" aria-describedby="basic-addon1" value="<?= $item["jenis_kelamin"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Fakultas</span>
                <input type="text" class="form-control" placeholder="Fakultas" aria-label="Fakultas" aria-describedby="basic-addon1" value="<?= $item["Fakultas"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Jurusan</span>
                <input type="text" class="form-control" placeholder="jurusan" aria-label="jurusan" aria-describedby="basic-addon1" value="<?= $item["jurusan"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">No Tlp</span>
                <input type="no_tlp" class="form-control" placeholder="no tlp" aria-label="no tlp" aria-describedby="basic-addon1" value="<?= $item["no_tlp"]; ?>" readonly>
                </div>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Tanggal Daftar</span>
                <input type="date" class="form-control" placeholder="tgl_pendaftaran" aria-label="tgl_pendaftaran" aria-describedby="basic-addon1" value="<?= $item["tgl_pendaftaran"]; ?>" readonly>
                </div>
            <?php endforeach; ?>
            </form>
        </div>
        </div>
        
        <div class="alert alert-danger mt-4" role="alert">Silahkan periksa kembali data diatas, pastikan sudah benar sebelum meminjam buku!. jika ada kesalahan data harap hubungi admin</div>
        
        <div class="card mt-4">
        <h5 class="card-header">Form Pinjam Buku</h5>
        <div class="card-body">
            <form action="" method="post">
            <!--Ambil data id buku-->
            <?php foreach ($query as $item) : ?>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">Id Buku</span>
                <input type="text" name="id_buku" class="form-control" placeholder="id buku" aria-label="id_buku" aria-describedby="basic-addon1" value="<?= $item["id_buku"]; ?>" readonly>
                </div>
            <?php endforeach; ?>
            <!--Ambil data NISN user yang login-->
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1">NIM</span>
                <input type="number" name="nisn" class="form-control" placeholder="nisn" aria-label="nisn" aria-describedby="basic-addon1" value="<?php echo htmlentities($_SESSION["member"]["nisn"]); ?>" readonly>
            </div>
            <div class="input-group mb-3 mt-3">
    <span class="input-group-text" id="basic-addon1">Tanggal Pinjam</span>
    <input type="date" name="tgl_peminjaman" id="tgl_peminjaman" class="form-control" min="<?php echo date('Y-m-d'); ?>" onchange="setReturnDate()" required>
</div>
<div class="input-group mb-3 mt-3">
    <span class="input-group-text" id="basic-addon1">Tenggat Pengembalian</span>
    <input type="date" name="tgl_pengembalian" id="tgl_pengembalian" class="form-control" readonly>
</div>

            
            <a class="btn btn-danger" href="../buku/daftarBuku.php"> Batal</a>
            <button type="submit" class="btn btn-success" name="pinjam">Pinjam</button>
            </form>
        </div>
        </div>
    
        <div class="alert alert-danger mt-4" role="alert"><span class="fw-bold">Catatan :</span> Setiap keterlambatan pada pengembalian buku akan dikenakan sanksi berupa denda.</div>
        
        </div>
        
        <footer class="shadow-lg bg-subtle p-3">
        <div class="container-fluid d-flex justify-content-between">
        <p class="mt-2">Created by <span class="text-primary"> Esa Unggul</span> © 2024</p>
        <p class="mt-2">versi 1.0</p>
        </div>
    </footer>
        
        <!--JAVASCRIPT -->
        
        <script src="../../style/js/script.js"></script>

        <script>
    function setReturnDate() {
        const tglPinjam = document.getElementById("tgl_peminjaman");
        const tglKembali = document.getElementById("tgl_pengembalian");

        if (tglPinjam.value) {
            const pinjamDate = new Date(tglPinjam.value);
            const kembaliDate = new Date(pinjamDate);
            kembaliDate.setDate(kembaliDate.getDate() + 7); // Tambahkan 7 hari
            tglKembali.value = kembaliDate.toISOString().split('T')[0];
        }
    }
</script>

        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
    </html>
