<?php
include '../../connection.php';

$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$status_types = ['Completed', 'Rejected', 'Open'];
$data = [];

// Inisialisasi array kosong
foreach ($status_types as $status) {
  $data[$status] = array_fill(0, 12, 0);
}

// Query grup berdasarkan status dan bulan
$query = "SELECT DATE_FORMAT(timestamp, '%b') AS month, status, COUNT(*) AS total
          FROM tbl_pengajuan
          WHERE YEAR(timestamp) = YEAR(CURDATE())
          GROUP BY month, status";

$result = mysqli_query($koneksi, $query);

while ($row = mysqli_fetch_assoc($result)) {
  $monthIndex = array_search($row['month'], $months);
  if ($monthIndex !== false && in_array($row['status'], $status_types)) {
    $data[$row['status']][$monthIndex] = (int)$row['total'];
  }
}

echo json_encode($data);
?>
