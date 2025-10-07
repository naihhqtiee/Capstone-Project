<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - OPCR Checklist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--background-color);
      color: var(--text-color);
      margin: 0;
      font-size: 14px;
      line-height: 1.6;
    }

    .layout {
      display: flex;
      min-height: 100vh;
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
      transition: margin-left 0.3s ease;
    }

    .content.full {
      margin-left: 0;
    }

    .navbar {
      background-color: #fff;
      border-bottom: 1px solid #e3e6f0;
      padding: 0.75rem 1.5rem;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: var(--card-shadow);
    }

    /* Enhanced Navbar */
    .navbar .form-control {
      border-radius: 20px;
      border: 1px solid var(--border-color);
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }

    .navbar .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    .progress-clickable {
      cursor: pointer;
      padding: 8px 16px;
      border-radius: 20px;
      transition: all 0.2s ease;
      font-size: 0.875rem;
      font-weight: 600;
      border: 2px solid;
    }

    .progress-clickable:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .text-success.progress-clickable {
      background: rgba(40, 167, 69, 0.1);
      border-color: #28a745;
      color: #28a745 !important;
    }

    .text-danger.progress-clickable {
      background: rgba(220, 53, 69, 0.1);
      border-color: #dc3545;
      color: #dc3545 !important;
    }

    /* Enhanced profile section in navbar */
    .navbar .dropdown-toggle {
      border: none;
      background: none;
      color: white;
    }

    .navbar .bg-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)) !important;
      border-radius: 25px;
      box-shadow: 0 2px 8px rgba(78, 115, 223, 0.3);
    }

    .notification-bell {
      position: relative;
    }

    .notification-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: #dc3545;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 0.6rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 600;
    }

    /* OPCR Table styling */
    .opcr-table {
      background: white;
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
      border: 1px solid var(--border-color);
    }

    .opcr-table th,
    .opcr-table td {
      border: 1px solid #e3e6f0;
      padding: 12px;
      vertical-align: top;
      font-size: 13px;
    }

    .opcr-table thead th {
      background: var(--primary-color);
      color: white;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 12px;
      text-align: left;
    }

    .opcr-table tbody tr:hover {
      background-color: #f8f9fa;
    }

    .opcr-table tbody tr.highlight-incomplete {
      background-color: #fff3cd !important;
      border-left: 4px solid #dc3545;
    }

    .col-mfo { width: 18%; }
    .col-indicator { width: 35%; }
    .col-accountable { width: 15%; }
    .col-status { width: 10%; text-align: center; }
    .col-remarks { width: 15%; }
    .col-actions { width: 7%; text-align: center; }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 6px 12px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
    }

    .status-done {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .status-ongoing {
      background: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }

    .status-empty {
      color: #6c757d;
    }

    .action-btn {
      padding: 6px 10px;
      margin: 0 2px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.2s ease;
    }

    .action-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    .btn-add { 
      background: #28a745; 
      color: white; 
    }
    
    .btn-edit { 
      background: var(--primary-color); 
      color: white; 
    }
    
    .btn-delete { 
      background: #dc3545; 
      color: white; 
    }

    /* Remarks input styling */
    .remarks-input-container {
      background: #f8f9fa;
      padding: 12px;
      border-radius: 8px;
      border: 1px solid #dee2e6;
    }

    .remarks-textarea {
      font-size: 12px;
      resize: vertical;
      min-height: 60px;
      border-radius: 6px;
      border: 1px solid var(--border-color);
    }

    .remarks-textarea:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    .file-input {
      font-size: 12px;
      border-radius: 6px;
    }

    .remarks-display {
      font-size: 12px;
      line-height: 1.4;
      padding: 8px;
      background: #e8f5e8;
      border-radius: 6px;
      border-left: 3px solid #28a745;
    }

    .attached-files {
      margin-top: 5px;
    }

    .file-attachment {
      display: flex;
      align-items: center;
      gap: 5px;
      padding: 4px 8px;
      background: #f1f3f4;
      border-radius: 4px;
      margin-bottom: 2px;
      font-size: 11px;
    }

    .user-account {
      font-size: 12px;
      font-weight: 500;
      color: #495057;
      padding: 6px 10px;
      background: #e3f2fd;
      border-radius: 6px;
      border-left: 3px solid #2196f3;
    }

    /* Section headers */
    .section-header {
      background: linear-gradient(135deg, #e3f2fd, #bbdefb);
      color: #1565c0;
      font-weight: bold;
      text-align: center;
      padding: 15px;
      margin: 20px 0 10px 0;
      border-radius: 8px;
      border-left: 4px solid #2196f3;
    }

    .subsection-header {
      background: linear-gradient(135deg, #f3e5f5, #e1bee7);
      color: #7b1fa2;
      font-weight: bold;
      text-align: center;
      padding: 12px;
      margin: 15px 0 8px 0;
      border-radius: 6px;
      border-left: 4px solid #9c27b0;
    }

    .section-row { 
      background: linear-gradient(135deg, #e9eefb, #d4e2fc); 
      font-weight: 700; 
      text-transform: uppercase; 
    }
    
    .section-row td { 
      padding: 12px; 
      border: 1px solid #e3e6f0; 
    }

    /* Enhanced buttons */
    .btn {
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-outline-primary {
      border-color: var(--primary-color);
      color: var(--primary-color);
    }

    .btn-outline-primary:hover {
      background: var(--primary-color);
      color: white;
    }

    /* Card styling */
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      overflow: hidden;
    }

    .card:hover {
      box-shadow: var(--hover-shadow);
    }

    /* Form controls */
    .form-control, .form-select {
      border-radius: 6px;
      border: 1px solid var(--border-color);
      transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    /* Dropdown menu styling */
    .dropdown-menu {
      border: none;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.12);
      border: 1px solid var(--border-color);
    }

    .dropdown-item {
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }

    .dropdown-item:hover {
      background: #f8f9fc;
      color: var(--primary-color);
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
      
      .navbar .d-flex {
        flex-wrap: wrap;
        gap: 0.5rem;
      }
    }

    @media (max-width: 768px) {
      .content {
        padding: 0.5rem;
      }
      
      .progress-clickable {
        font-size: 0.75rem;
        padding: 6px 12px;
      }
      
      .opcr-table th,
      .opcr-table td {
        padding: 8px;
        font-size: 12px;
      }
    }

    @media (max-width: 576px) {
      .navbar .form-control {
        width: 100%;
        margin-bottom: 0.5rem;
      }
      
      .action-btn {
        padding: 4px 8px;
        font-size: 11px;
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
          <a class="nav-link active" href="opcr-checklist">
            <i class='bx bx-task'></i>OPCR Checklist
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="complaints">
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

  <!-- Content -->
  <div class="content" id="content">
    <nav class="navbar d-flex align-items-center">
      <form class="d-flex align-items-center me-auto">
        <input class="form-control me-2" type="search" placeholder="Q Search" style="width: 200px;">
      </form>
      <div class="d-flex align-items-center" style="gap: 20px;">
        <span class="text-success fw-bold progress-clickable" id="completedPercent">90% Complete</span>
        <span class="text-danger fw-bold progress-clickable" id="incompletePercent">10% Not Completed</span>
        <button class="btn btn-outline-primary" onclick="downloadExcel()">Download Report</button>
        <div class="d-flex align-items-center bg-primary px-3 py-2 rounded-pill text-white" style="gap: 1rem;">
          <i class='bx bx-bell fs-4 position-relative'>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count">0</span>
          </i>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2" style="width:40px; height:40px;">
                <i class="bx bx-user text-dark fs-4"></i>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
              <li class="px-3 py-2">
                <div class="d-flex align-items-center">
                  <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2" style="width:50px; height:50px;">
                    <i class="bx bx-user text-dark fs-3"></i>
                  </div>
                  <div>
                    <div class="fw-bold"><?= esc(session()->get('full_name')) ?></div>
                    <small class="text-muted"><?= esc(session()->get('email')) ?></small>
                  </div>
                </div>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i> Account</a></li>
              <li><a class="dropdown-item" href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to log out?')"><i class="bx bx-log-out me-2"></i> Logout</a></li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="p-4">
      <h2 class="mb-4">OPCR Checklist</h2>
      
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="opcr-table" id="opcrTable">
              <thead>
                <tr>
                  <th class="col-mfo">MFO/PAP</th>
                  <th class="col-indicator">SUCCESS INDICATOR (TARGETS + MEASURES)</th>
                  <th class="col-accountable">UNIT/SECTION/INDIVIDUALS ACCOUNTABLE</th>
                  <th class="col-status">STATUS</th>
                  <th class="col-remarks">REMARKS</th>
                  <th class="col-actions">ACTIONS</th>
                </tr>
              </thead>
              <tbody>
                <!-- A. 2025 OPERATIONAL PLAN TARGETS -->
                <tr class="section-row">
                  <td colspan="6">A. 2025 Operational Plan Targets</td>
                </tr>
                <tr>
                  <td class="col-mfo">
                    <strong>Goal 3: Engaged Sustainable Communities</strong><br>
                    <strong>KRA 3: Extension Service</strong>
                  </td>
                  <td class="col-indicator">
                    <strong>3.1.</strong> Sustain awards received from local and international organizations<br>
                    <strong>3.1.1</strong> CHED-Philippine Anti-Illegal Drug Strategy (PADS) Innovative Awardee
                  </td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">
                    <button class="action-btn btn-add" title="Add">+</button>
                    <button class="action-btn btn-edit" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Delete">🗑</button>
                  </td>
                </tr>
                <!-- B. OFFICE CORE FUNCTIONS -->
                <tr class="section-row">
                  <td colspan="6">B. Office Core Functions</td>
                </tr>
                <tr>
                  <td class="col-mfo" rowspan="3">
                    <strong>1. Establishment of a system, standard or procedure in the implementation of Human Rights Education and related activities.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Approved CHRE operational guidelines within 2nd quarter of 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">
                    <button class="action-btn btn-add" title="Add">+</button>
                    <button class="action-btn btn-edit" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Delete">🗑</button>
                  </td>
                </tr>
                <tr>
                  <td class="col-indicator"><strong>b.</strong> Conducted quarterly meetings for updates and monitoring...</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr>
                  <td class="col-indicator"><strong>c.</strong> Conducted radio programs with trained HR advocates weekly</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <!-- 2. Facilitate in the conduct... -->
                <tr data-status="ongoing">
                  <td class="col-mfo" rowspan="3">
                    <strong>2. Facilitate in the conduct of human rights education activities involving administrator/school heads, department heads, faculty and staff, students and community clientele on its own or in</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Signed MOU with IBP and RICCI in furtherance of Human Rights Education in the college and the rest of the adopted communities of CSPC.</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Published articles related to human rights in the SPARK Publications once every semester.</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="ongoing">
                  <td class="col-indicator"><strong>c.</strong> Submitted monitoring reports on the integration of GAD perspectives in the College's teaching and learning modalities to the immediate supervisor by May and December 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 3. Promote and advocate... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>3. Promote and advocate for the promotion of rights based approach to development and governance, especially among its partners in the local government</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Conducted Voter's Education Forum for CSPC employees and students within April 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 4. Establish network and partnerships... -->
                <tr data-status="done">
                  <td class="col-mfo" rowspan="2">
                    <strong>4. Establish network and partnerships with government and non‑government organization in the conduct of human rights education activities.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Conducted LANTANGAN V3.0: A Human Rights Education Forum within the 2nd quarter</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Conducted RA 8972 Solo Parent Act/Orientation Seminar with students‑solo parents within April and November</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 5. Coordinate with CHR Regional Office... -->
                <tr data-status="ongoing">
                  <td class="col-mfo" rowspan="2">
                    <strong>5. Coordinate with CHR Regional Office with regard to program, project and activities.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> IRIBANAN 2025: Empowering CHRE organization through capacity building within February 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="ongoing">
                  <td class="col-indicator"><strong>b.</strong> CHRE Accredited Level 1 by 1st quarter</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 6. Formulate the Center's Annual Plan of Action... -->
                <tr data-status="done">
                  <td class="col-mfo" rowspan="3">
                    <strong>6. Formulate the Center's Annual Plan of Action covering human rights education, information, dissemination, monitoring and evaluation.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Submitted calendar of activities for 2025 to VPAA on or before the first week of January of 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Distributed IEC Material within the campus, adopted barangay and other sectors of the society.</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>c.</strong> Submitted proposal for Benchlearning Activities in UP and other SUC's with CHRE</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 7. Submit semestral reports... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>7. Submit semestral reports of its accomplishments to the CHR and Office of the College President.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Submit semestral reports of its accomplishments to the CHR and Office of the College President within May and December 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 8. Assist in the filing of the complaint... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>8. Assist in the filing of the complaint, if there is any, involving human rights violations in the institution.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Submitted Accomplishment report of cases related to Human Rights</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 9. Perform other duties... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>9. Perform other duties and functions that may be assigned/delegated by the College/University President.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Coordinated with Extension Community Services Unit on the application for CHED‑PADS Innovative Award as scheduled</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 10. Client Satisfaction Management -->
                <tr data-status="done">
                  <td class="col-mfo" rowspan="3">
                    <strong>10. Client Satisfaction Management</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Maintained a “Very Satisfactory Rating” on Client Satisfaction Measurement Survey</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Received no Negative Feedback and Disagree/Strongly Disagree ratings on CSM Survey</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>c.</strong> Received no Customer complaint</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
function updatePercentages() {
  // Count only real data rows (those that have an Actions cell)
  const rows = [...document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr')]
    .filter(r => r.querySelector('td.col-actions'));

  const total = rows.length;

  // Completed = rows with a non-empty Status cell (e.g., “Done”).
  // Blank Status ⇒ Not Completed.
  const completed = rows.filter(r => (r.querySelector('td.col-status')?.textContent || '').trim().length > 0).length;

  const incomplete = total - completed;
  const completedPercent = total ? Math.round((completed / total) * 100) : 0;
  const incompletePercent = total ? Math.round((incomplete / total) * 100) : 0;

  document.getElementById('completedPercent').textContent = `${completedPercent}% Complete`;
  document.getElementById('incompletePercent').textContent = `${incompletePercent}% Not Completed`;
}
updatePercentages();
</script>

<script>
function tableToSheetWithMerges(tableEl) {
  // Build AOA and merges, skipping the Actions column
  const rows = Array.from(tableEl.querySelectorAll('tr'));
  const aoa = [];
  const merges = [];

  rows.forEach((tr, r) => {
    if (!aoa[r]) aoa[r] = [];
    let c = 0;

    Array.from(tr.children)
      .filter(td => !td.classList.contains('col-actions'))
      .forEach(td => {
        while (aoa[r][c] !== undefined) c++; // skip occupied cells

        const txt = (td.innerText || '').replace(/\u00a0/g,' ').trim();
        aoa[r][c] = txt;

        const cs = td.colSpan || 1;
        const rs = td.rowSpan || 1;

        if (cs > 1 || rs > 1) {
          merges.push({ s: { r, c }, e: { r: r + rs - 1, c: c + cs - 1 } });
          for (let rr = 0; rr < rs; rr++) {
            for (let cc = 0; cc < cs; cc++) {
              if (rr === 0 && cc === 0) continue;
              if (!aoa[r + rr]) aoa[r + rr] = [];
              aoa[r + rr][c + cc] = null; // mark occupied
            }
          }
        }
        c++;
      });
  });

  const ws = XLSX.utils.aoa_to_sheet(aoa);
  ws['!merges'] = merges;
  ws['!cols'] = [
    { wch: 36 }, // MFO/PAP
    { wch: 80 }, // Success Indicator
    { wch: 32 }, // Unit/Section/Individuals Accountable
    { wch: 14 }, // Status
    { wch: 48 }  // Remarks
  ];
  return ws;
}

function downloadExcel() {
  const wb = XLSX.utils.book_new();

  const t1 = document.getElementById('opcrTable');
  if (t1) XLSX.utils.book_append_sheet(
    wb,
    tableToSheetWithMerges(t1),
    'A. 2025 OP PLAN TARGETS'
  );

  const t2 = document.getElementById('opcrTable2');
  if (t2) XLSX.utils.book_append_sheet(
    wb,
    tableToSheetWithMerges(t2),
    'B. OFFICE CORE FUNCTIONS'
  );

  XLSX.writeFile(wb, 'OPCR_Checklist.xlsx');
}
</script>

<script>
  // Highlight incomplete rows
  function highlightIncomplete() {
    document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr')
      .forEach(r => r.classList.remove('highlight-incomplete'));

    const rows = [...document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr')]
      .filter(r => r.querySelector('td.col-actions')); // only data rows

    rows.forEach(r => {
      const txt = (r.querySelector('td.col-status')?.textContent || '').replace(/\u00a0/g,'').trim();
      if (txt === '') r.classList.add('highlight-incomplete'); // blank ⇒ not completed
    });
  }

  // Remove highlights
  function removeHighlights() {
    document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr').forEach(row => {
      row.classList.remove('highlight-incomplete');
    });
  }

  // Event listeners
  document.getElementById('incompletePercent').addEventListener('click', highlightIncomplete);
  document.getElementById('completedPercent').addEventListener('click', removeHighlights);

  // Sidebar dropdown toggle
  document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentElement.classList.toggle('open');
    });
  });

  // Sidebar toggle button
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });

  // Initialize percentages on page load
  // updatePercentages(); // This line is now handled by the new updatePercentages function

  // Search functionality
  const searchInput = document.querySelector('input[type="search"]');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const table = document.getElementById('opcrTable');
      const rows = table.querySelectorAll('tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  }
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const actionHTML = `
    <button type="button" class="action-btn btn-add" title="Add" onclick="showRemarksInput(this)">+</button>
    <button type="button" class="action-btn btn-edit" title="Edit">✎</button>
    <button type="button" class="action-btn btn-delete" title="Delete">🗑</button>
  `;

  // Replace ALL action cells to ensure consistency
  document.querySelectorAll('#opcrTable tbody td.col-actions, #opcrTable2 tbody td.col-actions')
    .forEach(td => {
      console.log('Replacing action buttons in:', td); // Debug log
      td.innerHTML = actionHTML;
    });

  // Don't auto-populate user account - only show when remarks are added

  // Event delegation for Edit and Delete buttons
  const tables = [document.getElementById('opcrTable'), document.getElementById('opcrTable2')].filter(Boolean);
  tables.forEach(table => {
    table.addEventListener('click', function(e) {
      const target = e.target;
      if (target.classList.contains('btn-edit')) {
        editRemarks(target);
      } else if (target.classList.contains('btn-delete')) {
        deleteRemarks(target);
      }
    });
  });
});

