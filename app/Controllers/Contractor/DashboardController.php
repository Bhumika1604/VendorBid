<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected UserModel $userModel;
    protected ProjectModel $projectModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->projectModel = new ProjectModel();
    }

    public function index()
    {
        $profile = $this->userModel->find(authId());

        $data = [
            'title'     => 'Contractor Dashboard',
            'profile'   => $profile,
            'totalOpen' => $this->projectModel->totalOpen(),
        ];

        return view('contractor/dashboard', $data);
    }
}
