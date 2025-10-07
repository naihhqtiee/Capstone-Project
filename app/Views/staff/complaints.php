<?php
helper('text'); 

use App\Models\ComplaintModel;
use App\Models\NdaModel;

$model = new ComplaintModel();
$ndaModel = new NdaModel();

// Fetch complaints for table
$complaints = $model->select('
        complaints.*,
        accounts.full_name,
        accounts.email,
        students.contact_number
    ')
    ->join('accounts', 'accounts.id = complaints.user_id', 'left')
    ->join('students', 'students.account_id = accounts.id', 'left')
    ->orderBy('complaints.created_at', 'DESC')
    ->findAll();

// ✅ Real-time counts directly from DB
$total    = $model->countAllResults(); 
$model->resetQuery();

$pending  = $model->where('status', 'pending')->countAllResults();
$model->resetQuery();

$ongoing  = $model->where('status', 'ongoing')->countAllResults();
$model->resetQuery();

$resolved = $model->where('status', 'resolved')->countAllResults();
$model->resetQuery();

// Fetch all NDA files
$ndaFiles = $ndaModel->orderBy('uploaded_at', 'DESC')->findAll();

// Check for current active NDA
$activeNda = $ndaModel->where('is_active', 1)->first();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Complaints & NDA Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4e73df;
      --primary-hover: #3756c0;
      --background-color: #f8f9fc;
      --text-color: #212529;
      --text-muted: #6c757d;
      --border-color: #e3e6f0;
      --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      --card-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
      --hover-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
      --sidebar-width: 260px;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --info-color: #17a2b8;
      --light-gray: #f8f9fa;
    }
    
    * {
      box-sizing: border-box;
    }
    
    body { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background-color: var(--background-color); 
      margin: 0; 
      line-height: 1.6;
      font-size: 14px;
    }
    
    .layout { 
      display: flex; 
      min-height: 100vh; 
      transition: margin-left 0.3s ease; 
    }

    /* Sidebar Styles - Exactly matching dashboard */
    .sidebar {
      width: var(--sidebar-width);
      background: #fff;
      border-right: 1px solid var(--border-color);
      display: flex;
      flex-direction: column;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      transition: transform 0.3s ease;
      z-index: 1000;
      box-shadow: var(--shadow);
    }

    .sidebar.hide { 
      transform: translateX(-100%); 
    }

    .sidebar-header {
      padding: 1.5rem;
      text-align: center;
      border-bottom: 1px solid var(--border-color);
    }

    .sidebar-logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      box-shadow: var(--card-shadow);
      margin-bottom: 0.5rem;
    }

    .sidebar-title {
      font-weight: 600;
      font-size: 0.9rem;
      color: var(--text-color);
      margin: 0;
    }

    .sidebar-subtitle {
      font-size: 0.75rem;
      color: var(--text-muted);
      margin: 0;
    }

    .sidebar-nav {
      flex: 1;
      padding: 1rem 0;
    }

    .sidebar ul { 
      list-style: none; 
      padding: 0; 
      margin: 0; 
    }

    .nav-item {
      margin: 0.25rem 0;
    }

    .nav-link { 
      display: flex; 
      align-items: center; 
      padding: 0.75rem 1.5rem;
      color: var(--text-color); 
      font-size: 0.875rem; 
      text-decoration: none; 
      transition: all 0.2s ease;
      position: relative;
      font-weight: 500;
    }

    .nav-link:hover { 
      background-color: #f8f9fc;
      color: var(--primary-color);
      padding-left: 1.75rem;
    }

    .nav-link.active { 
      background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
      color: white;
      border-radius: 0 25px 25px 0;
      margin-right: 1rem;
      box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
    }

    .nav-link.active:hover {
      padding-left: 1.5rem;
    }

    .nav-link i {
      width: 20px;
      margin-right: 0.75rem;
      text-align: center;
    }

    .nav-badge {
      margin-left: auto;
      background: var(--primary-color);
      color: white;
      padding: 0.25rem 0.5rem;
      border-radius: 12px;
      font-size: 0.7rem;
      font-weight: 600;
    }

    .sidebar-footer {
      padding: 1rem;
      border-top: 1px solid var(--border-color);
    }

    .logout-btn {
      width: 100%;
      background: linear-gradient(135deg, #dc3545, #c82333);
      border: none;
      color: white;
      padding: 0.75rem;
      border-radius: 8px;
      font-size: 0.875rem;
      font-weight: 500;
      transition: all 0.2s ease;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logout-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
      color: white;
    }

    /* Sidebar Toggle */
    .sidebar-toggle {
      position: fixed;
      top: 1rem;
      left: 1rem;
      background: white;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 1100;
      transition: all 0.2s ease;
      box-shadow: var(--card-shadow);
    }

    .sidebar-toggle:hover { 
      background: var(--primary-color);
      color: white;
      box-shadow: var(--hover-shadow);
    }

    /* Content */
    .content { 
      flex-grow: 1; 
      margin-left: var(--sidebar-width); 
      padding: 2rem; 
      transition: margin-left 0.3s ease;
      background: #f5f5f5;
      min-height: 100vh;
    }
    .content.full { margin-left: 0; }

    /* Simple Plain Header */
    .page-header {
      background: #fff;
      border: 1px solid #ddd;
      padding: 1.5rem;
      margin-bottom: 2rem;
      color: #333;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
    }

    .page-header h1 {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #333;
    }

    .page-header p {
      font-size: 1rem;
      color: #666;
      margin-bottom: 0;
    }

    /* Simple Stats Cards */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      border: 1px solid #ddd;
      padding: 1rem;
      text-align: center;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
      transition: transform 0.2s ease;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--hover-shadow);
    }

    .stat-card-content {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .stat-card-info h3 {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
      color: #333;
    }

    .stat-card-info p {
      font-size: 0.9rem;
      color: #666;
      margin: 0;
      font-weight: 500;
    }

    /* Simple NDA Section */
    .nda-section {
      background: white;
      border: 1px solid #ddd;
      padding: 1.5rem;
      margin-bottom: 2rem;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
    }

    .nda-header {
      display: flex;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid #eee;
    }

    .nda-header h4 {
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
      margin: 0;
    }

    /* Simple NDA Upload Area */
    .nda-upload-area {
      border: 2px dashed #ccc;
      padding: 2rem;
      text-align: center;
      background: #f9f9f9;
      margin-bottom: 1rem;
      cursor: pointer;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .nda-upload-area:hover {
      border-color: #999;
      background: #f0f0f0;
    }

    .current-nda {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      padding: 1rem;
      margin-top: 1rem;
      border-radius: 8px;
    }

    /* Simple NDA File Items */
    .nda-file-item {
      background: #f8f9fa;
      border: 1px solid #e9ecef;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      transition: all 0.2s ease;
    }

    .nda-file-item:hover {
      box-shadow: var(--card-shadow);
    }

    .nda-file-item.active {
      background: #e8f5e8;
      border-color: #28a745;
    }

    /* Simple Buttons */
    .btn {
      border-radius: 6px;
      font-weight: 500;
      padding: 0.4rem 0.8rem;
      font-size: 0.85rem;
      border: 1px solid;
      text-decoration: none;
      display: inline-block;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .btn-sm {
      padding: 0.25rem 0.5rem;
      font-size: 0.8rem;
    }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      color: white;
    }

    .btn-primary:hover {
      background-color: var(--primary-hover);
      border-color: var(--primary-hover);
    }

    .btn-success {
      background-color: #28a745;
      border-color: #28a745;
      color: white;
    }

    .btn-info {
      background-color: #17a2b8;
      border-color: #17a2b8;
      color: white;
    }

    .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
      color: white;
    }

    .btn-outline-primary {
      border-color: var(--primary-color);
      color: var(--primary-color);
      background: transparent;
    }

    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
      color: white;
    }

    /* Excel-Style Table Container */
    .complaints-section {
      background: white;
      border: 1px solid #ddd;
      overflow: hidden;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
    }

    /* Plain Header */
    .complaints-header {
      background: #f8f9fa;
      color: #333;
      padding: 1rem 1.5rem;
      margin: 0;
      border-bottom: 1px solid #ddd;
    }

    .complaints-header h4 {
      margin: 0;
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
    }

    .table-container {
      overflow-x: auto;
    }

    /* Excel-Style Table */
    .complaints-table {
      margin: 0;
      border-collapse: collapse;
      width: 100%;
      font-size: 0.85rem;
      background: white;
    }

    .complaints-table thead th {
      background: #f1f3f4;
      border: 1px solid #d0d7de;
      font-weight: 600;
      color: #333;
      padding: 0.6rem 0.8rem;
      font-size: 0.85rem;
      text-align: left;
      white-space: nowrap;
    }

    .complaints-table tbody tr {
      border: none;
      background: white;
    }

    .complaints-table tbody tr:nth-child(even) {
      background: #f9f9f9;
    }

    .complaints-table tbody tr:hover {
      background-color: #e8f4fd;
    }

    .complaints-table td {
      padding: 0.6rem 0.8rem;
      vertical-align: top;
      border: 1px solid #d0d7de;
      font-size: 0.85rem;
      line-height: 1.4;
    }

    /* Simple Status Badges */
    .status-badge {
      display: inline-block;
      padding: 0.2rem 0.5rem;
      font-size: 0.75rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      border-radius: 4px;
    }

    .status-pending { 
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }
    .status-ongoing { 
      background: #cce5ff;
      color: #004085;
      border: 1px solid #99ccff;
    }
    .status-resolved { 
      background: #d4edda;
      color: #155724;
      border: 1px solid #a3d977;
    }

    /* Simple Alerts */
    .alert {
      padding: 1rem;
      margin-bottom: 1rem;
      border: 1px solid;
      border-radius: 6px;
    }

    .alert-success {
      background: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
    }

    .alert-danger {
      background: #f8d7da;
      border-color: #f5c6cb;
      color: #721c24;
    }

    .alert-warning {
      background: #fff3cd;
      border-color: #ffeaa7;
      color: #856404;
    }

    /* Action Buttons Container */
    .action-buttons {
      display: flex;
      gap: 0.3rem;
      flex-wrap: wrap;
    }

    /* Status Select Styling */
    .status-select {
      border: 1px solid #ced4da;
      padding: 0.3rem;
      background: white;
      font-size: 0.8rem;
      font-weight: 500;
      min-width: 100px;
      border-radius: 4px;
    }

    .status-select:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    /* Complaint Description Preview */
    .complaint-preview {
      max-width: 200px;
      line-height: 1.3;
      color: #333;
      font-size: 0.85rem;
    }

    /* User Info Styling */
    .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-name {
      font-weight: 500;
      color: #333;
      margin-bottom: 0.1rem;
      font-size: 0.85rem;
    }

    .user-email {
      font-size: 0.75rem;
      color: #666;
    }

    .anonymous-label {
      font-style: italic;
      color: #999;
      font-size: 0.85rem;
    }

    /* Filter and Search Bar */
    .filter-section {
      background: white;
      border: 1px solid #ddd;
      padding: 1rem;
      margin-bottom: 1rem;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
    }

    .search-input {
      border: 1px solid #ced4da;
      padding: 0.5rem;
      font-size: 0.9rem;
      border-radius: 6px;
    }

    .search-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: #999;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    /* File upload styling */
    .file-input {
      border: 1px solid #ced4da;
      padding: 0.4rem;
      font-size: 0.85rem;
      width: 100%;
      margin-bottom: 1rem;
      border-radius: 6px;
    }

    .selected-file {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      padding: 0.5rem;
      margin-top: 0.5rem;
      font-size: 0.85rem;
      border-radius: 6px;
    }

    /* Form controls */
    .form-select, .form-control {
      border-radius: 6px;
      border: 1px solid var(--border-color);
      transition: all 0.2s ease;
    }

    .form-select:focus, .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    /* Modal styling */
    .modal-content {
      border-radius: 12px;
      border: none;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .modal-header {
      background: var(--primary-color);
      color: white;
      border-radius: 12px 12px 0 0;
      border: none;
    }

    .modal-footer {
      border-top: 1px solid var(--border-color);
    }

    /* Responsive Design */
    @media (max-width: 992px) { 
      .sidebar {
        transform: translateX(-100%);
      }
      
      .content { 
        margin-left: 0; 
      }
      
      .sidebar.show {
        transform: translateX(0);
      }
    }

    @media (max-width: 768px) {
      .content { 
        margin-left: 0; 
        padding: 1rem;
      }
      .nda-section, .page-header { 
        padding: 1rem; 
      }
      .stats-container {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      .stat-card {
        padding: 1rem;
      }
      .action-buttons {
        flex-direction: column;
      }
    }

    @media (max-width: 576px) {
      .table-responsive {
        font-size: 0.8rem;
      }
      
      .complaints-table td {
        padding: 0.4rem;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar Toggle Button -->
<div class="sidebar-toggle" id="sidebarToggle">
  <i class='bx bx-menu'></i>
</div>

<div class="layout">
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
       
      <img src="/images/logochre.jpg" alt="Logo" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
      
      <div class="sidebar-title"><?= esc(session()->get('full_name')) ?></div>
      <div class="sidebar-subtitle"><?= esc(session()->get('role')) ?></div>
    </div>
    
    <nav class="sidebar-nav">
      <ul>
        <li class="nav-item">
          <a class="nav-link" href="dashboard">
            <i class='bx bx-grid-alt'></i>Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="opcr-checklist">
            <i class='bx bx-task'></i>OPCR Checklist
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="complaints">
            <i class='bx bx-message-square-error'></i>Complaints
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="appointments">
            <i class='bx bx-calendar-check'></i>Appointments
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="events">
            <i class='bx bx-calendar-event'></i>Events
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="students">
            <i class='bx bx-user-voice'></i>Students
          </a>
        </li>

      </ul>
    </nav>
    
    <div class="sidebar-footer">
      <a href="#" class="logout-btn" onclick="logout()">
        <i class='bx bx-log-out me-2'></i>Logout
      </a>
    </div>
  </div>
  <!-- Main Content -->
  <div class="content" id="content">
    <!-- Simple Plain Header -->
    <div class="page-header">
      <h1><i class='bx bx-message-square-error me-3'></i>Complaints</h1>
      <p>Manage student complaints </p>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <i class='fas fa-check-circle me-2'></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <i class='fas fa-exclamation-triangle me-2'></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Simple Stats Cards -->
    <div class="stats-container">
      <div class="stat-card total">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($total) ?></h3>
            <p>Total Complaints</p>
          </div>
        </div>
      </div>
      
      <div class="stat-card pending">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($pending) ?></h3>
            <p>Pending Review</p>
          </div>
        </div>
      </div>
      
      <div class="stat-card ongoing">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($ongoing) ?></h3>
            <p>In Progress</p>
          </div>
        </div>
      </div>
      
      <div class="stat-card resolved">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($resolved) ?></h3>
            <p>Resolved</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Simple NDA Management Section -->
    <div class="nda-section">
      <div class="nda-header">
        <h4><i class='fas fa-file-contract me-2'></i>NDA Document Management</h4>
        <small class="text-muted ms-auto">Manage the NDA file that users will see when filing complaints</small>
      </div>

      <!-- Upload Form -->
      <form action="<?= base_url('staff/upload-nda') ?>" method="post" enctype="multipart/form-data" id="ndaForm">
        <?= csrf_field() ?>
        
        <div class="nda-upload-area" id="ndaUploadArea">
          <i class='fas fa-cloud-upload-alt fa-2x text-muted mb-2'></i>
          <h5>Upload NDA Document</h5>
          <p class="text-muted">Click to select or drag and drop a PDF file (Max 5MB)</p>
          <input type="file" name="nda_file" id="ndaFile" accept=".pdf" class="file-input" style="display: none;">
        </div>
        
        <div id="selectedFile" class="selected-file" style="display: none;">
          <strong>Selected file:</strong> <span id="fileName"></span>
          <button type="button" class="btn btn-sm btn-secondary float-end" onclick="clearFile()">Clear</button>
        </div>

        <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
          <i class='fas fa-upload me-2'></i>Upload NDA
        </button>
      </form>

      <?php if ($activeNda): ?>
        <!-- Current Active NDA -->
        <div class="current-nda">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h6 class="text-success mb-2"><i class='fas fa-check-circle me-2'></i>Current NDA File </h6>
              <p class="mb-1"><strong><i class='fas fa-file-pdf me-2'></i>File:</strong> <?= esc(basename($activeNda['file_path'])) ?></p>
              <?php if (file_exists(FCPATH . $activeNda['file_path'])): ?>
                <p class="mb-1"><strong><i class='fas fa-weight me-2'></i>Size:</strong> <?= number_format(filesize(FCPATH . $activeNda['file_path']) / 1024, 2) ?> KB</p>
              <?php endif; ?>
              <p class="mb-0"><strong><i class='fas fa-calendar me-2'></i>Uploaded:</strong> <?= date('M d, Y H:i', strtotime($activeNda['uploaded_at'])) ?></p>
            </div>
            <div class="col-md-4 text-end">
              <div class="action-buttons">
                <a href="<?= base_url('staff/download-nda/' . $activeNda['id']) ?>" class="btn btn-outline-primary btn-sm">
                  <i class='fas fa-download me-1'></i> Download
                </a>
                <a href="<?= base_url('staff/view-nda/' . $activeNda['id']) ?>" class="btn btn-primary btn-sm" target="_blank">
                  <i class='fas fa-eye me-1'></i> Preview
                </a>
                <form action="<?= base_url('staff/delete-nda/' . $activeNda['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this NDA file?');">
                  <?= csrf_field() ?>
                  <button class="btn btn-danger btn-sm"><i class='fas fa-trash me-1'></i> Delete</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- List of All NDA Files -->
      <?php if (!empty($ndaFiles)): ?>
        <h5 class="mt-4 mb-3">All NDA Files</h5>
        <div class="row">
          <?php foreach ($ndaFiles as $nda): ?>
            <div class="col-md-6 col-lg-4 mb-3">
              <div class="nda-file-item <?= ($activeNda && $activeNda['id'] === $nda['id']) ? 'active' : '' ?>">
                <h6 class="mb-2">
                  <i class='fas fa-file-pdf me-2'></i><?= esc(basename($nda['file_path'])) ?>
                  <?php if ($activeNda && $activeNda['id'] === $nda['id']): ?>
                    <span class="badge bg-success ms-2">ACTIVE</span>
                  <?php endif; ?>
                </h6>
                <p class="mb-2 small text-muted">
                  <strong>Uploaded:</strong> <?= date('M d, Y H:i', strtotime($nda['uploaded_at'])) ?>
                </p>

                <div class="action-buttons">
                  <a href="<?= base_url('staff/view-nda/' . $nda['id']) ?>" target="_blank" class="btn btn-success btn-sm">
                    <i class='fas fa-eye me-1'></i> View
                  </a>
                  <a href="<?= base_url('staff/download-nda/' . $nda['id']) ?>" class="btn btn-info btn-sm">
                    <i class='fas fa-download me-1'></i> Download
                  </a>
                  <form action="<?= base_url('staff/delete-nda/' . $nda['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this NDA file?');">
                    <?= csrf_field() ?>
                    <button class="btn btn-danger btn-sm">
                      <i class='fas fa-trash me-1'></i> Delete
                    </button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="alert alert-warning mt-3">
          <i class='fas fa-info-circle me-2'></i>
          <strong>No NDA files found.</strong> Upload one to get started.
        </div>
      <?php endif; ?>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-light border-end-0">
              <i class='fas fa-search'></i>
            </span>
            <input type="text" class="form-control search-input border-start-0" placeholder="Search complaints by category, type, or user..." id="searchInput">
          </div>
        </div>
        <div class="col-md-3">
          <select class="form-select status-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="ongoing">Ongoing</option>
            <option value="resolved">Resolved</option>
          </select>
        </div>
        <div class="col-md-3 text-end">
          <button class="btn btn-outline-primary" onclick="resetFilters()">
            <i class='fas fa-refresh me-2'></i> Reset Filters
          </button>
        </div>
      </div>
    </div>

    <!-- Excel-Style Complaints Table -->
<div class="complaints-section">
  <div class="complaints-header">
    <h4><i class='fas fa-list me-2'></i>Complaints Overview & Management</h4>
  </div>
  
  <div class="table-container">
    <table class="complaints-table" id="complaintsTable">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="12%">Category</th>
          <th width="12%">Type</th>
          <th width="25%">Description</th>
          <th width="18%">Submitted By</th>
          <th width="10%">Date</th>
          <th width="12%">Status</th>
          <th width="15%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($complaints)): ?>
          <?php foreach ($complaints as $index => $c): ?>
          <tr class="complaint-row" 
              data-status="<?= esc($c['status']) ?>" 
              data-category="<?= esc($c['complaint_category']) ?>" 
              data-type="<?= esc($c['complaint_type']) ?>">
            <td><div class="fw-bold"><?= $index + 1 ?></div></td>
            <td><?= esc($c['complaint_category']) ?></td>
            <td><?= esc($c['complaint_type']) ?></td>
            <td>
              <div class="complaint-preview">
                <?= esc(word_limiter(strip_tags($c['description']), 15)) ?>
                <?php if (str_word_count(strip_tags($c['description'])) > 15): ?>
                  <small class="text-primary" style="cursor: pointer;" 
                         onclick="showFullDescription('<?= esc(addslashes($c['description'])) ?>')">
                    ...read more
                  </small>
                <?php endif; ?>
              </div>
            </td>
            <td>
              <div class="user-info">
                <?php if (empty($c['is_anonymous']) || $c['is_anonymous'] == 0): ?>
                  <div class="user-name"><?= esc($c['full_name'] ?? 'Unknown') ?></div>
                  <div class="user-email"><?= esc($c['email'] ?? 'No email') ?></div>
                  <?php if (!empty($c['contact_number'])): ?>
                    <div class="user-email"><?= esc($c['contact_number']) ?></div>
                  <?php endif; ?>
                <?php else: ?>
                  <span class="anonymous-label">Anonymous User</span>
                <?php endif; ?>
              </div>
            </td>
            <td>
              <div class="text-center">
                <div class="fw-bold"><?= date('M d, Y', strtotime($c['date'])) ?></div>
                <small class="text-muted"><?= date('H:i A', strtotime($c['created_at'] ?? $c['date'])) ?></small>
              </div>
            </td>
            <td>
              <select class="form-select status-select complaint-status" data-id="<?= $c['id'] ?>">
                <option value="pending" <?= $c['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="ongoing" <?= $c['status'] === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                <option value="resolved" <?= $c['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
              </select>
              <div class="mt-1">
                <span class="status-badge status-<?= esc($c['status']) ?>">
                  <?php if ($c['status'] === 'pending'): ?>
                    Pending
                  <?php elseif ($c['status'] === 'ongoing'): ?>
                    In Progress
                  <?php else: ?>
                    Resolved
                  <?php endif; ?>
                </span>
              </div>
            </td>
            <td>
              <div class="action-buttons">
  <!-- View Complaint -->
  <a href="<?= base_url('complaint/view/' . $c['id']) ?>" 
     class="btn btn-primary btn-sm" title="View Complaint">
    <i class='fas fa-eye'></i>
  </a>

  <!-- Add Note (opens modal) -->
  <button type="button" 
          class="btn btn-info btn-sm" 
          data-bs-toggle="modal" 
          data-bs-target="#addNoteModal<?= $c['id'] ?>" 
          title="Add Note">
    <i class='fas fa-sticky-note'></i>
  </button>

  <!-- Delete Complaint -->
  <form action="<?= base_url('complaint/delete/' . $c['id']) ?>" 
        method="post" class="d-inline" 
        onsubmit="return confirmDelete();">
    <?= csrf_field() ?>
    <button class="btn btn-danger btn-sm" title="Delete Complaint">
      <i class='fas fa-trash'></i>
    </button>
  </form>
</div>


              <!-- Add Note Modal -->
              <div class="modal fade" id="addNoteModal<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="<?= base_url('staff/complaint/' . $c['id'] . '/save_note') ?>" method="post">
                      <?= csrf_field() ?>
                      <div class="modal-header">
                        <h5 class="modal-title">Add Note for Complaint #<?= $c['id'] ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label for="note<?= $c['id'] ?>" class="form-label">Note</label>
                          <textarea name="note" id="note<?= $c['id'] ?>" 
                                    class="form-control" rows="4" required><?= esc($c['notes']) ?></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Note</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- End Add Note Modal -->
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center">
              <div class="empty-state">
                <i class='fas fa-inbox'></i>
                <h5 class="mt-3 mb-2">No Complaints Found</h5>
                <p class="text-muted">There are currently no complaints in the system.</p>
              </div>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

  </div>
</div>

<!-- Complaint Details Modal -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Sidebar toggle functionality
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });

  // NDA File Upload functionality
  const ndaUploadArea = document.getElementById('ndaUploadArea');
  const ndaFileInput = document.getElementById('ndaFile');
  const selectedFile = document.getElementById('selectedFile');
  const fileName = document.getElementById('fileName');
  const uploadBtn = document.getElementById('uploadBtn');

  // Click to select file
  ndaUploadArea.addEventListener('click', () => {
    ndaFileInput.click();
  });

  // File input change
  ndaFileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      handleNDAFile(file);
    }
  });

  // Drag and drop
  ndaUploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    ndaUploadArea.style.borderColor = '#007bff';
    ndaUploadArea.style.backgroundColor = '#f0f8ff';
  });

  ndaUploadArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    ndaUploadArea.style.borderColor = '#ccc';
    ndaUploadArea.style.backgroundColor = '#f9f9f9';
  });

  ndaUploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    ndaUploadArea.style.borderColor = '#ccc';
    ndaUploadArea.style.backgroundColor = '#f9f9f9';
    const file = e.dataTransfer.files[0];
    if (file) {
      ndaFileInput.files = e.dataTransfer.files;
      handleNDAFile(file);
    }
  });

  function handleNDAFile(file) {
    if (file.type !== 'application/pdf') {
      showAlert('Please select a PDF file only.', 'error');
      clearFile();
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      showAlert('File size must be less than 5MB.', 'error');
      clearFile();
      return;
    }

    fileName.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
    selectedFile.style.display = 'block';
    uploadBtn.disabled = false;
  }

  function clearFile() {
    ndaFileInput.value = '';
    selectedFile.style.display = 'none';
    uploadBtn.disabled = true;
  }

  // Search and filter functionality
  const searchInput = document.getElementById('searchInput');
  const statusFilter = document.getElementById('statusFilter');
  const complaintsTable = document.getElementById('complaintsTable');

  function filterComplaints() {
    const searchTerm = searchInput.value.toLowerCase();
    const statusValue = statusFilter.value.toLowerCase();
    const rows = complaintsTable.querySelectorAll('.complaint-row');

    rows.forEach(row => {
      const category = row.dataset.category.toLowerCase();
      const type = row.dataset.type.toLowerCase();
      const status = row.dataset.status.toLowerCase();
      const description = row.querySelector('.complaint-preview').textContent.toLowerCase();
      const userInfo = row.querySelector('.user-info').textContent.toLowerCase();

      const matchesSearch = !searchTerm || 
        category.includes(searchTerm) || 
        type.includes(searchTerm) || 
        description.includes(searchTerm) || 
        userInfo.includes(searchTerm);

      const matchesStatus = !statusValue || status === statusValue;

      if (matchesSearch && matchesStatus) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  function resetFilters() {
    searchInput.value = '';
    statusFilter.value = '';
    filterComplaints();
  }

  // Event listeners for filters
  searchInput.addEventListener('input', filterComplaints);
  statusFilter.addEventListener('change', filterComplaints);

  // Complaint status updates
  document.querySelectorAll('.complaint-status').forEach(select => {
    select.addEventListener('change', function() {
      const complaintId = this.dataset.id;
      const newStatus = this.value;
      const row = this.closest('.complaint-row');
      const statusBadge = row.querySelector('.status-badge');
      
      // Show loading state
      const originalSelect = this.innerHTML;
      this.innerHTML = '<option>Updating...</option>';
      this.disabled = true;

      fetch(`<?= base_url('complaint/update-status') ?>/${complaintId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: `status=${newStatus}`
      })
      .then(response => {
        if (response.ok) {
          row.dataset.status = newStatus;
          
          const badgeClass = `status-${newStatus}`;
          statusBadge.className = `status-badge ${badgeClass}`;
          
          let badgeContent = '';
          if (newStatus === 'pending') {
            badgeContent = 'Pending';
          } else if (newStatus === 'ongoing') {
            badgeContent = 'In Progress';
          } else {
            badgeContent = 'Resolved';
          }
          statusBadge.innerHTML = badgeContent;
          
          showAlert('Status updated successfully!', 'success');
          
          this.innerHTML = originalSelect;
          this.value = newStatus;
          this.disabled = false;
        } else {
          throw new Error('Update failed');
        }
      })
      .catch(error => {
        showAlert('Failed to update status. Please try again.', 'error');
        this.innerHTML = originalSelect;
        this.disabled = false;
      });
    });
  });

  // View complaint details function
  function viewComplaintDetails(complaintId) {
    const modal = new bootstrap.Modal(document.getElementById('complaintModal'));
    const modalBody = document.getElementById('complaintModalBody');
    
    modalBody.innerHTML = `
      <div class="text-center p-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Loading complaint details...</p>
      </div>
    `;
    
    modal.show();
    
    setTimeout(() => {
      modalBody.innerHTML = `
        <div class="alert alert-info">
          <strong>Note:</strong> This would show full complaint details including attachments, 
          conversation history, and detailed information.
        </div>
        <p><strong>Complaint ID:</strong> ${complaintId}</p>
      `;
    }, 1000);
  }

  // Show full description function
  function showFullDescription(description) {
    const modal = new bootstrap.Modal(document.getElementById('complaintModal'));
    const modalBody = document.getElementById('complaintModalBody');
    
    modalBody.innerHTML = `
      <div class="p-3">
        <h6>Full Description</h6>
        <div class="border p-3 bg-light">
          ${description.replace(/\n/g, '<br>')}
        </div>
      </div>
    `;
    
    modal.show();
  }

  // Alert function
  function showAlert(message, type) {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-dismissible fade show position-fixed`;
    alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    
    alertContainer.innerHTML = `
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertContainer);
    
    setTimeout(() => {
      if (alertContainer.parentNode) {
        alertContainer.remove();
      }
    }, 5000);
  }

  // Confirm delete function
  function confirmDelete() {
    return confirm('Are you sure you want to delete this complaint? This action cannot be undone.');
  }
</script>
</body>
</html>