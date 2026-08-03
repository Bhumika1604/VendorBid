<aside class="vb-sidebar" id="vbSidebar">
    <div class="vb-sidebar-brand">
        <i class="fa-solid fa-gavel"></i>
        <span>VendorBid</span>
    </div>

    <div class="vb-sidebar-user">
        <div class="vb-sidebar-avatar">
            <?= strtoupper(substr(session()->get('name') ?? 'U', 0, 1)) ?>
        </div>
        <div>
            <div class="vb-sidebar-username"><?= esc(session()->get('name')) ?></div>
            <div class="vb-sidebar-role"><?= esc(ucfirst(session()->get('role') ?? '')) ?></div>
        </div>
    </div>

    <nav class="vb-sidebar-nav">
        <?php if (isAdmin()) : ?>
            <a href="<?= base_url('admin/dashboard') ?>"
               class="vb-nav-link <?= current_url() === base_url('admin/dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
            </a>
            <a href="<?= base_url('admin/analytics') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'admin/analytics') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i> <span>Analytics</span>
            </a>
            <a href="<?= base_url('admin/projects') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'admin/projects') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-diagram-project"></i> <span>Projects</span>
            </a>
            <a href="<?= base_url('admin/bids') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'admin/bids') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i> <span>Bids</span>
            </a>
            <a href="<?= base_url('admin/awards') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'admin/awards') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-trophy"></i> <span>Awards</span>
            </a>
            <a href="<?= base_url('admin/reports/projects') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'admin/reports') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-column"></i> <span>Reports</span>
            </a>
            <a href="<?= base_url('admin/notifications') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'admin/notifications') !== false ? 'active' : '' ?>">
                <i class="fa-regular fa-bell"></i> <span>Notifications</span>
            </a>
        <?php elseif (isContractor()) : ?>
            <a href="<?= base_url('contractor/dashboard') ?>"
               class="vb-nav-link <?= current_url() === base_url('contractor/dashboard') ? 'active' : '' ?>">
                <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
            </a>
            <a href="<?= base_url('contractor/projects') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'contractor/projects') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-diagram-project"></i> <span>Open Projects</span>
            </a>
            <a href="<?= base_url('contractor/bids') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'contractor/bids') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i> <span>My Bids</span>
            </a>
            <a href="<?= base_url('contractor/profile') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'contractor/profile') !== false ? 'active' : '' ?>">
                <i class="fa-solid fa-user"></i> <span>My Profile</span>
            </a>
            <a href="<?= base_url('contractor/notifications') ?>"
               class="vb-nav-link <?= strpos(current_url(), 'contractor/notifications') !== false ? 'active' : '' ?>">
                <i class="fa-regular fa-bell"></i> <span>Notifications</span>
            </a>
        <?php endif; ?>
    </nav>

    <div class="vb-sidebar-bottom">
        <a href="<?= base_url('logout') ?>" class="vb-nav-link vb-logout">
            <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
        </a>
    </div>
</aside>

<div class="vb-sidebar-backdrop" id="vbSidebarBackdrop"></div>
