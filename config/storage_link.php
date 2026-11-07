<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public storage link setup token
    |--------------------------------------------------------------------------
    |
    | Set a long random value, then run: php artisan storage:link
    | then clear this env value and optionally delete the public script.
    |
    */

    'token' => env('STORAGE_LINK_TOKEN'),

];
