<?php
session_start();
include "../koneksi.php";
include_once "function/proses_get_partial_data.php";
if (!isset($_SESSION['rafi_id_users']) || $_SESSION['rafi_role'] != 'Petugas') {
  header("Location: ../index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kasirinbro - Petugas</title>
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
            <h3 class="welcome-sub-text">Your performance summary this </h3>
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
                <li class="nav-item"> <a class="nav-link" href="pages/tambah_petugas.php">Tambah Petugas</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="col-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <h4 class="card-title">Tambah Transaksi</h4>
                <p class="card-description">
                  Basic form elements
                </p>
                <form action="function/proses_transaksi.php" method="post">
                  <div class="form-group">
                    <label for="id_barang">Kode Barang</label>
                    <input type="text" name="rafi_id_barang" class="form-control" required>
                  </div>
                  <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" name="rafi_jumlah" class="form-control" required>
                  </div>
                  <button type="submit" class="btn btn-primary btn-block">Tambah ke Keranjang</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
              <div class="card-body">
                <?php if (!empty($_SESSION['keranjang'])) : ?>
                  <h4 class="card-title">Keranjang Belanja</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Nama Barang</th>
                          <th>Harga Barang</th>
                          <th>Jumlah Barang</th>
                          <th>Total Harga</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $total_belanja = 0;
                        foreach ($_SESSION['keranjang'] as $item => $barang) :
                          $total_harga = $barang['rafi_jumlah_barang'] * $barang['rafi_harga_barang'];
                          $total_belanja += $total_harga;
                        ?>
                          <tr>
                            <td><?php echo $barang['rafi_nama_barang']; ?></td>
                            <td>Rp <?php echo number_format($barang['rafi_harga_barang'], 2, ',', '.'); ?></td>
                            <td>x<?php echo $barang['rafi_jumlah_barang']; ?></td>
                            <td>Rp <?php echo number_format($total_harga, 2, ',', '.'); ?></td>
                            <td><a href='function/proses_hapus_item.php?barang=<?php echo $item; ?>' class="btn btn-danger"><i class='ti-trash'></a></td>
                          </tr>
                        <?php endforeach; ?>
                        <tr>
                          <td colspan='4'><strong>Total</strong></td>
                          <td colspan='2'><strong>Rp <?php echo number_format($total_belanja, 2, ',', '.'); ?></strong></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <form id="formPembayaran" onsubmit="return showConfirmModal()">
                    <div class="form-group">
                      <label for="bayar">Jumlah Bayar</label>
                      <input type="text" id="inputBayar" name="bayar" class="form-control" required>
                    </div>
                    <button type="submit" name="bayaruang" class="btn btn-success btn-block">Proses Pembayaran</button>
                  </form>
                <?php else : ?>
                  <p>Keranjang belanja kosong</p>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <div class="modal fade" id="konfirmasiPembayaranDetailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="detailModalLabel">Detail Pembayaran</h5>
                </div>
                <div class="modal-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Nama Barang</th>
                        <th>Harga Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Total Harga</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($_SESSION['keranjang'] as $item => $barang) : ?>
                        <tr>
                          <td><?php echo htmlspecialchars($barang['rafi_nama_barang']); ?></td>
                          <td>Rp <?php echo number_format($barang['rafi_harga_barang'], 2, ',', '.'); ?></td>
                          <td>x<?php echo htmlspecialchars($barang['rafi_jumlah_barang']); ?></td>
                          <td>Rp <?php echo number_format($barang['rafi_jumlah_barang'] * $barang['rafi_harga_barang'], 2, ',', '.'); ?></td>
                        </tr>
                      <?php endforeach; ?>
                      <tr>
                        <td colspan='3'><strong>Total</strong></td>
                        <td colspan='2' class="bg-warning"><strong>Rp <?php echo number_format($total_belanja, 2, ',', '.'); ?></strong></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  <button type="button" class="btn btn-primary" onclick="submitForm()">Konfirmasi Pembayaran</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="transaksiSuksesModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel">Transaksi Sukses</h5>
                </div>
                <div class="modal-body">
                  <div id="strukPembayaran">
                    <h4>Faktur Pembayaran</h4>
                    <div class="row">
                      <div class="col-6">No Pesanan :</div>
                      <div class="col-6"><strong><span id="noPesanan"></span></strong></div>
                    </div>
                    <div class="row">
                      <div class="col-6">Petugas Melayani :</div>
                      <div class="col-6"><strong><span id="namaPetugas"></span></strong></div>
                    </div>
                    <div class="row">
                      <div class="col-6">Total Pembelian :</div>
                      <div class="col-6"><strong>Rp <span id="totalBelanja"></span></strong></div>
                    </div>
                    <div class="row">
                      <div class="col-6">Total Tunai :</div>
                      <div class="col-6"><Strong>Rp <span id="bayar"></span></Strong></div>
                    </div>
                    <div class="row">
                      <div class="col-6">Total Kembalian :</div>
                      <div class="col-6"><strong>Rp <span id="kembalian"></span></strong></div>
                    </div>
                    <div class="row">
                      <div class="col-6">Waktu Pembelian :</div>
                      <div class="col-6"><strong><span id="waktuPembayaran"></span></strong></div>
                    </div>
                    <hr>
                    <h4>Detail Pemesanan</h4>
                    <div class="table-responsive">
                      <table class="table" id="detailPemesanan">
                        <thead>
                          <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga Barang</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- Isi dari JavaScript -->
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary btn-icon-text" onclick="printStruk()">Print
                    <i class="ti-printer btn-icon-append"></i>
                  </button>
                  <button type="button" class="btn btn-success" onclick="window.location.href='index.php'">OK</button>
                </div>
              </div>
            </div>
          </div>
          <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel">Error</h5>
                </div>
                <div class="modal-body">
                  <!-- Pesan akan diisi melalui JavaScript -->
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
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
          <div class="modal fade" id="uangKurangModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel">Peringatan</h5>
                </div>
                <div class="modal-body">
                  Uang yang dibayarkan kurang dari total belanja. Silahkan cek kembali.
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Coba Lagi</button>
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
    <?php if (isset($_SESSION["error_message"])) : ?>
      <script>
        $(document).ready(function() {
          $('#errorModal .modal-body').text("<?php echo $_SESSION["error_message"]; ?>");
          $('#errorModal').modal('show');
        });
      </script>
      <?php unset($_SESSION["error_message"]); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION["success_message"])) : ?>
      <script>
        $(document).ready(function() {
          $('#successModal .modal-body').text("<?php echo $_SESSION["success_message"]; ?>");
          $('#successModal').modal('show');
        });
      </script>
      <?php unset($_SESSION["success_message"]); ?>
    <?php endif; ?>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const inputBayar = document.getElementById('inputBayar');
        inputBayar.addEventListener('input', function(e) {
          const value = this.value.replace(/\D/g, '');
          const formattedValue = new Intl.NumberFormat('id-ID', {
            style: 'decimal'
          }).format(value);
          this.value = formattedValue;
        });
      });
      window.addEventListener('DOMContentLoaded', (event) => {
        const now = new Date();
        const options = {
          year: 'numeric',
          month: '2-digit',
          day: '2-digit',
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          hour12: false
        };
        const dateTimeString = now.toLocaleDateString('id-ID', options).replace(',', '');
        document.getElementById('tanggalTransaksi').textContent = dateTimeString;
      });

      function updateWaktuPembayaran() {
        const now = new Date();
        const options = {
          year: 'numeric',
          month: '2-digit',
          day: '2-digit',
          hour: '2-digit',
          minute: '2-digit',
          second: '2-digit',
          hour12: false
        };
        const dateTimeString = now.toLocaleDateString('id-ID', options).replace(',', '');
        document.getElementById('waktuPembayaran').textContent = dateTimeString;
      }

      function printStruk() {
        var printContents = document.getElementById('strukPembayaran').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
      }
      $('#transaksiSuksesModal').on('shown.bs.modal', function() {
        updateWaktuPembayaran();
      });

      function fillDetailTransaksi() {
        const detailPemesananBody = document.getElementById('detailPemesanan').getElementsByTagName('tbody')[0];
        const keranjang = <?php echo json_encode($_SESSION["keranjang"]); ?>;
        let totalBelanja = 0;
        keranjang.forEach(barang => {
          const totalHarga = barang.rafi_jumlah_barang * barang.rafi_harga_barang; // Sesuaikan dengan struktur objek keranjang Anda
          totalBelanja += totalHarga;
          const row = `
      <tr>
        <td>${barang.rafi_nama_barang}</td> <!-- Sesuaikan dengan nama properti pada objek keranjang -->
        <td>x${barang.rafi_jumlah_barang}</td> <!-- Sesuaikan dengan nama properti pada objek keranjang -->
        <td>Rp ${new Intl.NumberFormat('id-ID').format(barang.rafi_harga_barang)}</td>
        <td>Rp ${new Intl.NumberFormat('id-ID').format(totalHarga)}</td>
      </tr>
    `;
          detailPemesananBody.innerHTML += row;
        });
        document.getElementById('totalBelanja').textContent = new Intl.NumberFormat('id-ID').format(totalBelanja);
      }
      $('#transaksiSuksesModal').on('shown.bs.modal', function() {
        fillDetailTransaksi();
        updateWaktuPembayaran();
      });

      function convertRupiahToNumber(rupiah) {
        return parseInt(rupiah.split('.').join(''));
      }
      document.getElementById('formPembayaran').addEventListener('submit', function(event) {
        event.preventDefault();
        var totalKeseluruhan = <?php echo $total_belanja; ?>;
        var totalUangPembeli = document.querySelector('[name="bayar"]').value;
        var totalUangPembeliNumber = convertRupiahToNumber(totalUangPembeli);
        if (totalUangPembeliNumber < totalKeseluruhan) {
          $('#uangKurangModal').modal('show');
          return;
        }
        $('#totalBelanja').text(new Intl.NumberFormat('id-ID').format(totalKeseluruhan));
        $('#bayar').text(new Intl.NumberFormat('id-ID').format(totalUangPembeliNumber));
        $('#kembalian').text(new Intl.NumberFormat('id-ID').format(totalUangPembeliNumber - totalKeseluruhan));
        $('#konfirmasiPembayaranDetailModal').modal('show');
      });

      function submitForm() {
        var bayar = document.querySelector('[name="bayar"]').value;
        var bayarNumber = convertRupiahToNumber(bayar);
        $.ajax({
          url: 'function/proses_pembayaran.php',
          type: 'POST',
          data: {
            bayaruang: true,
            bayar: bayarNumber,
          },
          success: function(response) {
            var data = JSON.parse(response);
            if (data.status === "success") {
              $('#konfirmasiPembayaranDetailModal').modal('hide');
              setTimeout(function() {
                $('#noPesanan').text(data.no_pesanan);
                $('#namaPetugas').text(data.nama_petugas);
                $('#transaksiSuksesModal').modal('show');
              }, 500);
            } else {
              $('#konfirmasiPembayaranDetailModal').modal('hide');
              setTimeout(function() {
                $('#errorModal .modal-body').text(data.message);
                $('#errorModal').modal('show');
              }, 500);
            }
          }
        });
      }
    </script>
</body>

</html>