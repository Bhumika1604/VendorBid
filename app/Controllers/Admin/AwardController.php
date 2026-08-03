<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\NotificationService;
use App\Models\AwardModel;
use App\Models\BidModel;
use App\Models\ProjectModel;
use CodeIgniter\HTTP\RedirectResponse;

class AwardController extends BaseController
{
    protected AwardModel $awardModel;
    protected BidModel $bidModel;
    protected ProjectModel $projectModel;
    protected NotificationService $notifier;

    public function __construct()
    {
        $this->awardModel   = new AwardModel();
        $this->bidModel     = new BidModel();
        $this->projectModel = new ProjectModel();
        $this->notifier     = new NotificationService();
    }

    /**
     * GET /admin/awards
     * Lists every project that has received at least one bid, showing
     * whether it is still pending award or has already been awarded.
     * Also serves as the award history overview.
     */
    public function index()
    {
        $search = trim((string) $this->request->getGet('search'));

        $builder = $this->projectModel
            ->select('projects.*,
                (SELECT COUNT(*) FROM bids WHERE bids.project_id = projects.id AND bids.deleted_at IS NULL) as bid_count,
                (SELECT COUNT(*) FROM awards WHERE awards.project_id = projects.id AND awards.deleted_at IS NULL) as is_awarded')
            ->whereIn('projects.id', static function ($subQuery) {
                return $subQuery->select('project_id')->from('bids')->where('deleted_at', null);
            });

        if ($search !== '') {
            $builder->like('projects.title', $search);
        }

        $projects = $builder->orderBy('projects.created_at', 'DESC')->paginate(10, 'awards');
        $pager    = $this->projectModel->pager;
        $pager->only(['search']);

        return view('admin/awards/index', [
            'title'    => 'Award Management',
            'projects' => $projects,
            'pager'    => $pager,
            'search'   => $search,
        ]);
    }

    /**
     * GET /admin/awards/view/{projectId}
     * If not yet awarded: shows the bid comparison + winner-selection form.
     * If already awarded: shows the award record + full award history for that project.
     */
    public function show(int $projectId)
    {
        $project = $this->projectModel->find($projectId);

        if (! $project) {
            return redirect()->to('/admin/awards')->with('error', 'Project not found.');
        }

        $award = $this->awardModel->findByProject($projectId);
        $bids  = $this->bidModel->bidsForProject($projectId);

        $lowestBidId = null;

        if (! empty($bids)) {
            $lowestAmount = min(array_column($bids, 'bid_amount'));

            foreach ($bids as $bid) {
                if ((float) $bid['bid_amount'] === (float) $lowestAmount) {
                    $lowestBidId = $bid['id'];
                    break;
                }
            }
        }

        return view('admin/awards/view', [
            'title'       => $award ? 'Award Details' : 'Select Winning Bid',
            'project'     => $project,
            'award'       => $award,
            'bids'        => $bids,
            'lowestBidId' => $lowestBidId,
        ]);
    }

    /**
     * POST /admin/awards/store/{projectId}
     * Awards the project to the selected bid, rejects every other bid,
     * updates the project status, and fires award/rejection notifications.
     */
    public function store(int $projectId): RedirectResponse
    {
        $project = $this->projectModel->find($projectId);

        if (! $project) {
            return redirect()->to('/admin/awards')->with('error', 'Project not found.');
        }

        if ($this->awardModel->isProjectAwarded($projectId)) {
            return redirect()->to('/admin/awards/view/' . $projectId)->with('error', 'This project has already been awarded.');
        }

        $rules = [
            'bid_id'  => 'required|integer',
            'remarks' => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/admin/awards/view/' . $projectId)->with('errors', $this->validator->getErrors());
        }

        $winningBidId = (int) $this->request->getPost('bid_id');
        $winningBid   = $this->bidModel->find($winningBidId);

        if (! $winningBid || (int) $winningBid['project_id'] !== $projectId) {
            return redirect()->to('/admin/awards/view/' . $projectId)->with('error', 'The selected bid does not belong to this project.');
        }

        // 1. Record the award
        $awardData = [
            'project_id'     => $projectId,
            'bid_id'         => $winningBidId,
            'contractor_id'  => $winningBid['contractor_id'],
            'awarded_by'     => authId(),
            'awarded_amount' => $winningBid['bid_amount'],
            'remarks'        => $this->request->getPost('remarks'),
        ];

        if (! $this->awardModel->skipValidation(true)->insert($awardData)) {
            return redirect()->to('/admin/awards/view/' . $projectId)->with('errors', $this->awardModel->errors());
        }

        // 2. Update the project status
        $this->projectModel->skipValidation(true)->update($projectId, ['status' => 'awarded']);

        // 3. Mark the winning bid as awarded
        $this->bidModel->skipValidation(true)->update($winningBidId, ['status' => 'awarded']);

        // 4. Reject every other bid on this project
        $this->bidModel->skipValidation(true)
            ->where('project_id', $projectId)
            ->where('id !=', $winningBidId)
            ->set(['status' => 'rejected', 'updated_at' => date('Y-m-d H:i:s')])
            ->update();

        // 5. Fire notifications (email + in-app) — failures here never block the award itself
        $winningBidDetails = $this->bidModel->findWithDetails($winningBidId);
        if ($winningBidDetails) {
            $this->notifier->projectAwarded($winningBidDetails);
        }

        $otherBids = $this->bidModel
            ->where('project_id', $projectId)
            ->where('id !=', $winningBidId)
            ->findAll();

        foreach ($otherBids as $otherBid) {
            $details = $this->bidModel->findWithDetails($otherBid['id']);
            if ($details) {
                $this->notifier->bidRejected($details);
            }
        }

        return redirect()->to('/admin/awards/view/' . $projectId)->with('success', 'Project awarded successfully. Notifications have been sent to all bidders.');
    }
}
