<?php
// Get user registrations (you'll need to adjust this query based on your database structure)
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KARAMAY - Dashboard</title>
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

    .topbar-actions {
      display: flex;
      align-items: center;
      gap: 20px;
    }

/* Notification Bell Button */
.notification-bell {
  position: relative;
  background: #ffffff;
  border: none;
  border-radius: 50%;
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  color: #4b5563; /* neutral gray */
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.notification-bell:hover {
  background: #f3f4f6;
  color: #2563eb; /* your primary blue */
  transform: scale(1.05);
}

/* Red Badge */
.notification-badge {
  position: absolute;
  top: 6px;
  right: 6px;
  background: #ef4444; /* red */
  color: white;
  border-radius: 50%;
  width: 18px;
  height: 18px;
  font-size: 0.7rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  animation: pulse 2s infinite;
}

/* Dropdown Panel */
.notification-dropdown {
  position: absolute;
  top: 55px; /* below bell */
  right: 0;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  box-shadow: 0 6px 18px rgba(0,0,0,0.1);
  width: 300px;
  max-height: 350px;
  overflow-y: auto;
  z-index: 9999;
  padding: 10px 0;
  display: none;
  animation: fadeIn 0.25s ease-in-out;
}

/* Dropdown List */
.notification-dropdown ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.notification-dropdown li {
  padding: 12px 16px;
  font-size: 0.9rem;
  color: #374151;
  border-bottom: 1px solid #f3f4f6;
  cursor: pointer;
  transition: background 0.2s ease;
}

.notification-dropdown li:hover {
  background: #f9fafb;
}

    .user-menu {
      position: relative;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      cursor: pointer;
      font-weight: 600;
      border: 2px solid white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .dropdown-menu {
      position: absolute;
      top: 100%;
      right: 0;
      background: white;
      border: 1px solid rgba(0,0,0,0.1);
      border-radius: 10px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
      min-width: 200px;
      z-index: 1001;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
    }

    .dropdown-menu.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }

    .dropdown-item {
      padding: 12px 20px;
      display: flex;
      align-items: center;
      color: var(--dark-color);
      text-decoration: none;
      transition: background 0.2s ease;
    }

    .dropdown-item:hover {
      background: var(--light-color);
      color: var(--primary-color);
    }

    .dropdown-item i {
      margin-right: 10px;
      font-size: 1.1rem;
    }

    /* Content Area */
    .content-area {
      padding: 30px;
    }

    /* Welcome Section */
    .welcome-section {
      background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      border: 1px solid rgba(255,255,255,0.2);
    }

    .welcome-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .welcome-text h1 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 10px;
    }

    .welcome-text p {
      font-size: 1.1rem;
      color: #64748b;
      margin-bottom: 0;
    }

    .quick-actions {
      display: flex;
      gap: 15px;
    }

    .quick-action-btn {
      padding: 12px 20px;
      border-radius: 12px;
      text-decoration: none;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .quick-action-btn.primary {
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      color: white;
    }

    .quick-action-btn.secondary {
      background: linear-gradient(135deg, var(--secondary-color), #fbbf24);
      color: white;
    }

    .quick-action-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-left: 4px solid transparent;
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
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: white;
      margin-bottom: 20px;
      background: linear-gradient(135deg, var(--primary-color), var(--info-color));
    }

    .stat-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 8px;
    }

    .stat-label {
      color: #64748b;
      font-size: 1rem;
      font-weight: 500;
    }

    /* Events Section */
    .events-section {
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      margin-bottom: 30px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid var(--light-color);
    }

    .section-title {
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--dark-color);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .section-title i {
      color: var(--primary-color);
    }

    .filter-tabs {
      display: flex;
      gap: 10px;
      background: var(--light-color);
      padding: 5px;
      border-radius: 10px;
    }

    .filter-tab {
      padding: 8px 16px;
      border-radius: 8px;
      background: none;
      border: none;
      font-weight: 500;
      color: #64748b;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-tab.active, .filter-tab:hover {
      background: white;
      color: var(--primary-color);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .events-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
      gap: 25px;
    }

    .event-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      border: 1px solid rgba(0,0,0,0.05);
      position: relative;
    }

    .event-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .event-image {
      height: 200px;
      background-size: cover;
      background-position: center;
      position: relative;
      overflow: hidden;
    }

    .event-image::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.3) 100%);
    }

    .event-date-badge {
      position: absolute;
      top: 15px;
      left: 15px;
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(10px);
      padding: 8px 12px;
      border-radius: 10px;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--primary-color);
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .event-details {
      padding: 25px;
    }

    .event-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 12px;
      line-height: 1.4;
    }

    .event-description {
      font-size: 0.95rem;
      color: #64748b;
      line-height: 1.6;
      margin-bottom: 15px;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .event-meta {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-bottom: 20px;
      font-size: 0.9rem;
      color: #64748b;
    }

    .event-meta i {
      color: var(--primary-color);
    }

    .event-actions {
      display: flex;
      gap: 12px;
    }

    .btn {
      padding: 10px 20px;
      border-radius: 10px;
      font-weight: 600;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      font-size: 0.9rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      color: white;
    }

    .btn-outline {
      background: white;
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
    }

    .btn-warning {
      background: linear-gradient(135deg, var(--warning-color), #fbbf24);
      color: white;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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
      font-size: 1.3rem;
    }

    .modal-body {
      padding: 30px;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 8px;
    }

    .form-control, .form-select {
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      padding: 12px 15px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
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

      .welcome-content {
        flex-direction: column;
        gap: 20px;
        text-align: center;
      }

      .welcome-text h1 {
        font-size: 1.5rem;
      }

      .quick-actions {
        flex-wrap: wrap;
        justify-content: center;
      }

      .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
      }

      .events-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .events-section {
        padding: 20px;
      }

      .section-header {
        flex-direction: column;
        gap: 20px;
      }

      .filter-tabs {
        flex-wrap: wrap;
      }
    }

    @media (max-width: 480px) {
      .page-title {
        font-size: 1.2rem;
      }

      .welcome-text h1 {
        font-size: 1.3rem;
      }

      .stat-value {
        font-size: 2rem;
      }

      .event-details {
        padding: 20px;
      }
    }

    /* Animations */
    .fade-in {
      animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .slide-in {
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from { transform: translateX(-30px); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    /* Loading Animation */
    .loading-spinner {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 2px solid #f3f3f3;
      border-top: 2px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Custom Scrollbar */
    .sidebar::-webkit-scrollbar {
      width: 4px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: rgba(255,255,255,0.1);
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255,255,255,0.3);
      border-radius: 2px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(255,255,255,0.5);
    }

    /* Alert Styles */
    .alert {
      border: none;
      border-radius: 12px;
      padding: 15px 20px;
      margin-bottom: 20px;
    }

    .alert-success {
      background: rgba(16, 185, 129, 0.1);
      color: #059669;
      border-left: 4px solid var(--success-color);
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
        <a href="/user/userdashboard" class="nav-link active">
          <i class='bx bx-home'></i>
          <span>Dashboard</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/filing-complaint" class="nav-link">
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
    <!-- Top Bar -->
    <div class="topbar">
      <div style="display: flex; align-items: center;">
        <button class="menu-toggle" id="menuToggle">
          <i class='bx bx-menu'></i>
        </button>
        <div class="page-title">
          <i class='bx bx-home'></i>
          Dashboard
        </div>
      </div>
      <div class="topbar-actions">
<button class="notification-bell" id="notificationBell">
  <i class='bx bx-bell'></i>
  <span class="notification-badge" id="notificationCount"></span>
</button>

<div id="notificationDropdown" class="notification-dropdown" style="display:none;">
  <ul id="notificationList"></ul>
</div>

        <div class="user-menu">
          <div class="user-avatar" id="userDropdown">
            <i class='bx bx-user'></i>
          </div>
          <div class="dropdown-menu" id="dropdownMenu">
            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#profileModal">
              <i class='bx bx-user-circle'></i>
              My Profile
            </a>
            <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#passwordModal">
              <i class='bx bx-lock-alt'></i>
              Change Password
            </a>
            <hr style="margin: 5px 0; border: none; border-top: 1px solid #e2e8f0;">
            <a href="#" class="dropdown-item">
              <i class='bx bx-help-circle'></i>
              Help & Support
            </a>
            <a href="/logout" class="dropdown-item text-danger">
              <i class='bx bx-log-out'></i>
              Logout
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
      <!-- Success Alert -->
      <div class="alert alert-success fade-in" id="successAlert" style="display: none;">
        <i class='bx bx-check-circle me-2'></i>
        <span id="alertMessage">Welcome back! Your profile has been updated successfully.</span>
        <button type="button" class="btn-close" onclick="closeAlert()"></button>
      </div>

      <!-- Welcome Section -->
      <div class="welcome-section fade-in">
        <div class="welcome-content">
          <div class="welcome-text">
            <h1>Welcome back, <?= esc(session()->get('full_name')) ?> 👋</h1>
            <p>Stay updated with the latest events and manage your services efficiently.</p>
          </div>
          <div class="quick-actions">
            <a href="/user/filing-complaint" class="quick-action-btn primary">
              <i class='bx bx-edit-alt'></i>
              File Complaint
            </a>
            <a href="/user/appointment" class="quick-action-btn secondary">
              <i class='bx bx-calendar-plus'></i>
              Book Appointment
            </a>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
     <div class="stats-grid fade-in">
  <div class="stat-card">
    <div class="stat-icon">
      <i class='bx bx-calendar-event'></i>
    </div>
    <div class="stat-value"><?= $activeEvents ?></div>
    <div class="stat-label">Active Events</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class='bx bx-calendar-check'></i>
    </div>
    <div class="stat-value"><?= $myAppointments ?></div>
    <div class="stat-label">My Appointments</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class='bx bx-file-blank'></i>
    </div>
    <div class="stat-value"><?= $myComplaints ?></div>
    <div class="stat-label">My Complaints</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon">
      <i class='bx bx-star'></i>
    </div>
    <div class="stat-value"><?= $registeredEvents ?></div>
    <div class="stat-label">Registered Events</div>
  </div>
</div>

      <!-- Events Section -->
<div class="events-section fade-in">
    <div class="section-header">
        <div class="section-title">
            <i class='bx bx-calendar-event'></i>
            Upcoming Events & Services
        </div>
        <div class="filter-tabs">
            <button class="filter-tab active" data-filter="all">All Events</button>
            <button class="filter-tab" data-filter="upcoming">Upcoming</button>
            <button class="filter-tab" data-filter="registered">My Events</button>
        </div>
    </div>

<div class="events-grid" id="eventsGrid">
    <?php if (!empty($events)): ?>
        <?php foreach ($events as $event): ?>
            <?php
            $is_registered = in_array($event['id'], $registered_event_ids);
            $event_start = new DateTime($event['start_date']);
            $event_end = new DateTime($event['end_date']);
            $today = new DateTime();
            $is_upcoming = $event_start > $today;
            $is_ongoing = $today >= $event_start && $today <= $event_end;

            // ✅ Determine event category
            $category = 'upcoming';
            if ($is_registered) {
                $category = 'registered'; // Mark as "My Events"
            } elseif (!$is_upcoming && !$is_ongoing) {
                $category = 'past';
            }

            // Skip past events unless registered
            if (!$is_upcoming && !$is_ongoing && !$is_registered) {
                continue;
            }
            ?>

            <div class="event-card" 
                 data-category="<?= $category ?>" 
                 data-event-id="<?= $event['id'] ?>">
                 
                <!-- Event Image -->
                <div class="event-image" style="background-image: url('<?= !empty($event['file']) 
                    ? base_url('uploads/events/' . $event['file']) 
                    : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500&h=300&fit=crop' ?>');">
                    
                    <div class="event-date-badge">
                        <?php if ($event['start_date'] === $event['end_date']): ?>
                            <?= date('M d, Y', strtotime($event['start_date'])) ?>
                        <?php else: ?>
                            <?= date('M d', strtotime($event['start_date'])) ?> - <?= date('M d, Y', strtotime($event['end_date'])) ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($is_ongoing): ?>
                        <div class="event-status-badge ongoing">
                            <i class='bx bx-broadcast'></i>
                            Live Now
                        </div>
                    <?php elseif (!$is_upcoming && !$is_ongoing): ?>
                        <div class="event-status-badge completed">
                            <i class='bx bx-check'></i>
                            Completed
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Event Details -->
                <div class="event-details">
                    <div class="event-title"><?= esc($event['event_name']) ?></div>
                    <div class="event-description">
                        <?= esc(substr($event['description'], 0, 150)) ?>
                        <?= strlen($event['description']) > 150 ? '...' : '' ?>
                    </div>
                    
                    <div class="event-meta">
                        <span><i class='bx bx-map'></i> <?= esc($event['location']) ?></span>
                        <?php if (isset($event['start_time'])): ?>
                            <span><i class='bx bx-time'></i> <?= date('g:i A', strtotime($event['start_time'])) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="event-actions">
                        <!-- View Details -->
                        <?php if (!empty($event['file'])): ?>
                            <button class="btn btn-primary" onclick="viewEventDetails(<?= $event['id'] ?>, '<?= addslashes($event['event_name']) ?>')">
                                <i class='bx bx-info-circle'></i>
                                Read More
                            </button>
                        <?php else: ?>
                            <button class="btn btn-primary" onclick="viewEventDetails(<?= $event['id'] ?>, '<?= addslashes($event['event_name']) ?>')">
                                <i class='bx bx-info-circle'></i>
                                View Details
                            </button>
                        <?php endif; ?>
                        
                        <!-- Registration Button -->
                        <?php if ($is_registered): ?>
                            <button class="btn btn-outline registered" disabled>
                                <i class='bx bx-check'></i>
                                Registered
                            </button>
                        <?php elseif ($is_upcoming || $is_ongoing): ?>
                            <?php if (
                                $event['audience'] === 'everyone' ||
                                ($event['audience'] === 'students' && $current_user_role === 'student') ||
                                ($event['audience'] === 'employees' && $current_user_role === 'employee')
                            ): ?>
                                <button type="button" 
                                        class="btn btn-warning register-btn" 
                                        data-event-id="<?= $event['id'] ?>" 
                                        data-event-name="<?= esc($event['event_name']) ?>">
                                    <i class='bx bx-user-plus'></i>
                                    Join Now
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class='bx bx-lock'></i>
                                    Restricted
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-events-message">
            <div class="empty-state">
                <i class='bx bx-calendar-x'></i>
                <h3>No Events Available</h3>
                <p>There are currently no events scheduled. Check back later for upcoming events and programs.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<div style="text-align: center; margin-top: 30px;">
  <button class="btn btn-outline" id="loadMoreBtn">
    <i class='bx bx-plus'></i>
    Load More Events
  </button>
</div>

      </div>
    </div>
  </div>

  <!-- Mobile Overlay -->
  <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileSidebar()"></div>

  <!-- Profile Modal -->
  <div class="modal fade" id="profileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <form method="post" action="<?= base_url('student/updateProfile') ?>">
          <div class="modal-header bg-warning">
            <h5 class="modal-title">My Profile</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body row g-3">
            <div class="col-md-4 text-center">
              <i class='bx bx-user-circle' style="font-size:80px;color:#0a3a5a;"></i>
              <p class="fw-bold mt-2 mb-0"><?= session('full_name') ?></p>
              <small class="text-muted"><?= session('role') ?></small>
            </div>
            <div class="col-md-8">
              <div class="mb-2"><label class="form-label">First Name</label><input type="text" class="form-control" name="first_name" value="<?= session('first_name') ?>" required></div>
              <div class="mb-2"><label class="form-label">Middle Initial</label><input type="text" class="form-control" name="mi" value="<?= session('mi') ?>" maxlength="1"></div>
              <div class="mb-2"><label class="form-label">Last Name</label><input type="text" class="form-control" name="last_name" value="<?= session('last_name') ?>" required></div>
              <div class="mb-2"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= session('email') ?>" required></div>
              <div class="mb-2"><label class="form-label">Contact Number</label><input type="text" class="form-control" name="contact_number" value="<?= session('contact_number') ?>" required></div>
              <div class="mb-2"><label class="form-label">Department</label><input type="text" class="form-control" name="department" value="<?= session('department') ?>" required></div>
              <div class="mb-2"><label class="form-label">Course</label><input type="text" class="form-control" name="course" value="<?= session('course') ?>" required></div>
              <div class="mb-2"><label class="form-label">Year</label>
                <select name="year" class="form-select" required>
                  <option value="">Select Year</option>
                  <option value="1st Year" <?= session('year') === '1st Year' ? 'selected' : '' ?>>1st Year</option>
                  <option value="2nd Year" <?= session('year') === '2nd Year' ? 'selected' : '' ?>>2nd Year</option>
                  <option value="3rd Year" <?= session('year') === '3rd Year' ? 'selected' : '' ?>>3rd Year</option>
                  <option value="4th Year" <?= session('year') === '4th Year' ? 'selected' : '' ?>>4th Year</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-warning"><?= session('first_name') ? 'Update Profile' : 'Save Profile' ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Change Password Modal -->
  <div class="modal fade" id="passwordModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="/user/changePassword" method="post">
          <div class="modal-header">
            <h5 class="modal-title">
              <i class='bx bx-lock-alt me-2'></i>
              Change Password
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Current Password</label>
              <div style="position: relative;">
                <input type="password" class="form-control" name="current_password" id="currentPassword" required>
                <i class='bx bx-show' style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword('currentPassword', this)"></i>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <div style="position: relative;">
                <input type="password" class="form-control" name="new_password" id="newPassword" required>
                <i class='bx bx-show' style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword('newPassword', this)"></i>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm New Password</label>
              <div style="position: relative;">
                <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                <i class='bx bx-show' style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer;" onclick="togglePassword('confirmPassword', this)"></i>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">
              <i class='bx bx-save'></i>
              Update Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Event Registration Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
<form method="post" action="<?= base_url('/user/register-event') ?>">
    <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title">
            <i class='bx bx-calendar-plus me-2'></i>
            Register for Event
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="event_id" id="eventIdInput">
    <input type="hidden" name="event_name" id="eventNameInput">

          <div class="mb-3">
            <label class="form-label">Event Name</label>
            <input type="text" class="form-control" id="eventNameDisplay" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="full_name" value="<?= session('full_name') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control" name="email" value="<?= session('email') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="text" class="form-control" name="contact_number" value="<?= session('contact_number') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Special Requirements (Optional)</label>
            <textarea class="form-control" name="special_requirements" rows="3"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class='bx bx-check'></i>
            Register Now
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

  <!-- Scripts -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const menuToggle = document.getElementById('menuToggle');
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const filterTabs = document.querySelectorAll('.filter-tab');
    const eventCards = document.querySelectorAll('.event-card');
    const successAlert = document.getElementById('successAlert');

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

    function closeMobileSidebar() {
      if (isMobile()) {
        sidebar.classList.remove('mobile-open');
        mobileOverlay.style.display = 'none';
      }
    }

    userDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdownMenu.classList.toggle('show');
    });

    document.addEventListener('click', function(e) {
      if (!userDropdown.contains(e.target)) {
        dropdownMenu.classList.remove('show');
      }
    });

    // ✅ FIXED Event Filtering
    function applyFilter(filter) {
      const eventCards = document.querySelectorAll('.event-card');
      
      eventCards.forEach(card => {
        const category = card.getAttribute('data-category');
        
        if (filter === 'all') {
          card.style.display = 'block';
        } else if (filter === 'registered') {
          if (category === 'registered') {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        } else if (filter === 'upcoming') {
          if (category === 'upcoming') {
            card.style.display = 'block';
          } else {
            card.style.display = 'none';
          }
        } else {
          card.style.display = 'none';
        }
      });

      checkEmptyState(filter);
    }

    filterTabs.forEach(tab => {
      tab.addEventListener('click', function() {
        filterTabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');
        applyFilter(filter);
      });
    });

    function checkEmptyState(filter) {
      // Wait a bit for display changes to apply
      setTimeout(() => {
        const eventCards = document.querySelectorAll('.event-card');
        const visibleCards = Array.from(eventCards).filter(card => {
          const display = window.getComputedStyle(card).display;
          return display !== 'none';
        });
        
        const eventsGrid = document.getElementById('eventsGrid');
        const existingMessage = eventsGrid.querySelector('.filter-empty-state');
        
        if (existingMessage) {
          existingMessage.remove();
        }
        
        if (visibleCards.length === 0) {
          let message = '';
          if (filter === 'registered') {
            message = '<div class="filter-empty-state" style="text-align:center;padding:60px 20px;grid-column:1/-1;"><i class="bx bx-calendar-x" style="font-size:80px;color:#cbd5e1;"></i><h3 style="color:#64748b;margin-top:20px;">No Registered Events</h3><p style="color:#94a3b8;">You haven\'t registered for any events yet. Browse upcoming events and join now!</p></div>';
          } else if (filter === 'upcoming') {
            message = '<div class="filter-empty-state" style="text-align:center;padding:60px 20px;grid-column:1/-1;"><i class="bx bx-calendar" style="font-size:80px;color:#cbd5e1;"></i><h3 style="color:#64748b;margin-top:20px;">No Upcoming Events</h3><p style="color:#94a3b8;">There are no upcoming events at the moment. Check back later!</p></div>';
          }
          if (message) {
            eventsGrid.insertAdjacentHTML('beforeend', message);
          }
        }
      }, 50);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const registerButtons = document.querySelectorAll('.register-btn');
        const eventIdInput = document.getElementById('eventIdInput');
        const eventNameInput = document.getElementById('eventNameInput');
        const eventNameDisplay = document.getElementById('eventNameDisplay');
        const registerModalEl = document.getElementById('registerModal');

        if (!registerButtons.length || !registerModalEl) return;

        const registerModal = new bootstrap.Modal(registerModalEl);

        registerButtons.forEach(button => {
            button.addEventListener('click', () => {
                const eventId = button.dataset.eventId || '';
                const eventName = button.dataset.eventName || '';

                if (eventIdInput) eventIdInput.value = eventId;
                if (eventNameInput) eventNameInput.value = eventName;
                if (eventNameDisplay) eventNameDisplay.value = eventName;

                registerModal.show();
            });
        });
    });

    function viewEventDetails(eventName) {
        alert(`Viewing details for: ${eventName}`);
    }

    function togglePassword(inputId, icon) {
      const input = document.getElementById(inputId);
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bx-show', 'bx-hide');
      } else {
        input.type = 'password';
        icon.classList.replace('bx-hide', 'bx-show');
      }
    }

    document.getElementById('loadMoreBtn').addEventListener('click', function() {
      const btn = this;
      const originalContent = btn.innerHTML;
      btn.innerHTML = '<span class="loading-spinner"></span> Loading...';
      btn.disabled = true;
      setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        alert('More events would be loaded here via AJAX call.');
      }, 2000);
    });

    function showSuccessAlert(message) {
      document.getElementById('alertMessage').textContent = message;
      successAlert.style.display = 'block';
      setTimeout(() => {
        successAlert.style.display = 'none';
      }, 5000);
    }

    function closeAlert() {
      successAlert.style.display = 'none';
    }

    document.getElementById('notificationBell').addEventListener('click', function () {
      let dropdown = document.getElementById('notificationDropdown');
      dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });

    function loadNotifications() {
      fetch('/user/notifications/fetch')
        .then(response => response.json())
        .then(data => {
          let list = document.getElementById('notificationList');
          let count = document.getElementById('notificationCount');
          list.innerHTML = '';

          if (!data || data.length === 0) {
            list.innerHTML = '<li>No new notifications</li>';
            count.style.display = 'none';
          } else {
            data.forEach(n => {
              let li = document.createElement('li');
              li.textContent = n.message;
              list.appendChild(li);
            });
            let unreadCount = data.filter(n => n.is_read == 0).length;
            count.textContent = unreadCount;
            count.style.display = unreadCount > 0 ? 'inline-block' : 'none';
          }
        })
        .catch(err => console.error('Notification fetch failed:', err));
    }

    setInterval(loadNotifications, 30000);
    loadNotifications();

    window.addEventListener('resize', function() {
      if (!isMobile() && sidebar.classList.contains('mobile-open')) {
        closeMobileSidebar();
      }
    });

    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function() {
        const icon = this.querySelector('i');
        if (icon) {
          icon.style.transform = 'rotate(360deg)';
          icon.style.transition = 'transform 0.3s ease';
          setTimeout(() => icon.style.transform = 'rotate(0deg)', 300);
        }
      });
    });

    function initializeScrollAnimations() {
      const observerOptions = { threshold: 0.1, rootMargin: '0px 0px -50px 0px' };
      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      document.querySelectorAll('.event-card, .stat-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
      });
    }

    let logoutTimer, warningTimer;
    const LOGOUT_TIME = 10 * 60 * 1000;
    const WARNING_TIME = 9 * 60 * 1000;

    function redirectToLogin() {
      window.location.href = "<?= base_url('/login') ?>";
    }

    function showWarningModal() {
      const modal = document.createElement('div');
      modal.innerHTML = `
        <div id="logoutWarningModal" style="position:fixed;top:0;left:0;width:100%;height:100%;
          background:rgba(0,0,0,0.5);display:flex;justify-content:center;align-items:center;z-index:9999;">
          <div style="background:white;padding:20px;border-radius:10px;text-align:center;max-width:300px;">
            <h3>Session Expiring</h3>
            <p>You will be logged out in 1 minute due to inactivity.</p>
            <button id="stayLoggedInBtn" style="background:#2563eb;color:white;padding:8px 16px;
              border:none;border-radius:6px;cursor:pointer;">Stay Logged In</button>
          </div>
        </div>`;
      document.body.appendChild(modal);

      document.getElementById('stayLoggedInBtn').addEventListener('click', function() {
        resetTimer();
        document.getElementById('logoutWarningModal').remove();
      });
    }

    function resetTimer() {
      clearTimeout(logoutTimer);
      clearTimeout(warningTimer);
      warningTimer = setTimeout(showWarningModal, WARNING_TIME);
      logoutTimer = setTimeout(redirectToLogin, LOGOUT_TIME);
    }

    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.onscroll = resetTimer;
    document.onclick = resetTimer;

    window.addEventListener('load', function() {
      initializeScrollAnimations();
      setTimeout(() => {
        showSuccessAlert('Welcome back! Your dashboard has been updated with the latest events.');
      }, 1000);
    });

    const style = document.createElement('style');
    style.textContent = `
      .mobile-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 999;
      }
      @media (max-width: 768px) {
        .mobile-overlay.show { display: block; }
      }
      .quick-action-btn:hover { transform: translateY(-3px) scale(1.02); }
      .event-card:hover .event-image { transform: scale(1.05); }
      .event-image { transition: transform 0.3s ease; }
      .notification-badge { animation: pulse 2s infinite; }
      @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
      }
    `;
    document.head.appendChild(style);
</script>

</body>
</html>