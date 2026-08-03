<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Project Details</h4>
            <p class="text-muted mb-0">Review the full brief before deciding to bid.</p>
        </div>
        <a href="<?= base_url('contractor/projects') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to Projects
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h3 class="mb-0"><?= esc($project['title']) ?></h3>
                        <?php
                            $statusClass = match ($project['status']) {
                                'open'    => 'bg-success-subtle text-success',
                                'awarded' => 'bg-primary-subtle text-primary',
                                'closed'  => 'bg-secondary-subtle text-secondary',
                                default   => 'bg-light text-dark',
                            };
                        ?>
                        <span class="badge <?= $statusClass ?> text-capitalize px-3 py-2"><?= esc($project['status']) ?></span>
                    </div>

                    <p class="text-muted mb-4"><?= nl2br(esc($project['description'])) ?></p>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="text-muted small">Category</div>
                            <div class="fw-semibold"><i class="fa-solid fa-tags text-primary me-1"></i><?= esc($project['category']) ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Location</div>
                            <div class="fw-semibold"><i class="fa-solid fa-location-dot text-primary me-1"></i><?= esc($project['location']) ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Budget</div>
                            <div class="fw-semibold"><i class="fa-solid fa-indian-rupee-sign text-primary me-1"></i><?= number_format((float) $project['budget'], 2) ?></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-muted small">Deadline</div>
                            <div class="fw-semibold"><i class="fa-regular fa-calendar text-primary me-1"></i><?= date('d M Y', strtotime($project['deadline'])) ?></div>
                        </div>
                        <div class="col-12">
                            <div class="text-muted small">Required Skills</div>
                            <div class="mt-1">
                                <?php foreach (array_filter(array_map('trim', explode(',', (string) $project['required_skills']))) as $skill) : ?>
                                    <span class="badge bg-light text-dark border me-1 mb-1"><?= esc($skill) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <?php if ($project['status'] === 'open') : ?>
                        <i class="fa-solid fa-file-invoice-dollar fa-2x text-muted mb-3"></i>
                        <h6 class="mb-2">Bidding Not Yet Open</h6>
                        <p class="text-muted small mb-3">
                            The Bid module will be enabled in Part 3, after which you'll be able to submit your
                            proposal for this project directly from here.
                        </p>
                        <button class="btn btn-vb-primary w-100" disabled>
                            <i class="fa-solid fa-lock me-1"></i>Place Bid (Coming Soon)
                        </button>
                    <?php else : ?>
                        <i class="fa-solid fa-circle-info fa-2x text-muted mb-3"></i>
                        <h6 class="mb-2">Project Not Open</h6>
                        <p class="text-muted small mb-0">
                            This project is currently <strong class="text-capitalize"><?= esc($project['status']) ?></strong> and is not accepting new bids.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
