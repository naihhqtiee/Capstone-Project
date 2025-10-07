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
          <a class="nav-link" href="events">
            <i class='bx bx-calendar-event'></i>Events
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="students">
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
