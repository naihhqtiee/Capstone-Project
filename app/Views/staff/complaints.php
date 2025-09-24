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

// Check for existing NDA file
$ndaFile = null;
$ndaPath = WRITEPATH . 'uploads/nda/current_nda.pdf';
if (file_exists($ndaPath)) {
    $ndaFile = [
        'name' => 'current_nda.pdf',
        'size' => filesize($ndaPath),
        'uploaded_at' => date('Y-m-d H:i:s', filemtime($ndaPath))
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - Complaints & NDA Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #4e73df;
      --primary-hover: #3756c0;
      --background-color: #f8f9fc;
      --text-color: #212529;
      --success-color: #28a745;
      --warning-color: #ffc107;
      --danger-color: #dc3545;
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

    /* NDA Management Styles */
    .nda-section {
      background: linear-gradient(135deg, rgba(78, 115, 223, 0.1), rgba(255, 255, 255, 0.9));
      border-radius: 15px;
      padding: 25px;
      margin-bottom: 25px;
      border: 1px solid rgba(78, 115, 223, 0.2);
    }

    .nda-upload-area {
      border: 2px dashed #e2e8f0;
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      background: rgba(248, 250, 252, 0.5);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .nda-upload-area:hover,
    .nda-upload-area.dragover {
      border-color: var(--primary-color);
      background: rgba(78, 115, 223, 0.05);
    }

    .nda-upload-area i {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 15px;
    }

    .current-nda {
      background: rgba(40, 167, 69, 0.1);
      border: 1px solid rgba(40, 167, 69, 0.3);
      border-radius: 10px;
      padding: 20px;
      margin-top: 20px;
    }

    .btn {
      border-radius: 8px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .alert {
      border-radius: 10px;
      border: none;
    }

    @media (max-width: 768px) {
      .content { margin-left: 0; }
      .sidebar { transform: translateX(-100%); }
      .sidebar.show { transform: translateX(0); }
      .nda-section { padding: 20px 15px; }
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
    <h3 class="mb-4"><i class='bx bx-message-square-error me-2'></i>Complaints Dashboard & NDA Management</h3>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <i class='bx bx-check-circle me-2'></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <i class='bx bx-error me-2'></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- NDA Management Section -->
    <div class="nda-section">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class='bx bx-file-blank me-2'></i>NDA Management</h4>
        <small class="text-muted">Manage the NDA file that users will see when filing complaints</small>
      </div>

     <?php if ($ndaFile): ?>
  <!-- Current NDA Display -->
  <div class="current-nda">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h6 class="text-success mb-2"><i class='bx bx-check-circle me-2'></i>Current NDA File</h6>
        <p class="mb-1"><strong>File:</strong> <?= esc(basename($ndaFile['file_path'])) ?></p>
        <p class="mb-1"><strong>Size:</strong> <?= number_format(filesize(FCPATH . $ndaFile['file_path']) / 1024, 2) ?> KB</p>
        <p class="mb-0"><strong>Uploaded:</strong> <?= date('M d, Y H:i', strtotime($ndaFile['uploaded_at'])) ?></p>
      </div>
      <div class="col-md-4 text-end">
        <a href="<?= base_url('staff/download-nda') ?>" class="btn btn-outline-primary btn-sm me-2">
          <i class='bx bx-download'></i> Download
        </a>
        <a href="<?= base_url('staff/view-nda') ?>" class="btn btn-primary btn-sm me-2" target="_blank">
          <i class='bx bx-show'></i> View
        </a>
        <form action="<?= base_url('staff/delete-nda/' . $ndaFile['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this NDA file?');">
          <?= csrf_field() ?>
          <button class="btn btn-danger btn-sm"><i class='bx bx-trash'></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="alert alert-warning">
    <i class='bx bx-info-circle me-2'></i>
    <strong>No NDA file uploaded.</strong>
  </div>
<?php endif; ?>

      <!-- Upload New NDA -->
      <div class="mt-4">
        <h6><i class='bx bx-upload me-2'></i><?= $ndaFile ? 'Replace NDA File' : 'Upload NDA File' ?></h6>
        
        <form id="ndaForm" action="<?= base_url('staff/upload-nda') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          
          <div class="nda-upload-area" id="ndaUploadArea">
            <i class='bx bx-cloud-upload'></i>
            <h5>Drag & Drop NDA File Here</h5>
            <p class="text-muted mb-3">or <span style="color: var(--primary-color); cursor: pointer; text-decoration: underline;" onclick="document.getElementById('ndaFile').click()">browse files</span></p>
            <small class="text-muted">Only PDF files allowed. Maximum size: 5MB</small>
            <input type="file" id="ndaFile" name="nda_file" accept=".pdf" style="display: none;" required>
          </div>

          <div id="selectedFile" style="display: none;" class="mt-3">
            <div class="alert alert-info">
              <i class='bx bx-file-pdf me-2'></i>
              <span id="fileName"></span>
              <button type="button" class="btn btn-sm btn-outline-danger float-end" onclick="clearFile()">
                <i class='bx bx-x'></i>
              </button>
            </div>
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
              <i class='bx bx-upload'></i> Upload NDA
            </button>
            <button type="button" class="btn btn-secondary" onclick="clearFile()">
              <i class='bx bx-x'></i> Clear
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Complaints Stats Row -->
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

    <!-- Complaints Table -->
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class='bx bx-list-ul me-2'></i>All Complaints</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
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
                <td><span class="badge bg-secondary"><?= esc($c['complaint_category']) ?></span></td>
                <td><?= esc($c['complaint_type']) ?></td>
                <td>
                  <div style="max-width: 200px;">
                    <?= esc(word_limiter(strip_tags($c['description']), 10)) ?>
                  </div>
                </td>
                <td>
                  <?php if (empty($c['is_anonymous']) || $c['is_anonymous'] == 0): ?>
                    <div>
                      <strong><?= esc($c['full_name'] ?? 'Unknown') ?></strong><br>
                      <small class="text-muted"><?= esc($c['email'] ?? '') ?></small>
                    </div>
                  <?php else: ?>
                    <span class="text-muted"><em><i class='bx bx-user-x'></i> Anonymous</em></span>
                  <?php endif; ?>
                </td>
                <td><?= date('M d, Y', strtotime($c['date'])) ?></td>
                <td>
                  <form action="<?= base_url('complaint/update-status/' . $c['id']) ?>" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <select class="form-select form-select-sm complaint-status" data-id="<?= $c['id'] ?>">
                      <option value="pending"  <?= $c['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                      <option value="ongoing"  <?= $c['status'] === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                      <option value="resolved" <?= $c['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                  </form>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-primary me-1" onclick="viewComplaint(<?= $c['id'] ?>)" title="View Details">
                    <i class='bx bx-show'></i>
                  </button>
                  <form action="<?= base_url('complaint/delete/' . $c['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this complaint?');">
                    <?= csrf_field() ?>
                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                      <i class='bx bx-trash'></i>
                    </button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center text-muted py-4">No complaints found</td></tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Sidebar toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  sidebarToggle.addEventListener('click', () => {
    sidebar.classList.toggle('hide');
    content.classList.toggle('full');
  });

  // NDA File Upload
  const ndaUploadArea = document.getElementById('ndaUploadArea');
  const ndaFileInput = document.getElementById('ndaFile');
  const selectedFile = document.getElementById('selectedFile');
  const fileName = document.getElementById('fileName');
  const uploadBtn = document.getElementById('uploadBtn');
  const ndaForm = document.getElementById('ndaForm');

  // Click to select file
  ndaUploadArea.addEventListener('click', () => {
    ndaFileInput.click();
  });

  // File input change
  ndaFileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      handleNDAFile(file);
    }
  });

  // Drag and drop
  ndaUploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    ndaUploadArea.classList.add('dragover');
  });

  ndaUploadArea.addEventListener('dragleave', (e) => {
    e.preventDefault();
    ndaUploadArea.classList.remove('dragover');
  });

  ndaUploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    ndaUploadArea.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) {
      ndaFileInput.files = e.dataTransfer.files;
      handleNDAFile(file);
    }
  });

  function handleNDAFile(file) {
    if (file.type !== 'application/pdf') {
      alert('Please select a PDF file only.');
      clearFile();
      return;
    }

    if (file.size > 5 * 1024 * 1024) {
      alert('File size must be less than 5MB.');
      clearFile();
      return;
    }

    fileName.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
    selectedFile.style.display = 'block';
    uploadBtn.disabled = false;
  }

  function clearFile() {
    ndaFileInput.value = '';
    selectedFile.style.display = 'none';
    uploadBtn.disabled = true;
    ndaUploadArea.classList.remove('dragover');
  }

  // Complaint status updates
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
      .then(res => res.ok ? location.reload() : alert('Failed to update status'))
      .catch(err => alert('Error updating status'));
    });
  });

  // View complaint details (you can implement this modal)
  function viewComplaint(complaintId) {
    // Implement complaint details modal or redirect
    alert('View complaint details - ID: ' + complaintId);
  }
</script>
</body>
</html>