<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard - OPCR Checklist</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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
    }

    .content.full {
      margin-left: 0;
    }

    .navbar {
      background-color: #fff;
      border-bottom: 1px solid #e3e6f0;
      padding: 0.75rem 1.5rem;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    /* Progress stats clickable */
    .progress-clickable {
      cursor: pointer;
      padding: 5px 10px;
      border-radius: 5px;
      transition: background-color 0.2s;
    }

    .progress-clickable:hover {
      background-color: rgba(0,0,0,0.1);
    }

    /* OPCR Table styling */
    .opcr-table {
      background: white;
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .opcr-table th,
    .opcr-table td {
      border: 1px solid #e3e6f0;
      padding: 12px;
      vertical-align: top;
      font-size: 13px;
    }

    .opcr-table thead th {
      background: #f8f9fc;
      color: var(--text-color);
      font-weight: 700;
      text-transform: uppercase;
      font-size: 12px;
      text-align: left;
    }

    .opcr-table tbody tr:hover {
      background-color: #f8f9fa;
    }

    .opcr-table tbody tr.highlight-incomplete {
      background-color: #fff3cd !important;
      border-left: 4px solid #dc3545;
    }

    .col-mfo { width: 18%; }
    .col-indicator { width: 35%; }
    .col-accountable { width: 15%; }
    .col-status { width: 10%; text-align: center; }
    .col-remarks { width: 15%; }
    .col-actions { width: 7%; text-align: center; }

    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
    }

    .status-done {
      background: #d4edda;
      color: #155724;
    }

    .status-ongoing {
      background: #fff3cd;
      color: #856404;
    }

    .status-empty {
      color: #6c757d;
    }

    .action-btn {
      padding: 4px 8px;
      margin: 0 2px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 12px;
    }

    .btn-add { background: #28a745; color: white; }
    .btn-edit { background: #007bff; color: white; }
    .btn-delete { background: #dc3545; color: white; }

    /* Section headers */
    .section-header {
      background: #e3f2fd;
      color: #1565c0;
      font-weight: bold;
      text-align: center;
      padding: 15px;
      margin: 20px 0 10px 0;
      border-radius: 8px;
      border-left: 4px solid #2196f3;
    }

    .subsection-header {
      background: #f3e5f5;
      color: #7b1fa2;
      font-weight: bold;
      text-align: center;
      padding: 12px;
      margin: 15px 0 8px 0;
      border-radius: 6px;
      border-left: 4px solid #9c27b0;
    }

    .section-row { background:#e9eefb; font-weight:700; text-transform:uppercase; }
    .section-row td { padding:10px 12px; border:1px solid #e3e6f0; }

    /* Responsive */
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
      <li><a class="nav-link" href="dashboard"><i class='bx bx-grid-alt me-3'></i> Dashboard</a></li>
      <li><a class="nav-link active" href="<?= base_url('staff/opcr-checklist') ?>"><i class='bx bx-task me-3'></i> OPCR Checklist</a></li>
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
      <form class="d-flex align-items-center me-auto">
        <input class="form-control me-2" type="search" placeholder="Q Search" style="width: 200px;">
    </form>
      <div class="d-flex align-items-center" style="gap: 20px;">
        <span class="text-success fw-bold progress-clickable" id="completedPercent">90% Complete</span>
        <span class="text-danger fw-bold progress-clickable" id="incompletePercent">10% Not Completed</span>
        <button class="btn btn-outline-primary" onclick="downloadExcel()">Download Report</button>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="p-4">
      <h2 class="mb-4">OPCR Checklist</h2>
      
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="opcr-table" id="opcrTable">
              <thead>
                <tr>
                  <th class="col-mfo">MFO/PAP</th>
                  <th class="col-indicator">SUCCESS INDICATOR (TARGETS + MEASURES)</th>
                  <th class="col-accountable">UNIT/SECTION/INDIVIDUALS ACCOUNTABLE</th>
                  <th class="col-status">STATUS</th>
                  <th class="col-remarks">REMARKS</th>
                  <th class="col-actions">ACTIONS</th>
                </tr>
              </thead>
              <tbody>
                <!-- A. 2025 OPERATIONAL PLAN TARGETS -->
                <tr class="section-row">
                  <td colspan="6">A. 2025 Operational Plan Targets</td>
                </tr>
                <tr>
                  <td class="col-mfo">
                    <strong>Goal 3: Engaged Sustainable Communities</strong><br>
                    <strong>KRA 3: Extension Service</strong>
                  </td>
                  <td class="col-indicator">
                    <strong>3.1.</strong> Sustain awards received from local and international organizations<br>
                    <strong>3.1.1</strong> CHED-Philippine Anti-Illegal Drug Strategy (PADS) Innovative Awardee
                  </td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">
                    <button class="action-btn btn-add" title="Add">+</button>
                    <button class="action-btn btn-edit" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Delete">🗑</button>
                  </td>
                </tr>
                <!-- B. OFFICE CORE FUNCTIONS -->
                <tr class="section-row">
                  <td colspan="6">B. Office Core Functions</td>
                </tr>
                <tr>
                  <td class="col-mfo" rowspan="3">
                    <strong>1. Establishment of a system, standard or procedure in the implementation of Human Rights Education and related activities.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Approved CHRE operational guidelines within 2nd quarter of 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">
                    <button class="action-btn btn-add" title="Add">+</button>
                    <button class="action-btn btn-edit" title="Edit">✎</button>
                    <button class="action-btn btn-delete" title="Delete">🗑</button>
                  </td>
                </tr>
                <tr>
                  <td class="col-indicator"><strong>b.</strong> Conducted quarterly meetings for updates and monitoring...</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr>
                  <td class="col-indicator"><strong>c.</strong> Conducted radio programs with trained HR advocates weekly</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <!-- 2. Facilitate in the conduct... -->
                <tr data-status="ongoing">
                  <td class="col-mfo" rowspan="3">
                    <strong>2. Facilitate in the conduct of human rights education activities involving administrator/school heads, department heads, faculty and staff, students and community clientele on its own or in</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Signed MOU with IBP and RICCI in furtherance of Human Rights Education in the college and the rest of the adopted communities of CSPC.</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Published articles related to human rights in the SPARK Publications once every semester.</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="ongoing">
                  <td class="col-indicator"><strong>c.</strong> Submitted monitoring reports on the integration of GAD perspectives in the College's teaching and learning modalities to the immediate supervisor by May and December 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 3. Promote and advocate... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>3. Promote and advocate for the promotion of rights based approach to development and governance, especially among its partners in the local government</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Conducted Voter's Education Forum for CSPC employees and students within April 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 4. Establish network and partnerships... -->
                <tr data-status="done">
                  <td class="col-mfo" rowspan="2">
                    <strong>4. Establish network and partnerships with government and non‑government organization in the conduct of human rights education activities.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Conducted LANTANGAN V3.0: A Human Rights Education Forum within the 2nd quarter</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Conducted RA 8972 Solo Parent Act/Orientation Seminar with students‑solo parents within April and November</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 5. Coordinate with CHR Regional Office... -->
                <tr data-status="ongoing">
                  <td class="col-mfo" rowspan="2">
                    <strong>5. Coordinate with CHR Regional Office with regard to program, project and activities.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> IRIBANAN 2025: Empowering CHRE organization through capacity building within February 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="ongoing">
                  <td class="col-indicator"><strong>b.</strong> CHRE Accredited Level 1 by 1st quarter</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 6. Formulate the Center's Annual Plan of Action... -->
                <tr data-status="done">
                  <td class="col-mfo" rowspan="3">
                    <strong>6. Formulate the Center's Annual Plan of Action covering human rights education, information, dissemination, monitoring and evaluation.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Submitted calendar of activities for 2025 to VPAA on or before the first week of January of 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Distributed IEC Material within the campus, adopted barangay and other sectors of the society.</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>c.</strong> Submitted proposal for Benchlearning Activities in UP and other SUC's with CHRE</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 7. Submit semestral reports... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>7. Submit semestral reports of its accomplishments to the CHR and Office of the College President.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Submit semestral reports of its accomplishments to the CHR and Office of the College President within May and December 2025</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 8. Assist in the filing of the complaint... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>8. Assist in the filing of the complaint, if there is any, involving human rights violations in the institution.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Submitted Accomplishment report of cases related to Human Rights</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 9. Perform other duties... -->
                <tr data-status="done">
                  <td class="col-mfo">
                    <strong>9. Perform other duties and functions that may be assigned/delegated by the College/University President.</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Coordinated with Extension Community Services Unit on the application for CHED‑PADS Innovative Award as scheduled</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>

                <!-- 10. Client Satisfaction Management -->
                <tr data-status="done">
                  <td class="col-mfo" rowspan="3">
                    <strong>10. Client Satisfaction Management</strong>
                  </td>
                  <td class="col-indicator"><strong>a.</strong> Maintained a “Very Satisfactory Rating” on Client Satisfaction Measurement Survey</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>b.</strong> Received no Negative Feedback and Disagree/Strongly Disagree ratings on CSM Survey</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
                <tr data-status="done">
                  <td class="col-indicator"><strong>c.</strong> Received no Customer complaint</td>
                  <td class="col-accountable"></td>
                  <td class="col-status"></td>
                  <td class="col-remarks"></td>
                  <td class="col-actions">...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script>
function updatePercentages() {
  // Count only real data rows (those that have an Actions cell)
  const rows = [...document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr')]
    .filter(r => r.querySelector('td.col-actions'));

  const total = rows.length;

  // Completed = rows with a non-empty Status cell (e.g., “Done”).
  // Blank Status ⇒ Not Completed.
  const completed = rows.filter(r => (r.querySelector('td.col-status')?.textContent || '').trim().length > 0).length;

  const incomplete = total - completed;
  const completedPercent = total ? Math.round((completed / total) * 100) : 0;
  const incompletePercent = total ? Math.round((incomplete / total) * 100) : 0;

  document.getElementById('completedPercent').textContent = `${completedPercent}% Complete`;
  document.getElementById('incompletePercent').textContent = `${incompletePercent}% Not Completed`;
}
updatePercentages();
</script>

<script>
function tableToSheetWithMerges(tableEl) {
  // Build AOA and merges, skipping the Actions column
  const rows = Array.from(tableEl.querySelectorAll('tr'));
  const aoa = [];
  const merges = [];

  rows.forEach((tr, r) => {
    if (!aoa[r]) aoa[r] = [];
    let c = 0;

    Array.from(tr.children)
      .filter(td => !td.classList.contains('col-actions'))
      .forEach(td => {
        while (aoa[r][c] !== undefined) c++; // skip occupied cells

        const txt = (td.innerText || '').replace(/\u00a0/g,' ').trim();
        aoa[r][c] = txt;

        const cs = td.colSpan || 1;
        const rs = td.rowSpan || 1;

        if (cs > 1 || rs > 1) {
          merges.push({ s: { r, c }, e: { r: r + rs - 1, c: c + cs - 1 } });
          for (let rr = 0; rr < rs; rr++) {
            for (let cc = 0; cc < cs; cc++) {
              if (rr === 0 && cc === 0) continue;
              if (!aoa[r + rr]) aoa[r + rr] = [];
              aoa[r + rr][c + cc] = null; // mark occupied
            }
          }
        }
        c++;
      });
  });

  const ws = XLSX.utils.aoa_to_sheet(aoa);
  ws['!merges'] = merges;
  ws['!cols'] = [
    { wch: 36 }, // MFO/PAP
    { wch: 80 }, // Success Indicator
    { wch: 32 }, // Unit/Section/Individuals Accountable
    { wch: 14 }, // Status
    { wch: 48 }  // Remarks
  ];
  return ws;
}

function downloadExcel() {
  const wb = XLSX.utils.book_new();

  const t1 = document.getElementById('opcrTable');
  if (t1) XLSX.utils.book_append_sheet(
    wb,
    tableToSheetWithMerges(t1),
    'A. 2025 OP PLAN TARGETS'
  );

  const t2 = document.getElementById('opcrTable2');
  if (t2) XLSX.utils.book_append_sheet(
    wb,
    tableToSheetWithMerges(t2),
    'B. OFFICE CORE FUNCTIONS'
  );

  XLSX.writeFile(wb, 'OPCR_Checklist.xlsx');
}
</script>

<script>
  // Highlight incomplete rows
  function highlightIncomplete() {
    document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr')
      .forEach(r => r.classList.remove('highlight-incomplete'));

    const rows = [...document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr')]
      .filter(r => r.querySelector('td.col-actions')); // only data rows

    rows.forEach(r => {
      const txt = (r.querySelector('td.col-status')?.textContent || '').replace(/\u00a0/g,'').trim();
      if (txt === '') r.classList.add('highlight-incomplete'); // blank ⇒ not completed
    });
  }

  // Remove highlights
  function removeHighlights() {
    document.querySelectorAll('#opcrTable tbody tr, #opcrTable2 tbody tr').forEach(row => {
      row.classList.remove('highlight-incomplete');
    });
  }

  // Event listeners
  document.getElementById('incompletePercent').addEventListener('click', highlightIncomplete);
  document.getElementById('completedPercent').addEventListener('click', removeHighlights);

  // Sidebar dropdown toggle
  document.querySelectorAll('.dropdown-toggle').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentElement.classList.toggle('open');
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

  // Initialize percentages on page load
  // updatePercentages(); // This line is now handled by the new updatePercentages function
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const actionHTML = `
    <button type="button" class="action-btn btn-add" title="Add"><i class='bx bx-plus'></i></button>
    <button type="button" class="action-btn btn-edit" title="Edit"><i class='bx bx-edit'></i></button>
    <button type="button" class="action-btn btn-delete" title="Delete"><i class='bx bx-trash'></i></button>
  `;

  document.querySelectorAll('#opcrTable tbody td.col-actions, #opcrTable2 tbody td.col-actions')
    .forEach(td => {
      const raw = (td.textContent || '').trim();
      if (!td.innerHTML.trim() || raw === '...') {
        td.innerHTML = actionHTML;
      }
    });
  });
</script>

</body>
</html>