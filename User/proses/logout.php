<?php
session_start();

// Hapus session spesifik
unset($_SESSION['plat_nomor']);

// Jika mau hapus semua session
// session_unset();
// session_destroy();

// Arahkan kembali ke halaman utama atau login
header('Location: ../../index.php');
exit();
?>
