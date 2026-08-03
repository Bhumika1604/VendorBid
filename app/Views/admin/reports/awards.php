<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="vb-page-heading mb-1">Awards Report</h4>
            <p class="text-muted mb-0">Full history of every project awarded, with export options.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/reports/awards?' . http_build_query(['search' => $search, 'export' => 'pdf'])) ?>" class="btn btn-outline-danger">
                <i class="fa-solid fa-file-pdf me-1"></i>PDF
            </a>
            <a href="<?= base_url('admin/reports/awards?' . http_build_query(['search' => $search, 'export' => 'excel'])) ?>" class="btn btn-outline-success">
                <i class="fa-solid fa-file-excel me-1"></i>Excel
            </a>
        </div>
    </div>

    <ul class="nav nav-pills mb-4">
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/projects') ?>">Projects</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/contractors') ?>">Contractors</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= base_url('admin/reports/bids') ?>">Bids</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?= base_url('admin/reports/awards') ?>">Awards</a></li>
    </ul>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= form_open('admin/reports/awards', ['method' => 'get']) ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-11">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Search by project, contractor or company">
                        </div>
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
                            <th>Project</th><th>Awarded Contractor</th><th>Company</th><th>Awarded Amount</th><th>Awarded On</th><th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($awards)) : ?>
                            <tr><td colspan="6" class="text-center text-muted py-5">No awards found.</td></tr>
                        <?php else : ?>
                            <?php foreach ($awards as $a) : ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($a['project_title']) ?></td>
                                    <td><?= esc($a['contractor_name']) ?></td>
                                    <td><?= esc($a['contractor_company']) ?></td>
                                    <td>₹<?= number_format((float) $a['awarded_amount'], 0) ?></td>
                                    <td><?= date('d M Y', strtotime($a['created_at'])) ?></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/awards/view/' . $a['project_id']) ?>" class="btn btn-sm btn-outline-secondary" title="View Award">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (! empty($awards)) : ?>
            <div class="card-footer bg-white border-0 py-3">
                <?= $pager->links('reports', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
