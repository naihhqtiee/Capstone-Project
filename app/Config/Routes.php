<?php 

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==================== 
// Home & Public Pages
// ==================== 
$routes->get('/', 'Pages::Home');
$routes->get('about', 'Pages::about');
$routes->get('file-complaint', 'ComplaintController::complaintChoice');

// ==================== 
// Authentication Routes
// ==================== 
// ==================== 
// Authentication Routes
// ==================== 
$routes->get('login', 'AuthController::index');
$routes->post('login', 'AuthController::login'); // form action="/login"
$routes->post('auth/login', 'AuthController::login'); // alternative form action="/auth/login"
$routes->get('logout', 'AuthController::logout');

// 🔹 Google OAuth
$routes->get('google/login', 'GoogleAuth::login');
$routes->get('google-callback', 'GoogleAuth::callback');

$routes->get('forgot-password', 'AuthController::forgotPassword');
$routes->post('forgot-password/send', 'AuthController::sendResetLink');
$routes->get('reset-password/(:segment)', 'AuthController::resetPasswordForm/$1');
$routes->post('reset-password', 'AuthController::resetPassword');



// ==================== 
// Registration Routes
// ==================== 
$routes->get('register', 'Pages::register');
$routes->get('complaint/identified', 'Pages::register');
$routes->post('register/save', 'Pages::saveRegistration');

// ==================== 
// Complaint Filing
// ==================== 
$routes->get('complaint', 'ComplaintController::complaintChoice');
$routes->get('complaint/anonymous', 'ComplaintController::anonymousForm');
$routes->post('complaint/save_anonymous', 'ComplaintController::saveAnonymous');
$routes->get('complaint/identified', 'ComplaintController::identifiedForm', ['filter' => 'auth']);
$routes->post('complaint/saveIdentified', 'ComplaintController::saveIdentified');
$routes->get('complaint/delete/(:num)', 'ComplaintController::delete/$1');
$routes->post('complaint/delete/(:num)', 'ComplaintController::delete/$1');
$routes->get('complaint/view/(:num)', 'ComplaintController::view/$1');
$routes->post('complaint/update', 'ComplaintController::update');
$routes->post('complaint/update-status/(:num)', 'ComplaintController::updateStatus/$1');




// Staff action for identified
$routes->post('staff/store-identified', 'StaffController::storeIdentified');

// ==================== 
// User Routes
// ==================== 
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'User::userdashboard');
    $routes->match(['get', 'post'], 'filing-complaint', 'User::filing_complaint');
    $routes->get('appointment', 'User::appointment');
    $routes->get('view-complaint', 'User::viewComplaint');
    $routes->get('view-appointments', 'User::viewAppointments');
    $routes->get('userdashboard', 'User::userdashboard');
    $routes->post('saveIdentified', 'User::saveIdentified');
    $routes->post('register-event', 'User::registerEvent'); 
    $routes->post('changePassword', 'User::changePassword');
    $routes->get('notifications/fetch', 'User::fetchNotifications');
$routes->post('notifications/read/(:num)', 'User::markNotificationAsRead/$1');

});

// Complaint routes
$routes->group('complaint', ['filter' => 'auth'], function($routes) {
    $routes->get('edit/(:num)', 'ComplaintController::edit/$1');
    $routes->post('update/(:num)', 'ComplaintController::update/$1');
});

// Appointment routes
$routes->group('appointment', ['filter' => 'auth'], function($routes) {
    $routes->get('view/(:num)', 'User::viewAppointment/$1');
    $routes->get('reschedule/(:num)', 'User::rescheduleAppointment/$1');
    $routes->get('cancel/(:num)', 'User::cancelAppointment/$1');
    $routes->get('download/(:num)', 'User::downloadAppointment/$1');
    $routes->post('set', 'AppointmentController::set');
});

// ==================== 
// Admin Panel Routes (Admin Only)
// ==================== 
$routes->group('admin', ['filter' => 'adminauth'], function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('users', 'AdminController::users');
    $routes->get('users/create', 'AdminController::createUser');
    $routes->post('users/store', 'AdminController::storeUser');
    $routes->get('users/delete/(:num)', 'AdminController::deleteUser/$1');
    $routes->post('users/add', 'AdminController::addUser');

});

// Redirect /admin to /admin/dashboard
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('users', 'AdminController::users');
    $routes->get('new-users', 'AdminController::newUsers');
    $routes->get('complaints', 'AdminController::complaints');
    $routes->get('events', 'AdminController::events'); 
    $routes->get('students', 'AdminController::students'); 
    $routes->get('chre_staff', 'AdminController::chreStaff'); 
// Routes
; 
$routes->post('chre_staff/add', 'AdminController::addChreStaff');
$routes->post('chre_staff/add', 'AdminController::addChreStaff');

$routes->get('chre_staff/edit/(:num)', 'AdminController::editChreStaff/$1');
$routes->get('chre_staff/delete/(:num)', 'AdminController::deleteChreStaff/$1');
$routes->get('chre_staff/view/(:num)', 'AdminController::viewChreStaff/$1');

  $routes->get('notifications', 'AdminController::fetchNotifications');

    $routes->get('students/view/(:num)', 'AdminController::viewStudent/$1');
    $routes->get('students/edit/(:num)', 'AdminController::editStudent/$1');
    $routes->post('students/delete/(:num)', 'AdminController::deleteStudent/$1');

    // Complaints
     $routes->get('complaints/view/(:num)', 'ComplaintController::view/$1'); 
$routes->post('complaints/delete/(:num)', 'AdminController::deleteComplaint/$1');
    $routes->match(['get','post'], 'complaints/edit/(:num)', 'AdminController::editComplaint/$1');
  // handle form submit


    // Events

});

