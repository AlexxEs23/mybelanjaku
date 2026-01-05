<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Client\Response;

class SupabaseService
{
    protected $url;
    protected $key;
    protected $storageBucket;

    public function __construct()
    {
        $this->url = config('supabase.url', env('SUPABASE_URL'));
        $this->key = config('supabase.key', env('SUPABASE_KEY'));
        $this->storageBucket = config('supabase.storage.bucket', 'product-images');
    }

    /**
     * Upload file ke Supabase Storage
     * 
     * @param UploadedFile $file
     * @param string $folder
     * @return array ['success' => bool, 'path' => string, 'url' => string]
     */
    public function uploadFile(UploadedFile $file, $folder = 'produk')
    {
        try {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $folder . '/' . $filename;

            // Read file content
            $fileContent = file_get_contents($file->getRealPath());
            $contentType = $file->getMimeType();

            // Upload ke Supabase Storage - use asMultipart() for proper file upload
            /** @var Response $response */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
            ])->asMultipart()
              ->attach('file', $fileContent, $filename)
              ->post("{$this->url}/storage/v1/object/{$this->storageBucket}/{$path}");

            if ($response->successful()) {
                $publicUrl = "{$this->url}/storage/v1/object/public/{$this->storageBucket}/{$path}";
                
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $publicUrl,
                    'message' => 'File berhasil diupload ke Supabase'
                ];
            }

            Log::error('Supabase upload failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal upload ke Supabase: ' . $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Supabase upload exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete file dari Supabase Storage
     * 
     * @param string $path
     * @return array ['success' => bool, 'message' => string]
     */
    public function deleteFile($path)
    {
        try {
            if (empty($path)) {
                return ['success' => true, 'message' => 'No file to delete'];
            }

            /** @var Response $response */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
            ])->delete("{$this->url}/storage/v1/object/{$this->storageBucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'File berhasil dihapus dari Supabase'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal hapus dari Supabase: ' . $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Supabase delete exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get public URL untuk file
     * 
     * @param string $path
     * @return string
     */
    public function getPublicUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        return "{$this->url}/storage/v1/object/public/{$this->storageBucket}/{$path}";
    }

    /**
     * Check if file exists
     * 
     * @param string $path
     * @return bool
     */
    public function fileExists($path)
    {
        try {
            /** @var Response $response */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->key,
            ])->head("{$this->url}/storage/v1/object/public/{$this->storageBucket}/{$path}");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
