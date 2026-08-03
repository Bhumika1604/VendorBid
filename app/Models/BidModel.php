<?php

namespace App\Models;

use CodeIgniter\Model;

class BidModel extends Model
{
    protected $table            = 'bids';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'project_id',
        'contractor_id',
        'bid_amount',
        'estimated_days',
        'proposal_description',
        'previous_experience',
        'document_path',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'project_id'            => 'required|integer',
        'contractor_id'         => 'required|integer',
        'bid_amount'            => 'required|decimal|greater_than[0]',
        'estimated_days'        => 'required|integer|greater_than[0]',
        'proposal_description'  => 'required|min_length[20]',
        'previous_experience'   => 'required|min_length[10]',
        'document_path'         => 'permit_empty|max_length[255]',
        'status'                => 'permit_empty|in_list[pending,shortlisted,awarded,rejected]',
    ];

    protected $validationMessages = [
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
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Fixed list of bid statuses.
     *
     * @return list<string>
     */
    public static function statuses(): array
    {
        return ['pending', 'shortlisted', 'awarded', 'rejected'];
    }

    /**
     * Whether the given contractor has already placed a bid on the given project.
     * Enforces the "one bid per contractor per project" business rule.
     */
    public function hasContractorBid(int $projectId, int $contractorId): bool
    {
        return $this->where('project_id', $projectId)
            ->where('contractor_id', $contractorId)
            ->countAllResults() > 0;
    }

    /**
     * IDs of every project this contractor has already bid on.
     *
     * @return list<int>
     */
    public function biddedProjectIds(int $contractorId): array
    {
        $rows = $this->select('project_id')
            ->where('contractor_id', $contractorId)
            ->findAll();

        return array_map(static fn ($row) => (int) $row['project_id'], $rows);
    }

    /**
     * A single contractor's bids, joined with project details, newest first.
     *
     * @return array<int, array<string, mixed>>
     */
    public function myBidsQuery(int $contractorId)
    {
        return $this->select('bids.*, projects.title as project_title, projects.status as project_status, projects.budget as project_budget')
            ->join('projects', 'projects.id = bids.project_id')
            ->where('bids.contractor_id', $contractorId)
            ->orderBy('bids.created_at', 'DESC');
    }

    /**
     * A single bid (by id) joined with project + contractor details.
     *
     * @return array<string, mixed>|null
     */
    public function findWithDetails(int $id): ?array
    {
        return $this->select('bids.*, projects.title as project_title, projects.status as project_status,
                projects.budget as project_budget, projects.deadline as project_deadline, projects.location as project_location,
                users.name as contractor_name, users.email as contractor_email, users.phone as contractor_phone,
                users.company_name as contractor_company')
            ->join('projects', 'projects.id = bids.project_id')
            ->join('users', 'users.id = bids.contractor_id')
            ->where('bids.id', $id)
            ->first();
    }

    /**
     * Builder for the Admin "All Bids" listing, joined with project + contractor
     * details, with optional search/filter conditions applied.
     *
     * @param array<string, mixed> $filters ['search' => string, 'status' => string, 'project_id' => int|string]
     */
    public function adminBidsQuery(array $filters = []): self
    {
        $this->select('bids.*, projects.title as project_title, projects.status as project_status,
                users.name as contractor_name, users.company_name as contractor_company')
            ->join('projects', 'projects.id = bids.project_id')
            ->join('users', 'users.id = bids.contractor_id');

        if (! empty($filters['search'])) {
            $this->groupStart()
                ->like('projects.title', $filters['search'])
                ->orLike('users.name', $filters['search'])
                ->orLike('users.company_name', $filters['search'])
                ->groupEnd();
        }

        if (! empty($filters['status'])) {
            $this->where('bids.status', $filters['status']);
        }

        if (! empty($filters['project_id'])) {
            $this->where('bids.project_id', $filters['project_id']);
        }

        return $this;
    }

    /**
     * All bids for a single project, joined with contractor details,
     * ordered by bid amount ascending (lowest first) — used for comparison.
     *
     * @return array<int, array<string, mixed>>
     */
    public function bidsForProject(int $projectId): array
    {
        return $this->select('bids.*, users.name as contractor_name, users.company_name as contractor_company, users.email as contractor_email')
            ->join('users', 'users.id = bids.contractor_id')
            ->where('bids.project_id', $projectId)
            ->orderBy('bids.bid_amount', 'ASC')
            ->findAll();
    }

    /**
     * Distinct list of projects that currently have at least one bid,
     * used to populate the Admin filter dropdown.
     *
     * @return array<int, array<string, mixed>>
     */
    public function projectsWithBids(): array
    {
        return $this->select('projects.id, projects.title')
            ->join('projects', 'projects.id = bids.project_id')
            ->groupBy('projects.id, projects.title')
            ->orderBy('projects.title', 'ASC')
            ->findAll();
    }

    /**
     * Total number of bids placed (used by Admin dashboard).
     */
    public function totalBids(): int
    {
        return $this->countAllResults();
    }
}
