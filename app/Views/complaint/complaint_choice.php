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
        .overlay {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 0;
    }

    .card-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      max-width: 450px;
      width: 100%;
      z-index: 2;
    }

    .btn-custom {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      font-weight: bold;
      border-radius: 30px;
      transition: 0.3s;
    }

    .btn-anonymous {
      background-color: #6c757d;
      color: white;
    }

    .btn-anonymous:hover {
      background-color: #5a6268;
    }

    .btn-identified {
      background-color: #007bff;
      color: white;
    }

    .btn-identified:hover {
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
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-custom px-4">
    <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/'); ?>">
      <img src="<?= base_url('images/cspclogo.png'); ?>" alt="CSPC">
      <img src="<?= base_url('images/logochre.jpg'); ?>" alt="CHRE" class="rounded-circle">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('/'); ?>">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('about'); ?>">About</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('file-complaint'); ?>">File a complaint</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('login'); ?>">Login</a></li>
      </ul>
    </div>
  </nav>
<!-- Overlay -->
<div class="overlay"></div>

<!-- Centered Card -->
<div class="d-flex align-items-center justify-content-center" style="min-height: 100vh; position: relative; z-index: 2;">
  <div class="card-box text-center">
    <h4 class="mb-4">How would you like to file your complaint?</h4>
    <a href="<?= base_url('complaint/anonymous'); ?>" class="btn btn-custom btn-anonymous">File Anonymously</a>
    <a href="<?= base_url('register'); ?>" class="btn btn-custom btn-identified">File with Identity</a>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
