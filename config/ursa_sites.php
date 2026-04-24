<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Sister sites (subdomains / related apps)
    |--------------------------------------------------------------------------
    | Shown on the home grid before game cards. Local uses Herd .test hosts;
    | production uses live subdomains.
    */
    'sister_sites' => [
        [
            'key' => 'f1',
            'variant' => 'f1',
            'title' => 'ui.sister_f1_title',
            'blurb' => 'ui.sister_f1_blurb',
            'local' => 'http://formula1predictions.test',
            'production' => 'https://f1.ursaminor.games',
        ],
        [
            'key' => 'portal',
            'variant' => 'portal',
            'title' => 'ui.sister_portal_title',
            'blurb' => 'ui.sister_portal_blurb',
            'local' => 'http://website.test',
            'production' => 'https://ursaminor.games',
        ],
        [
            'key' => 'taverns',
            'variant' => 'taverns',
            'title' => 'ui.sister_taverns_title',
            'blurb' => 'ui.sister_taverns_blurb',
            'local' => 'http://tavernsandtreasures.test',
            'production' => 'https://taverns.ursaminor.games',
        ],
    ],
];
