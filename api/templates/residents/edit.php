<?php $pageTitle = 'Edit Resident'; include __DIR__ . '/../layout/header.php'; ?>

<div class="card">
    <div class="card-header"><h3><i class='bx bx-edit'></i> Edit Resident</h3></div>
    <div class="card-body">
        <form method="POST" class="form-grid">
            <div class="form-section">
                <h4>Personal Information</h4>
                <div class="form-row">
                    <div class="form-group"><label>Last Name *</label><input type="text" name="last_name" value="<?= htmlspecialchars($resident['last_name']) ?>" required></div>
                    <div class="form-group"><label>First Name *</label><input type="text" name="first_name" value="<?= htmlspecialchars($resident['first_name']) ?>" required></div>
                    <div class="form-group"><label>Middle Name</label><input type="text" name="middle_name" value="<?= htmlspecialchars($resident['middle_name'] ?? '') ?>"></div>
                    <div class="form-group"><label>Suffix</label><input type="text" name="suffix" value="<?= htmlspecialchars($resident['suffix'] ?? '') ?>"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Date of Birth</label><input type="date" name="date_of_birth" value="<?= $resident['date_of_birth'] ?? '' ?>"></div>
                    <div class="form-group"><label>Sex</label><select name="sex" class="form-select"><option value="">Select</option><option value="Male" <?= ($resident['sex']??'')==='Male'?'selected':'' ?>>Male</option><option value="Female" <?= ($resident['sex']??'')==='Female'?'selected':'' ?>>Female</option></select></div>
                    <div class="form-group"><label>Civil Status</label><select name="civil_status" class="form-select"><option value="">Select</option><?php foreach(['Single','Married','Widowed','Separated','Divorced'] as $cs): ?><option <?= ($resident['civil_status']??'')===$cs?'selected':'' ?>><?= $cs ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label>Nationality</label><input type="text" name="nationality" value="<?= htmlspecialchars($resident['nationality'] ?? 'Filipino') ?>"></div>
                </div>
            </div>
            <div class="form-section">
                <h4>Additional Details</h4>
                <div class="form-row">
                    <div class="form-group"><label>Religion</label><input type="text" name="religion" value="<?= htmlspecialchars($resident['religion'] ?? '') ?>"></div>
                    <div class="form-group"><label>Occupation</label><input type="text" name="occupation" value="<?= htmlspecialchars($resident['occupation'] ?? '') ?>"></div>
                    <div class="form-group"><label>Educational Attainment</label><select name="educational_attainment" class="form-select"><option value="">Select</option><?php foreach(['Elementary Level','Elementary Graduate','High School Level','High School Graduate','College Level','College Graduate','Vocational','Post Graduate','None'] as $ea): ?><option <?= ($resident['educational_attainment']??'')===$ea?'selected':'' ?>><?= $ea ?></option><?php endforeach; ?></select></div>
                    <div class="form-group"><label>Contact Number</label><input type="text" name="contact_number" value="<?= htmlspecialchars($resident['contact_number'] ?? '') ?>"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>PhilSys Card No.</label><input type="text" name="philsys_card_no" value="<?= htmlspecialchars($resident['philsys_card_no'] ?? '') ?>"></div>
                    <div class="form-group"><label>Household</label><select name="household_id" class="form-select"><option value="">-- None --</option><?php foreach ($households as $h): ?><option value="<?= $h['household_id'] ?>" <?= ($resident['household_id']??'')==$h['household_id']?'selected':'' ?>><?= htmlspecialchars($h['household_no'] . ' - ' . $h['purok_sitio']) ?></option><?php endforeach; ?></select></div>
                </div>
            </div>
            <div class="form-section">
                <h4>Sector Flags</h4>
                <div class="form-row checkbox-row">
                    <label class="checkbox-label"><input type="checkbox" name="voter_status" value="1" <?= $resident['voter_status']?'checked':'' ?>> Registered Voter</label>
                    <label class="checkbox-label"><input type="checkbox" name="pwd_flag" value="1" <?= $resident['pwd_flag']?'checked':'' ?>> PWD</label>
                    <label class="checkbox-label"><input type="checkbox" name="senior_citizen_flag" value="1" <?= $resident['senior_citizen_flag']?'checked':'' ?>> Senior Citizen</label>
                    <label class="checkbox-label"><input type="checkbox" name="solo_parent_flag" value="1" <?= $resident['solo_parent_flag']?'checked':'' ?>> Solo Parent</label>
                </div>
            </div>
            <div class="form-actions">
                <a href="<?= $appConfig['base_url'] ?>/residents/profile/<?= $resident['resident_id'] ?>" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Update Resident</button>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
