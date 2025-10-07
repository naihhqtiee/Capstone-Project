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
      --text-muted: #6c757d;
      --border-color: #e3e6f0;
      --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
      --card-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
      --hover-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
      --sidebar-width: 260px;
      --calendar-primary: #3b82f6;
      --calendar-hover: #2563eb;
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

    .content { 
      flex: 1;
      margin-left: var(--sidebar-width);
      transition: margin-left 0.3s ease;
      min-height: 100vh;
      padding: 20px;
    }

    .content.full { 
      margin-left: 0; 
    }

    .stats-card {
      border-radius: 12px;
      padding: 20px;
      color: white;
      text-align: center;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }

    .stats-card:hover {
      transform: translateY(-2px);
    }

    .stats-card .stats-number { 
      font-size: 2rem; 
      font-weight: bold; 
    }

    .pending { background: #f6c23e; }
    .approved { background: #1cc88a; }
    .completed { background: #36b9cc; }
    .rejected { background: #e74a3b; }

    .card {
      border: none;
      border-radius: 12px;
      box-shadow: var(--card-shadow);
      overflow: hidden;
    }

    .card-header {
      background: var(--primary-color) !important;
      color: white !important;
      border: none;
      padding: 1rem 1.5rem;
      font-size: 0.9rem;
      font-weight: 600;
    }

    .table thead th {
      background: #4e73df;
      color: white;
      text-align: center;
      border: none;
      font-weight: 500;
      font-size: 0.875rem;
    }

    .table td { 
      vertical-align: middle;
      padding: 1rem 0.75rem;
      border-color: #f1f3f4;
    }

    .table tbody tr:hover {
      background-color: #f8f9fc;
    }

    .status-dropdown { 
      min-width: 120px;
      font-size: 0.8rem;
    }
    
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
      border-radius: 6px;
      transition: all 0.2s ease;
    }

    .btn-delete:hover {
      background-color: #c82333;
      border-color: #bd2130;
      color: white;
      transform: translateY(-1px);
    }

    .btn-primary {
      background: var(--primary-color);
      border-color: var(--primary-color);
      transition: all 0.2s ease;
    }

    .btn-primary:hover {
      background: var(--primary-hover);
      border-color: var(--primary-hover);
      transform: translateY(-1px);
    }

    #calendar {
      background: white;
      padding: 20px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border: 1px solid #e5e7eb;
    }

    .fc-theme-standard .fc-view-harness {
      border-radius: 12px;
      overflow: hidden;
    }

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

    .fc-daygrid-day {
      background: white;
      border: 1px solid #f3f4f6 !important;
      transition: background-color 0.2s ease;
    }

    .fc-daygrid-day:hover {
      background-color: #f8fafc !important;
    }

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

    .fc-daygrid-day-number {
      color: #374151 !important;
      font-weight: 500 !important;
      font-size: 0.875rem !important;
      padding: 8px !important;
      text-decoration: none !important;
      display: inline-block;
      min-width: 28px;
      text-align: center;
    }

    .fc-daygrid-day-number.available { 
      color: white !important; 
      font-weight: 700 !important;
      background: #10b981 !important;
      border-radius: 50%;
      padding: 6px 10px !important;
      cursor: pointer;
      transition: all 0.2s ease;
    }
    
    .fc-daygrid-day-number.available:hover { 
      background: #059669 !important;
      transform: scale(1.05);
    }
    
    .fc-daygrid-day-number.fully-booked { 
      color: white !important; 
      font-weight: 700 !important;
      background: #dc2626 !important;
      border-radius: 50%;
      padding: 6px 10px !important;
      cursor: not-allowed;
    }

    .fc-day-today {
      background-color: rgba(59, 130, 246, 0.05) !important;
    }

    .fc-day-today .fc-daygrid-day-number:not(.available):not(.fully-booked) {
      background: var(--calendar-primary) !important;
      color: white !important;
      border-radius: 50% !important;
      padding: 6px 10px !important;
    }

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

    .fc-day-sat, .fc-day-sun {
      background-color: #fafafa !important;
    }

    .fc-day-other .fc-daygrid-day-number {
      color: #d1d5db !important;
    }

    .calendar-legend {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 15px;
      padding: 15px;
      background: white;
      border-radius: 12px;
      font-size: 0.875rem;
      box-shadow: var(--card-shadow);
      border: 1px solid var(--border-color);
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 500;
    }

    .legend-color {
      width: 16px;
      height: 16px;
      border-radius: 4px;
    }

    .form-select, .form-control {
      border-radius: 6px;
      border: 1px solid var(--border-color);
      transition: all 0.2s ease;
    }

    .form-select:focus, .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(78, 115, 223, 0.1);
    }

    .badge {
      font-size: 0.7rem;
      padding: 0.4rem 0.8rem;
      border-radius: 12px;
      font-weight: 500;
    }

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
      
      .calendar-legend {
        flex-wrap: wrap;
        gap: 10px;
      }
    }

    @media (max-width: 768px) {
      .fc-header-toolbar {
        flex-direction: column;
        gap: 10px;
      }
      
      .fc-toolbar-title {
        font-size: 1.25rem !important;
      }
      
      .stats-card .stats-number {
        font-size: 1.5rem;
      }
      
      .content {
        padding: 15px;
      }
    }
  </style>
</head>
<body>
<div class="sidebar-toggle" id="sidebarToggle">
  <i class='bx bx-menu'></i>
</div>

