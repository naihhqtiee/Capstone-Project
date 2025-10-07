<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Management</title>
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
        .modal-overlay {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.modal-overlay.show {
    opacity: 1;
    visibility: visible;
}

.modal-content {
    background: white;
    border-radius: 16px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    transform: scale(0.95) translateY(20px);
    transition: all 0.3s ease;
    margin: 20px;
}

.modal-overlay.show .modal-content {
    transform: scale(1) translateY(0);
}

.modal-header {
    padding: 24px 24px 0;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.modal-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    line-height: 1.2;
    flex: 1;
    margin-right: 16px;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #64748b;
    cursor: pointer;
    padding: 4px;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    flex-shrink: 0;
}

.modal-close:hover {
    background-color: #f1f5f9;
    color: #374151;
}

.modal-body {
    padding: 24px;
}

/* View Modal Specific Styles */
.event-details {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-icon {
    font-size: 18px;
    width: 24px;
    flex-shrink: 0;
    margin-top: 2px;
}

.detail-content {
    flex: 1;
}

.detail-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
    font-size: 14px;
}

.detail-value {
    color: #64748b;
    line-height: 1.5;
}

.file-attachment {
    margin-top: 8px;
}

.file-attachment img {
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    width: 100%;
    object-fit: contain;
    border: 1px solid #e2e8f0;
}

.modal-footer {
    padding: 0 24px 24px;
    border-top: 1px solid #e2e8f0;
    margin-top: 16px;
    padding-top: 16px;
}

.timestamp-info {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #64748b;
}

/* Edit Modal Specific Styles */
.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background-color: #ffffff;
}

.form-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
}

.form-submit {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 8px;
}