function showRemarksInput(button) {
  console.log('Plus button clicked!'); // Debug log
  const row = button.closest('tr');
  const remarksCell = row.querySelector('.col-remarks');
  const accountableCell = row.querySelector('.col-accountable');
  const statusCell = row.querySelector('.col-status');
  
  // Check if input already exists
  if (remarksCell.querySelector('.remarks-input-container')) {
    return;
  }

  // Create input container
  const inputContainer = document.createElement('div');
  inputContainer.className = 'remarks-input-container';
  inputContainer.innerHTML = `
    <div class="mb-2">
      <textarea class="form-control remarks-textarea" placeholder="Enter remarks..." rows="3"></textarea>
    </div>
    <div class="mb-2">
      <label class="form-label small">Attach File:</label>
      <input type="file" class="form-control form-control-sm file-input" multiple>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-success btn-sm" onclick="saveRemarks(this)">Save</button>
      <button type="button" class="btn btn-secondary btn-sm" onclick="cancelRemarks(this)">Cancel</button>
    </div>
  `;

  remarksCell.innerHTML = '';
  remarksCell.appendChild(inputContainer);
  
  // Focus on textarea
  const textarea = inputContainer.querySelector('.remarks-textarea');
  textarea.focus();
}

function saveRemarks(button) {
  const container = button.closest('.remarks-input-container');
  const textarea = container.querySelector('.remarks-textarea');
  const fileInput = container.querySelector('.file-input');
  const row = button.closest('tr');
  const remarksCell = row.querySelector('.col-remarks');
  const statusCell = row.querySelector('.col-status');
  const accountableCell = row.querySelector('.col-accountable');
  
  const remarksText = textarea.value.trim();
  const files = Array.from(fileInput.files);
  
  if (remarksText) {
    // Create remarks display
    let remarksHTML = `<div class="remarks-display">${remarksText}</div>`;
    
    // Add file attachments if any
    if (files.length > 0) {
      remarksHTML += '<div class="attached-files mt-2">';
      files.forEach(file => {
        remarksHTML += `<div class="file-attachment small text-muted">
          <i class='bx bx-paperclip'></i> ${file.name}
        </div>`;
      });
      remarksHTML += '</div>';
    }
    
    remarksCell.innerHTML = remarksHTML;
    
    // Mark as done
    statusCell.innerHTML = '<span class="status-badge status-done">Done</span>';
    
    // Add user account to accountable column only when remarks are saved
    const userName = '<?= esc(session()->get("full_name")) ?>';
    accountableCell.innerHTML = `<div class="user-account">${userName}</div>`;
    
    // Update percentages
    updatePercentages();
  } else {
    alert('Please enter remarks before saving.');
  }
}

