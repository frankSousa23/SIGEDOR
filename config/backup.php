<?php

return [
    'backup' => [
        'name' => env('APP_NAME', 'SIGEDOR'),
        'source' => [
            'files' => [
                base_path(),
                storage_path('app'),
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
