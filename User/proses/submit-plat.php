<?php
session_start();
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $plat = strtoupper(trim($_POST['plat_nomor']));
  $jenis = $_POST['jenis_kendaraan'];

  date_default_timezone_set('Asia/Jakarta');
  $now = date('Y-m-d H:i:s');

  // 1. Cek apakah kendaraan sudah parkir aktif
  $stmt = $koneksi->prepare("SELECT id_parking FROM tbl_parking WHERE plat_nomor = ? AND status = 'In'");
  $stmt->bind_param("s", $plat);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $_SESSION['plat_nomor'] = $plat;
    echo 'exists';
    exit();
  }

  // 2. Mapping jenis kendaraan ke prefix area
  $jenis_to_prefix = [
    'Motor' => 'MTR',
    'Mobil' => 'MBL',
    'Truk'  => 'TRK' // jika ada jenis lain
  ];

  if (!array_key_exists($jenis, $jenis_to_prefix)) {
    echo 'invalid_vehicle';
    exit();
  }

  $prefix = $jenis_to_prefix[$jenis];
  $like_prefix = $prefix . '%';

  // 3. Cari lokasi kosong yang cocok
  $query_area = "
    SELECT a.lokasi_parkir
    FROM tbl_area a
    LEFT JOIN (
      SELECT lokasi_parkir FROM tbl_parking WHERE status = 'In'
    ) AS p ON a.lokasi_parkir = p.lokasi_parkir
    WHERE p.lokasi_parkir IS NULL AND a.lokasi_parkir LIKE ?
    ORDER BY RAND()
    LIMIT 1
  ";

  $area_stmt = $koneksi->prepare($query_area);
  $area_stmt->bind_param("s", $like_prefix);
  $area_stmt->execute();
  $area_result = $area_stmt->get_result();

  if ($area_result->num_rows === 0) {
    echo 'full'; // Semua slot untuk jenis ini penuh
    exit();
  }

  $row = $area_result->fetch_assoc();
  $lokasi = $row['lokasi_parkir'];
  $status = 'In';

  // 4. Simpan ke database
  $insert = $koneksi->prepare("
    INSERT INTO tbl_parking (plat_nomor, time_in, jenis_kendaraan, lokasi_parkir, status)
    VALUES (?, ?, ?, ?, ?)
  ");
  $insert->bind_param("sssss", $plat, $now, $jenis, $lokasi, $status);

  if ($insert->execute()) {
    $_SESSION['plat_nomor'] = $plat;
    echo 'inserted';
  } else {
    echo 'error';
  }
}
?>
