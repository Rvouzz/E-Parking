<?php
include 'connection.php';
session_start();

// Auto login via cookie
if (isset($_COOKIE['email_address']) && isset($_COOKIE['password'])) {
  $email_address = mysqli_real_escape_string($koneksi, $_COOKIE['email_address']);
  $cookie_password = $_COOKIE['password'];

  $query = mysqli_query($koneksi, "SELECT * FROM tbl_users WHERE email_address = '$email_address' LIMIT 1");

  if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);

    if (hash('sha512', $row['password']) === $cookie_password) {
      $_SESSION['email_address'] = $row['email_address'];
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['role'] = $row['role'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['login_success'] = "Welcome back, " . $row['name'] . "!";

      switch ($row['role']) {
        case 'Admin':
          $_SESSION['redirect_to'] = "Admin/dashboard.php";
          break;
        default:
          $_SESSION['login_error'] = "Invalid role.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>E-Parking Login</title>
  <link rel="shortcut icon" href="assets/images/logos/favicon.png" type="image/png">
  <link rel="stylesheet" href="assets/css/styles.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background: linear-gradient(135deg, #e0e0e0, #ffffff);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 1rem;
      border: 1px solid rgba(255, 255, 255, 0.4);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      color: #333;
      width: 100%;
      max-width: 400px;
      animation: fadeIn 0.6s ease-in-out;
    }

    .glass-card .form-control {
      background-color: rgba(255, 255, 255, 0.8);
      color: #333;
      border: 1px solid rgba(0, 0, 0, 0.1);
    }

    .glass-card .form-control::placeholder {
      color: rgba(0, 0, 0, 0.5);
    }

    .glass-card .form-check-label,
    .glass-card label {
      color: #333 !important;
    }

    .text-glow {
      text-shadow: 0 0 6px rgba(255, 255, 255, 0.6);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>


</head>

<body>

  <div class="glass-card col-md-5 col-lg-4">
    <div class="text-center mb-4">
      <i class="fas fa-user-clock fa-3x text-primary mb-3"></i>
      <h2 class="fw-bold">E-Parking</h2>
      <p class="text-dark mb-1">Parking Management System</p>
    </div>

    <form method="POST" action="proses/proses_login.php">
      <div class="mb-3">
        <label for="email_address" class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email_address" id="email_address" placeholder="Enter email"
          required>
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
      </div>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="flexCheckChecked" id="flexCheckChecked">
          <label class="form-check-label" for="flexCheckChecked">Remember Me</label>
        </div>
        <a href="forgot_password.php" class="text-primary text-decoration-none">Forgot Password?</a>
      </div>
      <button type="submit" class="btn btn-primary w-100">
        <i class="fas fa-sign-in-alt me-2"></i> Sign In
      </button>
    </form>
  </div>

  <!-- SweetAlert Login Notifications -->
  <?php
  if (isset($_SESSION['login_success']) && isset($_SESSION['redirect_to'])) {
    echo "
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: '" . $_SESSION['login_success'] . "',
        confirmButtonText: 'Continue'
      }).then(() => {
        window.location.href = '" . $_SESSION['redirect_to'] . "';
      });
    </script>";
    unset($_SESSION['login_success']);
    unset($_SESSION['redirect_to']);
  }

  if (isset($_SESSION['login_error'])) {
    echo "
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '" . $_SESSION['login_error'] . "',
        confirmButtonText: 'Try Again'
      });
    </script>";
    unset($_SESSION['login_error']);
  }
  ?>
</body>

</html>