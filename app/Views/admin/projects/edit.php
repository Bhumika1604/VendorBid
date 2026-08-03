<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Edit Project</h4>
            <p class="text-muted mb-0">Update the details for "<?= esc($project['title']) ?>".</p>
        </div>
        <a href="<?= base_url('admin/projects') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to Projects
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <?= form_open('admin/projects/update/' . $project['id']) ?>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title"
                               value="<?= esc(old('title', $project['title'])) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category" required>
                            <?php $currentCategory = old('category', $project['category']); ?>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= esc($cat) ?>" <?= $currentCategory === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?= esc(old('description', $project['description'])) ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="required_skills" class="form-label">Required Skills <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="required_skills" name="required_skills"
                               value="<?= esc(old('required_skills', $project['required_skills'])) ?>" required>
                        <div class="form-text">Separate multiple skills with commas.</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="location" name="location"
                               value="<?= esc(old('location', $project['location'])) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="budget" class="form-label">Budget (₹) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control" id="budget" name="budget"
                               value="<?= esc(old('budget', $project['budget'])) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="deadline" class="form-label">Deadline <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="deadline" name="deadline"
                               value="<?= esc(old('deadline', $project['deadline'])) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <?php $currentStatus = old('status', $project['status']); ?>
                        <select class="form-select" id="status" name="status" required>
                            <?php foreach ($statuses as $st) : ?>
                                <option value="<?= esc($st) ?>" <?= $currentStatus === $st ? 'selected' : '' ?>><?= esc(ucfirst($st)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-vb-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Update Project
                    </button>
                    <a href="<?= base_url('admin/projects') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>

            <?= form_close() ?>

        </div>
    </div>

<?= $this->endSection() ?>
