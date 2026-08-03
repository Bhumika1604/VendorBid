<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Root "/" – route the visitor to wherever they belong.
     */
    public function index()
    {
        if (isLoggedIn()) {
            return isAdmin()
                ? redirect()->to('/admin/dashboard')
                : redirect()->to('/contractor/dashboard');
        }

        return redirect()->to('/login');
    }

    // -------------------------------------------------------------
    // Contractor Login
    // -------------------------------------------------------------

    public function contractorLogin()
    {
        if (isLoggedIn()) {
            return $this->redirectToDashboard();
        }

        return view('auth/contractor_login', [
            'title' => 'Contractor Login',
        ]);
    }

    public function contractorLoginAction()
    {
        return $this->attemptLogin('contractor');
    }

    // -------------------------------------------------------------
    // Admin Login
    // -------------------------------------------------------------

    public function adminLogin()
    {
        if (isLoggedIn()) {
            return $this->redirectToDashboard();
        }

        return view('auth/admin_login', [
            'title' => 'Admin Login',
        ]);
    }

    public function adminLoginAction()
    {
        return $this->attemptLogin('admin');
    }

    // -------------------------------------------------------------
    // Shared login attempt logic
    // -------------------------------------------------------------

    private function attemptLogin(string $role)
    {
        $rules = [
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->findByEmailAndRole($email, $role);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Invalid email or password.');
        }

        if ($user['status'] !== 'active') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Your account has been deactivated. Please contact the administrator.');
        }

        // Regenerate session id to prevent session fixation
        session()->regenerate();

        session()->set([
            'user_id'    => $user['id'],
            'name'       => $user['name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        session()->setFlashdata('success', 'Welcome back, ' . esc($user['name']) . '!');

        return $this->redirectToDashboard();
    }

    private function redirectToDashboard()
    {
        return isAdmin()
            ? redirect()->to('/admin/dashboard')
            : redirect()->to('/contractor/dashboard');
    }

    // -------------------------------------------------------------
    // Contractor Registration
    // -------------------------------------------------------------

    public function register()
    {
        if (isLoggedIn()) {
            return $this->redirectToDashboard();
        }

        return view('auth/register', [
            'title' => 'Contractor Registration',
        ]);
    }

    public function registerAction()
    {
        $rules = [
            'name'             => 'required|min_length[3]|max_length[150]',
            'email'            => 'required|valid_email|is_unique[users.email]',
            'company_name'     => 'required|min_length[2]|max_length[150]',
            'phone'            => 'required|min_length[7]|max_length[20]',
            'address'          => 'required|min_length[5]|max_length[255]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        $messages = [
            'email' => [
                'is_unique' => 'This email address is already registered.',
            ],
            'confirm_password' => [
                'matches' => 'Password confirmation does not match.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'role'         => 'contractor',
            'name'         => $this->request->getPost('name'),
            'email'        => $this->request->getPost('email'),
            'password'     => $this->request->getPost('password'), // hashed by model callback
            'company_name' => $this->request->getPost('company_name'),
            'phone'        => $this->request->getPost('phone'),
            'address'      => $this->request->getPost('address'),
            'status'       => 'active',
        ];

        if (! $this->userModel->insert($data, false)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->userModel->errors());
        }

        session()->setFlashdata('success', 'Registration successful! You can now login.');

        return redirect()->to('/login');
    }

    // -------------------------------------------------------------
    // Logout
    // -------------------------------------------------------------

    public function logout()
    {
        $wasAdmin = isAdmin();

        session()->destroy();

        return $wasAdmin ? redirect()->to('/admin/login') : redirect()->to('/login');
    }
}
