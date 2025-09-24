<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\AccountModel;
use App\Models\ComplaintModel;
use App\Models\EventRegistrationModel;
use App\Models\StudentModel;
use App\Models\NdaModel;

class User extends BaseController
{
public function userdashboard()
{
    $eventModel        = new EventModel();
    $appointmentModel  = new \App\Models\AppointmentModel();
    $complaintModel    = new ComplaintModel();
    $eventRegModel     = new EventRegistrationModel();

    $accountId = session()->get('account_id');   // session id from login
    $email     = session()->get('email');
    $current_user_role = session()->get('role') ?? 'guest'; // ✅ fallback to 'guest'

    // Fetch counts
    $data['activeEvents']     = $eventModel->where('status', 'active')->countAllResults();
    $data['myAppointments']   = $appointmentModel->where('email', $email)->countAllResults();
    $data['myComplaints']     = $complaintModel->where('user_id', $accountId)->countAllResults();
    $data['registeredEvents'] = $eventRegModel->where('user_id', $accountId)->countAllResults();

    // Fetch events (still sorted by start date)
    $data['events'] = $eventModel->orderBy('start_date', 'DESC')->findAll();

    // ✅ Pass the current user role to the view so it can filter audience
    $data['current_user_role'] = $current_user_role;

    return view('user/userdashboard', $data);
}
public function saveIdentified()
{
    $complaintModel = new ComplaintModel();
    
    // Check if filing with identity or anonymously
    $fileWithIdentity = $this->request->getPost('file_with_identity');
    
    $uploadedFileNames = $this->handleFileUploads();

    $resolutions = $this->request->getPost('resolution');
    $resolutionString = is_array($resolutions) ? implode(',', $resolutions) : $resolutions;

    $data = [
        'date'              => $this->request->getPost('date'),
        'time'              => $this->request->getPost('time'),
        'location'          => $this->request->getPost('location'),
        'complaint_type'    => $this->request->getPost('complaint_type'),
        'complaint_category'=> $this->request->getPost('complaint_category'),
        'description'       => $this->request->getPost('description'),
        'impact'            => $this->request->getPost('impact'),
        'files'             => !empty($uploadedFileNames) ? json_encode($uploadedFileNames) : null,
        'resolution'        => $resolutionString,
        'resolution_other'  => $this->request->getPost('other_resolution'),
        'status'            => 'pending',
        'created_at'        => date('Y-m-d H:i:s'),
        'updated_at'        => date('Y-m-d H:i:s')
    ];

    // If filing with identity, include user information
    if ($fileWithIdentity) {
        $data['user_id'] = session('account_id');
        $data['is_anonymous'] = 0;
    } else {
        // Filing anonymously - don't include user_id
        $data['user_id'] = null;
        $data['is_anonymous'] = 1;
    }

    if ($complaintModel->insert($data)) {
        return redirect()->to('/user/view-complaint')
            ->with('success', 'Complaint filed successfully.');
    }

    return redirect()->back()
        ->withInput()
        ->with('error', 'Failed to file complaint. Please check your input.');
}

    private function handleFileUploads(): array
    {
        $uploadedFiles = [];
        $files = $this->request->getFiles();

        if (isset($files['supporting_docs']) && is_array($files['supporting_docs'])) {
            foreach ($files['supporting_docs'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $uploadPath = WRITEPATH . 'uploads/complaints/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $newName = $file->getRandomName();

                    try {
                        $file->move($uploadPath, $newName);
                        $uploadedFiles[] = $newName;
                    } catch (\Exception $e) {
                        log_message('error', 'File upload failed: ' . $e->getMessage());
                    }
                }
            }
        }

        return $uploadedFiles;
    }

public function filing_complaint()
{
    log_message('info', 'filing_complaint method called with method: ' . $this->request->getMethod());

    $accountModel = new AccountModel();
    $studentModel = new StudentModel();
    $ndaModel     = new NdaModel();

    // Get logged-in account
    $account = $accountModel->find(session('id'));

    // Fetch student record linked to this account
    $student = $studentModel->where('account_id', session('id'))->first();

    // ✅ Fetch latest NDA uploaded by any staff account
    $ndaFile = $ndaModel->select('file_path')
                        ->join('accounts', 'accounts.id = nda_uploads.account_id')
                        ->where('accounts.role', 'staff')
                        ->orderBy('nda_uploads.id', 'DESC')
                        ->first();

    if ($this->request->getMethod() === 'GET') {
        return view('user/filing_complaint', [
            'account' => $account,
            'student' => $student,
            'nda'     => $ndaFile   // ✅ fixed: pass $ndaFile instead of $nda
        ]);
    }

    if ($this->request->getMethod() === 'POST') {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'date'              => 'required|valid_date',
            'location'          => 'required|min_length[5]',
            'description'       => 'required|min_length[50]',
            'complaint_type'    => 'required|in_list[academic,non-academic]',
            'complaint_category'=> 'required',
            'resolution'        => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $validation->getErrors());
        }

