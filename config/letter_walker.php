<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Leaderboard "today" timezone
    |--------------------------------------------------------------------------
    | Used for storing date_played and filtering "today's" scores. Default
    | Pacific/Auckland so the day rolls over at midnight NZ time (primary
    | audience). Override via LETTER_WALKER_LEADERBOARD_TIMEZONE in .env.
    */
    'leaderboard_timezone' => env('LETTER_WALKER_LEADERBOARD_TIMEZONE', 'Pacific/Auckland'),
];
