<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $serviceAccountPath;
    protected $accessToken;

    public function __construct()
    {
        // Path ke file service account JSON
        $this->serviceAccountPath = base_path('ecommerceumkm-4dbc3-firebase-adminsdk-fbsvc-8fe7f35302.json');
    }

    /**
     * Get OAuth2 Access Token from Service Account
     */
    private function getAccessToken()
    {
        if (!file_exists($this->serviceAccountPath)) {
            Log::error('Firebase service account file not found: ' . $this->serviceAccountPath);
            return null;
        }

        try {
            $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
            
            // Create JWT
            $now = time();
            $header = [
                'alg' => 'RS256',
                'typ' => 'JWT'
            ];
            
            $payload = [
                'iss' => $serviceAccount['client_email'],
                'sub' => $serviceAccount['client_email'],
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging'
            ];
            
            $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
            $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
            
            $signatureInput = $base64UrlHeader . '.' . $base64UrlPayload;
            
            // Sign with private key
            $privateKey = openssl_pkey_get_private($serviceAccount['private_key']);
            openssl_sign($signatureInput, $signature, $privateKey, OPENSSL_ALGO_SHA256);
            openssl_free_key($privateKey);
            
            $base64UrlSignature = $this->base64UrlEncode($signature);
            $jwt = $signatureInput . '.' . $base64UrlSignature;
            
            // Exchange JWT for access token
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['access_token'];
            }
            
            Log::error('Failed to get Firebase access token', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Firebase access token exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Base64 URL encode
     */
    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * Send push notification via Firebase Cloud Messaging (FCM v1)
     * 
     * @param string $fcmToken
     * @param string $title
     * @param string $body
     * @param array $data Additional data to send
     * @return array
     */
    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        if (empty($fcmToken)) {
            Log::warning('FCM Token is empty');
            return [
                'success' => false,
                'message' => 'FCM Token is empty'
            ];
        }

        try {
            // Get service account details
            $serviceAccount = json_decode(file_get_contents($this->serviceAccountPath), true);
            $projectId = $serviceAccount['project_id'];
            
            // Get access token
            $accessToken = $this->getAccessToken();
            if (!$accessToken) {
                return [
                    'success' => false,
                    'message' => 'Failed to get Firebase access token'
                ];
            }
            
            // FCM v1 API endpoint
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
            
            // Prepare message
            $message = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data,
                    'webpush' => [
                        'notification' => [
                            'icon' => url('/favicon.ico'),
                            'click_action' => $data['link'] ?? url('/')
                        ]
                    ]
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($url, $message);

            if ($response->successful()) {
                Log::info('Firebase notification sent successfully', [
                    'fcm_token' => substr($fcmToken, 0, 20) . '...',
                    'title' => $title
                ]);

                return [
                    'success' => true,
                    'message' => 'Notification sent successfully',
                    'response' => $response->json()
                ];
            }

            Log::error('Firebase notification failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send notification: ' . $response->body()
            ];

        } catch (\Exception $e) {
            Log::error('Firebase notification exception', [
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
     * Send notification to multiple tokens
     * 
     * @param array $fcmTokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendMultipleNotifications(array $fcmTokens, $title, $body, $data = [])
    {
        if (empty($fcmTokens)) {
            return [
                'success' => false,
                'message' => 'No FCM tokens provided'
            ];
        }

        $results = [];
        $successCount = 0;
        $failureCount = 0;

        foreach ($fcmTokens as $token) {
            $result = $this->sendNotification($token, $title, $body, $data);
            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
            }
            $results[] = $result;
        }

        Log::info('Firebase multiple notifications sent', [
            'total' => count($fcmTokens),
            'success' => $successCount,
            'failure' => $failureCount
        ]);

        return [
            'success' => $successCount > 0,
            'message' => "Sent {$successCount} of " . count($fcmTokens) . " notifications",
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'results' => $results
        ];
    }
}
