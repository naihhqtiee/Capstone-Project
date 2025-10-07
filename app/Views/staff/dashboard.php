<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - CHRE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
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
    
    /* Layout */
    .layout { 
      display: flex; 
      min-height: 100vh; 
    }
    
    /* Sidebar Styles */
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
    }
    
    .logout-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
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
    
    /* Content Area */
    .content { 
      flex: 1;
      margin-left: var(--sidebar-width);
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .content.full { 
      margin-left: 0; 
    }
    
    /* Navbar */
    .navbar {
      background: white;
      border-bottom: 1px solid var(--border-color);
      padding: 1rem 1.5rem;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: var(--card-shadow);
    }
    
    .navbar-brand {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--text-color);
      margin: 0;
    }
    
    .search-box {
      position: relative;
      width: 300px;
    }
    
    .search-input {
      width: 100%;
      padding: 0.5rem 1rem 0.5rem 2.5rem;
      border: 1px solid var(--border-color);
      border-radius: 20px;
      font-size: 0.875rem;
      transition: all 0.2s ease;
    }
    
    .search-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }
    
    .search-icon {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
    }
    
    .navbar-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    
    .date-display {
      font-size: 0.875rem;
      color: var(--text-muted);
      padding: 0.5rem 1rem;
      background: #f8f9fc;
      border-radius: 8px;
    }
    
    /* Notifications */
    .notification-dropdown {
      position: relative;
      cursor: pointer;
    }
    
    .notification-bell {
      width: 40px;
      height: 40px;
      background: var(--primary-color);
      color: white;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.25rem;
      transition: all 0.2s ease;
      position: relative;
    }
    
    .notification-bell:hover {
      background: var(--primary-hover);
      transform: scale(1.05);
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
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.1); }
      100% { transform: scale(1); }
    }
    
    .notification-dropdown-menu {
      position: absolute;
      top: calc(100% + 0.5rem);
      right: 0;
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.12);
      width: 350px;
      max-height: 400px;
      overflow: hidden;
      z-index: 1000;
      display: none;
      border: 1px solid var(--border-color);
    }
    
    .notification-dropdown-menu.show {
      display: block;
      animation: slideDown 0.2s ease;
    }
    
    @keyframes slideDown {
      from { opacity: 0; transform: translateY(-8px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .notification-header {
      padding: 1rem;
      background: var(--primary-color);
      color: white;
      font-weight: 600;
      font-size: 0.9rem;
    }
    
    .notification-list {
      max-height: 300px;
      overflow-y: auto;
    }
    
    .notification-item {
      padding: 1rem;
      border-bottom: 1px solid #f1f3f4;
      transition: background 0.2s ease;
      cursor: pointer;
    }
    
    .notification-item:hover {
      background: #f8f9fc;
    }
    
    .notification-item.unread {
      background: #e3f2fd;
      border-left: 3px solid var(--primary-color);
    }
    
    .notification-content h6 {
      font-size: 0.875rem;
      font-weight: 600;
      margin: 0 0 0.25rem 0;
    }
    
    .notification-content p {
      font-size: 0.8rem;
      color: var(--text-muted);
      margin: 0 0 0.25rem 0;
    }
    
    .notification-time {
      font-size: 0.7rem;
      color: var(--text-muted);
    }
    
    .notification-footer {
      padding: 0.75rem;
      text-align: center;
      border-top: 1px solid var(--border-color);
    }
    
    /* Profile Dropdown */
    .profile-dropdown {
      position: relative;
    }
    .profile-dropdown-menu.show {
  display: block;
  animation: fadeInUp 0.2s ease;
}
    
    .profile-section {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
      border-radius: 8px;
      padding: 0.5rem 0.75rem;
      cursor: pointer;
      transition: all 0.2s ease;
      border: none;
    }
    
    .profile-section:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
    }
    
    .profile-avatar {
      width: 32px;
      height: 32px;
      background: rgba(255,255,255,0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.5rem;
    }
    
    .profile-info {
      color: white;
    }
    
    .profile-name {
      font-size: 0.875rem;
      font-weight: 600;
      line-height: 1.2;
    }
    
    .profile-role {
      font-size: 0.7rem;
      opacity: 0.9;
      line-height: 1;
    }
    
    .profile-dropdown-menu {
      display: none; /* hide by default */
      position: absolute;
      top: calc(100% + 0.5rem);
      right: 0;
      background: white;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.12);
      min-width: 200px;
      overflow: hidden;
      z-index: 1000;
      border: 1px solid var(--border-color);
    }
    
    .profile-dropdown-header {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
      color: white;
      padding: 1rem;
    }
    
    .profile-dropdown-header .profile-avatar {
      width: 40px;
      height: 40px;
      background: rgba(255,255,255,0.2);
    }
    
    .dropdown-item {
      padding: 0.75rem 1rem;
      font-size: 0.875rem;
      color: var(--text-color);
      text-decoration: none;
      display: flex;
      align-items: center;
      transition: background 0.2s ease;
    }
    
    .dropdown-item:hover {
      background: #f8f9fc;
      color: var(--primary-color);
    }
    
    .dropdown-item i {
      width: 20px;
      margin-right: 0.75rem;
    }
    
    .dropdown-divider {
      height: 1px;
      background: var(--border-color);
      margin: 0;
    }
    
    /* Main Content */
    .main-content {
      flex: 1;
      padding: 1.5rem;
    }
    
    /* Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    
    .stat-card { 
      background: white;
      border: none; 
      border-radius: 12px; 
      padding: 1.5rem;
      transition: all 0.2s ease;
      box-shadow: var(--card-shadow);
      position: relative;
      overflow: hidden;
    }
    
    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
    }
    
    .stat-card.primary::before { background: var(--primary-color); }
    .stat-card.warning::before { background: #ffc107; }
    .stat-card.success::before { background: #28a745; }
    .stat-card.info::before { background: #17a2b8; }
    
    .stat-card:hover { 
      transform: translateY(-2px); 
      box-shadow: var(--hover-shadow);
    }
    
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .stat-icon.primary { background: rgba(78, 115, 223, 0.1); color: var(--primary-color); }
    .stat-icon.warning { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
    .stat-icon.success { background: rgba(40, 167, 69, 0.1); color: #28a745; }
    .stat-icon.info { background: rgba(23, 162, 184, 0.1); color: #17a2b8; }
    
    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-color);
      margin-bottom: 0.25rem;
    }
    
    .stat-label {
      font-size: 0.9rem;
      color: var(--text-muted);
      font-weight: 500;
      margin-bottom: 0.5rem;
    }
    
    .stat-change {
      font-size: 0.8rem;
      font-weight: 500;
    }
    
    .stat-change.positive { color: #28a745; }
    .stat-change.neutral { color: var(--text-muted); }
    
    /* Cards */
    .card {
      background: white;
      border: none;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      transition: all 0.2s ease;
      overflow: hidden;
    }
    
    .card:hover {
      box-shadow: var(--hover-shadow);
    }
    
    .card-header {
      background: var(--primary-color);
      color: white;
      border: none;
      padding: 1rem 1.5rem;
      font-size: 0.9rem;
      font-weight: 600;
    }
    
    .card-body {
      padding: 1.5rem;
    }
    
    /* Metrics */
    .metrics-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    
    .metric-card {
      background: white;
      padding: 1.5rem;
      border-radius: 12px;
      text-align: center;
      box-shadow: var(--card-shadow);
      transition: all 0.2s ease;
    }
    
    .metric-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--hover-shadow);
    }
    
    .metric-icon {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    
    .metric-value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.25rem;
    }
    
    .metric-label {
      color: var(--text-muted);
      font-size: 0.875rem;
      font-weight: 500;
    }
    
    /* Quick Actions */
    .action-btn {
      width: 100%;
      padding: 0.75rem;
      border: none;
      border-radius: 8px;
      font-size: 0.875rem;
      font-weight: 500;
      transition: all 0.2s ease;
      margin-bottom: 0.5rem;
    }
    
    .action-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Activity Feed */
    .activity-feed {
      max-height: 400px;
      overflow-y: auto;
    }
    
    .activity-item {
      display: flex;
      padding: 1rem;
      border-bottom: 1px solid #f1f3f4;
      transition: background 0.2s ease;
    }
    
    .activity-item:hover {
      background: #f8f9fc;
    }
    
    .activity-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 0.75rem;
      flex-shrink: 0;
      font-size: 1rem;
    }
    
    .activity-content {
      flex: 1;
    }
    
    .activity-title {
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }
    
    .activity-description {
      font-size: 0.8rem;
      color: var(--text-muted);
      margin-bottom: 0.25rem;
    }
    
    .activity-time {
      font-size: 0.7rem;
      color: var(--text-muted);
    }
    
    /* Progress Bars */
    .progress {
      height: 8px;
      border-radius: 4px;
      background: #e9ecef;
      overflow: hidden;
      margin-bottom: 0.5rem;
    }
    
    .progress-bar {
      border-radius: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--primary-hover));
      transition: width 0.6s ease;
    }
    
    /* Calendar */
    .calendar-widget {
      background: white;
      border-radius: 12px;
      overflow: hidden;
    }
    
    .calendar-header {
      background: var(--primary-color);
      color: white;
      padding: 1rem;
      text-align: center;
      font-weight: 600;
    }
    
    .calendar-grid {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 1px;
      background: #e9ecef;
    }
    
    .calendar-day {
      background: white;
      padding: 0.5rem;
      text-align: center;
      min-height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: background 0.2s ease;
      font-size: 0.8rem;
    }
    
    .calendar-day:hover {
      background: #f8f9fc;
    }
    
    .calendar-day.today {
      background: var(--primary-color);
      color: white;
      font-weight: 600;
    }
    
    .calendar-day.has-event {
      position: relative;
    }
    
    .calendar-day.has-event::after {
      content: '';
      position: absolute;
      bottom: 2px;
      left: 50%;
      transform: translateX(-50%);
      width: 4px;
      height: 4px;
      background: #28a745;
      border-radius: 50%;
    }
    
    /* Timeline */
    .timeline {
      position: relative;
      padding-left: 1.5rem;
    }
    
    .timeline::before {
      content: '';
      position: absolute;
      left: 0.75rem;
      top: 0;
      bottom: 0;
      width: 2px;
      background: #e9ecef;
    }
    
    .timeline-item {
      position: relative;
      margin-bottom: 1.5rem;
    }
    
    .timeline-item::before {
      content: '';
      position: absolute;
      left: -1.25rem;
      top: 0.25rem;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--primary-color);
      border: 2px solid white;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .timeline-content {
      background: white;
      padding: 0.75rem;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
    }
    
    .timeline-title {
      font-size: 0.875rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }
    
    .timeline-time {
      font-size: 0.75rem;
      color: var(--text-muted);
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
      
      .search-box {
        width: 200px;
      }
      
      .notification-dropdown-menu {
        width: 300px;
      }
      
      .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      }
    }
    
    @media (max-width: 768px) {
      .main-content {
        padding: 1rem;
      }
      
      .search-box {
        display: none;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
      
      .metrics-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    
    @media (max-width: 576px) {
      .navbar {
        padding: 1rem;
      }
      
      .metrics-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Loading States */
    .loading {
      opacity: 0.6;
      pointer-events: none;
    }
    
    .spinner {
      display: inline-block;
      width: 1rem;
      height: 1rem;
      border: 2px solid transparent;
      border-top: 2px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
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
          <a class="nav-link active" href="dashboard">
            <i class='bx bx-grid-alt'></i>Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="opcr-checklist">
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
      <button class="logout-btn" onclick="logout()">
        <i class='bx bx-log-out me-2'></i>Logout
      </button>
    </div>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <!-- Navbar -->
    <nav class="navbar">
      <div class="d-flex align-items-center me-auto">
        <h1 class="navbar-brand me-4">Dashboard</h1>
        <div class="search-box">
          <i class='bx bx-search search-icon'></i>
          <input type="text" class="search-input" placeholder="Search...">
        </div>
      </div>
      
      <div class="navbar-actions">
        <div class="date-display">
          <i class='bx bx-calendar me-2'></i>
          <span id="currentDate"></span>
        </div>
        
        <!-- Enhanced Notification -->
<div class="notification-dropdown" onclick="toggleNotifications()">
  <div class="notification-bell">
    <i class='bx bx-bell'></i>
    <span class="notification-badge" id="notification-count">0</span>
  </div>

  <div class="notification-dropdown-menu" id="notificationDropdown">
    <div class="notification-header">
      <i class='bx bx-bell me-2'></i>Notifications
    </div>

    <div class="notification-list" id="notificationList">
      <!-- JS will inject notifications here -->
    </div>

    <div class="notification-footer">
      <button class="btn btn-primary btn-sm" onclick="viewAllNotifications()">View All Notifications</button>
    </div>
  </div>
</div>


        
        <!-- Enhanced Profile -->
        <div class="profile-dropdown" onclick="toggleProfile()">
          <div class="profile-section">
            <div class="d-flex align-items-center">
              <div class="profile-avatar">
                <i class="bx bx-user"></i>
              </div>
              <div class="profile-info">
                <div class="profile-name"><?= esc(session()->get('full_name')) ?></div>
                <div class="profile-role"><?= esc(session()->get('role')) ?></div>
              </div>
              <i class='bx bx-chevron-down ms-2'></i>
            </div>
          </div>
          <div class="profile-dropdown-menu" id="profileDropdown">
            <div class="profile-dropdown-header">
              <div class="d-flex align-items-center">
                <div class="profile-avatar me-3">
                  <i class="bx bx-user"></i>
                </div>
                <div>
                  <div class="profile-name"><?= esc(session()->get('full_name')) ?></div>
                  <small style="opacity: 0.9;"><?= esc(session()->get('email')) ?></small>
                </div>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#" onclick="viewProfile()">
              <i class="bx bx-user"></i>My Profile
            </a>
            <a class="dropdown-item" href="#" onclick="openSettings()">
              <i class="bx bx-cog"></i>Settings
            </a>
            <a class="dropdown-item" href="#" onclick="openHelp()">
              <i class="bx bx-help-circle"></i>Help & Support
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="#" onclick="logout()">
              <i class="bx bx-log-out"></i>Logout
            </a>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
      <!-- Statistics Cards -->
      <div class="stats-grid">
        <div class="stat-card primary">
          <div class="stat-icon primary">
            <i class='bx bx-message-rounded-error'></i>
          </div>
          <div class="stat-value" id="total-complaints"><?= esc($total_complaints ?? 0); ?></div>
          <div class="stat-label">Total Complaints</div>
          <div class="stat-change positive">+12% from last month</div>
        </div>
        
        <div class="stat-card warning">
          <div class="stat-icon warning">
            <i class='bx bx-time-five'></i>
          </div>
          <div class="stat-value" id="pending-complaints"><?= esc($pending_cases ?? 0); ?></div>
          <div class="stat-label">Pending Cases</div>
          <div class="stat-change neutral">Requires attention</div>
        </div>
        
        <div class="stat-card success">
          <div class="stat-icon success">
            <i class='bx bx-check-circle'></i>
          </div>
          <div class="stat-value" id="resolved-complaints"><?= esc($resolved) ?></div>
          <div class="stat-label">Resolved Cases</div>
          <div class="stat-change positive">94% resolution rate</div>
        </div>
        
        <div class="stat-card info">
          <div class="stat-icon info">
            <i class='bx bx-group'></i>
          </div>
          <div class="stat-value"><?= esc($totalStudents) ?></div>
          <div class="stat-label">Total Students</div>
          
        </div>
      </div>

      <!-- Performance Metrics -->
      

      <!-- Main Dashboard Row -->
      <div class="row">
        <!-- Complaints Analytics -->
          
        <!-- Complaints by Type -->
        <div class="col-md-8 mb-3">
          <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
              <h5 class="mb-0">Complaints by Category</h5>
              <button class="btn btn-sm btn-outline-light" onclick="showAllComplaints()">View All</button>
            </div>
            <div class="card-body bg-light">
              <canvas id="complaintsChart" height="200"></canvas>
            </div>
          </div>
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-4">
          <!-- Quick Actions -->
          <div class="card mb-4">
            <div class="card-header">
              <i class='bx bx-flash me-2'></i>
              Quick Actions
            </div>
            <div class="card-body">
              <button class="action-btn btn btn-primary" onclick="showComplaints('pending')">
                <i class='bx bx-time-five me-2'></i>View Pending Cases
              </button>
              <button class="action-btn btn btn-success" onclick="showComplaints('resolved')">
                <i class='bx bx-check-circle me-2'></i>View Resolved Cases
              </button>
              <button class="action-btn btn btn-info text-white" onclick="showComplaints('anonymous')">
                <i class='bx bx-user-x me-2'></i>Anonymous Cases
              </button>
              <button class="action-btn btn btn-warning text-dark" onclick="exportComplaints()">
                <i class='bx bx-download me-2'></i>Export Report
              </button>
            </div>
          </div>

          <!-- Calendar Widget -->
          <div class="card calendar-widget mb-4">
            <div class="calendar-header">
              <i class='bx bx-calendar me-2'></i>
              September 2025
            </div>
            <div class="p-2">
              <div class="calendar-grid">
                <div class="calendar-day text-muted fw-bold">S</div>
                <div class="calendar-day text-muted fw-bold">M</div>
                <div class="calendar-day text-muted fw-bold">T</div>
                <div class="calendar-day text-muted fw-bold">W</div>
                <div class="calendar-day text-muted fw-bold">T</div>
                <div class="calendar-day text-muted fw-bold">F</div>
                <div class="calendar-day text-muted fw-bold">S</div>
                <!-- Calendar days -->
                <div class="calendar-day">1</div>
                <div class="calendar-day">2</div>
                <div class="calendar-day">3</div>
                <div class="calendar-day">4</div>
                <div class="calendar-day has-event">5</div>
                <div class="calendar-day">6</div>
                <div class="calendar-day">7</div>
                <div class="calendar-day">8</div>
                <div class="calendar-day">9</div>
                <div class="calendar-day">10</div>
                <div class="calendar-day">11</div>
                <div class="calendar-day has-event">12</div>
                <div class="calendar-day">13</div>
                <div class="calendar-day">14</div>
                <div class="calendar-day">15</div>
                <div class="calendar-day">16</div>
                <div class="calendar-day">17</div>
                <div class="calendar-day">18</div>
                <div class="calendar-day">19</div>
                <div class="calendar-day">20</div>
                <div class="calendar-day">21</div>
                <div class="calendar-day">22</div>
                <div class="calendar-day">23</div>
                <div class="calendar-day">24</div>
                <div class="calendar-day">25</div>
                <div class="calendar-day">26</div>
                <div class="calendar-day">27</div>
                <div class="calendar-day today">28</div>
                <div class="calendar-day">29</div>
                <div class="calendar-day">30</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Second Row -->
      <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-8 mb-4">
          <div class="card">
            <div class="card-header">
              <i class='bx bx-history me-2'></i>
              Recent Activity
            </div>
            <div class="card-body p-0">
                  <div class="activity-feed" id="activityFeed">

                <div class="activity-item">
                  <div class="activity-icon bg-primary text-white">
                    <i class='bx bx-message'></i>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">New Complaint Filed</div>
                    <div class="activity-description">Student Maria Santos filed a complaint about library facilities - Case #CR-2024-089</div>
                    <div class="activity-time">2 minutes ago</div>
                  </div>
                </div>
                
                <div class="activity-item">
                  <div class="activity-icon bg-success text-white">
                    <i class='bx bx-check-circle'></i>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">Case Resolved</div>
                    <div class="activity-description">Complaint #CR-2024-087 about cafeteria services has been successfully resolved</div>
                    <div class="activity-time">1 hour ago</div>
                  </div>
                </div>
                
                <div class="activity-item">
                  <div class="activity-icon bg-warning text-white">
                    <i class='bx bx-calendar'></i>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">Appointment Scheduled</div>
                    <div class="activity-description">Meeting scheduled with John Reyes regarding ongoing case #CR-2024-086</div>
                    <div class="activity-time">3 hours ago</div>
                  </div>
                </div>
                
                <div class="activity-item">
                  <div class="activity-icon bg-info text-white">
                    <i class='bx bx-user-plus'></i>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">New Student Registered</div>
                    <div class="activity-description">Elena Rodriguez registered for counseling services</div>
                    <div class="activity-time">5 hours ago</div>
                  </div>
                </div>
                
                <div class="activity-item">
                  <div class="activity-icon bg-secondary text-white">
                    <i class='bx bx-file'></i>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">Report Generated</div>
                    <div class="activity-description">Weekly complaint summary report has been generated and is ready for review</div>
                    <div class="activity-time">1 day ago</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
          <!-- Complaint Categories -->
<div class="card">
  <div class="card-header bg-primary text-white">
    <h5 class="mb-0">Complaint Category</h5>
  </div>
  <div class="card-body bg-light">

<?php foreach ($complaintProgress as $type => $stats): ?>
  <div class="mb-2">
    <small class="fw-bold"><?= esc($type) ?> (<?= $stats['count'] ?>)</small>
    <div class="progress" style="height: 8px;">
      <div class="progress-bar" style="width: <?= $stats['percentage'] ?>%;">
        <?= $stats['percentage'] ?>%
      </div>
    </div>
  </div>
<?php endforeach; ?>


  </div>
</div>

          <!-- Today's Timeline -->
          <div class="card">
            <div class="card-header">
              <i class='bx bx-time me-2'></i>
              Today's Schedule
            </div>
            <div class="card-body">
              <div class="timeline">
                <div class="timeline-item">
                  <div class="timeline-content">
                    <div class="timeline-title">Morning Briefing</div>
                    <div class="timeline-time">9:00 AM - Review pending cases</div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="timeline-content">
                    <div class="timeline-title">Student Consultation</div>
                    <div class="timeline-time">11:00 AM - Case #CR-2024-089</div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="timeline-content">
                    <div class="timeline-title">Report Review</div>
                    <div class="timeline-time">2:00 PM - Weekly statistics</div>
                  </div>
                </div>
                
                <div class="timeline-item">
                  <div class="timeline-content">
                    <div class="timeline-title">Team Meeting</div>
                    <div class="timeline-time">4:00 PM - Weekly sync-up</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Global variables
  let notificationDropdownOpen = false;
  let profileDropdownOpen = false;
  let complaintsChart = null;

  // Initialize on DOM load
  document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    initializeChart();
    startRealTimeUpdates();
  });

  function initializeDashboard() {
    // Set current date
    const currentDate = new Date().toLocaleDateString('en-US', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
    document.getElementById('currentDate').textContent = currentDate;

    // Initialize notifications
    fetchNotifications();

    // Add click outside listeners
    document.addEventListener('click', handleOutsideClick);

    // Initialize sidebar toggle
    initializeSidebarToggle();
  }
   document.addEventListener("DOMContentLoaded", function () {
    const complaintCategory = <?= json_encode($complaintCategory ?? []); ?>;
    const ctx = document.getElementById('complaintsChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: Object.keys(complaintCategory),
        datasets: [{
          label: 'Complaints by Category',
          data: Object.values(complaintCategory),
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  });


  // Sidebar functionality
  function initializeSidebarToggle() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    
    sidebarToggle.addEventListener('click', () => {
      if (window.innerWidth <= 992) {
        sidebar.classList.toggle('show');
      } else {
        sidebar.classList.toggle('hide');
        content.classList.toggle('full');
      }
    });
  }

  // Notification functions
  function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    notificationDropdownOpen = !notificationDropdownOpen;
    
    if (notificationDropdownOpen) {
      dropdown.classList.add('show');
      profileDropdownOpen = false;
      document.getElementById('profileDropdown').classList.remove('show');
    } else {
      dropdown.classList.remove('show');
    }
  }

  function markAsRead(notificationElement) {
    if (notificationElement.classList.contains("unread")) {
      notificationElement.classList.remove('unread');
      updateNotificationCount();
    }

    // Add visual feedback
    notificationElement.style.opacity = '0.7';
    setTimeout(() => {
      notificationElement.style.opacity = '1';
    }, 300);
  }

  function updateNotificationCount() {
    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
    const badge = document.getElementById('notification-count');
    badge.textContent = unreadCount;
    badge.style.display = unreadCount > 0 ? 'flex' : 'none';
  }

  function viewAllNotifications() {
    console.log('Navigating to notifications page...');
    // window.location.href = '/staff/notifications';
  }

  // 🔹 Fetch live notifications from backend
// Fetch live notifications
async function loadNotifications() {
      try {
        const response = await fetch("<?= base_url('staff/notifications') ?>");
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


  // Profile functions
  function toggleProfile() {
    const dropdown = document.getElementById('profileDropdown');
    profileDropdownOpen = !profileDropdownOpen;

    if (profileDropdownOpen) {
      dropdown.classList.add('show');
      notificationDropdownOpen = false;
      document.getElementById('notificationDropdown').classList.remove('show');
    } else {
      dropdown.classList.remove('show');
    }
  }

  function viewProfile() { console.log('Opening profile...'); }
  function openSettings() { console.log('Opening settings...'); }
  function openHelp() { console.log('Opening help...'); }

  // Handle clicks outside dropdowns
  function handleOutsideClick(event) {
    if (!event.target.closest('.notification-dropdown') && notificationDropdownOpen) {
      document.getElementById('notificationDropdown').classList.remove('show');
      notificationDropdownOpen = false;
    }

    if (!event.target.closest('.profile-dropdown') && profileDropdownOpen) {
      document.getElementById('profileDropdown').classList.remove('show');
      profileDropdownOpen = false;
    }
  }

  // Chart initialization
  document.addEventListener("DOMContentLoaded", function () {
    const complaintCategory = <?= json_encode($complaintCategory ?? []); ?>;
    const ctx = document.getElementById('complaintsChart').getContext('2d');
    complaintsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: Object.keys(complaintCategory),
        datasets: [{
          label: 'Complaints by Category',
          data: Object.values(complaintCategory),
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  });

  // Chart period change
  function changeChartPeriod(period) {
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
      btn.classList.remove('btn-light', 'text-primary');
      btn.classList.add('btn-outline-light');
    });

    event.target.classList.remove('btn-outline-light');
    event.target.classList.add('btn-light', 'text-primary');

    let newData;
    switch(period) {
      case 'week': newData = [5, 3, 2, 1, 0]; break;
      case 'month': newData = [21, 14, 8, 4, 0]; break;
      case 'year': newData = [156, 98, 67, 34, 12]; break;
    }

    complaintsChart.data.datasets[0].data = newData;
    complaintsChart.update();
  }

  // Quick action functions
  function showComplaints(type) {
    const button = event.target;
    const originalHTML = button.innerHTML;

    button.classList.add('loading');
    button.innerHTML = `<span class="spinner me-2"></span>Loading...`;

    setTimeout(() => {
      button.classList.remove('loading');
      button.innerHTML = originalHTML;
      console.log(`Showing ${type} complaints`);
    }, 1500);
  }

  function exportComplaints() {
    const button = event.target;
    const originalHTML = button.innerHTML;

    button.classList.add('loading');
    button.innerHTML = `<span class="spinner me-2"></span>Exporting...`;

    setTimeout(() => {
      button.classList.remove('loading');
      button.innerHTML = originalHTML;
      console.log('Exporting complaints data...');
    }, 2000);
  }

  // Real-time updates
  function startRealTimeUpdates() {
    setInterval(updateStatistics, 30000);
  }

  function updateStatistics() {
    const totalComplaints = document.getElementById('total-complaints');
    const pendingComplaints = document.getElementById('pending-complaints');
    const resolvedComplaints = document.getElementById('resolved-complaints');

    [totalComplaints, pendingComplaints, resolvedComplaints].forEach(element => {
      element.style.transform = 'scale(1.05)';
      setTimeout(() => {
        element.style.transform = 'scale(1)';
      }, 200);
    });
  }

  // Calendar interaction
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.calendar-day:not(.text-muted)').forEach(day => {
      day.addEventListener('click', function() {
        document.querySelectorAll('.calendar-day').forEach(d => d.classList.remove('selected'));
        this.classList.add('selected');
        console.log(`Selected date: ${this.textContent}`);
      });
    });
  });

  // Logout function
function logout() {
  if (confirm('Are you sure you want to log out?')) {
    window.location.href = "<?= base_url('logout') ?>";
  }
}


  // Idle session tracking
  let idleTime = 0;
  let warningShown = false;
  const idleLimit = 30 * 60; // 30 mins
  const warningTime = 25 * 60; // warn at 25 mins

  function resetIdle() {
    idleTime = 0;
    warningShown = false;
  }

  setInterval(() => {
    idleTime++;

    if (idleTime >= warningTime && !warningShown) {
      if (confirm('⚠️ You have been inactive for 25 minutes. You will be logged out in 5 minutes if no activity occurs. Click OK to stay logged in.')) {
        resetIdle();
      }
      warningShown = true;
    }

    if (idleTime >= idleLimit) {
      alert('You have been logged out due to inactivity.');
      logout();
    }
  }, 1000);

  ['load', 'mousemove', 'keypress', 'click', 'touchstart'].forEach(event => {
    window.addEventListener(event, resetIdle, true);
  });

  // Responsive handling
  window.addEventListener('resize', function() {
    if (window.innerWidth > 992) {
      document.getElementById('sidebar').classList.remove('show');
    }
  });

  // Search functionality
  document.querySelector('.search-input')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    if (searchTerm.length > 2) {
      console.log(`Searching for: ${searchTerm}`);
    }
  });

  // Smooth scrolling
  document.documentElement.style.scrollBehavior = 'smooth';

  // Lazy loading
  function initializeLazyLoading() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('loaded');
        }
      });
    });

    document.querySelectorAll('.card').forEach(card => {
      observer.observe(card);
    });
  }
  document.addEventListener('DOMContentLoaded', initializeLazyLoading);

  // Extra styles
  const additionalStyles = `
    .calendar-day.selected { background: var(--primary-color) !important; color: white !important; font-weight: 600; }
    .card:not(.loaded) { opacity: 0.8; }
    .card.loaded { opacity: 1; transition: opacity 0.3s ease; }
    .notification-item { transition: all 0.2s ease; }
    .notification-item:hover { transform: translateX(2px); }
    .stat-card:hover .stat-icon { transform: scale(1.1); }
    .metric-card:hover .metric-icon { transform: rotate(5deg); }
    .btn.loading { position: relative; color: transparent; }
    .btn.loading .spinner { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: currentColor; }
    * { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .btn:focus, .nav-link:focus, .search-input:focus { outline: 2px solid var(--primary-color); outline-offset: 2px; }
    @media (prefers-color-scheme: dark) {
      :root { --background-color: #1a1a1a; --text-color: #ffffff; --text-muted: #a0a0a0; --border-color: #333; }
    }
  `;
  const styleSheet = document.createElement('style');
  styleSheet.textContent = additionalStyles;
  document.head.appendChild(styleSheet);

  console.log('CHRE Staff Dashboard initialized successfully');
</script>



</body>
</html>