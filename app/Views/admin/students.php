<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
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

        /* Enhanced Modal Styles */
        .modal-content {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: none;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border: none;
            position: relative;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-close {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            opacity: 1;
            transition: all 0.3s ease;
        }

        .btn-close:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fc 100%);
        }

        /* View Modal Styles */
        .student-detail-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .student-detail-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.3s ease;
        }

        .student-detail-item:last-child {
            border-bottom: none;
        }

        .student-detail-item:hover {
            background: #f8f9fc;
            margin: 0 -15px;
            padding-left: 15px;
            padding-right: 15px;
            border-radius: 10px;
        }

        .detail-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 20px;
            font-size: 18px;
        }

        .detail-content {
            flex: 1;
        }

        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--secondary-color);
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-color);
        }

        /* Form Styles for Edit Modal */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
            background: white;
        }

        .modal-footer {
            background: #f8f9fc;
            border: none;
            padding: 25px 30px;
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--purple-color));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(78, 115, 223, 0.3);
        }

        .btn-secondary {
            background: #f8f9fc;
            color: var(--text-color);
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        /* Layout Styles - Simplified version */
        .layout {
            display: flex;
            min-height: 100vh;
        }

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

        .students-container {
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

        .stat-card.green { border-left-color: var(--success-color); }
        .stat-card.blue { border-left-color: var(--primary-color); }
        .stat-card.yellow { border-left-color: var(--warning-color); }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card.red { border-left-color: var(--danger-color); }

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

        .stat-icon.green { background-color: #f0fdf4; color: var(--success-color); }
        .stat-icon.blue { background-color: #eff6ff; color: var(--primary-color); }
        .stat-icon.yellow { background-color: #fffbeb; color: var(--warning-color); }
        .stat-icon.purple { background-color: #faf5ff; color: #8b5cf6; }
        .stat-icon.red { background-color: #fef2f2; color: var(--danger-color); }

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

        .student-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .student-info {
            flex: 1;
        }

        .student-name {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2px;
        }

        .student-id {
            color: var(--secondary-color);
            font-size: 12px;
        }

        .contact-info {
            color: var(--secondary-color);
            font-size: 13px;
            display: flex;
            flex-direction: column;
            gap: 2px;
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

        .d-none {
            display: none;
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
        <img src="https://via.placeholder.com/60x60/4e73df/ffffff?text=CHRE" alt="CHRE Logo">
        <div class="system-title">CHRE Management System</div>
      </div>

      <!-- User Profile -->
      <div class="user-profile">
        <div class="user-avatar">
          <i class='bx bx-user'></i>
        </div>
        <div class="user-name">Admin User</div>
        <div class="user-role">Administrator</div>
      </div>

      <!-- Navigation -->
            <div class="nav-section">
        <div class="nav-header">Main Menu</div>
        <nav>
          <div class="nav-item">
            <a class="nav-link" href="<?= base_url('admin/dashboard') ?>">
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
            <a class="nav-link active" href="<?= base_url('admin/students') ?>">
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
                    <span class="current">Students</span>
                </div>

                <div class="navbar-actions">
                    <!-- Search Bar -->
                    <div class="search-box">
                        <i class='bx bx-search search-icon'></i>
                        <input type="text" class="search-input" placeholder="Search students, records...">
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

            <!-- Students Content -->
            <div class="students-container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Students Management</h1>
                    <p class="page-subtitle">Manage student records, enrollment, and academic information</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <i class='bx bx-group'></i>
                        </div>
                        <div class="stat-number">1,245</div>
                        <div class="stat-label">Active Students</div>
                        <div class="stat-change positive">↑ +8%</div>
                    </div>
                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <i class='bx bx-book'></i>
                        </div>
                        <div class="stat-number">156</div>
                        <div class="stat-label">New Enrollments</div>
                        <div class="stat-change positive">↑ +12%</div>
                    </div>
                    <div class="stat-card yellow">
                        <div class="stat-icon yellow">
                            <i class='bx bx-time'></i>
                        </div>
                        <div class="stat-number">23</div>
                        <div class="stat-label">Pending Applications</div>
                        <div class="stat-change negative">↓ -5%</div>
                    </div>
                    <div class="stat-card purple">
                        <div class="stat-icon purple">
                            <i class='bx bx-medal'></i>
                        </div>
                        <div class="stat-number">89</div>
                        <div class="stat-label">Graduating Students</div>
                        <div class="stat-change positive">↑ +15%</div>
                    </div>
                    <div class="stat-card red">
                        <div class="stat-icon red">
                            <i class='bx bx-error'></i>
                        </div>
                        <div class="stat-number">12</div>
                        <div class="stat-label">At Risk Students</div>
                        <div class="stat-change negative">↓ -3%</div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="action-bar">
                    <div class="search-box">
                        <input type="text" placeholder="Search students..." id="studentSearch">
                        <i class='bx bx-search search-icon'></i>
                    </div>
                    <div class="filter-buttons">
                        <button class="btn btn-outline">
                            <i class='bx bx-list-ul'></i>
                            All Status
                        </button>
                        <button class="btn btn-outline">
                            <i class='bx bx-target-lock'></i>
                            Year Level
                        </button>
                        <button class="btn btn-outline">
                            <i class='bx bx-chart'></i>
                            GPA Range
                        </button>
                        <button class="btn btn-primary">
                            <i class='bx bx-download'></i>
                            Export Data
                        </button>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Student Records</h3>
                        <button class="btn btn-primary">
                            <i class='bx bx-plus'></i>
                            Add Student
                        </button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Full Name</th>
                                <th>Year</th>
                                <th>Contact</th>
                                <th>Department</th>
                                <th>Course</th>
                                <th>Date Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
<tbody id="studentsTableBody">
      <?php if (!empty($students)): ?>
        <?php foreach ($students as $student): ?>
          <tr>
            <!-- hidden ID -->
            <td class="student-id d-none"><?= esc($student['id']) ?></td>

            <!-- Name -->
            <td class="student-name">
              <?= esc($student['first_name'].' '.$student['mi'].'. '.$student['last_name']) ?>
            </td>

            <!-- Year -->
            <td class="student-year"><?= esc($student['year']) ?></td>

            <!-- Contact -->
            <td>
              <div class="student-email d-none"><?= esc($student['email']) ?></div>
              <div class="student-contact"><?= esc($student['contact_number']) ?></div>
              <small><?= esc($student['email']) ?></small>
            </td>

            <!-- Department -->
            <td class="student-dept"><?= esc($student['department']) ?></td>

            <!-- Course -->
            <td class="student-course"><?= esc($student['course']) ?></td>

            <!-- Date Registered -->
            <td><?= date('M j, Y', strtotime($student['created_at'] ?? 'now')) ?></td>

            <!-- Actions -->
<!-- Actions -->
<td>
  <div class="action-buttons">
    <button class="action-btn view" data-id="<?= $student['id'] ?>"><i class='bx bx-show'></i></button>
    <button class="action-btn edit" data-id="<?= $student['id'] ?>"><i class='bx bx-edit'></i></button>
    <button class="action-btn delete" data-id="<?= $student['id'] ?>"><i class='bx bx-trash'></i></button>
  </div>
</td>

          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="8" class="text-center text-muted">No students found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
            </div>
        </div>
    </div>

    <!-- Enhanced View Student Modal -->
    <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-labelledby="viewStudentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStudentLabel">
                        <i class='bx bx-user-circle' style="margin-right: 10px;"></i>
                        Student Information
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="student-detail-card">
                        <div class="student-detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-user'></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Full Name</div>
                                <div class="detail-value" id="viewName">-</div>
                            </div>
                        </div>
                        <div class="student-detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-envelope'></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Email Address</div>
                                <div class="detail-value" id="viewEmail">-</div>
                            </div>
                        </div>
                        <div class="student-detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-phone'></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Contact Number</div>
                                <div class="detail-value" id="viewContact">-</div>
                            </div>
                        </div>
                        <div class="student-detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-buildings'></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Department</div>
                                <div class="detail-value" id="viewDept">-</div>
                            </div>
                        </div>
                        <div class="student-detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-book'></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Course</div>
                                <div class="detail-value" id="viewCourse">-</div>
                            </div>
                        </div>
                        <div class="student-detail-item">
                            <div class="detail-icon">
                                <i class='bx bx-calendar'></i>
                            </div>
                            <div class="detail-content">
                                <div class="detail-label">Year Level</div>
                                <div class="detail-value" id="viewYear">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStudentLabel">
                        <i class='bx bx-edit' style="margin-right: 10px;"></i>
                        Edit Student Information
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStudentForm" method="post">
                        <input type="hidden" name="id" id="editId">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="editFirstName" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Middle Initial</label>
                                    <input type="text" class="form-control" name="mi" id="editMI" maxlength="1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="editLastName" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="editEmail" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number" id="editContact" placeholder="+63 912 345 6789">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-control" name="department" id="editDept" placeholder="e.g., Computer Science">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Year Level</label>
                                    <select class="form-control" name="year" id="editYear">
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year">3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                        <option value="Graduate">Graduate</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Course</label>
                            <input type="text" class="form-control" name="course" id="editCourse" placeholder="e.g., BS Computer Science">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editStudentForm" class="btn btn-primary">
                        <i class='bx bx-save' style="margin-right: 5px;"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

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
                console.log('Logging out...');
                // Add your logout logic here
            }
        }

        // ======================
        // Student Action Buttons - FIXED
        // ======================
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize Bootstrap modals properly
            const viewModalElement = document.getElementById("viewStudentModal");
            const editModalElement = document.getElementById("editStudentModal");
            
            let viewModal, editModal;
            
            // Initialize modals only if Bootstrap is available
            if (typeof bootstrap !== 'undefined') {
                viewModal = new bootstrap.Modal(viewModalElement, {
                    backdrop: 'static',
                    keyboard: true
                });
                editModal = new bootstrap.Modal(editModalElement, {
                    backdrop: 'static',
                    keyboard: true
                });
            }

            // VIEW button functionality
            document.querySelectorAll(".action-btn.view").forEach(btn => {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const row = this.closest("tr");
                    if (!row) return;

                    // Extract data from the row
                    const name = row.querySelector(".student-name")?.textContent?.trim() || '-';
                    const email = row.querySelector(".student-email")?.textContent?.trim() || 
                                  row.querySelector("small")?.textContent?.trim() || '-';
                    const contact = row.querySelector(".student-contact")?.textContent?.trim() || '-';
                    const dept = row.querySelector(".student-dept")?.textContent?.trim() || '-';
                    const course = row.querySelector(".student-course")?.textContent?.trim() || '-';
                    const year = row.querySelector(".student-year")?.textContent?.trim() || '-';

                    // Populate modal with data
                    document.getElementById("viewName").textContent = name;
                    document.getElementById("viewEmail").textContent = email;
                    document.getElementById("viewContact").textContent = contact;
                    document.getElementById("viewDept").textContent = dept;
                    document.getElementById("viewCourse").textContent = course;
                    document.getElementById("viewYear").textContent = year;

                    // Show modal
                    if (viewModal) {
                        viewModal.show();
                    } else {
                        viewModalElement.style.display = 'block';
                        viewModalElement.classList.add('show');
                    }
                });
            });

            // EDIT button functionality
            document.querySelectorAll(".action-btn.edit").forEach(btn => {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const row = this.closest("tr");
                    if (!row) return;

                    // Extract data from the row
                    const id = row.querySelector(".student-id")?.textContent?.trim() || '';
                    const fullName = row.querySelector(".student-name")?.textContent?.trim() || '';
                    const email = row.querySelector(".student-email")?.textContent?.trim() || 
                                  row.querySelector("small")?.textContent?.trim() || '';
                    const contact = row.querySelector(".student-contact")?.textContent?.trim() || '';
                    const dept = row.querySelector(".student-dept")?.textContent?.trim() || '';
                    const course = row.querySelector(".student-course")?.textContent?.trim() || '';
                    const year = row.querySelector(".student-year")?.textContent?.trim() || '';

                    // Parse full name
                    const nameParts = fullName.split(' ');
                    const firstName = nameParts[0] || '';
                    const lastName = nameParts.slice(-1)[0] || '';
                    const middleParts = nameParts.slice(1, -1);
                    const middleInitial = middleParts.length > 0 ? middleParts[0].charAt(0) : '';

                    // Populate form fields
                    document.getElementById("editId").value = id;
                    document.getElementById("editFirstName").value = firstName;
                    document.getElementById("editMI").value = middleInitial;
                    document.getElementById("editLastName").value = lastName;
                    document.getElementById("editEmail").value = email;
                    document.getElementById("editContact").value = contact;
                    document.getElementById("editDept").value = dept;
                    document.getElementById("editCourse").value = course;
                    document.getElementById("editYear").value = year;

                    // Show modal
                    if (editModal) {
                        editModal.show();
                    } else {
                        editModalElement.style.display = 'block';
                        editModalElement.classList.add('show');
                    }
                });
            });

            // DELETE button functionality
            document.querySelectorAll(".action-btn.delete").forEach(btn => {
                btn.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const row = this.closest("tr");
                    if (!row) return;
                    
                    const name = row.querySelector(".student-name")?.textContent?.trim() || 'this student';
                    const id = row.querySelector(".student-id")?.textContent?.trim() || '';

                    if (confirm(`Are you sure you want to delete ${name}?`)) {
                        // Simulate deletion - replace with actual delete logic
                        console.log(`Deleting student with ID: ${id}`);
                        row.remove();
                        
                        // If you have a backend endpoint, use this:
                        /*
                        fetch(`/admin/students/delete/${id}`, {
                            method: "POST",
                            headers: { 
                                "X-Requested-With": "XMLHttpRequest",
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.remove();
                            } else {
                                alert('Error deleting student');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error deleting student');
                        });
                        */
                    }
                });
            });

            // Form submission for edit modal
            document.getElementById('editStudentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const studentData = Object.fromEntries(formData.entries());
                
                console.log('Updating student:', studentData);
                
                // Simulate successful update
                alert('Student information updated successfully!');
                
                // Close modal
                if (editModal) {
                    editModal.hide();
                } else {
                    editModalElement.style.display = 'none';
                    editModalElement.classList.remove('show');
                }
                
                // Refresh the page or update the table row
                // location.reload(); // Uncomment this line if you want to refresh the page
                
                // If you have a backend endpoint, use this:
                /*
                fetch('/admin/students/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(studentData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Student updated successfully!');
                        editModal.hide();
                        location.reload();
                    } else {
                        alert('Error updating student: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating student');
                });
                */
            });

            // Close modal handlers for non-Bootstrap fallback
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const modal = this.closest('.modal');
                    if (modal) {
                        modal.style.display = 'none';
                        modal.classList.remove('show');
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('studentSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#studentsTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Filter buttons functionality
        document.querySelectorAll('.filter-buttons .btn-outline').forEach(btn => {
            btn.addEventListener('click', function() {
                console.log('Filter clicked:', this.textContent.trim());
                // Add your filter logic here
            });
        });

        // Export functionality
        document.querySelector('.btn-primary[onclick]')?.addEventListener('click', function() {
            if (this.textContent.includes('Export')) {
                console.log('Exporting student data...');
                // Add export logic here
            }
        });
    </script>
</body>
</html>