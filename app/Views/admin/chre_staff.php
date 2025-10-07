<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHRE Staff Management</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
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
            opacity: 0;
            visibility: hidden;
        }

        .loading-screen.show {
            opacity: 1;
            visibility: visible;
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

        /* Sidebar */
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
        .sidebar.collapsed .system-title,
        .sidebar.collapsed .nav-badge {
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
            background: var(--danger-color);
            color: white;
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
            outline: none;
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

        /* Staff Container */
        .staff-container {
            flex: 1;
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: var(--secondary-color);
            font-size: 0.9rem;
            margin: 0;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow);
            border-left: 4px solid;
            position: relative;
            transition: transform 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card.blue { border-left-color: #3b82f6; }
        .stat-card.green { border-left-color: #10b981; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card.orange { border-left-color: #f59e0b; }
        .stat-card.indigo { border-left-color: #6366f1; }

        .stat-icon {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-icon.blue { background-color: #eff6ff; color: #3b82f6; }
        .stat-icon.green { background-color: #f0fdf4; color: #10b981; }
        .stat-icon.purple { background-color: #faf5ff; color: #8b5cf6; }
        .stat-icon.orange { background-color: #fffbeb; color: #f59e0b; }
        .stat-icon.indigo { background-color: #eef2ff; color: #6366f1; }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--text-color);
            margin-bottom: 5px;
        }

        .stat-label {
            color: var(--secondary-color);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
        }

        .stat-change.positive { color: #10b981; }
        .stat-change.negative { color: #ef4444; }

        /* Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .action-bar .search-box {
            flex: 1;
            max-width: 300px;
        }

        .action-bar .search-box input {
            width: 100%;
            padding: 12px 40px 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            font-size: 14px;
            outline: none;
        }

        .action-bar .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-outline {
            background: white;
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background: #f8fafc;
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .w-100 {
            width: 100%;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tr:hover {
            background: #f8fafc;
        }

        .staff-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .staff-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .staff-info {
            flex: 1;
        }

        .staff-name {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .staff-id {
            color: var(--secondary-color);
            font-size: 12px;
        }

        .position-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .position-subtitle {
            color: var(--secondary-color);
            font-size: 12px;
        }

        .department-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .dept-administration { background: #e0e7ff; color: #3730a3; }
        .dept-counseling { background: #d1fae5; color: #065f46; }
        .dept-residence { background: #fef3c7; color: #92400e; }
        .dept-student-affairs { background: #fce7f3; color: #be185d; }
        .dept-security { background: #fee2e2; color: #991b1b; }
        .dept-maintenance { background: #f0fdf4; color: #166534; }

        .contact-info {
            color: var(--secondary-color);
            font-size: 13px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .experience-years {
            font-weight: 600;
            color: var(--text-color);
            font-size: 14px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active { background: #d1fae5; color: #065f46; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        .status-leave { background: #fef3c7; color: #92400e; }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .action-btn.view { background: #eff6ff; color: #3b82f6; }
        .action-btn.edit { background: #f0fdf4; color: #10b981; }
        .action-btn.delete { background: #fef2f2; color: #ef4444; }

        .action-btn:hover {
            transform: scale(1.1);
        }

        /* Alert Messages */
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 15px 20px;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            box-shadow: var(--shadow);
            opacity: 1;
            transition: opacity 0.3s ease;
            max-width: 400px;
        }

        .alert-success {
            background: #f0fdf4;
            color: #065f46;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid var(--danger-color);
        }

        /* Responsive */
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

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .action-bar {
                flex-direction: column;
                align-items: stretch;
            }
            
            .action-bar .search-box {
                max-width: none;
            }

            .filter-buttons {
                justify-content: center;
                flex-wrap: wrap;
            }

            th, td {
                padding: 12px 8px;
            }

            .staff-container {
                padding: 1rem;
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
      <div class="user-profile">
        <div class="user-avatar">
          <i class='bx bx-user'></i>
        </div>
        <div class="user-name"><?= esc(session()->get('full_name')) ?> </div>
        <div class="user-role"><?= esc(session()->get('role')) ?></div>
      </div>

            <!-- Navigation -->
      <div class="nav-section">
        <div class="nav-header">Main Menu</div>
        <nav>
          <div class="nav-item">
            <a class="nav-link " href="<?= base_url('admin/dashboard') ?>">
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
            <a class="nav-link active" href="<?= base_url('admin/chre_staff') ?>">
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
                    <span class="current">CHRE Staff</span>
                </div>

                <div class="navbar-actions">
                    <!-- Search Bar -->
                    <div class="search-box">
                        <i class='bx bx-search search-icon'></i>
                        <input type="text" class="search-input" placeholder="Search staff...">
                    </div>

                    <!-- Notifications -->
                    <button class="notification-bell" id="notificationBell">
                        <i class='bx bx-bell'></i>
                        <span class="notification-count">5</span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="user-dropdown">
                        <button class="user-dropdown-toggle">
                            <i class='bx bx-user'></i>
                           <span><?= esc(session()->get('full_name')) ?></span>
                            <i class='bx bx-chevron-down'></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Staff Container -->
            <div class="staff-container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">CHRE Staff Management</h1>
                    <p class="page-subtitle">Manage CHRE (Center for Health, Residence & Environment) staff members and personnel</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <i class='bx bx-group'></i>
                        </div>
                        <div class="stat-number">45</div>
                        <div class="stat-label">Active Staff</div>
                        <div class="stat-change positive">↑ +8%</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <i class='bx bx-clipboard'></i>
                        </div>
                        <div class="stat-number">6</div>
                        <div class="stat-label">Departments</div>
                        <div class="stat-change positive">↑ +12%</div>
                    </div>
                    <div class="stat-card purple">
                        <div class="stat-icon purple">
                            <i class='bx bx-plus-medical'></i>
                        </div>
                        <div class="stat-number">12</div>
                        <div class="stat-label">Health Staff</div>
                        <div class="stat-change positive">↑ +5%</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-icon orange">
                            <i class='bx bx-time'></i>
                        </div>
                        <div class="stat-number">3</div>
                        <div class="stat-label">On Leave</div>
                        <div class="stat-change negative">↓ -2%</div>
                    </div>
                    <div class="stat-card indigo">
                        <div class="stat-icon indigo">
                            <i class='bx bx-trending-up'></i>
                        </div>
                        <div class="stat-number">8.5</div>
                        <div class="stat-label">Avg. Experience (Years)</div>
                        <div class="stat-change positive">↑ +3%</div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="action-bar">
                    <div class="search-box">
                        <input type="text" placeholder="Search staff members..." id="staffSearch">
                        <i class='bx bx-search search-icon'></i>
                    </div>
                    <div class="filter-buttons">
                        <button class="btn btn-outline" onclick="filterByStatus('all')">
                            <i class='bx bx-list-ul'></i>All Status
                        </button>
                        <button class="btn btn-outline" onclick="filterByDepartment('all')">
                            <i class='bx bx-building'></i>Department
                        </button>
                        <button class="btn btn-outline" onclick="filterByExperience('all')">
                            <i class='bx bx-calendar'></i>Experience
                        </button>
                        <button class="btn btn-primary" onclick="exportData()">
                            <i class='bx bx-download'></i>Export Data
                        </button>
                    </div>
                </div>

                <!-- Staff Table -->
<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">CHRE Staff Directory</h3>
        <button class="btn btn-primary" onclick="addStaffMember()">
            <i class='bx bx-user-plus'></i>Add Staff Member
        </button>
    </div>

    <!-- ✅ Add Staff Modal -->
  <form id="addStaffForm" action="<?= base_url('admin/chre_staff/add') ?>" method="post">

        <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" required>
        </div>
        <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_number">
        </div>
        <div class="form-group">
            <label>Department</label>
            <select name="department">
                <option value="Administration">Administration</option>
                <option value="Counseling">Counseling</option>
                <option value="Residence">Residence</option>
                <option value="Student Affairs">Student Affairs</option>
                <option value="Security">Security</option>
                <option value="Maintenance">Maintenance</option>
            </select>
        </div>
        <div class="form-group">
            <label>Position</label>
            <input type="text" name="position">
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Active">Active</option>
                <option value="On Leave">On Leave</option>
            </select>
        </div>
        <button type="submit" class="btn-submit">Save Staff</button>
    </form>

    <table id="staffTable">
        <thead>
            <tr>
                <th>Staff Info</th>
                <th>Position</th>
                <th>Department</th>
                <th>Contact Information</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="staffTableBody">
            <?php if (!empty($staff)): ?>
                <?php foreach ($staff as $s): ?>
                    <?php
                        $initials = strtoupper(substr($s['first_name'], 0, 1) . substr($s['last_name'], 0, 1));
                        $fullName = $s['first_name'] . ' ' . $s['last_name'];
                    ?>
                    <tr>
                        <td>
                            <div class="staff-profile">
                                <div class="staff-avatar"><?= esc($initials) ?></div>
                                <div class="staff-info">
                                    <div class="staff-name"><?= esc($fullName) ?></div>
                                    <div class="staff-id">ID: CHRE-<?= esc($s['id']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td><div class="position-title"><?= esc($s['position']) ?></div></td>
                        <td><span class="department-badge"><?= esc($s['department']) ?></span></td>
                        <td>
                            <div class="contact-info">
                                <span><i class='bx bx-envelope'></i> <?= esc($s['email']) ?></span>
                                <span><i class='bx bx-phone'></i> <?= esc($s['contact_number']) ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge <?= $s['status'] === 'Active' ? 'status-active' : 'status-leave' ?>">
                                <?= esc($s['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn view" onclick="viewStaff('<?= $s['id'] ?>')"><i class='bx bx-show'></i></button>
                                <button class="action-btn edit" onclick="editStaff('<?= $s['id'] ?>')"><i class='bx bx-edit'></i></button>
                                <button class="action-btn delete" onclick="deleteStaff('<?= $s['id'] ?>')"><i class='bx bx-trash'></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">No staff members found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


            </div>
        </div>
    </div>

<script>
    // Inject PHP staff data into JS
    let staffMembers = <?= json_encode($staff) ?>;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            document.getElementById('loadingScreen').classList.remove('show');
        }, 1000);

        initializeEventListeners();
        updateStaffTable();
        updateStats();
    });

    function initializeEventListeners() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        });

        const searchInput = document.getElementById('staffSearch');
        searchInput.addEventListener('input', function() {
            filterStaff(this.value);
        });

        document.getElementById('notificationBell').addEventListener('click', () => {
            showAlert('You have 5 new notifications!', 'success');
        });

        if (window.innerWidth <= 768) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    }

    // Staff management functions
    function viewStaff(staffId) {
        window.location.href = `/admin/chre-staff/view/${staffId}`;
    }

    function editStaff(staffId) {
        window.location.href = `/admin/chre-staff/edit/${staffId}`;
    }

    function deleteStaff(staffId) {
        if (confirm(`Are you sure you want to delete staff ID: ${staffId}?`)) {
            window.location.href = `/admin/chre-staff/delete/${staffId}`;
        }
    }

    // ✅ Modal functions
    function addStaffMember() {
        document.getElementById('addStaffModal').style.display = 'block';
    }

    function closeAddStaffModal() {
        document.getElementById('addStaffModal').style.display = 'none';
    }

   

    // Close modal if clicked outside
    window.onclick = function(event) {
        const modal = document.getElementById('addStaffModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };

    function updateStaffTable() {
        const tbody = document.getElementById('staffTableBody');
        tbody.innerHTML = '';

        staffMembers.forEach(staff => {
            const initials = getInitials(staff.first_name + " " + staff.last_name);
            const row = `
                <tr>
                    <td>
                        <div class="staff-profile">
                            <div class="staff-avatar">${initials}</div>
                            <div class="staff-info">
                                <div class="staff-name">${staff.first_name} ${staff.last_name}</div>
                                <div class="staff-id">ID: ${staff.id}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="position-title">${staff.position}</div>
                        <div class="position-subtitle">Department Staff</div>
                    </td>
                    <td><span class="department-badge dept-${staff.department}">${capitalize(staff.department)}</span></td>
                    <td>
                        <div class="contact-info">
                            <span><i class='bx bx-envelope'></i> ${staff.email}</span>
                            <span><i class='bx bx-phone'></i> ${staff.contact_number}</span>
                        </div>
                    </td>
                    <td><span class="experience-years">${staff.experience || "N/A"}</span></td>
                    <td><span class="status-badge status-${staff.status}">${capitalize(staff.status)}</span></td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" onclick="viewStaff('${staff.id}')">
                                <i class='bx bx-show'></i>
                            </button>
                            <button class="action-btn edit" onclick="editStaff('${staff.id}')">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteStaff('${staff.id}')">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    function getInitials(name) {
        return name.split(' ').map(word => word.charAt(0).toUpperCase()).join('');
    }

    function capitalize(text) {
        return text ? text.charAt(0).toUpperCase() + text.slice(1).replace('-', ' ') : '';
    }

    function updateStats() {
        const activeStaff = staffMembers.filter(s => s.status === 'active').length;
        const onLeave = staffMembers.filter(s => s.status === 'leave').length;
        const departments = [...new Set(staffMembers.map(s => s.department))].length;

        const statCards = document.querySelectorAll('.stat-card');
        statCards[0].querySelector('.stat-number').textContent = activeStaff;
        statCards[1].querySelector('.stat-number').textContent = departments;
        statCards[3].querySelector('.stat-number').textContent = onLeave;
    }

    function filterStaff(searchTerm) {
        const tableRows = document.querySelectorAll('#staffTableBody tr');
        const term = searchTerm.toLowerCase();

        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    }

    function showAlert(message, type = 'success') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;

        document.body.appendChild(alert);

        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }

    function confirmLogout() {
        if (confirm('Are you sure you want to logout?')) {
            showAlert('Logging out...', 'success');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        }
    }

    window.addEventListener('resize', function() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show');
        }
    });
</script>

<style>
/* ✅ Modal Styling */
.modal {
  display: none; 
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.5);
  overflow-y: auto; /* <-- allow scroll when content is large */
}
.modal-content {
  background: #fff;
  margin: 5% auto;
  padding: 20px;
  border-radius: 12px;
  width: 400px;
  max-width: 90%;
  max-height: 90vh; /* <-- limit modal height to viewport */
  overflow-y: auto; /* <-- scroll inside modal */
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  display: flex;
  flex-direction: column;
}
.modal-content h2 { margin-bottom: 15px; }
.form-group { margin-bottom: 12px; }
.form-group label { display: block; margin-bottom: 6px; font-weight: bold; }
.form-group input, .form-group select {
  width: 100%; padding: 8px;
  border-radius: 6px; border: 1px solid #ccc;
}
.close {
  float: right; font-size: 22px; cursor: pointer;
}
.form-actions {
  margin-top: 15px;
  text-align: right;
  position: sticky;
  bottom: 0; /* <-- keeps button visible */
  background: #fff;
  padding-top: 10px;
}
.btn-submit {
  background: #28a745; color: white;
  border: none; padding: 10px 14px;
  border-radius: 6px; cursor: pointer;
}
.btn-submit:hover { background: #218838; }
</style>

</body>
</html>
