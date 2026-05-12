<?php $pageTitle = 'Blotter #' . $blotter['blotter_id']; include __DIR__ . '/../layout/header.php'; ?>

<div class="card">
    <div class="card-header">
        <div class="flex-between">
            <h3><i class='bx bx-error-circle'></i> Blotter Case #<?= $blotter['blotter_id'] ?></h3>
            <span class="badge badge-lg badge-<?= match($blotter['status']) { 'Unresolved' => 'danger', 'Resolved' => 'success', 'Mediated' => 'warning', 'Dismissed' => 'muted', default => 'muted' } ?>"><?= $blotter['status'] ?></span>
        </div>
    </div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><span class="detail-label">Incident Type</span><span class="detail-value font-medium"><?= htmlspecialchars($blotter['incident_type']) ?></span></div>
            <div class="detail-item"><span class="detail-label">Incident Date</span><span class="detail-value"><?= date('F d, Y', strtotime($blotter['incident_date'])) ?></span></div>
            <div class="detail-item"><span class="detail-label">Complainant</span><span class="detail-value"><?= htmlspecialchars($blotter['complainant_name']) ?><br><small class="text-muted"><?= htmlspecialchars($blotter['complainant_contact'] ?? '') ?></small></span></div>
            <div class="detail-item"><span class="detail-label">Respondent</span><span class="detail-value"><?= htmlspecialchars($blotter['respondent_name']) ?><br><small class="text-muted"><?= htmlspecialchars($blotter['respondent_contact'] ?? '') ?></small></span></div>
        </div>
        <div class="mt-6">
            <h4>Narrative</h4>
            <div class="narrative-box"><?= nl2br(htmlspecialchars($blotter['narrative'])) ?></div>
        </div>
        <?php if ($blotter['resolution_notes']): ?>
        <div class="mt-6">
            <h4>Resolution Notes</h4>
            <div class="narrative-box resolution"><?= nl2br(htmlspecialchars($blotter['resolution_notes'])) ?></div>
            <p class="text-muted mt-2">Resolved on: <?= $blotter['resolution_date'] ? date('F d, Y', strtotime($blotter['resolution_date'])) : '-' ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($blotter['status'] === 'Unresolved'): ?>
<div class="dashboard-grid mt-6">
    <div class="card">
        <div class="card-header"><h3><i class='bx bx-check-circle'></i> Settle / Resolve</h3></div>
        <div class="card-body">
            <form method="POST" action="<?= $appConfig['base_url'] ?>/blotters/settle/<?= $blotter['blotter_id'] ?>">
                <div class="form-group"><label>Resolution Status *</label>
                    <select name="status" class="form-select" required>
                        <option value="Resolved">Resolved</option>
                        <option value="Mediated">Mediated (Amicable Settlement)</option>
                        <option value="Dismissed">Dismissed</option>
                    </select>
                </div>
                <div class="form-group"><label>Resolution Date</label><input type="date" name="resolution_date" value="<?= date('Y-m-d') ?>" class="form-input"></div>
                <div class="form-group"><label>Resolution Notes</label><textarea name="resolution_notes" rows="3" class="form-textarea" placeholder="Details of settlement / mediation..."></textarea></div>
                <button type="submit" class="btn btn-primary btn-block"><i class='bx bx-check'></i> Submit Resolution</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3><i class='bx bx-envelope'></i> Issue Summons (Patawag)</h3></div>
        <div class="card-body">
            <form method="POST" action="<?= $appConfig['base_url'] ?>/blotters/summons/<?= $blotter['blotter_id'] ?>">
                <div class="form-group"><label>Summons Date *</label><input type="date" name="summons_date" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required></div>
                <div class="form-group"><label>Type</label>
                    <select name="summons_type" class="form-select">
                        <option>Patawag</option>
                        <option>2nd Patawag</option>
                        <option>3rd & Final Patawag</option>
                        <option>Notice of Hearing</option>
                    </select>
                </div>
                <div class="form-group"><label>Notes</label><textarea name="notes" rows="2" class="form-textarea" placeholder="Additional notes..."></textarea></div>
                <button type="submit" class="btn btn-outline btn-block"><i class='bx bx-send'></i> Issue Summons</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($summons)): ?>
<div class="card mt-6">
    <div class="card-header"><h3>Summons History</h3></div>
    <div class="card-body table-responsive">
        <table class="table">
            <thead><tr><th>Date</th><th>Type</th><th>Notes</th><th>Appeared</th></tr></thead>
            <tbody>
            <?php foreach ($summons as $s): ?>
            <tr>
                <td><?= date('M d, Y', strtotime($s['summons_date'])) ?></td>
                <td><span class="badge badge-warning"><?= htmlspecialchars($s['summons_type']) ?></span></td>
                <td><?= htmlspecialchars($s['notes'] ?? '-') ?></td>
                <td><?= $s['appeared'] ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-muted">No</span>' ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php include __DIR__ . '/../layout/footer.php'; ?>
