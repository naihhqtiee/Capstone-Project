<?php

namespace App\Controllers;

use App\Models\AccountModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Pages extends BaseController
{
    public function home()
    {
        return view('Pages/home');
    }

    public function about() 
    {
        return view('Pages/about');
    }

    public function register()
    {
        return view('Pages/register', [
            'validation' => \Config\Services::validation(),
            'error'      => session()->getFlashdata('error'),
            'success'    => session()->getFlashdata('success')
        ]);
    }

   public function saveRegistration()
{
    $validationRules = [
        'full_name' => 'required',
        'email'     => [
            'label'  => 'CSPC Email',
            'rules'  => 'required|valid_email|regex_match[/^[a-zA-Z0-9._%+-]+@(cspc\.edu\.ph|my\.cspc\.edu\.ph)$/]|is_unique[accounts.email]',
            'errors' => [
                'required'    => 'Email is required.',
                'valid_email' => 'Please enter a valid email address.',
                'regex_match' => 'Only CSPC emails are allowed (student: @my.cspc.edu.ph, employee: @cspc.edu.ph).',
                'is_unique'   => 'This email is already registered.'
            ]
        ],
        'password' => 'required|min_length[6]',
    ];

    if (!$this->validate($validationRules)) {
        $errors = $this->validator->getErrors();
        session()->setFlashdata('validation_errors', $errors);
        return redirect()->back()->withInput();
    }

    $email = $this->request->getPost('email');
    $token = bin2hex(random_bytes(32));

    // ✅ Identify role based on domain
    if (preg_match('/@my\.cspc\.edu\.ph$/', $email)) {
        $role = 'student';
    } elseif (preg_match('/@cspc\.edu\.ph$/', $email)) {
        $role = 'employee';
    } else {
        return redirect()->back()->withInput()->with('error', 'Invalid CSPC email domain.');
    }

    // Try sending email
    $emailSent = $this->sendVerificationEmail($email, $token);
    if ($emailSent !== true) {
        return redirect()->back()->withInput()->with('error', '⚠️ Email sending failed: ' . $emailSent);
    }

    $db = \Config\Database::connect();

    try {
        $data = [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $email,
            'password'  => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'      => $role, // 👈 set role automatically
            'token'     => $token,
            'status'    => 'pending',
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ];

        $builder = $db->table('accounts');
        $result = $builder->insert($data);

        if (!$result) {
            throw new \Exception('Database insert failed');
        }

        return redirect()->to('/register')->with('success_popup', '✅ Registration submitted! Check your CSPC email to verify your account.');

    } catch (\Exception $e) {
        log_message('error', 'Registration error: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
    }
}


    private function sendVerificationEmail($email, $token)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'agonosmaybelle@gmail.com'; // your Gmail
            $mail->Password   = 'mywueddhmuuawlmk'; // your App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('agonosmaybelle@gmail.com', 'CSPC CHRE');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Verify Your CSPC CHRE Registration';
            $mail->Body    = 'Click the link to verify your email: <a href="' . base_url('verify/' . $token) . '">Verify Email</a>';

            $mail->send();
            return true;

        } catch (Exception $e) {
            log_message('error', 'Email error: ' . $mail->ErrorInfo);
            return $mail->ErrorInfo; // return error message
        }
    }

    public function verify($token = null)
    {
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Invalid verification link.');
        }

        // Use direct database connection instead of model
        $db = \Config\Database::connect();

        try {
            // Find user by token
            $builder = $db->table('accounts');
            $user = $builder->where('token', $token)->get()->getRowArray();

            if (!$user) {
                return redirect()->to('/login')->with('error', '❌ Invalid or expired verification link.');
            }

            // Update user status
            $builder = $db->table('accounts');
            $result = $builder->where('id', $user['id'])
                ->update([
                    'status' => 'verified',
                    'token' => null,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            if (!$result) {
                throw new \Exception('Failed to update user status');
            }

            return redirect()->to('/login')->with('success', '✅ Email successfully verified. You can now log in.');

        } catch (\Exception $e) {
            log_message('error', 'Verification error: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', '❌ Verification failed. Please try again.');
        }
    }
}