.form-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
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

        /* Events Content */
        .events-container {
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

        .stat-card.cyan { border-left-color: #06b6d4; }
        .stat-card.blue { border-left-color: var(--primary-color); }
        .stat-card.green { border-left-color: var(--success-color); }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card.orange { border-left-color: var(--warning-color); }

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

        .stat-icon.cyan { background-color: #ecfeff; color: #06b6d4; }
        .stat-icon.blue { background-color: #eff6ff; color: var(--primary-color); }
        .stat-icon.green { background-color: #f0fdf4; color: var(--success-color); }
        .stat-icon.purple { background-color: #faf5ff; color: #8b5cf6; }
        .stat-icon.orange { background-color: #fffbeb; color: var(--warning-color); }

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

        .status-badge, .category-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-upcoming { background: #dbeafe; color: #1e40af; }
        .status-ongoing { background: #d1fae5; color: #065f46; }
        .status-completed { background: #f3f4f6; color: #374151; }
        .status-cancelled { background: #fecaca; color: #991b1b; }

        .category-academic { background: #e0e7ff; color: #3730a3; }
        .category-social { background: #fef3c7; color: #92400e; }
        .category-sports { background: #d1fae5; color: #065f46; }
        .category-cultural { background: #fce7f3; color: #be185d; }
        .category-workshop { background: #f0fdf4; color: #166534; }

        .event-title {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 4px;
        }

        .event-venue {
            color: var(--secondary-color);
            font-size: 13px;
        }

        .event-time {
            color: var(--secondary-color);
            font-size: 13px;
            display: flex;
            flex-direction: column;
        }

        .event-date {
            font-weight: 500;
            color: #374151;
        }

        .attendees-count {
            background: #f1f5f9;
            color: #475569;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
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
            <a class="nav-link" href= "<?= base_url('admin/complaints') ?>">
              <i class='bx bx-message-square-error'></i>
              <span class="nav-text">Complaints</span>
              <span class="nav-badge bg-danger"><?= esc($total) ?></span>
            </a>
          </div>
          <div class="nav-item">
            <a class="nav-link active" href="<?= base_url('admin/events') ?>">
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
                    <span class="current">Events</span>
                </div>

                <div class="navbar-actions">
                    <!-- Search Bar -->
                    <div class="search-box">
                        <i class='bx bx-search search-icon'></i>
                        <input type="text" class="search-input" placeholder="Search events, users...">
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

            <!-- Events Content -->
            <div class="events-container">
                <!-- Page Header -->
                <div class="page-header">
                    <h1 class="page-title">Events Management</h1>
                    <p class="page-subtitle">Create, manage and monitor all campus events and activities</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card cyan">
                        <div class="stat-icon cyan">
                            <i class='bx bx-calendar'></i>
                        </div>
                        <div class="stat-number"><?= count($upcomingEvents ?? []) ?>
</div>
                        <div class="stat-label">Upcoming Events</div>
                        <div class="stat-change positive">↑ +15%</div>
                    </div>
                    <div class="stat-card blue">
                        <div class="stat-icon blue">
                            <i class='bx bx-play-circle'></i>
                        </div>
                        <div class="stat-number"><?= count($ongoingEvents ?? []) ?>
</div>
                        <div class="stat-label">Ongoing Events</div>
                        <div class="stat-change positive">↑ +25%</div>
                    </div>
                    <div class="stat-card green">
                        <div class="stat-icon green">
                            <i class='bx bx-check-circle'></i>
                        </div>
                        <div class="stat-number"><?= count($completedEvents ?? []) ?>
</div>
                        <div class="stat-label">Completed Events</div>
                        <div class="stat-change positive">↑ +20%</div>
                    </div>
                    <div class="stat-card purple">
                        <div class="stat-icon purple">
                            <i class='bx bx-group'></i>
                        </div>
                        <div class="stat-number"><?= esc($totalAttendees ?? 0) ?></div>
                        <div class="stat-label">Total Attendees</div>
                        <div class="stat-change positive">↑ +18%</div>
                    </div>
                    <div class="stat-card orange">
                        <div class="stat-icon orange">
                            <i class='bx bx-chart'></i>
                        </div>
                        <div class="stat-number"><?= esc($totalEvents ?? 0) ?></div>
                        <div class="stat-label">Total Events</div>
                        <div class="stat-change positive">↑ +12%</div>
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="action-bar">
                    <div class="search-box">
                        <input type="text" placeholder="Search events...">
                        <i class='bx bx-search search-icon'></i>
                    </div>
                    <div class="filter-buttons">
                        <button class="btn btn-outline">
                            <i class='bx bx-list-ul'></i>
                            All Status
                        </button>
                        <button class="btn btn-outline">
                            <i class='bx bx-tag'></i>
                            Category
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

                <!-- Events Table -->
<div class="table-container">
    <div class="table-header">
        <h3 class="table-title">All Events</h3>
        <!-- ✅ Create Event Button -->
        <button id="openCreateModal" class="btn btn-primary">
            <i class='bx bx-plus'></i> Create Event
        </button>
    </div>

    <!-- ✅ CREATE EVENT MODAL -->
    <div id="createModal" class="modal-overlay hidden">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title">Create New Event</h2>
          <button id="closeCreateModal" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
          <form id="createEventForm">
            <div class="form-group">
              <label class="form-label">Event Name</label>
              <input type="text" name="event_name" class="form-input" required>
            </div>

            <div class="form-group">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-input form-textarea" required></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Location</label>
              <input type="text" name="location" class="form-input" required>
            </div>

            <div class="form-group">
              <label class="form-label">Start Date</label>
              <input type="date" name="start_date" class="form-input" required>
            </div>

            <div class="form-group">
              <label class="form-label">End Date</label>
              <input type="date" name="end_date" class="form-input" required>
            </div>

            <div class="form-group">
              <label class="form-label">Audience</label>
              <input type="text" name="audience" class="form-input" required>
            </div>

            <div class="form-group">
              <label class="form-label">Start Time</label>
              <input type="time" name="start_time" class="form-input">
            </div>

            <div class="form-group">
              <label class="form-label">End Time</label>
              <input type="time" name="end_time" class="form-input">
            </div>

            <button type="submit" class="form-submit">Create Event</button>
          </form>
        </div>
      </div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Event ID</th>
            <th>Event Details</th>
            <th>Description</th>
            <th>Date & Time</th>
            <th>Audience</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td>#E<?= esc($event['id']) ?></td>
                    <td>
                        <div class="event-title"><?= esc($event['event_name']) ?></div>
                        <div class="event-venue"><?= esc($event['location']) ?></div>
                    </td>
                    <td>
                        <span class="category-badge category-academic">
                            <?= esc($event['description']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="event-time">
                            <?php
                                $startDate = date('M d, Y', strtotime($event['start_date']));
                                $endDate   = date('M d, Y', strtotime($event['end_date']));
                                $startTime = $event['start_time'] ? date('g:i A', strtotime($event['start_time'])) : '';
                                $endTime   = $event['end_time'] ? date('g:i A', strtotime($event['end_time'])) : '';
                            ?>
                            <?php if ($startDate === $endDate): ?>
                                <span class="event-date"><?= $startDate ?></span><br>
                                <?php if ($startTime && $endTime): ?>
                                    <span><?= $startTime ?> - <?= $endTime ?></span>
                                <?php elseif ($startTime): ?>
                                    <span><?= $startTime ?></span>
                                <?php else: ?>
                                    <span>All Day Event</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="event-date"><?= $startDate ?> - <?= $endDate ?></span><br>
                                <?php if ($startTime && $endTime): ?>
                                    <span><?= $startTime ?> - <?= $endTime ?></span>
                                <?php elseif ($startTime): ?>
                                    <span><?= $startTime ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= esc(ucwords($event['audience'])) ?></td>
                    <td>
                        <span class="status-badge 
                            <?= strtotime($event['start_date']) > time() ? 'status-upcoming' : 
                               (strtotime($event['end_date']) < time() ? 'status-completed' : 'status-ongoing') ?>">
                            <?= strtotime($event['start_date']) > time() ? 'Upcoming' : 
                               (strtotime($event['end_date']) < time() ? 'Completed' : 'Ongoing') ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn view" data-id="<?= esc($event['id']) ?>">
                                <i class='bx bx-show'></i>
                            </button>
                            <button class="action-btn edit" data-id="<?= esc($event['id']) ?>">
                                <i class='bx bx-edit'></i>
                            </button>
                            <button class="action-btn delete" data-id="<?= esc($event['id']) ?>">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align:center;">No events found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- ✅ EDIT EVENT MODAL -->
<div id="editModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title">Edit Event</h2>
      <button id="closeEditModal" class="modal-close">&times;</button>
    </div>
    <div class="modal-body">
      <form id="editEventForm">
        <input type="hidden" name="id" id="editEventId">

        <div class="form-group">
          <label class="form-label">Event Name</label>
          <input type="text" name="event_name" id="editEventName" class="form-input" required>
        </div>

        <div class="form-group">
          <label class="form-label">Description</label>
          <textarea name="description" id="editEventDescription" class="form-input form-textarea"></textarea>
        </div>

        <div class="form-group">
          <label class="form-label">Location</label>
          <input type="text" name="location" id="editEventLocation" class="form-input">
        </div>

        <div class="form-group">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" id="editStartDate" class="form-input" required>
        </div>

        <div class="form-group">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" id="editEndDate" class="form-input" required>
        </div>

        <div class="form-group">
          <label class="form-label">Audience</label>
          <input type="text" name="audience" id="editEventAudience" class="form-input">
        </div>

        <button type="submit" class="form-submit">
          Save Changes
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Updated View Modal HTML -->
<div id="eventModal" class="modal-overlay">
  <div class="modal-content">
    <div class="modal-header">
      <h2 class="modal-title" id="modalEventName"></h2>
      <button id="closeModal" class="modal-close">&times;</button>
    </div>
    <div class="modal-body">
      <div class="event-details">
        <div class="detail-item">
          <span class="detail-icon">📄</span>
          <div class="detail-content">
            <div class="detail-label">Description</div>
            <div class="detail-value" id="modalEventDescription"></div>
          </div>
        </div>

        <div class="detail-item">
          <span class="detail-icon">📅</span>
          <div class="detail-content">
            <div class="detail-label">Start Date & Time</div>
            <div class="detail-value">
              <span id="modalStartDate"></span> <span id="modalStartTime"></span>
            </div>
          </div>
        </div>

        <div class="detail-item">
          <span class="detail-icon">📅</span>
          <div class="detail-content">
            <div class="detail-label">End Date & Time</div>
            <div class="detail-value">
              <span id="modalEndDate"></span> <span id="modalEndTime"></span>
            </div>
          </div>
        </div>

        <div class="detail-item">
          <span class="detail-icon">📍</span>
          <div class="detail-content">
            <div class="detail-label">Location</div>
            <div class="detail-value" id="modalLocation"></div>
          </div>
        </div>

        <div class="detail-item">
          <span class="detail-icon">👥</span>
          <div class="detail-content">
            <div class="detail-label">Audience</div>
            <div class="detail-value" id="modalAudience"></div>
          </div>
        </div>

        <div id="modalFileWrapper" class="detail-item hidden">
          <span class="detail-icon">📎</span>
          <div class="detail-content">
            <div class="detail-label">Attachment</div>
            <div class="file-attachment">
              <img id="modalFile" src="" alt="Event Attachment">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <div class="timestamp-info">
        <span>Created: <span id="modalCreatedAt"></span></span>
        <span>Updated: <span id="modalUpdatedAt"></span></span>
      </div>
    </div>
  </div>
</div>

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
        }
    }

document.addEventListener('DOMContentLoaded', function () {
    // Enhanced modal animation functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }

    // EVENT ACTIONS (View, Edit, Delete)
    // VIEW BUTTON
    document.querySelectorAll('.action-btn.view').forEach(btn => {
        btn.addEventListener('click', function () {
            const eventId = this.dataset.id;

            fetch(`/admin/events/view/${eventId}`)
                .then(res => res.json())
                .then(event => {
                    if (event.error) {
                        alert(event.error);
                        return;
                    }

                    // Fill modal content
                    document.getElementById('modalEventName').innerText = event.event_name;
                    document.getElementById('modalEventDescription').innerText = event.description;
                    document.getElementById('modalStartDate').innerText = event.start_date;
                    document.getElementById('modalStartTime').innerText = event.start_time ?? '';
                    document.getElementById('modalEndDate').innerText = event.end_date;
                    document.getElementById('modalEndTime').innerText = event.end_time ?? '';
                    document.getElementById('modalLocation').innerText = event.location;
                    document.getElementById('modalAudience').innerText = event.audience;
                    document.getElementById('modalCreatedAt').innerText = event.created_at ?? '—';
                    document.getElementById('modalUpdatedAt').innerText = event.updated_at ?? '—';

                    // Handle attachment
                    if (event.file_url) {
                        document.getElementById('modalFile').src = event.file_url;
                        document.getElementById('modalFileWrapper').classList.remove('hidden');
                    } else {
                        document.getElementById('modalFileWrapper').classList.add('hidden');
                    }

                    openModal('eventModal');
                })
                .catch(error => {
                    console.error('Error fetching event:', error);
                    alert('Error loading event details');
                });
        });
    });

    // CLOSE VIEW MODAL
    document.getElementById('closeModal').addEventListener('click', () => {
        closeModal('eventModal');
    });

    // Close modal when clicking background
    document.getElementById('eventModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal('eventModal');
        }
    });

    // EDIT BUTTON
    document.querySelectorAll('.action-btn.edit').forEach(btn => {
        btn.addEventListener('click', function () {
            const eventId = this.dataset.id;
            fetch(`/admin/events/view/${eventId}`)
                .then(res => res.json())
                .then(event => {
                    if (event.error) {
                        alert(event.error);
                        return;
                    }
                    
                    document.getElementById('editEventId').value = event.id;
                    document.getElementById('editEventName').value = event.event_name;
                    document.getElementById('editEventDescription').value = event.description;
                    document.getElementById('editEventLocation').value = event.location;
                    document.getElementById('editStartDate').value = event.start_date;
                    document.getElementById('editEndDate').value = event.end_date;
                    document.getElementById('editEventAudience').value = event.audience;
                    
                    openModal('editModal');
                })
                .catch(error => {
                    console.error('Error fetching event:', error);
                    alert('Error loading event details');
                });
        });
    });

    // CLOSE EDIT MODAL
    document.getElementById('closeEditModal').addEventListener('click', () => {
        closeModal('editModal');
    });

    // Close edit modal when clicking background
    document.getElementById('editModal').addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal('editModal');
        }
    });

    // EDIT FORM SUBMISSION
    document.getElementById('editEventForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector('.form-submit');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        fetch(`/admin/events/update`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            alert(response.message);
            if (response.success) {
                closeModal('editModal');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error updating event:', error);
            alert('Error updating event');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });

    // DELETE BUTTON
    document.querySelectorAll('.action-btn.delete').forEach(btn => {
        btn.addEventListener('click', function () {
            const eventName = this.closest('tr').querySelector('.event-title').textContent;
            
            if (confirm(`Are you sure you want to delete the event "${eventName}"? This action cannot be undone.`)) {
                fetch(`/admin/events/delete/${this.dataset.id}`, {
                    method: 'DELETE',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(response => {
                    alert(response.message);
                    if (response.success) location.reload();
                })
                .catch(error => {
                    console.error('Error deleting event:', error);
                    alert('Error deleting event');
                });
            }
        });
    });

    // ✅ CREATE EVENT MODAL
   // ✅ CREATE EVENT MODAL
const openCreateBtn = document.getElementById("openCreateModal");
const closeCreateBtn = document.getElementById("closeCreateModal");
const createModal = document.getElementById("createModal");

if (openCreateBtn && closeCreateBtn && createModal) {
    openCreateBtn.addEventListener("click", () => openModal("createModal"));
    closeCreateBtn.addEventListener("click", () => closeModal("createModal"));
    createModal.addEventListener("click", (e) => {
        if (e.target === createModal) closeModal("createModal");
    });

    // Handle form submit
    document.getElementById("createEventForm").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = this.querySelector(".form-submit");
        const originalText = submitBtn.textContent;

        submitBtn.textContent = "Creating...";
        submitBtn.disabled = true;

        fetch(`/events/store`, {   // ✅ CHANGED URL
            method: "POST",
            body: formData
        })
        .then(res => {
            if (res.redirected) {
                // store() uses redirect, so handle it
                window.location.href = res.url;
                return;
            }
            return res.json();
        })
        .then(response => {
            if (!response) return;
            alert(response.message || "Event created successfully!");
            if (response.success) {
                closeModal("createModal");
                location.reload();
            }
        })
        .catch(err => {
            console.error("Error creating event:", err);
            alert("Failed to create event.");
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
}


    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const viewModal = document.getElementById('eventModal');
            const editModal = document.getElementById('editModal');
            const createModal = document.getElementById('createModal');
            
            if (viewModal?.classList.contains('show')) {
                closeModal('eventModal');
            }
            if (editModal?.classList.contains('show')) {
                closeModal('editModal');
            }
            if (createModal?.classList.contains('show')) {
                closeModal('createModal');
            }
        }
    });
});

// Search functionality
document.querySelector('.action-bar .search-box input')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Filter buttons functionality
document.querySelectorAll('.filter-buttons .btn-outline')?.forEach(btn => {
    btn.addEventListener('click', function() {
        console.log('Filter clicked:', this.textContent.trim());
    });
});

// Export functionality
document.querySelector('.btn-primary')?.addEventListener('click', function() {
    if (this.textContent.includes('Export')) {
        console.log('Exporting data...');
    }
});
</script>

</body>
</html>
