<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Anonymous Complaint Form</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

  <style>
    body {
      background: url('/images/cspcback.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', sans-serif;
      color: #333;
    }

    /* Sidebar */
    .sidebar {
      width: 240px;
      background: #0a3a5a;
      color: #fff;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 20px;
    }

    .sidebar .logo {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      margin-bottom: 12px;
      object-fit: cover;
    }

    .sidebar nav {
      width: 100%;
      margin-top: 20px;
    }

    .sidebar .nav-link {
      color: #fff;
      padding: 14px 32px;
      font-size: 1.05rem;
      border-radius: 0 24px 24px 0;
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      transition: background 0.2s ease;
    }

    .sidebar .nav-link i {
      margin-right: 10px;
      font-size: 1.3rem;
    }

    .sidebar .nav-link.active, 
    .sidebar .nav-link:hover {
      background: #195b8a;
      font-weight: 600;
    }

    /* Complaint Form Container */
    .form-wrapper {
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      max-width: 600px;
      margin: 40px auto; /* Center */
      position: relative;
      left: 120px; /* Push a bit to the right because of sidebar */
    }

    .form-wrapper h4 {
      font-weight: 600;
      margin-bottom: 25px;
      color: #002147;
      text-align: center;
    }

    .form-label {
      font-weight: 500;
    }

    .form-control:focus, 
    .form-select:focus {
      border-color: #007bff;
      box-shadow: 0 0 0 0.25rem rgba(0,123,255,.25);
    }

    .alert {
      border-radius: 10px;
    }

    .btn {
      border-radius: 30px;
      padding: 10px 20px;
      font-weight: 500;
      transition: 0.3s;
    }

    .btn-primary {
      background: linear-gradient(90deg, #003366, #0056b3, #007bff);
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(90deg, #002147, #004080, #0066cc);
    }

    .file-preview {
      margin-top: 10px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .file-preview div {
      background: #f1f1f1;
      border-radius: 8px;
      padding: 6px 12px;
      font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: row;
        justify-content: center;
      }

      .form-wrapper {
        margin: 20px auto;
        left: 0;
        max-width: 95%;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <img src="/images/logochre.jpg" alt="Logo" class="logo">
    <img src="/images/karamay.png" alt="KARAMAY Logo" style="width:140px;margin-bottom:18px;">
    <nav class="nav flex-column w-100">
      <a class="nav-link" href="/user/userdashboard"><i class='bx bx-home'></i> Home</a>
      <a class="nav-link active" href="<?= base_url('user/filing-complaint') ?>"><i class='bx bx-edit'></i> File Complaint</a>
      <a class="nav-link" href="/user/appointment"><i class='bx bx-calendar'></i> Book Appointment</a>
      <a class="nav-link" href="/user/view-complaint"><i class='bx bx-search'></i> My Complaints</a>
      <a class="nav-link" href="/user/view-appointments"><i class='bx bx-calendar-check'></i> My Appointments</a>
    </nav>
  </div>

  <!-- Complaint Form -->
  <div class="form-wrapper">
    <h4>Anonymous Complaint Form</h4>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= base_url('complaint/save_anonymous'); ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <!-- Date -->
      <div class="mb-3">
        <label class="form-label">Date of Incident</label>
        <input type="date" class="form-control" name="date" value="<?= old('date') ?>" max="<?= date('Y-m-d') ?>" required>
      </div>

      <!-- Location -->
      <div class="mb-3">
        <label class="form-label">Location of Incident</label>
        <input type="text" class="form-control" name="location" placeholder="Enter location" value="<?= old('location') ?>" required>
      </div>

      <div class="mb-3">
    <label for="complaint_type" class="form-label">Complaint Type</label>
    <select id="complaint_type" name="complaint_type" class="form-control" required>
        <option value="">-- Select Type --</option>
        <option value="academic" <?= old('complaint_type') === 'academic' ? 'selected' : '' ?>>Academic</option>
        <option value="non-academic" <?= old('complaint_type') === 'non-academic' ? 'selected' : '' ?>>Non-Academic</option>
    </select>
</div>

<div class="mb-3">
    <label for="complaint_category" class="form-label">Complaint Category</label>
    <select id="complaint_category" name="complaint_category" class="form-control" required>
        <option value="">-- Select Category --</option>
        <option value="Harassment" <?= old('complaint_category') === 'Harassment' ? 'selected' : '' ?>>Harassment</option>
        <option value="Bullying" <?= old('complaint_category') === 'Bullying' ? 'selected' : '' ?>>Bullying</option>
        <option value="Discrimination" <?= old('complaint_category') === 'Discrimination' ? 'selected' : '' ?>>Discrimination</option>
        <option value="Abuse of Authority" <?= old('complaint_category') === 'Abuse of Authority' ? 'selected' : '' ?>>Abuse of Authority</option>
        <option value="Cheating" <?= old('complaint_category') === 'Cheating' ? 'selected' : '' ?>>Cheating</option>
        <option value="Other" <?= old('complaint_category') === 'Other' ? 'selected' : '' ?>>Other</option>
    </select>
</div>


      <!-- Impact -->
      <div class="mb-3">
        <label class="form-label">Impact of the Incident</label>
        <textarea class="form-control" name="impact" rows="3" placeholder="Describe how the incident affected you..." required><?= old('impact') ?></textarea>
      </div>

      <!-- Description -->
      <div class="mb-3">
        <label class="form-label">Detailed Description</label>
        <textarea class="form-control" name="description" rows="4" placeholder="Detailed description of the incident" required><?= old('description') ?></textarea>
      </div>

      <!-- File Upload -->
      <div class="mb-3">
        <label class="form-label">Supporting Documents</label>
        <input type="file" name="files[]" id="fileInput" class="form-control" multiple>
        <div class="file-preview" id="filePreview"></div>
        <small class="text-muted">Max 5 files. Images, documents or videos only. Max 100MB per file.</small>
      </div>
      <input type="hidden" name="resolution" value="N/A">

      <!-- Certification -->
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" required>
        <label class="form-check-label">I hereby certify the correctness of the foregoing.</label>
      </div>

      <p class="text-muted" style="font-size: 0.9rem;">
        Your information will be handled confidentially under RA 10173 (Data Privacy Act of 2012).
      </p>

      <!-- Buttons -->
      <div class="d-flex justify-content-between">
        <a href="<?= base_url('user/filing-complaint'); ?>" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // File upload preview
    document.getElementById('fileInput').addEventListener('change', function() {
      const preview = document.getElementById('filePreview');
      preview.innerHTML = "";
      Array.from(this.files).forEach(file => {
        const div = document.createElement('div');
        div.textContent = file.name;
        preview.appendChild(div);
      });
    });
  </script>
</body>
</html>
