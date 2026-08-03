<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Welcome back, <?= esc(session()->get('name')) ?> 👋</h4>
            <p class="text-muted mb-0">Here's what's happening across VendorBid today.</p>
        </div>
        <div class="text-muted small mt-2 mt-md-0">
            <i class="fa-regular fa-calendar me-1"></i><?= date('l, d F Y') ?>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-sm-6 col-xl-3">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="vb-stat-icon bg-primary-subtle text-primary">
                        <i class="fa-solid fa-diagram-project"></i>
                    </div>
                    <div class="ms-3">
                        <div class="vb-stat-value"><?= (int) $totalProjects ?></div>
                        <div class="vb-stat-label">Total Projects</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="vb-stat-icon bg-info-subtle text-info">
                        <i class="fa-solid fa-helmet-safety"></i>
                    </div>
                    <div class="ms-3">
                        <div class="vb-stat-value"><?= (int) $totalContractors ?></div>
                        <div class="vb-stat-label">Total Contractors</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="vb-stat-icon bg-warning-subtle text-warning">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div class="ms-3">
                        <div class="vb-stat-value"><?= (int) $totalBids ?></div>
                        <div class="vb-stat-label">Total Bids</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="vb-stat-icon bg-success-subtle text-success">
                        <i class="fa-solid fa-trophy"></i>
                    </div>
                    <div class="ms-3">
                        <div class="vb-stat-value"><?= (int) $totalAwarded ?></div>
                        <div class="vb-stat-label">Awarded Projects</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-1">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fa-solid fa-circle-info text-primary me-2"></i>Getting Started</h5>
                    <p class="text-muted mb-0">
                        Project management, bid review and award workflows will be enabled in the upcoming parts of
                        VendorBid. This dashboard already reflects live counts from the database, so figures will
                        update automatically as those modules go live.
                    </p>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
