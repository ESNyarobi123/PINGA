<?php

declare(strict_types=1);

/**
 * One-time helper: creates the public/storage → storage/app/public symlink (same as `php artisan storage:link`).
 *
 * 1. Set STORAGE_LINK_TOKEN in .env to a long random string.
 * 2. Visit: https://your-domain.com/create-storage-link.php?token=THAT_STRING
 * 3. Clear STORAGE_LINK_TOKEN from .env and delete this file on production when done.
 *
 * Note: Some file managers show a symlink as a "folder" icon — that is normal.
 * If uploads still 404, check the web server allows following symlinks (Apache FollowSymLinks).
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

$link = public_path('storage');
$target = storage_path('app/public');

// Laravel's storage:link does NOT replace a normal directory — only symlinks (with --force).
// Deploy zips often leave an empty `public/storage` folder; that must be removed first.
if (file_exists($link) && ! is_link($link)) {
    if (! is_dir($link)) {
        http_response_code(409);
        echo "public/storage ipo lakini si folda wala symlink. Futa kisha jaribu tena.\n";

        exit;
    }

    $entries = array_diff(scandir($link) ?: [], ['.', '..']);
    if ($entries !== []) {
        http_response_code(409);
        echo "public/storage ni folda HALISI yenye maudhui (sio symlink). Futa folda hiyo au nenda nayo kisha jaribu tena.\n";
        echo 'Vifungu vilivyopo: '.implode(', ', array_slice($entries, 0, 10)).(count($entries) > 10 ? ', ...' : '')."\n";

        exit;
    }

    if (! @rmdir($link)) {
        http_response_code(500);
        echo "Imeshindwa kuondoa public/storage tupu. Futa mwenyewe kupitia FTP/cPanel kisha jaribu tena.\n";

        exit;
    }

    echo "Folda tupu public/storage imeondolewa ili symlink iwezekane.\n";
}

if (! is_dir($target)) {
    http_response_code(500);
    echo "Hakuna folda ya lengo: {$target}\nHakikisha storage/app/public ipo (unda kwa mkdir ikiwa inahitajika).\n";

    exit;
}

try {
    \Illuminate\Support\Facades\Artisan::call('storage:link', ['--force' => true]);
    $output = trim(\Illuminate\Support\Facades\Artisan::output());
    echo "Imefanikiwa.\n";
    if ($output !== '') {
        echo $output."\n";
    }

    if (is_link($link)) {
        $resolved = @readlink($link) ?: '';
        echo "\nUhakiki: public/storage ni SYMLINK ✓\n";
        if ($resolved !== '') {
            echo 'Inaelekea: '.$resolved."\n";
        }
    } elseif (is_dir($link)) {
        echo "\nONYO: public/storage bado ni folda halisi, sio symlink. Angalia ruhusa za mfumo au hosting.\n";
    }

    echo "\nFuta STORAGE_LINK_TOKEN kwenye .env na uondoe faili hii ukiisha.\n";
} catch (\Throwable $e) {
    http_response_code(500);
    echo 'Hitilafu: '.$e->getMessage()."\n";
}
