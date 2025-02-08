<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hashing algorithm that should be used.
    | By default, Bcrypt is used for hashing.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'default' => 'bcrypt',

    'drivers' => [
        'bcrypt' => [
            'driver' => 'bcrypt',
            'rounds' => env('BCRYPT_ROUNDS', 10),
        ],

        'argon' => [
            'driver' => 'argon',
            'memory' => 65536,
            'threads' => 1,
            'time' => 4,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Cost Factor
    |--------------------------------------------------------------------------
    |
    | Here you may configure the cost factor of the Bcrypt hashing algorithm.
    | This is a numerical value that represents the complexity of the algorithm.
    |
    */

    'bcrypt' => [
        'rounds' => env('BCRYPT_ROUNDS', 12),
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Cost Factor
    |--------------------------------------------------------------------------
    |
    | Here you may configure the cost factor of the Argon hashing algorithm.
    | This is a numerical value that represents the memory and time complexity.
    |
    */

    'argon' => [
        'memory' => 1024,
        'threads' => 2,
        'time' => 2,
    ],

];
