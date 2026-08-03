<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="mb-4">
        <h4 class="vb-page-heading mb-1">Notifications</h4>
        <p class="text-muted mb-0">Recent activity related to your account.</p>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($notifications)) : ?>
                <div class="text-center text-muted py-5">
                    <i class="fa-regular fa-bell fa-2x mb-2 d-block"></i>
                    You don't have any notifications yet.
                </div>
            <?php else : ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($notifications as $note) : ?>
                        <?php
                            $icon = match ($note['type']) {
                                'project_awarded' => 'fa-trophy text-warning',
                                'bid_rejected'     => 'fa-circle-xmark text-danger',
                                'bid_submitted'    => 'fa-paper-plane text-primary',
                                default            => 'fa-bell text-secondary',
                            };
                            $openUrl = isAdmin()
                                ? base_url('admin/notifications/open/' . $note['id'])
                                : base_url('contractor/notifications/open/' . $note['id']);
                        ?>
                        <a href="<?= $openUrl ?>" class="list-group-item list-group-item-action d-flex align-items-start gap-3 py-3 <?= (int) $note['is_read'] === 0 ? 'bg-primary-subtle bg-opacity-10' : '' ?>">
                            <div class="vb-stat-icon bg-light" style="width:44px;height:44px;font-size:1.1rem;">
                                <i class="fa-solid <?= $icon ?>"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-semibold"><?= esc($note['title']) ?></div>
                                    <div class="text-muted small"><?= date('d M Y, h:i A', strtotime($note['created_at'])) ?></div>
                                </div>
                                <div class="text-muted small mt-1"><?= esc($note['message']) ?></div>
                            </div>
                            <?php if ((int) $note['is_read'] === 0) : ?>
                                <span class="badge bg-primary rounded-pill align-self-center">New</span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?= $this->endSection() ?>
