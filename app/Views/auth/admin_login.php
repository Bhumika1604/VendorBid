<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

    <div class="vb-auth-card-header">
        <div class="vb-auth-icon vb-auth-icon-admin">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <h2>Admin Login</h2>
        <p>Sign in to manage VendorBid projects, contractors and bids.</p>
    </div>

    <?= form_open('admin/login') ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= esc(old('email')) ?>" placeholder="admin@vendorbid.com" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" class="form-control" id="password" name="password"
                       placeholder="Enter your password" required>
                <button class="btn btn-outline-secondary vb-toggle-password" type="button" data-target="password">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-vb-primary w-100">
            <i class="fa-solid fa-right-to-bracket me-1"></i> Login as Admin
        </button>

    <?= form_close() ?>

    <div class="vb-auth-links">
        <a href="<?= base_url('login') ?>"><i class="fa-solid fa-arrow-left me-1"></i>Contractor Login</a>
    </div>

<?= $this->endSection() ?>
