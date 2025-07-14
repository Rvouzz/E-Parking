<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Welcome to E-Parking</title>
  <link rel="shortcut icon" href="assets/images/logos/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f8f9fa;
      color: #333;
    }

    .navbar {
      background-color: #ffffff;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .navbar-brand {
      color: #0d6efd !important;
      font-weight: bold;
      font-size: 1.5rem;
    }

    .hero {
      padding: 100px 20px 60px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .plat-form {
      background: #fff;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
      max-width: 500px;
      width: 100%;
    }

    .plat-form .form-label {
      font-weight: 500;
      margin-bottom: 6px;
      color: #333;
    }

    .plat-form input,
    .plat-form select {
      border-radius: 50px;
      padding: 0.75rem 1.25rem;
      font-size: 1rem;
    }

    .plat-form button {
      margin-top: 1.5rem;
      border-radius: 50px;
      font-weight: 600;
      font-size: 1.1rem;
      padding: 0.75rem;
      transition: background-color 0.3s;
    }

    .plat-form button:hover {
      background-color: #084cdf;
    }

    .hero-icon {
      font-size: 3.5rem;
      color: #0d6efd;
      margin-bottom: 1rem;
      background: #e9f2ff;
      padding: 1rem;
      border-radius: 50%;
    }

    .features {
      padding: 80px 20px;
      background-color: #ffffff;
    }

    .feature-card {
      background: #fff;
      border: 1px solid #eee;
      border-radius: 1rem;
      padding: 40px 30px;
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }

    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .feature-card i {
      font-size: 2.5rem;
      color: #0d6efd;
      margin-bottom: 1rem;
    }

    .feature-card h5 {
      font-weight: 600;
      margin-bottom: 0.5rem;
    }

    footer {
      background: #f1f3f5;
      text-align: center;
      padding: 1.2rem;
      font-size: 0.9rem;
      color: #666;
      border-top: 1px solid #eaeaea;
    }

    @media (max-width: 576px) {
      .plat-form {
        padding: 30px 20px;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top py-3 px-4">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="authentication-login.php">
        <i class="fas fa-car me-2"></i> E-Parking
      </a>
    </div>
  </nav>

  <!-- Hero -->
  <section class="hero" style="margin-top: -3rem;">
    <div class="hero-icon">
      <i class="fas fa-car"></i>
    </div>
    <form class="plat-form" id="platForm">
      <div class="mb-3">
        <label for="plat_nomor" class="form-label">Nomor Plat Kendaraan</label>
        <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" placeholder="Contoh: BP 1234 ZZ"
          required />
      </div>
      <div class="mb-3">
        <label for="jenis_kendaraan" class="form-label">Jenis Kendaraan</label>
        <select name="jenis_kendaraan" id="jenis_kendaraan" class="form-select" required>
          <option value="" disabled selected>--Pilih Jenis Kendaraan--</option>
          <option value="Motor">Motor</option>
          <option value="Mobil">Mobil</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-check-circle me-2"></i> Submit Plat Nomor
      </button>
    </form>
  </section>

  <!-- Features -->
  <section class="features">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold">Fitur Utama</h2>
        <p class="text-muted">Tingkatkan efisiensi dan keamanan parkir kendaraan dengan sistem digital</p>
      </div>
      <div class="row g-4">
        <div class="col-md-4 d-flex">
          <div class="feature-card d-flex flex-column h-100 w-100">
            <i class="fas fa-car-side"></i>
            <h5>Pencatatan Otomatis</h5>
            <p class="mt-auto">Catat kendaraan masuk & keluar secara otomatis dan cepat.</p>
          </div>
        </div>
        <div class="col-md-4 d-flex">
          <div class="feature-card d-flex flex-column h-100 w-100">
            <i class="fas fa-lock"></i>
            <h5>Keamanan Terjamin</h5>
            <p class="mt-auto">Data kendaraan terenkripsi dan hanya bisa diakses petugas parkir.</p>
          </div>
        </div>
        <div class="col-md-4 d-flex">
          <div class="feature-card d-flex flex-column h-100 w-100">
            <i class="fas fa-clock"></i>
            <h5>Rekap Waktu Parkir</h5>
            <p class="mt-auto">Hitung durasi dan tarif parkir secara otomatis.</p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Footer -->
  <footer>
    &copy; <?= date('Y'); ?> PT. XYZ BATAM — Powered by E-Parking System
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
  $('#platForm').submit(function (e) {
    e.preventDefault();
    const plat_nomor = $('#plat_nomor').val();
    const jenis_kendaraan = $('#jenis_kendaraan').val();

    $.ajax({
      url: './User/proses/submit-plat.php',
      method: 'POST',
      data: {
        plat_nomor: plat_nomor,
        jenis_kendaraan: jenis_kendaraan
      },
      success: function (res) {
        if (res === 'exists') {
          Swal.fire({
            icon: 'info',
            title: 'Sudah Terdaftar',
            text: 'Kendaraan ini sudah tercatat masuk.',
            confirmButtonColor: '#3085d6'
          }).then(() => {
            window.location.href = 'User/Dashboard.php';
          });
        } else if (res === 'inserted') {
          Swal.fire({
            icon: 'success',
            title: 'Tercatat!',
            text: 'Kendaraan berhasil didaftarkan.',
            confirmButtonColor: '#3085d6'
          }).then(() => {
            window.location.href = 'User/Dashboard.php';
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: 'Terjadi kesalahan pada server!',
          });
        }
      },
      error: function (xhr, status, error) {
        Swal.fire({
          icon: 'error',
          title: 'Gagal Terhubung',
          text: 'Tidak dapat menghubungi server.',
        });
      }
    });
  });
</script>


</html>