function cancelRemarks(button) {
  const container = button.closest('.remarks-input-container');
  const row = button.closest('tr');
  const remarksCell = row.querySelector('.col-remarks');
  
  remarksCell.innerHTML = '';
}

// Turn existing remarks into an editable input
function editRemarks(button) {
  const row = button.closest('tr');
  const remarksCell = row.querySelector('.col-remarks');
  const currentText = (remarksCell.textContent || '').trim();

  // If already editing, do nothing
  if (remarksCell.querySelector('.remarks-input-container')) return;

  const inputContainer = document.createElement('div');
  inputContainer.className = 'remarks-input-container';
  inputContainer.innerHTML = `
    <div class="mb-2">
      <textarea class="form-control remarks-textarea" placeholder="Enter remarks..." rows="3"></textarea>
    </div>
    <div class="mb-2">
      <label class="form-label small">Attach File:</label>
      <input type="file" class="form-control form-control-sm file-input" multiple>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-success btn-sm" onclick="saveRemarks(this)">Save</button>
      <button type="button" class="btn btn-secondary btn-sm" onclick="cancelRemarks(this)">Cancel</button>
    </div>
  `;

  remarksCell.innerHTML = '';
  remarksCell.appendChild(inputContainer);
  const textarea = inputContainer.querySelector('.remarks-textarea');
  textarea.value = currentText;
  textarea.focus();
}

// Clear remarks, status, and accountable for the row
function deleteRemarks(button) {
  if (!confirm('Clear remarks and reset status for this row?')) return;
  const row = button.closest('tr');
  const remarksCell = row.querySelector('.col-remarks');
  const statusCell = row.querySelector('.col-status');
  const accountableCell = row.querySelector('.col-accountable');

  remarksCell.innerHTML = '';
  statusCell.innerHTML = '';
  accountableCell.innerHTML = '';
  updatePercentages();
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>