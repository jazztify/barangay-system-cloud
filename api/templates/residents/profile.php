<?php $pageTitle = $resident['last_name'] . ', ' . $resident['first_name']; include __DIR__ . '/../layout/header.php'; ?>

<div class="profile-header-card">
    <div class="profile-avatar"><i class='bx bx-user'></i></div>
    <div class="profile-info">
        <h2><?= htmlspecialchars($resident['last_name'] . ', ' . $resident['first_name'] . ' ' . ($resident['middle_name'] ?? '') . ' ' . ($resident['suffix'] ?? '')) ?></h2>
        <p class="text-muted"><?= htmlspecialchars($resident['purok_sitio'] ?? 'No household assigned') ?> &bull; Household <?= htmlspecialchars($resident['household_no'] ?? 'N/A') ?></p>
        <div class="profile-badges">
            <?php if ($resident['voter_status']): ?><span class="badge badge-success">Voter</span><?php endif; ?>
            <?php if ($resident['pwd_flag']): ?><span class="badge badge-primary">PWD</span><?php endif; ?>
            <?php if ($resident['senior_citizen_flag']): ?><span class="badge badge-warning">Senior Citizen</span><?php endif; ?>
            <?php if ($resident['solo_parent_flag']): ?><span class="badge badge-info">Solo Parent</span><?php endif; ?>
        </div>
    </div>
    <div class="profile-actions">
        <a href="<?= $appConfig['base_url'] ?>/residents/edit/<?= $resident['resident_id'] ?>" class="btn btn-outline"><i class='bx bx-edit'></i> Edit</a>
        <a href="<?= $appConfig['base_url'] ?>/documents/request?resident_id=<?= $resident['resident_id'] ?>" class="btn btn-primary"><i class='bx bx-printer'></i> Issue Document</a>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card col-span-2">
        <div class="card-header"><h3>Personal Details</h3></div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item"><span class="detail-label">Date of Birth</span><span class="detail-value"><?= $resident['date_of_birth'] ? date('F d, Y', strtotime($resident['date_of_birth'])) : '-' ?></span></div>
                <div class="detail-item"><span class="detail-label">Age</span><span class="detail-value"><?= $resident['date_of_birth'] ? floor((time()-strtotime($resident['date_of_birth']))/31557600) . ' years old' : '-' ?></span></div>
                <div class="detail-item"><span class="detail-label">Sex</span><span class="detail-value"><?= htmlspecialchars($resident['sex'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">Civil Status</span><span class="detail-value"><?= htmlspecialchars($resident['civil_status'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">Nationality</span><span class="detail-value"><?= htmlspecialchars($resident['nationality'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">Religion</span><span class="detail-value"><?= htmlspecialchars($resident['religion'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">Occupation</span><span class="detail-value"><?= htmlspecialchars($resident['occupation'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">Education</span><span class="detail-value"><?= htmlspecialchars($resident['educational_attainment'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">Contact</span><span class="detail-value"><?= htmlspecialchars($resident['contact_number'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">PhilSys PCN</span><span class="detail-value"><?= htmlspecialchars($resident['philsys_card_no'] ?? '-') ?></span></div>
                <div class="detail-item"><span class="detail-label">Address</span><span class="detail-value"><?= htmlspecialchars($resident['household_address'] ?? '-') ?></span></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Blotter History</h3></div>
        <div class="card-body">
            <?php if (empty($blotters)): ?>
                <p class="text-muted text-center">No blotter records.</p>
            <?php else: ?>
                <?php foreach ($blotters as $b): ?>
                <div class="timeline-item">
                    <span class="badge badge-<?= $b['status']==='Unresolved'?'danger':($b['status']==='Resolved'?'success':'warning') ?>"><?= $b['status'] ?></span>
                    <p><strong><?= htmlspecialchars($b['incident_type']) ?></strong></p>
                    <small class="text-muted"><?= date('M d, Y', strtotime($b['incident_date'])) ?></small>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header"><h3>Issuance History</h3></div>
    <div class="card-body table-responsive">
        <?php if (empty($issuances)): ?>
            <p class="text-muted text-center">No documents issued yet.</p>
        <?php else: ?>
        <table class="table">
            <thead><tr><th>Control #</th><th>Document Type</th><th>Purpose</th><th>Issued By</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($issuances as $i): ?>
            <tr>
                <td class="font-mono"><?= htmlspecialchars($i['control_number']) ?></td>
                <td><?= htmlspecialchars($i['document_type']) ?></td>
                <td><?= htmlspecialchars($i['purpose'] ?? '-') ?></td>
                <td><?= htmlspecialchars($i['issued_by_name'] ?? '-') ?></td>
                <td><?= date('M d, Y', strtotime($i['issued_at'])) ?></td>
                <td><a href="<?= $appConfig['base_url'] ?>/documents/print/<?= $i['issuance_id'] ?>" class="btn btn-sm btn-outline" target="_blank"><i class='bx bx-printer'></i></a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
