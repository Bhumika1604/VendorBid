<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BidModel;
use App\Models\ProjectModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    protected ProjectModel $projectModel;
    protected UserModel $userModel;
    protected BidModel $bidModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->userModel    = new UserModel();
        $this->bidModel     = new BidModel();
    }

    public function index()
    {
        $data = [
            'title'            => 'Admin Dashboard',
            'totalProjects'    => $this->projectModel->totalProjects(),
            'totalContractors' => $this->userModel->totalContractors(),
            'totalBids'        => $this->bidModel->totalBids(),
            'totalAwarded'     => $this->projectModel->totalAwarded(),
        ];

        return view('admin/dashboard', $data);
    }
}
