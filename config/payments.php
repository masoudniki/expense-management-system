<?php

use App\Services\Payment\Gateways\Keshavarzi;

return [

        /*
        |--------------------------------------------------------------------------
        | Payments
        |--------------------------------------------------------------------------
        | Here we are going to put the available payment methods
        | via different APIs
        |
        */

        'gateways'=>[
            'keshavarzi'=>[
                'class'=> Keshavarzi::class,
                'config'=>[
                    'token' => 'some_secret'
                ],
                'iban_prefix'=>11,
            ],
            'meli'=>[
                'class'=> Keshavarzi::class,
                'config'=>[
                    'public_key' => 'some_public_key',
                    'private_key' => 'some_private_key',
                    'password' => 'some_password',
                ],
                'iban_prefix'=>22,
            ],
            'saderat'=>[
                'class'=> Keshavarzi::class,
                'config'=>[
                    'username'=> 'username',
                    'password'=> 'password',
                    'secret'=> 'some_secret'
                ],
                'iban_prefix'=>33,
            ]
        ]
];




