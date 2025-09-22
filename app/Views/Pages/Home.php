<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Center for Human Rights Education</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php helper('url'); ?>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }

    /* Navbar */
    .navbar-custom { background-color: #002147; padding-left: 12px; padding-right: 12px; }
    .navbar-custom .nav-link { color: #fff !important; font-weight: 500; margin-right: 15px; transition: 0.3s; }
    .navbar-custom .nav-link:hover { color: #ccc !important; }
    .navbar-brand img { height: 40px; margin-right: 10px; }
    .navbar-custom .nav-link:hover { color: #ffc107 !important; }

    /* Hero */
    .hero {
      background: url('<?= base_url("images/front.jpg"); ?>') no-repeat center center/cover;
      min-height: 100vh; display: flex; align-items: center; justify-content: center;
      text-align: center; color: white; position: relative;
    }
    .hero::before {
      content: ""; position: absolute; top:0; left:0; width:100%; height:100%;
      background: rgba(0,0,0,0.6);
    }
    .hero-content { position: relative; z-index: 2; max-width: 700px; }
    .hero h1 { font-size: 3rem; font-weight: 700; }
    .hero p { font-size: 1.3rem; margin-bottom: 30px; }
    .btn-main {
      background: #007bff; color: white; padding: 12px 30px;
      border-radius: 30px; transition: 0.3s;
    }
    .btn-main:hover { background: #0056b3; transform: scale(1.05); }

    /* Features */
    .features .card { transition: transform 0.3s, box-shadow 0.3s; border: none; }
    .features .card:hover { transform: translateY(-8px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }

    /* Stats */
    .stats { background: #f8f9fa; padding: 60px 0; }
    .stat-box { text-align: center; }
    .stat-box h2 { font-size: 2.5rem; font-weight: bold; color: #004080; }

    /* Testimonials */
    .testimonials { background: #002147; color: white; padding: 60px 0; }
    .testimonial-item { max-width: 700px; margin: auto; font-style: italic; }

    /* Footer */
    footer { background: #002147; color: #fff; padding: 40px 0; }
    footer a { color: #bbb; text-decoration: none; }
    footer a:hover { color: #fff; }
    .social-icons a { margin: 0 8px; font-size: 20px; color: #bbb; transition: 0.3s; }
    .social-icons a:hover { color: #ffc107; }

    /* Typewriter effect */
    .typewriter span { border-right: 2px solid #fff; padding-right: 5px; animation: blink 0.7s infinite; }
    @keyframes blink { 50% { border-color: transparent; } }

    /* About styles (from About page) */
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
    .highlight-box h5 { color: #002147; margin-bottom: 15px; }
    .image-grid img { width: 100%; height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; }

    /* Contact styles (from Contact page) */
    .contact-section { background: white; padding: 60px 0; position: relative; z-index: 1; }
    .contact-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); height: 100%; transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .contact-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
    .contact-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #003366, #007bff); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem; }
    .contact-info h5 { color: #002147; margin-bottom: 15px; font-weight: 600; }
    .contact-info p { color: #6c757d; margin-bottom: 10px; line-height: 1.6; }
    .contact-info a { color: #007bff; text-decoration: none; transition: color 0.3s ease; }
    .contact-info a:hover { color: #0056b3; }
    .map-container { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-top: 40px; }
    .map-frame { width: 100%; height: 400px; border: none; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.1); }
  </style>
</head>
<body>
  <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom px-4 fixed-top">
  <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/'); ?>">
    <img src="<?= base_url('images/cspclogo.png'); ?>" alt="CSPC">
    <img src="<?= base_url('images/logochre.jpg'); ?>" alt="CHRE" class="rounded-circle">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
      <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
      <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
      <li class="nav-item"><a class="nav-link" href="<?= base_url('login'); ?>">Login</a></li>
    </ul>
  </div>
</nav>

  

  <!-- Hero -->
  <section id="hero" class="hero">
    <div class="hero-content" data-aos="fade-up">
      <h1>Center for Human Rights Education</h1>
      <p class="typewriter"><span id="typewriter"></span></p>
      <a href="#" id="fileComplaintBtnHero" class="btn btn-main">File a Complaint</a>
      <a href="#about" class="btn btn-warning ms-2">Learn More</a>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 data-aos="fade-down">About Us</h2>
        <p data-aos="fade-down" data-aos-delay="100">
          We empower communities by promoting awareness of human rights and providing accessible channels for justice.
        </p>
      </div>

      <!-- Key pillars cards -->
      <div class="row g-4 features">
        <div class="col-md-4" data-aos="zoom-in">
          <div class="card h-100 text-center p-3 shadow-sm">
            <i class="fas fa-graduation-cap fa-2x text-primary mb-3"></i>
            <h5>Awareness</h5>
            <p>Educational programs to raise awareness of rights and freedoms.</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="100">
          <div class="card h-100 text-center p-3 shadow-sm">
            <i class="fas fa-hands-helping fa-2x text-success mb-3"></i>
            <h5>Support</h5>
            <p>Assisting individuals in filing complaints and seeking justice.</p>
          </div>
        </div>
        <div class="col-md-4" data-aos="zoom-in" data-aos-delay="200">
          <div class="card h-100 text-center p-3 shadow-sm">
            <i class="fas fa-bullhorn fa-2x text-danger mb-3"></i>
            <h5>Advocacy</h5>
            <p>Working with institutions to ensure policies respect human rights.</p>
          </div>
        </div>
      </div>

      <!-- About content from About page -->
      <div class="about-section mt-5" data-aos="fade-up">
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

        <div class="row image-grid mt-4">
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
    </div>
  </section>

  <!-- Stats -->
  <section id="stats" class="stats">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-3" data-aos="fade-up">
          <h2 class="counter" data-target="500">0</h2>
          <p>Complaints Filed</p>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
          <h2 class="counter" data-target="320">0</h2>
          <p>Cases Resolved</p>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
          <h2 class="counter" data-target="25">0</h2>
          <p>Programs Conducted</p>
        </div>
        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
          <h2 class="counter" data-target="15">0</h2>
          <p>Partner Institutions</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section class="testimonials text-center">
    <div class="container">
      <h2 class="mb-4" data-aos="fade-down">What People Say</h2>
      <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active testimonial-item">
            <p>"Thanks to CHRE, I was able to file a complaint safely and get justice."</p>
            <small>- Student, CSPC</small>
          </div>
          <div class="carousel-item testimonial-item">
            <p>"Their advocacy programs really opened my eyes about my rights."</p>
            <small>- Faculty Member</small>
          </div>
          <div class="carousel-item testimonial-item">
            <p>"The support I received was beyond expectations. Truly grateful."</p>
            <small>- Community Member</small>
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>
  </section>

  <!-- Contact (from Contact page) -->
  <section id="contact" class="contact-section">
    <div class="container">
      <div class="row justify-content-center mb-5">
        <div class="col-lg-5 col-md-6 mb-4">
          <div class="contact-card">
            <div class="contact-icon">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="contact-info text-center">
              <h5>Our Location</h5>
              <p><strong>Address:</strong><br>
              <!-- Fill in address here -->
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
              <!-- Fill in email here -->
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="map-container">
        <h3 class="text-center mb-4">Find Us</h3>
        <p class="text-center mb-4">The Center for Human Rights Education is located at Camarines Sur Polytechnic Colleges (CSPC) in Bicol.</p>
        <div class="map-frame" id="map">
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

  <!-- Login First Modal -->
  <div class="modal fade" id="loginFirstModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-body">
          <p class="mb-0">Please log in first.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container text-center">
      <p>&copy; <?= date('Y'); ?> Center for Human Rights Education. All rights reserved.</p>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 1000, once: true });

    // Typewriter effect
    const textArray = ["Promoting Equality", "Fighting for Justice", "Upholding Human Rights"];
    let index = 0, charIndex = 0;
    const speed = 100;
    const element = document.getElementById("typewriter");

    function typeEffect() {
      if (charIndex < textArray[index].length) {
        element.textContent += textArray[index].charAt(charIndex);
        charIndex++;
        setTimeout(typeEffect, speed);
      } else {
        setTimeout(eraseEffect, 2000);
      }
    }

    function eraseEffect() {
      if (charIndex > 0) {
        element.textContent = textArray[index].substring(0, charIndex - 1);
        charIndex--;
        setTimeout(eraseEffect, 50);
      } else {
        index = (index + 1) % textArray.length;
        setTimeout(typeEffect, 200);
      }
    }
    document.addEventListener("DOMContentLoaded", () => { typeEffect(); });

    // Counter animation
    const counters = document.querySelectorAll('.counter');
    const speedCounter = 200;
    counters.forEach(counter => {
      const updateCount = () => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const increment = Math.ceil(target / speedCounter);
        if (count < target) {
          counter.innerText = count + increment;
          setTimeout(updateCount, 20);
        } else {
          counter.innerText = target;
        }
      };
      updateCount();
    });

    // Login required modal
    document.getElementById('fileComplaintBtnHero').addEventListener('click', function(e) {
      e.preventDefault();
      var modal = new bootstrap.Modal(document.getElementById('loginFirstModal'));
      modal.show();
      setTimeout(() => { modal.hide(); }, 2000);
    });
    document.getElementById('fileComplaintBtn').addEventListener('click', function(e) {
      e.preventDefault();
      var modal = new bootstrap.Modal(document.getElementById('loginFirstModal'));
      modal.show();
      setTimeout(() => { modal.hide(); }, 2000);
    });
    // removed mobile offcanvas; navbar collapse handles mobile
  </script>
</body>
</html>
