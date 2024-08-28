<?php
session_start();
include "../../koneksi.php";
include_once "../function/proses_get_partial_data.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Petugas') {
  header("Location: ../../index.php");
  exit;
}

$type = $start = $end = "";
$results = [];

if (isset($_POST['submit'])) {
  $type = $_POST['type'];

  // Menyimpan tanggal pencarian ke dalam variabel $tanggalPencarian
  $tanggalPencarian = '';
  if ($type === 'harian') {
    $tanggalPencarian = $_POST['date']; // untuk harian, langsung gunakan tanggal
  } else if ($type === 'bulanan') {
    $tanggalPencarian = $_POST['month']; // untuk bulanan, gunakan bulan dan tahun
  } else if ($type === 'tahunan') {
    $tanggalPencarian = $_POST['year']; // untuk tahunan, gunakan tahun
  } else if ($type === 'custom') {
    $tanggalPencarian = $_POST['start'] . ' s/d ' . $_POST['end']; // untuk custom, gunakan range tanggal
  }

  // Menyimpan tanggal pencarian ke dalam sesi
  $_SESSION['search_date'] = $tanggalPencarian;

  switch ($type) {
    case 'harian':
      $query = "CALL sp_generate_laporan_harian(?)";
      $param = [$_POST['date']];
      break;
    case 'bulanan':
      $year = date('Y', strtotime($_POST['month']));
      $month = date('m', strtotime($_POST['month']));
      $query = "CALL sp_generate_laporan_bulanan(?, ?)";
      $param = [$year, $month];
      break;
    case 'tahunan':
      $year = $_POST['year'];
      $query = "CALL sp_generate_laporan_tahunan(?)";
      $param = [$year];
      break;
    case 'custom':
      $query = "CALL sp_generate_laporan_custom(?, ?)";
      $param = [$_POST['start'], $_POST['end']];
      break;
  }

  $stmt = $koneksi->prepare($query);
  if ($type === 'bulanan' || $type === 'custom') {
    $stmt->bind_param("ss", ...$param);
  } else {
    $stmt->bind_param("s", $param[0]);
  }
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $results[] = $row;
  }
  $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kasirinbro - Petugas</title>
  <link rel="stylesheet" href="../assets/vendors/feather/feather.css">
  <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../assets/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="../assets/vendors/simple-line-icons/css/simple-line-icons.css">
  <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../assets/vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../assets/vendors/css/custom-style.css">
  <link rel="stylesheet" href="../assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/vertical-layout-light/style.css">
  <link rel="shortcut icon" href="../../assets/ico/favicon.ico" />
</head>

