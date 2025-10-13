<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Lowercase Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, all text on the site will be rendered in lowercase via
    | CSS text-transform, regardless of the actual case in i18n strings.
    |
    | Set URSA_LOWERCASE_MODE=true in .env to enable.
    |
    */

    'lowercase_mode' => env('URSA_LOWERCASE_MODE', false),

];
