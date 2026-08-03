<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Bid Details</h4>
            <p class="text-muted mb-0">Submitted by <?= esc($bid['contractor_name']) ?> for "<?= esc($bid['project_title']) ?>".</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/bids/compare/' . $bid['project_id']) ?>" class="btn btn-vb-primary">
                <i class="fa-solid fa-scale-balanced me-1"></i>Compare Bids
            </a>
            <a href="<?= base_url('admin/bids') ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="mb-0"><i class="fa-solid fa-diagram-project text-primary me-2"></i><?= esc($bid['project_title']) ?></h5>
                        <?php
                            $statusClass = match ($bid['status']) {
                                'awarded'     => 'bg-primary-subtle text-primary',
                                'shortlisted' => 'bg-info-subtle text-info',
                                'rejected'    => 'bg-danger-subtle text-danger',
                                default       => 'bg-warning-subtle text-warning',
                            };
                        ?>
                        <span class="badge <?= $statusClass ?> text-capitalize px-3 py-2"><?= esc($bid['status']) ?></span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="text-muted small">Bid Amount</div>
                            <div class="fw-semibold fs-5"><i class="fa-solid fa-indian-rupee-sign text-primary me-1"></i><?= number_format((float) $bid['bid_amount'], 2) ?></div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Estimated Completion</div>
                            <div class="fw-semibold fs-5"><?= (int) $bid['estimated_days'] ?> days</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Project Budget</div>
                            <div class="fw-semibold fs-5">₹<?= number_format((float) $bid['project_budget'], 0) ?></div>
                        </div>
                    </div>

                    <h6 class="mb-2">Proposal Description</h6>
                    <p class="text-muted mb-4"><?= nl2br(esc($bid['proposal_description'])) ?></p>

                    <h6 class="mb-2">Previous Experience</h6>
                    <p class="text-muted mb-0"><?= nl2br(esc($bid['previous_experience'])) ?></p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted small mb-3">Contractor</h6>
                    <table class="table table-borderless mb-0 vb-profile-table">
                        <tbody>
                            <tr>
                                <th scope="row">Name</th>
                                <td><?= esc($bid['contractor_name']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Company</th>
                                <td><?= esc($bid['contractor_company']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td><?= esc($bid['contractor_email']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Phone</th>
                                <td><?= esc($bid['contractor_phone']) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted small mb-3">Bid Info</h6>
                    <table class="table table-borderless mb-0 vb-profile-table">
                        <tbody>
                            <tr>
                                <th scope="row">Bid ID</th>
                                <td>#<?= (int) $bid['id'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Project Status</th>
                                <td class="text-capitalize"><?= esc($bid['project_status']) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Submitted On</th>
                                <td><?= date('d M Y, h:i A', strtotime($bid['created_at'])) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <i class="fa-solid fa-file-arrow-down fa-2x text-primary mb-3"></i>
                    <h6 class="mb-2">Bid Document</h6>
                    <p class="text-muted small mb-3">The proposal document uploaded with this bid.</p>
                    <a href="<?= base_url('admin/bids/download/' . $bid['id']) ?>" class="btn btn-vb-primary w-100">
                        <i class="fa-solid fa-download me-1"></i>Download Document
                    </a>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
