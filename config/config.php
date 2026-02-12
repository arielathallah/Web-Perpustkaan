<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database_name = "library2";
$connection = mysqli_connect($host, $username, $password, $database_name);

// === FUNCTION KHUSUS ADMIN START ===

// MENAMPILKAN DATA KATEGORI BUKU
function queryReadData($query) {
    global $connection; // Pastikan ada koneksi $conn
    $result = mysqli_query($connection, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}


// Menambahkan data buku 
function tambahBuku($dataBuku) {
    global $connection;
    
    $cover = upload();
    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $kategoriBuku = $dataBuku["kategori"];
    $judulBuku = htmlspecialchars($dataBuku["judul"]);
    $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
    $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
    $tahunTerbit = $dataBuku["tahun_terbit"];
    $jumlahHalaman = $dataBuku["jumlah_halaman"];
    $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);
    
    if(!$cover) {
        return 0;
    } 
    
    $queryInsertDataBuku = "INSERT INTO buku VALUES('$cover', '$idBuku', '$kategoriBuku', '$judulBuku', '$pengarangBuku', '$penerbitBuku', '$tahunTerbit', $jumlahHalaman, '$deskripsiBuku')";
    
    mysqli_query($connection, $queryInsertDataBuku);
    return mysqli_affected_rows($connection);
}       

// Function upload gambar 
function upload() {
    $namaFile = $_FILES["cover"]["name"];
    $ukuranFile = $_FILES["cover"]["size"];
    $error = $_FILES["cover"]["error"];
    $tmpName = $_FILES["cover"]["tmp_name"];
    
    // cek apakah ada gambar yg diupload
    if($error === 4) {
        echo "<script>
        alert('Silahkan upload cover buku terlebih dahulu!')
        </script>";
        return 0;
    }
    
    // cek kesesuaian format gambar
    $formatGambarValid = ["jpg", "jpeg", "png", "svg", "bmp", "psd", "tiff"];
    $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    
    if(!in_array($ekstensiGambar, $formatGambarValid)) {
        echo "<script>
        alert('Format file tidak sesuai');
        </script>";
        return 0;
    }
    
    // batas ukuran file
    if($ukuranFile > 2000000) {
        echo "<script>
        alert('Ukuran file terlalu besar!');
        </script>";
        return 0;
    }
    
    //generate nama file baru, agar nama file tdk ada yg sama
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    move_uploaded_file($tmpName, '../../img/' . $namaFileBaru);
    return $namaFileBaru;
} 

// MENAMPILKAN SESUATU SESUAI DENGAN INPUTAN USER PADA * SEARCH ENGINE *
function search($keyword) {
    $querySearch = "SELECT * FROM buku WHERE judul LIKE '%$keyword%' OR kategori LIKE '%$keyword%'";
    return queryReadData($querySearch);
}

function searchMember ($keyword) {
    $searchMember = "SELECT * FROM member WHERE nisn LIKE '%$keyword%' OR nama LIKE '%$keyword%' OR jurusan LIKE '%$keyword%'";
    return queryReadData($searchMember);
}

// DELETE DATA Buku
function delete($bukuId) {
    global $connection;
    $queryDeleteBuku = "DELETE FROM buku WHERE id_buku = '$bukuId'";
    mysqli_query($connection, $queryDeleteBuku);
    return mysqli_affected_rows($connection);
}

// UPDATE || EDIT DATA BUKU 
function updateBuku($dataBuku) {
    global $connection;

    $gambarLama = htmlspecialchars($dataBuku["coverLama"]);
    $idBuku = htmlspecialchars($dataBuku["id_buku"]);
    $kategoriBuku = $dataBuku["kategori"];
    $judulBuku = htmlspecialchars($dataBuku["judul"]);
    $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
    $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
    $tahunTerbit = $dataBuku["tahun_terbit"];
    $jumlahHalaman = $dataBuku["jumlah_halaman"];
    $deskripsiBuku = htmlspecialchars($dataBuku["buku_deskripsi"]);
    
    $cover = ($_FILES["cover"]["error"] === 4) ? $gambarLama : upload();
    
    $queryUpdate = "UPDATE buku SET 
        cover = '$cover',
        id_buku = '$idBuku',
        kategori = '$kategoriBuku',
        judul = '$judulBuku',
        pengarang = '$pengarangBuku',
        penerbit = '$penerbitBuku',
        tahun_terbit = '$tahunTerbit',
        jumlah_halaman = $jumlahHalaman,
        buku_deskripsi = '$deskripsiBuku'
        WHERE id_buku = '$idBuku'";
    
    mysqli_query($connection, $queryUpdate);
    return mysqli_affected_rows($connection);
}

