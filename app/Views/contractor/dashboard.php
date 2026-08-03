<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Welcome back, <?= esc(session()->get('name')) ?> 👋</h4>
            <p class="text-muted mb-0">Here's a quick look at your VendorBid account.</p>
        </div>
        <div class="text-muted small mt-2 mt-md-0">
            <i class="fa-regular fa-calendar me-1"></i><?= date('l, d F Y') ?>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-sm-6 col-xl-3">
            <a href="<?= base_url('contractor/projects') ?>" class="text-decoration-none">
                <div class="card vb-stat-card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="vb-stat-icon bg-primary-subtle text-primary">
                            <i class="fa-solid fa-diagram-project"></i>
                        </div>
                        <div class="ms-3">
                            <div class="vb-stat-value"><?= (int) $totalOpen ?></div>
                            <div class="vb-stat-label">Open Projects Available</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="vb-stat-icon bg-info-subtle text-info">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div class="ms-3">
                        <div class="vb-stat-value-sm"><?= esc($profile['company_name'] ?? '—') ?></div>
                        <div class="vb-stat-label">Company Name</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="vb-stat-icon bg-success-subtle text-success">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div class="ms-3">
                        <div class="vb-stat-value-sm text-capitalize"><?= esc($profile['status'] ?? '—') ?></div>
                        <div class="vb-stat-label">Account Status</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="vb-stat-icon bg-warning-subtle text-warning">
                        <i class="fa-regular fa-calendar-check"></i>
                    </div>
                    <div class="ms-3">
                        <div class="vb-stat-value-sm">
                            <?= isset($profile['created_at']) ? date('d M Y', strtotime($profile['created_at'])) : '—' ?>
                        </div>
                        <div class="vb-stat-label">Registered On</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="fa-solid fa-id-card text-primary me-2"></i>Profile Details</h5>
                        <a href="<?= base_url('contractor/profile/edit') ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-pen me-1"></i>Edit
                        </a>
                    </div>
                    <table class="table table-borderless mb-0 vb-profile-table">
                        <tbody>
                            <tr>
                                <th scope="row">Full Name</th>
                                <td><?= esc($profile['name'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td><?= esc($profile['email'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Phone</th>
                                <td><?= esc($profile['phone'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Address</th>
                                <td><?= esc($profile['address'] ?? '—') ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>What's Next</h5>
                    <p class="text-muted">
                        Browse the current list of open projects and review full details before bidding opens.
                        Submitting bids will be enabled once the Bid module goes live in Part 3.
                    </p>
                    <a href="<?= base_url('contractor/projects') ?>" class="btn btn-vb-primary">
                        <i class="fa-solid fa-magnifying-glass me-1"></i>Browse Open Projects
                    </a>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
