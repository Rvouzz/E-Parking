<?php
session_start();
include("../../connection.php");

$arr = array();

// Ambil plat nomor dari session
$plat = isset($_SESSION['plat_nomor']) ? $_SESSION['plat_nomor'] : null;

if ($plat) {
  // Query hanya lokasi parkir yang dimiliki user ini (status = 'in')
  $get = mysqli_query($koneksi, "
    SELECT 
      a.lokasi_parkir AS loc_name,
      a.koordinat AS coordinate,
      a.lokasi_parkir AS loc,
      'OWNED' AS status_full
    FROM tbl_area a
    INNER JOIN tbl_parking p 
      ON a.lokasi_parkir = p.lokasi_parkir 
      AND p.plat_nomor = '$plat'
      AND p.status = 'in'
  ");

  while ($data = mysqli_fetch_assoc($get)) {
    $arr[] = array(
      "loc_name" => $data['loc_name'],
      "status_full" => $data['status_full'], // "OWNED"
      "coordinate" => $data['coordinate'],
      "loc" => $data['loc']
    );
  }
}

echo json_encode($arr);
?>