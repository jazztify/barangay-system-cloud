<?php $pageTitle = 'Add New Resident'; include __DIR__ . '/../layout/header.php'; ?>

<div class="card">
    <div class="card-header"><h3><i class='bx bx-user-plus'></i> New Resident (Form B)</h3></div>
    <div class="card-body">
        <form method="POST" class="form-grid">
            <div class="form-section">
                <h4>Personal Information</h4>
                <div class="form-row">
                    <div class="form-group"><label>Last Name *</label><input type="text" name="last_name" required></div>
                    <div class="form-group"><label>First Name *</label><input type="text" name="first_name" required></div>
                    <div class="form-group"><label>Middle Name</label><input type="text" name="middle_name"></div>
                    <div class="form-group"><label>Suffix</label><input type="text" name="suffix" placeholder="Jr., Sr., III"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>Date of Birth</label><input type="date" name="date_of_birth"></div>
                    <div class="form-group"><label>Sex</label><select name="sex" class="form-select"><option value="">Select</option><option value="Male">Male</option><option value="Female">Female</option></select></div>
                    <div class="form-group"><label>Civil Status</label><select name="civil_status" class="form-select"><option value="">Select</option><option>Single</option><option>Married</option><option>Widowed</option><option>Separated</option><option>Divorced</option></select></div>
                    <div class="form-group"><label>Nationality</label><input type="text" name="nationality" value="Filipino"></div>
                </div>
            </div>

            <div class="form-section">
                <h4>Additional Details</h4>
                <div class="form-row">
                    <div class="form-group"><label>Religion</label><input type="text" name="religion"></div>
                    <div class="form-group"><label>Occupation</label><input type="text" name="occupation"></div>
                    <div class="form-group"><label>Educational Attainment</label><select name="educational_attainment" class="form-select"><option value="">Select</option><option>Elementary Level</option><option>Elementary Graduate</option><option>High School Level</option><option>High School Graduate</option><option>College Level</option><option>College Graduate</option><option>Vocational</option><option>Post Graduate</option><option>None</option></select></div>
                    <div class="form-group"><label>Contact Number</label><input type="text" name="contact_number" placeholder="09XX-XXX-XXXX"></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label>PhilSys Card No. (PCN)</label><input type="text" name="philsys_card_no" placeholder="Optional"></div>
                    <div class="form-group"><label>Household</label><select name="household_id" class="form-select"><option value="">-- None --</option><?php foreach ($households as $h): ?><option value="<?= $h['household_id'] ?>"><?= htmlspecialchars($h['household_no'] . ' - ' . $h['purok_sitio']) ?></option><?php endforeach; ?></select></div>
                </div>
            </div>

            <div class="form-section">
                <h4>Sector Flags</h4>
                <div class="form-row checkbox-row">
                    <label class="checkbox-label"><input type="checkbox" name="voter_status" value="1"> Registered Voter</label>
                    <label class="checkbox-label"><input type="checkbox" name="pwd_flag" value="1"> Person with Disability (PWD)</label>
                    <label class="checkbox-label"><input type="checkbox" name="senior_citizen_flag" value="1"> Senior Citizen</label>
                    <label class="checkbox-label"><input type="checkbox" name="solo_parent_flag" value="1"> Solo Parent</label>
                </div>
            </div>

            <div class="form-actions">
                <a href="<?= $appConfig['base_url'] ?>/residents" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary"><i class='bx bx-save'></i> Save Resident</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
