<?php $pageTitle = 'Blotter & Peace'; include __DIR__ . '/../layout/header.php'; ?>

<div class="page-actions">
    <form class="search-bar" method="GET" action="<?= $appConfig['base_url'] ?>/blotters">
        <div class="search-group"><i class='bx bx-search'></i><input type="text" name="search" placeholder="Search incidents..." value="<?= htmlspecialchars($search) ?>"></div>
        <select name="status" class="form-select">
            <option value="">All Status</option>
            <option value="Unresolved" <?= $status==='Unresolved'?'selected':'' ?>>Unresolved</option>
            <option value="Resolved" <?= $status==='Resolved'?'selected':'' ?>>Resolved</option>
            <option value="Mediated" <?= $status==='Mediated'?'selected':'' ?>>Mediated</option>
            <option value="Dismissed" <?= $status==='Dismissed'?'selected':'' ?>>Dismissed</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class='bx bx-filter'></i> Filter</button>
    </form>
    <a href="<?= $appConfig['base_url'] ?>/blotters/create" class="btn btn-primary"><i class='bx bx-plus'></i> File Blotter</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table">
            <thead><tr><th>ID</th><th>Date</th><th>Incident Type</th><th>Complainant</th><th>Respondent</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($blotters)): ?>
                <tr><td colspan="7" class="text-center text-muted">No blotter records.</td></tr>
            <?php else: ?>
                <?php foreach ($blotters as $b): ?>
                <tr>
                    <td class="font-mono">#<?= $b['blotter_id'] ?></td>
                    <td><?= date('M d, Y', strtotime($b['incident_date'])) ?></td>
                    <td class="font-medium"><?= htmlspecialchars($b['incident_type']) ?></td>
                    <td><?= htmlspecialchars($b['complainant_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($b['respondent_name'] ?? '-') ?></td>
                    <td><span class="badge badge-<?= match($b['status']) { 'Unresolved' => 'danger', 'Resolved' => 'success', 'Mediated' => 'warning', 'Dismissed' => 'muted', default => 'muted' } ?>"><?= $b['status'] ?></span></td>
                    <td><a href="<?= $appConfig['base_url'] ?>/blotters/view/<?= $b['blotter_id'] ?>" class="btn btn-sm btn-outline"><i class='bx bx-show'></i> View</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
