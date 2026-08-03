<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">Edit Profile</h4>
            <p class="text-muted mb-0">Keep your contact and business information up to date.</p>
        </div>
        <a href="<?= base_url('contractor/profile') ?>" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i>Back to Profile
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <?= form_open('contractor/profile/update') ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="<?= esc(old('name', $profile['name'])) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="company_name" name="company_name"
                               value="<?= esc(old('company_name', $profile['company_name'])) ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= esc(old('email', $profile['email'])) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone"
                               value="<?= esc(old('phone', $profile['phone'])) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Business Address <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="address" name="address" rows="2" required><?= esc(old('address', $profile['address'])) ?></textarea>
                </div>

                <hr class="my-4">
                <h6 class="mb-3">Change Password <span class="text-muted small fw-normal">(optional)</span></h6>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" minlength="6"
                                   placeholder="Leave blank to keep current password">
                            <button class="btn btn-outline-secondary vb-toggle-password" type="button" data-target="password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="6">
                            <button class="btn btn-outline-secondary vb-toggle-password" type="button" data-target="confirm_password">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-2">
                    <button type="submit" class="btn btn-vb-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i>Save Changes
                    </button>
                    <a href="<?= base_url('contractor/profile') ?>" class="btn btn-outline-secondary">Cancel</a>
                </div>

            <?= form_close() ?>

        </div>
    </div>

<?= $this->endSection() ?>
