<?php $pageTitle = 'Issue Document'; include __DIR__ . '/../layout/header.php'; $preselectedId = $_GET['resident_id'] ?? ''; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-error"><?= $error ?></div>
<?php endif; ?>
<?php if (isset($warning)): ?>
    <div class="alert alert-warning"><?= $warning ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header"><h3><i class='bx bx-file-blank'></i> Issue New Document</h3></div>
    <div class="card-body">
        <form method="POST" class="form-grid" id="doc-form">
            <div class="form-row">
                <div class="form-group" style="position:relative">
                    <label>Search Resident *</label>
                    <input type="text" id="doc-resident-search" placeholder="Type name to search..." autocomplete="off" <?= $preselectedId ? 'disabled' : '' ?>>
                    <input type="hidden" name="resident_id" id="doc-resident-id" value="<?= htmlspecialchars($preselectedId) ?>" required>
                    <div id="doc-resident-results" class="search-dropdown"></div>
                    <?php if ($preselectedId): ?>
                        <?php $preResident = \App\Models\Resident::getById((int)$preselectedId); ?>
                        <?php if ($preResident): ?>
                            <div class="selected-resident">
                                <span><?= htmlspecialchars($preResident['last_name'] . ', ' . $preResident['first_name']) ?></span>
                                <a href="<?= $appConfig['base_url'] ?>/documents/request" class="btn btn-sm btn-outline">Change</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Document Type *</label>
                    <select name="document_type" id="doc-type" class="form-select" required>
                        <option value="">Select document...</option>
                        <option value="Barangay Clearance">Barangay Clearance</option>
                        <option value="Certificate of Residency">Certificate of Residency</option>
                        <option value="Certificate of Indigency">Certificate of Indigency</option>
                        <option value="First-Time Job Seeker (RA 11261)">First-Time Job Seeker (R.A. 11261)</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Purpose</label><input type="text" name="purpose" placeholder="e.g. Employment, Scholarship, Legal"></div>
                <div class="form-group"><label>O.R. Number</label><input type="text" name="or_number" placeholder="Official Receipt #"></div>
            </div>

            <!-- Indigency extra fields -->
            <div id="indigency-fields" style="display:none">
                <div class="form-row">
                    <div class="form-group"><label>Annual Income</label><input type="text" name="annual_income" placeholder="e.g. Below Php 100,000"></div>
                    <div class="form-group"><label>Parent/Guardian Name</label><input type="text" name="parents_name"></div>
                    <div class="form-group"><label>Beneficiary Name</label><input type="text" name="beneficiary_name"></div>
                </div>
            </div>

            <!-- RA 11261 extra fields -->
            <div id="jobseeker-fields" style="display:none">
                <div class="form-row">
                    <div class="form-group"><label>Oath Date</label><input type="date" name="oath_date" value="<?= date('Y-m-d') ?>"></div>
                </div>
                <div class="alert alert-info"><i class='bx bx-info-circle'></i> This certification is valid for ONE (1) year from date of issuance and is a one-time availment only per R.A. 11261.</div>
            </div>

            <div class="form-actions">
                <a href="<?= $appConfig['base_url'] ?>/documents" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class='bx bx-printer'></i> Generate & Print</button>
            </div>
        </form>
    </div>
</div>

<script>
// Resident search for document issuance
(function() {
    const input = document.getElementById('doc-resident-search');
    const hidden = document.getElementById('doc-resident-id');
    const results = document.getElementById('doc-resident-results');
    if (!input || input.disabled) return;
    let timeout;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const q = this.value.trim();
        if (q.length < 2) { results.innerHTML = ''; results.style.display = 'none'; return; }
        timeout = setTimeout(() => {
            fetch('<?= $appConfig['base_url'] ?>/residents/api-search?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    results.innerHTML = data.length === 0 
                        ? '<div class="search-item text-muted">No results</div>'
                        : data.map(r => `<div class="search-item" data-id="${r.resident_id}">${r.last_name}, ${r.first_name} ${r.middle_name||''}</div>`).join('');
                    results.style.display = 'block';
                    results.querySelectorAll('.search-item[data-id]').forEach(item => {
                        item.addEventListener('click', function() {
                            input.value = this.textContent.trim();
                            hidden.value = this.dataset.id;
                            results.style.display = 'none';
                        });
                    });
                });
        }, 300);
    });
    document.addEventListener('click', e => { if (!input.contains(e.target) && !results.contains(e.target)) results.style.display = 'none'; });
})();

// Toggle extra fields based on document type
document.getElementById('doc-type').addEventListener('change', function() {
    document.getElementById('indigency-fields').style.display = this.value === 'Certificate of Indigency' ? 'block' : 'none';
    document.getElementById('jobseeker-fields').style.display = this.value === 'First-Time Job Seeker (RA 11261)' ? 'block' : 'none';
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
