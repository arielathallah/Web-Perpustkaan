    <?php 
    session_start();

    if(!isset($_SESSION["sign_In"]) ) {
    header("Location: ../../sign_in/member/sign_in.php");
    exit;
    }
    require "../../config/config.php";
    $idPeminjaman = $_GET["id"];
    $query = queryReadData("SELECT peminjaman.id_peminjaman, peminjaman.id_buku, buku.judul, peminjaman.nisn, member.nama, peminjaman.tgl_peminjaman, peminjaman.tgl_pengembalian
    FROM peminjaman
    INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
    INNER JOIN member ON peminjaman.nisn = member.nisn
    WHERE peminjaman.id_peminjaman = $idPeminjaman");

    // Jika tombol submit kembalikan diklik
    if(isset($_POST["kembalikan"]) ) {
    
    if(pengembalian($_POST) > 0) {
        echo "<script>
        alert('Terimakasih telah mengembalikan buku!');
        </script>";
    }else 
        echo "<script>
        alert('Buku gagal dikembalikan');
        </script>";
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
        <title>Form Pengembalian Buku || Member</title>
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
        <div class="card p-3 mt-5">
        <form action="" method="post">
        <h3>Form Pengembalian buku</h3>
        <?php foreach ($query as $item) : ?>
        <div class="mt-3 d-flex flex-wrap gap-3">
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Id Peminjaman</label>
        <input type="number" class="form-control" placeholder="id peminjaman" name="id_peminjaman" id="id_peminjaman" value="<?= $item["id_peminjaman"]; ?>" readonly>
        </div>
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Id Buku</label>
        <input type="text" class="form-control" placeholder="id peminjaman" name="id_buku" id="id_buku" value="<?= $item["id_buku"]; ?>" readonly>
        </div>
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Judul Buku</label>
        <input type="text" class="form-control" placeholder="Judul Buku" name="judul" id="judul" value="<?= $item["judul"]; ?>" readonly>
        </div>
        </div>
        
    <div class="d-flex flex-wrap gap-3">
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">NIM Mahasiswa</label>
        <input type="number" class="form-control" placeholder="Nisn Siswa" name="nisn" id="nisn" value="<?= $item["nisn"]; ?>" readonly>
        </div>
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Nama Mahasiswa</label>
        <input type="text" class="form-control" placeholder="Nama Siswa" name="nama" id="nama" value=" <?= $item["nama"]; ?>" readonly>
        </div>
    </div>
    
    <div class="d-flex flex-wrap gap-4">
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Tanggal Buku Dipinjam</label>
        <input type="date" class="form-control" name="tgl_peminjaman" id="tgl_peminjaman" value="<?= $item["tgl_peminjaman"]; ?>" readonly>
        </div>
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Tenggat Pengembalian Buku</label>
        <input type="date" class="form-control" name="tgl_pengembalian" id="tgl_pengembalian" value="<?= $item["tgl_pengembalian"]; ?>"  oninput="hitungDenda()" readonly>
        </div>
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Hari Pengembalian Buku</label>
        <input type="date" class="form-control" name="buku_kembali" id="buku_kembali" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>" oninput="hitungDenda()"> 
    </div>

    </div>
    <?php endforeach; ?> 
    
    <div class="d-flex flex-wrap gap-4">
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Keterlambatan</label>
        <input type="text" class="form-control" name="keterlambatan" id="keterlambatan" oninput="hitungDenda()" readonly>
        </div>
        <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Denda</label>
        <input type="number" class="form-control" name="denda" id="denda" readonly>
        </div>
    </div>
    <a class="btn btn-danger" href="TransaksiPeminjaman.php"> Batal</a>
    <button type="submit" class="btn btn-success" name="kembalikan">Kembalikan</button>
        </form>
        </div>
    </div>
        
        <!--JAVASCRIPT -->
        <script src="../../style/js/script.js"></script>
        <script>
    function hitungDenda() {
        const tglPengembalian = document.getElementById("tgl_pengembalian").value; // Tanggal jatuh tempo
        const tglKembali = document.getElementById("buku_kembali").value; // Tanggal aktual pengembalian
        const keterlambatanInput = document.getElementById("keterlambatan");
        const dendaInput = document.getElementById("denda");

        // Periksa apakah kedua tanggal telah diisi
        if (tglPengembalian && tglKembali) {
            const datePengembalian = new Date(tglPengembalian);
            const dateKembali = new Date(tglKembali);

            // Hitung selisih dalam milidetik, lalu konversi ke hari
            const selisihWaktu = dateKembali - datePengembalian;
            const hariTerlambat = Math.max(0, Math.ceil(selisihWaktu / (1000 * 60 * 60 * 24))); // Maksimal 0 jika tidak terlambat

            // Tentukan denda per hari
            const dendaPerHari = 10000; // Anda dapat mengubah ini sesuai kebutuhan
            const totalDenda = hariTerlambat * dendaPerHari;

            // Tampilkan hasil perhitungan
            keterlambatanInput.value = hariTerlambat > 0 ? hariTerlambat + " hari" : "Tidak terlambat";
            dendaInput.value = totalDenda;
        } else {
            // Reset jika salah satu tanggal kosong
            keterlambatanInput.value = "";
            dendaInput.value = "";
        }
    }
</script>

        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
    </html>