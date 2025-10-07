
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KARAMAY - Set Appointment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">

  <style>
    :root {
      --primary-color: #1e40af;
      --secondary-color: #f59e0b;
      --success-color: #10b981;
      --danger-color: #ef4444;
      --warning-color: #f59e0b;
      --info-color: #3b82f6;
      --dark-color: #1f2937;
      --light-color: #f8fafc;
      --sidebar-width: 280px;
      --topbar-height: 70px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: var(--sidebar-width);
      height: 100vh;
      background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
      color: white;
      z-index: 1000;
      transition: all 0.3s ease;
      box-shadow: 4px 0 20px rgba(0,0,0,0.1);
      overflow-y: auto;
    }

    .sidebar.collapsed {
      width: 70px;
    }

    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      text-align: center;
    }

    .sidebar.collapsed .sidebar-header {
      padding: 20px 10px;
    }

    .logo {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-bottom: 10px;
      background: white;
      display: inline-block;
    }

    .sidebar.collapsed .logo {
      width: 40px;
      height: 40px;
    }

    .brand-name {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 5px;
      transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .brand-name {
      opacity: 0;
      display: none;
    }

    .nav-menu {
      padding: 20px 0;
    }

    .nav-item {
      margin-bottom: 5px;
    }

    .nav-link {
      display: flex;
      align-items: center;
      padding: 15px 20px;
      color: rgba(255,255,255,0.8);
      text-decoration: none;
      transition: all 0.3s ease;
      border-radius: 0 25px 25px 0;
      margin-right: 20px;
      position: relative;
    }

    .sidebar.collapsed .nav-link {
      justify-content: center;
      margin-right: 10px;
      border-radius: 10px;
      padding: 15px 10px;
    }

    .nav-link:hover, .nav-link.active {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .sidebar.collapsed .nav-link:hover,
    .sidebar.collapsed .nav-link.active {
      transform: translateX(0);
    }

    .nav-link i {
      font-size: 1.3rem;
      margin-right: 15px;
      min-width: 20px;
    }

    .sidebar.collapsed .nav-link i {
      margin-right: 0;
    }

    .nav-link span {
      font-size: 0.95rem;
      font-weight: 500;
      transition: opacity 0.3s ease;
    }

    .sidebar.collapsed .nav-link span {
      opacity: 0;
      display: none;
    }

    .main-content {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      transition: margin-left 0.3s ease;
    }

    .main-content.expanded {
      margin-left: 70px;
    }

    .topbar {
      height: var(--topbar-height);
      background: rgba(255,255,255,0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 30px;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .menu-toggle {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--dark-color);
      cursor: pointer;
      margin-right: 20px;
    }

    .page-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark-color);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .content-area {
      padding: 30px;
    }

    .appointment-form-container {
      background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
      backdrop-filter: blur(15px);
      border-radius: 25px;
      padding: 40px;
      margin: 0 auto;
      max-width: 800px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.15);
      border: 1px solid rgba(255,255,255,0.3);
      position: relative;
      overflow: hidden;
    }

    .appointment-form-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--success-color));
    }

    .appointment-form-container h3 {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      text-align: center;
      margin-bottom: 30px;
      position: relative;
    }

    .appointment-form-container h3::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      border-radius: 2px;
    }

    .form-label {
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-label i {
      color: var(--primary-color);
      font-size: 1.1rem;
    }

    .form-control, .form-select {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 15px 20px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: rgba(255,255,255,0.8);
      backdrop-filter: blur(5px);
    }

    .form-control:focus, .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
      outline: none;
      transform: translateY(-2px);
    }

    .form-control:hover, .form-select:hover {
      border-color: var(--secondary-color);
      transform: translateY(-1px);
    }

    .input-group-enhanced {
      position: relative;
      margin-bottom: 25px;
    }

    .input-group-enhanced .form-control {
      padding-left: 50px;
    }

    .input-group-enhanced .input-icon {
      position: absolute;
      left: 18px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--primary-color);
      font-size: 1.2rem;
      z-index: 5;
    }

    .btn {
      padding: 12px 30px;
      border-radius: 12px;
      font-weight: 600;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-size: 1rem;
      position: relative;
      overflow: hidden;
    }

    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn:hover::before {
      left: 100%;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), #3b82f6);
      color: white;
      box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(30, 64, 175, 0.4);
    }

    .btn-secondary {
      background: linear-gradient(135deg, #6b7280, #4b5563);
      color: white;
      box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
    }

    .btn-secondary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(107, 114, 128, 0.4);
    }

    #date {
      background-color: #fff;
      cursor: pointer;
    }

    .alert {
      border: none;
      border-radius: 15px;
      padding: 18px 24px;
      margin-bottom: 25px;
      font-weight: 500;
      position: relative;
      backdrop-filter: blur(10px);
    }

    .alert-success {
      background: rgba(16, 185, 129, 0.15);
      color: #065f46;
      border-left: 4px solid var(--success-color);
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.15);
      color: #991b1b;
      border-left: 4px solid var(--danger-color);
    }

    .alert-warning {
      background: rgba(245, 158, 11, 0.15);
      color: #92400e;
      border-left: 4px solid var(--warning-color);
    }

    .form-progress {
      position: relative;
      height: 6px;
      background: #e2e8f0;
      border-radius: 3px;
      margin-bottom: 30px;
      overflow: hidden;
    }

    .form-progress-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      border-radius: 3px;
      transition: width 0.3s ease;
      width: 0%;
    }

    .char-counter {
      font-size: 0.85rem;
      margin-top: 5px;
      font-weight: 500;
    }

    .char-counter.danger { color: var(--danger-color); }
    .char-counter.warning { color: var(--warning-color); }
    .char-counter.success { color: var(--success-color); }
    .char-counter.muted { color: #64748b; }

    .btn-loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .loading-spinner {
      width: 20px;
      height: 20px;
      border: 2px solid transparent;
      border-top: 2px solid currentColor;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .form-control.is-valid {
      border-color: var(--success-color);
    }

    .form-control.is-invalid {
      border-color: var(--danger-color);
    }

    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        width: var(--sidebar-width);
      }

      .sidebar.mobile-open {
        transform: translateX(0);
      }

      .main-content {
        margin-left: 0;
      }

      .main-content.expanded {
        margin-left: 0;
      }

      .topbar {
        padding: 0 15px;
      }

      .content-area {
        padding: 20px 15px;
      }

      .appointment-form-container {
        padding: 30px 20px;
        margin: 10px;
      }

      .appointment-form-container h3 {
        font-size: 1.5rem;
      }

      .btn {
        width: 100%;
        margin-bottom: 10px;
      }
    }

    @media (max-width: 480px) {
      .form-control, .form-select {
        padding: 12px 15px;
      }

      .input-group-enhanced .form-control {
        padding-left: 45px;
      }

      .input-group-enhanced .input-icon {
        left: 15px;
        font-size: 1.1rem;
      }
    }

    .fade-in {
      animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .slide-up {
      animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <img src="/images/logochre.jpg" alt="Logo" class="logo">
      <div class="brand-name">
        <img src="/images/karamay.png" alt="KARAMAY" style="width:120px;">
      </div>
    </div>
    
    <nav class="nav-menu">
      <div class="nav-item">
        <a href="/user/userdashboard" class="nav-link">
          <i class='bx bx-home'></i>
          <span>Dashboard</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/filing-complaint" class="nav-link">
          <i class='bx bx-edit-alt'></i>
          <span>File Complaint</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/appointment" class="nav-link active">
          <i class='bx bx-calendar-plus'></i>
          <span>Book Appointment</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/view-complaint" class="nav-link">
          <i class='bx bx-search-alt'></i>
          <span>My Complaints</span>
        </a>
      </div>
      <div class="nav-item">
        <a href="/user/view-appointments" class="nav-link">
          <i class='bx bx-calendar-check'></i>
          <span>My Appointments</span>
        </a>
      </div>
    </nav>
  </div>

  <div class="main-content" id="mainContent">
    <div class="topbar">
      <div style="display: flex; align-items: center;">
        <button class="menu-toggle" id="menuToggle">
          <i class='bx bx-menu'></i>
        </button>
        <div class="page-title">
          <i class='bx bx-calendar-plus'></i>
          Set Appointment
        </div>
      </div>
    </div>

    <div class="content-area">
      <div class="appointment-form-container fade-in">
        <h3><i class='bx bx-calendar-heart'></i> SET AN APPOINTMENT</h3>
        
        <div class="form-progress">
          <div class="form-progress-bar" id="progressBar"></div>
        </div>

        <div id="alertContainer"></div>

        <form method="post" id="appointmentForm" action="<?= base_url('appointment/set') ?>" class="slide-up">
          <?= csrf_field() ?>

          <div class="input-group-enhanced">
            <label for="fullname" class="form-label"><i class='bx bx-user'></i> Full Name *</label>
            <div style="position: relative;">
              <i class='bx bx-user input-icon'></i>
              <input type="text" class="form-control" id="fullname" name="fullname" 
                     value="<?= old('fullname') ?: session('full_name') ?>"
                     placeholder="Enter your full name" required>
            </div>
          </div>

          <div class="input-group-enhanced">
            <label for="email" class="form-label"><i class='bx bx-envelope'></i> Email Address *</label>
            <div style="position: relative;">
              <i class='bx bx-envelope input-icon'></i>
              <input type="email" class="form-control" id="email" name="email" 
                     value="<?= old('email') ?: session('email') ?>"
                     placeholder="Enter your email address" required>
            </div>
          </div>

          <div class="input-group-enhanced">
            <label for="contactnumber" class="form-label"><i class='bx bx-phone'></i> Contact Number *</label>
            <div style="position: relative;">
              <i class='bx bx-phone input-icon'></i>
              <input type="tel" class="form-control" id="contactnumber" name="contactnumber" 
                     value="<?= old('contactnumber') ?: session('contact_number') ?>"
                     placeholder="Enter your contact number" required
                     pattern="[0-9]{11}" title="Please enter a valid 11-digit phone number">
            </div>
            <small class="text-muted">Format: 11 digits number (e.g., 09123456789)</small>
          </div>

          <div class="row mb-4">
            <div class="col-md-7 mb-3">
              <label for="date" class="form-label"><i class='bx bx-calendar'></i> Date of Appointment *</label>
              <div style="position: relative;">
                <i class='bx bx-calendar input-icon'></i>
                <input type="text" class="form-control" id="date" name="date" placeholder="Click to select date" required readonly>
              </div>
              <small class="text-muted">Click to select from available dates (Mon-Fri)</small>
            </div>

            <div class="col-md-5 mb-3">
              <label for="time" class="form-label"><i class='bx bx-time'></i> Preferred Time *</label>
              <div style="position: relative;">
                <i class='bx bx-time input-icon'></i>
                <select class="form-control" id="time" name="time" required>
                  <option value="">Select Date First</option>
                </select>
              </div>
            </div>
          </div>

          <div class="input-group-enhanced">
            <label for="purpose" class="form-label"><i class='bx bx-edit'></i> Purpose of Appointment *</label>
            <textarea class="form-control" id="purpose" name="purpose" rows="4"
                      placeholder="Please describe the purpose of your appointment in detail..."
                      maxlength="500" required><?= old('purpose') ?></textarea>
            <div class="char-counter muted" id="charCounter">0/500 characters</div>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-4">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
              <i class='bx bx-arrow-back'></i> Back
            </button>
            <button type="submit" class="btn btn-primary" id="submitBtn">
              <i class='bx bx-calendar-check'></i> Set Appointment
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="mobile-overlay" id="mobileOverlay" onclick="closeMobileSidebar()" 
       style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

  <script>
    let availableDates = [];
    let flatpickrInstance = null;

  
    function fetchAvailableDates() {
      console.log('🔄 Fetching available dates...');
      fetch("<?= base_url('appointment/getAvailableDates') ?>")
        .then(res => {
          if (!res.ok) throw new Error('Network response was not ok');
          return res.json();
        })
        .then(data => {
          console.log("📅 Raw data from server:", data);
          
          // Ensure we have an array of date strings
          if (Array.isArray(data)) {
            availableDates = data.map(date => {
              // Ensure consistent YYYY-MM-DD format
              if (typeof date === 'string') {
                return date.split('T')[0]; // Remove time if present
              }
              return date;
            });
          } else if (data.available && Array.isArray(data.available)) {
            availableDates = data.available.map(date => {
              if (typeof date === 'string') {
                return date.split('T')[0];
              }
              return date;
            });
          } else {
            console.error("Unexpected data format:", data);
            availableDates = [];
          }

          console.log("✅ Available Dates Loaded:", availableDates);

          if (availableDates.length === 0) {
            showAlert('No available dates found. Please contact the administrator.', 'warning');
          } else {
            showAlert(`${availableDates.length} dates available for booking.`, 'success');
          }

          initializeDatePicker();
        })
        .catch(err => {
          console.error("❌ Error fetching available dates:", err);
          showAlert('Failed to load available dates. Please refresh the page.', 'danger');
        });
    }

    function initializeDatePicker() {
      if (flatpickrInstance) {
        flatpickrInstance.destroy();
      }

      flatpickrInstance = flatpickr("#date", {
        dateFormat: "Y-m-d",
        enable: availableDates.length > 0 ? availableDates : [],
        minDate: "today",
        disableMobile: true,
        clickOpens: true,
        allowInput: false,
        onDayCreate: function(dObj, dStr, fp, dayElem) {
          const date = dayElem.dateObj.toISOString().split('T')[0];
          if (availableDates.includes(date)) {
            dayElem.style.backgroundColor = "#4CAF50";
            dayElem.style.color = "white";
            dayElem.style.fontWeight = "bold";
            dayElem.title = "Available for booking";
          }
        },
        onChange: function(selectedDates, dateStr) {
          if (dateStr) {
            console.log("📆 Date selected:", dateStr);
            fetchAvailableSlots(dateStr);
            dateInput.classList.add('is-valid');
            dateInput.classList.remove('is-invalid');
          }
        },
        onOpen: function() {
          console.log("📅 Calendar opened. Available dates:", availableDates);
        }
      });

      console.log("🔧 Flatpickr initialized with", availableDates.length, "available dates");
    }

    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const menuToggle = document.getElementById('menuToggle');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const dateInput = document.getElementById('date');
    const timeDropdown = document.getElementById('time');
    const purposeField = document.getElementById('purpose');
    const charCounter = document.getElementById('charCounter');
    const form = document.getElementById('appointmentForm');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');

    function isMobile() {
      return window.innerWidth <= 768;
    }

    function formatTime(time24) {
      const [hours, minutes] = time24.split(':');
      const hour = parseInt(hours);
      const ampm = hour >= 12 ? 'PM' : 'AM';
      const displayHour = hour === 0 ? 12 : hour > 12 ? hour - 12 : hour;
      return `${displayHour}:${minutes} ${ampm}`;
    }

    function fetchAvailableSlots(selectedDate) {
      console.log('🔍 Fetching slots for date:', selectedDate);
      
      timeDropdown.innerHTML = '<option value="">Loading slots...</option>';
      timeDropdown.disabled = true;

      const url = "<?= base_url('appointment/getAvailableSlots') ?>/" + selectedDate;
      console.log('📡 Full URL:', url);

      fetch(url)
        .then(response => response.json())
        .then(data => {
          console.log('📊 Slots data:', data);

          timeDropdown.innerHTML = '';
          timeDropdown.disabled = false;

          if (!data.available || data.available.length === 0) {
            timeDropdown.innerHTML = '<option value="" disabled>No available slots</option>';
            showAlert(data.message || 'No time slots available for this date.', 'warning');
            return;
          }

          const defaultOption = document.createElement('option');
          defaultOption.value = '';
          defaultOption.textContent = 'Select a time slot';
          timeDropdown.appendChild(defaultOption);

          data.available.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot;
            option.textContent = formatTime(slot);
            timeDropdown.appendChild(option);
          });

          showAlert(`${data.available.length} time slots loaded successfully!`, 'success');
        })
        .catch(err => {
          console.error('❌ Error fetching slots:', err);
          timeDropdown.innerHTML = '<option value="" disabled>Error loading slots</option>';
          timeDropdown.disabled = false;
          showAlert('Failed to fetch available slots.', 'danger');
        });
    }

    function updateCharacterCount() {
      const currentLength = purposeField.value.length;
      const maxLength = 500;
      const remaining = maxLength - currentLength;

      charCounter.textContent = `${currentLength}/${maxLength} characters`;
      
      if (remaining < 50) {
        charCounter.className = 'char-counter danger';
      } else if (remaining < 100) {
        charCounter.className = 'char-counter warning';
      } else if (currentLength > 0) {
        charCounter.className = 'char-counter success';
      } else {
        charCounter.className = 'char-counter muted';
      }
    }

    function validateForm() {
      let isValid = true;
      const requiredFields = ['fullname', 'email', 'contactnumber', 'date', 'time', 'purpose'];

      requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
          field.classList.add('is-invalid');
          isValid = false;
        } else {
          field.classList.remove('is-invalid');
          field.classList.add('is-valid');
        }
      });

      const emailField = document.getElementById('email');
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (emailField.value && !emailRegex.test(emailField.value)) {
        emailField.classList.add('is-invalid');
        showAlert('Please enter a valid email address.', 'danger');
        isValid = false;
      }

      const purposeValue = purposeField.value.trim();
      if (purposeValue.length < 10) {
        purposeField.classList.add('is-invalid');
        showAlert('Purpose must be at least 10 characters long.', 'danger');
        isValid = false;
      }

      if (!timeDropdown.value) {
        timeDropdown.classList.add('is-invalid');
        showAlert('Please select an available time slot.', 'danger');
        isValid = false;
      }

      return isValid;
    }

