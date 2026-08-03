<?php

namespace App\Models;

use CodeIgniter\Model;

class ProjectModel extends Model
{
    protected $table            = 'projects';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'title',
        'description',
        'category',
        'required_skills',
        'location',
        'budget',
        'deadline',
        'status',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules = [
        'title'           => 'required|min_length[5]|max_length[200]',
        'description'     => 'required|min_length[20]',
        'category'        => 'required|max_length[100]',
        'required_skills' => 'required|max_length[500]',
        'location'        => 'required|max_length[150]',
        'budget'          => 'required|decimal|greater_than[0]',
        'deadline'        => 'required|valid_date[Y-m-d]',
        'status'          => 'required|in_list[open,closed,awarded]',
        'created_by'      => 'required|integer',
    ];

    protected $validationMessages = [
        'budget' => [
            'greater_than' => 'Budget must be a positive amount.',
        ],
        'deadline' => [
            'valid_date' => 'Please provide a valid deadline date.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Fixed list of project categories used across create/edit/filter forms.
     *
     * @return list<string>
     */
    public static function categories(): array
    {
        return [
            'Construction',
            'IT & Software',
            'Electrical',
            'Plumbing',
            'Interior Design',
            'Landscaping',
            'Architecture',
            'Consulting',
            'Transportation',
            'Other',
        ];
    }

    /**
     * Fixed list of statuses used across create/edit/filter forms.
     *
     * @return list<string>
     */
    public static function statuses(): array
    {
        return ['open', 'closed', 'awarded'];
    }

    /**
     * Apply search + filter conditions to the current query builder.
     * Chainable — call before paginate()/findAll().
     *
     * @param array<string, mixed> $filters ['search' => string, 'status' => string, 'category' => string]
     */
    public function scopeFilters(array $filters): self
    {
        if (! empty($filters['search'])) {
            $this->groupStart()
                ->like('title', $filters['search'])
                ->orLike('description', $filters['search'])
                ->orLike('location', $filters['search'])
                ->groupEnd();
        }

        if (! empty($filters['status'])) {
            $this->where('status', $filters['status']);
        }

        if (! empty($filters['category'])) {
            $this->where('category', $filters['category']);
        }

        return $this;
    }

    /**
     * Total number of projects (used by Admin dashboard).
     */
    public function totalProjects(): int
    {
        return $this->countAllResults();
    }

    /**
     * Total number of projects that have been awarded (used by Admin dashboard).
     */
    public function totalAwarded(): int
    {
        return $this->where('status', 'awarded')->countAllResults();
    }

    /**
     * Total number of currently open projects (used by Contractor dashboard).
     */
    public function totalOpen(): int
    {
        return $this->where('status', 'open')->countAllResults();
    }

    /**
     * Fetch a single open project, or null if it doesn't exist / isn't open.
     *
     * @return array<string, mixed>|null
     */
    public function findOpenProject(int $id): ?array
    {
        return $this->where('id', $id)->where('status', 'open')->first();
    }
}
