<?php $pageTitle = 'Add Household'; include __DIR__ . '/../layout/header.php'; ?>
<div class="card">
    <div class="card-header"><h3><i class='bx bx-home-alt'></i> New Household (Form A)</h3></div>
    <div class="card-body">
        <form method="POST" class="form-grid">
            <div class="form-row">
                <div class="form-group"><label>Household No. *</label><input type="text" name="household_no" required placeholder="e.g. HH-2024-004"></div>
                <div class="form-group"><label>Purok / Sitio *</label><input type="text" name="purok_sitio" required placeholder="e.g. Purok 1 - Sampaguita"></div>
            </div>
            <div class="form-group"><label>Full Address</label><textarea name="address" rows="2" class="form-textarea" placeholder="Street, Barangay, City"></textarea></div>
            <div class="form-actions">
                <a href="<?= $appConfig['base_url'] ?>/households" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Save Household</button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
