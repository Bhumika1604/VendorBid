<?php

namespace App\Controllers\Contractor;

use App\Controllers\BaseController;
use App\Models\ProjectModel;

class ProjectController extends BaseController
{
    protected ProjectModel $projectModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }

    /**
     * GET /contractor/projects
     * Browse all open projects available for bidding, with search + filter + pagination.
     */
    public function index()
    {
        $search   = trim((string) $this->request->getGet('search'));
        $category = (string) $this->request->getGet('category');

        $filters = [
            'search'   => $search,
            'category' => $category,
        ];

        $projects = $this->projectModel
            ->where('status', 'open')
            ->scopeFilters($filters)
            ->orderBy('created_at', 'DESC')
            ->paginate(9, 'projects');

        $pager = $this->projectModel->pager;
        $pager->only(['search', 'category']);

        return view('contractor/projects/index', [
            'title'      => 'Available Projects',
            'projects'   => $projects,
            'pager'      => $pager,
            'search'     => $search,
            'category'   => $category,
            'categories' => ProjectModel::categories(),
        ]);
    }

    /**
     * GET /contractor/projects/view/{id}
     */
    public function show(int $id)
    {
        $project = $this->projectModel->find($id);

        if (! $project) {
            return redirect()->to('/contractor/projects')->with('error', 'Project not found.');
        }

        return view('contractor/projects/view', [
            'title'   => 'Project Details',
            'project' => $project,
        ]);
    }
}
