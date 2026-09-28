<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Transaction Handler
    |--------------------------------------------------------------------------
    |
    | Maatwebsite Excel resolves this when the Excel facade is created.
    | PHP null is not a driver and throws "Unable to resolve NULL driver".
    | Supported handlers: "db", "null".
    |
    */
    'transactions' => [
        'handler' => 'db',
        'db' => [
            'connection' => null,
        ],
    ],
];
