<?php 
session_start();

if(!isset($_SESSION["sign_In"])) {
  header("Location: ../../sign_in/member/sign_in.php");
  exit;
}
require "../../config/config.php";

if(isset($_POST["bayar"])) {
  // Ambil nilai denda langsung dari database
  $id_pengembalian = $_POST["id_pengembalian"];
  $denda = queryReadData("SELECT denda FROM pengembalian WHERE id_pengembalian = $id_pengembalian")[0]["denda"];
  
  // Lanjutkan ke proses bayar denda
  if(bayarDenda(["id_pengembalian" => $id_pengembalian, "denda" => $denda]) > 0) {
    echo "<script>
    alert('Denda berhasil dibayar');
    document.location.href = 'TransaksiDenda.php';
    </script>";
  } else {
    echo "<script>
    alert('Denda gagal dibayar');
    </script>";
  }
}

error_reporting(E_ALL);
ini_set('display_errors', 1);


$dendaSiswa = $_GET["id"];
$query = queryReadData("SELECT 
        pengembalian.id_pengembalian, 
        buku.judul, 
        member.nama, 
        pengembalian.buku_kembali, 
        pengembalian.keterlambatan, 
        pengembalian.denda, 
        peminjaman.tgl_peminjaman
    FROM pengembalian
    LEFT JOIN buku ON pengembalian.id_buku = buku.id_buku
    LEFT JOIN member ON pengembalian.nisn = member.nisn
    LEFT JOIN peminjaman ON pengembalian.id_peminjaman = peminjaman.id_peminjaman
    WHERE pengembalian.id_pengembalian = $dendaSiswa
");

if (!$query) {
  die("Query Error: " . mysqli_error($conn));
} else if (empty($query)) {
  die("Data tidak ditemukan untuk ID: $dendaSiswa");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Form Bayar Denda || Member</title>
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
  <div class="mt-5 card p-3 mb-5">
    <form action="" method="post">
      <h3>Form Bayar Denda</h3>
      <?php foreach ($query as $item) : ?>
        <input type="hidden" name="id_pengembalian" id="id_pengembalian" value="<?= $item["id_pengembalian"]; ?>">

    <div class="mt-4 mb-3">
      <label for="exampleFormControlInput1" class="form-label">Nama</label>
      <input type="text" class="form-control" placeholder="Nama siswa" name="nama" id="nama" value="<?= $item["nama"]; ?>" readonly>
    </div>

    <div class="d-flex flex-wrap gap-4">
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Buku yang Dipinjam</label>
        <input type="text" class="form-control" placeholder="Judul Buku" name="judul" id="judul" value="<?= $item["judul"]; ?>" readonly>
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Tanggal Dikembalikan</label>
        <input type="date" class="form-control" name="buku_kembali" id="buku_kembali" value="<?= $item["buku_kembali"]; ?>" readonly>
      </div>
      <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Besar Denda</label>
        <input type="text" class="form-control" name="denda" id="denda" value="<?= number_format($item["denda"], 0, ',', '.'); ?>" readonly>
      </div>
    </div>
      <?php endforeach; ?>
      
      <!-- Tambahkan Metode Pembayaran -->
      <div class="mb-3">
        <label for="paymentMethod" class="form-label">Pilih Metode Pembayaran</label>
        <select class="form-select" name="paymentMethod" id="paymentMethod" required onchange="showRekening()">
          <option value="" disabled selected>Pilih Metode</option>
          <option value="BRI">Bank BRI</option>
          <option value="BCA">Bank BCA</option>
          <option value="BNI">Bank BNI</option>
          <option value="VA">Virtual Account DANA ke BCA</option>
        </select>
      </div>
      
      <!-- Nomor Rekening Dinamis -->
      <div class="mb-3" id="rekeningContainer" style="display: none;">
        <label for="rekeningNumber" class="form-label">Nomor Rekening</label>
        <input type="text" class="form-control" id="rekeningNumber" readonly>
      </div>
          
      <button type="reset" class="btn btn-warning text-light">Reset</button>
      <button type="submit" class="btn btn-success" name="bayar">Bayar</button>
    </form>
  </div>
</div>
  
<footer class="fixed-bottom mt-5 shadow-lg bg-subtle p-3">
  <div class="container-fluid d-flex justify-content-between">
    <p class="mt-2">Created by <span class="text-primary"> Esa Unggul</span> © 2024</p>
    <p class="mt-2">versi 1.0</p>
  </div>
</footer>

<script>
  function showRekening() {
    const paymentMethod = document.getElementById("paymentMethod").value;
    const rekeningContainer = document.getElementById("rekeningContainer");
    const rekeningNumber = document.getElementById("rekeningNumber");
    
    let rekening = "";
    if (paymentMethod === "BRI") {
      rekening = "057601003795508";
    } else if (paymentMethod === "BCA") {
      rekening = "78806350373";
    } else if (paymentMethod === "BNI") {
      rekening = "0265239763";
    } else if (paymentMethod === "VA") {
      rekening = "3901089652935248";
    }

    if (rekening) {
      rekeningContainer.style.display = "block";
      rekeningNumber.value = rekening;
    } else {
      rekeningContainer.style.display = "none";
      rekeningNumber.value = "";
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
