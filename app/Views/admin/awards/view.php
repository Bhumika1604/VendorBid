<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="vb-page-heading mb-1"><?= $award ? 'Award Details' : 'Select Winning Bid' ?></h4>
            <p class="text-muted mb-0"><?= esc($project['title']) ?></p>
        </div>
        <a href="<?= base_url('admin/awards') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to Award Management
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
                    <div class="text-muted small">Project Status</div>
                    <div class="fw-semibold text-capitalize"><?= esc($project['status']) ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($award) : ?>

        <!-- ============ ALREADY AWARDED: show the award record ============ -->
        <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-trophy me-2 fs-5"></i>
            This project was awarded to <strong class="mx-1"><?= esc($award['contractor_name']) ?></strong>
            on <?= date('d M Y, h:i A', strtotime($award['created_at'])) ?>.
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted small mb-3">Winning Contractor</h6>
                        <table class="table table-borderless mb-0 vb-profile-table">
                            <tbody>
                                <tr><th scope="row">Name</th><td><?= esc($award['contractor_name']) ?></td></tr>
                                <tr><th scope="row">Company</th><td><?= esc($award['contractor_company']) ?></td></tr>
                                <tr><th scope="row">Email</th><td><?= esc($award['contractor_email']) ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h6 class="text-uppercase text-muted small mb-3">Award Info</h6>
                        <table class="table table-borderless mb-0 vb-profile-table">
                            <tbody>
                                <tr><th scope="row">Awarded Amount</th><td class="fw-semibold text-success">₹<?= number_format((float) $award['awarded_amount'], 2) ?></td></tr>
                                <tr><th scope="row">Awarded By</th><td><?= esc($award['awarded_by_name']) ?></td></tr>
                                <tr><th scope="row">Awarded On</th><td><?= date('d M Y, h:i A', strtotime($award['created_at'])) ?></td></tr>
                                <?php if (! empty($award['remarks'])) : ?>
                                    <tr><th scope="row">Remarks</th><td><?= esc($award['remarks']) ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- ============ ALL BIDS FOR THIS PROJECT (comparison / history) ============ -->
    <h5 class="mb-3"><?= $award ? 'All Bids Received' : 'Compare Bids &amp; Select a Winner' ?></h5>

    <?php if (empty($bids)) : ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fa-solid fa-file-invoice-dollar fa-2x mb-2 d-block"></i>
                No bids have been submitted for this project yet.
            </div>
        </div>
    <?php else : ?>

        <?php if (! $award) : ?>
            <?= form_open('admin/awards/store/' . $project['id']) ?>
        <?php endif; ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <?php if (! $award) : ?><th style="width:40px;"></th><?php endif; ?>
                                <th>Contractor</th>
                                <th>Bid Amount</th>
                                <th>Completion Days</th>
                                <th>Previous Experience</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th class="text-end">Document</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bids as $bid) : ?>
                                <?php
                                    $isLowest = $bid['id'] === $lowestBidId;
                                    $isWinner = $award && (int) $award['bid_id'] === (int) $bid['id'];
                                    $rowClass = $isWinner ? 'table-primary' : ($isLowest && ! $award ? 'table-success' : '');
                                    $statusClass = match ($bid['status']) {
                                        'awarded'     => 'bg-primary-subtle text-primary',
                                        'shortlisted' => 'bg-info-subtle text-info',
                                        'rejected'    => 'bg-danger-subtle text-danger',
                                        default       => 'bg-warning-subtle text-warning',
                                    };
                                ?>
                                <tr class="<?= $rowClass ?>">
                                    <?php if (! $award) : ?>
                                        <td>
                                            <input class="form-check-input" type="radio" name="bid_id" value="<?= (int) $bid['id'] ?>" required>
                                        </td>
                                    <?php endif; ?>
                                    <td>
                                        <div class="fw-semibold">
                                            <?= esc($bid['contractor_name']) ?>
                                            <?php if ($isWinner) : ?><i class="fa-solid fa-trophy text-warning ms-1" title="Winner"></i><?php endif; ?>
                                        </div>
                                        <div class="text-muted small"><?= esc($bid['contractor_company']) ?></div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">₹<?= number_format((float) $bid['bid_amount'], 0) ?></span>
                                        <?php if ($isLowest && ! $award) : ?>
                                            <span class="badge bg-success ms-1"><i class="fa-solid fa-arrow-down me-1"></i>Lowest</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= (int) $bid['estimated_days'] ?> days</td>
                                    <td style="max-width:240px;">
                                        <span class="text-muted small"><?= esc(mb_strimwidth($bid['previous_experience'], 0, 90, '...')) ?></span>
                                    </td>
                                    <td><?= date('d M Y', strtotime($bid['created_at'])) ?></td>
                                    <td><span class="badge <?= $statusClass ?> text-capitalize"><?= esc($bid['status']) ?></span></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('admin/bids/download/' . $bid['id']) ?>" class="btn btn-sm btn-outline-primary" title="Download Document">
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

        <?php if (! $award) : ?>
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Award Remarks <span class="text-muted small fw-normal">(optional)</span></label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="2"
                                  placeholder="Any internal notes about this award decision..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-vb-primary vb-confirm-award"
                            data-confirm="Award this project to the selected contractor? All other bids will be automatically rejected and every contractor will be notified by email. This cannot be undone.">
                        <i class="fa-solid fa-trophy me-1"></i>Award Project
                    </button>
                </div>
            </div>
            <?= form_close() ?>
        <?php endif; ?>

    <?php endif; ?>

<?= $this->endSection() ?>