<div class="layout">
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
          <a class="nav-link active" href="appointments">
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
      <a href="#" class="logout-btn" onclick="logout()">
        <i class='bx bx-log-out me-2'></i>Logout
      </a>
    </div>
  </div>

  <div class="content" id="content">
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

    <div class="calendar-legend">
      <div class="legend-item"><div class="legend-color" style="background-color: #10b981;"></div><span>Available (Click to Remove)</span></div>
      <div class="legend-item"><div class="legend-color" style="background-color: #dc2626;"></div><span>Fully Booked</span></div>
      <div class="legend-item"><div class="legend-color" style="background-color: #f6c23e;"></div><span>Pending</span></div>
      <div class="legend-item"><div class="legend-color" style="background-color: #1cc88a;"></div><span>Approved</span></div>
      <div class="legend-item"><div class="legend-color" style="background-color: #36b9cc;"></div><span>Completed</span></div>
      <div class="legend-item"><div class="legend-color" style="background-color: #e74a3b;"></div><span>Rejected</span></div>
    </div>

    <div id="calendar"></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {

  /* ------------------------------
     SIDEBAR TOGGLE
  ------------------------------ */
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');
  document.getElementById('sidebarToggle').addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });

  /* ------------------------------
     REJECTION BOX TOGGLE
  ------------------------------ */
  document.querySelectorAll('select[name="status"]').forEach(select => {
    select.addEventListener('change', function() {
      const row = this.closest('tr');
      const rejectionBox = row.querySelector('.rejection-box');
      rejectionBox.style.display = this.value === 'Rejected' ? 'block' : 'none';
      rejectionBox.required = this.value === 'Rejected';
    });
  });

  /* ------------------------------
     EVENTS FROM DATABASE
  ------------------------------ */
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

  /* ------------------------------
     AVAILABLE DATES FROM DB
  ------------------------------ */
 let availableDates = <?= json_encode($availableDates ?? []); ?>;
console.log("Loaded available dates:", availableDates);


  const appointmentsByDate = <?php echo json_encode($appointmentsByDate); ?>;

  /* ------------------------------
     FULLCALENDAR INITIALIZATION
  ------------------------------ */
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

    /* --------------------------
       PAINT CELLS (on load)
    -------------------------- */
    dayCellDidMount: function(info) {
      const dateStr = info.date.toLocaleDateString('en-CA'); // gives YYYY-MM-DD in local timezone
      const numberEl = info.el.querySelector('.fc-daygrid-day-number');
      if (!numberEl) return;

      const booked = appointmentsByDate[dateStr] || [];
      const officeHours = ["08:00:00","09:00:00","10:00:00","11:00:00","13:00:00","14:00:00","15:00:00","16:00:00"];
      
      // Mark fully booked days
// If day has appointments booked at all, don't mark as available
if (booked.length > 0) {
  if (booked.length >= officeHours.length - 1) {
    numberEl.classList.add('fully-booked');
    numberEl.title = 'Fully booked - Cannot accept more appointments';
  } else {
    numberEl.title = booked.length + ' appointments scheduled';
  }
}
// Only mark as available if there are NO bookings that day
else if (availableDates.includes(dateStr)) {
  numberEl.classList.add('available');
  numberEl.title = 'Available for booking - Click to remove availability';
}

    },

    /* --------------------------
       DATE CLICK (add/remove)
    -------------------------- */
    dateClick: function(info) {
      const dateStr = info.date.toLocaleDateString('en-CA');
      const clickedElement = info.dayEl.querySelector('.fc-daygrid-day-number');
      
      // ✅ If date already available → remove it
      if (availableDates.includes(dateStr)) {
        if (confirm("Remove availability for " + dateStr + "?")) {
fetch("<?= base_url('appointment/remove-availability') ?>", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
  },
  body: JSON.stringify({ date: dateStr })
})
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert("✓ Availability removed for " + dateStr);
              availableDates = availableDates.filter(d => d !== dateStr);
              clickedElement.classList.remove('available');
              clickedElement.title = 'Click to mark as available';
              console.log('Updated available dates:', availableDates);
            } else {
              alert("Failed to remove availability: " + (data.message || 'Unknown error'));
            }
          })
          .catch(err => {
            console.error('Error:', err);
            alert("Error removing availability. Please try again.");
          });
        }
      } 
      // ❌ If not available → add it
      else {
        if (confirm("Mark " + dateStr + " as available?\nStudents will be able to book appointments on this date.")) {
fetch("<?= base_url('appointment/add-availability') ?>", {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "X-CSRF-TOKEN": "<?= csrf_hash() ?>"
  },
  body: JSON.stringify({ date: dateStr })
})
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert("✓ Date marked as available: " + dateStr);
              availableDates.push(dateStr);
              clickedElement.classList.add('available');
              clickedElement.title = 'Available for booking - Click to remove availability';
              console.log('Updated available dates:', availableDates);
            } else {
              alert("Failed to mark as available: " + (data.message || 'Unknown error'));
            }
          })
          .catch(err => {
            console.error('Error:', err);
            alert("Error marking date as available. Please try again.");
          });
        }
      }
    }
  });

  calendar.render();

  /* ------------------------------
     CSS HIGHLIGHTING (optional)
  ------------------------------ */
  const style = document.createElement('style');
  style.innerHTML = `
    .fc-daygrid-day-number.available {
      background-color: #19dd85ff !important;
      color: white !important;
      border-radius: 6px;
      padding: 2px 6px;
    }
    .fc-daygrid-day-number.fully-booked {
      background-color: #e74a3b !important;
      color: white !important;
      border-radius: 6px;
      padding: 2px 6px;
    }
  `;
  document.head.appendChild(style);
});
</script>
</body>
</html>