// ==================== 
// Events Routes (Shared by Admin & Staff)
// ==================== 
$routes->group('events', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'EventsController::index');          // List events
    $routes->post('store', 'EventsController::store');     // Create event
    $routes->get('view/(:num)', 'EventsController::view/$1');   // View JSON
    $routes->get('edit/(:num)', 'EventsController::edit/$1');   // Edit form
    $routes->post('update', 'EventsController::update');   // Update event
    $routes->delete('delete/(:num)', 'EventsController::delete/$1'); // Delete event

});



// ==================== 
// Staff Routes (Staff Only)
// ==================== 
$routes->group('staff', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'StaffController::dashboard');
    $routes->get('anonymous', 'StaffController::anonymous');
    $routes->get('identified', 'ComplaintController::identifiedForm');
    $routes->get('complaints', 'StaffController::complaints');
    $routes->post('upload-nda', 'StaffController::uploadNda');
    $routes->get('nda', 'StaffController::ndaManagement');
$routes->get('view-nda', 'StaffController::viewNda');
$routes->get('download-nda', 'StaffController::downloadNda');
$routes->post('delete-nda/(:num)', 'StaffController::deleteNda/$1');
$routes->get('view-nda/(:num)', 'StaffController::viewNda/$1');
$routes->get('download-nda/(:num)', 'StaffController::downloadNda/$1');
$routes->post('add_note/(:num)', 'StaffController::addNote/$1');
$routes->get('notifications/fetch', 'StaffController::fetchNotifications');

$routes->post('complaint/(:num)/save_note', 'StaffController::save_note/$1');
$routes->get('events/registrants/(:num)', 'StaffController::eventRegistrants/$1');





    // OPCR Checklist
    $routes->get('opcr-checklist', 'OpcrChecklistController::index');
    $routes->post('opcr-checklist/import', 'OpcrChecklistController::import');
    $routes->get('opcr-checklist/export', 'OpcrChecklistController::export');

    // View uploaded file (show contents as table)
    $routes->get('opcr-checklist/view/(:any)', 'OpcrChecklistController::view/$1');
    $routes->get('opcr-checklist/embed/(:any)', 'OpcrChecklistController::embed/$1');

    // Download uploaded file
    $routes->get('opcr-checklist/download/(:any)', 'OpcrChecklistController::download/$1', ['as' => 'download_opcr']);

    // Delete uploaded file
    $routes->get('opcr-checklist/delete/(:any)', 'OpcrChecklistController::delete/$1');

    $routes->get('students', 'StaffController::students');
    $routes->get('notifications', 'StaffController::getNotifications');
    $routes->post('events/delete/(:num)', 'StaffController::deleteEvent/$1');
    $routes->post('events/update/(:num)', 'StaffController::updateEvent/$1');
    $routes->get('events/registrants/(:num)', 'StaffController::eventRegistrants/$1');
    $routes->match(['get', 'post'], 'all_complaints', 'StaffController::allComplaints');

    // Events
    $routes->get('events', 'EventsController::index', ['filter' => 'auth']);
    $routes->post('events/store', 'EventsController::store', ['filter' => 'auth']);
    $routes->get('staff/events', 'EventsController::index', ['filter' => 'auth']);

    // Appointments
    $routes->get('appointments', 'StaffController::appointments');
    $routes->post('appointments/update-status/(:num)', 'AppointmentController::updateStatus/$1');
    
    // Other actions
    $routes->post('appointment/delete/(:num)', 'AppointmentController::delete/$1');
    $routes->post('complaint/saveIdentified', 'ComplaintController::saveIdentified');
    
});

// ==================== 
// Email Verification
// ==================== 
$routes->get('verify/(:segment)', 'Pages::verify/$1');

// ==================== 
// Student Profile
// ==================== 
$routes->post('student/saveProfile', 'StudentController::saveProfile');
$routes->post('student/updateProfile', 'StudentController::updateProfile');
$routes->get('student/completeProfile', 'StudentController::completeProfile');

// ==================== 
// Appointment Actions
// ==================== 
// ====================
// Appointment Routes
// ====================
$routes->post('appointments/set', 'AppointmentController::set');
$routes->get('appointments/getAvailableSlots/(:any)', 'AppointmentController::getAvailableSlots/$1');
$routes->get('appointments/getAvailableDates', 'AppointmentController::getAvailableDates');
$routes->post('appointments/delete/(:num)', 'AppointmentController::delete/$1');

// ====================
// Staff Dashboard & Status Update
// ====================
$routes->get('staff/dashboard', 'StaffController::dashboard');
$routes->post('appointments/update-status/(:num)', 'StaffController::updateStatus/$1');



// ==================== 
// Appointment Routes (Availability & Booking)
// ==================== 
// Protected routes for staff/appointments
$routes->group('appointment', ['filter' => 'auth'], function($routes) {
    // Booking routes
    $routes->post('set', 'AppointmentController::set'); 
    $routes->get('slots/(:segment)', 'AppointmentController::getAvailableSlots/$1');
    $routes->post('delete/(:num)', 'AppointmentController::delete/$1');

    // Staff: manage availability
    $routes->post('add-availability', 'AppointmentController::addAvailability');
    $routes->post('remove-availability', 'AppointmentController::removeAvailability');
    $routes->get('get-available-dates', 'AppointmentController::getAvailableDates');
});

// Public/User routes (not restricted by auth)
$routes->get('appointment/getAvailableDates', 'User::getAvailableDates');
$routes->get('appointment/getAvailableSlots/(:segment)', 'User::getAvailableSlots/$1');



// ==================== 
// Debug
// ==================== 
$routes->get('debug/upload', 'EventsController::checkUpload');

