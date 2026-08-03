<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="vb-page-heading mb-1">My Profile</h4>
            <p class="text-muted mb-0">Your account information as registered on VendorBid.</p>
        </div>
        <a href="<?= base_url('contractor/profile/edit') ?>" class="btn btn-vb-primary">
            <i class="fa-solid fa-pen me-1"></i>Edit Profile
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center">
                    <div class="vb-sidebar-avatar mx-auto mb-3" style="width:72px;height:72px;font-size:1.6rem;">
                        <?= strtoupper(substr($profile['name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <h5 class="mb-1"><?= esc($profile['name'] ?? '—') ?></h5>
                    <p class="text-muted small mb-2"><?= esc($profile['company_name'] ?? '—') ?></p>
                    <span class="badge bg-success-subtle text-success text-capitalize"><?= esc($profile['status'] ?? '—') ?></span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-muted small mb-3">Account Details</h6>
                    <table class="table table-borderless mb-0 vb-profile-table">
                        <tbody>
                            <tr>
                                <th scope="row">Full Name</th>
                                <td><?= esc($profile['name'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Email</th>
                                <td><?= esc($profile['email'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Company Name</th>
                                <td><?= esc($profile['company_name'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Phone</th>
                                <td><?= esc($profile['phone'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Address</th>
                                <td><?= esc($profile['address'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Account Status</th>
                                <td class="text-capitalize"><?= esc($profile['status'] ?? '—') ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Registered On</th>
                                <td>
                                    <?= isset($profile['created_at']) ? date('d M Y, h:i A', strtotime($profile['created_at'])) : '—' ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
