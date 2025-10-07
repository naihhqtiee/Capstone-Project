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

      public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    // Handle reset link request
 public function sendResetLink()
{
    $email = trim($this->request->getPost('email'));
    $accountModel = new AccountModel();
    $user = $accountModel->where('email', $email)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'Email not found.');
    }

    $token = bin2hex(random_bytes(50));

    if (!$accountModel->update($user['id'], ['reset_token' => $token])) {
        return redirect()->back()->with('error', 'Failed to generate reset token.');
    }

    $resetLink = base_url("reset-password/$token");

    // PHPMailer
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'agonosmaybelle@gmail.com'; // your Gmail
        $mail->Password   = 'mywueddhmuuawlmk'; // App Password
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('agonosmaybelle@gmail.com', 'CSPC CHRE');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = '<p>Hello ' . $user['full_name'] . ',</p>
                          <p>You requested a password reset. Click the link below:</p>
                          <p><a href="' . $resetLink . '">Reset Password</a></p>
                          <p>If you did not request this, ignore this email.</p>';

        $mail->send();
        return redirect()->to('/login')->with('success', 'Password reset link sent to your email.');

    } catch (\PHPMailer\PHPMailer\Exception $e) {
        log_message('error', 'Reset email failed: ' . $mail->ErrorInfo);
        return redirect()->back()->with('error', 'Failed to send reset email: ' . $mail->ErrorInfo);
    }
}



    // Reset Password form
    public function resetPasswordForm($token)
    {
        $accountModel = new AccountModel();
        $user = $accountModel->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid or expired reset link.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    // Handle new password save
    public function resetPassword()
    {
        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        $accountModel = new AccountModel();
        $user = $accountModel->where('reset_token', $token)->first();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }

        $accountModel->update($user['id'], [
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'reset_token' => null
        ]);

        return redirect()->to('/login')->with('success', 'Password updated successfully.');
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