<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Center for Human Rights Education</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php helper('url'); ?>
  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    body { background: url('<?= base_url('images/front.jpg'); ?>') no-repeat center center fixed; background-size: cover; height: 100vh; }
    .overlay { position: absolute; top: 0; left: 0; height: 100%; width: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 0; }
    .wrapper .title { font-size: 32px; font-weight: 600; text-align: center; margin-bottom: 25px; color: #002147; }
    .form-container { width: 100%; }
    .field input { height: 50px; width: 100%; padding: 0 15px; border-radius: 15px; border: 1px solid #ccc; font-size: 16px; margin-bottom: 20px; transition: all 0.3s ease; }
    .field input:focus { border-color: #007bff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
    .pass-link { text-align: right; margin-top: -15px; margin-bottom: 20px; }
    .pass-link a { color: #007bff; text-decoration: none; }
    .pass-link a:hover { text-decoration: underline; }
    .btn-layer { background: linear-gradient(90deg, #003366, #0056b3, #007bff); position: absolute; top: 0; left: -100%; width: 300%; height: 100%; border-radius: 15px; transition: 0.4s ease; z-index: 0; }
    .btn:hover .btn-layer { left: 0; }
    .btn input[type="submit"] { z-index: 1; position: relative; background: none; border: none; color: #fff; width: 100%; height: 100%; font-size: 18px; font-weight: 600; border-radius: 15px; cursor: pointer; }
    .btn { height: 50px; width: 100%; border-radius: 15px; position: relative; overflow: hidden; }
    .register-line { margin-top: 20px; text-align: center; font-size: 15px; }
    .register-line a { margin-left: 5px; text-decoration: none; color: #007bff; font-weight: 500; }
    .register-line a:hover { text-decoration: underline; }
    .back-button { position: absolute; top: 20px; left: 20px; background: #002147; color: white; border: none; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; z-index: 3; }
    .wrapper { background: #fff; padding: 60px; border-radius: 25px; box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.25); width: 100%; max-width: 500px; z-index: 1; position: relative; }
    .back-button:hover { background: #003366; transform: scale(1.1); }
    .back-button i { font-size: 18px; }
    .navbar-custom { background-color: #002147; z-index: 2; position: relative; }
    .navbar-custom .nav-link { color: #fff !important; font-weight: 500; }
    .navbar-brand img { height: 40px; margin-right: 10px; }
    @media (max-width: 576px) { .wrapper { padding: 40px 30px; } }
  </style>
</head>
<body>

  <!-- Overlay background -->
  <div class="overlay"></div>

  <!-- Login Centered -->
  <div class="d-flex align-items-center justify-content-center" style="min-height: 90vh; position: relative; z-index: 2;">
    <div class="wrapper">
      <button class="back-button" onclick="history.back()" title="Go Back">
        <i class="fas fa-arrow-left"></i>
      </button>
      <div class="title">Login</div>
      <div class="form-container">
        <form action="<?= base_url('auth/login') ?>" method="post">
          <div class="field">
            <input type="email" name="email" placeholder="Email" required>
          </div>
          <div class="field">
            <input type="password" name="password" placeholder="Password" required>
          </div>

          <!-- Forgot Password -->
          <div class="pass-link mb-3">
            <a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot password?</a>
          </div>

          <!-- Normal Login Button -->
          <div class="btn mb-3">
            <div class="btn-layer"></div>
            <input type="submit" value="Login">
          </div>
        </form>

        <!-- Google Login Button -->
        <a href="<?= base_url('google/login') ?>" class="btn btn-outline-primary">
          <i class="fab fa-google"></i> Sign in with Google
        </a>

        <!-- Register Line -->
        <div class="register-line">
          Don't have an account?
          <a href="<?= base_url('register') ?>">Register</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Forgot Password Modal -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="<?= base_url('forgot-password/send') ?>" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="forgotPasswordLabel">Forgot Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Enter your email to receive a password reset link.</p>
            <div class="mb-3">
              <label for="forgotEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="forgotEmail" name="email" required>
            </div>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Send Reset Link</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Auto-open modal if there was a forgot-password error -->
  <script>
    <?php if(session()->getFlashdata('error')): ?>
      var forgotModal = new bootstrap.Modal(document.getElementById('forgotPasswordModal'));
      forgotModal.show();
    <?php endif; ?>
  </script>

  <!-- SweetAlert for Login Error -->
  <?php if (session()->getFlashdata('error')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?= session()->getFlashdata('error'); ?>',
        confirmButtonText: 'Try Again'
      });
    });
  </script>
  <?php endif; ?>

  <!-- SweetAlert for Login Success -->
  <?php if (session()->getFlashdata('success')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?= session()->getFlashdata('success'); ?>',
        confirmButtonText: 'Okay'
      });
    });
  </script>
  <?php endif; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
