

<?php
// Calculate statistics from appointments data
$stats = [
    'pending' => 0,
    'approved' => 0,
    'completed' => 0,
    'cancelled' => 0
];

if (!empty($appointments)) {
    foreach ($appointments as $apt) {
        $status = strtolower($apt['status']);
        if (isset($stats[$status])) {
            $stats[$status]++;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KARAMAY - My Appointments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
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

    /* Sidebar Logo Styles */
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
  visibility: hidden;
}

/* Calendar Styles */
.calendar-container {
  padding: 20px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.simple-calendar {
  width: 100%;
  max-width: 800px;
  margin: 0 auto;
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  padding: 0 10px;
}

.calendar-nav {
  background: #3b82f6;
  color: white;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
}

.calendar-nav:hover {
  background: #2563eb;
}

.calendar-grid {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.calendar-days {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  background: #f8fafc;
}

.day-header {
  padding: 15px 10px;
  text-align: center;
  font-weight: 600;
  color: #374151;
  border-right: 1px solid #e5e7eb;
  font-size: 0.9rem;
}

.day-header:last-child {
  border-right: none;
}

.days-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  min-height: 200px;
}

.calendar-day {
  padding: 20px;
  border-right: 1px solid #e5e7eb;
  border-bottom: 1px solid #e5e7eb;
  min-height: 80px;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6b7280;
}

/* Search and Filter Styles */
.search-filters {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
  flex-wrap: wrap;
  align-items: center;
  padding: 20px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
}

.search-input {
  position: relative;
  flex: 1;
  min-width: 300px;
}

.search-input input {
  width: 100%;
  padding: 12px 45px 12px 15px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 14px;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.search-input input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-input i {
  position: absolute;
  right: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #6b7280;
  font-size: 18px;
}

.filter-select {
  padding: 12px 15px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  background: white;
  font-size: 14px;
  cursor: pointer;
  min-width: 150px;
  transition: border-color 0.3s ease;
}

.filter-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Status Badge Styles */
.status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: inline-block;
}

.status-pending {
  background: #fef3c7;
  color: #92400e;
  border: 1px solid #f9a8d4;
}

.status-approved {
  background: #dbeafe;
  color: #1e40af;
  border: 1px solid #93c5fd;
}

.status-completed {
  background: #d1fae5;
  color: #065f46;
  border: 1px solid #86efac;
}

.status-cancelled {
  background: #fee2e2;
  color: #991b1b;
  border: 1px solid #fca5a5;
}

/* Stats Grid Styles */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 24px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  border: 1px solid #e5e7eb;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  position: relative;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
}

.stat-card.pending::before {
  background: #f59e0b;
}

.stat-card.approved::before {
  background: #3b82f6;
}

.stat-card.completed::before {
  background: #10b981;
}

.stat-card.cancelled::before {
  background: #ef4444;
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
      justify-content: between;
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
      flex: 1;
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
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .stat-card.pending {
      border-left-color: var(--warning-color);
    }

    .stat-card.approved {
      border-left-color: var(--success-color);
    }

    .stat-card.completed {
      border-left-color: var(--info-color);
    }

    .stat-card.cancelled {
      border-left-color: var(--danger-color);
    }

    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
      margin-bottom: 15px;
    }

    .stat-card.pending .stat-icon {
      background: linear-gradient(135deg, #fbbf24, #f59e0b);
    }

    .stat-card.approved .stat-icon {
      background: linear-gradient(135deg, #34d399, #10b981);
    }

    .stat-card.completed .stat-icon {
      background: linear-gradient(135deg, #60a5fa, #3b82f6);
    }

    .stat-card.cancelled .stat-icon {
      background: linear-gradient(135deg, #f87171, #ef4444);
    }

    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 5px;
    }

    .stat-label {
      color: #64748b;
      font-size: 0.9rem;
      font-weight: 500;
    }

    .card-container {
      background: white;
      border-radius: 15px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      transition: box-shadow 0.3s ease;
    }

    .card-container:hover {
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid var(--light-color);
    }

    .card-title {
      font-size: 1.4rem;
      font-weight: 600;
      color: var(--dark-color);
      display: flex;
      align-items: center;
    }

    .card-title i {
      margin-right: 10px;
      color: var(--primary-color);
    }

    .search-filters {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
      flex-wrap: wrap;
      align-items: center;
    }

    .search-input {
      flex: 1;
      min-width: 250px;
      position: relative;
    }

    .search-input input {
      width: 100%;
      padding: 12px 45px 12px 15px;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    .search-input input:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
    }

    .search-input i {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #64748b;
    }

    .filter-select {
      padding: 12px 15px;
      border: 2px solid #e2e8f0;
      border-radius: 10px;
      font-size: 0.95rem;
      background: white;
      cursor: pointer;
      transition: border-color 0.3s ease;
    }

    .filter-select:focus {
      border-color: var(--primary-color);
      outline: none;
    }

    .btn {
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      color: white;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(30, 64, 175, 0.3);
    }

    .btn-success {
      background: linear-gradient(135deg, var(--success-color), #34d399);
      color: white;
    }

    .btn-warning {
      background: linear-gradient(135deg, var(--warning-color), #fbbf24);
      color: white;
    }

    .btn-danger {
      background: linear-gradient(135deg, var(--danger-color), #f87171);
      color: white;
    }

    .btn-info {
      background: linear-gradient(135deg, var(--info-color), #60a5fa);
      color: white;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Table Styles */
    .table-container {
      overflow-x: auto;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
    }

    .appointments-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0;
    }

    .appointments-table th {
      background: linear-gradient(135deg, #f8fafc, #f1f5f9);
      color: var(--dark-color);
      font-weight: 600;
      padding: 18px 16px;
      text-align: left;
      font-size: 0.9rem;
      border-bottom: 2px solid #e2e8f0;
      white-space: nowrap;
    }

    .appointments-table td {
      padding: 16px;
      border-bottom: 1px solid #f1f5f9;
      font-size: 0.9rem;
      vertical-align: middle;
    }

    .appointments-table tr:hover {
      background: rgba(30, 64, 175, 0.05);
    }

    .appointments-table tr:last-child td {
      border-bottom: none;
    }

    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-pending {
      background: rgba(245, 158, 11, 0.1);
      color: #d97706;
      border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .status-approved {
      background: rgba(16, 185, 129, 0.1);
      color: #059669;
      border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .status-completed {
      background: rgba(59, 130, 246, 0.1);
      color: #2563eb;
      border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .status-cancelled {
      background: rgba(239, 68, 68, 0.1);
      color: #dc2626;
      border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .action-buttons {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .action-buttons .btn {
      font-size: 0.8rem;
      padding: 6px 12px;
    }

    /* Calendar Styles */
    .fc {
      font-family: 'Inter', sans-serif;
    }

    .fc-toolbar {
      margin-bottom: 20px;
    }

    .fc-button-primary {
      background: var(--primary-color);
      border-color: var(--primary-color);
      border-radius: 8px;
      font-weight: 500;
    }

    .fc-button-primary:hover {
      background: #1e40af;
      border-color: #1e40af;
    }

    .fc-event {
      border-radius: 6px;
      border: none;
      padding: 2px 6px;
      font-size: 0.85rem;
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

      .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
      }

      .card-container {
        padding: 20px;
        margin-bottom: 20px;
      }

      .search-filters {
        flex-direction: column;
        align-items: stretch;
      }

      .search-input {
        min-width: auto;
      }

      .appointments-table th,
      .appointments-table td {
        padding: 12px 8px;
        font-size: 0.8rem;
      }

      .action-buttons {
        flex-direction: column;
      }

      .action-buttons .btn {
        width: 100%;
        justify-content: center;
      }
    }

    @media (max-width: 480px) {
      .page-title {
        font-size: 1.2rem;
      }

      .stat-card {
        padding: 20px;
      }

      .stat-value {
        font-size: 1.5rem;
      }
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

    /* Smooth Animations */
    .fade-in {
      animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
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
        <a href="/user/userdashboard" class="nav-link">
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
        <a href="/user/view-appointments" class="nav-link active">
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
      <button class="menu-toggle" id="menuToggle">
        <i class='bx bx-menu'></i>
      </button>
      <div class="page-title">
        <i class='bx bx-calendar-check'></i>
        My Appointments
      </div>
      
      
    </div>

    <!-- Content Area -->
    <div class="content-area">
    <!-- Statistics Cards -->
    <div class="stats-grid fade-in">
        <div class="stat-card pending">
            <div class="stat-icon">
                <i class='bx bx-time-five'></i>
            </div>
            <div class="stat-value" id="pendingCount"><?= $stats['pending'] ?></div>
            <div class="stat-label">Pending Appointments</div>
        </div>
        <div class="stat-card approved">
            <div class="stat-icon">
                <i class='bx bx-check-circle'></i>
            </div>
            <div class="stat-value" id="approvedCount"><?= $stats['approved'] ?></div>
            <div class="stat-label">Approved Appointments</div>
        </div>
        <div class="stat-card completed">
            <div class="stat-icon">
                <i class='bx bx-calendar-check'></i>
            </div>
            <div class="stat-value" id="completedCount"><?= $stats['completed'] ?></div>
            <div class="stat-label">Completed Appointments</div>
        </div>
        <div class="stat-card cancelled">
            <div class="stat-icon">
                <i class='bx bx-x-circle'></i>
            </div>
            <div class="stat-value" id="cancelledCount"><?= $stats['cancelled'] ?></div>
            <div class="stat-label">Cancelled Appointments</div>
        </div>
    </div>

    <!-- Calendar Section -->
    <div class="card-container fade-in">
        <div class="card-header">
            <div class="card-title">
                <i class='bx bx-calendar'></i>
                Appointments Calendar
            </div>
            <div>
                <button class="btn btn-primary" id="newAppointmentBtn">
                    <i class='bx bx-plus'></i>
                    New Appointment
                </button>
            </div>
        </div>
        <div id="calendar" class="calendar-container">
            <!-- Simple calendar placeholder - you can integrate with FullCalendar.js or similar -->
            <div class="simple-calendar">
                <div class="calendar-header">
                    <button id="prevMonth" class="calendar-nav">&lt;</button>
                    <h3 id="currentMonth"><?= date('F Y') ?></h3>
                    <button id="nextMonth" class="calendar-nav">&gt;</button>
                </div>
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Calendar will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="card-container fade-in">
        <div class="card-header">
            <div class="card-title">
                <i class='bx bx-list-ul'></i>
                Appointments List
            </div>
            <div>
                <button class="btn btn-primary" id="refreshBtn">
                    <i class='bx bx-refresh'></i>
                    Refresh
                </button>
            </div>
        </div>

        <div class="search-filters">
            <div class="search-input">
                <input type="text" placeholder="Search appointments by name, purpose..." id="searchInput">
                <i class='bx bx-search'></i>
            </div>
            <select class="filter-select" id="statusFilter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select class="filter-select" id="dateFilter">
                <option value="">All Dates</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>

<div class="table-container">
  <table class="appointments-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date</th>
        <th>Time</th>
        <th>Status</th>
        <th>Purpose/Concern</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="appointmentsTableBody">
      <?php if (!empty($appointments)): ?>
        <?php foreach ($appointments as $apt): ?>
          <tr>
            <td><?= 'APT-' . str_pad($apt['id'], 3, '0', STR_PAD_LEFT) ?></td>
            <td><?= esc($apt['fullname']) ?></td>
            <td><?= date('M d, Y', strtotime($apt['appointment_date'])) ?></td>
            <td><?= date('h:i A', strtotime($apt['appointment_time'])) ?></td>
            <td><span class="status-badge status-<?= strtolower($apt['status']) ?>"><?= esc($apt['status']) ?></span></td>
            <td><?= esc($apt['purpose']) ?></td>
            <td>
              <div class="action-buttons">
                <?php if ($apt['status'] === 'Pending'): ?>
                  <button class="btn btn-warning btn-sm">
                    <i class='bx bx-edit'></i>
                    Reschedule
                  </button>
                  <button class="btn btn-danger btn-sm">
                    <i class='bx bx-x'></i>
                    Cancel
                  </button>
                <?php elseif ($apt['status'] === 'Approved'): ?>
                  <button class="btn btn-danger btn-sm">
                    <i class='bx bx-x'></i>
                    Cancel
                  </button>
                <?php elseif ($apt['status'] === 'Completed'): ?>
                  <button class="btn btn-info btn-sm">
                    <i class='bx bx-info-circle'></i>
                    View Details
                  </button>
                  <button class="btn btn-success btn-sm">
                    <i class='bx bx-download'></i>
                    Download
                  </button>
                <?php else: ?>
                  <button class="btn btn-info btn-sm">
                    <i class='bx bx-info-circle'></i>
                    View Details
                  </button>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
            <i class='bx bx-calendar-x' style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
            <h3>No appointments found</h3>
            <p>You don't have any appointments scheduled yet.</p>
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div style="margin-top: 20px; text-align: center; color: #64748b; font-size: 0.9rem;">
  <?php if (!empty($appointments)): ?>
    Showing <?= count($appointments) ?> of <?= $totalAppointments ?? count($appointments) ?> appointments
    <?php if (($totalAppointments ?? 0) > count($appointments)): ?>
      <button class="btn btn-primary" style="margin-left: 20px;">
        <i class='bx bx-plus'></i>
        Load More
      </button>
    <?php endif; ?>
  <?php endif; ?>
</div>

  <!-- Mobile Overlay -->
  <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileSidebar()"></div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

  <script>
    // DOM Elements
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const menuToggle = document.getElementById('menuToggle');
    const userDropdown = document.getElementById('userDropdown');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const dateFilter = document.getElementById('dateFilter');
    const refreshBtn = document.getElementById('refreshBtn');
    const mobileOverlay = document.getElementById('mobileOverlay');

    // Mobile Check
    function isMobile() {
      return window.innerWidth <= 768;
    }

    // Sidebar Toggle
    menuToggle.addEventListener('click', function() {
      if (isMobile()) {
        sidebar.classList.toggle('mobile-open');
        mobileOverlay.style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
      } else {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
      }
    });

    // Close mobile sidebar
    function closeMobileSidebar() {
      if (isMobile()) {
        sidebar.classList.remove('mobile-open');
        mobileOverlay.style.display = 'none';
      }
    }

    // User Dropdown Toggle
    userDropdown.addEventListener('click', function(e) {
      e.stopPropagation();
      dropdownMenu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
      if (!userDropdown.contains(e.target)) {
        dropdownMenu.classList.remove('show');
      }
    });

    // Search Functionality
    searchInput.addEventListener('input', function() {
      filterAppointments();
    });

    statusFilter.addEventListener('change', function() {
      filterAppointments();
    });

    dateFilter.addEventListener('change', function() {
      filterAppointments();
    });

    function filterAppointments() {
      const searchTerm = searchInput.value.toLowerCase();
      const selectedStatus = statusFilter.value;
      const selectedDate = dateFilter.value;
      const rows = document.querySelectorAll('#appointmentsTableBody tr');

      rows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const purpose = row.cells[5].textContent.toLowerCase();
        const status = row.cells[4].textContent.trim();
        const appointmentDate = row.cells[2].textContent;

        let showRow = true;

        // Search filter
        if (searchTerm && !name.includes(searchTerm) && !purpose.includes(searchTerm)) {
          showRow = false;
        }

        // Status filter
        if (selectedStatus && !status.toLowerCase().includes(selectedStatus.toLowerCase())) {
          showRow = false;
        }

        // Date filter (simplified logic)
        if (selectedDate && selectedDate !== '') {
          const today = new Date();
          const appointDate = new Date(appointmentDate);
          
          switch(selectedDate) {
            case 'today':
              if (appointDate.toDateString() !== today.toDateString()) {
                showRow = false;
              }
              break;
            case 'week':
              const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
              if (appointDate < weekAgo || appointDate > today) {
                showRow = false;
              }
              break;
            case 'month':
              if (appointDate.getMonth() !== today.getMonth() || appointDate.getFullYear() !== today.getFullYear()) {
                showRow = false;
              }
              break;
          }
        }

        row.style.display = showRow ? '' : 'none';
      });
    }

    // Refresh Button
    refreshBtn.addEventListener('click', function() {
      const icon = refreshBtn.querySelector('i');
      icon.classList.add('bx-spin');
      
      // Simulate loading
      setTimeout(() => {
        icon.classList.remove('bx-spin');
        // You can add actual refresh logic here
        console.log('Appointments refreshed');
      }, 1000);
    });

    // Window resize handler
    window.addEventListener('resize', function() {
      if (!isMobile() && sidebar.classList.contains('mobile-open')) {
        sidebar.classList.remove('mobile-open');
        mobileOverlay.style.display = 'none';
      }
    });

    // Initialize Calendar
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 500,
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
          today: 'Today',
          month: 'Month',
          week: 'Week',
          day: 'Day'
        },
        events: [
          {
            title: 'Business License (Pending)',
            start: '2025-01-15T10:00:00',
            color: '#f59e0b',
            textColor: '#ffffff'
          },
          {
            title: 'Tax Consultation (Approved)',
            start: '2025-01-12T14:30:00',
            color: '#10b981',
            textColor: '#ffffff'
          },
          {
            title: 'Permit Application (Completed)',
            start: '2025-01-08T09:15:00',
            color: '#3b82f6',
            textColor: '#ffffff'
          },
          {
            title: 'Document Review (Approved)',
            start: '2025-01-20T11:00:00',
            color: '#10b981',
            textColor: '#ffffff'
          },
          {
            title: 'Legal Consultation (Pending)',
            start: '2025-01-22T15:30:00',
            color: '#f59e0b',
            textColor: '#ffffff'
          }
        ],
        eventClick: function(info) {
          alert('Appointment: ' + info.event.title + '\nDate: ' + info.event.start.toLocaleDateString());
        },
        eventMouseEnter: function(info) {
          info.el.style.transform = 'scale(1.05)';
          info.el.style.transition = 'transform 0.2s ease';
        },
        eventMouseLeave: function(info) {
          info.el.style.transform = 'scale(1)';
        }
      });
      
      calendar.render();
    });

    // Add smooth scrolling to navigation links
    document.querySelectorAll('.nav-link').forEach(link => {
      link.addEventListener('click', function(e) {
        // Add loading effect
        const icon = this.querySelector('i');
        if (icon) {
          icon.style.transform = 'rotate(360deg)';
          setTimeout(() => {
            icon.style.transform = 'rotate(0deg)';
          }, 300);
        }
      });
    });

    // Action button handlers
    document.addEventListener('click', function(e) {
      if (e.target.closest('.btn-warning')) {
        // Reschedule logic
        alert('Reschedule appointment functionality would be implemented here');
      } else if (e.target.closest('.btn-danger')) {
        // Cancel logic
        if (confirm('Are you sure you want to cancel this appointment?')) {
          alert('Cancel appointment functionality would be implemented here');
        }
      } else if (e.target.closest('.btn-info')) {
        // View details logic
        alert('View appointment details functionality would be implemented here');
      } else if (e.target.closest('.btn-success')) {
        // Download logic
        alert('Download appointment document functionality would be implemented here');
      }
    });

    // Add loading states for buttons
    document.querySelectorAll('.btn').forEach(btn => {
      btn.addEventListener('click', function() {
        if (!this.classList.contains('loading')) {
          const originalContent = this.innerHTML;
          this.classList.add('loading');
          this.innerHTML = '<span class="loading-spinner"></span> Loading...';
          
          setTimeout(() => {
            this.classList.remove('loading');
            this.innerHTML = originalContent;
          }, 1500);
        }
      });
    });

    // Initialize tooltips and animations
    function initializeAnimations() {
      // Fade in elements on scroll
      const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
      };

      const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, observerOptions);

      document.querySelectorAll('.card-container, .stat-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
      });
    }

    // Initialize on load
    window.addEventListener('load', function() {
      initializeAnimations();
    });

    // Add custom CSS for mobile overlay
    const style = document.createElement('style');
    style.textContent = `
      .mobile-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
      }

      @media (max-width: 768px) {
        .mobile-overlay.show {
          display: block;
        }
      }

      .loading {
        pointer-events: none;
        opacity: 0.7;
      }

      /* Custom animations */
      @keyframes slideIn {
        from {
          transform: translateX(-100%);
          opacity: 0;
        }
        to {
          transform: translateX(0);
          opacity: 1;
        }
      }

      .sidebar.mobile-open {
        animation: slideIn 0.3s ease-out;
      }

      /* Button hover effects */
      .btn:not(.loading):hover {
        transform: translateY(-2px);
      }

      .btn:not(.loading):active {
        transform: translateY(0);
      }

      /* Table row hover effect */
      .appointments-table tbody tr {
        transition: all 0.2s ease;
      }

      .appointments-table tbody tr:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      /* Status badge animation */
      .status-badge {
        transition: all 0.2s ease;
      }

      .status-badge:hover {
        transform: scale(1.05);
      }
    `;
    document.head.appendChild(style);

  </script>
</body>
</html>