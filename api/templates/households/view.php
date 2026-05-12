<?php $pageTitle = 'Household ' . $household['household_no']; include __DIR__ . '/../layout/header.php'; ?>
<div class="card">
    <div class="card-header">
        <h3><i class='bx bx-home'></i> <?= htmlspecialchars($household['household_no']) ?> — <?= htmlspecialchars($household['purok_sitio']) ?></h3>
    </div>
    <div class="card-body">
        <p><strong>Address:</strong> <?= htmlspecialchars($household['address'] ?? '-') ?></p>
        <p><strong>Registered:</strong> <?= date('M d, Y', strtotime($household['created_at'])) ?></p>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header"><h3>Household Members (<?= count($members) ?>)</h3></div>
    <div class="card-body table-responsive">
        <?php if (empty($members)): ?>
            <p class="text-muted text-center">No members assigned.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Name</th><th>Sex</th><th>DOB</th><th>Civil Status</th><th>Occupation</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($members as $m): ?>
            <tr>
                <td class="font-medium"><?= htmlspecialchars($m['last_name'] . ', ' . $m['first_name'] . ' ' . ($m['middle_name'] ?? '')) ?></td>
                <td><?= $m['sex'] ?? '-' ?></td>
                <td><?= $m['date_of_birth'] ? date('M d, Y', strtotime($m['date_of_birth'])) : '-' ?></td>
                <td><?= $m['civil_status'] ?? '-' ?></td>
                <td><?= htmlspecialchars($m['occupation'] ?? '-') ?></td>
                <td><a href="<?= $appConfig['base_url'] ?>/residents/profile/<?= $m['resident_id'] ?>" class="btn btn-sm btn-outline"><i class='bx bx-show'></i></a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
