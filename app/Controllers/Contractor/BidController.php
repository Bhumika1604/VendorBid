<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;
use App\Models\BidModel;
use App\Models\ProjectModel;
use CodeIgniter\HTTP\RedirectResponse;

class BidController extends BaseController
{
    protected BidModel $bidModel;
    protected ProjectModel $projectModel;

    /**
     * Allowed upload settings for bid documents.
     */
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];
    private const MAX_UPLOAD_KB      = 5120; // 5 MB

    public function __construct()
    {
        $this->bidModel     = new BidModel();
        $this->projectModel = new ProjectModel();
    }

    /**
     * GET /contractor/bids
     * List every bid this contractor has submitted, with pagination.
     */
    public function index()
    {
        $bids = $this->bidModel
            ->myBidsQuery(authId())
            ->paginate(10, 'bids');

        $pager = $this->bidModel->pager;

        return view('contractor/bids/index', [
            'title' => 'My Bids',
            'bids'  => $bids,
            'pager' => $pager,
        ]);
    }

    /**
     * GET /contractor/bids/create
     * Show the bid submission form, listing only open projects the
     * contractor has not already bid on.
     */
    public function create()
    {
        $contractorId = authId();

        $biddedProjectIds = $this->bidModel->biddedProjectIds($contractorId);

        $projectsQuery = $this->projectModel->where('status', 'open');

        if (! empty($biddedProjectIds)) {
            $projectsQuery->whereNotIn('id', $biddedProjectIds);
        }

        $availableProjects = $projectsQuery->orderBy('created_at', 'DESC')->findAll();

        // If arriving from a specific project's "Place Bid" link (?project_id=)
        $preselectedProjectId = (int) ($this->request->getGet('project_id') ?? 0);

        return view('contractor/bids/create', [
            'title'                 => 'Submit a Bid',
            'projects'              => $availableProjects,
            'preselectedProjectId'  => $preselectedProjectId,
            'allowedExtensions'     => self::ALLOWED_EXTENSIONS,
            'maxUploadKb'           => self::MAX_UPLOAD_KB,
        ]);
    }

    /**
     * POST /contractor/bids/store
     */
    public function store(): RedirectResponse
    {
        $contractorId = authId();
        $projectId    = (int) $this->request->getPost('project_id');

        $project = $this->projectModel->find($projectId);

        if (! $project || $project['status'] !== 'open') {
            return redirect()->back()->withInput()->with('error', 'The selected project is not available for bidding.');
        }

        if ($this->bidModel->hasContractorBid($projectId, $contractorId)) {
            return redirect()->back()->withInput()->with('error', 'You have already submitted a bid for this project.');
        }

        $extensionList = implode(',', self::ALLOWED_EXTENSIONS);

        $rules = [
            'project_id'            => 'required|integer',
            'bid_amount'            => 'required|decimal|greater_than[0]',
            'estimated_days'        => 'required|integer|greater_than[0]',
            'proposal_description'  => 'required|min_length[20]',
            'previous_experience'   => 'required|min_length[10]',
            'document'              => "uploaded[document]|max_size[document," . self::MAX_UPLOAD_KB . "]|ext_in[document,{$extensionList}]",
        ];

        $messages = [
            'bid_amount' => [
                'greater_than' => 'Bid amount must be a positive value.',
            ],
            'estimated_days' => [
                'greater_than' => 'Estimated completion days must be at least 1.',
            ],
            'proposal_description' => [
                'min_length' => 'Please provide a more detailed proposal (at least 20 characters).',
            ],
            'previous_experience' => [
                'min_length' => 'Please describe your previous experience (at least 10 characters).',
            ],
            'document' => [
                'uploaded'  => 'Please upload your bid document (PDF, DOC or DOCX).',
                'max_size'  => 'The uploaded document must not exceed ' . round(self::MAX_UPLOAD_KB / 1024) . ' MB.',
                'ext_in'    => 'Only PDF, DOC and DOCX files are allowed.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('document');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return redirect()->back()->withInput()->with('error', 'There was a problem uploading your document. Please try again.');
        }

        $uploadPath = WRITEPATH . 'uploads/bids';

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newFileName = $file->getRandomName();
        $file->move($uploadPath, $newFileName);

        $data = [
            'project_id'            => $projectId,
            'contractor_id'         => $contractorId,
            'bid_amount'            => $this->request->getPost('bid_amount'),
            'estimated_days'        => $this->request->getPost('estimated_days'),
            'proposal_description'  => $this->request->getPost('proposal_description'),
            'previous_experience'   => $this->request->getPost('previous_experience'),
            'document_path'         => $newFileName,
            'status'                => 'pending',
        ];

        // Already validated above; skip the model's own validation pass.
        if (! $this->bidModel->skipValidation(true)->insert($data)) {
            // Roll back the uploaded file if the DB insert failed for any reason.
            $storedFile = $uploadPath . DIRECTORY_SEPARATOR . $newFileName;
            if (is_file($storedFile)) {
                unlink($storedFile);
            }

            return redirect()->back()->withInput()->with('errors', $this->bidModel->errors());
        }

        return redirect()->to('/contractor/bids')->with('success', 'Your bid has been submitted successfully.');
    }

    /**
     * GET /contractor/bids/view/{id}
     */
    public function show(int $id)
    {
        $bid = $this->bidModel->findWithDetails($id);

        if (! $bid || (int) $bid['contractor_id'] !== authId()) {
            return redirect()->to('/contractor/bids')->with('error', 'Bid not found.');
        }

        return view('contractor/bids/view', [
            'title' => 'Bid Details',
            'bid'   => $bid,
        ]);
    }

    /**
     * GET /contractor/bids/download/{id}
     * Securely stream a contractor's own uploaded bid document.
     */
    public function download(int $id)
    {
        $bid = $this->bidModel->find($id);

        if (! $bid || (int) $bid['contractor_id'] !== authId() || empty($bid['document_path'])) {
            return redirect()->to('/contractor/bids')->with('error', 'Document not found.');
        }

        $filePath = WRITEPATH . 'uploads/bids/' . $bid['document_path'];

        if (! is_file($filePath)) {
            return redirect()->back()->with('error', 'The requested document could not be located on the server.');
        }

        return $this->response->download($filePath, null);
    }
}
