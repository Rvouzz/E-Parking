<?php
session_start();
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $plat = $_POST['plat_nomor'] ?? '';

  if (!$plat) {
    echo 'no_plat';
    exit;
  }

  // Cari data kendaraan yang sedang parkir
  $query = $koneksi->prepare("SELECT * FROM tbl_parking WHERE plat_nomor = ? AND status = 'In' LIMIT 1");
  $query->bind_param("s", $plat);
  $query->execute();
  $result = $query->get_result();

  if ($result->num_rows === 0) {
    echo 'not_found';
    exit;
  }

  $data = $result->fetch_assoc();

  // Hitung durasi dan total pembayaran
  date_default_timezone_set('Asia/Jakarta');
  $time_out = date('Y-m-d H:i:s');
  $time_in = $data['time_in'];
  $start = strtotime($time_in);
  $end = strtotime($time_out);
  $jam_total = ceil(($end - $start) / 3600);

  $jenis = $data['jenis_kendaraan'];
  $lokasi = $data['lokasi_parkir'];

  // Ambil tarif
  $tarif_result = mysqli_query($koneksi, "SELECT tarif_awal, tarif_per_jam FROM tbl_kendaraan WHERE jenis = '$jenis' LIMIT 1");
  $tarif = mysqli_fetch_assoc($tarif_result);
  $tarif_awal = $tarif['tarif_awal'];
  $tarif_per_jam = $tarif['tarif_per_jam'];

  $total = ($jam_total <= 1) ? $tarif_awal : $tarif_awal + ($jam_total - 1) * $tarif_per_jam;

  // Masukkan ke tbl_history
  $insert = $koneksi->prepare("INSERT INTO tbl_history (plat_nomor, time_in, time_out, jenis_kendaraan, lokasi_parkir, total_pembayaran) VALUES (?, ?, ?, ?, ?, ?)");
  $insert->bind_param("sssssd", $data['plat_nomor'], $time_in, $time_out, $jenis, $lokasi, $total);
  $insert->execute();

  // Hapus dari tbl_parking
  $delete = $koneksi->prepare("DELETE FROM tbl_parking WHERE id_parking = ?");
  $delete->bind_param("i", $data['id_parking']);
  $delete->execute();

  // Hapus session
  unset($_SESSION['plat_nomor']);

  echo 'success';
}
?>