<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\AccountModel;
use App\Models\ComplaintModel;
use App\Models\ChreStaffModel;
use App\Models\EventModel;

class AdminController extends BaseController
{
    public function index()
    {
        return redirect()->to('/admin/dashboard');
    }

    public function dashboard()
    {
        $db = \Config\Database::connect();

        $complaintModel = new ComplaintModel();
        $studentModel   = new StudentModel();
        $chreStaffModel = new ChreStaffModel();
        $accountModel   = new AccountModel();
        $eventModel     = new EventModel();

        // ✅ User counts
        $totalUsers = $accountModel->countAllResults();
        $verifiedUsers = $accountModel->where('status', 'verified')->countAllResults();

        // ✅ Complaint counts
        $total    = $complaintModel->countAllResults();
        $complaintModel->resetQuery();
        $pending  = $complaintModel->where('status', 'pending')->countAllResults();
        $complaintModel->resetQuery();
        $ongoing  = $complaintModel->where('status', 'in_progress')->countAllResults();
        $complaintModel->resetQuery();
        $resolved = $complaintModel->where('status', 'resolved')->countAllResults();

        // ✅ Events
        $today = date('Y-m-d');
        $upcomingEvents = $eventModel->where('DATE(start_date) >=', $today)->countAllResults();

        return view('admin/dashboard', [
            'totalUsers'      => $totalUsers,
            'verifiedUsers'   => $verifiedUsers,
            'total'           => $total,
            'pending'         => $pending,
            'ongoing'         => $ongoing,
            'resolved'        => $resolved,
            'upcomingEvents'  => $upcomingEvents,
            'totalStudents'   => $studentModel->countAllResults(),
            'totalChreStaff'  => $chreStaffModel->countAllResults(),
            'totalEvents'     => $eventModel->countAllResults()
        ]);
    }

   public function users()
{
    $db = \Config\Database::connect();
    $accountModel = new AccountModel();
    $complaintModel = new \App\Models\ComplaintModel();
    $studentModel   = new \App\Models\StudentModel();
    $chreStaffModel = new \App\Models\ChreStaffModel();
    $eventModel     = new \App\Models\EventModel();

    // ✅ Get all users with student contact number (JOIN)
    $users = $db->table('accounts')
        ->select('accounts.*, students.contact_number')
        ->join('students', 'students.account_id = accounts.id', 'left')
        ->orderBy('accounts.created_at', 'DESC')
        ->get()
        ->getResultArray();

    // ✅ Complaint total (to fix undefined $total error)
    $totalComplaints = $complaintModel->countAllResults();

    // ✅ Get counts by status
    $verifiedUsers = $accountModel->where('status', 'verified')->findAll();
    $accountModel->resetQuery();
    $pendingUsers = $accountModel->where('status', 'pending')->findAll();
    $accountModel->resetQuery();
    $adminUsers   = $accountModel->where('role', 'admin')->findAll();

    return view('admin/users', [
        'users'         => $users,
        'totalUsers'    => count($users),
        'verifiedUsers' => $verifiedUsers,
        'verifiedCount' => count($verifiedUsers),
        'pendingUsers'  => $pendingUsers,
        'pendingCount'  => count($pendingUsers),
        'adminCount'    => count($adminUsers),
        'total'         => $totalComplaints, // ✅ now available in view
        'totalStudents' => $studentModel->countAllResults(),
        'totalChreStaff'=> $chreStaffModel->countAllResults(),
        'totalEvents'   => $eventModel->countAllResults()
    ]);
}

    public function newUsers()
    {
        $model = new StudentModel();
        $verifiedUsers = $model->findAll();

        return view('admin/new_user', [
            'verifiedUsers' => $verifiedUsers,
            'verifiedCount' => count($verifiedUsers)
        ]);
    }

    public function addUser()
    {
        $accountModel = new AccountModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'full_name' => 'required|min_length[3]',
            'email'     => 'required|valid_email|is_unique[accounts.email]',
            'password'  => 'required|min_length[6]',
            'role'      => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('error', implode('<br>', $validation->getErrors()));
        }

