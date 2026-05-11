<?php

$app = require_once __DIR__ . '/../bootstrap/app.php';
if (!file_exists(__DIR__ . '/../public/storage')) {
    symlink(__DIR__ . '/../storage/app/public', __DIR__ . '/../public/storage');
}

require __DIR__ . '/../public/index.php';