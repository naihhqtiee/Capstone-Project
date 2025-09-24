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
$routes->get('login', 'AuthController::index');
$routes->post('login', 'AuthController::login'); // form action="/login"
$routes->post('auth/login', 'AuthController::login'); // alternative form action="/auth/login"
$routes->get('logout', 'AuthController::logout');

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
$routes->get('user/dashboard', 'User::userdashboard', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'user/filing-complaint', 'User::filing_complaint');
$routes->get('user/appointment', 'User::appointment');
$routes->get('user/view-complaint', 'User::viewComplaint');
$routes->get('user/view-appointments', 'User::viewAppointments');
$routes->get('user/userdashboard', 'User::userdashboard');
$routes->post('user/register-event', 'User::registerEvent'); // ✅ Your register event route
$routes->post('user/changePassword', 'User::changePassword');
$routes->post('user/saveIdentified', 'User::saveIdentified');
$routes->get('complaint/edit/(:num)', 'ComplaintController::edit/$1');
$routes->post('complaint/update/(:num)', 'ComplaintController::update/$1');
$routes->post('user/saveIdentified', 'User::saveIdentified');

// Appointment
$routes->post('appointment/set', 'AppointmentController::set');

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
    $routes->get('notifications', 'AdminController::fetchNotifications');
    $routes->get('complaints/view/(:num)', 'AdminController::viewComplaint/$1');
    $routes->match(['get','post'], 'complaints/edit/(:num)', 'AdminController::editComplaint/$1');
    $routes->get('complaints/view/(:num)', 'AdminController::viewComplaint/$1');
    $routes->get('complaints/delete/(:num)', 'AdminController::deleteComplaint/$1');

    $routes->get('events', 'AdminController::events');
    $routes->get('events/view/(:num)', 'EventsController::view/$1');
    $routes->get('events/edit/(:num)', 'EventsController::edit/$1');
    $routes->post('events/update', 'EventsController::update');  // ✅ FIXED
    $routes->delete('events/delete/(:num)', 'EventsController::delete/$1');
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
$routes->post('appointment/set', 'AppointmentController::set');

// ==================== 
// Staff Dashboard & Status Update (Outside Group)
// ==================== 
$routes->get('staff/dashboard', 'StaffController::dashboard');
$routes->post('appointments/update-status/(:num)', 'StaffController::updateStatus/$1');
$routes->get('appointment/slots/(:any)', 'AppointmentController::getAvailableSlots/$1');
// Add this to your Routes.php file
$routes->get('appointment/getAvailableSlots/(:segment)', 'AppointmentController::getAvailableSlots/$1');
$routes->post('appointments/delete/(:num)', 'AppointmentController::delete/$1');

// ==================== 
// Debug
// ==================== 
$routes->get('debug/upload', 'EventsController::checkUpload');

