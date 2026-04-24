<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard opcional para sesiones web del personal administrativo
        'personal_administrativo' => [
            'driver' => 'session',
            'provider' => 'personal_administrativo',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        // Clientes normales
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // Administrador / despacho
        'personal_administrativo' => [
            'driver' => 'eloquent',
            'model' => App\Models\PersonalAdministrativo::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        // Recuperación de contraseña para clientes normales
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        // Recuperación de contraseña para administrador / despacho
        'personal_administrativo' => [
            'provider' => 'personal_administrativo',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];