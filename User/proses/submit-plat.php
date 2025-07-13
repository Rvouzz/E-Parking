<?php
session_start();
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $plat = trim($_POST['plat_nomor']);
  $jenis = $_POST['jenis_kendaraan'];

  $plat = strtoupper($plat); // pastikan uppercase agar konsisten

  $stmt = $koneksi->prepare("SELECT id_parking FROM tbl_parking WHERE plat_nomor = ? AND status = 'in'");
  $stmt->bind_param("s", $plat);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['plat_nomor'] = $plat; // 🟢 Set session meskipun sudah ada
    echo 'exists';
  } else {
    $now = date('Y-m-d H:i:s');
    $status = 'In';
    $lokasi = 'Area A'; // default area

    $insert = $koneksi->prepare("INSERT INTO tbl_parking (plat_nomor, time_in, jenis_kendaraan, lokasi_parkir, status) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("sssss", $plat, $now, $jenis, $lokasi, $status);

    if ($insert->execute()) {
      $_SESSION['plat_nomor'] = $plat; // 🟢 Set session setelah insert berhasil
      echo 'inserted';
    } else {
      echo 'error';
    }
  }
}
?>