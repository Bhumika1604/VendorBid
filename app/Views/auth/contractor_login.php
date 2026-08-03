<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

    <div class="vb-auth-card-header">
        <div class="vb-auth-icon vb-auth-icon-contractor">
            <i class="fa-solid fa-helmet-safety"></i>
        </div>
        <h2>Contractor Login</h2>
        <p>Sign in to browse projects and place your bids.</p>
    </div>

    <?= form_open('login') ?>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email"
                       value="<?= esc(old('email')) ?>" placeholder="you@company.com" required autofocus>
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
            <i class="fa-solid fa-right-to-bracket me-1"></i> Login as Contractor
        </button>

    <?= form_close() ?>

    <div class="vb-auth-links">
        <a href="<?= base_url('register') ?>"><i class="fa-solid fa-user-plus me-1"></i>New contractor? Register here</a>
        <span class="vb-auth-links-sep">&middot;</span>
        <a href="<?= base_url('admin/login') ?>"><i class="fa-solid fa-user-shield me-1"></i>Admin Login</a>
    </div>

<?= $this->endSection() ?>
