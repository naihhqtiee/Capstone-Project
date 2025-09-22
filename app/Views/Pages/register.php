<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success text-center mb-0">
    <?= session()->getFlashdata('success') ?>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger text-center mb-0">
    <?= session()->getFlashdata('error') ?>
  </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Center for Human Rights Education</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php helper('url'); ?>
  <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: url('<?= base_url('images/front.jpg'); ?>') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 0;
    }

    .registration-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 500px;
      z-index: 1;
    }

    label {
      font-weight: 600;
      color: #01497c;
    }

    .form-control, .form-select {
      border-radius: 12px;
      padding: 12px;
    }

    .btn-submit {
      background-color: #007bff;
      color: white;
      padding: 12px 30px;
      border-radius: 30px;
      font-weight: bold;
      transition: 0.3s ease-in-out;
    }
        .register-line {
      margin-top: 20px;
      text-align: center;
      font-size: 15px;
    }

    .register-line a {
      margin-left: 5px;
      text-decoration: none;
      color: #007bff;
      font-weight: 500;
    }

    .register-line a:hover {
      text-decoration: underline;
    }

    .btn-submit:hover {
      background-color: #0056b3;
    }

    .navbar-custom {
      background-color: #002147;
      z-index: 2;
      position: relative;
    }

    .navbar-custom .nav-link {
      color: #fff !important;
      font-weight: 500;
      margin-right: 15px;
    }

    .navbar-custom .nav-link:hover {
      color: #ccc !important;
    }

    .navbar-brand img {
      height: 40px;
      margin-right: 10px;
    }

    .wrapper {
      background: #fff;
      padding: 60px;
      border-radius: 25px;
      box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.25);
      width: 100%;
      max-width: 500px;
      z-index: 1;
    }

    .wrapper .title {
      font-size: 32px;
      font-weight: 600;
      text-align: center;
      margin-bottom: 25px;
      color: #002147;
    }

    .form-container {
      width: 100%;
    }

    .field input {
      height: 50px;
      width: 100%;
      padding: 0 15px;
      border-radius: 15px;
      border: 1px solid #ccc;
      font-size: 16px;
      margin-bottom: 20px;
      transition: all 0.3s ease;
    }

    .field input:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .pass-link {
      text-align: right;
      margin-top: -15px;
      margin-bottom: 20px;
    }

    .pass-link a {
      color: #007bff;
      text-decoration: none;
    }

    .pass-link a:hover {
      text-decoration: underline;
    }

    .btn-layer {
      background: linear-gradient(90deg, #003366, #0056b3, #007bff);
      position: absolute;
      top: 0;
      left: -100%;
      width: 300%;
      height: 100%;
      border-radius: 15px;
      transition: 0.4s ease;
      z-index: 0;
    }
    

    .btn:hover .btn-layer {
      left: 0;
    }

    .btn input[type="submit"] {
      z-index: 1;
      position: relative;
      background: none;
      border: none;
      color: #fff;
      width: 100%;
      height: 100%;
      font-size: 18px;
      font-weight: 600;
      border-radius: 15px;
      cursor: pointer;
    }

    .btn {
      height: 50px;
      width: 100%;
      border-radius: 15px;
      position: relative;
      overflow: hidden;
    }

    .register-line {
      margin-top: 20px;
      text-align: center;
      font-size: 15px;
    }

    .register-line a {
      margin-left: 5px;
      text-decoration: none;
      color: #007bff;
      font-weight: 500;
    }

    .register-line a:hover {
      text-decoration: underline;
    }

    .navbar-custom {
      background-color: #002147;
      z-index: 2;
      position: relative;
    }

    .navbar-custom .nav-link {
      color: #fff !important;
      font-weight: 500;
    }

    .navbar-brand img {
      height: 40px;
      margin-right: 10px;
    }

    @media (max-width: 576px) {
      .wrapper {
        padding: 40px 30px;
      }
    }
  </style>
</head>
<body>


  <!-- Background Overlay -->
  <div class="overlay"></div>

  <!-- Centered Registration Form -->
  <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; position: relative; z-index: 2;">
    <div class="registration-box">
      <h2 class="text-center mb-4">Student Registration Form</h2>
      <form action="<?= base_url('register/save') ?>" method="post">
        
        <!-- Full Name -->
        <div class="mb-3">
          <label for="full_name">Name</label>
          <input type="text" name="full_name" class="form-control" required>
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="form-control"
                pattern="^[a-zA-Z0-9._%+-]+@my\.cspc\.edu\.ph$"
                pattern="^[a-zA-Z0-9._%+-]+@(cspc\.edu\.ph|my\.cspc\.edu\.ph)$"
        required
        oninvalid="this.setCustomValidity('Please enter a CSPC email (student: @my.cspc.edu.ph or employee: @cspc.edu.ph)')"
        oninput="this.setCustomValidity('')">
</div>

        <!-- Password -->
        <div class="mb-3">
          <label for="password">Password</label>
          <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <!-- Contact -->
       

        <!-- Submit Button -->
        <div class="text-center mt-4">
          <button type="submit" class="btn btn-submit">Register</button>
        </div>
          <div class="register-line">
            Already have an account?
            <a href="<?= base_url('login') ?>">Login</a>
          </div>
      </form>
    </div>
  </div>

  <!-- SweetAlert for Errors -->
<?php if (session()->getFlashdata('validation_errors')): 
    $errors = session()->getFlashdata('validation_errors');
    $firstField = array_key_first($errors);
?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let field = "<?= $firstField ?>";
      let message = "<?= esc($errors[$firstField]) ?>";

      Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        text: message,
        confirmButtonText: 'Okay'
      });
    });
  </script>
<?php endif; ?>


  <!-- SweetAlert for Success -->
  <?php if (session()->get('success_popup')): ?>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '<?= session()->get('success_popup'); ?>',
        confirmButtonText: 'Okay'
      });
    });
  </script>
  <?php endif; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
