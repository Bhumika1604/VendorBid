<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="mb-4">
        <h4 class="vb-page-heading mb-1">Manage Bids</h4>
        <p class="text-muted mb-0">Review, search and compare bids submitted by contractors.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= form_open('admin/bids', ['method' => 'get']) ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" name="search" value="<?= esc($search) ?>"
                                   placeholder="Search by contractor, company or project">
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
                        <label class="form-label small text-muted">Project</label>
                        <select name="project_id" class="form-select">
                            <option value="">All Projects</option>
                            <?php foreach ($projectOptions as $proj) : ?>
                                <option value="<?= (int) $proj['id'] ?>" <?= (string) $projectId === (string) $proj['id'] ? 'selected' : '' ?>>
                                    <?= esc($proj['title']) ?>
                                </option>
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

            <?php if ($search !== '' || $status !== '' || $projectId !== '') : ?>
                <div class="mt-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="<?= base_url('admin/bids') ?>" class="small text-decoration-none">
                        <i class="fa-solid fa-xmark me-1"></i>Clear filters
                    </a>
                    <?php if ($projectId !== '') : ?>
                        <a href="<?= base_url('admin/bids/compare/' . $projectId) ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-scale-balanced me-1"></i>Compare Bids for This Project
                        </a>
                    <?php endif; ?>
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
                            <th>Contractor</th>
                            <th>Project</th>
                            <th>Bid Amount</th>
                            <th>Est. Days</th>
                            <th>Status</th>
                            <th>Submitted On</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bids)) : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-file-invoice-dollar fa-2x mb-2 d-block"></i>
                                    No bids found. Try adjusting your search or filters.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($bids as $bid) : ?>
                                <?php
                                    $statusClass = match ($bid['status']) {
                                        'awarded'     => 'bg-primary-subtle text-primary',
                                        'shortlisted' => 'bg-info-subtle text-info',
                                        'rejected'    => 'bg-danger-subtle text-danger',
                                        default       => 'bg-warning-subtle text-warning',
                                    };
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><?= esc($bid['contractor_name']) ?></div>
                                        <div class="text-muted small"><?= esc($bid['contractor_company']) ?></div>
                                    </td>
                                    <td><?= esc($bid['project_title']) ?></td>
                                    <td>₹<?= number_format((float) $bid['bid_amount'], 0) ?></td>
                                    <td><?= (int) $bid['estimated_days'] ?> days</td>
                                    <td><span class="badge <?= $statusClass ?> text-capitalize"><?= esc($bid['status']) ?></span></td>
                                    <td><?= date('d M Y', strtotime($bid['created_at'])) ?></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/bids/view/' . $bid['id']) ?>"
                                           class="btn btn-sm btn-outline-secondary" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/bids/compare/' . $bid['project_id']) ?>"
                                           class="btn btn-sm btn-outline-primary" title="Compare all bids for this project">
                                            <i class="fa-solid fa-scale-balanced"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (! empty($bids)) : ?>
            <div class="card-footer bg-white border-0 py-3">
                <?= $pager->links('bids', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
