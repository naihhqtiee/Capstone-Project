<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users Management</title>
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
            background: var(--primary-color);
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

        /* Users Content */
        .users-container {
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

        .stat-card.blue { border-left-color: var(--primary-color); }
        .stat-card.green { border-left-color: var(--success-color); }
        .stat-card.yellow { border-left-color: var(--warning-color); }
        .stat-card.purple { border-left-color: #8b5cf6; }

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

        .stat-icon.blue { background-color: #eff6ff; color: var(--primary-color); }
        .stat-icon.green { background-color: #f0fdf4; color: var(--success-color); }
        .stat-icon.yellow { background-color: #fffbeb; color: var(--warning-color); }
        .stat-icon.purple { background-color: #faf5ff; color: #8b5cf6; }

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
            outline: none;
        }

        .action-bar .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
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

        .table-responsive {
            overflow-x: auto;
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

        .user-profile-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-cell {
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

        .user-info-cell {
            flex: 1;
        }

        .user-name-cell {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .user-email-cell {
            color: var(--secondary-color);
            font-size: 12px;
        }

        .status-badge, .role-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-verified { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }

        .role-admin { background: #fce7f3; color: #be185d; }
        .role-staff { background: #d1fae5; color: #065f46; }
        .role-student { background: #dbeafe; color: #1e40af; }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: var(--secondary-color);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: flex;
            opacity: 1;
            align-items: center;
            justify-content: center;
        }

        .modal-dialog {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            transform: scale(0.7);
            transition: transform 0.3s ease;
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        .modal-content {
            padding: 0;
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-color);
            margin: 0;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--secondary-color);
        }

        .modal-body {
            padding: 25px;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.1);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            padding-right: 40px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-secondary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-secondary:hover {
            background: #6c757d;
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

            .table-responsive {
                font-size: 14px;
            }

            th, td {
                padding: 12px 8px;
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
            <a class="nav-link active" href="<?= base_url('admin/users') ?>">
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
                    <span class="current">All Users</span>
                </div>

                <div class="navbar-actions">
                    <!-- Search Bar -->
                    <div class="search-box">
                        <i class='bx bx-search search-icon'></i>
                        <input type="text" class="search-input" placeholder="Search users, roles...">
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

            <!-- Users Content -->
            <div class="users-container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">All Users Management</h1>
                    <p class="page-subtitle">Comprehensive user management across all system roles and departments</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <i class='bx bx-group'></i>
                        </div>
                        <div class="stat-number"><?= $totalUsers ?></div>
                        <div class="stat-label">Total Users</div>
                        <div class="stat-change positive">↑ +12%</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <i class='bx bx-check-circle'></i>
                        </div>
                        <div class="stat-number"><?= $verifiedCount ?></div>
                        <div class="stat-label">Verified Users</div>
                        <div class="stat-change positive">↑ +8%</div>
                    </div>
                    <div class="stat-card yellow">
                        <div class="stat-icon yellow">
                            <i class='bx bx-time'></i>
                        </div>
                        <div class="stat-number"><?= $pendingCount ?></div>
                        <div class="stat-label">Pending Approval</div>
                        <div class="stat-change negative">↓ -5%</div>
                    </div>
                    <div class="stat-card purple">
                        <div class="stat-icon purple">
                            <i class='bx bx-shield-check'></i>
                        </div>
                        <div class="stat-number"><?= $adminCount ?></div>
                        <div class="stat-label">Admin Users</div>
                        <div class="stat-change positive">↑ +15%</div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="action-bar">
                    <div class="search-box">
                        <input type="text" placeholder="Search users by name, email, or role..." id="userSearch">
                        <i class='bx bx-search search-icon'></i>
                    </div>
                    <button class="btn btn-primary" id="addUserBtn">
                        <i class='bx bx-user-plus'></i>
                        Add New User
                    </button>
                </div>

                <!-- Users Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">User Directory</h3>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Info</th>
                                    <th>Contact Number</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Date Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
<tbody>
  <?php if (!empty($users)): ?>
    <?php foreach ($users as $user): ?>
      <tr>
        <td>#<?= esc($user['id']) ?></td>

        <!-- ✅ User Info (Name + Email + Avatar) -->
        <td>
          <div class="user-profile-cell">
            <div class="user-avatar-cell">
              <?php 
                $names = explode(' ', $user['full_name']);
                $initials = strtoupper(substr($names[0] ?? '', 0, 1));
                if (isset($names[1])) $initials .= strtoupper(substr($names[1], 0, 1));
                echo $initials;
              ?>
            </div>
            <div class="user-info-cell">
              <div class="user-name-cell"><?= esc($user['full_name']) ?></div>
              <div class="user-email-cell"><?= esc($user['email']) ?></div>
            </div>
          </div>
        </td>

        <!-- ✅ Contact Number -->
        <td>
          <?= !empty($user['contact_number']) 
                ? esc($user['contact_number']) 
                : '<span class="text-muted">Not Provided</span>' ?>
        </td>

        <!-- ✅ Role -->
        <td><span class="role-badge role-<?= esc($user['role']) ?>">
          <?= ucfirst(esc($user['role'])) ?>
        </span></td>

        <!-- ✅ Status -->
        <td class="text-center">
          <span class="status-badge status-<?= $user['status'] === 'verified' ? 'verified' : 'pending' ?>">
            <?= ucfirst(esc($user['status'])) ?>
          </span>
        </td>

        <!-- ✅ Date Registered -->
        <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>

        <!-- ✅ Actions -->
        <td class="text-center">
          <a href="<?= base_url('admin/users/delete/'.$user['id']) ?>" 
             class="btn btn-danger btn-sm"
             onclick="return confirm('Are you sure you want to delete this user?')">
             <i class="bx bx-trash"></i> Delete
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr>
      <td colspan="7" class="text-center text-muted">No users found</td>
    </tr>
  <?php endif; ?>
</tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal" id="addUserModal">
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
    </div>

    <script>
        // Global variables
        let users = [
            {id: 1, name: 'John Doe', email: 'john.doe@example.com', role: 'admin', status: 'verified', date: 'Mar 15, 2024'},
            {id: 2, name: 'Jane Smith', email: 'jane.smith@example.com', role: 'staff', status: 'verified', date: 'Mar 10, 2024'},
            {id: 3, name: 'Mike Brown', email: 'mike.brown@example.com', role: 'student', status: 'pending', date: 'Mar 5, 2024'},
            {id: 4, name: 'Sarah Davis', email: 'sarah.davis@example.com', role: 'staff', status: 'verified', date: 'Feb 28, 2024'},
            {id: 5, name: 'Robert Wilson', email: 'robert.wilson@example.com', role: 'student', status: 'verified', date: 'Feb 20, 2024'}
        ];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Hide loading screen after a short delay
            setTimeout(() => {
                document.getElementById('loadingScreen').classList.remove('show');
            }, 1000);

            // Initialize event listeners
            initializeEventListeners();
        });

        function initializeEventListeners() {
            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');

            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });

            // Add user button
            document.getElementById('addUserBtn').addEventListener('click', () => {
                openModal('addUserModal');
            });

            // Search functionality
            const searchInput = document.getElementById('userSearch');
            searchInput.addEventListener('input', function() {
                filterUsers(this.value);
            });

            // Add user form submission
            document.getElementById('addUserForm').addEventListener('submit', function(e) {
                e.preventDefault();
                addNewUser();
            });

            // Notification bell click
            document.getElementById('notificationBell').addEventListener('click', () => {
                showAlert('You have 5 new notifications!', 'success');
            });

            // Mobile sidebar toggle
            if (window.innerWidth <= 768) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                });
            }
        }

        // Modal functions
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
            
            // Reset form if it's the add user modal
            if (modalId === 'addUserModal') {
                document.getElementById('addUserForm').reset();
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal')) {
                closeModal(e.target.id);
            }
        });

        // User management functions
        function addNewUser() {
            const form = document.getElementById('addUserForm');
            const formData = new FormData(form);
            
            const newUser = {
                id: users.length + 1,
                name: formData.get('full_name'),
                email: formData.get('email'),
                role: formData.get('role'),
                status: 'pending',
                date: new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
            };

            users.push(newUser);
            updateUsersTable();
            updateStats();
            closeModal('addUserModal');
            showAlert(`User ${newUser.name} added successfully!`, 'success');
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                const userIndex = users.findIndex(user => user.id === userId);
                if (userIndex > -1) {
                    const deletedUser = users.splice(userIndex, 1)[0];
                    updateUsersTable();
                    updateStats();
                    showAlert(`User ${deletedUser.name} deleted successfully!`, 'success');
                }
            }
        }

        function updateUsersTable() {
            const tbody = document.getElementById('usersTableBody');
            tbody.innerHTML = '';

            users.forEach(user => {
                const initials = getInitials(user.name);
                const row = `
                    <tr>
                        <td>#${String(user.id).padStart(3, '0')}</td>
                        <td>
                            <div class="user-profile-cell">
                                <div class="user-avatar-cell">${initials}</div>
                                <div class="user-info-cell">
                                    <div class="user-name-cell">${user.name}</div>
                                    <div class="user-email-cell">${user.email}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="role-badge role-${user.role}">${user.role.charAt(0).toUpperCase() + user.role.slice(1)}</span></td>
                        <td class="text-center">
                            <span class="status-badge status-${user.status}">${user.status.charAt(0).toUpperCase() + user.status.slice(1)}</span>
                        </td>
                        <td>${user.date}</td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">
                                <i class="bx bx-trash"></i> Delete
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        function getInitials(name) {
            return name.split(' ').map(word => word.charAt(0).toUpperCase()).join('');
        }

        function updateStats() {
            const totalUsers = users.length;
            const verifiedUsers = users.filter(user => user.status === 'verified').length;
            const pendingUsers = users.filter(user => user.status === 'pending').length;
            const adminUsers = users.filter(user => user.role === 'admin').length;

            // Update stat cards (you could make this more dynamic)
            document.querySelector('.stat-card.blue .stat-number').textContent = totalUsers;
            document.querySelector('.stat-card.green .stat-number').textContent = verifiedUsers;
            document.querySelector('.stat-card.yellow .stat-number').textContent = pendingUsers;
            document.querySelector('.stat-card.purple .stat-number').textContent = adminUsers;
        }

        function filterUsers(searchTerm) {
            const tableRows = document.querySelectorAll('#usersTableBody tr');
            const term = searchTerm.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Utility functions
        function showAlert(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            
            document.body.appendChild(alert);
            
            // Auto-hide after 5 seconds
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

        // Responsive handling
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>
</html>