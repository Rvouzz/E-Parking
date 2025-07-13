<?php
include '../../connection.php';

$year = date('Y');
$query = "
  SELECT u.department, p.status, COUNT(*) AS total
  FROM tbl_pengajuan p
  JOIN tbl_users u ON p.email_address = u.email_address
  WHERE YEAR(p.timestamp) = '$year'
  GROUP BY u.department, p.status
";

$result = mysqli_query($koneksi, $query);

$data = [];
$departments = [];

while ($row = mysqli_fetch_assoc($result)) {
  $dept = $row['department'];
  $status = $row['status'];
  $count = (int)$row['total'];

  if (!in_array($dept, $departments)) {
    $departments[] = $dept;
  }

  $data[$status][$dept] = $count;
}

// Normalisasi data
$Completed = [];
$Rejected = [];
$Open = [];

foreach ($departments as $dept) {
  $Completed[] = $data['Completed'][$dept] ?? 0;
  $Rejected[] = $data['Rejected'][$dept] ?? 0;
  $Open[] = $data['Open'][$dept] ?? 0;
}

echo json_encode([
  "departments" => $departments,
  "Completed" => $Completed,
  "Rejected" => $Rejected,
  "Open" => $Open
]);
