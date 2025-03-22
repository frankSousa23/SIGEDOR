<?php

return [
    'backup' => [
        'name' => env('APP_NAME', 'SIGEDOR'),
        'source' => [
            'files' => [
                'include' => [
                    base_path(),
                ],

                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                    storage_path('app/backup-temp'),
                ],

                'follow_links' => false,

                'ignore_unreadable_directories' => false,

                'relative_path' => null,
            ],
            'databases' => [env('DB_CONNECTION', 'mysql')],
        ],
        'destination' => [
            'filename_prefix' => 'sigedor-',
            'disks' => ['local', 'backup_disk'],
        ],
    ],
    // ... resto de configuraciones
];