// Update progress bar
function updateProgress() {
  const fields = ['fullname', 'email', 'date', 'time', 'purpose'];
  let completed = 0;

  fields.forEach(fieldName => {
    const field = document.getElementById(fieldName);
    if (field && field.value.trim()) {
      completed++;
    }
  });

  const progress = (completed / fields.length) * 100;
  progressBar.style.width = progress + '%';
}

// Show alert messages
function showAlert(message, type) {
  const alertContainer = document.getElementById('alertContainer');
  const alertClass = type === 'danger' ? 'alert-danger' : 'alert-success';
  const icon = type === 'danger' ? 'bx-error' : 'bx-check-circle';

  alertContainer.innerHTML = `
    <div class="alert ${alertClass} fade-in">
      <i class='bx ${icon}'></i>
      ${message}
    </div>
  `;

  // Auto hide after 5 seconds
  setTimeout(() => {
    const alert = alertContainer.querySelector('.alert');
    if (alert) {
      alert.style.opacity = '0';
      setTimeout(() => {
        if (alert.parentNode) {
          alert.remove();
        }
      }, 300);
    }
  }, 5000);

  // Scroll to alert
  alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Event Listeners
dateInput.addEventListener('change', function() {
  console.log('Date changed to:', this.value);
  
  if (this.value && validateDate(this.value)) {
    fetchAvailableSlots(this.value);
    timeDropdown.classList.remove('is-invalid');
  } else {
    timeDropdown.innerHTML = '<option value="">Select a valid date first</option>';
    timeDropdown.disabled = false;
  }
  updateProgress();
});

timeDropdown.addEventListener('change', function() {
  this.classList.remove('is-invalid');
  if (this.value) {
    this.classList.add('is-valid');
  }
  updateProgress();
});

purposeField.addEventListener('input', function() {
  updateCharacterCount();
  this.classList.remove('is-invalid');
  if (this.value.length >= 10) {
    this.classList.add('is-valid');
  }
  updateProgress();
});

// Real-time validation for other fields
['fullname', 'email'].forEach(fieldName => {
  const field = document.getElementById(fieldName);
  field.addEventListener('input', function() {
    this.classList.remove('is-invalid');
    if (this.value.trim()) {
      if (fieldName === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailRegex.test(this.value)) {
          this.classList.add('is-valid');
        }
      } else {
        this.classList.add('is-valid');
      }
    }
    updateProgress();
  });
});

