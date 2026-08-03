<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AwardModel;
use App\Models\BidModel;
use App\Models\ProjectModel;
use App\Models\UserModel;

class AnalyticsController extends BaseController
{
    protected ProjectModel $projectModel;
    protected UserModel $userModel;
    protected BidModel $bidModel;
    protected AwardModel $awardModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->userModel    = new UserModel();
        $this->bidModel     = new BidModel();
        $this->awardModel   = new AwardModel();
    }

    /**
     * GET /admin/analytics
     * Visual dashboard: Total Projects, Open Projects, Awarded Projects,
     * Total Contractors, Total Bids — rendered with Chart.js.
     */
    public function index()
    {
        $totalProjects    = $this->projectModel->totalProjects();
        $openProjects     = $this->projectModel->totalOpen();
        $awardedProjects  = $this->projectModel->totalAwarded();
        $closedProjects   = max(0, $totalProjects - $openProjects - $awardedProjects);
        $totalContractors = $this->userModel->totalContractors();
        $totalBids        = $this->bidModel->totalBids();

        // Bid status breakdown for the doughnut chart
        $bidStatusCounts = [];
        foreach (BidModel::statuses() as $status) {
            $bidStatusCounts[$status] = $this->bidModel->where('status', $status)->countAllResults();
        }

        // Projects created per month for the last 6 months (trend line)
        $monthlyLabels = [];
        $monthlyCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01', strtotime("-{$i} months"));
            $monthEnd   = date('Y-m-t', strtotime("-{$i} months"));
            $label      = date('M Y', strtotime($monthStart));

            $count = $this->projectModel
                ->where('created_at >=', $monthStart . ' 00:00:00')
                ->where('created_at <=', $monthEnd . ' 23:59:59')
                ->countAllResults();

            $monthlyLabels[] = $label;
            $monthlyCounts[] = $count;
        }

        return view('admin/analytics/index', [
            'title'             => 'Analytics',
            'totalProjects'     => $totalProjects,
            'openProjects'      => $openProjects,
            'awardedProjects'   => $awardedProjects,
            'closedProjects'    => $closedProjects,
            'totalContractors'  => $totalContractors,
            'totalBids'         => $totalBids,
            'bidStatusCounts'   => $bidStatusCounts,
            'monthlyLabels'     => $monthlyLabels,
            'monthlyCounts'     => $monthlyCounts,
        ]);
    }
}
