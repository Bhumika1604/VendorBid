<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

    <div class="vb-auth-card-header">
        <div class="vb-auth-icon vb-auth-icon-contractor">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <h2>Contractor Registration</h2>
        <p>Create your contractor account to start bidding on projects.</p>
    </div>

    <?= form_open('register') ?>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name"
                       value="<?= esc(old('name')) ?>" placeholder="John Doe" required autofocus>
            </div>
            <div class="col-md-6 mb-3">
                <label for="company_name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name"
                       value="<?= esc(old('company_name')) ?>" placeholder="BuildRight Constructions" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= esc(old('email')) ?>" placeholder="you@company.com" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone" name="phone"
                       value="<?= esc(old('phone')) ?>" placeholder="9876543210" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Business Address</label>
            <textarea class="form-control" id="address" name="address" rows="2" required placeholder="Street, City, State"><?= esc(old('address')) ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    <button class="btn btn-outline-secondary vb-toggle-password" type="button" data-target="password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
                <div class="form-text">Minimum 6 characters.</div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
                    <button class="btn btn-outline-secondary vb-toggle-password" type="button" data-target="confirm_password">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-vb-primary w-100 mt-2">
            <i class="fa-solid fa-user-plus me-1"></i> Create Account
        </button>

    <?= form_close() ?>

    <div class="vb-auth-links">
        <a href="<?= base_url('login') ?>"><i class="fa-solid fa-arrow-left me-1"></i>Already have an account? Login</a>
    </div>

<?= $this->endSection() ?>
