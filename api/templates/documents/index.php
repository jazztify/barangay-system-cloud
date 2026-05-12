<?php $pageTitle = 'Issuances & Documents'; include __DIR__ . '/../layout/header.php'; ?>

<div class="page-actions">
    <form class="search-bar" method="GET" action="<?= $appConfig['base_url'] ?>/documents">
        <div class="search-group"><i class='bx bx-search'></i><input type="text" name="search" placeholder="Search by control#, name, type..." value="<?= htmlspecialchars($search) ?>"></div>
        <button type="submit" class="btn btn-primary"><i class='bx bx-filter'></i> Search</button>
    </form>
    <a href="<?= $appConfig['base_url'] ?>/documents/request" class="btn btn-primary"><i class='bx bx-plus'></i> Issue Document</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table">
            <thead><tr><th>Control #</th><th>Document Type</th><th>Resident</th><th>OR #</th><th>Purpose</th><th>Issued By</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($issuances)): ?>
                <tr><td colspan="8" class="text-center text-muted">No documents issued yet.</td></tr>
            <?php else: ?>
                <?php foreach ($issuances as $i): ?>
                <tr>
                    <td class="font-mono font-medium"><?= htmlspecialchars($i['control_number']) ?></td>
                    <td><?= htmlspecialchars($i['document_type']) ?></td>
                    <td><?= htmlspecialchars($i['resident_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($i['or_number'] ?? '-') ?></td>
                    <td class="text-truncate"><?= htmlspecialchars($i['purpose'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($i['issued_by_name'] ?? '-') ?></td>
                    <td><?= date('M d, Y', strtotime($i['issued_at'])) ?></td>
                    <td><a href="<?= $appConfig['base_url'] ?>/documents/print/<?= $i['issuance_id'] ?>" class="btn btn-sm btn-outline" target="_blank"><i class='bx bx-printer'></i> Print</a></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
