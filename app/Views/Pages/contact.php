<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - Center for Human Rights Education</title>
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

    .contact-section {
      background: white;
      padding: 60px 0;
      position: relative;
      z-index: 1;
    }

    .contact-card {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      height: 100%;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .contact-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .contact-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #003366, #007bff);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      color: white;
      font-size: 2rem;
    }

    .contact-info h5 {
      color: #002147;
      margin-bottom: 15px;
      font-weight: 600;
    }

    .contact-info p {
      color: #6c757d;
      margin-bottom: 10px;
      line-height: 1.6;
    }

    .contact-info a {
      color: #007bff;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .contact-info a:hover {
      color: #0056b3;
    }

    .map-container {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-top: 40px;
    }

    .map-frame {
      width: 100%;
      height: 400px;
      border: none;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }



    .contact-form {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      margin-top: 40px;
    }

    .form-control {
      border-radius: 10px;
      border: 2px solid #e9ecef;
      padding: 12px 15px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .btn-submit {
      background: linear-gradient(135deg, #003366, #007bff);
      border: none;
      color: white;
      padding: 12px 30px;
      border-radius: 25px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-submit:hover {
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
      
      .contact-card {
        margin-bottom: 30px;
        padding: 30px 20px;
      }
      
      .map-frame {
        height: 300px;
      }
    }

    @media (max-width: 576px) {
      .hero-section h1 {
        font-size: 2rem;
      }
      
      .hero-section p {
        padding: 130px 0 0 0;
        font-size: 1rem;
      }
      
      .contact-card {
        padding: 25px 20px;
      }
      
      .map-frame {
        height: 250px;
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

          <p class="text-white mb-4" style="padding-top: 20px;">Get in touch with the Center for Human Rights Education. We're here to help and support you.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact-section">
    <div class="container">
      <!-- Contact Information Cards -->
      <div class="row justify-content-center mb-5">
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="contact-card">
            <div class="contact-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="contact-info text-center">
              <h5>Our Location</h5>
              <p><strong>Address:</strong><br>
              <!-- Leave blank for now - will be filled in later -->
              </p>
            </div>
          </div>
        </div>
        
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="contact-card">
            <div class="contact-icon">
              <i class="fas fa-envelope"></i>
            </div>
            <div class="contact-info text-center">
              <h5>Email Us</h5>
              <p><strong>Email:</strong><br>
              <!-- Leave blank for now - will be filled in later -->
              </p>
            </div>
          </div>
        </div>
      </div>
      </div>

      <!-- Map Section -->
      <div class="map-container">
        <h3 class="text-center mb-4">Find Us</h3>
        <p class="text-center mb-4">The Center for Human Rights Education is located at Camarines Sur Polytechnic Colleges (CSPC) in Bicol.</p>
        <div class="map-frame" id="map">
          <!-- Google Maps will be embedded here -->
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.1234567890123!2d123.3720!3d13.4059!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a14f5b1c8b4a91%3A0x7e8c4b2a5d9e6f10!2sCSPC%20-%20Camarines%20Sur%20Polytechnic%20Colleges!5e0!3m2!1sen!2sph!4v1234567890123&zoom=17&maptype=roadmap"
            width="100%" 
            height="100%" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
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