// Hapus member yang terdaftar
function deleteMember($nisnMember) {
    global $connection;
    $deleteMember = "DELETE FROM member WHERE nisn = $nisnMember";
    mysqli_query($connection, $deleteMember);
    return mysqli_affected_rows($connection);
}

// === FUNCTION KHUSUS MEMBER START ===
// Peminjaman BUKU
function pinjamBuku($dataBuku) {
    global $connection;

    $idBuku = $dataBuku["id_buku"];
    $nisn = $dataBuku["nisn"];
    $tglPinjam = $dataBuku["tgl_peminjaman"];
    $tglKembali = $dataBuku["tgl_pengembalian"];

    if (empty($idBuku) || empty($nisn) || empty($tglPinjam) || empty($tglKembali)) {
        echo "<script>alert('Data tidak lengkap, peminjaman gagal!');</script>";
        return 0;
    }

    $queryPinjam = "INSERT INTO peminjaman (id_peminjaman, id_buku, nisn, tgl_peminjaman, tgl_pengembalian) 
                    VALUES (NULL, ?, ?, ?, ?)";
    $stmt = $connection->prepare($queryPinjam);
    if (!$stmt) {
        die("Error: " . $connection->error);
    }
    $stmt->bind_param("siss", $idBuku, $nisn, $tglPinjam, $tglKembali);
    $stmt->execute();

    return $stmt->affected_rows;
}

// Pengembalian BUKU
function pengembalian($dataBuku) {
    global $connection;

    $idPeminjaman = $dataBuku["id_peminjaman"];
    $idBuku = $dataBuku["id_buku"];
    $nisn = $dataBuku["nisn"];
    $tglPengembalian = $dataBuku["tgl_pengembalian"];
    $bukuKembali = $dataBuku["buku_kembali"];
    $dendaPerHari = 10000;

    if (empty($idPeminjaman) || empty($idBuku) || empty($nisn) || empty($bukuKembali)) {
        echo "<script>alert('Data tidak lengkap, pengembalian gagal!');</script>";
        return 0;
    }

    $selisihWaktu = strtotime($bukuKembali) - strtotime($tglPengembalian);
    $keterlambatanHari = max(0, ceil($selisihWaktu / (60 * 60 * 24)));

    $keterlambatan = ($keterlambatanHari > 0) ? "IYA" : "TIDAK";
    $denda = $keterlambatanHari * $dendaPerHari;

    $hapusQuery = "DELETE FROM peminjaman WHERE id_peminjaman = ?";
    $hapusStmt = $connection->prepare($hapusQuery);
    $hapusStmt->bind_param("i", $idPeminjaman);
    $hapusStmt->execute();

    $queryPengembalian = "INSERT INTO pengembalian (id_peminjaman, id_buku, nisn, buku_kembali, keterlambatan, denda)
                        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($queryPengembalian);
    if (!$stmt) {
        die("Error: " . $connection->error);
    }
    $stmt->bind_param("issssd", $idPeminjaman, $idBuku, $nisn, $bukuKembali, $keterlambatan, $denda);
    $stmt->execute();

    return $stmt->affected_rows;
}
// Fungsi untuk menghapus data pengembalian
function deleteDataPengembalian($id_pengembalian) {
    global $connection; // Pastikan $conn adalah koneksi ke database Anda

    // Query untuk menghapus data berdasarkan ID pengembalian
    $query = "DELETE FROM pengembalian WHERE id_pengembalian = $id_pengembalian";
    
    // Eksekusi query dan kembalikan jumlah baris yang terpengaruh
    mysqli_query($connection, $query);
    
    return mysqli_affected_rows($connection); // Mengembalikan jumlah baris yang terpengaruh (>=1 jika berhasil)
}

function bayarDenda($data) {
    global $connection;
    $id_pengembalian = $data["id_pengembalian"];
    $denda = $data["denda"];

    // Query untuk memperbarui status pembayaran denda
    $query = "UPDATE pengembalian SET status_bayar = 1, denda = 0 WHERE id_pengembalian = $id_pengembalian";
    
    // Eksekusi query
    $result = mysqli_query($connection, $query);
    
    if ($result) {
        return mysqli_affected_rows($connection);  // Mengembalikan jumlah baris yang terpengaruh
    } else {
        return 0;  // Jika gagal, mengembalikan 0
    }
    $query = "UPDATE pengembalian SET denda = ? WHERE id_pengembalian = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("di", $calculate, $idPengembalian);
    $stmt->execute();

    return $stmt->affected_rows;
}

?>
