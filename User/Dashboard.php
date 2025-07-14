<?php
session_start();
$judul = 'Dashboard';
include '../connection.php';

$plat = $_SESSION['plat_nomor'] ?? null;
$jenis = $lokasi_parkir = $time_in = $time_now = $jam_total = $total_tarif = '-';

if ($plat) {
  $query_parking = mysqli_query($koneksi, "
    SELECT * FROM tbl_parking 
    WHERE plat_nomor = '$plat' AND status = 'In'
    ORDER BY time_in DESC LIMIT 1
  ");

  if ($query_parking && mysqli_num_rows($query_parking) > 0) {
    $parkir = mysqli_fetch_assoc($query_parking);
    $jenis = $parkir['jenis_kendaraan'];
    $lokasi_parkir = $parkir['lokasi_parkir'];
    $time_in = $parkir['time_in'];

    $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    $time_now = $now->format('Y-m-d H:i:s');

    // Hitung durasi jam
    $start = strtotime($time_in);
    $end = strtotime($time_now);
    $jam_total = ceil(($end - $start) / 3600);

    // Ambil tarif kendaraan
    $query_tarif = mysqli_query($koneksi, "SELECT * FROM tbl_kendaraan WHERE jenis = '$jenis' LIMIT 1");
    if ($query_tarif && mysqli_num_rows($query_tarif) > 0) {
      $kendaraan = mysqli_fetch_assoc($query_tarif);
      $tarif_awal = $kendaraan['tarif_awal'];
      $tarif_per_jam = $kendaraan['tarif_per_jam'];

      $total_tarif = ($jam_total <= 1) ? $tarif_awal : $tarif_awal + ($jam_total - 1) * $tarif_per_jam;
    }
  }
}

// Fungsi format waktu lokal
function format_waktu($datetime)
{
  $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  $timestamp = strtotime($datetime);
  $hari = $days[date('w', $timestamp)];
  $tgl = date('d', $timestamp);
  $bulan = $months[date('n', $timestamp)];
  $tahun = date('Y', $timestamp);
  $jam = date('H:i', $timestamp);
  return "$hari, $tgl $bulan $tahun $jam WIB";
}

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
      <div class="row">
        <!-- LEFT: Canvas Map -->
        <div class="col-md-6">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-info text-white fw-bold">
              Parking Map
            </div>
            <div class="card-body p-3">
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
            <div class="card-header bg-info text-white fw-bold">
              Informasi Parkir Saat Ini
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0">
                  <tbody>
                    <tr>
                      <th>Plat Nomor</th>
                      <td><?= htmlspecialchars($plat) ?></td>
                    </tr>
                    <tr>
                      <th>Jenis Kendaraan</th>
                      <td><?= htmlspecialchars($jenis) ?></td>
                    </tr>
                    <tr>
                      <th>Lokasi Parkir</th>
                      <td><?= htmlspecialchars($lokasi_parkir) ?></td>
                    </tr>
                    <tr>
                      <th>Waktu Masuk</th>
                      <td><?= format_waktu($time_in) ?></td>
                    </tr>
                    <tr>
                      <th>Waktu Sekarang</th>
                      <td><?= format_waktu($time_now) ?></td>
                    </tr>
                    <tr>
                      <th>Lama Parkir</th>
                      <td><?= htmlspecialchars($jam_total) ?> jam</td>
                    </tr>
                    <tr class="bg-light">
                      <th>Total Tarif</th>
                      <td class="text-success fw-bold">Rp. <?= number_format($total_tarif, 2, ',', '.') ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Tombol Checkout -->
              <form id="checkoutForm" class="text-end mt-4">
                <input type="hidden" name="plat_nomor" id="plat_nomor_checkout" value="<?= htmlspecialchars($plat) ?>">
                <button type="submit" class="btn btn-danger">
                  <i class="fas fa-sign-out-alt me-1"></i> Check Out
                </button>
              </form>

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

            // Ubah warna berdasarkan status
            drawLocation(ctx, normalized,
              e.status_full === "OWNED" ? "#007bff" :
                e.status_full === "EMPTY" ? "#31CE36" : "#F25961"
            );

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

  $('#checkoutForm').submit(function (e) {
    e.preventDefault();
    const plat_nomor = $('#plat_nomor_checkout').val();

    Swal.fire({
      title: 'Yakin ingin check out?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Check Out',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#d33'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'proses/checkout.php',
          method: 'POST',
          data: { plat_nomor: plat_nomor },
          success: function (res) {
            if (res === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kendaraan telah berhasil check out.',
                confirmButtonColor: '#3085d6'
              }).then(() => {
                window.location.href = '../index.php';
              });
            } else if (res === 'not_found') {
              Swal.fire('Gagal', 'Data kendaraan tidak ditemukan.', 'error');
            } else {
              Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
            }
          },
          error: function () {
            Swal.fire('Error', 'Gagal terhubung ke server.', 'error');
          }
        });
      }
    });
  });


</script>