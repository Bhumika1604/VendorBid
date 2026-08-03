<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * AuthFilter
 *
 * Protects routes based on login state and role.
 * Usage in Routes.php:
 *   ['filter' => 'authfilter:admin']
 *   ['filter' => 'authfilter:contractor']
 */
class AuthFilter implements FilterInterface
{
    /**
     * Runs before the controller method.
     *
     * @param array|null $arguments
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = Services::session();

        // Not logged in at all -> send to the appropriate login page
        if (! $session->get('isLoggedIn')) {
            $session->setFlashdata('error', 'Please login to continue.');

            if (is_array($arguments) && in_array('admin', $arguments, true)) {
                return redirect()->to('/admin/login');
            }

            return redirect()->to('/login');
        }

        // Logged in, but role does not match what this route requires
        $role = $session->get('role');

        if (is_array($arguments) && ! in_array($role, $arguments, true)) {
            $session->setFlashdata('error', 'You are not authorized to access that area.');

            return $role === 'admin'
                ? redirect()->to('/admin/dashboard')
                : redirect()->to('/contractor/dashboard');
        }

        return null;
    }

    /**
     * Runs after the controller method.
     *
     * @param array|null $arguments
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing to do here.
    }
}
