<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="mb-4">
        <h4 class="vb-page-heading mb-1">Available Projects</h4>
        <p class="text-muted mb-0">Browse currently open projects that match your expertise.</p>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <?= form_open('contractor/projects', ['method' => 'get']) ?>
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label small text-muted">Search</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" class="form-control" name="search" value="<?= esc($search) ?>"
                                   placeholder="Search by title, description or location">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= esc($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-vb-primary" title="Search">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>
                </div>
            <?= form_close() ?>

            <?php if ($search !== '' || $category !== '') : ?>
                <div class="mt-2">
                    <a href="<?= base_url('contractor/projects') ?>" class="small text-decoration-none">
                        <i class="fa-solid fa-xmark me-1"></i>Clear filters
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (empty($projects)) : ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fa-solid fa-folder-open fa-2x mb-2 d-block"></i>
                No open projects match your search right now. Please check back later.
            </div>
        </div>
    <?php else : ?>
        <div class="row g-4">
            <?php foreach ($projects as $project) : ?>
                <div class="col-md-6 col-xl-4">
                    <div class="card vb-stat-card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-success-subtle text-success text-capitalize">Open</span>
                                <span class="text-muted small"><i class="fa-solid fa-tags me-1"></i><?= esc($project['category']) ?></span>
                            </div>
                            <h5 class="mb-2"><?= esc($project['title']) ?></h5>
                            <p class="text-muted small mb-3 flex-grow-1">
                                <?= esc(mb_strimwidth($project['description'], 0, 110, '...')) ?>
                            </p>
                            <ul class="list-unstyled small text-muted mb-3">
                                <li class="mb-1"><i class="fa-solid fa-location-dot text-primary me-1"></i><?= esc($project['location']) ?></li>
                                <li class="mb-1"><i class="fa-solid fa-indian-rupee-sign text-primary me-1"></i><?= number_format((float) $project['budget'], 0) ?></li>
                                <li><i class="fa-regular fa-calendar text-primary me-1"></i>Deadline: <?= date('d M Y', strtotime($project['deadline'])) ?></li>
                            </ul>
                            <a href="<?= base_url('contractor/projects/view/' . $project['id']) ?>" class="btn btn-vb-primary mt-auto">
                                <i class="fa-solid fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-4">
            <?= $pager->links('projects', 'default_full') ?>
        </div>
    <?php endif; ?>

<?= $this->endSection() ?>
