<?php

return [
    'db' => [
        'driver' => 'Pgsql',
        'adapters' => [
            'portal_db' => [
                'driver' => 'Pgsql',
                'host' => 'portal_postgres',
                'username' => 'postgres',
                'dbname' => 'login_portal',
            ],
        ],
    ],
];
