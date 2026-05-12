<?php $pageTitle = 'Dashboard'; include __DIR__ . '/../layout/header.php'; ?>

<?php
// Prepare chart data
$ageLabels = ['0-14 yrs', '15-24 yrs', '25-54 yrs', '55-64 yrs', '65+ yrs'];
$ageCounts = array_fill(0, 5, 0);
if (!empty($demographics['age_brackets'])) {
    foreach ($demographics['age_brackets'] as $row) {
        $idx = array_search($row['bracket'], $ageLabels);
        if ($idx !== false) $ageCounts[$idx] = (int)$row['count'];
    }
}
$sexLabels = []; $sexCounts = [];
if (!empty($demographics['sex'])) {
    foreach ($demographics['sex'] as $row) { $sexLabels[] = $row['sex']; $sexCounts[] = (int)$row['count']; }
}
?>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon bg-primary-light"><i class='bx bx-group text-primary'></i></div>
        <div class="stat-details"><h3>Total Residents</h3><p class="stat-number"><?= number_format($stats['total_residents']) ?></p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-success-light"><i class='bx bx-home text-success'></i></div>
        <div class="stat-details"><h3>Households</h3><p class="stat-number"><?= number_format($stats['total_households']) ?></p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-warning-light"><i class='bx bx-error text-warning'></i></div>
        <div class="stat-details"><h3>Active Blotters</h3><p class="stat-number"><?= number_format($stats['active_blotters']) ?></p></div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-info-light"><i class='bx bx-file text-info'></i></div>
        <div class="stat-details"><h3>Docs Issued (MTD)</h3><p class="stat-number"><?= number_format($stats['docs_this_month']) ?></p></div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card col-span-2">
        <div class="card-header"><h3>Population by Age Bracket</h3></div>
        <div class="card-body"><canvas id="ageChart" height="120"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Sex Distribution</h3></div>
        <div class="card-body"><canvas id="sexChart" height="200"></canvas></div>
    </div>
</div>

<div class="dashboard-grid mt-6">
    <div class="card">
        <div class="card-header"><h3>Sector Summary</h3></div>
        <div class="card-body">
            <div class="sector-list">
                <div class="sector-item"><span>PWD</span><span class="badge badge-primary"><?= $demographics['pwd'] ?></span></div>
                <div class="sector-item"><span>Senior Citizens</span><span class="badge badge-warning"><?= $demographics['senior'] ?></span></div>
                <div class="sector-item"><span>Solo Parents</span><span class="badge badge-info"><?= $demographics['solo_parent'] ?></span></div>
                <div class="sector-item"><span>Registered Voters</span><span class="badge badge-success"><?= $demographics['voters'] ?></span></div>
            </div>
        </div>
    </div>
    <div class="card col-span-2">
        <div class="card-header"><h3>Recent Activity</h3></div>
        <div class="card-body">
            <?php if (empty($recentActivity)): ?>
                <p class="text-muted">No recent activity.</p>
            <?php else: ?>
                <table class="table">
                    <thead><tr><th>User</th><th>Action</th><th>Details</th><th>When</th></tr></thead>
                    <tbody>
                    <?php foreach ($recentActivity as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['user_name'] ?? 'System') ?></td>
                            <td><?= htmlspecialchars($log['action']) ?></td>
                            <td class="text-truncate"><?= htmlspecialchars($log['details']) ?></td>
                            <td><?= date('M d, g:i A', strtotime($log['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('ageChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($ageLabels) ?>,
            datasets: [{
                label: 'Population',
                data: <?= json_encode($ageCounts) ?>,
                backgroundColor: ['#93c5fd','#60a5fa','#3b82f6','#2563eb','#1d4ed8'],
                borderRadius: 6
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
    new Chart(document.getElementById('sexChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($sexLabels) ?>,
            datasets: [{ data: <?= json_encode($sexCounts) ?>, backgroundColor: ['#3b82f6','#ec4899'], hoverOffset: 4 }]
        },
        options: { responsive: true, cutout: '60%' }
    });
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
