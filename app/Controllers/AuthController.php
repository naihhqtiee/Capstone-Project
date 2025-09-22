<?php

namespace App\Controllers;

use App\Models\AccountModel;


class AuthController extends BaseController
{
    // Show login page
    public function index()
    {
        if (session()->get('logged_in')) {
            $role = session()->get('role');
            return $this->redirectByRole($role);
        }

        return view('login');
    }

    // Handle login POST
    public function login()
    {
        $session = session();
        
$email = trim($this->request->getPost('email'));
$password = trim($this->request->getPost('password'));


        // Use direct database connection to avoid model issues
        $db = \Config\Database::connect();
        
        try {
            // Find user by email
            $builder = $db->table('accounts');
            $user = $builder->where('email', $email)->get()->getRowArray();

            if (!$user) {
                return redirect()->back()->with('error', 'User not found.');
            }

            if ($user['status'] !== 'verified') {
                return redirect()->back()->with('error', 'Please verify your email before logging in.');
            }

            // Verify password
           if (password_verify($password, $user['password'])) {
    // Always use account_id instead of id for clarity
    $sessionData = [
        'account_id' => $user['id'],   // ✅ clearer naming
        'email'      => $user['email'],
        'full_name'  => $user['full_name'],
        'role'       => $user['role'],
        'logged_in'  => true
    ];

    // If student, fetch student details
    if ($user['role'] === 'student') {
        $builderStudent = $db->table('students');
        $student = $builderStudent->where('account_id', $user['id'])->get()->getRowArray();

        if ($student) {
            $fullName = $student['first_name'] . ' ' . 
                        ($student['mi'] ? $student['mi'] . '. ' : '') . 
                        $student['last_name'];

            $sessionData = array_merge($sessionData, [
                'first_name'     => $student['first_name'],
                'mi'             => $student['mi'],
                'last_name'      => $student['last_name'],
                'contact_number' => $student['contact_number'],
                'department'     => $student['department'],
                'course'         => $student['course'],
                'year'           => $student['year'],
                'full_name'      => $fullName // overwrite from accounts if needed
            ]);
        }
    }

    // Set all session data at once
    $session->set($sessionData);

    log_message('debug', 'Session after login: ' . print_r($session->get(), true));

    return $this->redirectByRole($user['role']);
}


            return redirect()->back()->with('error', 'Invalid email or password.');
            
        } catch (\Exception $e) {
            log_message('error', 'Login error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Login failed. Please try again.');
        }
    }

    // Helper: Redirect by role
    private function redirectByRole($role)
    {
        log_message('debug', 'Redirecting user with role: ' . $role);

        switch ($role) {
            case 'admin':
                log_message('debug', 'Redirecting to admin dashboard');
                return redirect()->to('/admin/dashboard');
            case 'staff':
                log_message('debug', 'Redirecting to staff dashboard');
                return redirect()->to('/staff/dashboard');
            case 'user':
            case 'student': // In case you're using 'student' instead of 'user'
                log_message('debug', 'Redirecting to user dashboard');
                return redirect()->to('/user/userdashboard');
            default:
                log_message('debug', 'Unknown role: ' . $role . ', redirecting to home');
                return redirect()->to('/');
        }
    }

    // Handle logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}