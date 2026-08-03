<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="vb-page-heading mb-1">Contractors Report</h4>
            <p class="text-muted mb-0">Every registered contractor account, with export options.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/reports/contractors?' . http_build_query(['search' => $search, 'status' => $status, 'export' => 'pdf'])) ?>" class="btn btn-outline-danger">
                <i class="fa-solid fa-file-pdf me-1"></i>PDF
            </a>
            <a href="<?= base_url('admin/reports/contractors?' . http_build_query(['search' => $search, 'status' => $status, 'export' => 'excel'])) ?>" class="btn btn-outline-success">
                <i class="fa-solid fa-file-excel me-1"></i>Excel
            </a>
        </div>
    </div>

    <ul class="nav nav-pills mb-4">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/projects') ?>">Projects</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?= base_url('admin/reports/contractors') ?>">Contractors</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/bids') ?>">Bids</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/awards') ?>">Awards</a></li>
    </ul>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= form_open('admin/reports/contractors', ['method' => 'get']) ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Search by name, email or company">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
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
                            <th>Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Status</th><th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contractors)) : ?>
                            <tr><td colspan="6" class="text-center text-muted py-5">No contractors found.</td></tr>
                        <?php else : ?>
                            <?php foreach ($contractors as $c) : ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($c['name']) ?></td>
                                    <td><?= esc($c['company_name']) ?></td>
                                    <td><?= esc($c['email']) ?></td>
                                    <td><?= esc($c['phone']) ?></td>
                                    <td>
                                        <span class="badge <?= $c['status'] === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?> text-capitalize">
                                            <?= esc($c['status']) ?>
                                        </span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($c['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (! empty($contractors)) : ?>
            <div class="card-footer bg-white border-0 py-3">
                <?= $pager->links('reports', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
