<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Event List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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

    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar Styles - Exactly matching dashboard */
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

    /* Content */
    .content {
      flex-grow: 1;
      margin-left: var(--sidebar-width);
      transition: margin-left 0.3s ease;
    }

    .content.full {
      margin-left: 0;
    }

    /* Enhanced Event Cards */
    .event-card {
      border: none;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      transition: all 0.3s ease;
      overflow: hidden;
      background: white;
    }

    .event-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 32px rgba(0,0,0,0.15);
    }

    .event-card .card-body {
      padding: 1.5rem;
    }

    .event-card .card-title {
      font-weight: 600;
      color: var(--primary-color);
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }

    .badge-date {
      font-size: 0.8rem;
      background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 20px;
      font-weight: 500;
    }

    .card-text {
      color: var(--text-muted);
      font-size: 0.9rem;
      line-height: 1.5;
    }

    .event-card p {
      font-size: 0.85rem;
      margin-bottom: 0.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .event-card p i {
      color: var(--primary-color);
      font-size: 1rem;
    }

    /* Enhanced Buttons */
    .btn {
      border-radius: 8px;
      font-weight: 500;
      padding: 0.5rem 1rem;
      font-size: 0.85rem;
      transition: all 0.2s ease;
      border: none;
    }

    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-sm {
      padding: 0.4rem 0.8rem;
      font-size: 0.8rem;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
      color: white;
    }

    .btn-outline-primary {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
      background: transparent;
    }

    .btn-outline-primary:hover {
      background: var(--primary-color);
      color: white;
    }

    .btn-warning {
      background: linear-gradient(135deg, #ffc107, #e0a800);
      color: #856404;
    }

    .btn-danger {
      background: linear-gradient(135deg, #dc3545, #c82333);
      color: white;
    }

    .btn-success {
      background: linear-gradient(135deg, #28a745, #1e7e34);
      color: white;
    }

    .btn-secondary {
      background: linear-gradient(135deg, #6c757d, #5a6268);
      color: white;
    }

    /* Container styling */
    .container {
      max-width: 1200px;
      padding: 2rem;
    }

    .page-header {
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      margin-bottom: 2rem;
    }

    .page-header h2 {
      color: var(--primary-color);
      font-weight: 600;
      margin: 0;
    }

    /* Modal styling */
    .modal-content {
      border-radius: 12px;
      border: none;
      box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    }

    .modal-header {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
      color: white;
      border-radius: 12px 12px 0 0;
      border: none;
    }

    .modal-title {
      font-weight: 600;
    }

    .modal-footer {
      border-top: 1px solid var(--border-color);
      border-radius: 0 0 12px 12px;
    }

    /* Form controls */
    .form-control, .form-select {
      border-radius: 8px;
      border: 1px solid var(--border-color);
      padding: 0.75rem;
      transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    .form-label {
      font-weight: 500;
      color: var(--text-color);
      margin-bottom: 0.5rem;
    }

    /* Form check styling */
    .form-check {
      padding: 1rem;
      background: #f8f9fc;
      border-radius: 8px;
      border: 1px solid var(--border-color);
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    /* Event action buttons container */
    .event-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid #f1f3f4;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--text-muted);
    }

    .empty-state i {
      font-size: 4rem;
      color: var(--border-color);
      margin-bottom: 1rem;
    }

    .empty-state h4 {
      color: var(--text-muted);
      font-weight: 500;
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
    }

    @media (max-width: 768px) {
      .container {
        padding: 1rem;
      }
      
      .page-header {
        padding: 1.5rem;
      }
      
      .event-card .card-body {
        padding: 1rem;
      }
      
      .event-actions {
        flex-direction: column;
      }
    }

    @media (max-width: 576px) {
      .btn-sm {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
      }
      
      .badge-date {
        font-size: 0.7rem;
        padding: 0.4rem 0.8rem;
      }
    }

    /* Loading animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .event-card-container {
      animation: fadeInUp 0.6s ease forwards;
    }

    .event-card-container:nth-child(2) {
      animation-delay: 0.1s;
    }

    .event-card-container:nth-child(3) {
      animation-delay: 0.2s;
    }

    .event-card-container:nth-child(4) {
      animation-delay: 0.3s;
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
          <a class="nav-link" href="appointments">
            <i class='bx bx-calendar-check'></i>Appointments
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="events">
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
      <a href="#" class="logout-btn" onclick="logout()">
        <i class='bx bx-log-out me-2'></i>Logout
      </a>
    </div>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <div class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Human Rights Events</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEventModal">
          <i class="bx bx-plus"></i> Add Event
        </button>
      </div>

      <!-- Event Cards -->
      <div class="row g-4" id="eventList">
        <?php if (!empty($events)) : ?>
          <?php foreach ($events as $event) : ?>
            <?php
              $startDT = !empty($event['start_date']) ? new DateTime($event['start_date'] . ' ' . $event['start_time']) : null;
              $endDT   = !empty($event['end_date']) ? new DateTime($event['end_date'] . ' ' . $event['end_time']) : null;

              $startDate = $startDT ? $startDT->format("M j, Y") : '';
              $endDate   = $endDT ? $endDT->format("M j, Y") : '';

              $startTime = ($startDT) ? $startDT->format("g:i A (H:i)") : '';
              $endTime   = ($endDT) ? $endDT->format("g:i A (H:i)") : '';
            ?>
            <div class="col-md-6 col-lg-4 event-card-container">
              <div class="card event-card h-100">
                <div class="card-body">
                  <h5 class="card-title"><?= esc($event['event_name']) ?></h5>
                  <span class="badge badge-date mb-2">
                    <?php if ($endDT && $startDate !== $endDate): ?>
                      <?= $startDate ?> <?= $startTime ?> – <?= $endDate ?> <?= $endTime ?>
                    <?php else: ?>
                      <?= $startDate ?> <?= $startTime ?> <?= $endTime ?>
                    <?php endif; ?>
                  </span>
                  <p class="card-text mt-2"><?= esc($event['description']) ?></p>
                  <p><i class="bx bx-map"></i> <?= esc($event['location']) ?></p>
                  <p><i class="bx bx-group"></i> Audience: <?= esc($event['audience']) ?></p>

                  <?php if (!empty($event['file'])): ?>
                    <a href="<?= base_url('uploads/events/' . $event['file']) ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-2">View File</a>
                  <?php endif; ?>

                  <!-- Edit button -->
                  <button type="button" class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editEventModal<?= $event['id'] ?>">
                    <i class="bx bx-edit"></i> Edit
                  </button>

                  <!-- Delete -->
                  <form action="<?= base_url('staff/events/delete/' . $event['id']) ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this event?');" style="display:inline-block;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-sm mt-2"><i class="bx bx-trash"></i> Delete</button>
                  </form>

                  <a href="<?= base_url('staff/events/registrants/' . $event['id']) ?>" class="btn btn-success btn-sm mt-2">
                    <i class="bx bx-user"></i> View Registrants
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p class="text-center">No events found.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
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


<!-- JS -->
<script>
  document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', function(e) {
      if (this.closest('.sidebar')) {
        e.preventDefault();
        this.parentElement.classList.toggle('open');
      }
    });
  });

  const sidebarToggle = document.createElement('div');
  sidebarToggle.id = 'sidebarToggle';
  sidebarToggle.className = 'sidebar-toggle';
  sidebarToggle.innerHTML = '<i class="bx bx-menu"></i>';
  document.body.appendChild(sidebarToggle);

  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });

  document.getElementById("multiDayToggle").addEventListener("change", function() {
    const endFields = document.getElementById("endDateTimeFields");
    if (this.checked) {
      endFields.style.display = "block";
    } else {
      endFields.style.display = "none";
      document.getElementById("end_date").value = "";
      document.getElementById("end_time").value = "";
    }
  });

  // Restrict date picker: only today and future dates
  const today = new Date().toISOString().split("T")[0];
  document.getElementById("start_date").setAttribute("min", today);
  document.getElementById("end_date").setAttribute("min", today);
  const multiDayToggle = document.getElementById('multiDayToggle');
  const endDateTimeFields = document.getElementById('endDateTimeFields');

  multiDayToggle.addEventListener('change', () => {
    endDateTimeFields.style.display = multiDayToggle.checked ? 'block' : 'none';
  });
  

  // Convert 24-hour input to 12-hour AM/PM display
  function formatTimeToAMPM(timeStr) {
    if (!timeStr) return '';
    const [hour, minute] = timeStr.split(':').map(Number);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 === 0 ? 12 : hour % 12;
    return `${hour12}:${minute.toString().padStart(2,'0')} ${ampm} (${timeStr} 24h)`;
  }

  const startTimeInput = document.getElementById('start_time');
  const endTimeInput = document.getElementById('end_time');
  const startTimeDisplay = document.getElementById('startTimeDisplay');
  const endTimeDisplay = document.getElementById('endTimeDisplay');

  startTimeInput.addEventListener('input', () => {
    startTimeDisplay.textContent = formatTimeToAMPM(startTimeInput.value);
  });

  endTimeInput.addEventListener('input', () => {
    endTimeDisplay.textContent = formatTimeToAMPM(endTimeInput.value);
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
