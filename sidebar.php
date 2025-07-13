<?php
session_start();
// Jika user adalah admin, maka cek email seperti biasa
if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {
  if (!isset($_SESSION['email_address'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
  }
} else {
  // Kalau bukan admin (user biasa via plat nomor), cek plat_nomor
  if (!isset($_SESSION['plat_nomor'])) {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
  }
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
$email_address = isset($_SESSION['email_address']) ? $_SESSION['email_address'] : '';
$get_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
?>

<style>
  .badge {
    font-size: 0.75rem;
    padding: 5px 10px;
    border-radius: 50rem;
  }
</style>

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="#" class="logo d-flex align-items-center">
        <i class="fas fa-user-clock text-white me-2 fs-5"></i> <!-- Icon representing E-Leave -->
        <span class="text-white fw-bold">E-Leave</span> <!-- App name -->
      </a>
      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
        <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
      </div>
      <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
    </div>

    <!-- End Logo Header -->
  </div>

  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
          <!-- Dashboard -->
          <li class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
            <a href="dashboard.php">
              <i class="fas fa-home"></i>
              <p>Dashboard</p>
            </a>
          </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</div>
<!-- End Sidebar -->

<div class="main-panel">
  <div class="main-header">
    <!-- Logo Header -->
    <div class="main-header-logo">
      <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo">
          <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
        </a>
        <div class="nav-toggle">
          <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
          <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
        </div>
        <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
      </div>
    </div>
    <!-- End Logo Header -->

    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
      <div class="container-fluid">
        <h5>E-Parking Management System</h5>
        <!-- Topbar Icons -->
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
            <!-- User Profile -->
            <li class="nav-item topbar-user dropdown hidden-caret">
              <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#">
                <div class="avatar-sm">
                  <?php
                  $name_encoded = urlencode($name);
                  $avatar_url = "https://ui-avatars.com/api/?name={$name_encoded}&background=random&color=fff&rounded=true";
                  ?>
                  <img src="<?= $avatar_url ?>" alt="User Avatar" class="avatar-img rounded-circle" />
                </div>
                <span class="profile-username">
                  <span class="op-7">Hi,</span>
                  <span class="fw-bold"><?= htmlspecialchars($name) ?></span>
                </span>
              </a>
              <ul class="dropdown-menu dropdown-user animated fadeIn">
                <div class="dropdown-user-scroll scrollbar-outer">
                  <li>
                    <div class="user-box">
                      <div class="avatar-lg">
                        <?php
                        $name_encoded = urlencode($name);
                        $avatar_url = "https://ui-avatars.com/api/?name={$name_encoded}&background=random&color=fff&rounded=true";
                        ?>
                        <img src="<?= $avatar_url ?>" alt="User Avatar" class="avatar-img rounded-circle" />
                      </div>
                      <div class="u-text">
                        <h4><?= htmlspecialchars($name) ?></h4>
                        <p class="text-muted"><?= htmlspecialchars($email_address) ?></p>
                        <!-- <a href="profile.php" class="btn btn-xs btn-secondary btn-sm">View Profile</a> -->
                      </div>
                    </div>
                  </li>
                  <li>
                    <!-- <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Account Setting</a> -->
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" id="logoutButton">Logout</a>
                  </li>
                </div>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->
  </div>