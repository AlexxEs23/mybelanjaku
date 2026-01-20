<?php

require __DIR__.'/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\FirebaseService;

echo "🔥 Testing Firebase Service with Service Account JSON...\n\n";

// Check if service account file exists
$serviceAccountPath = __DIR__ . '/ecommerceumkm-4dbc3-firebase-adminsdk-fbsvc-8fe7f35302.json';
if (!file_exists($serviceAccountPath)) {
    die("❌ Service account file not found!\n");
}

echo "✅ Service account file found\n";

$serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
echo "✅ Project ID: " . $serviceAccount['project_id'] . "\n";
echo "✅ Client Email: " . $serviceAccount['client_email'] . "\n\n";

// Test Firebase Service
echo "Testing notification send...\n";

$firebase = new FirebaseService();

// Replace with actual FCM token from your database
$fcmToken = 'PASTE_YOUR_FCM_TOKEN_HERE'; // Get from users table

if ($fcmToken === 'PASTE_YOUR_FCM_TOKEN_HERE') {
    echo "\n⚠️  Please update \$fcmToken with actual token from database:\n";
    echo "   SELECT fcm_token FROM users WHERE fcm_token IS NOT NULL LIMIT 1;\n\n";
    exit(0);
}

$result = $firebase->sendNotification(
    $fcmToken,
    'Test Notifikasi',
    'Ini adalah test notifikasi dari Firebase Admin SDK',
    [
        'tipe' => 'test',
        'link' => url('/')
    ]
);

echo "\n📊 Result:\n";
print_r($result);

if ($result['success']) {
    echo "\n✅ Notification sent successfully!\n";
    echo "Check your Windows notification! 🎉\n";
} else {
    echo "\n❌ Failed to send notification:\n";
    echo $result['message'] . "\n";
}
