<?php
session_start();
include("../../connection.php");

$arr = array();

$get = mysqli_query($koneksi, "
  SELECT 
    a.lokasi_parkir AS loc_name,
    a.koordinat AS coordinate,
    a.lokasi_parkir AS loc,
    CASE 
      WHEN COUNT(p.plat_nomor) > 0 THEN 'OCCUPIED'
      ELSE 'EMPTY'
    END AS status_full
  FROM tbl_area a
  LEFT JOIN tbl_parking p 
    ON a.lokasi_parkir = p.lokasi_parkir AND p.status = 'in'
  GROUP BY a.lokasi_parkir, a.koordinat
");

while ($data = mysqli_fetch_assoc($get)) {
  $arr[] = array(
    "loc_name" => $data['loc_name'],
    "status_full" => $data['status_full'], // "OCCUPIED" or "EMPTY"
    "coordinate" => $data['coordinate'],
    "loc" => $data['loc']
  );
}

echo json_encode($arr);
?>