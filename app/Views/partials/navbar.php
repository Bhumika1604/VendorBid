<header class="vb-navbar">
    <button class="vb-sidebar-toggle" id="vbSidebarToggle" type="button" aria-label="Toggle sidebar">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="vb-navbar-title">
        <h1><?= esc($title ?? 'Dashboard') ?></h1>
    </div>

    <div class="vb-navbar-right">
        <span class="vb-navbar-badge">
            <i class="fa-solid fa-shield-halved"></i>
            <?= esc(ucfirst(session()->get('role') ?? '')) ?>
        </span>

        <div class="dropdown">
            <button class="vb-navbar-user btn" type="button" id="vbUserMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="vb-navbar-avatar">
                    <?= strtoupper(substr(session()->get('name') ?? 'U', 0, 1)) ?>
                </div>
                <span class="d-none d-md-inline"><?= esc(session()->get('name')) ?></span>
                <i class="fa-solid fa-chevron-down small"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="vbUserMenu">
                <li><h6 class="dropdown-header"><?= esc(session()->get('email')) ?></h6></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
