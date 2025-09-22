<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Students List</title>
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

    .layout {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
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
      transition: background-color 0.2s ease;
    }

    .sidebar-toggle:hover {
      background-color: var(--background-color);
    }

    /* Content */
    .content {
      flex-grow: 1;
      margin-left: 260px;
      transition: margin-left 0.3s ease;
      padding: 20px;
    }

    .content.full {
      margin-left: 0;
    }

    @media (max-width: 992px) {
      .content {
        margin-left: 0;
      }
    }

    /* ✅ Excel-like table */
    table.excel-table {
      width: 100%;
      border-collapse: collapse;
      border: 1px solid #b2b2b2;
      font-size: 14px;
      background-color: #fff;
    }

    table.excel-table thead th {
      background-color: #d9ead3; /* Excel light green header */
      border: 1px solid #b2b2b2;
      padding: 8px;
      text-align: left;
      font-weight: bold;
    }

    table.excel-table tbody td {
      border: 1px solid #b2b2b2;
      padding: 6px 8px;
    }

    table.excel-table tbody tr:nth-child(even) {
      background-color: #f9f9f9; /* light striping */
    }

    table.excel-table tbody tr:hover {
      background-color: #e6f0ff; /* light blue highlight */
    }

    .table-actions {
      margin-bottom: 15px;
      display: flex;
      gap: 10px;
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
      <li><a class="nav-link" href="<?= base_url('staff/events') ?>"><i class='bx bx-calendar-event me-3'></i> Events</a></li>
     <li><a class="nav-link active" href="<?= base_url('staff/students') ?>"><i class='bx bx-user-voice me-3'></i> Students</a></li>
      <li><a class="nav-link" href="#"><i class='bx bx-id-card me-3'></i> CHRE Staff <span class="badge bg-primary ms-auto">1</span></a></li>
    </ul>
    <a href="<?= base_url('logout') ?>" class="btn btn-danger m-3"><i class='bx bx-log-out me-2'></i> Logout</a>
  </div>

  <!-- Content -->
  <div class="content" id="content">
    <div class="container-fluid mt-4">
      <h2 class="mb-4">Students List</h2>

      <!-- ✅ Action buttons -->
      <div class="table-actions">
        <button class="btn btn-success btn-sm" onclick="downloadExcel()">
          <i class='bx bx-file me-1'></i> Excel
        </button>
        <button class="btn btn-danger btn-sm" onclick="downloadPDF()">
          <i class='bx bx-file me-1'></i> PDF
        </button>
      </div>

      <div class="table-responsive">
        <table class="excel-table" id="studentsTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Contact</th>
              <th>Department</th>
              <th>Course</th>
              <th>Year</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($students)): ?>
              <?php foreach ($students as $index => $student): ?>
                <tr>
                  <td><?= $index + 1 ?></td>
                  <td><?= esc($student['first_name'].' '.$student['mi'].'. '.$student['last_name']) ?></td>
                  <td><?= esc($student['email']) ?></td>
                  <td><?= esc($student['contact_number']) ?></td>
                  <td><?= esc($student['department']) ?></td>
                  <td><?= esc($student['course']) ?></td>
                  <td><?= esc($student['year']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" class="text-center">No students found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ✅ JS Libraries for Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

  // ✅ Export Excel
  function downloadExcel() {
    let table = document.getElementById("studentsTable");
    let workbook = XLSX.utils.table_to_book(table, { sheet: "Students" });
    XLSX.writeFile(workbook, "students.xlsx");
  }

  // ✅ Export PDF
  function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Students List", 14, 16);
    doc.autoTable({ html: "#studentsTable", startY: 20 });
    doc.save("students.pdf");
  }
</script>
</body>
</html>