        $resolutions = $this->request->getPost('resolution');
        $resolutionString = is_array($resolutions) ? implode(',', $resolutions) : $resolutions;

        $data = [
            'user_id'           => session('account_id'),
            'contact_number'    => $this->request->getPost('contact_number') ?? ($student['contact_number'] ?? null),
            'nda_file'          => $ndaFile['file_path'] ?? null, // ✅ safely include NDA
            'date'              => $this->request->getPost('date'),
            'location'          => $this->request->getPost('location'),
            'complaint_type'    => $this->request->getPost('complaint_type'),
            'complaint_category'=> $this->request->getPost('complaint_category'),
            'description'       => $this->request->getPost('description'),
            'impact'            => $this->request->getPost('impact'),
            'resolution'        => $resolutionString,
            'resolution_other'  => $this->request->getPost('other_resolution'),
            'files'             => null,
            'is_anonymous'      => $this->request->getPost('is_anonymous') ? 1 : 0,
            'status'            => 'pending',
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s')
        ];

        // Handle user supporting documents upload
        $uploadedFiles = $this->handleFileUploads();
        if (!empty($uploadedFiles)) {
            $data['files'] = json_encode($uploadedFiles);
        }

        try {
            $complaintModel = new ComplaintModel();
            $result = $complaintModel->insert($data);

            if ($result) {
                return redirect()->to(base_url('user/filing-complaint'))
                    ->with('success', 'Complaint submitted successfully! Your complaint ID is: ' . $result);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Failed to submit complaint. Please try again.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Database error in filing_complaint: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Database error occurred. Please try again.');
        }
    }

    return redirect()->to(base_url('user/filing-complaint'));
}
public function viewNda($filename)
{
    $filePath = WRITEPATH . 'uploads/nda/' . $filename;

    if (!file_exists($filePath)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    return $this->response->download($filePath, null)->setFileName($filename);
}




    public function appointment()
    {
        return view('user/appointment');
    }

public function viewComplaint()
{
    $complaintModel = new \App\Models\ComplaintModel();

    $userId = session()->get('account_id');  // use consistent session key

    $data['complaints'] = $complaintModel
        ->where('user_id', $userId)
        ->orderBy('date', 'DESC')
        ->findAll();

    return view('user/view_complaint', $data);
}







    public function viewAppointments()
    {
        $appointmentModel = new \App\Models\AppointmentModel();
        $email = session('email');

        $appointments = $appointmentModel
            ->where('email', $email)
            ->orderBy('appointment_date', 'DESC')
            ->findAll();

        return view('user/view_appointments', ['appointments' => $appointments]);
    }

public function registerEvent()
{
    $eventId  = $this->request->getPost('event_id');
    $fullName = $this->request->getPost('full_name');
    $email    = $this->request->getPost('email');
    $contact  = $this->request->getPost('contact_number');
    $special  = $this->request->getPost('special_requirements');

    // ✅ Check if user is logged in
    $userId = session()->get('account_id');
    $userRole = session()->get('role'); // 'student' or 'employee'

    if (!$userId) {
        return redirect()->to('/login')
            ->with('error', 'You must be logged in to register for an event.');
    }

    // ✅ Validate input
    if (empty($eventId) || empty($fullName) || empty($email)) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Please fill out all required fields.');
    }

    // ✅ Get event info (to check audience)
    $eventModel = new \App\Models\EventModel();
    $event = $eventModel->find($eventId);

    if (!$event) {
        return redirect()->back()
            ->with('error', 'This event does not exist.');
    }

    // ✅ Audience check (only student or employee)
    $allowed = false;
    if ($event['audience'] === 'everyone') {
        $allowed = true;
    } elseif ($event['audience'] === 'students' && $userRole === 'student') {
        $allowed = true;
    } elseif ($event['audience'] === 'employees' && $userRole === 'employee') {
        $allowed = true;
    }

    if (!$allowed) {
        return redirect()->back()
            ->with('error', 'You are not allowed to register for this event.');
    }

    // ✅ Save registration
    $registrationModel = new \App\Models\EventRegistrationModel();

    $data = [
        'event_id'             => $eventId,
        'user_id'              => $userId,
        'full_name'            => $fullName,
        'email'                => $email,
        'contact_number'       => $contact,
        'special_requirements' => $special,
        'created_at'           => date('Y-m-d H:i:s')
    ];

    try {
        $registrationModel->insert($data);
        return redirect()->back()->with('success', 'You have successfully registered for this event!');
    } catch (\Exception $e) {
        log_message('error', 'Event Registration Failed: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to register for event. Please try again later.');
    }
}




    public function changePassword()
    {
        $session = session();
        $accountModel = new \App\Models\AccountModel();

        $userId = $session->get('account_id');
        $currentPassword = $this->request->getPost('current_password');
        $newPassword     = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        $account = $accountModel->find($userId);

        if (!$account) {
            return redirect()->back()->with('error', 'Account not found.');
        }

        if (!password_verify($currentPassword, $account['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New passwords do not match');
        }

        $accountModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully');
    }

    /**
     * ✅ New method to fetch identified complaints with user info
     */
    
}

