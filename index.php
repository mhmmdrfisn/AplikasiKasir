<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kasirinbro</title>
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="shortcut icon" href="assets/ico/favicon.ico" />
</head>
<style>
  .toast-container {
    display: flex;
    align-items: center;
    gap: 10px;
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    padding: 10px;
    background-color: #ffffff;
    color: #333;
    border: none;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    visibility: hidden;
    opacity: 0;
    transition: visibility 0s, opacity 0.5s ease;
    z-index: 1000;
    overflow: hidden;
  }

  .toast-container.show {
    visibility: visible;
    opacity: 1;
  }

  .toast-success {
    background-color: #ffffff;
    border: none;
  }

  .toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 5px;
    width: 100%;
    transition: width 5s linear;
  }

  .toast-progress.error {
    background: red;
    /* Warna merah soft untuk error */
  }

  .toast-progress.success {
    background: #4CAF50;
    /* Warna hijau soft untuk success, diubah dari green ke kode warna hijau spesifik */
  }

  .toast-message {
    flex-grow: 1;
    margin-bottom: 8px;
  }

  .toast-icon {
    font-size: 24px;
  }

  .toast-icon.error {
    color: #ff0000;
    /* Warna merah untuk icon error */
  }

  .toast-icon.success {
    color: #4CAF50;
    /* Warna hijau untuk icon success, diubah dari #ff0000 ke kode warna hijau spesifik */
  }
</style>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row flex-grow">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <img src="assets/ico/kasirinbro.png">
              </div>
              <h4>Hello! let's get started</h4>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form action="proses_login.php" method="post" class="pt-3">
                <div class="form-group">
                  <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Username">
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="mt-3">
                  <input type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="submit" value="Login">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="login-toast" class="toast-container">
    <i class="mdi mdi-alert-circle-outline toast-icon"></i>
    <span class="toast-message"></span>
    <div class="toast-progress"></div>
  </div>
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/misc.js"></script>
  <script>
    function showToast(message, isSuccess = false) {
      const toast = document.getElementById('login-toast');
      const progressBar = toast.querySelector('.toast-progress');
      const toastMessage = toast.querySelector('.toast-message');
      const toastIcon = toast.querySelector('.toast-icon');
      progressBar.style.width = '100%';
      toastMessage.textContent = message;
      if (isSuccess) {
        toast.classList.add('toast-success');
        progressBar.classList.add('success');
        progressBar.classList.remove('error');
        toastIcon.classList.add('success');
        toastIcon.classList.remove('error');
      } else {
        toast.classList.remove('toast-success');
        progressBar.classList.add('error');
        progressBar.classList.remove('success');
        toastIcon.classList.add('error');
        toastIcon.classList.remove('success');
      }
      toast.classList.add('show');

      setTimeout(() => {
        progressBar.style.width = '0%';
      }, 10);

      setTimeout(() => {
        toast.classList.remove('show');
      }, 5000);
    }
    window.onload = function() {
      const urlParams = new URLSearchParams(window.location.search);
      const error = urlParams.get('error');
      const success = urlParams.get('succes');
      if (error) {
        let message;
        switch (error) {
          case 'emptyfields':
            message = 'Harap isi semua bidang!';
            break;
          case 'wrongpassword':
            message = 'Password salah!';
            break;
          case 'nouser':
            message = 'Tidak ada pengguna dengan username tersebut!';
            break;
          default:
            message = 'Terjadi kesalahan, silakan coba lagi!';
            break;
        }
        showToast(message);
      } else if (success === 'logout') {
        showToast('Berhasil logout', true);
      }
    };
  </script>
</body>

</html>