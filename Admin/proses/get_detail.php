<?php
include '../../connection.php';

if (!isset($_GET['coordinate'])) {
  echo "Coordinate not provided.";
  exit();
}

$coordinate = $_GET['coordinate'];

// Ambil lokasi_parkir dari koordinat
$query_area = mysqli_query($koneksi, "SELECT lokasi_parkir FROM tbl_area WHERE koordinat = '$coordinate'");
if (!$query_area || mysqli_num_rows($query_area) === 0) {
  echo "Area not found for coordinate: $coordinate";
  exit();
}

$area = mysqli_fetch_assoc($query_area);
$lokasi_parkir = $area['lokasi_parkir'];

// Ambil data kendaraan yang sedang parkir di lokasi tersebut
$query_parking = mysqli_query($koneksi, "
  SELECT * FROM tbl_parking 
  WHERE lokasi_parkir = '$lokasi_parkir' AND status = 'In'
  ORDER BY time_in DESC LIMIT 1
");

if (!$query_parking || mysqli_num_rows($query_parking) === 0) {
  echo "No vehicle currently parked in this area.";
  exit();
}

$parkir = mysqli_fetch_assoc($query_parking);
$plat = $parkir['plat_nomor'];
$jenis = $parkir['jenis_kendaraan'];
$time_in = $parkir['time_in'];
$now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
$time_now = $now->format('Y-m-d H:i:s');

// Hitung durasi parkir
$start = strtotime($time_in);
$end = strtotime($time_now);
$diff = $end - $start;
$jam_total = ceil($diff / 3600); // dibulatkan ke atas

// Ambil tarif dari jenis kendaraan
$query_tarif = mysqli_query($koneksi, "SELECT * FROM tbl_kendaraan WHERE jenis = '$jenis' LIMIT 1");
if (!$query_tarif || mysqli_num_rows($query_tarif) === 0) {
  echo "Tariff data not found for vehicle type: $jenis";
  exit();
}

$kendaraan = mysqli_fetch_assoc($query_tarif);
$tarif_awal = $kendaraan['tarif_awal'];
$tarif_per_jam = $kendaraan['tarif_per_jam'];
$mata_uang = $kendaraan['mata_uang'];

// Hitung tarif total
if ($jam_total <= 1) {
  $total_tarif = $tarif_awal;
} else {
  $total_tarif = $tarif_awal + ($jam_total - 1) * $tarif_per_jam;
}

// Format tanggal lokal
function format_waktu($datetime)
{
  $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

  $timestamp = strtotime($datetime);
  $hari = $days[date('w', $timestamp)];
  $tgl = date('d', $timestamp);
  $bulan = $months[date('n', $timestamp)];
  $tahun = date('Y', $timestamp);
  $jam = date('H:i', $timestamp);

  return "$hari, $tgl $bulan $tahun $jam WIB";
}


// Tampilan HTML
echo '
<div class="card shadow-sm border-0">
  <div class="card-body">
    <ul class="list-group list-group-flush">
      <li class="list-group-item d-flex justify-content-between">
        <strong>Plat Nomor:</strong> <span>' . htmlspecialchars($plat) . '</span>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <strong>Jenis Kendaraan:</strong> <span>' . htmlspecialchars($jenis) . '</span>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <strong>Lokasi Parkir:</strong> <span>' . htmlspecialchars($lokasi_parkir) . '</span>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <strong>Waktu Masuk:</strong> <span>' . format_waktu($time_in) . '</span>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <strong>Waktu Sekarang:</strong> <span>' . format_waktu($time_now) . '</span>
      </li>
      <li class="list-group-item d-flex justify-content-between">
        <strong>Lama Parkir:</strong> <span>' . $jam_total . ' jam</span>
      </li>
      <li class="list-group-item d-flex justify-content-between bg-light">
        <strong>Total Tarif:</strong> 
        <span class="text-success fw-bold">Rp. ' . number_format($total_tarif, 2, ',', '.') . '</span>
        </li>
    </ul>
  </div>
</div>
';
?>