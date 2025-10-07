<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - CHRE Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4e73df;
      --primary-hover: #3756c0;
      --secondary-color: #858796;
      --success-color: #1cc88a;
      --info-color: #36b9cc;
      --warning-color: #f6c23e;
      --danger-color: #e74a3b;
      --purple-color: #6f42c1;
      --background-color: #f8f9fc;
      --sidebar-bg: #ffffff;
      --text-color: #5a5c69;
      --border-color: #e3e6f0;
      --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      --sidebar-width: 280px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--background-color);
      color: var(--text-color);
      margin: 0;
      overflow-x: hidden;
    }

    /* Loading Screen */
    .loading-screen {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.5s ease;
    }

    .loading-spinner {
      width: 50px;
      height: 50px;
      border: 4px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Layout */
    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* Enhanced Sidebar */
    .sidebar {
      width: var(--sidebar-width);
      background: linear-gradient(180deg, #ffffff 0%, #f8f9fc 100%);
      border-right: 1px solid var(--border-color);
      display: flex;
      flex-direction: column;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      z-index: 1000;
      box-shadow: var(--shadow);
    }

    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar.collapsed .nav-text,
    .sidebar.collapsed .user-name,
    .sidebar.collapsed .badge {
      display: none;
    }

    /* Logo Section */
    .logo-section {
      padding: 1.5rem 1rem;
      text-align: center;
      border-bottom: 1px solid var(--border-color);
      background: white;
    }

    .logo-section img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .logo-section img:hover {
      transform: scale(1.1);
    }

    .system-title {
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--primary-color);
      margin-top: 0.5rem;
      transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .system-title {
      display: none;
    }

    /* User Profile Section */
    .user-profile {
      padding: 1rem;
      background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
      color: white;
      text-align: center;
    }

    .user-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 0.5rem;
      font-size: 1.5rem;
    }

    .user-name {
      font-weight: 600;
      font-size: 0.95rem;
      margin: 0;
    }

    .user-role {
      font-size: 0.8rem;
      opacity: 0.8;
      margin: 0;
    }

    /* Navigation */
    .nav-section {
      flex: 1;
      padding: 1rem 0;
    }

    .nav-header {
      font-size: 0.75rem;
      font-weight: 700;
      text-transform: uppercase;
      color: var(--secondary-color);
      padding: 0.5rem 1.5rem;
      margin-bottom: 0.5rem;
      letter-spacing: 0.5px;
    }

    .nav-item {
      margin: 0.2rem 0.5rem;
    }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 0.8rem 1rem;
      color: var(--text-color);
      text-decoration: none;
      border-radius: 0.5rem;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
      transition: left 0.3s ease;
      z-index: -1;
    }

    .nav-link:hover::before,
    .nav-link.active::before {
      left: 0;
    }

    .nav-link:hover,
    .nav-link.active {
      color: white;
      transform: translateX(5px);
    }

    .nav-link i {
      font-size: 1.2rem;
      margin-right: 0.8rem;
      width: 20px;
      text-align: center;
    }

    .nav-text {
      font-weight: 500;
    }

    .nav-badge {
      margin-left: auto;
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
      border-radius: 10px;
    }
.notification-dropdown {
  position: absolute;
  right: 20px;
  top: 60px;
  width: 300px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 5px 20px rgba(0,0,0,0.1);
  display: none;
  z-index: 1000;
}

.notification-dropdown.hidden {
  display: none;
}

.notification-dropdown:not(.hidden) {
  display: block;
}

.notification-item {
  padding: 10px;
  border-bottom: 1px solid #eee;
}

.notification-item:last-child {
  border-bottom: none;
}

