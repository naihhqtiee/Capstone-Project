<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Controllers\BaseController;
use App\Models\ComplaintModel;

class ComplaintController extends BaseController
{
    protected $complaintModel;

    // Allowed values
    protected $allowedComplaintTypes = ['academic', 'non-academic'];
    protected $allowedComplaintCategories = [
        'Harassment',
        'Bullying',
        'Discrimination',
        'Abuse of Authority',
        'Cheating',
        'Other'
    ];

    public function __construct()
    {
        $this->complaintModel = new ComplaintModel();
    }

    /**
     * List complaints for the logged-in user
     */
    public function index()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Please log in first.');
        }

        $data['complaints'] = $this->complaintModel->getByUser($userId);
        return view('user/view_complaint', $data);
    }

    /**
     * List anonymous complaints for staff
     */
    public function listAnonymous()
    {
        $complaints = $this->complaintModel
            ->where('is_anonymous', 1)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('staff/anonymous', ['complaints' => $complaints]);
    }
    private function getAnonymousValidationRules(): array
    {
        return [
            'date'               => 'required|valid_date',
            'location'           => 'required|min_length[3]',
            'complaint_type'     => 'required|in_list[academic,non-academic]',
            'complaint_category' => 'required|in_list[Harassment,Bullying,Discrimination,Abuse of Authority,Cheating,Other]',
            'impact'             => 'required|min_length[5]',
            'description'        => 'required|min_length[10]',
        ];
    }

    public function saveAnonymous()
    {
        if (!$this->validate($this->getAnonymousValidationRules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $uploadedFileNames = $this->handleFileUploads();
        if ($uploadedFileNames === false) {
            return redirect()->back()->withInput();
        }

        $data = [
            'user_id'           => null,
            'date'              => $this->request->getPost('date'),
            'location'          => trim((string) $this->request->getPost('location')),
            'complaint_type'    => $this->request->getPost('complaint_type'),
            'complaint_category'=> $this->request->getPost('complaint_category'),
            'description'       => trim((string) $this->request->getPost('description')),
            'impact'            => trim((string) $this->request->getPost('impact')),
            'files'             => !empty($uploadedFileNames) ? json_encode($uploadedFileNames) : null,
            'resolution'        => 'N/A',
            'resolution_other'  => null,
            'is_anonymous'      => 1,
            'status'            => 'pending',
            'created_at'        => date('Y-m-d H:i:s'),
        ];

        if ($this->complaintModel->insert($data)) {
            session()->setFlashdata('success', 'Your complaint has been submitted successfully!');
            return redirect()->to(base_url('user/userdashboard'));
        }

        session()->setFlashdata('error', 'Failed to submit complaint. Please try again.');
        return redirect()->back()->withInput();
    }

    /**
     * Save identified complaint (with time)
     */
    /**
     * View complaint (generate PDF)
     */
    public function view($id)
    {
        $complaint = $this->complaintModel->find($id);

        if (!$complaint) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Complaint not found");
        }

        $complaint['files'] = json_decode($complaint['files'], true);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $html = "
            <h2 style='text-align:center;'>Complaint Report</h2>
            <p><strong>Date:</strong> {$complaint['date']}</p>";

        // ✅ Show time only if exists (identified complaint)
        if (!empty($complaint['time'])) {
            $html .= "<p><strong>Time:</strong> {$complaint['time']}</p>";
        }

        $html .= "
            <p><strong>Location:</strong> {$complaint['location']}</p>
            <p><strong>Type:</strong> {$complaint['complaint_type']}</p>
            <p><strong>Category:</strong> {$complaint['complaint_category']}</p>
            <p><strong>Description:</strong> {$complaint['description']}</p>
            <p><strong>Impact:</strong> {$complaint['impact']}</p>
            <p><strong>Resolution:</strong> {$complaint['resolution']}</p>";

        if (!empty($complaint['resolution_other'])) {
            $html .= "<p><strong>Resolution (Other):</strong> {$complaint['resolution_other']}</p>";
        }

        if (!empty($complaint['files'])) {
            $html .= "<p><strong>Attached Files:</strong></p><ul>";
            foreach ($complaint['files'] as $file) {
                $fileUrl = base_url('uploads/complaints/' . $file);
                $html .= "<li><a href='{$fileUrl}'>{$file}</a></li>";
            }
            $html .= "</ul>";
        }

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("complaint_report_{$id}.pdf", ["Attachment" => false]);
    }

    /**
     * Delete a complaint
     */
    public function delete($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', 'No complaint ID provided.');
            return redirect()->back();
        }

        if ($this->complaintModel->delete($id)) {
            session()->setFlashdata('success', 'Complaint deleted successfully.');
        } else {
            session()->setFlashdata('error', 'Failed to delete complaint.');
        }

        return redirect()->to('/staff/complaints');
    }

    /**
     * Validation rules for identified complaints (with time)
     */
    private function getValidationRules()
    {
        return [
            'date'               => 'required|valid_date',
            'time'               => 'required', // ✅ validate time
            'location'           => 'required|min_length[3]|max_length[255]',
            'complaint_type'     => 'required|in_list[academic,non-academic]',
            'complaint_category' => 'required|in_list[Harassment,Bullying,Discrimination,Abuse of Authority,Cheating,Other]',
            'impact'             => 'required|min_length[10]|max_length[1000]',
            'description'        => 'required|min_length[20]|max_length[2000]',
            'resolution'         => 'required',
            'files'              => 'max_size[files,102400]|ext_in[files,jpg,jpeg,png,gif,mp4,avi,mov,pdf,doc,docx]'
        ];
    }
    

    /**
     * Handle file uploads
     */
    private function handleFileUploads()
    {
        $uploadedFileNames = [];
        $files = $this->request->getFileMultiple('files');
        $uploadPath = FCPATH . 'uploads/complaints';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        if ($files) {
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $maxSize = 100 * 1024 * 1024;
                    $allowedTypes = [
                        'image/jpeg', 'image/png', 'image/gif',
                        'video/mp4', 'video/avi', 'video/mov',
                        'application/pdf', 'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    ];

                    if ($file->getSize() > $maxSize) {
                        session()->setFlashdata('error', 'File too large: ' . $file->getName());
                        return false;
                    }

                    if (!in_array($file->getMimeType(), $allowedTypes)) {
                        session()->setFlashdata('error', 'Invalid file type: ' . $file->getName());
                        return false;
                    }

                    $newName = $file->getRandomName();
                    $file->move($uploadPath, $newName);
                    $uploadedFileNames[] = $newName;
                }
            }
        }

        return $uploadedFileNames;
    }

    public function edit($id)
{
    $complaintModel = new \App\Models\ComplaintModel();
    $complaint = $complaintModel->find($id);

    if (!$complaint) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Complaint not found");
    }

    return view('complaints/edit', ['complaint' => $complaint]);
}

public function update()
{
    $complaintModel = new \App\Models\ComplaintModel();

    $id = $this->request->getPost('id');
    $data = [
        'complaint_type'     => $this->request->getPost('complaint_type'),
        'complaint_category' => $this->request->getPost('complaint_category'),
        'location'           => $this->request->getPost('location'),
        'date'               => $this->request->getPost('date'),
        'description'        => $this->request->getPost('description'),
        'updated_at'         => date('Y-m-d H:i:s')
    ];

    if ($complaintModel->update($id, $data)) {
        return $this->response->setJSON(['success' => true]);
    }

    return $this->response->setJSON(['success' => false]);
}
public function updateStatus($id)
{
    $model = new \App\Models\ComplaintModel();

    $status = $this->request->getPost('status');
    if ($model->update($id, ['status' => $status])) {
        return redirect()->back()->with('success', 'Complaint status updated.');
    } else {
        return redirect()->back()->with('error', 'Failed to update status.');
    }
}



}
