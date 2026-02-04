<?php

return [
    'access_token' => env('MP_ACCESS_TOKEN'),
    'public_key'   => env('MP_PUBLIC_KEY'),
    'sandbox'      => env('MP_SANDBOX', true),
    'currency'     => env('MP_CURRENCY', 'BRL'),
];