        $accountModel->save([
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $this->request->getPost('role'),
            'status'    => 'pending',
        ]);

        return redirect()->to(base_url('admin/users'))->with('success', 'User added successfully!');
    }

    public function deleteUser($id)
    {
        $model = new AccountModel();
        $model->delete($id);
        return redirect()->to('/admin/users')->with('success', 'User deleted successfully!');
    }

    public function complaints()
    {
        $model = new ComplaintModel();
        $studentModel   = new StudentModel();
        $chreStaffModel = new ChreStaffModel();
        $accountModel   = new AccountModel();
        $eventModel     = new EventModel();

        $complaints = $model->select('
                complaints.id,
                complaints.complaint_category,
                complaints.complaint_type,
                complaints.location,
                complaints.date,
                complaints.status,
                complaints.created_at,
                accounts.full_name,
                accounts.email,
                students.contact_number
            ')
            ->join('accounts', 'accounts.id = complaints.user_id', 'left')
            ->join('students', 'students.account_id = accounts.id', 'left')
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll();

        $totalCount  = $model->countAllResults(false);
        $pendingCount = $model->where('status', 'pending')->countAllResults();
        $model->resetQuery();
        $onGoingCount = $model->where('status', 'in_progress')->countAllResults();
        $model->resetQuery();
        $resolvedCount = $model->where('status', 'resolved')->countAllResults();
        $model->resetQuery();
        $closedCount = $model->where('status', 'closed')->countAllResults();

        return view('admin/complaints', [
            'complaints'     => $complaints,
            'total'          => $totalCount,
            'pending'        => $pendingCount,
            'ongoing'        => $onGoingCount,
            'resolved'       => $resolvedCount,
            'closed'         => $closedCount,
            'totalStudents'  => $studentModel->countAllResults(),
            'totalChreStaff' => $chreStaffModel->countAllResults(),
            'totalEvents'    => $eventModel->countAllResults(),
            'totalUsers'     => $accountModel->countAllResults() // ✅ FIXED
        ]);
    }
    public function viewComplaint($id)
{
    $complaintModel = new \App\Models\ComplaintModel();
    $complaint = $complaintModel
        ->select('complaints.*, accounts.full_name, accounts.email, students.contact_number')
        ->join('accounts', 'accounts.id = complaints.user_id', 'left')
        ->join('students', 'students.account_id = accounts.id', 'left')
        ->where('complaints.id', $id)
        ->first();

    if (!$complaint) {
        return redirect()->back()->with('error', 'Complaint not found.');
    }

    return view('admin/complaint_view', ['complaint' => $complaint]);
}

public function editComplaint($id)
{
    $complaintModel = new \App\Models\ComplaintModel();
    $complaint = $complaintModel->find($id);

    if (!$complaint) {
        return redirect()->back()->with('error', 'Complaint not found.');
    }

    // ✅ If form is submitted
    if ($this->request->getMethod() === 'post') {
        $complaintModel->update($id, [
            'complaint_category' => $this->request->getPost('complaint_category'),
            'complaint_type'     => $this->request->getPost('complaint_type'),
            'status'             => $this->request->getPost('status'),
        ]);

        return redirect()->to(base_url('admin/complaints'))->with('success', 'Complaint updated successfully.');
    }

    return view('admin/complaint_edit', ['complaint' => $complaint]);
}

public function deleteComplaint($id)
{
    $complaintModel = new \App\Models\ComplaintModel();
    if ($complaintModel->find($id)) {
        $complaintModel->delete($id);
        return redirect()->to(base_url('admin/complaints'))->with('success', 'Complaint deleted successfully.');
    }

    return redirect()->to(base_url('admin/complaints'))->with('error', 'Complaint not found.');
}


public function events()
{
    $complaintModel        = new \App\Models\ComplaintModel();
    $eventModel            = new \App\Models\EventModel();
    $studentModel          = new \App\Models\StudentModel();
    $chreStaffModel        = new \App\Models\ChreStaffModel();
    $accountModel          = new \App\Models\AccountModel();
    $eventRegistrationModel = new \App\Models\EventRegistrationModel(); // ✅ NEW

    $today = date('Y-m-d');

    // Get events (only active)
    $events = $eventModel
        ->where('status', 'active')
        ->orderBy('start_date', 'ASC')
        ->findAll();

    // Calculate upcoming, ongoing, and completed events
    $upcomingEvents  = $eventModel
        ->where('start_date >', $today)
        ->where('status', 'active')
        ->findAll();

    $ongoingEvents = $eventModel
        ->where('start_date <=', $today)
        ->where('end_date >=', $today)
        ->where('status', 'active')
        ->findAll();

    $completedEvents = $eventModel
        ->where('end_date <', $today)
        ->where('status', 'active')
        ->findAll();

    // ✅ Real-time total events
    $totalEvents = count($events);

    // ✅ Total attendees from event_registrations table
    $totalAttendees = $eventRegistrationModel->countAllResults();

    return view('admin/events', [
        'events'          => $events,
        'upcomingEvents'  => $upcomingEvents,
        'ongoingEvents'   => $ongoingEvents,
        'completedEvents' => $completedEvents,
        'total'           => $complaintModel->countAllResults(),
        'totalStudents'   => $studentModel->countAllResults(),
        'totalChreStaff'  => $chreStaffModel->countAllResults(),
        'totalEvents'     => $totalEvents,
        'totalAttendees'  => $totalAttendees, // ✅ PASS TO VIEW
        'totalUsers'      => $accountModel->countAllResults()
    ]);
}


public function students()
{
    $complaintModel = new \App\Models\ComplaintModel();
    $studentModel   = new \App\Models\StudentModel();
    $chreStaffModel = new \App\Models\ChreStaffModel();
    $eventModel     = new \App\Models\EventModel();
    $accountModel   = new \App\Models\AccountModel(); // ✅ ADDED

    $students = $studentModel->findAll();

    return view('admin/students', [
        'students'       => $students,
        'total'          => $complaintModel->countAllResults(),
        'totalStudents'  => count($students),
        'totalChreStaff' => $chreStaffModel->countAllResults(),
        'totalEvents'    => $eventModel->countAllResults(),
        'totalUsers'     => $accountModel->countAllResults() // ✅ FIXED
    ]);
}
public function viewStudent($id)
{
    $studentModel = new \App\Models\StudentModel();
    $student = $studentModel->find($id);

    if (!$student) {
        return redirect()->to('admin/students')->with('error', 'Student not found.');
    }

    return view('admin/students/view', ['student' => $student]);
}

public function editStudent($id)
{
    $studentModel = new \App\Models\StudentModel();
    $student = $studentModel->find($id);

    if (!$student) {
        return redirect()->to('admin/students')->with('error', 'Student not found.');
    }

    return view('admin/students/edit', ['student' => $student]);
}

public function deleteStudent($id)
{
    $studentModel = new \App\Models\StudentModel();

    if ($studentModel->find($id)) {
        $studentModel->delete($id);
        return redirect()->to('admin/students')->with('success', 'Student deleted successfully.');
    }

    return redirect()->to('admin/students')->with('error', 'Student not found.');
}


public function chreStaff()
{
    $complaintModel = new \App\Models\ComplaintModel();
    $chreStaffModel = new \App\Models\ChreStaffModel();
    $studentModel   = new \App\Models\StudentModel();
    $eventModel     = new \App\Models\EventModel();
    $accountModel   = new \App\Models\AccountModel(); // ✅ ADDED

    $staff = $chreStaffModel->findAll();

    return view('admin/chre_staff', [
        'staff'         => $staff,
        'total'         => $complaintModel->countAllResults(),
        'totalStudents' => $studentModel->countAllResults(),
        'totalChreStaff'=> $chreStaffModel->countAllResults(),
        'totalEvents'   => $eventModel->countAllResults(),
        'totalUsers'    => $accountModel->countAllResults() // ✅ FIXED
    ]);
}
public function addChreStaff()
{
    $chreStaffModel = new \App\Models\ChreStaffModel();

    if ($this->request->getMethod() === 'post') {
        $data = [
            'first_name'     => $this->request->getPost('first_name'),
            'last_name'      => $this->request->getPost('last_name'),
            'email'          => $this->request->getPost('email'),
            'contact_number' => $this->request->getPost('contact_number'),
            'department'     => $this->request->getPost('department'),
            'position'       => $this->request->getPost('position'),
            'status'         => $this->request->getPost('status'),
        ];

        if (!$chreStaffModel->insert($data)) {
            // Show DB errors if insert fails
            return redirect()->back()->with('error', implode(', ', $chreStaffModel->errors()));
        }

        return redirect()->to(base_url('admin/chre_staff'))->with('success', 'Staff member added successfully.');
    }

    return redirect()->to(base_url('admin/chre_staff'));
}


public function editChreStaff($id) {
     $chreStaffModel = new \App\Models\ChreStaffModel(); 
     $staff = $chreStaffModel->find($id);
      if (!$staff) {
         return redirect()->to('admin/chre-staff')->with('error', 'Staff not found.'); 
        } 
        if ($this->request->getMethod() === 'post') { 
            $chreStaffModel->update($id, [ 
                'first_name' => $this->request->getPost('first_name'), 
                'last_name' => $this->request->getPost('last_name'), 
                'email' => $this->request->getPost('email'), 
                'contact_number' => $this->request->getPost('contact_number'), 
                'department' => $this->request->getPost('department'), 
                'position' => $this->request->getPost('position'), 
                'status' => $this->request->getPost('status'), ]); 
                return redirect()->to('admin/chre-staff')->with('success', 'Staff member updated successfully.'); 
            } 
            return view('admin/chre_staff/edit', ['staff' => $staff]); 
        } 
        public function deleteChreStaff($id) { 
            $chreStaffModel = new \App\Models\ChreStaffModel(); 
            if 
            ($chreStaffModel->find($id)) { 
                $chreStaffModel->delete($id);
                return redirect()->to('admin/chre-staff')->with('success', 'Staff member deleted successfully.'); 
            } 
            return redirect()->to('admin/chre-staff')->with('error', 'Staff not found.'); 
        }

        
public function fetchNotifications()
{
    $db = \Config\Database::connect();

    // ✅ Explicit select so we don’t get missing fields
    $newUsers = $db->table('accounts')
        ->select('id, full_name, role, created_at')
        ->where('status', 'pending')
        ->orderBy('created_at', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    $newComplaints = $db->table('complaints')
        ->select('id, complaint_category, description, created_at')
        ->orderBy('created_at', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    $notifications = [];

    // ✅ Users
    foreach ($newUsers as $user) {
        $notifications[] = [
            'type' => 'user',
            'title' => 'New user registered',
            'description' => ($user['full_name'] ?? 'Unknown') 
                . ' joined as ' . ($user['role'] ?? 'N/A'),
            'time' => $this->timeAgo($user['created_at'] ?? date('Y-m-d H:i:s'))
        ];
    }

    // ✅ Complaints
    foreach ($newComplaints as $complaint) {
        $notifications[] = [
            'type' => 'complaint',
            'title' => 'New complaint submitted',
            'description' => ($complaint['complaint_category'] ?? 'General') 
                . ' - ' . ($complaint['description'] ?? ''),
            'time' => $this->timeAgo($complaint['created_at'] ?? date('Y-m-d H:i:s'))
        ];
    }

    return $this->response->setJSON($notifications);
}

private function timeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return $diff . " sec ago";
    if ($diff < 3600) return floor($diff / 60) . " min ago";
    if ($diff < 86400) return floor($diff / 3600) . " hour ago";
    return floor($diff / 86400) . " day ago";
}

}
