<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\AccountModel;

class StudentController extends BaseController
{
    protected $studentModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
    }

    public function profile()
    {
        $accountId = session()->get('account_id'); // ✅ fixed

        if (!$accountId) {
            return redirect()->to('/login')->with('error', 'Unauthorized access.');
        }

        $student = $this->studentModel->where('account_id', $accountId)->first();

        // ✅ Show the form even if profile does not exist
        return view('student/profile', ['student' => $student]);
    }

    public function login()
    {
        $accountModel = new AccountModel();
        $studentModel = new StudentModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $accountModel->where('email', $email)->first(); // ✅ fixed

        if ($user && password_verify($password, $user['password'])) {
            session()->set([
                'account_id' => $user['id'], // ✅ fixed
                'email'      => $user['email'],
                'role'       => $user['role'],
                'logged_in'  => true,
            ]);

            if ($user['role'] === 'student') {
                $student = $studentModel->where('account_id', $user['id'])->first();

                if ($student) {
                    session()->set([
                        'first_name'     => $student['first_name'],
                        'mi'             => $student['mi'],
                        'last_name'      => $student['last_name'],
                        'email'          => $student['email'],
                        'contact_number' => $student['contact_number'],
                        'department'     => $student['department'],
                        'course'         => $student['course'],
                        'year'           => $student['year'],
                        'full_name'      => $student['first_name'] . ' ' . $student['last_name'],
                    ]);
                }
            }

            return redirect()->to('/user/userdashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
    }

    public function updateProfile()
    {
        $session = session();
        $studentModel = new StudentModel();
        $accountModel = new AccountModel();

        $accountId = $session->get('account_id'); // ✅ fixed

        if (!$accountId) {
            return redirect()->to('/login')->with('error', 'Unauthorized access.');
        }

        // validation rules
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email'      => "required|valid_email|is_unique[accounts.email,id,{$accountId}]",
            'first_name' => 'required',
            'last_name'  => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('error', $validation->listErrors())->withInput();
        }

        $studentData = [
            'first_name'     => $this->request->getPost('first_name'),
            'mi'             => $this->request->getPost('mi'),
            'last_name'      => $this->request->getPost('last_name'),
            'email'          => $this->request->getPost('email'),
            'contact_number' => $this->request->getPost('contact_number'),
            'department'     => $this->request->getPost('department'),
            'course'         => $this->request->getPost('course'),
            'year'           => $this->request->getPost('year'),
            'account_id'     => $accountId,
        ];

        $existing = $studentModel->where('account_id', $accountId)->first();

        if ($existing) {
            $studentModel->update($existing['id'], $studentData);
        } else {
            $studentModel->insert($studentData);
        }

        // update account email
        $accountModel->update($accountId, ['email' => $studentData['email']]);

        // refresh session
        $session->set([
            'first_name'     => $studentData['first_name'],
            'mi'             => $studentData['mi'],
            'last_name'      => $studentData['last_name'],
            'email'          => $studentData['email'],
            'contact_number' => $studentData['contact_number'],
            'department'     => $studentData['department'],
            'course'         => $studentData['course'],
            'year'           => $studentData['year'],
            'full_name'      => $studentData['first_name'] . ' ' . $studentData['last_name'],
        ]);

        return redirect()->back()->with('message', 'Profile updated successfully.');
    }
}
