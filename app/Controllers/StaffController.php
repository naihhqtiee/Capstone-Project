<?php

namespace App\Controllers;

use App\Models\ComplaintModel;
use App\Models\AppointmentModel;
use App\Models\StudentModel;
use App\Models\EventModel;

class StaffController extends BaseController
{
    public function dashboard()
    {
        // Complaints
        $complaintModel = new ComplaintModel();
        $data['total_complaints'] = $complaintModel->countAll();
        $data['pending_cases'] = $complaintModel->where('status', 'pending')->countAllResults();

        // Complaints grouped by type
        $complaintCategory = $complaintModel->select('complaint_category, COUNT(*) as total')
            ->groupBy('complaint_category')
            ->findAll();

        $typeData = [];
        foreach ($complaintCategory as $row) {
            $typeData[$row['complaint_category']] = (int) $row['total'];
        }

        $data['complaintCategory'] = $typeData;

        // Percentages
        $total = array_sum($typeData);
        $progressData = [];
        foreach ($typeData as $type => $count) {
            $progressData[$type] = [
                'count' => $count,
                'percentage' => $total > 0 ? round(($count / $total) * 100, 1) : 0
            ];
        }
        $data['complaintProgress'] = $progressData;

        // Appointments
        $appointmentModel = new AppointmentModel();
        $data['appointments'] = $appointmentModel
            ->orderBy('appointment_date', 'ASC')
            ->findAll();

        return view('staff/dashboard', $data);
    }



public function complaints()
{
    $model = new \App\Models\ComplaintModel();

    // ✅ Get complaints with user data for identified ones
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

    return view('staff/complaints', [
        'complaints' => $complaints ?? []
    ]);
}


    public function storeIdentified()
    {
        $model = new ComplaintModel();

        // Handle file uploads if any
        $uploadedFiles = [];
        if ($this->request->getFiles()) {
            foreach ($this->request->getFiles()['files'] ?? [] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/complaints', $newName);
                    $uploadedFiles[] = $newName;
                }
            }
        }

        $complaintData = [
            'date'             => $this->request->getPost('date'),
            'location'         => $this->request->getPost('location'),
            'complaint_type'   => $this->request->getPost('complaint_type'),
            'description'      => $this->request->getPost('description'),
            'impact'           => $this->request->getPost('impact'),
            'files'            => json_encode($uploadedFiles),
            'resolution'       => $this->request->getPost('resolution'),
            'resolution_other' => $this->request->getPost('resolution_other'),
            'is_anonymous'     => 0,
            'user_id'          => session()->get('user_id'),
            'status'           => 'pending',
            'created_at'       => date('Y-m-d H:i:s'),
        ];

        $model->insert($complaintData);

