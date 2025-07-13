<footer class="footer">
  <div class="container-fluid d-flex justify-content-between">
    <nav class="pull-left">
      <!-- Dihapus nav-link yang tidak diperlukan -->
    </nav>
    <div class="copyright">© 2025
      <a href="#">PT. XYZ BATAM</a>
    </div>
  </div>
</footer>

<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>

<!-- jQuery Scrollbar -->
<script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

<!-- Chart JS -->
<script src="../assets/js/plugin/chart.js/chart.min.js"></script>

<!-- jQuery Sparkline -->
<script src="../assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

<!-- Chart Circle -->
<script src="../assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Datatables -->
<script src="../assets/js/plugin/datatables/datatables.min.js"></script>

<!-- Bootstrap Notify -->
<script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="../assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="../assets/js/plugin/jsvectormap/world.js"></script>

<!-- Sweet Alert -->
<script src="../assets/js/plugin/sweetalert/sweetalert.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Kaiadmin JS -->
<script src="../assets/js/kaiadmin.min.js"></script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://code.highcharts.com/themes/adaptive.js"></script>

<script>
  document.getElementById("logoutButton").addEventListener("click", function (e) {
    e.preventDefault(); // Prevent the default link behavior

    Swal.fire({
      title: 'Are you sure?',
      text: "You will be logged out of the system.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, logout!'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "../proses/logout.php";
      }
    });
  });
</script>