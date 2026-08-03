<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="vb-page-heading mb-1">Compare Bids</h4>
            <p class="text-muted mb-0">All bids submitted for "<?= esc($project['title']) ?>".</p>
        </div>
        <a href="<?= base_url('admin/bids') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to All Bids
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="text-muted small">Project</div>
                    <div class="fw-semibold"><?= esc($project['title']) ?></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="text-muted small">Category</div>
                    <div class="fw-semibold"><?= esc($project['category']) ?></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="text-muted small">Budget</div>
                    <div class="fw-semibold">₹<?= number_format((float) $project['budget'], 0) ?></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="text-muted small">Total Bids Received</div>
                    <div class="fw-semibold"><?= count($bids) ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($bids)) : ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fa-solid fa-file-invoice-dollar fa-2x mb-2 d-block"></i>
                No bids have been submitted for this project yet.
            </div>
        </div>
    <?php else : ?>

        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            The lowest bid amount is highlighted in green below.
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Contractor Name</th>
                                <th>Project Name</th>
                                <th>Bid Amount</th>
                                <th>Completion Days</th>
                                <th>Previous Experience</th>
                                <th>Submitted Date</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bids as $bid) : ?>
                                <?php
                                    $isLowest = $bid['id'] === $lowestBidId;
                                    $rowClass = $isLowest ? 'table-success' : '';
                                    $statusClass = match ($bid['status']) {
                                        'awarded'     => 'bg-primary-subtle text-primary',
                                        'shortlisted' => 'bg-info-subtle text-info',
                                        'rejected'    => 'bg-danger-subtle text-danger',
                                        default       => 'bg-warning-subtle text-warning',
                                    };
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <td>
                                        <div class="fw-semibold"><?= esc($bid['contractor_name']) ?></div>
                                        <div class="text-muted small"><?= esc($bid['contractor_company']) ?></div>
                                    </td>
                                    <td><?= esc($project['title']) ?></td>
                                    <td>
                                        <span class="fw-semibold">₹<?= number_format((float) $bid['bid_amount'], 0) ?></span>
                                        <?php if ($isLowest) : ?>
                                            <span class="badge bg-success ms-1"><i class="fa-solid fa-trophy me-1"></i>Lowest Bid</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= (int) $bid['estimated_days'] ?> days</td>
                                    <td style="max-width: 260px;">
                                        <span class="text-muted small"><?= esc(mb_strimwidth($bid['previous_experience'], 0, 90, '...')) ?></span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($bid['created_at'])) ?></td>
                                    <td><span class="badge <?= $statusClass ?> text-capitalize"><?= esc($bid['status']) ?></span></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/bids/view/' . $bid['id']) ?>"
                                           class="btn btn-sm btn-outline-secondary" title="View Full Bid">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/bids/download/' . $bid['id']) ?>"
                                           class="btn btn-sm btn-outline-primary" title="Download Document">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>

<?= $this->endSection() ?>
