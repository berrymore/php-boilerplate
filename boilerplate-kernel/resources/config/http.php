<?php

declare(strict_types=1);

return [
    'http' => [
        'cors' => [
            'allowed_origins' => ['*'],
            'allowed_methods' => ['GET', 'POST', 'OPTIONS'],
            'allowed_headers' => ['Content-Type', 'Authorization'],
        ],
    ],
];
