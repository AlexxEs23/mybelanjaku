<?php

use Illuminate\Support\Facades\Route;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;

Route::get('/test-supabase', function () {
    $supabase = new SupabaseService();
    
    $results = [];
    
    // Test 1: Check Configuration
    $results['config'] = [
        'url' => config('supabase.url'),
        'bucket' => config('supabase.storage.bucket'),
        'key_exists' => !empty(config('supabase.key')),
        'key_length' => strlen(config('supabase.key')),
    ];
    
    // Test 2: Check Environment
    $results['env'] = [
        'SUPABASE_URL' => env('SUPABASE_URL'),
        'SUPABASE_STORAGE_BUCKET' => env('SUPABASE_STORAGE_BUCKET'),
        'SUPABASE_KEY_SET' => !empty(env('SUPABASE_KEY')),
    ];
    
    // Test 3: Test API Connection
    try {
        /** @var Response $response */
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('supabase.key'),
        ])->get(config('supabase.url') . '/storage/v1/bucket');
        
        $results['api_test'] = [
            'status' => $response->status(),
            'success' => $response->successful(),
            'body' => $response->json(),
        ];
    } catch (\Exception $e) {
        $results['api_test'] = [
            'error' => $e->getMessage(),
        ];
    }
    
    // Test 4: Check if bucket exists
    try {
        /** @var Response $response */
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('supabase.key'),
        ])->get(config('supabase.url') . '/storage/v1/bucket/' . config('supabase.storage.bucket'));
        
        $results['bucket_check'] = [
            'status' => $response->status(),
            'exists' => $response->successful(),
            'data' => $response->json(),
        ];
    } catch (\Exception $e) {
        $results['bucket_check'] = [
            'error' => $e->getMessage(),
        ];
    }
    
    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});
