<?php

namespace App\Controllers\Admin;

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
     * Shared field-level validation rules for create/edit forms.
     * (Excludes 'created_by', which is set internally and never edited.)
     *
     * @return array<string, string>
     */
    private function projectRules(): array
    {
        return [
            'title'           => 'required|min_length[5]|max_length[200]',
            'description'     => 'required|min_length[20]',
            'category'        => 'required|max_length[100]',
            'required_skills' => 'required|max_length[500]',
            'location'        => 'required|max_length[150]',
            'budget'          => 'required|decimal|greater_than[0]',
            'deadline'        => 'required|valid_date[Y-m-d]',
            'status'          => 'required|in_list[open,closed,awarded]',
        ];
    }

    /**
     * GET /admin/projects
     * List all projects with search, filter and pagination.
     */
    public function index()
    {
        $search   = trim((string) $this->request->getGet('search'));
        $status   = (string) $this->request->getGet('status');
        $category = (string) $this->request->getGet('category');

        $filters = [
            'search'   => $search,
            'status'   => $status,
            'category' => $category,
        ];

        $projects = $this->projectModel
            ->scopeFilters($filters)
            ->orderBy('created_at', 'DESC')
            ->paginate(10, 'projects');

        $pager = $this->projectModel->pager;
        $pager->only(['search', 'status', 'category']);

        return view('admin/projects/index', [
            'title'      => 'Manage Projects',
            'projects'   => $projects,
            'pager'      => $pager,
            'search'     => $search,
            'status'     => $status,
            'category'   => $category,
            'categories' => ProjectModel::categories(),
            'statuses'   => ProjectModel::statuses(),
        ]);
    }

    /**
     * GET /admin/projects/view/{id}
     */
    public function show(int $id)
    {
        $project = $this->projectModel->find($id);

        if (! $project) {
            return redirect()->to('/admin/projects')->with('error', 'Project not found.');
        }

        return view('admin/projects/view', [
            'title'   => 'Project Details',
            'project' => $project,
        ]);
    }

    /**
     * GET /admin/projects/create
     */
    public function create()
    {
        return view('admin/projects/create', [
            'title'      => 'Create Project',
            'categories' => ProjectModel::categories(),
            'statuses'   => ProjectModel::statuses(),
        ]);
    }

    /**
     * POST /admin/projects/store
     */
    public function store()
    {
        if (! $this->validate($this->projectRules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title'           => $this->request->getPost('title'),
            'description'     => $this->request->getPost('description'),
            'category'        => $this->request->getPost('category'),
            'required_skills' => $this->request->getPost('required_skills'),
            'location'        => $this->request->getPost('location'),
            'budget'          => $this->request->getPost('budget'),
            'deadline'        => $this->request->getPost('deadline'),
            'status'          => $this->request->getPost('status') ?: 'open',
            'created_by'      => authId(),
        ];

        // Already validated above; skip the model's own validation pass.
        if (! $this->projectModel->skipValidation(true)->insert($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->projectModel->errors());
        }

        return redirect()->to('/admin/projects')->with('success', 'Project created successfully.');
    }

    /**
     * GET /admin/projects/edit/{id}
     */
    public function edit(int $id)
    {
        $project = $this->projectModel->find($id);

        if (! $project) {
            return redirect()->to('/admin/projects')->with('error', 'Project not found.');
        }

        return view('admin/projects/edit', [
            'title'      => 'Edit Project',
            'project'    => $project,
            'categories' => ProjectModel::categories(),
            'statuses'   => ProjectModel::statuses(),
        ]);
    }

    /**
     * POST /admin/projects/update/{id}
     */
    public function update(int $id)
    {
        $project = $this->projectModel->find($id);

        if (! $project) {
            return redirect()->to('/admin/projects')->with('error', 'Project not found.');
        }

        if (! $this->validate($this->projectRules())) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title'           => $this->request->getPost('title'),
            'description'     => $this->request->getPost('description'),
            'category'        => $this->request->getPost('category'),
            'required_skills' => $this->request->getPost('required_skills'),
            'location'        => $this->request->getPost('location'),
            'budget'          => $this->request->getPost('budget'),
            'deadline'        => $this->request->getPost('deadline'),
            'status'          => $this->request->getPost('status'),
        ];

        // Already validated above; skip the model's own validation pass
        // (it would otherwise fail on the missing 'created_by' field).
        if (! $this->projectModel->skipValidation(true)->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->projectModel->errors());
        }

        return redirect()->to('/admin/projects')->with('success', 'Project updated successfully.');
    }

    /**
     * GET /admin/projects/delete/{id}
     */
    public function delete(int $id)
    {
        $project = $this->projectModel->find($id);

        if (! $project) {
            return redirect()->to('/admin/projects')->with('error', 'Project not found.');
        }

        $this->projectModel->delete($id);

        return redirect()->to('/admin/projects')->with('success', 'Project deleted successfully.');
    }
}
