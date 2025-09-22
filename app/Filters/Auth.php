<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Auth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Authentication required.');
        }

        // Check session timeout
        $loginTime = session()->get('login_time');
        $sessionExpiry = getenv('APP_SESSION_EXPIRES') ?: 7200; // 2 hours default
        
        if ($loginTime && (time() - $loginTime) > $sessionExpiry) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Session expired.');
        }

        // Role-based access control
        if ($arguments) {
            $requiredRole = $arguments[0];
            $userRole = session()->get('user_role');
            
            if ($userRole !== $requiredRole) {
                return redirect()->to('/unauthorized');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Update last activity time
        if (session()->get('logged_in')) {
            session()->set('last_activity', time());
        }
    }
}