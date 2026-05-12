<?php
global $appConfig;
$fullName = $doc['first_name'] . ' ' . ($doc['middle_name'] ? $doc['middle_name'][0] . '. ' : '') . $doc['last_name'] . ($doc['suffix'] ? ' ' . $doc['suffix'] : '');
$age = $doc['date_of_birth'] ? floor((time() - strtotime($doc['date_of_birth'])) / 31557600) : '___';
$address = $doc['household_address'] ?? $doc['purok_sitio'] ?? '____________________';
$issuedDate = date('F d, Y', strtotime($doc['issued_at']));
$day = date('jS', strtotime($doc['issued_at']));
$monthYear = date('F, Y', strtotime($doc['issued_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Clearance - <?= htmlspecialchars($fullName) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Old+Standard+TT:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 15mm 20mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Old Standard TT', Georgia, serif; font-size: 13pt; color: #1a1a1a; background: #f5f5f5; }
        .print-page { width: 210mm; min-height: 297mm; margin: 0 auto; background: white; padding: 15mm 20mm; position: relative; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .doc-header { text-align: center; margin-bottom: 10mm; border-bottom: 3px double #1a237e; padding-bottom: 8mm; }
        .doc-header .republic { font-size: 11pt; letter-spacing: 2px; color: #444; }
        .doc-header .province { font-size: 12pt; font-weight: 600; }
        .doc-header .barangay-name { font-size: 18pt; font-weight: 700; color: #1a237e; text-transform: uppercase; letter-spacing: 3px; margin: 4mm 0; }
        .doc-header .office { font-size: 11pt; font-style: italic; color: #555; }
        .seal-placeholder { width: 25mm; height: 25mm; border: 2px dashed #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 4mm; font-size: 8pt; color: #999; font-family: 'Inter', sans-serif; }
        .doc-title { text-align: center; margin: 10mm 0 8mm; }
        .doc-title h1 { font-size: 22pt; text-transform: uppercase; letter-spacing: 5px; color: #1a237e; border-bottom: 2px solid #1a237e; display: inline-block; padding-bottom: 2mm; }
        .control-line { text-align: right; font-family: 'Inter', sans-serif; font-size: 10pt; color: #666; margin-bottom: 6mm; }
        .doc-body { line-height: 2; text-align: justify; margin-bottom: 10mm; }
        .doc-body .to-whom { font-weight: 700; margin-bottom: 4mm; }
        .doc-body .indent { text-indent: 12mm; }
        .highlight-name { text-transform: uppercase; font-weight: 700; text-decoration: underline; }
        .purpose-box { border: 1px solid #ccc; padding: 3mm 5mm; display: inline-block; font-weight: 600; margin: 2mm 0; }
        .sig-block { margin-top: 20mm; }
        .sig-line { text-align: center; width: 70mm; margin-left: auto; }
        .sig-line .name { font-weight: 700; font-size: 14pt; text-transform: uppercase; border-bottom: 1px solid #333; padding-bottom: 1mm; }
        .sig-line .title { font-size: 10pt; color: #555; margin-top: 1mm; }
        .footer-line { position: absolute; bottom: 15mm; left: 20mm; right: 20mm; border-top: 1px solid #ddd; padding-top: 3mm; display: flex; justify-content: space-between; font-family: 'Inter', sans-serif; font-size: 8pt; color: #999; }
        .no-print { font-family: 'Inter', sans-serif; text-align: center; padding: 10px; background: #1a237e; color: white; }
        .no-print button { background: #ffc107; color: #1a1a1a; border: none; padding: 8px 24px; border-radius: 4px; font-weight: 600; cursor: pointer; margin: 0 5px; font-size: 11pt; }
        @media print { .no-print { display: none; } body { background: white; } .print-page { box-shadow: none; margin: 0; padding: 12mm 18mm; } }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Document</button>
        <button onclick="window.close()">✕ Close</button>
    </div>
    <div class="print-page">
        <div class="doc-header">
            <p class="republic">Republic of the Philippines</p>
            <p class="province">Province of Nueva Ecija</p>
            <p class="province">City of Cabanatuan</p>
            <div class="seal-placeholder">[BARANGAY SEAL]</div>
            <p class="barangay-name">Barangay Sample</p>
            <p class="office">Office of the Punong Barangay</p>
        </div>

        <div class="control-line">
            Control No.: <strong><?= htmlspecialchars($doc['control_number']) ?></strong><br>
            O.R. No.: <?= htmlspecialchars($doc['or_number'] ?? '________') ?>
        </div>

        <div class="doc-title"><h1>Barangay Clearance</h1></div>

        <div class="doc-body">
            <p class="to-whom">TO WHOM IT MAY CONCERN:</p>
            <p class="indent">
                This is to certify that <span class="highlight-name"><?= htmlspecialchars($fullName) ?></span>,
                <?= $age ?> years old, <?= htmlspecialchars($doc['civil_status'] ?? '________') ?>,
                Filipino, and a bonafide resident of <strong><?= htmlspecialchars($address) ?></strong>,
                Barangay Sample, City of Cabanatuan, Province of Nueva Ecija.
            </p>
            <p class="indent">
                This further certifies that the above-named person has <strong>NO DEROGATORY RECORD</strong> 
                filed in this barangay as of this date.
            </p>
            <p class="indent">
                This clearance is being issued upon the request of the above-named person for 
                <span class="purpose-box"><?= htmlspecialchars($doc['purpose'] ?: 'WHATEVER LEGAL PURPOSE IT MAY SERVE') ?></span>.
            </p>
            <p class="indent">
                Issued this <strong><?= $day ?></strong> day of <strong><?= $monthYear ?></strong> at 
                Barangay Sample, City of Cabanatuan, Province of Nueva Ecija.
            </p>
        </div>

        <div class="sig-block">
            <div class="sig-line">
                <div class="name">[PUNONG BARANGAY NAME]</div>
                <div class="title">Punong Barangay</div>
            </div>
        </div>

        <div class="footer-line">
            <span>Control#: <?= htmlspecialchars($doc['control_number']) ?></span>
            <span>Issued: <?= $issuedDate ?></span>
            <span>Valid for 6 months from date of issuance</span>
        </div>
    </div>
</body>
</html>
