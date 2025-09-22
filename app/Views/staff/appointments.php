<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Appointments</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">

  <style>
    :root {
      --primary-color: #4e73df;
      --primary-hover: #3756c0;
      --background-color: #f8f9fc;
      --text-color: #212529;
      --calendar-primary: #3b82f6;
      --calendar-hover: #2563eb;
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: var(--background-color);
      color: var(--text-color);
      margin: 0;
    }
    .layout { display: flex; min-height: 100vh; }
    .sidebar {
      width: 260px; background-color: #fff; color: var(--text-color);
      border-right: 1px solid #e3e6f0; display: flex; flex-direction: column;
      height: 100vh; position: fixed; top: 0; left: 0; overflow-y: auto;
      transition: transform 0.3s ease; z-index: 1000;
    }
    .sidebar.hide { transform: translateX(-100%); }
    .sidebar ul { list-style: none; padding-left: 0; margin: 0; }
    .sidebar a { display: flex; align-items: center; padding: 14px 22px;
      color: var(--text-color); font-size: 1rem; text-decoration: none; transition: background 0.2s ease; }
    .sidebar a:hover, .sidebar a.active { background-color: var(--background-color); font-weight: 600; }
    .sidebar .section-title { font-size: 0.75rem; text-transform: uppercase; opacity: 0.6; padding: 15px 22px 5px; }
    .submenu { display: none; background-color: #f1f3f9; }
    .dropdown.open .submenu { display: block; }
    .submenu li a { font-size: 0.95rem; padding: 10px 20px 10px 40px; display: block; }
    .sidebar-toggle {
      position: fixed; top: 15px; left: 15px; background-color: #fff;
      border: 1px solid #ddd; border-radius: 50%; width: 45px; height: 45px;
      display: flex; align-items: center; justify-content: center; cursor: pointer;
      z-index: 1100; transition: background-color 0.2s ease;
    }
    .sidebar-toggle:hover { background-color: var(--background-color); }
    .content { flex-grow: 1; margin-left: 260px; transition: margin-left 0.3s ease; }
    .content.full { margin-left: 0; }
    .navbar {
      background-color: #fff; border-bottom: 1px solid #e3e6f0;
      padding: 0.75rem 1.5rem; position: sticky; top: 0; z-index: 999;
    }
    .content {
      flex-grow: 1;
      margin-left: 250px;
      padding: 20px;
      transition: margin-left 0.3s ease;
    }
    .content.full { margin-left: 0; }

    /* Stats cards */
    .stats-card {
      border-radius: 12px;
      padding: 20px;
      color: white;
      text-align: center;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    .stats-card .stats-number { font-size: 2rem; font-weight: bold; }
    .pending { background: #f6c23e; }
    .approved { background: #1cc88a; }
    .completed { background: #36b9cc; }
    .rejected { background: #e74a3b; }

    /* Table */
    .table thead th {
      background: #4e73df;
      color: white;
      text-align: center;
    }
    .table td { vertical-align: middle; }
    .status-dropdown { min-width: 120px; }
    
    /* Action buttons */
    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .action-row {
      display: flex;
      gap: 5px;
      align-items: center;
    }
    .btn-delete {
      background-color: #dc3545;
      border-color: #dc3545;
      color: white;
      padding: 2px 8px;
      font-size: 0.75rem;
    }
    .btn-delete:hover {
      background-color: #c82333;
      border-color: #bd2130;
      color: white;
    }

    /* Calendar Improvements */
    #calendar {
      background: white;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border: 1px solid #e5e7eb;
    }

    /* FullCalendar Custom Styles */
    .fc-theme-standard .fc-view-harness {
      border-radius: 12px;
      overflow: hidden;
    }

    /* Header styling */
    .fc-header-toolbar {
      background: linear-gradient(135deg, var(--calendar-primary) 0%, var(--calendar-hover) 100%);
      padding: 16px 20px !important;
      margin-bottom: 0 !important;
      border-radius: 12px 12px 0 0;
    }

    .fc-toolbar-title {
      color: white !important;
      font-weight: 700 !important;
      font-size: 1.5rem !important;
    }

    .fc-button-primary {
      background-color: rgba(255,255,255,0.2) !important;
      border: 1px solid rgba(255,255,255,0.3) !important;
      color: white !important;
      border-radius: 8px !important;
      padding: 8px 16px !important;
      font-weight: 500 !important;
      transition: all 0.2s ease !important;
    }

    .fc-button-primary:hover {
      background-color: rgba(255,255,255,0.3) !important;
      border-color: rgba(255,255,255,0.4) !important;
      transform: translateY(-1px);
    }

    .fc-button-primary:disabled {
      opacity: 0.6 !important;
      background-color: rgba(255,255,255,0.1) !important;
    }

    /* Day grid styling */
    .fc-daygrid-day {
      background: white;
      border: 1px solid #f3f4f6 !important;
      transition: background-color 0.2s ease;
    }

    .fc-daygrid-day:hover {
      background-color: #f8fafc !important;
    }

    /* Day header styling */
    .fc-col-header-cell {
      background: #f8fafc !important;
      border: 1px solid #e5e7eb !important;
      padding: 12px 8px !important;
    }

    .fc-col-header-cell-cushion {
      color: #374151 !important;
      font-weight: 600 !important;
      font-size: 0.875rem !important;
      text-transform: uppercase !important;
      letter-spacing: 0.05em !important;
    }

    /* Day number styling */
    .fc-daygrid-day-number {
      color: #374151 !important;
      font-weight: 500 !important;
      font-size: 0.875rem !important;
      padding: 8px !important;
      text-decoration: none !important;
    }

    .fc-daygrid-day-number.available { 
      color: #059669 !important; 
      font-weight: 700 !important;
      background: rgba(16, 185, 129, 0.1);
      border-radius: 6px;
    }
    
    .fc-daygrid-day-number.fully-booked { 
      color: #dc2626 !important; 
      font-weight: 700 !important;
      background: rgba(239, 68, 68, 0.1);
      border-radius: 6px;
    }

    /* Today styling */
    .fc-day-today {
      background-color: rgba(59, 130, 246, 0.05) !important;
    }

    .fc-day-today .fc-daygrid-day-number {
      background: var(--calendar-primary) !important;
      color: white !important;
      border-radius: 50% !important;
      width: 32px !important;
      height: 32px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      margin: 4px !important;
    }

    /* Event styling */
    .fc-event {
      border-radius: 6px !important;
      border: none !important;
      padding: 2px 6px !important;
      margin: 1px 2px !important;
      font-size: 0.75rem !important;
      font-weight: 500 !important;
      box-shadow: 0 1px 3px rgba(0,0,0,0.12) !important;
    }

    .fc-event-title {
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      white-space: nowrap !important;
    }

    /* Weekend styling */
    .fc-day-sat, .fc-day-sun {
      background-color: #fafafa !important;
    }

    /* Other month days */
    .fc-day-other .fc-daygrid-day-number {
      color: #d1d5db !important;
    }

    /* Calendar legend */
    .calendar-legend {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 15px;
      padding: 10px;
      background: #f8fafc;
      border-radius: 8px;
      font-size: 0.875rem;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .legend-color {
      width: 16px;
      height: 16px;
      border-radius: 4px;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
      .fc-header-toolbar {
        flex-direction: column;
        gap: 10px;
      }
      
      .fc-toolbar-title {
        font-size: 1.25rem !important;
      }
      
      .calendar-legend {
        flex-wrap: wrap;
        gap: 10px;
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
  <li>
    <a class="nav-link" href="<?= base_url('staff/dashboard') ?>">
      <i class='bx bx-grid-alt me-3'></i> Dashboard
    </a>
  </li>
  <li>
    <a class="nav-link" href="<?= base_url('staff/opcr-checklist') ?>">
      <i class='bx bx-task me-3'></i> OPCR Checklist
    </a>
  </li>
  <li>
    <a class="nav-link" href="<?= base_url('staff/complaints'); ?>">
      <i class='bx bx-message-square-error me-3'></i> Complaints
    </a>
  </li>
  <li>
    <a class="nav-link active" href="<?= base_url('staff/appointments') ?>">
      <i class='bx bx-calendar-check me-3'></i> Appointments
    </a>
  </li>
  <li>
    <a class="nav-link" href="<?= base_url('staff/events') ?>">
      <i class='bx bx-calendar-event me-3'></i> Events
    </a>
  </li>
  <li>
    <a class="nav-link" href="<?= base_url('staff/students') ?>">
      <i class='bx bx-user-voice me-3'></i> Students
    </a>
  </li>
  <li>
    <a class="nav-link" href="#">
      <i class='bx bx-id-card me-3'></i> CHRE Staff 
      <span class="badge bg-primary ms-auto">1</span>
    </a>
  </li>
</ul>

<a href="<?= base_url('logout') ?>" class="btn btn-danger m-3">
  <i class='bx bx-log-out me-2'></i> Logout
</a>
  </div>

    <!-- Content -->
    <div class="content" id="content">
      <!-- Stats -->
      <div class="row mb-4">
        <?php
          $pending = $approved = $completed = $rejected = 0;
          foreach ($appointments as $a) {
            if ($a['status'] === 'Pending') $pending++;
            if ($a['status'] === 'Approved') $approved++;
            if ($a['status'] === 'Completed') $completed++;
            if ($a['status'] === 'Rejected') $rejected++;
          }
        ?>
        <div class="col-md-3 mb-3"><div class="stats-card pending"><div class="stats-number"><?= $pending ?></div><div>Pending</div></div></div>
        <div class="col-md-3 mb-3"><div class="stats-card approved"><div class="stats-number"><?= $approved ?></div><div>Approved</div></div></div>
        <div class="col-md-3 mb-3"><div class="stats-card completed"><div class="stats-number"><?= $completed ?></div><div>Completed</div></div></div>
        <div class="col-md-3 mb-3"><div class="stats-card rejected"><div class="stats-number"><?= $rejected ?></div><div>Rejected</div></div></div>
      </div>

      <!-- Appointments Table -->
      <div class="card mb-4">
        <div class="card-header bg-primary text-white">Appointments</div>
        <div class="card-body p-0">
          <table class="table table-bordered mb-0">
            <thead>
              <tr>
                <th>Full Name</th><th>Email</th><th>Date</th><th>Time</th><th>Purpose</th><th>Status</th><th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($appointments)): foreach ($appointments as $app): ?>
                <tr>
                  <td><?= esc($app['fullname']) ?></td>
                  <td><?= esc($app['email']) ?></td>
                  <td class="text-center"><?= esc($app['appointment_date']) ?></td>
                  <td class="text-center"><?= date('h:i A', strtotime($app['appointment_time'])) ?></td>
                  <td><?= esc($app['purpose']) ?></td>
                  <td class="text-center"><span class="badge 
                    <?= $app['status']=='Pending'?'bg-warning':
                        ($app['status']=='Approved'?'bg-success':
                        ($app['status']=='Completed'?'bg-info':'bg-danger')) ?>">
                    <?= esc($app['status']) ?></span></td>
                  <td>
                    <div class="action-buttons">
                      <form method="post" action="<?= base_url('appointments/update-status/' . $app['id']) ?>">
                        <?= csrf_field() ?>
                        <div class="action-row">
                          <select name="status" class="form-select form-select-sm status-dropdown">
                            <option value="Approved" <?= $app['status']=='Approved'?'selected':'' ?>>Approve</option>
                            <option value="Rejected" <?= $app['status']=='Rejected'?'selected':'' ?>>Reject</option>
                            <option value="Completed" <?= $app['status']=='Completed'?'selected':'' ?>>Completed</option>
                          </select>
                          <button type="submit" class="btn btn-sm btn-primary">Update</button>
                        </div>
                        <textarea name="rejection_reason" class="form-control form-control-sm rejection-box mt-2" id="rejection-box-<?= $app['id'] ?>" placeholder="Enter rejection reason..." style="<?= $app['status'] === 'Rejected' ? '' : 'display: none;' ?>"><?= esc($app['rejection_reason'] ?? '') ?></textarea>
                      </form>
                      
                      <!-- Delete Button -->
                      <form method="post" action="<?= base_url('appointments/delete/' . $app['id']) ?>" onsubmit="return confirm('Are you sure you want to delete this appointment? This action cannot be undone.')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-delete btn-sm w-100">
                          <i class='bx bx-trash'></i> Delete
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; else: ?>
                <tr><td colspan="7" class="text-center text-muted">No appointments found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Calendar with Legend -->
      <div class="calendar-legend">
        <div class="legend-item">
          <div class="legend-color" style="background-color: #059669;"></div>
          <span>Available</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #dc2626;"></div>
          <span>Fully Booked</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #f6c23e;"></div>
          <span>Pending</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #1cc88a;"></div>
          <span>Approved</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #36b9cc;"></div>
          <span>Completed</span>
        </div>
        <div class="legend-item">
          <div class="legend-color" style="background-color: #e74a3b;"></div>
          <span>Rejected</span>
        </div>
      </div>
      
      <div id="calendar"></div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    document.getElementById('sidebarToggle').addEventListener('click', () => {
      sidebar.classList.toggle('hide');
      content.classList.toggle('full');
    });

    document.querySelectorAll('.dropdown > a').forEach(el => {
      el.addEventListener('click', (e) => {
        e.preventDefault();
        el.parentElement.classList.toggle('open');
      });
    });

    // Handle status dropdown change to show/hide rejection reason
    document.querySelectorAll('select[name="status"]').forEach(select => {
      select.addEventListener('change', function() {
        const row = this.closest('tr');
        const rejectionBox = row.querySelector('.rejection-box');
        if (this.value === 'Rejected') {
          rejectionBox.style.display = 'block';
          rejectionBox.required = true;
        } else {
          rejectionBox.style.display = 'none';
          rejectionBox.required = false;
        }
      });
    });

    // Prepare events
    let events = <?php
      $calendarEvents = [];
      $appointmentsByDate = [];
      foreach ($appointments as $app) {
        $appointmentsByDate[$app['appointment_date']][] = $app;
        $color = $app['status']=='Approved'?'#1cc88a':
                 ($app['status']=='Rejected'?'#e74a3b':
                 ($app['status']=='Completed'?'#36b9cc':'#f6c23e'));
        $calendarEvents[] = [
          'title' => $app['fullname'] . ' - ' . $app['status'],
          'start' => $app['appointment_date'] . 'T' . $app['appointment_time'],
          'color' => $color,
        ];
      }
      echo json_encode($calendarEvents);
    ?>;

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 600,
      events: events,
      headerToolbar: { 
        left: 'prev,next today', 
        center: 'title', 
        right: 'dayGridMonth,timeGridWeek,timeGridDay' 
      },
      eventDisplay: 'block',
      dayMaxEvents: 3,
      moreLinkClick: 'popover',
      eventClick: function(info) {
        // You can add event click functionality here
        console.log('Event clicked:', info.event.title);
      },
      dayCellDidMount: function(info) {
        const dateStr = info.date.toISOString().split('T')[0];
        const booked = (<?php echo json_encode($appointmentsByDate); ?>)[dateStr] || [];
        const officeHours = ["08:00:00","09:00:00","10:00:00","11:00:00","13:00:00","14:00:00","15:00:00","16:00:00"];

        const bookedTimes = booked.map(a => a.appointment_time);
        const numberEl = info.el.querySelector('.fc-daygrid-day-number');

        if (!numberEl) return;

        if (bookedTimes.length >= officeHours.length - 1) { // exclude lunch
          numberEl.classList.add('fully-booked');
        } else if (bookedTimes.length > 0) {
          numberEl.classList.add('available');
        }
      }
    });

    calendar.render();
  });
  </script>
</body>
</html>