.notification-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  font-weight: bold;
  border-bottom: 1px solid #ddd;
}

    /* Sidebar Toggle */
    .sidebar-toggle {
      position: fixed;
      top: 20px;
      left: 20px;
      background: white;
      border: none;
      border-radius: 8px;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 1100;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
    }

    .sidebar-toggle:hover {
      transform: scale(1.1);
      background: var(--primary-color);
      color: white;
    }

    /* Main Content */
    .main-content {
      flex: 1;
      margin-left: var(--sidebar-width);
      transition: margin-left 0.3s ease;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .main-content.expanded {
      margin-left: 70px;
    }

    /* Top Navigation */
    .top-navbar {
      background: white;
      border-bottom: 1px solid var(--border-color);
      padding: 1rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .breadcrumb-nav {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.9rem;
    }

    .breadcrumb-nav span {
      color: var(--secondary-color);
    }

    .breadcrumb-nav .current {
      color: var(--primary-color);
      font-weight: 600;
    }

    .navbar-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    /* Search Bar */
    .search-box {
      position: relative;
    }

    .search-input {
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 0.5rem 1rem 0.5rem 2.5rem;
      width: 250px;
      transition: all 0.3s ease;
    }

    .search-input:focus {
      width: 350px;
      box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
      border-color: var(--primary-color);
    }

    .search-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--secondary-color);
    }

    /* Notification Bell */
    .notification-bell {
      position: relative;
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--text-color);
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .notification-bell:hover {
      color: var(--primary-color);
    }

    .notification-count {
      position: absolute;
      top: -5px;
      right: -5px;
      background: var(--danger-color);
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      font-size: 0.7rem;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }

    /* User Dropdown */
    .user-dropdown {
      position: relative;
    }

    .user-dropdown-toggle {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      background: var(--primary-color);
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .user-dropdown-toggle:hover {
      background: var(--primary-hover);
      transform: translateY(-2px);
    }

    /* Dashboard Content */
    .dashboard-content {
      flex: 1;
      padding: 2rem;
    }

    .page-header {
      display: flex;
      justify-content: between;
      align-items: center;
      margin-bottom: 2rem;
    }

    .page-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-color);
      margin: 0;
    }

    .page-subtitle {
      color: var(--secondary-color);
      font-size: 0.9rem;
      margin: 0.5rem 0 0 0;
    }

    /* Enhanced Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .stat-card {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      border: none;
      cursor: pointer;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--purple-color));
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .stat-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1rem;
    }

    .stat-card-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: white;
    }

    .stat-card-value {
      font-size: 2.5rem;
      font-weight: 700;
      margin: 0;
      color: var(--text-color);
    }

    .stat-card-label {
      color: var(--secondary-color);
      font-size: 0.9rem;
      margin: 0;
    }

    .stat-card-change {
      font-size: 0.8rem;
      padding: 0.25rem 0.5rem;
      border-radius: 15px;
      font-weight: 600;
    }

    .stat-card-change.positive {
      background: rgba(28, 200, 138, 0.1);
      color: var(--success-color);
    }

    .stat-card-change.negative {
      background: rgba(231, 74, 59, 0.1);
      color: var(--danger-color);
    }

    /* Chart Section */
    .chart-section {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .chart-card {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: var(--shadow);
    }

    .chart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border-color);
    }

    .chart-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin: 0;
      color: var(--text-color);
    }

    .chart-actions {
      display: flex;
      gap: 0.5rem;
    }

    .chart-btn {
      padding: 0.4rem 0.8rem;
      border: 1px solid var(--border-color);
      background: white;
      border-radius: 6px;
      font-size: 0.8rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .chart-btn:hover {
      background: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
    }

    /* Activity Feed */
    .activity-feed {
      max-height: 400px;
      overflow-y: auto;
    }

    .activity-item {
      display: flex;
      align-items: flex-start;
      gap: 1rem;
      padding: 1rem 0;
      border-bottom: 1px solid var(--border-color);
    }

    .activity-item:last-child {
      border-bottom: none;
    }

    .activity-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      color: white;
      flex-shrink: 0;
    }

    .activity-content {
      flex: 1;
    }

    .activity-title {
      font-weight: 600;
      margin: 0 0 0.25rem 0;
      font-size: 0.9rem;
    }

    .activity-description {
      color: var(--secondary-color);
      font-size: 0.8rem;
      margin: 0;
    }

    .activity-time {
      color: var(--secondary-color);
      font-size: 0.75rem;
      white-space: nowrap;
    }

    /* Quick Actions */
    .quick-actions {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .quick-action {
      background: white;
      border-radius: 10px;
      padding: 1rem;
      text-align: center;
      text-decoration: none;
      color: var(--text-color);
      box-shadow: var(--shadow);
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
    }

    .quick-action:hover {
      transform: translateY(-3px);
      color: var(--primary-color);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .quick-action i {
      font-size: 2rem;
      margin-bottom: 0.5rem;
      display: block;
    }

    /* Footer */
    .dashboard-footer {
      background: white;
      border-top: 1px solid var(--border-color);
      padding: 1.5rem 2rem;
      margin-top: auto;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: var(--secondary-color);
      font-size: 0.9rem;
    }

    .footer-links {
      display: flex;
      gap: 2rem;
    }

    .footer-links a {
      color: var(--secondary-color);
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: var(--primary-color);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        transform: translateX(-100%);
      }

      .sidebar.show {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .top-navbar {
        padding: 1rem;
      }

      .search-input {
        width: 200px;
      }

      .search-input:focus {
        width: 250px;
      }

      .chart-section {
        grid-template-columns: 1fr;
      }

      .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      }
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
      width: 8px;
    }

    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
      background: var(--primary-color);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: var(--primary-hover);
    }

    /* Animations */
    .animate-fade-in {
      animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <!-- Loading Screen -->
  <div class="loading-screen" id="loadingScreen">
    <div class="loading-spinner"></div>
  </div>

  <!-- Sidebar Toggle -->
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class='bx bx-menu'></i>
  </button>

  <div class="layout">
    <!-- Enhanced Sidebar -->
    <div class="sidebar" id="sidebar">
      <!-- Logo Section -->
      <div class="logo-section">
        <img src="/images/logochre.jpg" alt="CHRE Logo">
        <div class="system-title">CHRE Management System</div>
      </div>

      <!-- User Profile -->

      <!-- Navigation -->
      <div class="nav-section">
        <div class="nav-header">Main Menu</div>
        <nav>
          <div class="nav-item">
            <a class="nav-link active" href="<?= base_url('admin/dashboard') ?>">
              <i class='bx bx-grid-alt'></i>
              <span class="nav-text">Dashboard</span>
            </a>
          </div>
          <div class="nav-item">
            <a class="nav-link" href= "<?= base_url('admin/complaints') ?>">
              <i class='bx bx-message-square-error'></i>
              <span class="nav-text">Complaints</span>
              <span class="nav-badge bg-danger"><?= esc($total) ?></span>
            </a>
          </div>
          <div class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/events') ?>">
              <i class='bx bx-calendar-event'></i>
              <span class="nav-text">Events</span>
              <span class="nav-badge bg-info"><?= esc($totalEvents) ?></span>
            </a>
          </div>
        </nav>

        <div class="nav-header">User Management</div>
        <nav>
          <div class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/students') ?>">
              <i class='bx bx-user-voice'></i>
              <span class="nav-text">Students</span>
              <span class="nav-badge bg-success"><?= esc($totalStudents) ?></span>
            </a>
          </div>
          <div class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/chre_staff') ?>">
              <i class='bx bx-id-card'></i>
              <span class="nav-text">CHRE Staff</span>
              <span class="nav-badge bg-primary"><?= esc($totalChreStaff) ?></span>
            </a>
          </div>
          <div class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/users') ?>">
              <i class='bx bx-group'></i>
              <span class="nav-text">All Users</span>
              <span class="nav-badge bg-warning"><?= esc($totalUsers) ?></span>
            </a>
          </div>
        </nav>

        <div class="nav-header">System</div>
        <nav>
          <div class="nav-item">
            <a class="nav-link" href="#">
              <i class='bx bx-cog'></i>
              <span class="nav-text">Settings</span>
            </a>
          </div>
          <div class="nav-item">
            <a class="nav-link" href="#">
              <i class='bx bx-pie-chart-alt-2'></i>
              <span class="nav-text">Analytics</span>
            </a>
          </div>
          <div class="nav-item">
            <a class="nav-link" href="#">
              <i class='bx bx-shield-check'></i>
              <span class="nav-text">Security</span>
            </a>
          </div>
        </nav>
      </div>

      <!-- Logout Button -->
      <div style="padding: 1rem;">
        <button class="btn btn-danger w-100" onclick="confirmLogout()">
          <i class='bx bx-log-out me-2'></i>
          <span class="nav-text">Logout</span>
        </button>
      </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
      <!-- Top Navigation -->
      <div class="top-navbar">
        <div class="breadcrumb-nav">
          <i class='bx bx-home'></i>
          <span>/</span>
          <span class="current">Dashboard</span>
        </div>

        <div class="navbar-actions">
          <!-- Search Bar -->
          <div class="search-box">
            <i class='bx bx-search search-icon'></i>
            <input type="text" class="search-input" placeholder="Search users, complaints, events...">
          </div>

          <!-- Notifications -->
<button class="notification-bell" id="notificationBell">
  <i class='bx bx-bell'></i>
  <span class="notification-count" id="notificationCount">0</span>
</button>

<!-- ✅ Hidden Dropdown for Notifications -->
<div class="notification-dropdown hidden" id="notificationDropdown">
  <div class="notification-header">
    <h4>Notifications</h4>
    <button id="clearNotifications" class="clear-btn">Clear</button>
  </div>
  <div class="notification-list" id="notificationList">
    <p class="empty-text">No new notifications</p>
  </div>
</div>


          <!-- User Dropdown -->
          <div class="dropdown user-dropdown">
            <button class="user-dropdown-toggle" data-bs-toggle="dropdown">
              <i class='bx bx-user'></i>
              <span><?= esc(session()->get('full_name')) ?></span>
              <i class='bx bx-chevron-down'></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow">
              <li>
                <div class="dropdown-header">
                  <div class="fw-bold"><?= esc(session()->get('full_name')) ?></div>
                  <small class="text-muted"><?= esc(session()->get('email')) ?></small>
                </div>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#"><i class="bx bx-user me-2"></i>Profile</a></li>
              <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i>Settings</a></li>
              <li><a class="dropdown-item" href="#"><i class="bx bx-help-circle me-2"></i>Help</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#" onclick="confirmLogout()"><i class="bx bx-log-out me-2"></i>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Dashboard Content -->
      <div class="dashboard-content animate-fade-in">
        <!-- Page Header -->
        <div class="page-header">
          <div>
            <h1 class="page-title">Dashboard Overview</h1>
            <p class="page-subtitle">Welcome back! Here's what's happening in your system today.</p>
          </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
          <div class="stat-card" data-bs-toggle="tooltip" title="Click to view all users">
            <div class="stat-card-header">
              <div class="stat-card-icon bg-primary">
                <i class='bx bx-group'></i>
              </div>
              <div class="stat-card-change positive">
                <i class='bx bx-trending-up'></i> +12%
              </div>
            </div>
            <h3 class="stat-card-value"> <?= esc($totalUsers ?? 0) ?> </h3>
            <p class="stat-card-label">Total Users</p>
            
          </div>

          <div class="stat-card" data-bs-toggle="tooltip" title="Active approved users">
            <div class="stat-card-header">
              <div class="stat-card-icon bg-success">
                <i class='bx bx-check-shield'></i>
              </div>
              <div class="stat-card-change positive">
                <i class='bx bx-trending-up'></i> +8%
              </div>
            </div>
            <h3 class="stat-card-value"><?= esc($verifiedUsers ) ?></h3>
            <p class="stat-card-label">Approved Users</p>
          </div>

          <div class="stat-card" data-bs-toggle="tooltip" title="Pending complaints requiring attention">
            <div class="stat-card-header">
              <div class="stat-card-icon bg-warning">
                <i class='bx bx-message-square-error'></i>
              </div>
              <div class="stat-card-change negative">
                <i class='bx bx-trending-down'></i> -5%
              </div>
            </div>
            <h3 class="stat-card-value"><?= esc($pending) ?></h3>
            <p class="stat-card-label">Pending Complaints</p>
          </div>

<div class="stat-card" data-bs-toggle="tooltip" title="Upcoming events this month">
  <div class="stat-card-header">
    <div class="stat-card-icon bg-info">
      <i class='bx bx-calendar-event'></i>
    </div>
    <div class="stat-card-change positive">
      <i class='bx bx-trending-up'></i> +15%
    </div>
  </div>
  <h3 class="stat-card-value"><?= $upcomingEvents ?></h3>
  <p class="stat-card-label">Upcoming Events</p>
</div>

        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
          <button class="quick-action" onclick="openModal('addUser')">
            <i class='bx bx-user-plus'></i>
            <div>Add New User</div>
          </button>
          <button class="quick-action" onclick="openModal('createEvent')">
            <i class='bx bx-calendar-plus'></i>
            <div>Create Event</div>
          </button>
          <button class="quick-action" onclick="openModal('viewReports')">
            <i class='bx bx-file-blank'></i>
            <div>Generate Report</div>
          </button>
          <button class="quick-action" onclick="openModal('systemSettings')">
            <i class='bx bx-cog'></i>
            <div>System Settings</div>
          </button>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
          <!-- Main Chart -->
          <div class="chart-card">
            <div class="chart-header">
              <h5 class="chart-title">User Registration Trends</h5>
              <div class="chart-actions">
                <button class="chart-btn">7 Days</button>
                <button class="chart-btn">30 Days</button>
                <button class="chart-btn active">90 Days</button>
                <button class="chart-btn">Export</button>
              </div>
            </div>
            <div class="chart-placeholder" style="height: 300px; background: linear-gradient(45deg, #f8f9fc 25%, transparent 25%, transparent 75%, #f8f9fc 75%), linear-gradient(45deg, #f8f9fc 25%, transparent 25%, transparent 75%, #f8f9fc 75%); background-size: 20px 20px; background-position: 0 0, 10px 10px; display: flex; align-items: center; justify-content: center; color: #858796; border-radius: 8px;">
              <div style="text-align: center;">
                <i class='bx bx-line-chart' style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <p>Interactive Chart Would Display Here</p>
                <small>Real-time user registration data visualization</small>
              </div>
            </div>
          </div>

          <!-- Activity Feed -->
          <div class="activity-feed" id="activityFeed">
  <div class="loading">Loading recent activity...</div>
</div>


        <!-- Additional Stats Row -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header bg-transparent">
                <h6 class="card-title mb-0">System Health</h6>
              </div>
              <div class="card-body">
                <div class="mb-3">
                  <div class="d-flex justify-content-between mb-1">
                    <small>Server Performance</small>
                    <small>92%</small>
                  </div>
                  <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 92%"></div>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="d-flex justify-content-between mb-1">
                    <small>Database Usage</small>
                    <small>78%</small>
                  </div>
                  <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-info" style="width: 78%"></div>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="d-flex justify-content-between mb-1">
                    <small>Storage Space</small>
                    <small>65%</small>
                  </div>
                  <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-warning" style="width: 65%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header bg-transparent">
                <h6 class="card-title mb-0">User Demographics</h6>
              </div>
              <div class="card-body">
                <div class="row text-center">
                  <div class="col-4">
                    <div class="text-primary">
                      <i class='bx bx-user-voice' style="font-size: 2rem;"></i>
                      <div class="fw-bold">1,156</div>
                      <small>Users</small>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="text-success">
                      <i class='bx bx-id-card' style="font-size: 2rem;"></i>
                      <div class="fw-bold">89</div>
                      <small>Staff</small>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="text-warning">
                      <i class='bx bx-user-check' style="font-size: 2rem;"></i>
                      <div class="fw-bold">602</div>
                      <small>Alumni</small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="dashboard-footer">
        <div class="footer-content">
          <div>
            <strong>CHRE Management System</strong> © 2025
          </div>
          <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Support</a>
            <a href="#">Documentation</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('admin/users/add') ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
              <option value="" disabled selected>Select role</option>
              <option value="admin">Admin</option>
              <option value="staff">Staff</option>
              <option value="student">Student</option>
              <option value="student">Employee</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add User</button>
        </div>
      </div>
    </form>
  </div>
</div>

  <!-- Create Event Modal -->
  <div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= base_url('staff/events/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title" id="addEventModalLabel">Create Human Rights Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="event_name" class="form-label">Event Name</label>
            <input type="text" name="event_name" id="event_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="start_time" class="form-label">Start Time</label>
            <input type="time" name="start_time" id="start_time" class="form-control" required>
            <small id="startTimeDisplay" class="text-muted"></small>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="multiDayToggle">
            <label class="form-check-label" for="multiDayToggle">Multi-day event</label>
          </div>
          <div id="endDateTimeFields" style="display: none;">
            <div class="mb-3">
              <label for="end_date" class="form-label">End Date</label>
              <input type="date" name="end_date" id="end_date" class="form-control">
            </div>
            <div class="mb-3">
              <label for="end_time" class="form-label">End Time</label>
              <input type="time" name="end_time" id="end_time" class="form-control">
              <small id="endTimeDisplay" class="text-muted"></small>
            </div>
          </div>
          <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="audience" class="form-label">Audience</label>
            <select name="audience" id="audience" class="form-control" required>
              <option value="students">Students</option>
              <option value="employees">Employees</option>
              <option value="all">All</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="attachment" class="form-label">Upload File/Image</label>
            <input type="file" name="attachment" id="attachment" class="form-control" accept="image/*,video/*,.pdf,.doc,.docx">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>
        </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Loading screen
    window.addEventListener('load', function() {
      setTimeout(function() {
        document.getElementById('loadingScreen').style.opacity = '0';
        setTimeout(function() {
          document.getElementById('loadingScreen').style.display = 'none';
        }, 500);
      }, 1000);
    });

    // Sidebar toggle functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');

    sidebarToggle.addEventListener('click', function() {
      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('expanded');
      
      // Update toggle icon
      const icon = sidebarToggle.querySelector('i');
      icon.classList.toggle('bx-menu');
      icon.classList.toggle('bx-x');
    });

    // Search functionality
    document.querySelector('.search-input').addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      console.log('Searching for:', searchTerm);
      // Implement search logic here
    });

    // Notification bell + dropdown
    async function loadNotifications() {
      try {
        const response = await fetch("<?= base_url('admin/notifications') ?>");
        const notifications = await response.json();

        // Update bell count
        const countElem = document.getElementById('notificationCount');
        countElem.innerText = notifications.length;

        // Update notification dropdown list
        const listElem = document.getElementById('notificationList');
        listElem.innerHTML = '';
        if (notifications.length === 0) {
          listElem.innerHTML = '<li class="empty">No new notifications</li>';
        } else {
          notifications.forEach(n => {
            const li = document.createElement('li');
            li.classList.add('notif-item');
            li.innerHTML = `
              <span class="notif-message">${n.title}</span>
              <span class="notif-time">${n.time}</span>
            `;
            listElem.appendChild(li);
          });
        }

        // Update Recent Activity feed
        const activityFeed = document.querySelector('.activity-feed');
        activityFeed.innerHTML = ''; // Clear old static data

        notifications.forEach(n => {
          let iconClass = 'bx bx-bell';
          let bgClass = 'bg-primary';

          if (n.type === 'user') {
            iconClass = 'bx bx-user-plus';
            bgClass = 'bg-success';
          } else if (n.type === 'complaint') {
            iconClass = 'bx bx-message-square-error';
            bgClass = 'bg-warning';
          }

          activityFeed.innerHTML += `
            <div class="activity-item">
              <div class="activity-icon ${bgClass}">
                <i class='${iconClass}'></i>
              </div>
              <div class="activity-content">
                <div class="activity-title">${n.title}</div>
                <div class="activity-description">${n.description}</div>
              </div>
              <div class="activity-time">${n.time}</div>
            </div>
          `;
        });

      } catch (error) {
        console.error('Error loading notifications:', error);
      }
    }

    // ✅ Show/Hide notification dropdown when clicking bell
    const notificationBell = document.getElementById('notificationBell');
    const notificationDropdown = document.getElementById('notificationDropdown');

    notificationBell.addEventListener('click', (event) => {
      event.stopPropagation();
      notificationDropdown.classList.toggle('hidden');
      document.getElementById('notificationCount').innerText = "0";
    });

    // ✅ Hide dropdown when clicking outside
    document.addEventListener('click', (event) => {
      if (!notificationDropdown.contains(event.target) && !notificationBell.contains(event.target)) {
        notificationDropdown.classList.add('hidden');
      }
    });

    // Refresh every 10 seconds
    setInterval(loadNotifications, 10000);
    loadNotifications();

    // Modal functions
    function openModal(modalType) {
      switch(modalType) {
        case 'addUser':
          new bootstrap.Modal(document.getElementById('addUserModal')).show();
          break;
        case 'createEvent':
          new bootstrap.Modal(document.getElementById('createEventModal')).show();
          break;
        case 'viewReports':
          alert('Generate Report functionality would open here');
          break;
        case 'systemSettings':
          alert('System Settings panel would open here');
          break;
      }
    }

    // Logout confirmation
    function confirmLogout() {
      if (confirm('Are you sure you want to log out?')) {
        // Show loading and redirect
        document.getElementById('loadingScreen').style.display = 'flex';
        setTimeout(function() {
          window.location.href = '/logout';
        }, 1000);
      }
    }

    // Auto-logout functionality
    let idleTime = 0;
    let warningShown = false;
    const idleLimit = 30 * 60; // 30 minutes
    const warningTime = 25 * 60; // 25 minutes

    function resetIdle() {
      idleTime = 0;
      warningShown = false;
    }

    // Idle timer
    setInterval(function() {
      idleTime++;

      if (idleTime >= warningTime && !warningShown) {
        warningShown = true;
        if (confirm('⚠️ You have been inactive for 25 minutes. You will be logged out in 5 minutes unless you click OK to stay logged in.')) {
          resetIdle();
        }
      }

      if (idleTime >= idleLimit) {
        alert('Session expired due to inactivity. You will be logged out.');
        confirmLogout();
      }
    }, 1000);

    // Reset idle timer on user activity
    ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'].forEach(function(event) {
      document.addEventListener(event, resetIdle, true);
    });

    // Stat card click handlers
    document.querySelectorAll('.stat-card').forEach(card => {
      card.addEventListener('click', function() {
        const label = this.querySelector('.stat-card-label').textContent;
        alert(`Navigating to ${label} management page...`);
      });
    });

    // Chart period buttons
    document.querySelectorAll('.chart-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        // Remove active class from siblings
        this.parentElement.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
        // Add active class to clicked button
        this.classList.add('active');
        
        if (this.textContent === 'Export') {
          alert('Chart data would be exported as CSV/PDF');
        } else {
          console.log('Loading chart data for period:', this.textContent);
        }
      });
    });

    // Mobile responsive sidebar
    window.addEventListener('resize', function() {
      if (window.innerWidth <= 768) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('expanded');
      } else {
        sidebar.classList.remove('show');
      }
    });

    // Add mobile sidebar show functionality
    if (window.innerWidth <= 768) {
      sidebarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        sidebar.classList.toggle('show');
      });

      // Close sidebar when clicking outside on mobile
      document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768 && 
            !sidebar.contains(e.target) && 
            !sidebarToggle.contains(e.target)) {
          sidebar.classList.remove('show');
        }
      });
    }

    // Real-time clock
    function updateClock() {
      const now = new Date();
      const timeString = now.toLocaleTimeString();
      const dateString = now.toLocaleDateString();
      
      // Update page subtitle with current time
      const subtitle = document.querySelector('.page-subtitle');
      if (subtitle) {
        subtitle.textContent = `Welcome back! Here's what's happening in your system today. ${dateString} ${timeString}`;
      }
    }

    setInterval(updateClock, 1000);
    updateClock(); // Initial call

    // Simulate real-time updates
    setInterval(function() {
      // Randomly update notification count
      const notificationCount = document.querySelector('.notification-count');
      const currentCount = parseInt(notificationCount.textContent);
      const newCount = Math.max(0, currentCount + Math.floor(Math.random() * 3) - 1);
      notificationCount.textContent = newCount;
      
      if (newCount > 0) {
        notificationCount.style.display = 'flex';
      } else {
        notificationCount.style.display = 'none';
      }
    }, 30000); // Update every 30 seconds

    console.log('🎉 Enhanced Admin Dashboard Loaded Successfully!');
    console.log('📊 Features: Interactive sidebar, real-time updates, responsive design');
    console.log('🔒 Security: Auto-logout, session management, activity tracking');
</script>

</body>
</html>