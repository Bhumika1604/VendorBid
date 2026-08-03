<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="mb-4">
        <h4 class="vb-page-heading mb-1">Analytics</h4>
        <p class="text-muted mb-0">A visual breakdown of activity across VendorBid.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-2-4">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="vb-stat-icon bg-primary-subtle text-primary mb-2"><i class="fa-solid fa-diagram-project"></i></div>
                    <div class="vb-stat-value"><?= (int) $totalProjects ?></div>
                    <div class="vb-stat-label">Total Projects</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2-4">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="vb-stat-icon bg-success-subtle text-success mb-2"><i class="fa-solid fa-lock-open"></i></div>
                    <div class="vb-stat-value"><?= (int) $openProjects ?></div>
                    <div class="vb-stat-label">Open Projects</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2-4">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="vb-stat-icon bg-warning-subtle text-warning mb-2"><i class="fa-solid fa-trophy"></i></div>
                    <div class="vb-stat-value"><?= (int) $awardedProjects ?></div>
                    <div class="vb-stat-label">Awarded Projects</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2-4">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="vb-stat-icon bg-info-subtle text-info mb-2"><i class="fa-solid fa-helmet-safety"></i></div>
                    <div class="vb-stat-value"><?= (int) $totalContractors ?></div>
                    <div class="vb-stat-label">Total Contractors</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2-4">
            <div class="card vb-stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="vb-stat-icon bg-primary-subtle text-primary mb-2"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    <div class="vb-stat-value"><?= (int) $totalBids ?></div>
                    <div class="vb-stat-label">Total Bids</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="mb-3"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Project Status Breakdown</h6>
                    <canvas id="projectStatusChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="mb-3"><i class="fa-solid fa-chart-line text-primary me-2"></i>Projects Created (Last 6 Months)</h6>
                    <canvas id="monthlyTrendChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="mb-3"><i class="fa-solid fa-chart-simple text-primary me-2"></i>Bid Status Breakdown</h6>
                    <canvas id="bidStatusChart" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h6 class="mb-3"><i class="fa-solid fa-scale-balanced text-primary me-2"></i>Contractors vs Bids</h6>
                    <canvas id="contractorsBidsChart" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    /* 5-column stat row on large screens, wraps naturally below */
    @media (min-width: 1200px) {
        .col-xl-2-4 { flex: 0 0 auto; width: 20%; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    (function () {
        var vbColors = {
            primary: '#2451B8',
            secondary: '#F5A623',
            success: '#198754',
            info: '#0dcaf0',
            warning: '#ffc107',
            danger: '#dc3545',
            grey: '#adb5bd'
        };

        // Project Status Breakdown (doughnut)
        new Chart(document.getElementById('projectStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Open', 'Awarded', 'Closed'],
                datasets: [{
                    data: [<?= (int) $openProjects ?>, <?= (int) $awardedProjects ?>, <?= (int) $closedProjects ?>],
                    backgroundColor: [vbColors.success, vbColors.primary, vbColors.grey]
                }]
            },
            options: { plugins: { legend: { position: 'bottom' } } }
        });

        // Monthly Trend (line)
        new Chart(document.getElementById('monthlyTrendChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($monthlyLabels) ?>,
                datasets: [{
                    label: 'Projects Created',
                    data: <?= json_encode($monthlyCounts) ?>,
                    borderColor: vbColors.primary,
                    backgroundColor: 'rgba(36,81,184,0.12)',
                    tension: 0.35,
                    fill: true,
                    pointRadius: 4
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // Bid Status Breakdown (bar)
        new Chart(document.getElementById('bidStatusChart'), {
            type: 'bar',
            data: {
                labels: ['Pending', 'Shortlisted', 'Awarded', 'Rejected'],
                datasets: [{
                    label: 'Bids',
                    data: [
                        <?= (int) ($bidStatusCounts['pending'] ?? 0) ?>,
                        <?= (int) ($bidStatusCounts['shortlisted'] ?? 0) ?>,
                        <?= (int) ($bidStatusCounts['awarded'] ?? 0) ?>,
                        <?= (int) ($bidStatusCounts['rejected'] ?? 0) ?>
                    ],
                    backgroundColor: [vbColors.warning, vbColors.info, vbColors.primary, vbColors.danger],
                    borderRadius: 6
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });

        // Contractors vs Bids (bar comparison)
        new Chart(document.getElementById('contractorsBidsChart'), {
            type: 'bar',
            data: {
                labels: ['Total Contractors', 'Total Bids'],
                datasets: [{
                    label: 'Count',
                    data: [<?= (int) $totalContractors ?>, <?= (int) $totalBids ?>],
                    backgroundColor: [vbColors.info, vbColors.secondary],
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    })();
</script>
<?= $this->endSection() ?>
