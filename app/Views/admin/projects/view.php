<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Project Details</h4>
            <p class="text-muted mb-0">Full information for this project listing.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/projects/edit/' . $project['id']) ?>" class="btn btn-vb-primary">
                <i class="fa-solid fa-pen me-1"></i>Edit
            </a>
            <a href="<?= base_url('admin/projects') ?>" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i>Back
            </a>
        </div>
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
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted small mb-3">Listing Info</h6>
                    <table class="table table-borderless mb-0 vb-profile-table">
                        <tbody>
                            <tr>
                                <th scope="row">Project ID</th>
                                <td>#<?= (int) $project['id'] ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Created On</th>
                                <td><?= date('d M Y, h:i A', strtotime($project['created_at'])) ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Last Updated</th>
                                <td><?= date('d M Y, h:i A', strtotime($project['updated_at'])) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