<body onload="updateForm()">
  <div class="container-scroller">
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="generate_laporan.php">
            <img src="../../assets/ico/kasirinbro.png" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="generate_laporan.php">
            <img src="../../assets/ico/android-chrome-192x192.png" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Welcome, <span class="text-black fw-bold"><?php echo $_SESSION['rafi_username']; ?></span></h1>
            <h3 class="welcome-sub-text">Your performance summary this week</h3>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="<?php echo !empty($_SESSION['rafi_profile']) ? "../../pictprofile/" . $_SESSION['rafi_profile'] : '../images/faces/default.jpg'; ?>" alt="Profile image"></a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-xs rounded-circle" src="<?php echo !empty($_SESSION['rafi_profile']) ? "../../pictprofile/" . $_SESSION['rafi_profile'] : '../images/faces/default.jpg'; ?>" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['rafi_username']; ?> - <?php echo $_SESSION['rafi_role']; ?></p>
              </div>
              <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i>My Profile</a>
              <a href="../../logout.php" class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-danger me-2"></i>Sign Out</a>
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
            <img class="img-lg rounded-circle" src="<?php echo !empty($_SESSION['rafi_profile']) ? "../../pictprofile/" . $_SESSION['rafi_profile'] : '../images/faces/default.jpg'; ?>" alt="Profile image">
            <p class="mb-1 mt-3 font-weight-semibold"><?php echo $_SESSION['rafi_username']; ?></p>
          </div>
          <form action="../function/proses_update_profile.php" method="post" enctype="multipart/form-data">
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
            <a class="nav-link" href="../index.php">
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
                <li class="nav-item"><a class="nav-link" href="index.php">Lihat Riwayat Transaksi</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="generate_laporan.php">Generate Laporan</a></li>
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
                <li class="nav-item"> <a class="nav-link" href="tambah_petugas.php">Tambah Petugas</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Generate Laporan</h4>
                  <p class="card-description">
                    Add class <code>.table-bordered</code>
                  </p>
                  <form id="reportForm" action="" method="POST" class="row g-3">
                    <div class="col-md-4">
                      <label for="type" class="form-label">Jenis Laporan</label>
                      <select id="type" name="type" onchange="showRelevantInputFields()" class="form-select" required>
                        <option value="">Pilih Jenis Laporan...</option>
                        <option value="harian">Harian</option>
                        <option value="bulanan">Bulanan</option>
                        <option value="tahunan">Tahunan</option>
                        <option value="custom">Custom Range</option>
                      </select>
                    </div>
                    <div id="inputHarian" class="col-md-4" style="display:none;">
                      <label for="date" class="form-label">Tanggal</label>
                      <input type="date" class="form-control" id="date" name="date">
                    </div>

                    <div id="inputBulanan" class="col-md-4" style="display:none;">
                      <label for="start" class="form-label">Bulan</label>
                      <input type="month" class="form-control" id="month" name="month">
                    </div>
                    <div id="inputTahunan" class="col-md-4" style="display:none;">
                      <label for="start" class="form-label">Tahun</label>
                      <input type="number" class="form-control" id="year" name="year" min="1000" max="3000">
                    </div>
                    <div id="inputCustom" class="col-md-8" style="display:none;">
                      <div class="row">
                        <div class="col">
                          <label for="start" class="form-label">Tanggal Mulai</label>
                          <input type="date" class="form-control" name="start">
                        </div>
                        <div class="col">
                          <label for="end" class="form-label">Tanggal Akhir</label>
                          <input type="date" class="form-control" name="end">
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary btn-icon-text" name="submit"><i class="ti-reload btn-icon-prepend"></i>Generate</button>
                      <button type="button" class="btn btn-success btn-icon-text float-end" data-bs-toggle="modal" data-bs-target="#exportModal">
                        <i class="ti-export btn-icon-prepend"></i>Export
                      </button>
                    </div>
                  </form>
                  <div class="table-responsive pt-3">
                    <?php if (!empty($results)) : ?>
                      <?php
                      $results_reversed = array_reverse($results);
                      $_SESSION['export_data'] = $results_reversed;
                      $_SESSION['search_date'] = $tanggalPencarian // Tambahkan baris ini, sesuaikan variabel dengan yang Anda gunakan
                      ?>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>ID Transaksi</th>
                            <th>Petugas Melayani</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga Barang</th>
                            <th>Jumlah Pesanan</th>
                            <th>Subtotal</th>
                            <th>Total Keseluruhan</th>
                            <th>Total Pembayaran</th>
                            <th>Total Kembalian</th>
                            <th>Waktu Transaksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1; ?>
                          <?php $currentId = ''; ?>
                          <?php $totalPendapatan = 0; ?>
                          <?php foreach ($results_reversed as $index => $row) : ?>
                            <?php
                            $isNewTransaction = $row['rafi_id_transaksi'] !== $currentId;
                            if ($isNewTransaction) {
                              $currentId = $row['rafi_id_transaksi'];
                              $rowsCount = count(array_filter($results, function ($item) use ($currentId) {
                                return $item['rafi_id_transaksi'] === $currentId;
                              }));
                            }
                            ?>
                            <?php if ($isNewTransaction) : ?>
                              <?php if ($index > 0) : ?>
                                </tr> <!-- Penutup untuk item transaksi sebelumnya -->
                              <?php endif; ?>
                              <tr>
                                <td rowspan="<?= $rowsCount ?>"><?= $no++ ?></td>
                                <td rowspan="<?= $rowsCount ?>"><?= htmlspecialchars($row['rafi_id_transaksi']) ?></td>
                                <td rowspan="<?= $rowsCount ?>"><?= htmlspecialchars($row['rafi_username']) ?></td>
                              <?php else : ?>
                              <tr>
                              <?php endif; ?>
                              <td><?= htmlspecialchars($row['rafi_id_barang']) ?></td>
                              <td><?= htmlspecialchars($row['rafi_nama_barang']) ?></td>
                              <td>Rp. <?= number_format($row['rafi_harga_barang'], 2, ',', '.') ?></td>
                              <td><?= htmlspecialchars($row['rafi_jumlah_barang']) ?></td>
                              <td>Rp. <?= number_format($row['subtotal'], 2, ',', '.') ?></td>
                              <?php if ($isNewTransaction) : ?>
                                <td rowspan="<?= $rowsCount ?>">Rp. <?= number_format($row['rafi_total_keseluruhan'], 2, ',', '.') ?></td>
                                <td rowspan="<?= $rowsCount ?>">Rp. <?= number_format($row['rafi_total_pembayaran'], 2, ',', '.') ?></td>
                                <td rowspan="<?= $rowsCount ?>">Rp. <?= number_format($row['rafi_total_kembalian'], 2, ',', '.') ?></td>
                                <td rowspan="<?= $rowsCount ?>"><?= htmlspecialchars($row['rafi_date_transaksi']) ?></td>
                              <?php endif; ?>
                              <?php
                              if ($isNewTransaction) {
                                $totalPendapatan += $row['rafi_total_keseluruhan'];
                              }
                              ?>
                            <?php endforeach; ?>
                              </tr> <!-- Penutup untuk item transaksi terakhir -->
                              <tr>
                                <td class="bg-warning" colspan="2" style="text-align:center;"><strong>Total Pendapatan</strong></td>
                                <td class="bg-warning" colspan="10" style="text-align:center;"><strong>Rp. <?= number_format($totalPendapatan, 2, ',', '.') ?></strong></td>
                              </tr>
                        </tbody>
                      </table>
                    <?php else : ?>
                      <?php if (isset($_POST['submit'])) : ?>
                        <div class="alert alert-warning" role="alert">
                          Tidak ada data transaksi yang sesuai.
                        </div>
                      <?php else : ?>
                        <div class="alert alert-primary " role="alert">
                          Silahkan masukkan data.
                        </div>
                      <?php endif; ?>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exportModalLabel">Export Data</h5>
                </div>
                <div class="modal-body">
                  Pilih format file untuk mengekspor data
                </div>
                <div class="modal-footer">
                  <!-- Tombol Export ke PDF -->
                  <button type="button" class="btn btn-danger me-3 d-flex align-items-center justify-content-center" onclick="location.href='../function/export_to_pdf.php'" title="Export to PDF">
                    <i class="ti-file"></i>
                    <span class="ms-2" style="font-size: 16px;">PDF</span>
                  </button>
                  <!-- Tombol Export ke Excel -->
                  <button type="button" class="btn btn-success d-flex align-items-center justify-content-center" onclick="location.href='../function/export_to_xls.php'" title="Export to Excel">
                    <i class="ti-layout-tab"></i>
                    <span class="ms-2" style="font-size: 16px;">XLSX</span>
                  </button>
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
    <script src="../assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="../assets/vendors/chart.js/Chart.min.js"></script>
    <script src="../assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="../assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="../assets/js/off-canvas.js"></script>
    <script src="../assets/js/hoverable-collapse.js"></script>
    <script src="../assets/js/template.js"></script>
    <script src="../assets/js/settings.js"></script>
    <script src="../assets/js/todolist.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/Chart.roundedBarCharts.js"></script>
    <script>
      function showRelevantInputFields() {
        var type = document.getElementById("type").value;

        // Sembunyikan semua input
        document.getElementById("inputHarian").style.display = 'none';
        document.getElementById("inputBulanan").style.display = 'none';
        document.getElementById("inputTahunan").style.display = 'none';
        document.getElementById("inputCustom").style.display = 'none';

        // Tampilkan input yang relevan
        if (type === "harian") {
          document.getElementById("inputHarian").style.display = 'block';
        } else if (type === "bulanan") {
          document.getElementById("inputBulanan").style.display = 'block';
        } else if (type === "tahunan") {
          document.getElementById("inputTahunan").style.display = 'block';
        } else if (type === "custom") {
          document.getElementById("inputCustom").style.display = 'block';
        }
      }
    </script>
</body>

</html>