<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="vb-page-heading mb-1">Manage Projects</h4>
            <p class="text-muted mb-0">Create, update and monitor all projects listed on VendorBid.</p>
        </div>
        <a href="<?= base_url('admin/projects/create') ?>" class="btn btn-vb-primary">
            <i class="fa-solid fa-plus me-1"></i>Create Project
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= form_open('admin/projects', ['method' => 'get']) ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" name="search" value="<?= esc($search) ?>"
                                   placeholder="Search by title, description or location">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $st) : ?>
                                <option value="<?= esc($st) ?>" <?= $status === $st ? 'selected' : '' ?>><?= esc(ucfirst($st)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= esc($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-vb-primary" title="Apply filters">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>
                </div>
            <?= form_close() ?>

            <?php if ($search !== '' || $status !== '' || $category !== '') : ?>
                <div class="mt-2">
                    <a href="<?= base_url('admin/projects') ?>" class="small text-decoration-none">
                        <i class="fa-solid fa-xmark me-1"></i>Clear filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Budget</th>
                            <th>Deadline</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)) : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i>
                                    No projects found. Try adjusting your search or filters.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($projects as $project) : ?>
                                <?php
                                    $statusClass = match ($project['status']) {
                                        'open'    => 'bg-success-subtle text-success',
                                        'awarded' => 'bg-primary-subtle text-primary',
                                        'closed'  => 'bg-secondary-subtle text-secondary',
                                        default   => 'bg-light text-dark',
                                    };
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= esc($project['title']) ?></div>
                                        <div class="text-muted small text-truncate" style="max-width:280px;">
                                            <?= esc(mb_strimwidth($project['description'], 0, 80, '...')) ?>
                                        </div>
                                    </td>
                                    <td><?= esc($project['category']) ?></td>
                                    <td>₹<?= number_format((float) $project['budget'], 0) ?></td>
                                    <td><?= date('d M Y', strtotime($project['deadline'])) ?></td>
                                    <td><?= esc($project['location']) ?></td>
                                    <td><span class="badge <?= $statusClass ?> text-capitalize"><?= esc($project['status']) ?></span></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/projects/view/' . $project['id']) ?>"
                                           class="btn btn-sm btn-outline-secondary" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/projects/edit/' . $project['id']) ?>"
                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="<?= base_url('admin/projects/delete/' . $project['id']) ?>"
                                           class="btn btn-sm btn-outline-danger vb-confirm-delete" title="Delete"
                                           data-confirm="<?= esc('Are you sure you want to delete \'' . $project['title'] . '\'? This action cannot be undone.', 'attr') ?>">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (! empty($projects)) : ?>
            <div class="card-footer bg-white border-0 py-3">
                <?= $pager->links('projects', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
