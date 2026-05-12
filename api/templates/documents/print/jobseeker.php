<?php
global $appConfig;
$fullName = $doc['first_name'] . ' ' . ($doc['middle_name'] ? $doc['middle_name'][0] . '. ' : '') . $doc['last_name'] . ($doc['suffix'] ? ' ' . $doc['suffix'] : '');
$age = $doc['date_of_birth'] ? floor((time() - strtotime($doc['date_of_birth'])) / 31557600) : '___';
$address = $doc['household_address'] ?? $doc['purok_sitio'] ?? '____________________';
$issuedDate = date('F d, Y', strtotime($doc['issued_at']));
$day = date('jS', strtotime($doc['issued_at']));
$monthYear = date('F, Y', strtotime($doc['issued_at']));
$oathDate = !empty($additionalData['oath_date']) ? date('F d, Y', strtotime($additionalData['oath_date'])) : $issuedDate;
$expiryDate = $doc['valid_until'] ? date('F d, Y', strtotime($doc['valid_until'])) : date('F d, Y', strtotime('+1 year'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>First-Time Job Seeker - <?= htmlspecialchars($fullName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 15mm 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Old Standard TT', Georgia, serif; font-size: 12pt; color: #1a1a1a; background: #f5f5f5; }
        .print-page { width: 210mm; min-height: 297mm; margin: 0 auto; background: white; padding: 15mm 20mm; position: relative; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .doc-header { text-align: center; margin-bottom: 8mm; border-bottom: 3px double #b71c1c; padding-bottom: 6mm; }
        .doc-header .republic { font-size: 11pt; letter-spacing: 2px; color: #444; }
        .doc-header .province { font-size: 12pt; font-weight: 600; }
        .doc-header .barangay-name { font-size: 18pt; font-weight: 700; color: #b71c1c; text-transform: uppercase; letter-spacing: 3px; margin: 3mm 0; }
        .doc-header .office { font-size: 11pt; font-style: italic; color: #555; }
        .seal-placeholder { width: 22mm; height: 22mm; border: 2px dashed #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 3mm; font-size: 8pt; color: #999; }
        .doc-title { text-align: center; margin: 6mm 0; }
        .doc-title h1 { font-size: 16pt; text-transform: uppercase; letter-spacing: 3px; color: #b71c1c; border-bottom: 2px solid #b71c1c; display: inline-block; padding-bottom: 2mm; }
        .doc-title .ra-ref { font-size: 10pt; color: #666; margin-top: 2mm; font-style: italic; }
        .control-line { text-align: right; font-size: 10pt; color: #666; margin-bottom: 4mm; }
        .doc-body { line-height: 1.9; text-align: justify; margin-bottom: 6mm; font-size: 11.5pt; }
        .doc-body .to-whom { font-weight: 700; margin-bottom: 3mm; }
        .doc-body .indent { text-indent: 12mm; }
        .highlight-name { text-transform: uppercase; font-weight: 700; text-decoration: underline; }
        .oath-section { border: 1px solid #ddd; padding: 5mm; margin: 6mm 0; background: #fafafa; }
        .oath-section h4 { margin-bottom: 3mm; color: #b71c1c; }
        .oath-section p { font-size: 11pt; line-height: 1.8; }
        .sig-grid { display: flex; justify-content: space-between; margin-top: 15mm; }
        .sig-line { text-align: center; width: 65mm; }
        .sig-line .name { font-weight: 700; font-size: 12pt; text-transform: uppercase; border-bottom: 1px solid #333; padding-bottom: 1mm; }
        .sig-line .title { font-size: 9pt; color: #555; margin-top: 1mm; }
        .validity-box { border: 2px solid #b71c1c; padding: 3mm 5mm; text-align: center; margin: 5mm 0; font-weight: 700; color: #b71c1c; font-size: 11pt; }
        .footer-line { position: absolute; bottom: 12mm; left: 20mm; right: 20mm; border-top: 1px solid #ddd; padding-top: 2mm; display: flex; justify-content: space-between; font-size: 8pt; color: #999; }
        .no-print { text-align: center; padding: 10px; background: #b71c1c; color: white; }
        .no-print button { background: #ffc107; color: #1a1a1a; border: none; padding: 8px 24px; border-radius: 4px; font-weight: 600; cursor: pointer; margin: 0 5px; }
        @media print { .no-print { display: none; } body { background: white; } .print-page { box-shadow: none; margin: 0; } }
    </style>
</head>
<body>
    <div class="no-print"><button onclick="window.print()">🖨️ Print</button><button onclick="window.close()">✕ Close</button></div>
    <div class="print-page">
        <div class="doc-header">
            <p class="republic">Republic of the Philippines</p><p class="province">Province of Nueva Ecija</p><p class="province">City of Cabanatuan</p>
            <div class="seal-placeholder">[BARANGAY SEAL]</div>
            <p class="barangay-name">Barangay Sample</p><p class="office">Office of the Punong Barangay</p>
        </div>
        <div class="control-line">Control No.: <strong><?= htmlspecialchars($doc['control_number']) ?></strong></div>
        <div class="doc-title">
            <h1>First-Time Jobseekers Certification</h1>
            <p class="ra-ref">Pursuant to Republic Act No. 11261 (First-Time Jobseekers Assistance Act)</p>
        </div>
        <div class="doc-body">
            <p class="to-whom">TO WHOM IT MAY CONCERN:</p>
            <p class="indent">This is to certify that <span class="highlight-name"><?= htmlspecialchars($fullName) ?></span>, <?= $age ?> years old, <?= htmlspecialchars($doc['civil_status'] ?? '') ?>, Filipino, a bonafide resident of <strong><?= htmlspecialchars($address) ?></strong>, Barangay Sample, City of Cabanatuan, Province of Nueva Ecija, has personally appeared before this office and has executed an oath/undertaking that he/she is a FIRST-TIME JOBSEEKER.</p>
            <p class="indent">This further certifies that the above-named person has availed of the benefits under R.A. 11261 for the first time and has not previously enjoyed the same benefit from any barangay.</p>
        </div>

        <div class="oath-section">
            <h4>OATH/UNDERTAKING</h4>
            <p>I, <strong><?= htmlspecialchars(strtoupper($fullName)) ?></strong>, do solemnly swear that I am a first-time jobseeker as defined under R.A. 11261; that I have not previously availed of the benefits under this Act; and that the documents I will request are to be used solely for my employment application.</p>
            <p style="margin-top:3mm">Oath Date: <strong><?= $oathDate ?></strong></p>
        </div>

        <div class="validity-box">⚠ VALID UNTIL: <?= $expiryDate ?> (ONE YEAR FROM ISSUANCE)</div>

        <div class="sig-grid">
            <div class="sig-line"><div class="name"><?= htmlspecialchars(strtoupper($fullName)) ?></div><div class="title">Applicant / First-Time Jobseeker</div></div>
            <div class="sig-line"><div class="name">[PUNONG BARANGAY NAME]</div><div class="title">Punong Barangay / Administering Officer</div></div>
        </div>

        <div class="footer-line">
            <span>Control#: <?= htmlspecialchars($doc['control_number']) ?></span>
            <span>Issued: <?= $issuedDate ?></span>
            <span>Expires: <?= $expiryDate ?></span>
        </div>
    </div>
</body>
</html>
