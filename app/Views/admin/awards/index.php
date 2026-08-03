<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="mb-4">
        <h4 class="vb-page-heading mb-1">Award Management</h4>
        <p class="text-muted mb-0">Select winning bids, award projects, and review award history.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= form_open('admin/awards', ['method' => 'get']) ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-10">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" name="search" value="<?= esc($search) ?>"
                                   placeholder="Search by project title">
                        </div>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-vb-primary">
                            <i class="fa-solid fa-magnifying-glass me-1"></i>Search
                        </button>
                    </div>
                </div>
            <?= form_close() ?>
            <?php if ($search !== '') : ?>
                <div class="mt-2">
                    <a href="<?= base_url('admin/awards') ?>" class="small text-decoration-none">
                        <i class="fa-solid fa-xmark me-1"></i>Clear search
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
                            <th>Project</th>
                            <th>Category</th>
                            <th>Budget</th>
                            <th>Bids Received</th>
                            <th>Award Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)) : ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-trophy fa-2x mb-2 d-block"></i>
                                    No projects with bids found yet.
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($projects as $project) : ?>
                                <?php $isAwarded = (int) $project['is_awarded'] > 0; ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($project['title']) ?></td>
                                    <td><?= esc($project['category']) ?></td>
                                    <td>₹<?= number_format((float) $project['budget'], 0) ?></td>
                                    <td><span class="badge bg-info-subtle text-info"><?= (int) $project['bid_count'] ?> bids</span></td>
                                    <td>
                                        <?php if ($isAwarded) : ?>
                                            <span class="badge bg-primary-subtle text-primary"><i class="fa-solid fa-trophy me-1"></i>Awarded</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning-subtle text-warning">Pending Award</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/awards/view/' . $project['id']) ?>"
                                           class="btn btn-sm <?= $isAwarded ? 'btn-outline-secondary' : 'btn-vb-primary' ?>">
                                            <?php if ($isAwarded) : ?>
                                                <i class="fa-solid fa-eye me-1"></i>View Award
                                            <?php else : ?>
                                                <i class="fa-solid fa-scale-balanced me-1"></i>Select Winner
                                            <?php endif; ?>
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
                <?= $pager->links('awards', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
