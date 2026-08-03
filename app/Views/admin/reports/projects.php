<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="vb-page-heading mb-1">Projects Report</h4>
            <p class="text-muted mb-0">Every project listed on VendorBid, with export options.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/reports/projects?' . http_build_query(['search' => $search, 'status' => $status, 'export' => 'pdf'])) ?>" class="btn btn-outline-danger">
                <i class="fa-solid fa-file-pdf me-1"></i>PDF
            </a>
            <a href="<?= base_url('admin/reports/projects?' . http_build_query(['search' => $search, 'status' => $status, 'export' => 'excel'])) ?>" class="btn btn-outline-success">
                <i class="fa-solid fa-file-excel me-1"></i>Excel
            </a>
        </div>
    </div>

    <ul class="nav nav-pills mb-4">
        <li class="nav-item"><a class="nav-link active" href="<?= base_url('admin/reports/projects') ?>">Projects</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/contractors') ?>">Contractors</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/bids') ?>">Bids</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/awards') ?>">Awards</a></li>
    </ul>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= form_open('admin/reports/projects', ['method' => 'get']) ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Search by title, description or location">
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
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-vb-primary"><i class="fa-solid fa-filter"></i></button>
                    </div>
                </div>
            <?= form_close() ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th><th>Category</th><th>Budget</th><th>Location</th><th>Deadline</th><th>Status</th><th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)) : ?>
                            <tr><td colspan="7" class="text-center text-muted py-5">No projects found.</td></tr>
                        <?php else : ?>
                            <?php foreach ($projects as $p) : ?>
                                <?php
                                    $statusClass = match ($p['status']) {
                                        'open'    => 'bg-success-subtle text-success',
                                        'awarded' => 'bg-primary-subtle text-primary',
                                        'closed'  => 'bg-secondary-subtle text-secondary',
                                        default   => 'bg-light text-dark',
                                    };
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($p['title']) ?></td>
                                    <td><?= esc($p['category']) ?></td>
                                    <td>₹<?= number_format((float) $p['budget'], 0) ?></td>
                                    <td><?= esc($p['location']) ?></td>
                                    <td><?= date('d M Y', strtotime($p['deadline'])) ?></td>
                                    <td><span class="badge <?= $statusClass ?> text-capitalize"><?= esc($p['status']) ?></span></td>
                                    <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (! empty($projects)) : ?>
            <div class="card-footer bg-white border-0 py-3">
                <?= $pager->links('reports', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
