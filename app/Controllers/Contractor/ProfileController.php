<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;
use App\Models\UserModel;

class ProfileController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * GET /contractor/profile
     */
    public function index()
    {
        $profile = $this->userModel->find(authId());

        return view('contractor/profile/index', [
            'title'   => 'My Profile',
            'profile' => $profile,
        ]);
    }

    /**
     * GET /contractor/profile/edit
     */
    public function edit()
    {
        $profile = $this->userModel->find(authId());

        return view('contractor/profile/edit', [
            'title'   => 'Edit Profile',
            'profile' => $profile,
        ]);
    }

    /**
     * POST /contractor/profile/update
     */
    public function update()
    {
        $id = authId();

        $rules = [
            'name'         => 'required|min_length[3]|max_length[150]',
            'email'        => "required|valid_email|max_length[150]|is_unique[users.email,id,{$id}]",
            'company_name' => 'required|min_length[2]|max_length[150]',
            'phone'        => 'required|min_length[7]|max_length[20]',
            'address'      => 'required|min_length[5]|max_length[255]',
        ];

        $messages = [
            'email' => [
                'is_unique' => 'This email address is already registered to another account.',
            ],
        ];

        // Only validate password fields if the contractor is trying to change it
        $newPassword = $this->request->getPost('password');

        if (! empty($newPassword)) {
            $rules['password']         = 'required|min_length[6]';
            $rules['confirm_password'] = 'required|matches[password]';
        }

        if (! $this->validate($rules, $messages)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'         => $this->request->getPost('name'),
            'email'        => $this->request->getPost('email'),
            'company_name' => $this->request->getPost('company_name'),
            'phone'        => $this->request->getPost('phone'),
            'address'      => $this->request->getPost('address'),
        ];

        if (! empty($newPassword)) {
            $data['password'] = $newPassword; // hashed automatically by the model callback
        }

        if (! $this->userModel->skipValidation(true)->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->userModel->errors());
        }

        // Keep session display values in sync with the updated profile
        session()->set([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        session()->setFlashdata('success', 'Profile updated successfully.');

        return redirect()->to('/contractor/profile');
    }
}
