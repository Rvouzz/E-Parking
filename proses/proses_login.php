<?php
include '../connection.php';
session_start();

$email_address = $_POST['email_address'];
$password = $_POST['password'];

// Check database connection
if (!$koneksi) {
  die("Connection failed: " . mysqli_connect_error());
}

// Retrieve user from database
$sql = "SELECT * FROM tbl_users WHERE email_address = '$email_address' LIMIT 1";
$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $hashedPassword = $row['password'];

  // Verify password
  if (password_verify($password, $hashedPassword)) {
    // Set cookies if Remember Me is checked
    if (isset($_POST['flexCheckChecked'])) {
      setcookie('email_address', $email_address, time() + (86400 * 30), '/'); // 30 days
      setcookie('password', hash('sha512', $password), time() + (86400 * 30), '/');
    }

    // Check user role
    if ($row['role'] !== 'Admin') {
      $_SESSION['login_error'] = "Permission denied. You do not have access.";
      header("Location: ../authentication-login.php");
      exit();
    }

    // Successful login - set session
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['email_address'] = $row['email_address'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['role'] = $row['role'];

    // Redirect based on role
    switch ($row['role']) {
      case 'Admin':
        header("Location: ../Admin/dashboard.php");
        exit();
      default:
        $_SESSION['login_error'] = "Invalid role.";
        header("Location: ../authentication-login.php");
        exit();
    }
  }
}

// If login fails
$_SESSION['login_error'] = "Invalid email or password.";
header("Location: ../authentication-login.php");
exit();
?>