<?php
// app/Views/user/filing_complaint.php

// Safe extraction of current user values
$passedUser = isset($user) ? $user : null;
$fullName   = $passedUser['full_name'] ?? $passedUser['name'] ?? session('full_name') ?? '';
$email      = $passedUser['email'] ?? session('email') ?? '';
$userId     = $passedUser['id'] ?? session('user_id') ?? '';
$contact    = $passedUser['contact'] ?? session('contact') ?? '';
?> 

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KARAMAY - File Complaint</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #1e40af;
      --secondary-color: #f59e0b;
      --success-color: #10b981;
      --danger-color: #ef4444;
      --warning-color: #f59e0b;
      --info-color: #3b82f6;
      --dark-color: #1f2937;
      --light-color: #f8fafc;
      --sidebar-width: 280px;
      --topbar-height: 70px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }

    /* Sidebar Styles */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
      color: white;
      z-index: 1000;
      transition: all 0.3s ease;
      box-shadow: 4px 0 20px rgba(0,0,0,0.1);
      overflow-y: auto;
    }

    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      text-align: center;
    }

    .sidebar.collapsed .sidebar-header {
      padding: 20px 10px;
    }

    .logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-bottom: 10px;
      background: white;
      display: inline-block;
    }

    .sidebar.collapsed .logo {
      width: 40px;
      height: 40px;
    }

    .brand-name {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 5px;
      transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .brand-name {
      opacity: 0;
      display: none;
    }

    .nav-menu {
      padding: 20px 0;
    }

    .nav-item {
      margin-bottom: 5px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 15px 20px;
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      transition: all 0.3s ease;
      border-radius: 0 25px 25px 0;
      margin-right: 20px;
      position: relative;
    }

    .sidebar.collapsed .nav-link {
      justify-content: center;
      margin-right: 10px;
      border-radius: 10px;
      padding: 15px 10px;
    }

    .nav-link:hover, .nav-link.active {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .sidebar.collapsed .nav-link:hover,
    .sidebar.collapsed .nav-link.active {
      transform: translateX(0);
    }

    .nav-link i {
      font-size: 1.3rem;
      margin-right: 15px;
      min-width: 20px;
    }

    .sidebar.collapsed .nav-link i {
      margin-right: 0;
    }

    .nav-link span {
      font-size: 0.95rem;
      font-weight: 500;
      transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .nav-link span {
      opacity: 0;
      display: none;
    }

    /* Main Content */
    .main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      transition: margin-left 0.3s ease;
    }

    .main-content.expanded {
      margin-left: 70px;
    }

    /* Top Bar */
    .topbar {
      height: var(--topbar-height);
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 30px;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .menu-toggle {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--dark-color);
      cursor: pointer;
      margin-right: 20px;
    }

    .page-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark-color);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Content Area */
    .content-area {
      padding: 30px;
    }

    /* Complaint Type Selection */
    .complaint-type-container {
      background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
      backdrop-filter: blur(15px);
      border-radius: 25px;
      padding: 40px;
      margin: 0 auto 30px;
      max-width: 600px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.15);
      border: 1px solid rgba(255,255,255,0.3);
      text-align: center;
    }

    .complaint-type-container h3 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 30px;
      position: relative;
    }

    .complaint-type-container h3::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      border-radius: 2px;
    }

    .complaint-btn {
      display: block;
      width: 100%;
      padding: 20px;
      margin: 15px 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7));
      border: 2px solid #e2e8f0;
      border-radius: 15px;
      text-decoration: none;
      color: var(--dark-color);
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      cursor: pointer;
    }

    .complaint-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s ease;
    }

    .complaint-btn:hover::before {
      left: 100%;
    }

    .complaint-btn.anonymous {
      border-color: #6b7280;
    }

    .complaint-btn.anonymous:hover {
      background: linear-gradient(135deg, #6b7280, #4b5563);
      color: white;
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(107, 114, 128, 0.3);
    }

    .complaint-btn.identified {
      border-color: var(--primary-color);
    }

    .complaint-btn.identified:hover {
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      color: white;
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(30, 64, 175, 0.3);
    }

    .complaint-btn i {
      margin-right: 12px;
      font-size: 1.4rem;
    }

    /* Modal Styles */
    .modal-content {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 80px rgba(0,0,0,0.2);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border-bottom: none;
      padding: 25px 30px;
    }

    .modal-title {
      font-weight: 700;
      font-size: 1.5rem;
    }

    .modal-body {
      padding: 30px;
      max-height: 70vh;
      overflow-y: auto;
    }

    /* Step Indicator */
    .step-indicator {
      display: flex;
      justify-content: center;
      margin-bottom: 30px;
      gap: 20px;
    }

    .step {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #e2e8f0;
      color: #64748b;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
    }

    .step.active {
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      color: white;
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .step.completed {
      background: var(--success-color);
      color: white;
    }

    .step::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 100%;
      width: 20px;
      height: 2px;
      background: #e2e8f0;
      transform: translateY(-50%);
    }

    .step:last-child::after {
      display: none;
    }

    .step.completed::after,
    .step.active::after {
      background: var(--primary-color);
    }

    /* Progress Bar */
    .progress-container {
      margin-bottom: 20px;
    }

    .progress {
      height: 8px;
      border-radius: 4px;
      background: #e2e8f0;
      overflow: hidden;
    }

    .progress-bar {
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      transition: width 0.3s ease;
    }

    /* Form Sections */
    .form-section {
      display: none;
      animation: fadeIn 0.5s ease-in;
    }

    .form-section.active {
      display: block;
    }

    .form-section h4 {
      color: var(--dark-color);
      font-weight: 600;
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--light-color);
    }

    /* Enhanced Form Styles */
    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-label i {
      color: var(--primary-color);
      font-size: 1.1rem;
    }

    .form-control, .form-select {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 15px 20px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255,255,255,0.8);
      backdrop-filter: blur(5px);
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
      outline: none;
      transform: translateY(-2px);
    }

    .form-control:hover, .form-select:hover {
      border-color: var(--secondary-color);
      transform: translateY(-1px);
    }

    /* File Upload Styles */
    .file-upload-area {
      border: 2px dashed #e2e8f0;
      border-radius: 15px;
      padding: 30px;
      text-align: center;
      background: rgba(248, 250, 252, 0.5);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .file-upload-area:hover {
      border-color: var(--primary-color);
      background: rgba(30, 64, 175, 0.05);
    }

    .file-upload-area.dragover {
      border-color: var(--success-color);
      background: rgba(16, 185, 129, 0.1);
    }

    .file-list {
      margin-top: 20px;
    }

    .file-item {
      display: flex;
      align-items: center;
      padding: 12px;
      background: rgba(255,255,255,0.8);
      border-radius: 10px;
      margin-bottom: 10px;
      border: 1px solid #e2e8f0;
    }

    .file-item i {
      color: var(--primary-color);
      margin-right: 10px;
      font-size: 1.2rem;
    }

    .file-info {
      flex-grow: 1;
    }

    .file-name {
      font-weight: 600;
      color: var(--dark-color);
    }

    .file-size {
      font-size: 0.85rem;
      color: #64748b;
    }

    .remove-file {
      background: var(--danger-color);
      color: white;
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .remove-file:hover {
      background: #dc2626;
      transform: scale(1.1);
    }

    /* Checkbox Styles */
    .form-check-input {
      width: 1.2em;
      height: 1.2em;
      margin-right: 10px;
      border: 2px solid #e2e8f0;
      transition: all 0.3s ease;
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .form-check-label {
      display: flex;
      align-items: center;
      font-weight: 500;
      cursor: pointer;
    }

    .form-check-label i {
      margin-right: 8px;
      color: var(--primary-color);
    }

    /* Button Styles */
    .btn {
      padding: 12px 30px;
      border-radius: 12px;
      font-weight: 600;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-size: 1rem;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn:hover::before {
      left: 100%;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      color: white;
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
    }

    .btn-secondary {
      background: linear-gradient(135deg, #6b7280, #4b5563);
      color: white;
      box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }

    .btn-secondary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
    }

    .btn-outline-secondary {
      background: transparent;
      border: 2px solid #6b7280;
      color: #6b7280;
    }

    .btn-outline-secondary:hover {
      background: #6b7280;
      color: white;
    }

    /* Alert Styles */
    .alert {
      border: none;
      border-radius: 15px;
      padding: 18px 24px;
      margin-bottom: 25px;
      font-weight: 500;
      position: relative;
      backdrop-filter: blur(10px);
    }

    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      color: #065f46;
      border-left: 4px solid var(--success-color);
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      color: #991b1b;
      border-left: 4px solid var(--danger-color);
    }

    .alert-info {
      background: rgba(59, 130, 246, 0.15);
      color: #1e40af;
      border-left: 4px solid var(--info-color);
    }

    /* Character Counter */
    .char-counter {
      font-size: 0.85rem;
      margin-top: 5px;
      font-weight: 500;
    }

    .char-counter.danger {
      color: var(--danger-color);
    }

    .char-counter.warning {
      color: var(--warning-color);
    }

    .char-counter.success {
      color: var(--success-color);
    }

    .char-counter.muted {
      color: #64748b;
    }

    /* Loading State */
    .btn-loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .loading-spinner {
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      border-top: 2px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width);
      }

      .sidebar.mobile-open {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .main-content.expanded {
        margin-left: 0;
      }

      .topbar {
        padding: 0 15px;
      }

      .content-area {
        padding: 20px 15px;
      }

      .complaint-type-container {
        padding: 30px 20px;
        margin: 10px;
      }

      .modal-body {
        padding: 20px;
      }

      .step-indicator {
        gap: 10px;
      }

      .step {
        width: 35px;
        height: 35px;
      }

      .step::after {
        width: 10px;
      }
    }

    /* Animations */
    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .slide-in {
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from { opacity: 0; transform: translateX(-30px); }
      to { opacity: 1; transform: translateX(0); }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f5f9;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: linear-gradient(135deg, #1e3a8a, #d97706);
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <img src="/images/logochre.jpg" alt="Logo" class="logo">
      <div class="brand-name">
        <img src="/images/karamay.png" alt="KARAMAY" style="width:120px;">
      </div>
    </div>
    
    <nav class="nav-menu">
      <div class="nav-item">
        <a href="/user/userdashboard" class="nav-link">
          <i class='bx bx-home'></i>
          <span>Dashboard</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/filing-complaint" class="nav-link active">
          <i class='bx bx-edit-alt'></i>
          <span>File Complaint</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/appointment" class="nav-link">
          <i class='bx bx-calendar-plus'></i>
          <span>Book Appointment</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/view-complaint" class="nav-link">
          <i class='bx bx-search-alt'></i>
          <span>My Complaints</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/view-appointments" class="nav-link">
          <i class='bx bx-calendar-check'></i>
          <span>My Appointments</span>
        </a>
      </div>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <div class="topbar">
      <div style="display: flex; align-items: center;">
        <button class="menu-toggle" id="menuToggle">
          <i class='bx bx-menu'></i>
        </button>
        <div class="page-title">
          <i class='bx bx-edit-alt'></i>
          File Complaint
        </div>
      </div>
    </div>

    <div class="content-area">
      <!-- Complaint Type Selection -->
      <div class="complaint-type-container fade-in">
        <h3><i class='bx bx-shield-alt-2'></i> How would you like to file your complaint?</h3>
        <p style="color: #64748b; margin-bottom: 30px;">Choose the method that best suits your privacy preferences</p>
        
        <a href="<?= base_url('complaint/anonymous'); ?>" class="complaint-btn anonymous">
          <i class='bx bx-user-x'></i>
          File Anonymously
          <small style="display: block; margin-top: 5px; opacity: 0.8;">Your identity will be kept confidential</small>
        </a>
        
        <button type="button" class="complaint-btn identified" data-bs-toggle="modal" data-bs-target="#complaintModal">
          <i class='bx bx-user-check'></i>
          File with Identity
          <small style="display: block; margin-top: 5px; opacity: 0.8;">File with your personal information for follow-up</small>
        </button>
      </div>
    </div>
  </div>

  <!-- Enhanced Complaint Modal -->
  <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class='bx bx-shield-alt-2 me-2'></i>
          File a Complaint
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <!-- FORM STARTS HERE -->
      <form id="complaintForm" action="<?= site_url('user/saveIdentified') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="user_id" value="<?= esc($userId) ?>">

        <div class="modal-body">

          <!-- Step Indicator -->
          <div class="step-indicator">
            <div class="step active" id="step1">1</div>
            <div class="step" id="step2">2</div>
            <div class="step" id="step3">3</div>
            <div class="step" id="step4">4</div>
          </div>

          <!-- Progress Bar -->
          <div class="progress-container">
            <div class="progress">
              <div class="progress-bar" id="progressBar" style="width: 25%;"></div>
            </div>
            <small class="text-muted">Step <span id="currentStep">1</span> of 4</small>
          </div>

          <!-- Step 1: Personal Information -->
          <div class="form-section active" id="section1">
            <h4><i class='bx bx-user'></i> Personal Information</h4>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="fullname" class="form-label"><i class='bx bx-user'></i> Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?= esc($fullName) ?>" readonly>
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label"><i class='bx bx-envelope'></i> Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= esc($email) ?>" readonly>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="phone" class="form-label"><i class='bx bx-phone'></i> Phone Number *</label>
                <input type="text" id="contact" name="contact" class="form-control" value="<?= esc(session('contact_number')) ?>" readonly>
              </div>
            </div>
          </div>

          <!-- Step 2: Incident Details -->
          <div class="form-section" id="section2">
            <h4><i class='bx bx-info-circle'></i> Incident Details</h4>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="complaint_type" class="form-label"><i class='bx bx-category'></i> Complaint Type *</label>
                <select name="complaint_type" id="complaint_type" class="form-select" required>
                  <option value="">-- Select Type --</option>
                  <option value="academic">Academic</option>
                  <option value="non-academic">Non-Academic</option>
                </select>
              </div>


              <div class="col-md-6">
                <label for="complaint_category" class="form-label"><i class='bx bx-list-ul'></i> Complaint Category *</label>
                <select name="complaint_category" id="complaint_category" class="form-select" required>
                  <option value="">-- Select Category --</option>
                  <option value="Harassment">Harassment</option>
                  <option value="Bullying">Bullying</option>
                  <option value="Discrimination">Discrimination</option>
                  <option value="Cheating">Cheating</option>
                  <option value="Abuse of Authority">Abuse of Authority</option>
                  <option value="Other">Other</option>
                </select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="date" class="form-label"><i class='bx bx-calendar'></i> Date of Incident *</label>
                <input type="date" class="form-control" id="date" name="date" required>
              </div>
              <div class="col-md-6">
                <label for="time" class="form-label"><i class='bx bx-time'></i> Time of Incident *</label>
                <select class="form-select" id="time" name="time" required>
                  <option value="">Select Time</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
                <label for="location" class="form-label"><i class='bx bx-map'></i> Location of incident *</label>
                 <input type="text" class="form-control" id="location" name="location" required>
              </div>
            <div class="mb-3">
              <label for="description" class="form-label"><i class='bx bx-detail'></i> Detailed Description *</label>
              <textarea class="form-control" id="description" name="description" rows="5" maxlength="1000" required></textarea>
              <div class="char-counter muted" id="charCounter">0/1000 characters (minimum 50 required)</div>
            </div>
          </div>
          

          <!-- Step 3: Supporting Documents -->
          <div class="form-section" id="section3">
            <h4><i class='bx bx-file'></i> Supporting Documents</h4>
            <p class="text-muted mb-4">Upload any evidence or supporting documents (optional but recommended)</p>
            <div class="file-upload-area" id="fileUploadArea">
              <i class='bx bx-cloud-upload' style="font-size: 3rem; color: var(--primary-color); margin-bottom: 15px;"></i>
              <h5>Drag & Drop Files Here</h5>
              <p class="text-muted">or <span style="color: var(--primary-color); cursor: pointer;" onclick="document.getElementById('supporting_docs').click()">browse files</span></p>
              <small class="text-muted">Maximum 3 files, 10MB each. Formats: JPG, PNG, PDF</small>
              <input type="file" id="supporting_docs" name="supporting_docs[]" multiple accept="image/*,application/pdf" style="display: none;">
            </div>
            <div class="file-list" id="fileList"></div>
          </div>

          <!-- Step 4: Resolution & Confirmation -->
          <div class="form-section" id="section4">
            <h4><i class='bx bx-check-square'></i> Preferred Resolution & Confirmation</h4>
            <div class="mb-4">
              <label class="form-label"><i class='bx bx-target-lock'></i> What resolution would you prefer? *</label>
              <div class="resolution-options">
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" value="Investigation" id="investigation" name="resolution[]">
                  <label class="form-check-label" for="investigation"><i class='bx bx-search-alt'></i> Formal Investigation</label>
                </div>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" value="Legal Action" id="legal" name="resolution[]">
                  <label class="form-check-label" for="legal"><i class='bx bx-balance-scale'></i> Legal Action</label>
                </div>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" value="Mediation" id="mediation" name="resolution[]">
                  <label class="form-check-label" for="mediation"><i class='bx bx-group'></i> Mediation</label>
                </div>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" value="Disciplinary Action" id="disciplinary" name="resolution[]">
                  <label class="form-check-label" for="disciplinary"><i class='bx bx-shield-quarter'></i> Disciplinary Action</label>
                </div>
                <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" value="Other" id="other" name="resolution[]">
                  <label class="form-check-label" for="other"><i class='bx bx-edit'></i> Other</label>
                </div>
              </div>
              <div id="otherResolution" style="display: none;" class="mt-3">
                <input type="text" class="form-control" name="other_resolution" placeholder="Please specify your preferred resolution..." maxlength="200">
              </div>
            </div>
            <div class="alert alert-info mb-4">
              <i class='bx bx-info-circle me-2'></i>
              <strong>Data Privacy Notice:</strong> Your data will be kept confidential and only used for resolving this complaint.
            </div>
            <div class="form-check mb-4">
              <input class="form-check-input" type="checkbox" value="1" id="certify" name="certify" required>
              <label class="form-check-label" for="certify">
                <strong>I hereby certify</strong> that the information provided is true and accurate.
              </label>
            </div>
          </div>
        </div><!-- end modal-body -->

        <!-- Modal Footer (inside form now) -->
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class='bx bx-x'></i> Cancel
          </button>
          <button type="button" class="btn btn-outline-secondary" id="resetBtn">
            <i class='bx bx-refresh'></i> Reset Form
          </button>
          <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
            <i class='bx bx-chevron-left'></i> Previous
          </button>
          <button type="button" class="btn btn-primary" id="nextBtn">
            Next <i class='bx bx-chevron-right'></i>
          </button>
          <button type="submit" class="btn btn-primary" id="submitBtn" style="display: none;">
            <i class='bx bx-send'></i> Submit Complaint
          </button>
        </div>
      </form>
      <!-- FORM ENDS HERE -->


        </div>
      </div>
    </div>
  </div>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
  <?php endif; ?>

  <!-- Mobile Overlay -->
  <div class="mobile-overlay" id="mobileOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Global variables
  let currentStepIndex = 0;
  const totalSteps = 4;
  let uploadedFiles = [];

  // DOM Elements
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('mainContent');
  const menuToggle = document.getElementById('menuToggle');
  const mobileOverlay = document.getElementById('mobileOverlay');
  const form = document.getElementById('complaintForm');
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const submitBtn = document.getElementById('submitBtn');
  const resetBtn = document.getElementById('resetBtn');
  const progressBar = document.getElementById('progressBar');
  const currentStepSpan = document.getElementById('currentStep');

  // Initialize on page load
  document.addEventListener('DOMContentLoaded', function() {
    generateTimeOptions();
    updateStepIndicator();
    setMaxDate();

    // Real-time validation
    document.querySelectorAll('.form-control, .form-select').forEach(field => {
      field.addEventListener('blur', function() {
        if (this.hasAttribute('required') && this.value.trim()) {
          this.classList.add('is-valid');
          this.classList.remove('is-invalid');
        }
      });
    });

    // Prevent Enter key submission (except in textarea)
    form.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
      }
    });
  });

  // Mobile functionality
  function isMobile() {
    return window.innerWidth <= 768;
  }

  menuToggle.addEventListener('click', function() {
    if (isMobile()) {
      sidebar.classList.toggle('mobile-open');
      mobileOverlay.style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
    } else {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
    }
  });

  mobileOverlay.addEventListener('click', function() {
    sidebar.classList.remove('mobile-open');
    mobileOverlay.style.display = 'none';
  });

  // Step navigation
  function updateStepIndicator() {
    const steps = document.querySelectorAll('.step');
    const sections = document.querySelectorAll('.form-section');
    
    steps.forEach((step, index) => {
      step.classList.remove('active', 'completed');
      if (index < currentStepIndex) {
        step.classList.add('completed');
      } else if (index === currentStepIndex) {
        step.classList.add('active');
      }
    });

    sections.forEach((section, index) => {
      section.classList.remove('active');
      if (index === currentStepIndex) {
        section.classList.add('active');
      }
    });

    // Update progress bar
    const progress = ((currentStepIndex + 1) / totalSteps) * 100;
    progressBar.style.width = progress + '%';
    currentStepSpan.textContent = currentStepIndex + 1;

    // Update buttons
    prevBtn.style.display = currentStepIndex === 0 ? 'none' : 'inline-flex';
    if (currentStepIndex === totalSteps - 1) {
      nextBtn.style.display = 'none';
      submitBtn.style.display = 'inline-flex';
    } else {
      nextBtn.style.display = 'inline-flex';
      submitBtn.style.display = 'none';
    }
  }

  // Form validation for each step
  function validateCurrentStep() {
    const currentSection = document.querySelector('.form-section.active');
    const requiredFields = currentSection.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
      if (!field.value.trim()) {
        field.classList.add('is-invalid');
        isValid = false;
      } else {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
      }
    });

    // Special validation for description
    if (currentStepIndex === 1) {
      const description = document.getElementById('description');
      if (description.value.trim().length < 50) {
        description.classList.add('is-invalid');
        showAlert('Description must be at least 50 characters long.', 'danger');
        isValid = false;
      }
    }

    // Special validation for resolution
    if (currentStepIndex === 3) {
      const resolutionChecked = document.querySelectorAll('input[name="resolution[]"]:checked');
      if (resolutionChecked.length === 0) {
        showAlert('Please select at least one preferred resolution.', 'danger');
        isValid = false;
      }
    }

    return isValid;
  }

  // Navigation event listeners
  nextBtn.addEventListener('click', function() {
    if (validateCurrentStep()) {
      if (currentStepIndex < totalSteps - 1) {
        currentStepIndex++;
        updateStepIndicator();
      }
    }
  });

  prevBtn.addEventListener('click', function() {
    if (currentStepIndex > 0) {
      currentStepIndex--;
      updateStepIndicator();
    }
  });

  // Generate time options
  function generateTimeOptions() {
    const timeSelect = document.getElementById('time');
    timeSelect.innerHTML = '<option value="">Select Time</option>';
    
    for (let hour = 0; hour < 24; hour++) {
      for (let minute = 0; minute < 60; minute += 30) {
        const timeValue = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
        const timeDisplay = new Date(`2000-01-01T${timeValue}`).toLocaleTimeString([], {
          hour: 'numeric',
          minute: '2-digit',
          hour12: true
        });
        
        const option = document.createElement('option');
        option.value = timeValue;
        option.textContent = timeDisplay;
        timeSelect.appendChild(option);
      }
    }
  }

  // Set maximum date to today
  function setMaxDate() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').max = today;
  }

  // Character counter for description
  const description = document.getElementById('description');
  const charCounter = document.getElementById('charCounter');
  
  description.addEventListener('input', function() {
    const count = this.value.length;
    const minLength = 50;
    const maxLength = 1000;
    
    charCounter.textContent = `${count}/${maxLength} characters`;
    
    if (count < minLength) {
      charCounter.className = 'char-counter danger';
      charCounter.textContent += ` (${minLength - count} more needed)`;
    } else {
      charCounter.className = 'char-counter success';
    }
    
    // Auto-resize textarea
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 200) + 'px';
  });

  // File upload functionality
  const fileUploadArea = document.getElementById('fileUploadArea');
  const fileInput = document.getElementById('supporting_docs');
  const fileList = document.getElementById('fileList');

  // Drag and drop
  fileUploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    this.classList.add('dragover');
  });

  fileUploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
  });

  fileUploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    this.classList.remove('dragover');
    
    const files = Array.from(e.dataTransfer.files);
    handleFiles(files);
  });

  fileInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    handleFiles(files);
  });

  function handleFiles(files) {
    if (uploadedFiles.length + files.length > 3) {
      showAlert('Maximum 3 files allowed. Please remove some files first.', 'danger');
      return;
    }

    files.forEach(file => {
      if (file.size > 10 * 1024 * 1024) {
        showAlert(`File "${file.name}" is too large. Maximum size is 10MB.`, 'danger');
        return;
      }

      if (!file.type.match(/^(image\/.*)|(application\/pdf)$/)) {
        showAlert(`File "${file.name}" is not supported. Please use JPG, PNG, or PDF files.`, 'danger');
        return;
      }

      uploadedFiles.push(file);
      displayFile(file);
    });
  }

  function displayFile(file) {
    const fileItem = document.createElement('div');
    fileItem.className = 'file-item slide-in';
    fileItem.innerHTML = `
      <i class='bx ${file.type.includes('pdf') ? 'bx-file-pdf' : 'bx-image'}'></i>
      <div class="file-info">
        <div class="file-name">${file.name}</div>
        <div class="file-size">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
      </div>
      <button type="button" class="remove-file" onclick="removeFile('${file.name}')">
        <i class='bx bx-x'></i>
      </button>
    `;
    fileList.appendChild(fileItem);
  }

  function removeFile(fileName) {
    uploadedFiles = uploadedFiles.filter(file => file.name !== fileName);
    const fileItem = Array.from(fileList.children).find(item => 
      item.querySelector('.file-name').textContent === fileName
    );
    if (fileItem) {
      fileItem.remove();
    }
  }

  // Other resolution toggle
  document.getElementById('other').addEventListener('change', function() {
    const otherResolution = document.getElementById('otherResolution');
    const otherInput = otherResolution.querySelector('input');
    
    if (this.checked) {
      otherResolution.style.display = 'block';
      otherInput.required = true;
    } else {
      otherResolution.style.display = 'none';
      otherInput.required = false;
      otherInput.value = '';
    }
  });

  // Form submission (final step only)
