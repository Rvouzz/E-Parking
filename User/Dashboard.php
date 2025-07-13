<?php
$judul = 'Dashboard';
include '../connection.php';
?>
<?php include '../header.php'; ?>
<div class="wrapper"> <!-- INI WAJIB -->
  <?php include '../sidebar.php'; ?> <!-- file sidebar.php kamu -->
  <div class="container">
    <div class="page-inner">
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-warning bubble-shadow-small">
                    <i class="fas fa-building"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Departments</p>
                    <h4 class="card-title">
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Employees -->
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-info bubble-shadow-small">
                    <i class="fas fa-users"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Employees</p>
                    <h4 class="card-title">
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Supervisors -->
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-primary bubble-shadow-small">
                    <i class="fas fa-user"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Pending Approval</p>
                    <h4 class="card-title">
                    </h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Submissions -->
        <div class="col-sm-6 col-md-3">
          <div class="card card-stats card-round">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-icon">
                  <div class="icon-big text-center icon-secondary bubble-shadow-small">
                    <i class="fas fa-file-signature"></i>
                  </div>
                </div>
                <div class="col col-stats ms-3 ms-sm-0">
                  <div class="numbers">
                    <p class="card-category">Submissions</p>
                    <h4 class="card-title">1</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row">
                <h5 class="card-title text-center w-100" id="chartTitle">Leave Application Summary</h5>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-container" style="min-height: 375px">
                <div id="chart1"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row">
                <h5 class="card-title text-center w-100" id="chartTitle1">Leave Application by Department Summary</h5>
              </div>
            </div>
            <div class="card-body">
              <div class="chart-container" style="min-height: 375px">
                <div id="chart2"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-12">
          <div class="card card-round">
            <div class="card-header">
              <div class="card-head-row card-tools-still-right">
                <div class="card-title text-center w-100">Submissions History</div>
              </div>
            </div>
            <div class="card-body p-3">
              <div class="table-responsive">
                <!-- Projects table -->
                <table id="approvalTable" class="table align-items-center mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col" class="text-center">Employee Name</th>
                      <th scope="col" class="text-center">Date & Time</th>
                      <th scope="col" class="text-center">Type</th>
                      <th scope="col" class="text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
  <?php include '../footer.php' ?>
</div>
</div> <!-- Penutup .wrapper -->

<script>


</script>