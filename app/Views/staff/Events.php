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

    .sidebar {
      width: 260px;
      background-color: #fff;
      color: var(--text-color);
      border-right: 1px solid #e3e6f0;
      display: flex;
      flex-direction: column;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      transition: transform 0.3s ease;
      z-index: 1000;
    }

    .sidebar.hide {
      transform: translateX(-100%);
    }

    .sidebar ul {
      list-style: none;
      padding-left: 0;
      margin: 0;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 14px 22px;
      color: var(--text-color);
      font-size: 1rem;
      text-decoration: none;
      transition: background 0.2s ease;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: var(--background-color);
      font-weight: 600;
    }

    .sidebar .section-title {
      font-size: 0.75rem;
      text-transform: uppercase;
      opacity: 0.6;
      padding: 15px 22px 5px;
    }

    .submenu {
      display: none;
      background-color: #f1f3f9;
    }

    .dropdown.open .submenu {
      display: block;
    }

    .submenu li a {
      font-size: 0.95rem;
      padding: 10px 20px 10px 40px;
      display: block;
    }

    .sidebar-toggle {
      position: fixed;
      top: 15px;
      left: 15px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 50%;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 1100;
      transition: background-color 0.2s ease;
    }

    .sidebar-toggle:hover {
      background-color: var(--background-color);
    }

    .content {
      flex-grow: 1;
      margin-left: 260px;
      transition: margin-left 0.3s ease;
    }

    .content.full {
      margin-left: 0;
    }

    .event-card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      overflow: hidden;
    }

    .event-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 24px rgba(0,0,0,0.2);
    }

    .event-card .card-body {
      padding: 1.5rem;
    }

    .event-card .card-title {
      font-weight: 600;
      color: var(--primary-color);
    }

    .badge-date {
      font-size: 0.85rem;
      background: var(--primary-color);
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
    <ul class="nav flex-column">
      <li class="text-center mb-2 mt-3">
        <img src="/images/logochre.jpg" alt="Logo" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
      </li>
      <li class="text-center mb-3">
        <span style="font-weight: bold; font-size: 1.1rem;"><?= esc(session()->get('full_name')) ?></span>
      </li>
      <li><a class="nav-link " href="dashboard"><i class='bx bx-grid-alt me-3'></i> Dashboard</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/opcr-checklist') ?>"><i class='bx bx-task me-3'></i> OPCR Checklist</a></li>
      <li class="dropdown">
        <a class="nav-link " href="<?= base_url('staff/complaints'); ?>" ><i class='bx bx-message-square-error me-3'></i> Complaints</a>
      </li>
      <li><a class="nav-link" href="<?= base_url('staff/appointments') ?>"><i class='bx bx-calendar-check me-3'></i> Appointments</a></li>
      <li><a class="nav-link active" href="<?= base_url('staff/events') ?>"><i class='bx bx-calendar-event me-3'></i> Events</a></li>
     <li><a class="nav-link" href="<?= base_url('staff/students') ?>"><i class='bx bx-user-voice me-3'></i> Students</a></li>
      <li><a class="nav-link" href="#"><i class='bx bx-id-card me-3'></i> CHRE Staff <span class="badge bg-primary ms-auto">1</span></a></li>
    </ul>
     <a href="<?= base_url('logout') ?>" class="btn btn-danger m-3"><i class='bx bx-log-out me-2'></i> Logout</a>
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