// Form submission
form.addEventListener('submit', function(e) {
  if (!validateForm()) {
    e.preventDefault();
    return false;
  }

  // Show loading state
  submitBtn.disabled = true;
  submitBtn.classList.add('btn-loading');
  submitBtn.innerHTML = '<div class="loading-spinner"></div> Setting Appointment...';
  
  // Let the form submit normally
  // The loading state will be reset when the page reloads
});

// Sidebar toggle functionality
menuToggle.addEventListener('click', function() {
  if (isMobile()) {
    sidebar.classList.toggle('mobile-open');
    mobileOverlay.style.display = sidebar.classList.contains('mobile-open') ? 'block' : 'none';
  } else {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
  }
});

// Close mobile sidebar
window.closeMobileSidebar = function() {
  sidebar.classList.remove('mobile-open');
  mobileOverlay.style.display = 'none';
};

// Handle window resize
window.addEventListener('resize', function() {
  if (!isMobile() && sidebar.classList.contains('mobile-open')) {
    sidebar.classList.remove('mobile-open');
    mobileOverlay.style.display = 'none';
  }
});

// Debug function - you can remove this later
function testSlotFetch() {
  const testDate = '2024-12-20'; // Use a future date
  console.log('Testing slot fetch for:', testDate);
  fetchAvailableSlots(testDate);
}
document.addEventListener('DOMContentLoaded', () => {
  fetchAvailableDates();
});


  </script>

</body>
</html>