<?php

declare(strict_types=1);

return [
    'orm' => [
        'default' => [
            'connection'    => [
                'dbname'   => $_ENV['PG_DB'],
                'user'     => $_ENV['PG_USER'],
                'password' => $_ENV['PG_PWD'],
                'host'     => $_ENV['PG_HOST'],
                'driver'   => 'pgsql',
            ],
            'configuration' => []
        ]
    ]
];
