<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h4 class="vb-page-heading mb-1">My Bids</h4>
            <p class="text-muted mb-0">Track every bid you've submitted and its current status.</p>
        </div>
        <a href="<?= base_url('contractor/bids/create') ?>" class="btn btn-vb-primary">
            <i class="fa-solid fa-paper-plane me-1"></i>Submit New Bid
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Project</th>
                            <th>Bid Amount</th>
                            <th>Est. Days</th>
                            <th>Project Status</th>
                            <th>Bid Status</th>
                            <th>Submitted On</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bids)) : ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-file-invoice-dollar fa-2x mb-2 d-block"></i>
                                    You haven't submitted any bids yet.
                                    <div class="mt-2">
                                        <a href="<?= base_url('contractor/bids/create') ?>" class="btn btn-sm btn-vb-primary">
                                            <i class="fa-solid fa-paper-plane me-1"></i>Submit Your First Bid
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($bids as $bid) : ?>
                                <?php
                                    $bidStatusClass = match ($bid['status']) {
                                        'awarded'     => 'bg-primary-subtle text-primary',
                                        'shortlisted' => 'bg-info-subtle text-info',
                                        'rejected'    => 'bg-danger-subtle text-danger',
                                        default       => 'bg-warning-subtle text-warning',
                                    };
                                    $projectStatusClass = match ($bid['project_status']) {
                                        'open'    => 'bg-success-subtle text-success',
                                        'awarded' => 'bg-primary-subtle text-primary',
                                        'closed'  => 'bg-secondary-subtle text-secondary',
                                        default   => 'bg-light text-dark',
                                    };
                                ?>
                                <tr>
                                    <td class="fw-semibold"><?= esc($bid['project_title']) ?></td>
                                    <td>₹<?= number_format((float) $bid['bid_amount'], 0) ?></td>
                                    <td><?= (int) $bid['estimated_days'] ?> days</td>
                                    <td><span class="badge <?= $projectStatusClass ?> text-capitalize"><?= esc($bid['project_status']) ?></span></td>
                                    <td><span class="badge <?= $bidStatusClass ?> text-capitalize"><?= esc($bid['status']) ?></span></td>
                                    <td><?= date('d M Y', strtotime($bid['created_at'])) ?></td>
                                    <td class="text-end">
                                        <a href="<?= base_url('contractor/bids/view/' . $bid['id']) ?>"
                                           class="btn btn-sm btn-outline-secondary" title="View">
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
        <?php if (! empty($bids)) : ?>
            <div class="card-footer bg-white border-0 py-3">
                <?= $pager->links('bids', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