        return redirect()->to('/staff/identified')
            ->with('success', 'Your identified complaint has been submitted.');
    }

  

    public function appointments()
    {
        $model = new AppointmentModel();
        $data['appointments'] = $model->orderBy('appointment_date', 'ASC')->findAll();

        return view('staff/appointments', $data);
    }

    public function updateStatus($id)
    {
        $model = new AppointmentModel();

        $status = $this->request->getPost('status');
        $reason = $this->request->getPost('rejection_reason');

        $model->update($id, [
            'status' => $status,
            'rejection_reason' => ($status === 'Rejected') ? $reason : null
        ]);

        return redirect()->back()->with('message', 'Appointment status updated!');
    }

    public function students()
    {
        $studentModel = new StudentModel();
        $data['students'] = $studentModel->findAll();
        return view('staff/students', $data);
    }

    public function getNotifications()
    {
        $complaintModel = new \App\Models\ComplaintModel();
        $appointmentModel = new \App\Models\AppointmentModel();
        $studentModel = new \App\Models\StudentModel();

        $data = [
            'complaints'   => $complaintModel->where('is_read', 0)->countAllResults(),
            'appointments' => $appointmentModel->where('is_read', 0)->countAllResults(),
            'students'     => $studentModel->where('created_at >=', date('Y-m-d 00:00:00'))->countAllResults(),
        ];

        $data['total'] = array_sum($data);

        return $this->response->setJSON($data);
    }

    public function eventRegistrants($eventId)
    {
        $db = \Config\Database::connect();

        $registrants = $db->table('event_registrations')
            ->select('id, full_name, email, contact_number, special_requirements, created_at')
            ->where('event_id', $eventId)
            ->get()
            ->getResultArray();

        return view('staff/event_registrants', ['registrants' => $registrants]);
    }

    public function deleteEvent($id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);

        if (!$event) {
            return redirect()->back()->with('error', 'Event not found.');
        }

        $eventModel->delete($id);

        return redirect()->to('/staff/events')->with('success', 'Event deleted successfully.');
    }

    public function updateEvent($id)
    {
        $eventModel = new EventModel();

        // File upload
        $file = $this->request->getFile('file');
        $fileName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/events', $fileName);
        }

        $start_date = $this->request->getPost('start_date');
        $start_time = $this->request->getPost('start_time');
        $end_date   = $this->request->getPost('end_date');
        $end_time   = $this->request->getPost('end_time');

        $eventModel->save([
            'id'          => $id,
            'event_name'  => $this->request->getPost('event_name'),
            'description' => $this->request->getPost('description'),
            'start_date'  => $start_date . ' ' . $start_time,
            'end_date'    => $end_date . ' ' . $end_time,
            'location'    => $this->request->getPost('location'),
            'audience'    => $this->request->getPost('audience'),
            'file'        => $fileName ?? $this->request->getPost('existing_file')
        ]);

        return redirect()->to('/staff/events')->with('success', 'Event updated successfully!');
    }
public function uploadNda()
{
    $file = $this->request->getFile('nda_file');

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();

        // ✅ Save into public/uploads/nda so it's accessible via URL
        $file->move(FCPATH . 'uploads/nda', $newName);

        // Save record in database
        $ndaModel = new \App\Models\NdaModel();
        $ndaModel->insert([
            'account_id'  => session('account_id'), // logged-in staff account
            'file_path'   => 'uploads/nda/' . $newName, // relative to public/
            'uploaded_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'NDA uploaded successfully!',
            'file'    => $newName,
            'url'     => base_url('uploads/nda/' . $newName) // ✅ return URL
        ]);
    }

    return $this->response->setJSON([
        'success' => false,
        'message' => 'Failed to upload NDA.'
    ]);
}
public function ndaManagement()
{
    $ndaModel = new NdaModel();

    // ✅ Fetch the latest NDA file (last uploaded one)
    $ndaFile = $ndaModel->orderBy('uploaded_at', 'DESC')->first();

    return view('staff/nda_management', [
        'ndaFile' => $ndaFile
    ]);
}

public function viewNda()
{
    $ndaModel = new NdaModel();
    $ndaFile = $ndaModel->orderBy('uploaded_at', 'DESC')->first();

    if (!$ndaFile || !file_exists(FCPATH . $ndaFile['file_path'])) {
        return redirect()->back()->with('error', 'NDA file not found.');
    }

    return $this->response->download(FCPATH . $ndaFile['file_path'], null)->setFileName('nda.pdf');
}

public function downloadNda()
{
    $ndaModel = new NdaModel();
    $ndaFile = $ndaModel->orderBy('uploaded_at', 'DESC')->first();

    if (!$ndaFile || !file_exists(FCPATH . $ndaFile['file_path'])) {
        return redirect()->back()->with('error', 'NDA file not found.');
    }

    return $this->response->download(FCPATH . $ndaFile['file_path'], null);
}

public function deleteNda($id)
{
    $ndaModel = new NdaModel();
    $ndaFile = $ndaModel->find($id);

    if ($ndaFile) {
        // Delete physical file
        if (file_exists(FCPATH . $ndaFile['file_path'])) {
            unlink(FCPATH . $ndaFile['file_path']);
        }

        // Delete DB record
        $ndaModel->delete($id);
        return redirect()->back()->with('success', 'NDA deleted successfully.');
    }

    return redirect()->back()->with('error', 'NDA not found.');
}


}
