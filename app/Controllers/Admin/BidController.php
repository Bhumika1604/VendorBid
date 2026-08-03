<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BidModel;
use App\Models\ProjectModel;

class BidController extends BaseController
{
    protected BidModel $bidModel;
    protected ProjectModel $projectModel;

    public function __construct()
    {
        $this->bidModel     = new BidModel();
        $this->projectModel = new ProjectModel();
    }

    /**
     * GET /admin/bids
     * List every bid submitted across all projects, with search, filter and pagination.
     */
    public function index()
    {
        $search    = trim((string) $this->request->getGet('search'));
        $status    = (string) $this->request->getGet('status');
        $projectId = (string) $this->request->getGet('project_id');

        $filters = [
            'search'     => $search,
            'status'     => $status,
            'project_id' => $projectId,
        ];

        $bids = $this->bidModel
            ->adminBidsQuery($filters)
            ->orderBy('bids.created_at', 'DESC')
            ->paginate(10, 'bids');

        $pager = $this->bidModel->pager;
        $pager->only(['search', 'status', 'project_id']);

        return view('admin/bids/index', [
            'title'          => 'Manage Bids',
            'bids'           => $bids,
            'pager'          => $pager,
            'search'         => $search,
            'status'         => $status,
            'projectId'      => $projectId,
            'statuses'       => BidModel::statuses(),
            'projectOptions' => $this->bidModel->projectsWithBids(),
        ]);
    }

    /**
     * GET /admin/bids/view/{id}
     */
    public function show(int $id)
    {
        $bid = $this->bidModel->findWithDetails($id);

        if (! $bid) {
            return redirect()->to('/admin/bids')->with('error', 'Bid not found.');
        }

        return view('admin/bids/view', [
            'title' => 'Bid Details',
            'bid'   => $bid,
        ]);
    }

    /**
     * GET /admin/bids/compare/{projectId}
     * Side-by-side comparison of every bid submitted for one project,
     * with the lowest bid amount highlighted.
     */
    public function compare(int $projectId)
    {
        $project = $this->projectModel->find($projectId);

        if (! $project) {
            return redirect()->to('/admin/bids')->with('error', 'Project not found.');
        }

        $bids = $this->bidModel->bidsForProject($projectId);

        $lowestBidId = null;

        if (! empty($bids)) {
            $lowestBid   = min(array_column($bids, 'bid_amount'));
            $lowestBidId = null;

            foreach ($bids as $bid) {
                if ((float) $bid['bid_amount'] === (float) $lowestBid) {
                    $lowestBidId = $bid['id'];
                    break;
                }
            }
        }

        return view('admin/bids/compare', [
            'title'       => 'Compare Bids',
            'project'     => $project,
            'bids'        => $bids,
            'lowestBidId' => $lowestBidId,
        ]);
    }

    /**
     * GET /admin/bids/download/{id}
     * Securely stream any contractor's uploaded bid document.
     */
    public function download(int $id)
    {
        $bid = $this->bidModel->find($id);

        if (! $bid || empty($bid['document_path'])) {
            return redirect()->to('/admin/bids')->with('error', 'Document not found.');
        }

        $filePath = WRITEPATH . 'uploads/bids/' . $bid['document_path'];

        if (! is_file($filePath)) {
            return redirect()->back()->with('error', 'The requested document could not be located on the server.');
        }

        return $this->response->download($filePath, null);
    }
}
