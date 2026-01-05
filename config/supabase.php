<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration untuk integrasi Supabase dengan Laravel
    |
    */

    'url' => env('SUPABASE_URL', ''),
    'key' => env('SUPABASE_KEY', ''),
    
    'storage' => [
        'bucket' => env('SUPABASE_STORAGE_BUCKET', 'product-images'),
        'public_url_prefix' => env('SUPABASE_URL', '') . '/storage/v1/object/public/',
    ],
];
