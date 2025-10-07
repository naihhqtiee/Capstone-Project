<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\AccountModel;
use Google\Client as GoogleClient;
use Google\Service\Oauth2;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class GoogleAuth extends Controller
{
    private $client;

    public function __construct()
    {
        // Initialize Google Client
        $this->client = new GoogleClient();
        $this->client->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(base_url('google-callback'));
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    // Step 1: Redirect user to Google login
    public function login()
    {
        return redirect()->to($this->client->createAuthUrl());
    }

    // Step 2: Handle Google callback
    public function callback()
    {
        $code = $this->request->getVar('code');

        if (!$code) {
            return redirect()->to('/login')->with('error', 'Failed to login with Google.');
        }

        $token = $this->client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            return redirect()->to('/login')->with('error', 'Error fetching access token.');
        }

        $this->client->setAccessToken($token);

        // Get user info
        $oauth = new Oauth2($this->client);
        $googleUser = $oauth->userinfo->get();
        $email = $googleUser->email;

        // ✅ Restrict to CSPC emails only
        if (!preg_match('/@(cspc\.edu\.ph|my\.cspc\.edu\.ph)$/', $email)) {
            return redirect()->to('/login')->with('error', 'Only CSPC emails are allowed to sign in.');
        }

        // ✅ Determine role automatically
        $role = preg_match('/@my\.cspc\.edu\.ph$/', $email) ? 'student' : 'employee';

        $accountModel = new AccountModel();
        $user = $accountModel->where('email', $email)->first();

        if (!$user) {
            // Create new account if not exists
            $userData = [
                'full_name' => $googleUser->name,
                'email'     => $email,
                'password'  => password_hash(uniqid(), PASSWORD_BCRYPT), // dummy password
                'role'      => $role,
                'status'    => 'verified',
                'created_at'=> date('Y-m-d H:i:s'),
                'updated_at'=> date('Y-m-d H:i:s')
            ];
            $accountModel->insert($userData);
            $user = $accountModel->where('email', $email)->first();
        }

        // ✅ Match session format
        session()->set([
            'account_id' => $user['id'],
            'email'      => $user['email'],
            'full_name'  => $user['full_name'],
            'role'       => $user['role'],
            'logged_in'  => true
        ]);

        // ✅ Send login notification email using PHPMailer
        $this->sendLoginNotification($user['email'], $user['full_name']);

        // Redirect by role
        if ($user['role'] === 'student') {
            return redirect()->to('/user/userdashboard')->with('success', 'Logged in with Google!');
        } elseif ($user['role'] === 'employee') {
            return redirect()->to('/staff/dashboard')->with('success', 'Logged in with Google!');
        } elseif ($user['role'] === 'admin') {
            return redirect()->to('/admin/dashboard')->with('success', 'Logged in with Google!');
        }

        return redirect()->to('/login')->with('error', 'Role not recognized.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // ✅ Helper to send login notification with PHPMailer
    private function sendLoginNotification($email, $fullName)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'agonosmaybelle@gmail.com'; // your Gmail
            $mail->Password   = 'mywueddhmuuawlmk'; // your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('agonosmaybelle@gmail.com', 'CSPC CHRE');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Login Notification - CSPC CHRE';
            $mail->Body    = "
                Hello {$fullName},<br><br>
                Your account <b>{$email}</b> was just signed in using Google on 
                <b>" . date('Y-m-d H:i:s') . "</b>.<br><br>
                If this was you, no action is needed.<br>
                If not, please reset your password immediately or contact support.<br><br>
                Regards,<br>
                CSPC CHRE Security Team
            ";

            $mail->send();
            log_message('info', "Login notification sent to {$email}");

        } catch (Exception $e) {
            log_message('error', 'Login notification email failed: ' . $mail->ErrorInfo);
        }
    }
}
