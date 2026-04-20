<?php

declare(strict_types=1);

/**
 * One-time helper: creates the public/storage → storage/app/public symlink (same as `php artisan storage:link`).
 *
 * 1. Set STORAGE_LINK_TOKEN in .env to a long random string.
 * 2. Visit: https://your-domain.com/create-storage-link.php?token=THAT_STRING
 * 3. Clear STORAGE_LINK_TOKEN from .env and delete this file on production when done.
 */

require __DIR__.'/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$configured = config('storage_link.token');
$configured = is_string($configured) ? $configured : '';
$given = isset($_GET['token']) && is_string($_GET['token']) ? $_GET['token'] : '';

header('Content-Type: text/plain; charset=UTF-8');

if ($configured === '') {
    http_response_code(503);
    echo "STORAGE_LINK_TOKEN haijawekwa kwenye .env.\n";

    exit;
}

if (! hash_equals($configured, $given)) {
    http_response_code(403);
    echo "Forbidden.\n";

    exit;
}

try {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    $output = trim(\Illuminate\Support\Facades\Artisan::output());
    echo "Imefanikiwa.\n";
    if ($output !== '') {
        echo $output."\n";
    }
    echo "Futa STORAGE_LINK_TOKEN kwenye .env na uondoe faili hii ukiisha.\n";
} catch (\Throwable $e) {
    http_response_code(500);
    echo 'Hitilafu: '.$e->getMessage()."\n";
}
