<?php

return [

   

    'defaults' => [
      
        'guard' => 'team', 
        'passwords' => 'teams',
    ],



    'guards' => [

      
        'team' => [
            'driver' => 'sanctum',
            'provider' => 'teams',
        ],

 
        'teacher' => [
            'driver' => 'sanctum',
            'provider' => 'teachers',
        ],

        // Puedes dejar web si quieres usar sesiones (panel admin, etc.)
        'web' => [
            'driver' => 'session',
            'provider' => 'teams', // O puedes crear un provider 'users' si quieres
        ],
    ],


    'providers' => [

     
        'teams' => [
            'driver' => 'eloquent',
            'model' => App\Models\Team::class,
        ],

       
        'teachers' => [
            'driver' => 'eloquent',
            'model' => App\Models\Teacher::class,
        ],

      
    ],

    'passwords' => [
        'teams' => [
            'provider' => 'teams',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'teachers' => [
            'provider' => 'teachers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],


    'password_timeout' => 10800,
];
