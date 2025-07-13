<?php
session_start();
session_unset(); // Menghapus semua variabel sesi
session_destroy(); // Mengakhiri sesi
header("Location: ../index.php"); // Arahkan kembali ke halaman login (ubah sesuai lokasi login Anda)
exit;
?>