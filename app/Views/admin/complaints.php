<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Management</title>
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
        }

        .search-input:focus {
            width: 350px;
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
            border-color: var(--primary-color);
            outline: none;
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

        /* Complaints Content */
        .complaints-container {
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

        .stat-card.red { border-left-color: var(--danger-color); }
        .stat-card.yellow { border-left-color: var(--warning-color); }
        .stat-card.green { border-left-color: var(--success-color); }
        .stat-card.blue { border-left-color: var(--primary-color); }

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

        .stat-icon.red { background-color: #fef2f2; color: var(--danger-color); }
        .stat-icon.yellow { background-color: #fffbeb; color: var(--warning-color); }
        .stat-icon.green { background-color: #f0fdf4; color: var(--success-color); }
        .stat-icon.blue { background-color: #eff6ff; color: var(--primary-color); }

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

        .stat-change.positive { color: var(--success-color); }
        .stat-change.negative { color: var(--danger-color); }

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
            display: flex;
            align-items: center;
            gap: 8px;
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
            color: var(--secondary-color);
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

        .status-badge, .priority-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending { background: #fef3c7; color: #92400e; }
        .status-in-progress { background: #dbeafe; color: #1e40af; }
        .status-resolved { background: #d1fae5; color: #065f46; }
        .status-closed { background: #f3f4f6; color: #374151; }

        .priority-high { background: #fecaca; color: #991b1b; }
        .priority-medium { background: #fed7aa; color: #9a3412; }
        .priority-low { background: #d1fae5; color: #065f46; }

        .complaint-title {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 4px;
        }

        .complaint-user {
            color: var(--secondary-color);
            font-size: 13px;
        }

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

        .action-btn.view { background: #eff6ff; color: var(--primary-color); }
        .action-btn.edit { background: #f0fdf4; color: var(--success-color); }
        .action-btn.delete { background: #fef2f2; color: var(--danger-color); }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .date-text {
            color: var(--secondary-color);
            font-size: 13px;
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
            <a class="nav-link active" href= "<?= base_url('admin/complaints') ?>">
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
                    <span class="current">Complaints</span>
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
                        <span class="notification-count">5</span>
                    </button>

                    <!-- User Dropdown -->
                    <div class="user-dropdown">
                        <button class="user-dropdown-toggle">
                            <i class='bx bx-user'></i>
                            <span>Admin User</span>
                            <i class='bx bx-chevron-down'></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Complaints Content -->
            <div class="complaints-container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Complaints Management</h1>
                    <p class="page-subtitle">Monitor and manage all complaints submitted by users</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card red">
                        <div class="stat-icon red">
                            <i class='bx bx-error'></i>
                        </div>
                        <div class="stat-number"><?= esc($pending) ?></div>
                        <div class="stat-label">Pending Complaints</div>
                        <div class="stat-change negative">↓ -5%</div>
                    </div>
                    <div class="stat-card yellow">
                        <div class="stat-icon yellow">
                            <i class='bx bx-loader-alt'></i>
                        </div>
                        <div class="stat-number"><?= esc($ongoing) ?></div>
                        <div class="stat-label">In Progress</div>
                        <div class="stat-change positive">↑ +8%</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <i class='bx bx-check'></i>
                        </div>
                        <div class="stat-number"><?= esc($resolved) ?></div>
                        <div class="stat-label">Resolved</div>
                        <div class="stat-change positive">↑ +15%</div>
                    </div>
                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <i class='bx bx-chart'></i>
                        </div>
                        <div class="stat-number"><?= esc($total) ?></div>
                        <div class="stat-label">Total Complaints</div>
                        <div class="stat-change positive">↑ +12%</div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="action-bar">
                    <div class="search-box">
                        <input type="text" placeholder="Search complaints...">
                        <i class='bx bx-search search-icon'></i>
                    </div>
                    <div class="filter-buttons">
                        <button class="btn btn-outline">
                            <i class='bx bx-list-ul'></i>
                            All Status
                        </button>
                        <button class="btn btn-outline">
                            <i class='bx bx-sort-down'></i>
                            Priority
                        </button>
                        <button class="btn btn-outline">
                            <i class='bx bx-calendar'></i>
                            Date Range
                        </button>
                        <button class="btn btn-primary">
                            <i class='bx bx-download'></i>
                            Export Data
                        </button>
                    </div>
                </div>

                <!-- Complaints Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Recent Complaints</h3>
                        <button class="btn btn-primary">
                            <i class='bx bx-plus'></i>
                            New Complaint
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Complaint Details</th>
                                <th>Date of incident</th>
                                <th>Complaint Type</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
<tbody>
<?php if (!empty($complaints)): ?>
    <?php foreach ($complaints as $complaint): ?>
        <tr>
            <td>#C<?= esc($complaint['id']) ?></td>

            <td>
                <div class="complaint-title">
                    <!-- Show category + location -->
                    <?= esc($complaint['complaint_category']) ?> - <?= esc($complaint['location']) ?>
                </div>

                <!-- ✅ Show complainant details -->
                <div class="complaint-user">
                    <?php if (!empty($complaint['full_name'])): ?>
                        <strong><?= esc($complaint['full_name']) ?></strong><br>
                        <small>Email: <?= esc($complaint['email']) ?></small><br>
                        <small>Contact: <?= esc($complaint['contact_number'] ?? 'N/A') ?></small>
                    <?php else: ?>
                        <em>Anonymous</em>
                    <?php endif; ?>
                </div>
            </td>

            <!-- ✅ Date of Incident -->
            <td class="date-text">
                <?= !empty($complaint['date']) 
                    ? date('M d, Y', strtotime($complaint['date'])) 
                    : 'Not Provided' ?>
            </td>

            <!-- ✅ Complaint Type (academic / non-academic) -->
            <td>
                <span class="priority-badge <?= $complaint['complaint_type'] === 'academic' 
                        ? 'priority-high' 
                        : 'priority-medium' ?>">
                    <?= ucfirst($complaint['complaint_type']) ?>
                </span>
            </td>

            <!-- Status -->
            <td>
                <?php
                    $statusClass = match($complaint['status']) {
                        'pending'     => 'status-pending',
                        'in_progress' => 'status-in-progress',
                        'resolved'    => 'status-resolved',
                        'closed'      => 'status-closed',
                        default       => 'status-pending'
                    };
                ?>
                <span class="status-badge <?= $statusClass ?>">
                    <?= ucfirst(str_replace('_', ' ', $complaint['status'] ?? 'Pending')) ?>
                </span>
            </td>

            <!-- Date Submitted -->
            <td class="date-text">
                <?= date('M d, Y', strtotime($complaint['created_at'])) ?>
            </td>

            <!-- Actions -->
<td>
    <div class="action-buttons">
        <a href="<?= base_url('admin/complaints/view/'.$complaint['id']) ?>" class="action-btn view">
            <i class='bx bx-show'></i>
        </a>
        <a href="<?= base_url('admin/complaints/edit/ '.$complaint['id']) ?>" class="action-btn edit">
            <i class='bx bx-edit'></i>
        </a>
        <a href="<?= base_url('admin/complaints/delete/'.$complaint['id']) ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this complaint?')">
            <i class='bx bx-trash'></i>
        </a>
    </div>
</td>

        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center">No recent complaints found.</td>
    </tr>
<?php endif; ?>
</tbody>



                    </table>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <script>
        // Loading screen
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('loadingScreen').style.opacity = '0';
                setTimeout(() => {
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
        });

        // Mobile sidebar toggle
        if (window.innerWidth <= 768) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
        }

        // Logout confirmation
        function confirmLogout() {
            if (confirm('Are you sure you want to logout?')) {
                // Add your logout logic here
                console.log('Logging out...');
            }
        }

        // Add click handlers for action buttons
// Add click handlers for action buttons
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();  // 🚨 THIS BLOCKS YOUR LINKS
        const action = this.classList.contains('view') ? 'View' : 
                      this.classList.contains('edit') ? 'Edit' : 'Delete';
        const row = this.closest('tr');
        const id = row.cells[0].textContent;
        
        if (action === 'Delete') {
            if (confirm(`Are you sure you want to delete complaint ${id}?`)) {
                console.log(`${action} complaint ${id}`);
            }
        } else {
            console.log(`${action} complaint ${id}`);
        }
    });
});


        // Search functionality
        document.querySelector('.action-bar .search-box input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Filter buttons functionality
        document.querySelectorAll('.filter-buttons .btn-outline').forEach(btn => {
            btn.addEventListener('click', function() {
                // Add filter logic here
                console.log('Filter clicked:', this.textContent.trim());
            });
        });

        // Export functionality
        document.querySelector('.btn-primary[onclick*="Export"]')?.addEventListener('click', function() {
            console.log('Exporting data...');
            // Add export logic here
        });
    </script>
</body>
</html>