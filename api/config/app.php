<?php
return [
    'app_name' => 'Barangay Information System',
    'timezone' => 'Asia/Manila',
    'secret_key' => getenv('APP_SECRET_KEY') ?: 'super_secret_hmac_key_for_barangay_verification',
    'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost:8080'
];
