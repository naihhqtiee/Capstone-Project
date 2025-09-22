<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Center for Human Rights Education</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
      background: #f8f9fa;
      min-height: 100vh;
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

    .hero-section {
      position: relative;
      padding: 120px 0 80px 0;
      color: white;
      text-align: center;
      min-height: 60vh;
      display: flex;
      align-items: center;
      background: url('<?= base_url('images/front.jpg'); ?>') no-repeat center center;
      background-size: cover;
    }

    .hero-section h1 {
      font-size: 3.5rem;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
      line-height: 1.2;
    }

    .hero-section p {
      font-size: 1.2rem;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    .hero-buttons .btn {
      padding: 12px 30px;
      border-radius: 25px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .hero-buttons .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .about-section {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-top: 0;
      position: relative;
      z-index: 1;
    }

    .highlight-box {
      background: #f8f9fa;
      padding: 25px;
      border-radius: 10px;
      margin: 20px 0;
      border-left: 4px solid #007bff;
    }

    .highlight-box h5 {
      color: #002147;
      margin-bottom: 15px;
    }

    .image-grid img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
      margin-bottom: 15px;
    }

    .btn-custom {
      background: linear-gradient(90deg, #003366, #0056b3, #007bff);
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 25px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      margin: 0 10px;
      transition: all 0.3s ease;
    }

    .btn-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      color: white;
    }



    @media (max-width: 768px) {
      .hero-section {
        padding: 100px 0 60px 0;
        min-height: 50vh;
      }
      
      .hero-section h1 {
        font-size: 2.5rem;
      }
      
      .about-section {
        margin-top: 0;
        padding: 30px 20px;
      }
    }

    @media (max-width: 576px) {
      .wrapper {
        padding: 40px 30px;
      }
      
      .hero-section h1 {
        font-size: 2rem;
      }
      
      .hero-section p {
        font-size: 1rem;
        padding: 130px 0 0 0;
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
        <li class="nav-item"><a class="nav-link" href="<?= base_url('contact'); ?>">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('login'); ?>">Login</a></li>
      </ul>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 text-center">
       
          <p class="text-white mb-4" style="padding: 80px 0 0 0;">Promoting human dignity, justice, and freedom through education, advocacy, and community empowerment.</p>
   
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section class="py-5">
    <div class="container">
      <div class="about-section">
        <h2 class="text-center mb-4">Our Mission & Vision</h2>
      <p>
        The <strong>Center for Human Rights Education (CHRE)</strong> of Camarines Sur Polytechnic Colleges (CSPC) is a vital institutional arm committed to promoting human dignity, justice, and freedom through education, advocacy, and community empowerment.
      </p>
      <p>
        CHRE stands as a beacon of awareness and support for individuals seeking justice and equality. Through well-planned programs and partnerships with local and national human rights bodies, the center fosters an inclusive environment where rights are respected and protected.
      </p>

      <div class="highlight-box">
        <h5>Our Core Objectives</h5>
        <ul>
          <li>Provide education and training on human rights principles and laws.</li>
          <li>Empower students and citizens to become advocates for justice.</li>
          <li>Serve as a platform for reporting and addressing human rights concerns within the institution and the community.</li>
        </ul>
      </div>

      <div class="highlight-box">
        <h5>Why It Matters</h5>
        <p>
          In today’s world, where discrimination, abuse, and inequality persist, CHRE plays a critical role in shaping a more informed and compassionate generation. It serves not only as an educational resource but also as a support system for those whose rights have been violated or overlooked.
        </p>
      </div>

      <p class="mt-4">
        Through this Capstone Project, we aim to develop a digital platform that enhances the efficiency and accessibility of CHRE’s services — allowing complainants to report incidents, administrators to act on concerns, and everyone to stay informed.
      </p>

             <!-- Images Section -->
       <div class="row image-grid">
         <div class="col-md-4 mb-4">
           <img src="<?= base_url('images/chre1.jpg'); ?>" alt="CHRE Event 1">
         </div>
                   <div class="col-md-4 mb-4">
            <img src="<?= base_url('images/chre2.jpg'); ?>" alt="CHRE Event 2">
          </div>
                   <div class="col-md-4 mb-4">
            <img src="<?= base_url('images/chre3.jpg'); ?>" alt="CHRE Event 3">
          </div>
       </div>
     </div>
   </section>

<!-- Include Footer -->
<?php include APPPATH . 'Views/components/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
