<?php $pageTitle = 'Residents (RBI)'; include __DIR__ . '/../layout/header.php'; ?>

<div class="page-actions">
    <form class="search-bar" method="GET" action="<?= $appConfig['base_url'] ?>/residents">
        <div class="search-group">
            <i class='bx bx-search'></i>
            <input type="text" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <select name="purok" class="form-select">
            <option value="">All Puroks</option>
            <?php foreach ($puroks as $p): ?>
                <option value="<?= htmlspecialchars($p) ?>" <?= $purok === $p ? 'selected' : '' ?>><?= htmlspecialchars($p) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="sector" class="form-select">
            <option value="">All Sectors</option>
            <option value="pwd" <?= $sector==='pwd'?'selected':'' ?>>PWD</option>
            <option value="senior" <?= $sector==='senior'?'selected':'' ?>>Senior Citizen</option>
            <option value="solo_parent" <?= $sector==='solo_parent'?'selected':'' ?>>Solo Parent</option>
            <option value="voter" <?= $sector==='voter'?'selected':'' ?>>Registered Voter</option>
        </select>
        <button type="submit" class="btn btn-primary"><i class='bx bx-filter'></i> Filter</button>
    </form>
    <a href="<?= $appConfig['base_url'] ?>/residents/create" class="btn btn-primary"><i class='bx bx-plus'></i> Add Resident</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Purok</th>
                    <th>Sex</th>
                    <th>Civil Status</th>
                    <th>DOB</th>
                    <th>Occupation</th>
                    <th>Voter</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($residents)): ?>
                <tr><td colspan="8" class="text-center text-muted">No residents found.</td></tr>
            <?php else: ?>
                <?php foreach ($residents as $r): ?>
                <tr>
                    <td class="font-medium"><?= htmlspecialchars($r['last_name'] . ', ' . $r['first_name'] . ' ' . ($r['middle_name'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($r['purok_sitio'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['sex'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($r['civil_status'] ?? '-') ?></td>
                    <td><?= $r['date_of_birth'] ? date('M d, Y', strtotime($r['date_of_birth'])) : '-' ?></td>
                    <td><?= htmlspecialchars($r['occupation'] ?? '-') ?></td>
                    <td><?= $r['voter_status'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-muted">No</span>' ?></td>
                    <td class="actions-cell">
                        <a href="<?= $appConfig['base_url'] ?>/residents/profile/<?= $r['resident_id'] ?>" class="btn btn-sm btn-outline" title="View"><i class='bx bx-show'></i></a>
                        <a href="<?= $appConfig['base_url'] ?>/residents/edit/<?= $r['resident_id'] ?>" class="btn btn-sm btn-outline" title="Edit"><i class='bx bx-edit'></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="results-count"><?= count($residents) ?> resident(s) found</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
