<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Early renewal window (days before expiry)
    |--------------------------------------------------------------------------
    |
    | Winga may pay for the same plan again only when the current period is
    | within this many days of expiring. Each payment extends expires_at by
    | the plan's duration_days from the previous expiry date.
    |
    */
    'renewal_days_before_expiry' => (int) env('SUBSCRIPTION_RENEWAL_DAYS_BEFORE_EXPIRY', 7),

];
