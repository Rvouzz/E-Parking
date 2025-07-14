<?php
session_start();
$judul = 'Dashboard';
include '../proses/check_admin.php';
include '../connection.php';

// Total vehicles in history
$totalVehicles = countQuery($koneksi, "SELECT COUNT(*) AS total FROM tbl_history");

// Average parking duration (in hours)
$avgDurationQuery = mysqli_query($koneksi, "
  SELECT AVG(TIMESTAMPDIFF(MINUTE, time_in, time_out)) AS avg_minutes
  FROM tbl_history 
  WHERE time_out IS NOT NULL
");
$avgDuration = round(mysqli_fetch_assoc($avgDurationQuery)['avg_minutes'] ?? 0);

// Total successful transactions
$totalTransactions = countQuery($koneksi, "SELECT COUNT(*) AS total FROM tbl_history WHERE total_pembayaran > 0");

// Most active day
$mostActive = mysqli_fetch_assoc(mysqli_query($koneksi, "
  SELECT DATE(time_in) AS date, COUNT(*) AS total 
  FROM tbl_history 
  GROUP BY DATE(time_in) 
  ORDER BY total DESC LIMIT 1
"));

// Least active day
$leastActive = mysqli_fetch_assoc(mysqli_query($koneksi, "
  SELECT DATE(time_in) AS date, COUNT(*) AS total 
  FROM tbl_history 
  GROUP BY DATE(time_in) 
  ORDER BY total ASC LIMIT 1
"));

$most_slot = mysqli_fetch_assoc(mysqli_query($koneksi, "
  SELECT lokasi_parkir, COUNT(*) AS total 
  FROM tbl_history 
  GROUP BY lokasi_parkir 
  ORDER BY total DESC 
  LIMIT 1
"));

$today = date('Y-m-d');
$earningToday = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_pembayaran) AS total FROM tbl_history WHERE DATE(time_out) = '$today'"))['total'] ?? 0;

$totalEarning = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_pembayaran) AS total FROM tbl_history"))['total'] ?? 0;

$monthly_income = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_pembayaran) AS total FROM tbl_history WHERE MONTH(time_out) = MONTH(CURDATE()) AND YEAR(time_out) = YEAR(CURDATE())"))['total'] ?? 0;

$todayIn = countQuery($koneksi, "SELECT COUNT(*) AS total FROM tbl_parking WHERE DATE(time_in) = '$today'");

$todayOut = countQuery($koneksi, "SELECT COUNT(*) AS total FROM tbl_history WHERE DATE(time_out) = '$today'");

?>

<?php include '../header.php'; ?>

<!-- <style>
  canvas {
  max-width: 100%;
  height: auto;
}
</style> -->

<div class="wrapper">
  <?php include '../sidebar.php'; ?>
  <div class="container">
    <div class="page-inner">

      <!-- TOP SUMMARY CARDS -->
      <div class="row mt-2">
        <?php
        function countQuery($koneksi, $sql)
        {
          $result = mysqli_fetch_assoc(mysqli_query($koneksi, $sql));
          return $result['total'] ?? 0;
        }

        $totalArea = countQuery($koneksi, "SELECT COUNT(*) AS total FROM tbl_area");
        $empty = countQuery($koneksi, "
            SELECT COUNT(*) AS total
            FROM tbl_area a
            WHERE NOT EXISTS (
              SELECT 1 FROM tbl_parking p 
              WHERE p.lokasi_parkir = a.lokasi_parkir AND p.status = 'In'
            )");
        $occupied = countQuery($koneksi, "
            SELECT COUNT(DISTINCT lokasi_parkir) AS total
            FROM tbl_parking
            WHERE status = 'In'");

        $topCards = [
          ['icon' => 'fa-building', 'title' => 'Total Locations', 'value' => $totalArea, 'color' => 'primary'],
          ['icon' => 'fa-parking', 'title' => 'Empty Slots', 'value' => $empty, 'color' => 'success'],
          ['icon' => 'fa-car', 'title' => 'Occupied Slots', 'value' => $occupied, 'color' => 'danger'],
        ];

        foreach ($topCards as $card) {
          echo '
          <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
              <div class="card-body">
                <div class="row align-items-center">
                  <div class="col-icon">
                    <div class="icon-big text-center icon-' . $card['color'] . ' bubble-shadow-small">
                      <i class="fas ' . $card['icon'] . '"></i>
                    </div>
                  </div>
                  <div class="col col-stats ms-3 ms-sm-0">
                    <div class="numbers">
                      <p class="card-category">' . $card['title'] . '</p>
                      <h4 class="card-title">' . number_format($card['value']) . '</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>';
        }
        ?>
      </div>

      <div class="row">
        <!-- LEFT: Canvas Map -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
              <h5 class="mb-0 fw-bold">Parking Map</h5>
            </div>
            <div class="card-body p-3">

              <!-- Legend -->
              <div class="mb-3 d-flex justify-content-center gap-4">
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-success me-2" style="width: 15px; height: 15px;"></div>
                  <small>Empty</small>
                </div>
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-danger me-2" style="width: 15px; height: 15px;"></div>
                  <small>Occupied</small>
                </div>
              </div>

              <!-- Canvas -->
              <div class="d-flex justify-content-center">
                <canvas id="imageCanvas" width="729" height="404" style="max-width: 100%; height: auto;"></canvas>
                <map name="image-map" id="image-map"></map>
              </div>
            </div>
          </div>
        </div>



        <!-- RIGHT: Summary as Table -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <h5 class="mb-4 fw-bold">Summary</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-sm">
                  <tbody>
                    <tr>
                      <th class="text-muted">Today's Income</th>
                      <td class="text-success fw-bold">Rp. <?= number_format($earningToday, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Total Income</th>
                      <td class="text-dark fw-bold">Rp. <?= number_format($totalEarning, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Monthly Income</th>
                      <td class="text-primary fw-bold">Rp. <?= number_format($monthly_income, 2, ',', '.') ?></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Entered Today</th>
                      <td class="text-info fw-bold"><?= number_format($todayIn) ?> vehicles</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Exited Today</th>
                      <td class="text-secondary fw-bold"><?= number_format($todayOut) ?> vehicles</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Most Used Slot</th>
                      <td class="text-danger fw-bold"><?= $most_slot['lokasi_parkir'] ?? '-' ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- LEFT: Extra Insights -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <h5 class="mb-4 fw-bold">Extra Insights</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Total Vehicles (All Time)
                  <span class="fw-bold"><?= number_format($totalVehicles) ?> vehicles</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Avg. Parking Duration
                  <span class="fw-bold"><?= $avgDuration ?> minutes</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Total Transactions
                  <span class="fw-bold"><?= number_format($totalTransactions) ?> payments</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- RIGHT: Most & Least Active Day -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <h5 class="mb-4 fw-bold">Activity Highlights</h5>
              <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Most Active Day
                  <span class="fw-bold"><?= $mostActive['date'] ?? '-' ?> (<?= $mostActive['total'] ?? 0 ?>)</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Least Active Day
                  <span class="fw-bold"><?= $leastActive['date'] ?? '-' ?> (<?= $leastActive['total'] ?? 0 ?>)</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
  <?php include '../footer.php'; ?>



</div>

<!-- Modal -->
<div class="modal fade" id="mappingModal" tabindex="-1" aria-labelledby="mappingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title" id="mappingModalLabel">Parking Area Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="modalContent">Loading...</p>
      </div>
    </div>
  </div>
</div>

<script>
  const canvas = document.getElementById('imageCanvas');
  const ctx = canvas.getContext('2d');

  window.onload = function () {
    get_loc_mapping();
  };

  function normalizeCoords(coords) {
    let [x1, y1, x2, y2] = coords.split(',').map(Number);
    if (x1 > x2) [x1, x2] = [x2, x1];
    if (y1 > y2) [y1, y2] = [y2, y1];
    return [x1, y1, x2, y2].join(',');
  }

  function get_loc_mapping() {
    $.ajax({
      type: "GET",
      url: "proses/get_mapping.php"
    }).done(function (res) {
      const arr = JSON.parse(res);
      const img = new Image();
      img.src = '../assets/images/PARKING - AREA2.png';

      img.onload = function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        $('#image-map').empty();

        arr.forEach(e => {
          if (e.coordinate) {
            const normalized = normalizeCoords(e.coordinate);
            const original = e.coordinate;
            drawLocation(ctx, normalized, e.status_full === "EMPTY" ? "#31CE36" : "#F25961");

            if (e.loc_name) {
              const area = $('<area>')
                .attr('shape', 'rect')
                .attr('coords', normalized)
                .attr('title', e.loc_name)
                .attr('data-coordinate', normalized)
                .attr('data-db-coordinate', original);

              $('#image-map').append(area);
            }
          }
        });

        $('#imageCanvas').on('click', function (event) {
          const rect = canvas.getBoundingClientRect();
          const scaleX = canvas.width / rect.width;
          const scaleY = canvas.height / rect.height;
          const x = (event.clientX - rect.left) * scaleX;
          const y = (event.clientY - rect.top) * scaleY;

          $('#image-map area').each(function () {
            const coords = $(this).attr('coords');
            if (coords) {
              const [x1, y1, x2, y2] = coords.split(',').map(Number);
              if (x >= x1 && x <= x2 && y >= y1 && y <= y2) {
                $(this).trigger('click');
              }
            }
          });
        });
      };
    });
  }

  function drawLocation(ctx, coordinate, color) {
    const [x1, y1, x2, y2] = coordinate.split(',').map(Number);
    const width = x2 - x1;
    const height = y2 - y1;
    ctx.fillStyle = color;
    ctx.fillRect(x1, y1, width, height);
  }

  $(document).on("click", "#image-map area", function (e) {
    e.preventDefault();
    const coordFromDB = $(this).data('db-coordinate');
    get_detail_mapping(coordFromDB);
  });

  function get_detail_mapping(coordinate) {
    $('#modalContent').text("Loading...");
    $('#mappingModal').modal('show');

    $.ajax({
      url: 'proses/get_detail.php',
      type: 'GET',
      data: { coordinate: coordinate },
      success: function (res) {
        $('#modalContent').html(res);
      },
      error: function () {
        $('#modalContent').text("Failed to load details.");
      }
    });
  }

  $('#imageCanvas').on('mousemove', function (event) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    const x = (event.clientX - rect.left) * scaleX;
    const y = (event.clientY - rect.top) * scaleY;

    let hovering = false;
    $('#image-map area').each(function () {
      const coords = $(this).attr('coords');
      if (coords) {
        const [x1, y1, x2, y2] = coords.split(',').map(Number);
        if (x >= x1 && x <= x2 && y >= y1 && y <= y2) {
          hovering = true;
          return false;
        }
      }
    });

    canvas.style.cursor = hovering ? 'pointer' : 'default';
  });
</script>