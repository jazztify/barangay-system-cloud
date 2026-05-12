<?php $pageTitle = 'File a Blotter'; include __DIR__ . '/../layout/header.php'; ?>
<div class="card">
    <div class="card-header"><h3><i class='bx bx-error-circle'></i> File New Blotter Incident</h3></div>
    <div class="card-body">
        <form method="POST" class="form-grid">
            <div class="form-row">
                <div class="form-group">
                    <label>Complainant *</label>
                    <input type="text" id="complainant-search" placeholder="Type to search resident..." autocomplete="off">
                    <input type="hidden" name="complainant_id" id="complainant_id" required>
                    <div id="complainant-results" class="search-dropdown"></div>
                </div>
                <div class="form-group">
                    <label>Respondent *</label>
                    <input type="text" id="respondent-search" placeholder="Type to search resident..." autocomplete="off">
                    <input type="hidden" name="respondent_id" id="respondent_id" required>
                    <div id="respondent-results" class="search-dropdown"></div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Incident Type *</label>
                    <select name="incident_type" class="form-select" required>
                        <option value="">Select type...</option>
                        <option>Physical Assault</option>
                        <option>Verbal Abuse / Threats</option>
                        <option>Theft / Robbery</option>
                        <option>Swindling / Estafa</option>
                        <option>Land / Property Dispute</option>
                        <option>Noise Complaint</option>
                        <option>Domestic Violence</option>
                        <option>Vandalism</option>
                        <option>Trespassing</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Incident Date *</label>
                    <input type="date" name="incident_date" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Narrative / Detailed Account *</label>
                <textarea name="narrative" rows="5" class="form-textarea" required placeholder="Detailed account of the incident..."></textarea>
            </div>
            <div class="form-actions">
                <a href="<?= $appConfig['base_url'] ?>/blotters" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> File Blotter</button>
            </div>
        </form>
    </div>
</div>

<script>
function setupResidentSearch(inputId, hiddenId, resultsId) {
    const input = document.getElementById(inputId);
    const hidden = document.getElementById(hiddenId);
    const results = document.getElementById(resultsId);
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const q = this.value.trim();
        if (q.length < 2) { results.innerHTML = ''; results.style.display = 'none'; return; }
        timeout = setTimeout(() => {
            fetch('<?= $appConfig['base_url'] ?>/residents/api-search?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    if (data.length === 0) { results.innerHTML = '<div class="search-item text-muted">No results</div>'; }
                    else {
                        results.innerHTML = data.map(r => 
                            `<div class="search-item" data-id="${r.resident_id}">${r.last_name}, ${r.first_name} ${r.middle_name||''}</div>`
                        ).join('');
                    }
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
}
setupResidentSearch('complainant-search', 'complainant_id', 'complainant-results');
setupResidentSearch('respondent-search', 'respondent_id', 'respondent-results');
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
