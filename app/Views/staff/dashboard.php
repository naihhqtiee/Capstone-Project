<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    .stat-card { border: none; border-radius: 0.75rem; color: #fff; }
    .stat-card .card-body { text-align: center; padding: 1.5rem; }
    .stat-card i { font-size: 2.5rem; margin-bottom: 0.5rem; }
    .recent-complaints { max-height: 400px; overflow-y: auto; }
    @media (max-width: 992px) { .content { margin-left: 0; } }
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
      <li><a class="nav-link active" href="dashboard"><i class='bx bx-grid-alt me-3'></i> Dashboard</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/opcr-checklist') ?>"><i class='bx bx-task me-3'></i> OPCR Checklist</a></li>
      <li class="dropdown">
        <a class="nav-link " href="<?= base_url('staff/complaints'); ?>" ><i class='bx bx-message-square-error me-3'></i> Complaints</a>
      </li>
      <li><a class="nav-link" href="<?= base_url('staff/appointments') ?>"><i class='bx bx-calendar-check me-3'></i> Appointments</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/events') ?>"><i class='bx bx-calendar-event me-3'></i> Events</a></li>
     <li><a class="nav-link" href="<?= base_url('staff/students') ?>"><i class='bx bx-user-voice me-3'></i> Students</a></li>
      <li><a class="nav-link" href="#"><i class='bx bx-id-card me-3'></i> CHRE Staff <span class="badge bg-primary ms-auto">1</span></a></li>
    </ul>
    <a href="<?= base_url('logout') ?>" class="btn btn-danger m-3"><i class='bx bx-log-out me-2'></i> Logout</a>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <nav class="navbar d-flex align-items-center">
      <form class="d-flex align-items-center me-auto"></form>
      <div class="d-flex align-items-center bg-primary px-3 py-2 rounded-pill text-white" style="gap: 1rem;">
        <i class='bx bx-bell fs-4 position-relative'>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notification-count">0</span>
        </i>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2" style="width:40px; height:40px;">
              <i class="bx bx-user text-dark fs-4"></i>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
            <li class="px-3 py-2">
              <div class="d-flex align-items-center">
                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2" style="width:50px; height:50px;">
                  <i class="bx bx-user text-dark fs-3"></i>
                </div>
                <div>
                  <div class="fw-bold"><?= esc(session()->get('full_name')) ?></div>
                  <small class="text-muted"><?= esc(session()->get('email')) ?></small>
                </div>
              </div>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-2"></i> Account</a></li>
            <li><a class="dropdown-item" href="<?= base_url('logout') ?>" onclick="return confirm('Are you sure you want to log out?')"><i class="bx bx-log-out me-2"></i> Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="p-4">
      <h2>Dashboard</h2>
      <div class="row mt-4">
        <div class="col-md-4 mb-3">
          <div class="card stat-card bg-primary">
            <div class="card-body">
              <i class='bx bx-message-rounded-error'></i>
              <h6>Total Complaints</h6>
              <h4 id="total-complaints"><?= esc($total_complaints ?? 0); ?></h4>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card stat-card bg-warning text-dark">
            <div class="card-body">
              <i class='bx bx-time-five'></i>
              <h6>Pending Cases</h6>
              <h4 id="pending-complaints"><?= esc($pending_cases ?? 0); ?></h4>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card stat-card bg-success">
            <div class="card-body">
              <i class='bx bx-check-circle'></i>
              <h6>Resolved Cases</h6>
              <h4 id="resolved-complaints">0</h4>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
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

        <!-- Quick Actions -->
        <div class="col-md-4">
          <div class="card mb-3">
            <div class="card-header bg-primary text-white"><h5 class="mb-0">Quick Actions</h5></div>
            <div class="card-body bg-light">
              <div class="d-grid gap-2">
                <button class="btn btn-primary" onclick="showComplaints('pending')"><i class='bx bx-time-five me-2'></i> View Pending</button>
                <button class="btn btn-success" onclick="showComplaints('resolved')"><i class='bx bx-check-circle me-2'></i> View Resolved</button>
                <button class="btn btn-info text-white" onclick="showComplaints('anonymous')"><i class='bx bx-user-x me-2'></i> Anonymous Only</button>
                <button class="btn btn-warning text-dark" onclick="exportComplaints()"><i class='bx bx-download me-2'></i> Export Data</button>
              </div>
            </div>
          </div>

          <!-- Complaint Types -->
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

          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Add Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // Sidebar dropdown toggle
  document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', function(e) {
      if (this.closest('.sidebar')) {
        e.preventDefault();
        this.parentElement.classList.toggle('open');
      }
    });
  });

  // Sidebar toggle button
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');
  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });

  // Notifications
  function fetchNotifications() {
    fetch("<?= base_url('staff/notifications') ?>")
      .then(response => response.json())
      .then(data => {
        let badge = document.getElementById("notification-count");
        badge.textContent = data.total;
        badge.style.display = data.total === 0 ? "none" : "inline-block";
      })
      .catch(error => console.error("Error fetching notifications:", error));
  }
  setInterval(fetchNotifications, 10000);
  fetchNotifications();

  // Chart
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


  ocument.getElementById("loginBtn").addEventListener("click", function(e) {
    e.preventDefault(); // prevent page redirect

    Swal.fire({
      title: 'Login',
      html: `
        <form id="loginForm" action="<?= base_url('auth/login') ?>" method="post">
          <input type="email" name="email" class="swal2-input" placeholder="Email" required>
          <input type="password" name="password" class="swal2-input" placeholder="Password" required>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div style="margin-top:10px; font-size:14px;">
          Don't have an account? <a href="<?= base_url('register') ?>">Register</a>
        </div>
      `,
      showConfirmButton: false,  // remove default confirm
      focusConfirm: false,
      didOpen: () => {
        const form = Swal.getPopup().querySelector('#loginForm');
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          form.submit(); // submit actual login
        });
      }
    });
  });

  let idleTime = 0;
let warningShown = false;
const idleLimit = 30 * 60; // 30 min in seconds
const warningTime = 25 * 60; // show warning at 25 min

function resetIdle() {
    idleTime = 0;
    warningShown = false;
}

setInterval(() => {
    idleTime++;

    // Show warning 5 minutes before logout
    if (idleTime >= warningTime && !warningShown) {
        alert("⚠️ You have been inactive for 25 minutes. You will be logged out in 5 minutes if no activity occurs.");
        warningShown = true;
    }

    // Auto logout after idleLimit
    if (idleTime >= idleLimit) {
        window.location.href = "/logout"; // AuthController::logout
    }
}, 1000); // check every second

// Detect user activity
window.onload = resetIdle;
document.onmousemove = resetIdle;
document.onkeypress = resetIdle;
document.onclick = resetIdle;
</script>

</body>
</html>
