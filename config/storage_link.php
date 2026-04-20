<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public storage link setup token
    |--------------------------------------------------------------------------
    |
    | Set a long random value, run once: /create-storage-link.php?token=...
    | then clear this env value and optionally delete the public script.
    |
    */

    'token' => env('STORAGE_LINK_TOKEN'),

];
