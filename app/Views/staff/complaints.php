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
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
      --info-color: #17a2b8;
      --light-gray: #f8f9fa;
      --border-color: #e3e6f0;
    }
    
    body { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background-color: var(--background-color); 
      margin: 0; 
      line-height: 1.6;
    }
    
    .layout { 
      display: flex; 
      min-height: 100vh; 
      transition: margin-left 0.3s ease; 
    }

    /* Sidebar - Keep original styling */
    .sidebar { 
      width: 260px; 
      background-color: #fff; 
      border-right: 1px solid #e3e6f0; 
      display: flex; 
      flex-direction: column; 
      height: 100vh; 
      position: fixed; 
      top: 0; 
      left: 0; 
      overflow-y: auto; 
      transition: transform 0.3s ease;
      z-index: 1000;
    }
    .sidebar.hide { transform: translateX(-100%); }

    /* Sidebar toggle button */
    .sidebar-toggle { 
      position: fixed; 
      top: 15px; 
      left: 15px; 
      background-color: #fff; 
      border: 1px solid #ddd; 
      border-radius: 50%; 
      width: 45px; 
      height: 45px; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      cursor: pointer; 
      z-index: 1100; 
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: left 0.3s ease;
    }
    .sidebar.hide + .sidebar-toggle {
      left: 15px;
    }

    /* Content */
    .content { 
      flex-grow: 1; 
      margin-left: 260px; 
      padding: 2rem; 
      transition: margin-left 0.3s ease;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
    }
    .content.full { margin-left: 0; }

    /* Sidebar links - Keep original */
    .sidebar ul { list-style: none; padding: 0; margin: 0; }
    .sidebar a { display: flex; align-items: center; padding: 14px 22px; color: var(--text-color); font-size: 1rem; text-decoration: none; transition: background 0.2s ease; }
    .sidebar a:hover, .sidebar a.active { background-color: #eef1f8; font-weight: 600; }

    /* Enhanced Header */
    .page-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      color: white;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .page-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .page-header p {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 0;
    }

    /* Enhanced Stats Cards */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      border: none;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--card-color);
    }

    .stat-card.total::before { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-card.pending::before { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .stat-card.ongoing::before { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .stat-card.resolved::before { background: linear-gradient(135deg, #43e97b, #38f9d7); }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .stat-card-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-card-info h3 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: #2d3748;
    }

    .stat-card-info p {
      font-size: 0.95rem;
      color: #718096;
      margin: 0;
      font-weight: 500;
    }

    .stat-card-icon {
      width: 60px;
      height: 60px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: white;
    }

    .stat-card.total .stat-card-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
    .stat-card.pending .stat-card-icon { background: linear-gradient(135deg, #f093fb, #f5576c); }
    .stat-card.ongoing .stat-card-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); }
    .stat-card.resolved .stat-card-icon { background: linear-gradient(135deg, #43e97b, #38f9d7); }

    /* Enhanced NDA Section */
    .nda-section {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      border: 1px solid rgba(255,255,255,0.2);
    }

    .nda-header {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #f7fafc;
    }

    .nda-header h4 {
      font-size: 1.5rem;
      font-weight: 600;
      color: #2d3748;
      margin: 0;
    }

    /* Simple NDA Upload Area */
    .nda-upload-area {
      border: 2px dashed #ccc;
      border-radius: 8px;
      padding: 2rem;
      text-align: center;
      background: #f9f9f9;
      margin-bottom: 1rem;
      cursor: pointer;
    }

    .nda-upload-area:hover {
      border-color: #007bff;
      background: #f0f8ff;
    }

    .current-nda {
      background: #e8f5e8;
      border: 1px solid #c3e6c3;
      border-radius: 8px;
      padding: 1rem;
      margin-top: 1rem;
    }

    /* Simple NDA File Items */
    .nda-file-item {
      background: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .nda-file-item.active {
      background: #e8f5e8;
      border-color: #28a745;
    }

    /* Simple Buttons - Plain and Clean */
    .btn {
      border-radius: 4px;
      font-weight: 500;
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
      border: 1px solid transparent;
      text-decoration: none;
      display: inline-block;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .btn:hover {
      opacity: 0.9;
    }

    .btn-sm {
      padding: 0.3rem 0.6rem;
      font-size: 0.8rem;
    }

    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
      color: white;
    }

    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
      color: white;
    }

    .btn-success {
      background-color: #28a745;
      border-color: #28a745;
      color: white;
    }

    .btn-success:hover {
      background-color: #218838;
      border-color: #1e7e34;
      color: white;
    }

    .btn-info {
      background-color: #17a2b8;
      border-color: #17a2b8;
      color: white;
    }

    .btn-info:hover {
      background-color: #138496;
      border-color: #117a8b;
      color: white;
    }

    .btn-danger {
      background-color: #dc3545;
      border-color: #dc3545;
      color: white;
    }

    .btn-danger:hover {
      background-color: #c82333;
      border-color: #bd2130;
      color: white;
    }

    .btn-outline-primary {
      border-color: #007bff;
      color: #007bff;
      background: transparent;
    }

    .btn-outline-primary:hover {
      background-color: #007bff;
      color: white;
    }

    .btn-secondary {
      background-color: #6c757d;
      border-color: #6c757d;
      color: white;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
      border-color: #545b62;
      color: white;
    }

    /* Enhanced Complaints Table */
    .complaints-section {
      background: white;
      border-radius: 20px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    .complaints-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 1.5rem 2rem;
      margin: 0;
    }

    .complaints-header h4 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: 600;
    }

    .table-container {
      overflow-x: auto;
    }

    .complaints-table {
      margin: 0;
      border: none;
    }

    .complaints-table thead th {
      background: #f8fafc;
      border: none;
      font-weight: 600;
      color: #4a5568;
      padding: 1rem;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .complaints-table tbody tr {
      border-bottom: 1px solid #e2e8f0;
      transition: all 0.2s ease;
    }

    .complaints-table tbody tr:hover {
      background-color: #f7fafc;
      transform: scale(1.01);
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .complaints-table td {
      padding: 1.2rem 1rem;
      vertical-align: middle;
      border: none;
    }

    /* Enhanced Status Badges */
    .status-badge {
      display: inline-flex;
      align-items: center;
      padding: 0.5rem 1rem;
      border-radius: 25px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.3s ease;
    }

    .status-pending { 
      background: linear-gradient(135deg, #f093fb, #f5576c);
      color: white;
    }
    .status-ongoing { 
      background: linear-gradient(135deg, #4facfe, #00f2fe);
      color: white;
    }
    .status-resolved { 
      background: linear-gradient(135deg, #43e97b, #38f9d7);
      color: white;
    }

    /* Enhanced Alerts */
    .alert {
      border-radius: 15px;
      border: none;
      padding: 1.2rem 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .alert-success {
      background: linear-gradient(135deg, rgba(67, 233, 123, 0.1), rgba(56, 249, 215, 0.1));
      border-left: 4px solid #43e97b;
      color: #2f855a;
    }

    .alert-danger {
      background: linear-gradient(135deg, rgba(240, 147, 251, 0.1), rgba(245, 87, 108, 0.1));
      border-left: 4px solid #f093fb;
      color: #c53030;
    }

    .alert-warning {
      background: #fff3cd;
      border-left: 4px solid #ffc107;
      color: #856404;
    }

    /* Action Buttons Container */
    .action-buttons {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    /* Status Select Styling */
    .status-select {
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      padding: 0.5rem;
      background: white;
      font-size: 0.85rem;
      font-weight: 500;
      transition: all 0.3s ease;
      min-width: 120px;
    }

    .status-select:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    /* Complaint Description Preview */
    .complaint-preview {
      max-width: 200px;
      line-height: 1.4;
      color: #4a5568;
    }

    /* User Info Styling */
    .user-info {
      display: flex;
      flex-direction: column;
    }

    .user-name {
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 0.2rem;
    }

    .user-email {
      font-size: 0.8rem;
      color: #718096;
    }

    .anonymous-label {
      font-style: italic;
      color: #a0aec0;
      font-size: 0.9rem;
    }

    /* Filter and Search Bar */
    .filter-section {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .search-input {
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      padding: 0.75rem 1rem;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    .search-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: #a0aec0;
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 1rem;
      opacity: 0.5;
    }

    /* File upload styling */
    .file-input {
      border: 1px solid #ced4da;
      border-radius: 4px;
      padding: 0.5rem;
      font-size: 0.9rem;
      width: 100%;
      margin-bottom: 1rem;
    }

    .selected-file {
      background: #e8f5e8;
      border: 1px solid #c3e6c3;
      border-radius: 4px;
      padding: 0.5rem;
      margin-top: 0.5rem;
      font-size: 0.9rem;
    }

    @media (max-width: 768px) {
      .content { 
        margin-left: 0; 
        padding: 1rem;
      }
      .sidebar { 
        transform: translateX(-100%); 
      }
      .sidebar.show { 
        transform: translateX(0); 
      }
      .nda-section, .page-header { 
        padding: 1.5rem 1rem; 
      }
      .stats-container {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      .stat-card {
        padding: 1.5rem;
      }
      .action-buttons {
        flex-direction: column;
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
  <!-- Sidebar - Keep original structure -->
  <div class="sidebar" id="sidebar">
    <ul class="nav flex-column">
      <li class="text-center mb-2 mt-3">
        <img src="/images/logochre.jpg" alt="Logo" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
      </li>
      <li class="text-center mb-3">
        <span style="font-weight: bold; font-size: 1.1rem;"><?= esc(session()->get('full_name')) ?></span>
      </li>
      <li><a class="nav-link" href="<?= base_url('staff/dashboard') ?>"><i class='bx bx-grid-alt me-3'></i> Dashboard</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/opcr-checklist') ?>"><i class='bx bx-task me-3'></i> OPCR Checklist</a></li>
      <li><a class="nav-link active" href="<?= base_url('staff/complaints'); ?>"><i class='bx bx-message-square-error me-3'></i> Complaints</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/appointments') ?>"><i class='bx bx-calendar-check me-3'></i> Appointments</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/events') ?>"><i class='bx bx-calendar-event me-3'></i> Events</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/students') ?>"><i class='bx bx-user-voice me-3'></i> Students</a></li>
      <li><a class="nav-link" href="#"><i class='bx bx-id-card me-3'></i> CHRE Staff <span class="badge bg-primary ms-auto">1</span></a></li>
    </ul>
    <a href="<?= base_url('logout') ?>" class="btn btn-danger m-3"><i class='bx bx-log-out me-2'></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content" id="content">
    <!-- Enhanced Page Header -->
    <div class="page-header">
      <h1><i class='bx bx-message-square-error me-3'></i>Complaints Management Center</h1>
      <p>Manage student complaints and NDA documentation with comprehensive oversight and tracking capabilities.</p>
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

    <!-- Enhanced Stats Cards -->
    <div class="stats-container">
      <div class="stat-card total">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($total) ?></h3>
            <p>Total Complaints</p>
          </div>
          <div class="stat-card-icon">
            <i class='fas fa-clipboard-list'></i>
          </div>
        </div>
      </div>
      
      <div class="stat-card pending">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($pending) ?></h3>
            <p>Pending Review</p>
          </div>
          <div class="stat-card-icon">
            <i class='fas fa-clock'></i>
          </div>
        </div>
      </div>
      
      <div class="stat-card ongoing">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($ongoing) ?></h3>
            <p>In Progress</p>
          </div>
          <div class="stat-card-icon">
            <i class='fas fa-spinner'></i>
          </div>
        </div>
      </div>
      
      <div class="stat-card resolved">
        <div class="stat-card-content">
          <div class="stat-card-info">
            <h3><?= esc($resolved) ?></h3>
            <p>Resolved</p>
          </div>
          <div class="stat-card-icon">
            <i class='fas fa-check-circle'></i>
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
          <i class='fas fa-cloud-upload-alt fa-3x text-muted mb-3'></i>
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
              <h6 class="text-success mb-2"><i class='fas fa-check-circle me-2'></i>Current NDA File Active</h6>
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
      <?php else: ?>

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
                  <?php if (!$activeNda || $activeNda['id'] !== $nda['id']): ?>

                  <?php endif; ?>
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

    <!-- Enhanced Complaints Table -->
    <div class="complaints-section">
      <div class="complaints-header">
        <h4><i class='fas fa-list me-2'></i>Complaints Overview & Management</h4>
      </div>
      
      <div class="table-container">
        <table class="table complaints-table" id="complaintsTable">
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
              <tr class="complaint-row" data-status="<?= esc($c['status']) ?>" data-category="<?= esc($c['complaint_category']) ?>" data-type="<?= esc($c['complaint_type']) ?>">
                <td>
                  <div class="fw-bold text-primary"><?= $index + 1 ?></div>
                </td>
                <td>
                  <span class="badge bg-light text-dark border">
                    <i class='fas fa-tag me-1'></i><?= esc($c['complaint_category']) ?>
                  </span>
                </td>
                <td>
                  <span class="badge bg-secondary">
                    <i class='fas fa-folder me-1'></i><?= esc($c['complaint_type']) ?>
                  </span>
                </td>
                <td>
                  <div class="complaint-preview">
                    <?= esc(word_limiter(strip_tags($c['description']), 15)) ?>
                    <?php if (str_word_count(strip_tags($c['description'])) > 15): ?>
                      <small class="text-primary" style="cursor: pointer;" onclick="showFullDescription('<?= esc(addslashes($c['description'])) ?>')">
                        ...read more
                      </small>
                    <?php endif; ?>
                  </div>
                </td>
                <td>
                  <div class="user-info">
                    <?php if (empty($c['is_anonymous']) || $c['is_anonymous'] == 0): ?>
                      <div class="user-name">
                        <i class='fas fa-user me-1'></i><?= esc($c['full_name'] ?? 'Unknown') ?>
                      </div>
                      <div class="user-email">
                        <i class='fas fa-envelope me-1'></i><?= esc($c['email'] ?? 'No email') ?>
                      </div>
                      <?php if (!empty($c['contact_number'])): ?>
                        <div class="user-email">
                          <i class='fas fa-phone me-1'></i><?= esc($c['contact_number']) ?>
                        </div>
                      <?php endif; ?>
                    <?php else: ?>
                      <span class="anonymous-label">
                        <i class='fas fa-user-secret me-1'></i>Anonymous User
                      </span>
                    <?php endif; ?>
                  </div>
                </td>
                <td>
                  <div class="text-center">
                    <div class="fw-bold text-dark"><?= date('M d, Y', strtotime($c['date'])) ?></div>
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
                        <i class='fas fa-clock me-1'></i>Pending
                      <?php elseif ($c['status'] === 'ongoing'): ?>
                        <i class='fas fa-spinner me-1'></i>In Progress
                      <?php else: ?>
                        <i class='fas fa-check-circle me-1'></i>Resolved
                      <?php endif; ?>
                    </span>
                  </div>
                </td>
                <td>
                  <div class="action-buttons">
                    <button class="btn btn-primary btn-sm" onclick="viewComplaintDetails(<?= $c['id'] ?>)" title="View Full Details">
                      <i class='fas fa-eye'></i>
                    </button>
                    <a href="<?= base_url('complaint/view/' . $c['id']) ?>" target="_blank" 
                       class="btn btn-info btn-sm" title="Open in New Tab">
                      <i class='fas fa-external-link-alt'></i>
                    </a>
                    <form action="<?= base_url('complaint/delete/' . $c['id']) ?>" method="post" class="d-inline" onsubmit="return confirmDelete();">
                      <?= csrf_field() ?>
                      <button class="btn btn-danger btn-sm" title="Delete Complaint">
                        <i class='fas fa-trash'></i>
                      </button>
                    </form>
                  </div>
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
<div class="modal fade" id="complaintModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius: 15px;">
      <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px 15px 0 0;">
        <h5 class="modal-title"><i class='fas fa-file-alt me-2'></i>Complaint Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="complaintModalBody">
        <!-- Content will be loaded dynamically -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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
            badgeContent = '<i class="fas fa-clock me-1"></i>Pending';
          } else if (newStatus === 'ongoing') {
            badgeContent = '<i class="fas fa-spinner me-1"></i>In Progress';
          } else {
            badgeContent = '<i class="fas fa-check-circle me-1"></i>Resolved';
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
          <i class='fas fa-info-circle me-2'></i>
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
        <h6><i class='fas fa-align-left me-2'></i>Full Description</h6>
        <div class="border rounded p-3 bg-light">
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
      <i class='fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'} me-2'></i>
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