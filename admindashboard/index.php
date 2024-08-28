<?php
session_start();
include "../koneksi.php";
include_once "function/proses_get_partial_data.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Administrator') {
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kasirinbro - Administrator</title>
  <link rel="stylesheet" href="assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/vendors/select2/select2.min.css">
  <link rel="stylesheet" href="assets/vendors/css/custom-style.css">
  <link rel="stylesheet" href="assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="assets/js/select.dataTables.min.css">
  <link rel="shortcut icon" href="../assets/ico/favicon.ico" />
</head>

<body>
  <div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="index.php">
            <img src="../assets/ico/kasirinbro.png" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="index.php">
            <img src="../assets/ico/android-chrome-192x192.png" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Welcome, <span class="text-black fw-bold"><?php echo $_SESSION['rafi_username']; ?></span></h1>
            <h3 class="welcome-sub-text">Your performance summary this week </h3>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="<?php echo !empty($_SESSION['rafi_profile']) ? "../pictprofile/" . $_SESSION['rafi_profile'] : '../images/faces/default.jpg'; ?>" alt="Profile image"></a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-xs rounded-circle" src="<?php echo !empty($_SESSION['rafi_profile']) ? "../pictprofile/" . $_SESSION['rafi_profile'] : '../images/faces/default.jpg'; ?>" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['rafi_username']; ?> - <?php echo $_SESSION['rafi_role']; ?></p>
              </div>
              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>My Profile</a>
              <a href="../logout.php" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-danger me-2"></i>Sign Out</a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="profileModalLabel">My Profile</h5>
          </div>
          <div class="dropdown-header text-center">
            <img class="img-lg rounded-circle" src="<?php echo !empty($_SESSION['rafi_profile']) ? "../pictprofile/" . $_SESSION['rafi_profile'] : '../images/faces/default.jpg'; ?>" alt="Profile image">
            <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['rafi_username']; ?></p>
          </div>
          <form action="function/proses_update_profile.php" method="post" enctype="multipart/form-data">
            <div class="modal-body">
              <input type="hidden" name="rafi_id_users" value="<?php echo $userData['rafi_id_users']; ?>">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="rafi_username" value="<?php echo $userData['rafi_username']; ?>" readonly>
              </div>
              <div class="mb-3">
                <label for="namaLengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="namaLengkap" name="rafi_nama_lengkap" value="<?php echo $userData['rafi_nama_lengkap']; ?>">
              </div>
              <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" class="form-control" id="Alamat" name="rafi_alamat" value="<?php echo $userData['rafi_alamat']; ?>">
              </div>
              <div class="mb-3">
                <label for="fotoProfil" class="form-label">Foto Profil</label>
                <input type="file" class="form-control" id="fotoProfil" name="foto_profil">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="container-fluid page-body-wrapper">
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme">
            <div class="img-ss rounded-circle bg-light border me-3"></div>Light
          </div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme">
            <div class="img-ss rounded-circle bg-dark border me-3"></div>Dark
          </div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item nav-category">Data</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="menu-icon mdi mdi-card-text-outline"></i>
              <span class="menu-title">Transaksi</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="pages/index.php">Lihat Riwayat Transaksi</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item nav-category">Barang</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="menu-icon mdi mdi-floor-plan"></i>
              <span class="menu-title">Barang</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/lihat_barang.php">Lihat Barang</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/tambah_barang.php">Tambah Barang</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
              <i class="menu-icon mdi mdi-chart-line"></i>
              <span class="menu-title">Laporan</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/generate_laporan.php">Generate Laporan</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item nav-category">Users</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="menu-icon mdi mdi-account-circle-outline"></i>
              <span class="menu-title">User Settings</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="pages/lihat_petugas.php">Lihat Petugas</a></li>
                <li class="nav-item"> <a class="nav-link" href="pages/tambah_petugas.php">Tambah Petugas</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-sm-12">
              <div class="home-tab">
                <div class="d-sm-flex align-items-center justify-content-between border-bottom">
                  <ul class="nav nav-tabs" role="tablist">
                  </ul>
                  <div>
                    <div class="btn-wrapper">
                      <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
                    </div>
                  </div>
                </div>
                <div class="tab-content tab-content-basic">
                  <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                    <div class="row">
                      <div class="col-lg-12 d-flex flex-column">
                        <div class="row flex-grow">
                          <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <p class="card-title card-title-dash">Total Barang</p>
                                <h3 class="rate-percentage"><?php echo $total_barang; ?></h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <p class="card-title card-title-dash">Total Petugas</p>
                                <h3 class="rate-percentage"><?php echo $total_petugas; ?></h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <p class="card-title card-title-dash">Pendapatan Hari Ini</p>
                                <h3 class="rate-percentage"><?php echo $incomeTodayFormatted; ?></h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <p class="card-title card-title-dash">Pendapatan Bulan Ini</p>
                                <h3 class="rate-percentage"><?php echo $incomeThisMonthFormatted; ?></h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <p class="card-title card-title-dash">Pendapatan Bulan Lalu</p>
                                <h3 class="rate-percentage"><?php echo $incomeLastMonthFormatted; ?></h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <p class="card-title card-title-dash">Pendapatan Tahun Lalu</p>
                                <h3 class="rate-percentage"><?php echo $incomeBeforeThisYearFormatted; ?></h3>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12 d-flex flex-column">
                            <div class="row flex-grow">
                              <div class="col-md-12 col-lg-12 grid-margin stretch-card">
                                <div class="card bg-primary card-rounded">
                                  <div class="card-body pb-0 ">
                                    <h4 class="card-title card-title-dash text-white mb-4">Status Summary</h4>
                                    <div class="row">
                                      <div class="col-sm-9">
                                        <p class="status-summary-ight-white mb-1">Total Transaksi</p>
                                        <h2 class="text-info"><?php echo $total_transaksi; ?></h2>
                                      </div>
                                      <div class="col-sm-12">
                                        <div class="status-summary-chart-wrapper pb-4">
                                          <canvas id="status-summary"></canvas>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <h4 class="card-title card-title-dash">Pendapatan Tahunan</h4>
                                <canvas id="yearlyIncomeChart"></canvas>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-6 grid-margin stretch-card">
                            <div class="card card-rounded">
                              <div class="card-body">
                                <h4 class="card-title card-title-dash">Pendapatan Bulanan</h4>
                                <canvas id="monthlyIncomeChart"></canvas>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-12 d-flex flex-column">
                            <div class="row flex-grow">
                              <div class="col-12 grid-margin stretch-card">
                                <div class="card card-rounded">
                                  <div class="card-body">
                                    <div class="d-sm-flex justify-content-between align-items-start">
                                      <div>
                                        <h4 class="card-title card-title-dash">Market Overview</h4>
                                        <p class="card-subtitle card-subtitle-dash">Total Pendapatan Keseluruhan</p>
                                      </div>
                                    </div>
                                    <div class="d-sm-flex align-items-center mt-4 justify-content-between">
                                      <div class="d-sm-flex align-items-center mt-4 justify-content-between">
                                        <h2 class="me-2 fw-bold"><?php echo $total_pendapatan_formatted; ?></h2>
                                        <h4 class="me-2">IDR</h4>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="row flex-grow">
                              <div class="col-12 grid-margin stretch-card">
                                <div class="card card-rounded">
                                  <div class="card-body">
                                    <div class="row">
                                      <div class="col-lg-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                          <div>
                                            <h4 class="card-title card-title-dash">Top Performer</h4>
                                          </div>
                                        </div>
                                        <div class="mt-3">
                                          <?php
                                          foreach ($transactionsByUser as $user) {
                                            // Menentukan path foto profil dengan memeriksa apakah ada dan valid
                                            $fotoProfil = !empty($user['rafi_profile']) ? "../pictprofile/" . $user['rafi_profile'] : 'images/faces/default.jpg';

                                            echo "<div class='wrapper d-flex align-items-center justify-content-between py-2 border-bottom'>";
                                            echo "<div class='d-flex'>";
                                            // Menampilkan foto profil dari database atau default jika tidak ada
                                            echo "<img class='img-sm rounded-10' src='" . $fotoProfil . "' alt='profile'>";
                                            echo "<div class='wrapper ms-3'>";
                                            echo "<p class='ms-1 mb-1 fw-bold'>" . $user['rafi_nama_lengkap'] . "</p>";
                                            echo "<small class='text-muted mb-0'>" . $user['total_transaksi'] . " Transactions</small>";
                                            echo "</div></div>";
                                            echo "</div>";
                                          }
                                          ?>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="modalLabelSuccess" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabelSuccess">Berhasil</h5>
                </div>
                <div class="modal-body">
                  <!-- Pesan akan diisi melalui JavaScript -->
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium Dashboard from BootstrapDash.</span>
              <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © Rafi Sanjaya Aplikasi Kasir 2024</span>
            </div>
          </footer>
        </div>
      </div>
    </div>
    <?php if (isset($_SESSION["success_message"])) : ?>
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          var myModal = new bootstrap.Modal(document.getElementById('successModal'));
          document.querySelector('#successModal .modal-body').textContent = "<?php echo $_SESSION["success_message"]; ?>";
          myModal.show();
        });
      </script>
      <?php unset($_SESSION["success_message"]); ?>
    <?php endif; ?>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/Chart.roundedBarCharts.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var ctxYearly = document.getElementById('yearlyIncomeChart').getContext('2d');
        var yearlyIncomeChart = new Chart(ctxYearly, {
          type: 'line',
          data: {
            labels: <?php echo json_encode($years); ?>.map(y => y + ""),
            datasets: [{
              label: 'Pendapatan Tahunan',
              data: <?php echo json_encode($yearlyIncome); ?>,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
              fill: true
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function(value) {
                    return formatRupiah(value);
                  }
                }
              }
            },
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                  return formatRupiah(tooltipItem.yLabel);
                }
              }
            }
          }
        });

        var ctxMonthly = document.getElementById('monthlyIncomeChart').getContext('2d');
        var monthlyIncomeChart = new Chart(ctxMonthly, {
          type: 'bar',
          data: {
            labels: <?php echo json_encode($months); ?>.map(m => monthName(m)),
            datasets: [{
              label: 'Pendapatan Bulanan',
              data: <?php echo json_encode($monthlyIncome); ?>,
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              borderColor: 'rgba(255, 99, 132, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: function(value) {
                    return formatRupiah(value);
                  }
                }
              }
            },
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                  return formatRupiah(tooltipItem.yLabel);
                }
              }
            }
          }
        });
      });

      function monthName(monthNumber) {
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return monthNames[monthNumber - 1]; // mengingat array dimulai dari 0
      }

      function formatRupiah(amount) {
        return 'Rp' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
      }
    </script>


</body>

</html>