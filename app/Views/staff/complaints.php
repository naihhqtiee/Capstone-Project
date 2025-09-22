<?php
helper('text'); 

use App\Models\ComplaintModel;
$model = new ComplaintModel();

// Fetch complaints for table
$complaints = $model->select('
        complaints.*,
        accounts.full_name,
        accounts.email,
        students.contact_number
    ')
    ->join('accounts', 'accounts.id = complaints.user_id', 'left')
    ->join('students', 'students.account_id = accounts.id', 'left')
    ->orderBy('complaints.created_at', 'DESC')
    ->findAll();

// ✅ Real-time counts directly from DB
$total    = $model->countAllResults(); 
$model->resetQuery();

$pending  = $model->where('status', 'pending')->countAllResults();
$model->resetQuery();

$ongoing  = $model->where('status', 'ongoing')->countAllResults();
$model->resetQuery();

$resolved = $model->where('status', 'resolved')->countAllResults();
$model->resetQuery();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Complaints</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4e73df;
      --primary-hover: #3756c0;
      --background-color: #f8f9fc;
      --text-color: #212529;
    }
    body { font-family: 'Segoe UI', sans-serif; background-color: var(--background-color); margin: 0; }
    .layout { display: flex; min-height: 100vh; transition: margin-left 0.3s ease; }

    /* Sidebar */
    .sidebar { 
      width: 260px; 
      background-color: #fff; 
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
    .sidebar.hide { transform: translateX(-100%); }

    /* Sidebar toggle button */
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
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: left 0.3s ease;
    }
    .sidebar.hide + .sidebar-toggle {
      left: 15px;
    }

    /* Content */
    .content { 
      flex-grow: 1; 
      margin-left: 260px; 
      padding: 1.5rem; 
      transition: margin-left 0.3s ease;
    }
    .content.full { margin-left: 0; }

    /* Sidebar links */
    .sidebar ul { list-style: none; padding: 0; margin: 0; }
    .sidebar a { display: flex; align-items: center; padding: 14px 22px; color: var(--text-color); font-size: 1rem; text-decoration: none; transition: background 0.2s ease; }
    .sidebar a:hover, .sidebar a.active { background-color: #eef1f8; font-weight: 600; }

    /* Cards and Table */
    .stat-card { border: none; border-radius: 0.75rem; color: #fff; }
    .stat-card .card-body { text-align: center; padding: 1.2rem; }
    .table th { background-color: #f1f3f9; }
    .status-pending { background-color: #ffc107; color: #212529; font-weight: bold; padding: 2px 8px; border-radius: 4px; }
    .status-ongoing { background-color: #17a2b8; color: #fff; font-weight: bold; padding: 2px 8px; border-radius: 4px; }
    .status-resolved { background-color: #28a745; color: #fff; font-weight: bold; padding: 2px 8px; border-radius: 4px; }

    @media (max-width: 768px) {
      .content { margin-left: 0; }
      .sidebar { transform: translateX(-100%); }
      .sidebar.show { transform: translateX(0); }
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
      <li><a class="nav-link" href="<?= base_url('staff/dashboard') ?>"><i class='bx bx-grid-alt me-3'></i> Dashboard</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/opcr-checklist') ?>"><i class='bx bx-task me-3'></i> OPCR Checklist</a></li>
      <li><a class="nav-link active" href="<?= base_url('staff/complaints'); ?>"><i class='bx bx-message-square-error me-3'></i> Complaints</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/appointments') ?>"><i class='bx bx-calendar-check me-3'></i> Appointments</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/events') ?>"><i class='bx bx-calendar-event me-3'></i> Events</a></li>
      <li><a class="nav-link" href="<?= base_url('staff/students') ?>"><i class='bx bx-user-voice me-3'></i> Students</a></li>
      <li><a class="nav-link" href="#"><i class='bx bx-id-card me-3'></i> CHRE Staff <span class="badge bg-primary ms-auto">1</span></a></li>
    </ul>
    <a href="<?= base_url('logout') ?>" class="btn btn-danger m-3"><i class='bx bx-log-out me-2'></i> Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content" id="content">
    <h3 class="mb-4">Complaints Dashboard</h3>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card stat-card shadow-sm bg-primary">
          <div class="card-body">
            <i class='bx bx-error-circle'></i>
            <h6>Total</h6>
            <h3><?= esc($total) ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card stat-card shadow-sm bg-warning text-dark">
          <div class="card-body">
            <i class='bx bx-time'></i>
            <h6>Pending</h6>
            <h3><?= esc($pending) ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card stat-card shadow-sm bg-info">
          <div class="card-body">
            <i class='bx bx-refresh'></i>
            <h6>Ongoing</h6>
            <h3><?= esc($ongoing) ?></h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card stat-card shadow-sm bg-success">
          <div class="card-body">
            <i class='bx bx-check-circle'></i>
            <h6>Resolved</h6>
            <h3><?= esc($resolved) ?></h3>
          </div>
        </div>
      </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="table-responsive shadow-sm">
      <table class="table table-bordered table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Category</th>
            <th>Type</th>
            <th>Description</th>
            <th>Submitted By</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php if (!empty($complaints)): ?>
          <?php foreach ($complaints as $index => $c): ?>
          <tr>
            <td><?= $index+1 ?></td>
            <td><?= esc($c['complaint_category']) ?></td>
            <td><?= esc($c['complaint_type']) ?></td>
            <td><?= esc(word_limiter(strip_tags($c['description']), 10)) ?></td>
            <td>
              <?php if (empty($c['is_anonymous']) || $c['is_anonymous'] == 0): ?>
                <?= esc($c['full_name'] ?? 'Unknown') ?><br>
                <small><?= esc($c['email'] ?? '') ?></small>
              <?php else: ?>
                <span class="text-muted"><em>Anonymous</em></span>
              <?php endif; ?>
            </td>
            <td><?= esc($c['date']) ?></td>
            <td>
              <div class="card-footer d-flex justify-content-between align-items-center">
                <form action="<?= base_url('complaint/update-status/' . $c['id']) ?>" method="post" class="d-flex align-items-center">
                  <?= csrf_field() ?>
                  <select class="form-select form-select-sm complaint-status" data-id="<?= $c['id'] ?>">
                    <option value="pending"  <?= $c['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="ongoing"  <?= $c['status'] === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                    <option value="resolved" <?= $c['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                  </select>
                </form>
              </div>
            </td>
            <td>
              <a href="<?= base_url('complaint/view/' . $c['id']) ?>" class="btn btn-sm btn-info">View</a>
              <form action="<?= base_url('complaint/delete/' . $c['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure?');">
                <?= csrf_field() ?>
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8" class="text-center text-muted">No complaints found</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });

  document.querySelectorAll('.complaint-status').forEach(select => {
    select.addEventListener('change', function() {
      const complaintId = this.dataset.id;
      const newStatus = this.value;
      fetch(`<?= base_url('complaint/update-status') ?>/${complaintId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: `status=${newStatus}`
      })
      .then(res => res.ok ? location.reload() : alert('Failed to update status'));
    });
  });
</script>
</body>
</html>
