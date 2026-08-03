<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Submit a Bid</h4>
            <p class="text-muted mb-0">Choose an open project and submit your proposal.</p>
        </div>
        <a href="<?= base_url('contractor/bids') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to My Bids
        </a>
    </div>

    <?php if (empty($projects)) : ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="fa-solid fa-circle-info fa-2x mb-2 d-block"></i>
                There are no open projects currently available for you to bid on
                (either none are open, or you've already bid on all of them).
                <div class="mt-3">
                    <a href="<?= base_url('contractor/projects') ?>" class="btn btn-vb-primary">
                        <i class="fa-solid fa-diagram-project me-1"></i>Browse Open Projects
                    </a>
                </div>
            </div>
        </div>
    <?php else : ?>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <?= form_open_multipart('contractor/bids/store') ?>

                    <div class="mb-3">
                        <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                        <select class="form-select" id="project_id" name="project_id" required>
                            <option value="" disabled <?= old('project_id', (string) $preselectedProjectId) === '0' ? 'selected' : '' ?>>Select a project</option>
                            <?php foreach ($projects as $proj) : ?>
                                <?php $selected = (string) old('project_id', (string) $preselectedProjectId) === (string) $proj['id']; ?>
                                <option value="<?= (int) $proj['id'] ?>" <?= $selected ? 'selected' : '' ?>>
                                    <?= esc($proj['title']) ?> — ₹<?= number_format((float) $proj['budget'], 0) ?> (<?= esc($proj['location']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Only open projects you haven't already bid on are shown here.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bid_amount" class="form-label">Bid Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" class="form-control" id="bid_amount" name="bid_amount"
                                   value="<?= esc(old('bid_amount')) ?>" placeholder="450000" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estimated_days" class="form-label">Estimated Completion Days <span class="text-danger">*</span></label>
                            <input type="number" min="1" step="1" class="form-control" id="estimated_days" name="estimated_days"
                                   value="<?= esc(old('estimated_days')) ?>" placeholder="45" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="proposal_description" class="form-label">Proposal Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="proposal_description" name="proposal_description" rows="4" required
                                  placeholder="Explain your approach, methodology and what makes your proposal a strong fit..."><?= esc(old('proposal_description')) ?></textarea>
                        <div class="form-text">Minimum 20 characters.</div>
                    </div>

                    <div class="mb-3">
                        <label for="previous_experience" class="form-label">Previous Experience <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="previous_experience" name="previous_experience" rows="3" required
                                  placeholder="Summarize relevant past projects, certifications or achievements..."><?= esc(old('previous_experience')) ?></textarea>
                        <div class="form-text">Minimum 10 characters.</div>
                    </div>

                    <div class="mb-3">
                        <label for="document" class="form-label">Upload Bid Document <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="document" name="document"
                               accept=".pdf,.doc,.docx" required>
                        <div class="form-text" id="vbFileHelp">
                            Accepted formats: PDF, DOC, DOCX. Maximum size: <?= round($maxUploadKb / 1024) ?> MB.
                        </div>
                        <div class="invalid-feedback d-block d-none" id="vbFileError"></div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-vb-primary">
                            <i class="fa-solid fa-paper-plane me-1"></i>Submit Bid
                        </button>
                        <a href="<?= base_url('contractor/bids') ?>" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                <?= form_close() ?>

            </div>
        </div>

    <?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    (function () {
        var allowedExtensions = <?= json_encode($allowedExtensions) ?>;
        var maxSizeBytes = <?= (int) $maxUploadKb ?> * 1024;
        var fileInput = document.getElementById('document');
        var errorBox = document.getElementById('vbFileError');

        if (fileInput) {
            fileInput.addEventListener('change', function () {
                errorBox.classList.add('d-none');
                errorBox.textContent = '';
                fileInput.classList.remove('is-invalid');

                if (!fileInput.files || !fileInput.files.length) {
                    return;
                }

                var file = fileInput.files[0];
                var parts = file.name.split('.');
                var ext = parts.length > 1 ? parts.pop().toLowerCase() : '';

                if (allowedExtensions.indexOf(ext) === -1) {
                    errorBox.textContent = 'Only PDF, DOC and DOCX files are allowed.';
                    errorBox.classList.remove('d-none');
                    fileInput.classList.add('is-invalid');
                    fileInput.value = '';
                    return;
                }

                if (file.size > maxSizeBytes) {
                    errorBox.textContent = 'File is too large. Maximum allowed size is ' + Math.round(maxSizeBytes / 1024 / 1024) + ' MB.';
                    errorBox.classList.remove('d-none');
                    fileInput.classList.add('is-invalid');
                    fileInput.value = '';
                }
            });
        }
    })();
</script>
<?= $this->endSection() ?>
