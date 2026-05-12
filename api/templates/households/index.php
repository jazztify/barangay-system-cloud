<?php $pageTitle = 'Households'; include __DIR__ . '/../layout/header.php'; ?>

<div class="page-actions">
    <form class="search-bar" method="GET" action="<?= $appConfig['base_url'] ?>/households">
        <div class="search-group"><i class='bx bx-search'></i><input type="text" name="search" placeholder="Search household..." value="<?= htmlspecialchars($search) ?>"></div>
        <button type="submit" class="btn btn-primary"><i class='bx bx-filter'></i> Search</button>
    </form>
    <a href="<?= $appConfig['base_url'] ?>/households/create" class="btn btn-primary"><i class='bx bx-plus'></i> Add Household</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table">
            <thead><tr><th>Household No.</th><th>Purok / Sitio</th><th>Address</th><th>Members</th><th>Actions</th></tr></thead>
            <tbody>
            <?php if (empty($households)): ?>
                <tr><td colspan="5" class="text-center text-muted">No households found.</td></tr>
            <?php else: ?>
                <?php foreach ($households as $h): ?>
                <tr>
                    <td class="font-mono font-medium"><?= htmlspecialchars($h['household_no']) ?></td>
                    <td><?= htmlspecialchars($h['purok_sitio']) ?></td>
                    <td><?= htmlspecialchars($h['address'] ?? '-') ?></td>
                    <td><span class="badge badge-primary"><?= $h['member_count'] ?></span></td>
                    <td class="actions-cell">
                        <a href="<?= $appConfig['base_url'] ?>/households/view/<?= $h['household_id'] ?>" class="btn btn-sm btn-outline"><i class='bx bx-show'></i></a>
                        <a href="<?= $appConfig['base_url'] ?>/households/edit/<?= $h['household_id'] ?>" class="btn btn-sm btn-outline"><i class='bx bx-edit'></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
