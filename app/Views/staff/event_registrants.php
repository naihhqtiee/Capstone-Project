<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Event Registrants</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #4e73df;
      --primary-hover: #3756c0;
      --background-color: #f8f9fc;
      --text-color: #212529;
      --sidebar-width: 260px;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--background-color);
      color: var(--text-color);
      margin: 0;
    }

    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
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
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logout-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
      color: white;
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

    /* Main Content */
    .content {
      flex-grow: 1;
      margin-left: 260px;
      padding: 2rem;
      transition: margin-left 0.3s ease;
    }

    .content.full {
      margin-left: 0;
    }

    /* Card for Stats */
    .card-stat {
      border: none;
      border-radius: 0.75rem;
      background-color: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .card-stat i {
      font-size: 2rem;
      color: var(--primary-color);
    }

    .card-stat h4 {
      margin: 0;
      font-size: 1.5rem;
      font-weight: bold;
    }

    /* Table Styling */
    .table {
      background-color: #fff;
      border-radius: 0.75rem;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .table thead {
      background-color: var(--primary-color);
      color: #fff;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    /* Buttons */
    .btn-back {
      background-color: var(--primary-color);
      color: white;
      border-radius: 0.5rem;
      padding: 0.5rem 1rem;
      font-weight: 500;
      transition: background-color 0.2s ease;
    }

    .btn-back:hover {
      background-color: var(--primary-hover);
      color: white;
    }

    @media (max-width: 992px) {
      .content {
        margin-left: 0;
      }
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
          <a class="nav-link" href="dashboard">
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
          <a class="nav-link " href="appointments">
            <i class='bx bx-calendar-check'></i>Appointments
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="events">
            <i class='bx bx-calendar-event'></i>Events
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="students">
            <i class='bx bx-user-voice'></i>Students
          </a>
        </li>

      </ul>
    </nav>
    
    <div class="sidebar-footer">
      <a href="#" class="logout-btn" onclick="logout()">
        <i class='bx bx-log-out me-2'></i>Logout
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="content" id="content">
    <h2 class="mb-4"><i class='bx bx-group me-2'></i> Event Registrants</h2>

    <!-- Statistics Card -->
    <div class="card-stat">
      <i class='bx bx-user'></i>
      <div>
        <h4><?= count($registrants) ?></h4>
        <span>Total Registrants</span>
      </div>
    </div>

    <?php if (!empty($registrants)): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Contact</th>
              <th>Special Requirements</th>
              <th>Registered At</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($registrants as $index => $r): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= esc($r['full_name']) ?></td>
                <td><?= esc($r['email']) ?></td>
                <td><?= esc($r['contact_number']) ?></td>
                <td><?= esc($r['special_requirements']) ?: '<em class="text-muted">None</em>' ?></td>
                <td><?= esc(date('M d, Y h:i A', strtotime($r['created_at']))) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-info p-4 rounded shadow-sm">
        <i class='bx bx-info-circle me-2'></i> No one has registered for this event yet.
      </div>
    <?php endif; ?>

    <a href="<?= base_url('staff/events') ?>" class="btn btn-back mt-3">
      <i class='bx bx-arrow-back me-1'></i> Back to Events
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Sidebar Dropdown Toggle
  document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', function(e) {
      if (this.closest('.sidebar')) {
        e.preventDefault();
        this.parentElement.classList.toggle('open');
      }
    });
  });

  // Sidebar Toggle Button
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });
</script>
</body>
</html>
