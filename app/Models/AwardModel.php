<?php

namespace App\Models;

use CodeIgniter\Model;

class AwardModel extends Model
{
    protected $table            = 'awards';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'project_id',
        'bid_id',
        'contractor_id',
        'awarded_by',
        'awarded_amount',
        'remarks',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'project_id'      => 'required|integer|is_unique[awards.project_id,id,{id}]',
        'bid_id'          => 'required|integer',
        'contractor_id'   => 'required|integer',
        'awarded_by'      => 'required|integer',
        'awarded_amount'  => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [
        'project_id' => [
            'is_unique' => 'This project has already been awarded.',
        ],
    ];

    /**
     * Whether a project has already been awarded.
     */
    public function isProjectAwarded(int $projectId): bool
    {
        return $this->where('project_id', $projectId)->countAllResults() > 0;
    }

    /**
     * Fetch the award record for a project, joined with contractor + project details.
     *
     * @return array<string, mixed>|null
     */
    public function findByProject(int $projectId): ?array
    {
        return $this->select('awards.*, projects.title as project_title, projects.category as project_category,
                projects.budget as project_budget, users.name as contractor_name, users.email as contractor_email,
                users.company_name as contractor_company, admins.name as awarded_by_name')
            ->join('projects', 'projects.id = awards.project_id')
            ->join('users', 'users.id = awards.contractor_id')
            ->join('users as admins', 'admins.id = awards.awarded_by')
            ->where('awards.project_id', $projectId)
            ->first();
    }

    /**
     * Award history — every award ever made, newest first, with search support.
     *
     * @param array<string, mixed> $filters ['search' => string]
     */
    public function historyQuery(array $filters = []): self
    {
        $this->select('awards.*, projects.title as project_title, projects.category as project_category,
                users.name as contractor_name, users.company_name as contractor_company')
            ->join('projects', 'projects.id = awards.project_id')
            ->join('users', 'users.id = awards.contractor_id');

        if (! empty($filters['search'])) {
            $this->groupStart()
                ->like('projects.title', $filters['search'])
                ->orLike('users.name', $filters['search'])
                ->orLike('users.company_name', $filters['search'])
                ->groupEnd();
        }

        return $this;
    }

    /**
     * Total number of projects awarded (used for reports/analytics).
     */
    public function totalAwards(): int
    {
        return $this->countAllResults();
    }
}
