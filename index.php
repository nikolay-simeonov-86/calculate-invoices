#!/usr/bin/php
<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use \App\Controllers\Console\StartCliApp;

$app = new StartCliApp();
$app->calculateInvoices($argv);