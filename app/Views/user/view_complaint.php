<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KARAMAY - My Complaints Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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

    /* Enhanced Sidebar */
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
      color: white;
      z-index: 1000;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
      position: relative;
    }

    .logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-bottom: 10px;
      background: white;
      display: inline-block;
      transition: all 0.3s ease;
    }

    .brand-name {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 5px;
      transition: opacity 0.3s ease;
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
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      border-radius: 0 25px 25px 0;
      margin-right: 20px;
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
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
      transition: left 0.5s;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link:hover, .nav-link.active {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .nav-link i {
      font-size: 1.3rem;
      margin-right: 15px;
      min-width: 20px;
      transition: transform 0.3s ease;
    }

    .nav-link:hover i {
      transform: scale(1.1);
    }

    /* Enhanced Main Content */
    .main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      transition: margin-left 0.3s ease;
    }

    .main-content.expanded {
      margin-left: 70px;
    }

    /* Enhanced Top Bar */
    .topbar {
      height: var(--topbar-height);
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 30px;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 20px rgba(0,0,0,0.1);
    }

    .menu-toggle {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--dark-color);
      cursor: pointer;
      margin-right: 20px;
      transition: transform 0.3s ease;
    }

    .menu-toggle:hover {
      transform: scale(1.1);
    }

    .page-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark-color);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Enhanced Complaint Header */
    .complaint-header {
      background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
      backdrop-filter: blur(20px);
      border-radius: 20px;
      padding: 30px;
      margin: 30px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      text-align: center;
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      position: relative;
      overflow: hidden;
    }

    .complaint-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(30, 64, 175, 0.1), transparent);
      animation: shine 2s infinite;
    }

    @keyframes shine {
      0% { left: -100%; }
      100% { left: 100%; }
    }

    /* Enhanced Search and Filter Section */
    .search-filter-section {
      background: white;
      border-radius: 20px;
      padding: 25px;
      margin: 0 30px 30px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      display: flex;
      gap: 20px;
      align-items: center;
      flex-wrap: wrap;
    }

    .search-container {
      position: relative;
      flex: 1;
      min-width: 300px;
    }

    .search-input {
      width: 100%;
      padding: 15px 50px 15px 20px;
      border: 2px solid #e2e8f0;
      border-radius: 15px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: #f8fafc;
    }

    .search-input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
      outline: none;
      background: white;
    }

    .search-icon {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      color: #64748b;
      font-size: 1.2rem;
    }

    .filter-container {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .filter-select {
      padding: 12px 15px;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      background: white;
      color: var(--dark-color);
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
      outline: none;
    }

    .clear-filters {
      padding: 12px 20px;
      background: linear-gradient(135deg, var(--danger-color), #f87171);
      color: white;
      border: none;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .clear-filters:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    }

    /* Enhanced Statistics Cards */
    .stats-section {
      margin: 0 30px 30px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .stat-card {
      background: white;
      border-radius: 20px;
      padding: 25px;
      text-align: center;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      color: white;
      margin: 0 auto 15px;
      background: linear-gradient(135deg, var(--primary-color), var(--info-color));
    }

    .stat-value {
      font-size: 2.2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 8px;
    }

    .stat-label {
      color: #64748b;
      font-size: 0.9rem;
      font-weight: 500;
    }

    /* Enhanced Table Container */
    .table-container {
      background: white;
      border-radius: 20px;
      margin: 0 30px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      overflow: hidden;
      position: relative;
    }

    .table-header {
      background: linear-gradient(135deg, var(--primary-color), var(--info-color));
      color: white;
      padding: 20px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .table-title {
      font-size: 1.3rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .export-buttons {
      display: flex;
      gap: 10px;
    }

    .export-btn {
      padding: 8px 15px;
      background: rgba(255,255,255,0.2);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .export-btn:hover {
      background: rgba(255,255,255,0.3);
      transform: translateY(-2px);
    }

    /* Fixed and Compact Table Styling */
    .table-responsive {
      overflow-x: auto;
      max-height: 600px;
      overflow-y: auto;
    }

    .complaints-table {
      width: 100%;
      min-width: 1100px; /* Fixed minimum width to prevent squishing */
      border-collapse: collapse;
      font-size: 0.8rem; /* Smaller font for compactness */
      table-layout: fixed; /* Fixed layout for consistent column widths */
    }

    /* Optimized column widths to fit all 8 columns */
    .complaints-table th:nth-child(1) { width: 8%; }  /* ID */
    .complaints-table th:nth-child(2) { width: 12%; } /* Type */
    .complaints-table th:nth-child(3) { width: 12%; } /* Category */
    .complaints-table th:nth-child(4) { width: 8%; }  /* Date */
    .complaints-table th:nth-child(5) { width: 8%; }  /* Status */
    .complaints-table th:nth-child(6) { width: 25%; } /* Description */
    .complaints-table th:nth-child(7) { width: 15%; } /* Staff Notes */
    .complaints-table th:nth-child(8) { width: 12%; } /* Actions */

    .complaints-table thead th {
      background: #f8fafc;
      padding: 12px 8px; /* Reduced padding */
      font-weight: 600;
      color: var(--dark-color);
      text-align: left;
      border-bottom: 2px solid #e2e8f0;
      position: sticky;
      top: 0;
      z-index: 10;
      font-size: 0.75rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .complaints-table tbody tr {
      transition: all 0.3s ease;
      border-bottom: 1px solid #f1f5f9;
    }

    .complaints-table tbody tr:hover {
      background: #f8fafc;
      transform: scale(1.005);
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .complaints-table tbody td {
      padding: 10px 8px; /* Reduced padding */
      vertical-align: middle;
      border-right: 1px solid #f1f5f9;
      font-size: 0.8rem;
      word-wrap: break-word;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .complaints-table tbody td:last-child {
      border-right: none;
    }

    /* Text truncation and tooltips for better space management */
    .text-truncate-cell {
      max-height: 40px;
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      line-height: 1.3;
      cursor: help;
    }

    .notes-cell {
      font-size: 0.75rem;
      color: #6b7280;
      font-style: italic;
    }

    /* Compact Status Badges */
    .status-badge {
      padding: 2px 6px;
      border-radius: 10px;
      font-size: 0.65rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      display: inline-block;
      white-space: nowrap;
    }

    .status-pending {
      background: rgba(245, 158, 11, 0.1);
      color: #d97706;
      border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-in-progress {
      background: rgba(59, 130, 246, 0.1);
      color: #2563eb;
      border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-resolved {
      background: rgba(16, 185, 129, 0.1);
      color: #059669;
      border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .status-rejected {
      background: rgba(239, 68, 68, 0.1);
      color: #dc2626;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Compact Action Buttons */
    .action-buttons {
      display: flex;
      gap: 3px;
      align-items: center;
      flex-wrap: nowrap;
    }

    .btn-action {
      padding: 4px 8px;
      border: none;
      border-radius: 4px;
      font-size: 0.7rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 2px;
      white-space: nowrap;
    }

    .btn-view {
      background: linear-gradient(135deg, var(--info-color), #60a5fa);
      color: white;
    }

    .btn-edit {
      background: linear-gradient(135deg, var(--warning-color), #fbbf24);
      color: white;
    }

    .btn-delete {
      background: linear-gradient(135deg, var(--danger-color), #f87171);
      color: white;
    }

    .btn-action:hover {
      transform: translateY(-1px) scale(1.05);
      box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }

    .btn-action i {
      font-size: 0.65rem;
    }

    /* Hide button text on smaller screens to save space */
    .btn-text {
      margin-left: 2px;
    }

    @media (max-width: 1200px) {
      .btn-text {
        display: none;
      }
      
      .btn-action {
        padding: 4px 6px;
      }
    }

    /* Tooltip for truncated content */
    .tooltip-content {
      position: relative;
      cursor: help;
    }

    .tooltip-content:hover::after {
      content: attr(data-full-text);
      position: absolute;
      bottom: 100%;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 8px 12px;
      border-radius: 4px;
      font-size: 0.7rem;
      white-space: normal;
      max-width: 200px;
      z-index: 1000;
      word-wrap: break-word;
      margin-bottom: 5px;
    }

    /* Enhanced Pagination */
    .pagination-container {
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #f8fafc;
      border-top: 1px solid #e2e8f0;
    }

    .pagination {
      display: flex;
      gap: 5px;
      margin: 0 auto;
    }

    .page-btn {
      padding: 8px 12px;
      border: 2px solid #e2e8f0;
      background: white;
      color: var(--dark-color);
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      font-size: 0.85rem;
    }

    .page-btn:hover, .page-btn.active {
      background: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
      transform: translateY(-1px);
    }

    .pagination-info {
      color: #64748b;
      font-size: 0.85rem;
    }

    /* Enhanced Empty State */
    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #64748b;
    }

    .empty-icon {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.5;
    }

    .empty-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--dark-color);
    }

    .empty-description {
      font-size: 0.9rem;
      margin-bottom: 20px;
    }

    .empty-action {
      padding: 10px 20px;
      background: linear-gradient(135deg, var(--primary-color), var(--info-color));
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 0.85rem;
    }

    .empty-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    /* Loading States */
    .loading-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255,255,255,0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 100;
    }

    .loading-spinner {
      width: 35px;
      height: 35px;
      border: 3px solid #f3f4f6;
      border-top: 3px solid var(--primary-color);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* Enhanced Mobile Responsive */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        z-index: 1050;
      }

      .sidebar.mobile-open {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .search-filter-section {
        flex-direction: column;
        align-items: stretch;
        padding: 15px;
        margin: 0 15px 20px;
      }

      .search-container {
        min-width: auto;
      }

      .filter-container {
        flex-direction: column;
        gap: 10px;
      }

      .stats-section {
        grid-template-columns: repeat(2, 1fr);
        margin: 0 15px 20px;
      }

      .table-container {
        margin: 0 15px;
      }

      .complaints-table {
        min-width: 900px; /* Smaller minimum on mobile */
        font-size: 0.75rem;
      }

      .complaints-table thead th,
      .complaints-table tbody td {
        padding: 6px 4px;
      }

      .btn-action {
        padding: 3px 5px;
        font-size: 0.65rem;
      }

      .btn-text {
        display: none; /* Always hide text on mobile */
      }
    }

    @media (max-width: 480px) {
      .complaint-header {
        font-size: 1.3rem;
        padding: 15px;
        margin: 15px;
      }

      .stats-section {
        grid-template-columns: 1fr;
      }

      .table-header {
        padding: 15px 20px;
        flex-direction: column;
        gap: 10px;
      }

      .pagination-container {
        flex-direction: column;
        gap: 10px;
        padding: 15px 20px;
      }
    }

    /* Scrollbar styling for table */
    .table-responsive::-webkit-scrollbar {
      height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
      background: #c1c1c1;
      border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
      background: #a8a8a8;
    }
  </style>
</head>
<body>
  <!-- Enhanced Sidebar -->
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
        <a href="/user/view-complaint" class="nav-link active">
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

  <div class="main-content" id="mainContent">
    <!-- Enhanced Top Bar -->
    <div class="topbar">
      <div style="display: flex; align-items: center;">
        <button class="menu-toggle" onclick="toggleSidebar()">
          <i class="bx bx-menu"></i>
        </button>
        <div class="page-title">
          <i class="fas fa-clipboard-list"></i>
          My Complaints
        </div>
      </div>
    </div>

    <!-- Enhanced Header -->
    <div class="complaint-header">
      <i class="fas fa-list-check" style="margin-right: 15px; font-size: 1.8rem;"></i>
      List of My Complaints
    </div>

    <!-- Statistics Section -->
    <div class="stats-section">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-clipboard"></i>
        </div>
        <div class="stat-value" id="totalComplaints"><?= $myComplaints ?></div>
        <div class="stat-label">Total Complaints</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--warning-color), #fbbf24);">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value" id="pendingComplaints"><?= $pendingComplaints ?></div>
        <div class="stat-label">Pending</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--info-color), #60a5fa);">
          <i class="fas fa-cog"></i>
        </div>
        <div class="stat-value" id="inProgressComplaints"><?= $inProgressComplaints ?></div>
        <div class="stat-label">In Progress</div>
      </div>

      <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, var(--success-color), #34d399);">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value" id="resolvedComplaints"><?= $resolvedComplaints ?></div>
        <div class="stat-label">Resolved</div>
      </div>
    </div>

    <!-- Enhanced Search and Filter Section -->
    <div class="search-filter-section">
      <div class="search-container">
        <input type="text" class="search-input" placeholder="Search by Complaint ID, Type, or Description..." id="searchInput">
        <i class="fas fa-search search-icon"></i>
      </div>
      <div class="filter-container">
        <select class="filter-select" id="statusFilter">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="in-progress">In Progress</option>
          <option value="resolved">Resolved</option>
          <option value="rejected">Rejected</option>
        </select>
        <select class="filter-select" id="categoryFilter">
          <option value="">All Categories</option>
          <option value="road">Road Issues</option>
          <option value="water">Water Supply</option>
          <option value="electricity">Electricity</option>
          <option value="waste">Waste Management</option>
          <option value="other">Other</option>
        </select>
        <button class="clear-filters" onclick="clearFilters()">
          <i class="fas fa-times"></i> Clear
        </button>
      </div>
    </div>

    <!-- Enhanced Table Container with Fixed Layout -->
    <div class="table-container">
      <div class="table-header">
        <div class="table-title">
          <i class="fas fa-table"></i>
          Complaints Overview
        </div>
        <div class="export-buttons">
          <button class="export-btn" onclick="exportData('csv')">
            <i class="fas fa-file-csv"></i> CSV
          </button>
          <button class="export-btn" onclick="exportData('pdf')">
            <i class="fas fa-file-pdf"></i> PDF
          </button>
          <button class="export-btn" onclick="printTable()">
            <i class="fas fa-print"></i> Print
          </button>
        </div>
      </div>
      
      <div class="table-responsive">
        <div class="loading-overlay" id="loadingOverlay" style="display: none;">
          <div class="loading-spinner"></div>
        </div>
        
        <table class="complaints-table">
          <thead>
            <tr>
              <th onclick="sortTable(0)">
                ID <i class="fas fa-sort"></i>
              </th>
              <th onclick="sortTable(1)">
                Type <i class="fas fa-sort"></i>
              </th>
              <th onclick="sortTable(2)">
                Category <i class="fas fa-sort"></i>
              </th>
              <th onclick="sortTable(3)">
                Date <i class="fas fa-sort"></i>
              </th>
              <th onclick="sortTable(4)">
                Status <i class="fas fa-sort"></i>
              </th>
              <th>Description</th>
              <th>Staff Notes</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            <?php if (!empty($complaints)): ?>
              <?php foreach ($complaints as $c): ?>
                <tr>
                  <td>
                    <strong><?= 'CMP-' . str_pad($c['id'], 3, '0', STR_PAD_LEFT) ?></strong>
                  </td>

                  <td>
                    <div class="tooltip-content" data-full-text="<?= esc($c['complaint_type']) ?>">
                      <?= esc(strlen($c['complaint_type']) > 15 ? substr($c['complaint_type'], 0, 15) . '...' : $c['complaint_type']) ?>
                    </div>
                  </td>

                  <td>
                    <div class="tooltip-content" data-full-text="<?= esc($c['complaint_category']) ?>">
                      <?= esc(strlen($c['complaint_category']) > 15 ? substr($c['complaint_category'], 0, 15) . '...' : $c['complaint_category']) ?>
                    </div>
                  </td>

                  <td>
                    <div style="font-size: 0.75rem; text-align: center;">
                      <div style="font-weight: 600;"><?= date('M d', strtotime($c['date'])) ?></div>
                      <div style="color: #6b7280;"><?= date('Y', strtotime($c['date'])) ?></div>
                    </div>
                  </td>

                  <td>
                    <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $c['status'])) ?>">
                      <?= esc($c['status']) ?>
                    </span>
                  </td>

                  <td>
                    <div class="text-truncate-cell tooltip-content" data-full-text="<?= esc($c['description']) ?>">
                      <?= esc($c['description']) ?>
                    </div>
                  </td>

                  <td>
                    <div class="notes-cell">
                      <?php if (!empty($c['notes'])): ?>
                        <div class="tooltip-content" data-full-text="<?= esc($c['notes']) ?>">
                          <?= esc(strlen($c['notes']) > 30 ? substr($c['notes'], 0, 30) . '...' : $c['notes']) ?>
                        </div>
                      <?php else: ?>
                        <span style="color: #9ca3af;">No notes</span>
                      <?php endif; ?>
                    </div>
                  </td>

                  <td>
                    <div class="action-buttons">
                      <a href="<?= base_url('complaint/view/' . $c['id']) ?>" class="btn-action btn-view" title="View Details">
                        <i class="fas fa-eye"></i><span class="btn-text">View</span>
                      </a>
                      <button type="button" 
                              class="btn-action btn-edit"
                              title="Edit Complaint"
                              data-id="<?= $c['id'] ?>"
                              data-type="<?= esc($c['complaint_type']) ?>"
                              data-category="<?= esc($c['complaint_category']) ?>"
                              data-location="<?= esc($c['location']) ?>"
                              data-date="<?= esc($c['date']) ?>"
                              data-description="<?= esc($c['description']) ?>">
                        <i class="fas fa-edit"></i><span class="btn-text">Edit</span>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center">
                  <div class="empty-state">
                    <div class="empty-icon">
                      <i class="fas fa-inbox"></i>
                    </div>
                    <div class="empty-title">No Complaints Found</div>
                    <div class="empty-description">
                      You haven't submitted any complaints yet.
                    </div>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="pagination-container">
        <div class="pagination-info">
          Showing <span id="startItem">1</span> to <span id="endItem">4</span> of <span id="totalItems">4</span> entries
        </div>
        <div class="pagination">
          <button class="page-btn" onclick="previousPage()">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button class="page-btn active" onclick="goToPage(1)">1</button>
          <button class="page-btn" onclick="nextPage()">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Complaint Modal -->
  <div class="modal fade" id="editComplaintModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <form method="post" id="editComplaintForm">
          <?= csrf_field() ?>
          <div class="modal-header">
            <h5 class="modal-title">
              <i class="fas fa-edit"></i> Edit Complaint
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="id" id="editComplaintId">

            <!-- Complaint Type -->
            <div class="mb-3">
              <label class="form-label">Complaint Type</label>
              <input type="text" class="form-control" name="complaint_type" id="editComplaintType" required>
            </div>

            <!-- Complaint Category -->
            <div class="mb-3">
              <label class="form-label">Complaint Category</label>
              <input type="text" class="form-control" name="complaint_category" id="editComplaintCategory" required>
            </div>

            <!-- Location -->
            <div class="mb-3">
              <label class="form-label">Location</label>
              <input type="text" class="form-control" name="location" id="editComplaintLocation" required>
            </div>

            <!-- Date -->
            <div class="mb-3">
              <label class="form-label">Date</label>
              <input type="date" class="form-control" name="date" id="editComplaintDate" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea class="form-control" name="description" id="editComplaintDescription" rows="4" required></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal for Viewing Complaint Details -->
  <div class="modal fade" id="complaintModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 20px 80px rgba(0,0,0,0.2);">
        <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border-bottom: none; padding: 25px 30px;">
          <h5 class="modal-title" style="font-weight: 700; font-size: 1.3rem;">
            <i class="fas fa-file-alt"></i> Complaint Details
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" style="padding: 30px;">
          <div id="complaintDetails">
            <!-- Complaint details will be loaded here -->
          </div>
        </div>
        <div class="modal-footer" style="border-top: none; padding: 20px 30px;">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="downloadComplaint()">
            <i class="fas fa-download"></i> Download PDF
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
    <div id="toastContainer"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Enhanced JavaScript for Interactive Features
    let filteredData = [];
    let currentSortColumn = -1;
    let sortDirection = 1;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
      setupEventListeners();
      
      // Animate sections on page load
      setTimeout(() => {
        document.querySelectorAll('.stat-card, .table-container').forEach((el, index) => {
          el.style.opacity = '0';
          el.style.transform = 'translateY(30px)';
          setTimeout(() => {
            el.style.transition = 'all 0.6s ease';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
          }, index * 100);
        });
      }, 100);
    });

    // Toggle sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('mainContent');
      
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('mobile-open');
      } else {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
      }
    }

    // Setup event listeners
    function setupEventListeners() {
      const searchInput = document.getElementById('searchInput');
      const statusFilter = document.getElementById('statusFilter');
      const categoryFilter = document.getElementById('categoryFilter');

      if (searchInput) searchInput.addEventListener('input', debounce(filterComplaints, 300));
      if (statusFilter) statusFilter.addEventListener('change', filterComplaints);
      if (categoryFilter) categoryFilter.addEventListener('change', filterComplaints);

      // Setup edit button listeners
      document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
          // Fill modal fields
          document.getElementById('editComplaintId').value = this.dataset.id;
          document.getElementById('editComplaintType').value = this.dataset.type;
          document.getElementById('editComplaintCategory').value = this.dataset.category;
          document.getElementById('editComplaintLocation').value = this.dataset.location;
          document.getElementById('editComplaintDate').value = this.dataset.date;
          document.getElementById('editComplaintDescription').value = this.dataset.description;

          // Show modal
          const editModal = new bootstrap.Modal(document.getElementById('editComplaintModal'));
          editModal.show();
        });
      });

      // Handle form submission
      const editForm = document.getElementById('editComplaintForm');
      if (editForm) {
        editForm.addEventListener('submit', function (e) {
          e.preventDefault();

          fetch("<?= base_url('/complaint/update') ?>", {
            method: "POST",
            body: new FormData(this)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showToast("Complaint updated successfully!", 'success');
              setTimeout(() => location.reload(), 1500);
            } else {
              showToast("Failed to update complaint.", 'error');
            }
          })
          .catch(err => {
            console.error(err);
            showToast("Error updating complaint.", 'error');
          });
        });
      }

      // Close sidebar on outside click (mobile)
      document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (window.innerWidth <= 768 && 
            !sidebar.contains(e.target) && 
            !menuToggle.contains(e.target) && 
            sidebar.classList.contains('mobile-open')) {
          sidebar.classList.remove('mobile-open');
        }
      });
    }

    // Debounce function for search
    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }

    // Filter complaints (simplified for PHP template)
    function filterComplaints() {
      showToast('Filter functionality active', 'info');
      // This would work with JavaScript data or AJAX calls
    }

    // Clear filters
    function clearFilters() {
      document.getElementById('searchInput').value = '';
      document.getElementById('statusFilter').value = '';
      document.getElementById('categoryFilter').value = '';
      showToast('Filters cleared!', 'info');
    }

    // Sort table
    function sortTable(columnIndex) {
      showToast('Sort functionality would be implemented here', 'info');
    }

    // Export data
    function exportData(format) {
      showLoading();
      
      setTimeout(() => {
        hideLoading();
        if (format === 'csv') {
          exportToCSV();
        } else if (format === 'pdf') {
          exportToPDF();
        }
        showToast(`Data exported as ${format.toUpperCase()}!`, 'success');
      }, 1500);
    }

    // Export to CSV
    function exportToCSV() {
      showToast('CSV export would be implemented here', 'info');
    }

    // Export to PDF
    function exportToPDF() {
      showToast('PDF export would be implemented here', 'info');
    }

    // Print table
    function printTable() {
      window.print();
    }

    // Download complaint
    function downloadComplaint() {
      showToast('Downloading complaint details...', 'info');
    }

    // Pagination functions
    function previousPage() {
      showToast('Previous page', 'info');
    }

    function nextPage() {
      showToast('Next page', 'info');
    }

    function goToPage(page) {
      showToast(`Going to page ${page}`, 'info');
    }

    // Loading functions
    function showLoading() {
      const overlay = document.getElementById('loadingOverlay');
      if (overlay) overlay.style.display = 'flex';
    }

    function hideLoading() {
      const overlay = document.getElementById('loadingOverlay');
      if (overlay) overlay.style.display = 'none';
    }

    // Toast notification system
    function showToast(message, type = 'info') {
      const toastContainer = document.getElementById('toastContainer');
      const toastId = 'toast-' + Date.now();
      
      const toastHTML = `
        <div class="toast align-items-center text-bg-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'error' ? 'danger' : 'info'} border-0" role="alert" id="${toastId}" style="margin-bottom: 10px;">
          <div class="d-flex">
            <div class="toast-body d-flex align-items-center">
              <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : type === 'error' ? 'times-circle' : 'info-circle'} me-2"></i>
              ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
          </div>
        </div>
      `;
      
      toastContainer.insertAdjacentHTML('beforeend', toastHTML);
      const toastElement = document.getElementById(toastId);
      const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
      toast.show();
      
      // Remove toast element after it's hidden
      toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
      });
    }
  </script>
</body>
</html>