submitBtn.addEventListener('click', function () {
  if (!validateCurrentStep()) {
    return;
  }

  form.submit(); // send form to controller


    // Show loading state
    this.disabled = true;
    this.classList.add('btn-loading');
    this.innerHTML = '<div class="loading-spinner"></div> Submitting...';

    // Simulate form submission
    setTimeout(() => {
      showAlert('Complaint submitted successfully! You will receive a confirmation email with your complaint reference number.', 'success');
      
      // Reset form
      form.reset();
      uploadedFiles = [];
      fileList.innerHTML = '';
      currentStepIndex = 0;
      updateStepIndicator();
      
      // Close modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('complaintModal'));
      modal.hide();
      
      // Reset button
      this.disabled = false;
      this.classList.remove('btn-loading');
      this.innerHTML = '<i class="bx bx-send"></i> Submit Complaint';
    }, 2000);
  });

  // Reset form
  resetBtn.addEventListener('click', function() {
    if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
      form.reset();
      uploadedFiles = [];
      fileList.innerHTML = '';
      currentStepIndex = 0;
      updateStepIndicator();
      document.querySelectorAll('.form-control').forEach(field => {
        field.classList.remove('is-valid', 'is-invalid');
      });
    }
  });

  // Show alert function
  function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} fade-in`;
    alertDiv.innerHTML = `
      <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error'}'></i>
      ${message}
    `;
    
    // Insert at the top of modal body
    const modalBody = document.querySelector('.modal-body');
    modalBody.insertBefore(alertDiv, modalBody.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
      if (alertDiv.parentNode) {
        alertDiv.style.opacity = '0';
        setTimeout(() => {
          alertDiv.remove();
        }, 300);
      }
    }, 5000);
  }

  // Window resize handler
  window.addEventListener('resize', function() {
    if (!isMobile() && sidebar.classList.contains('mobile-open')) {
      sidebar.classList.remove('mobile-open');
      mobileOverlay.style.display = 'none';
    }
  });
</script>

</body>
</html>