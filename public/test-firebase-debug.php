<?php
// File untuk debug dan test Firebase notification
// Akses via browser: http://127.0.0.1:8000/test-firebase-debug.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\FirebaseService;
use App\Models\User;

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Firebase Debug Tool</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .info { color: #9cdcfe; }
        pre { background: #2d2d2d; padding: 15px; border-radius: 5px; overflow-x: auto; }
        button { background: #0e639c; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; }
        button:hover { background: #1177bb; }
        .step { margin: 20px 0; padding: 15px; background: #252526; border-left: 4px solid #0e639c; }
    </style>
</head>
<body>
    <h1>🔥 Firebase Push Notification - Debug Tool</h1>

<?php

echo "<div class='step'>";
echo "<h2>Step 1: Cek Service Account JSON</h2>";

$serviceAccountPath = __DIR__ . '/ecommerceumkm-4dbc3-firebase-adminsdk-fbsvc-8fe7f35302.json';

if (file_exists($serviceAccountPath)) {
    echo "<p class='success'>✅ File service account ditemukan!</p>";
    
    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
    echo "<pre>";
    echo "Project ID: <span class='info'>" . $serviceAccount['project_id'] . "</span>\n";
    echo "Client Email: <span class='info'>" . $serviceAccount['client_email'] . "</span>\n";
    echo "Private Key: <span class='success'>[TERSEDIA]</span>";
    echo "</pre>";
} else {
    echo "<p class='error'>❌ File service account TIDAK ditemukan!</p>";
    echo "<p>Path: <code>$serviceAccountPath</code></p>";
}
echo "</div>";

echo "<div class='step'>";
echo "<h2>Step 2: Cek Users dengan FCM Token</h2>";

$usersWithToken = User::whereNotNull('fcm_token')->get();

if ($usersWithToken->count() > 0) {
    echo "<p class='success'>✅ Ditemukan {$usersWithToken->count()} user dengan FCM token</p>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #2d2d2d;'>";
    echo "<th style='padding: 10px; text-align: left;'>ID</th>";
    echo "<th style='padding: 10px; text-align: left;'>Name</th>";
    echo "<th style='padding: 10px; text-align: left;'>Role</th>";
    echo "<th style='padding: 10px; text-align: left;'>FCM Token</th>";
    echo "<th style='padding: 10px; text-align: left;'>Action</th>";
    echo "</tr>";
    
    foreach ($usersWithToken as $user) {
        echo "<tr style='border-bottom: 1px solid #3e3e3e;'>";
        echo "<td style='padding: 10px;'>{$user->id}</td>";
        echo "<td style='padding: 10px;'>{$user->name}</td>";
        echo "<td style='padding: 10px;'><span class='info'>{$user->role}</span></td>";
        echo "<td style='padding: 10px;'><code>" . substr($user->fcm_token, 0, 30) . "...</code></td>";
        echo "<td style='padding: 10px;'>";
        echo "<a href='?test_user={$user->id}' style='color: #4ec9b0;'>Test Notifikasi →</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>❌ TIDAK ada user yang memiliki FCM token!</p>";
    echo "<p class='warning'>⚠️ Anda harus klik tombol 'Aktifkan Notifikasi' di dashboard terlebih dahulu!</p>";
    echo "<ol>";
    echo "<li>Login ke aplikasi</li>";
    echo "<li>Buka dashboard</li>";
    echo "<li>Klik tombol biru <strong>'Aktifkan Notifikasi'</strong></li>";
    echo "<li>Klik <strong>'Allow'</strong> saat browser minta permission</li>";
    echo "<li>Refresh halaman ini</li>";
    echo "</ol>";
}
echo "</div>";

// Test notification jika ada parameter
if (isset($_GET['test_user'])) {
    $userId = (int)$_GET['test_user'];
    $user = User::find($userId);
    
    echo "<div class='step'>";
    echo "<h2>Step 3: Test Kirim Notifikasi ke {$user->name}</h2>";
    
    if ($user && $user->fcm_token) {
        echo "<p class='info'>⏳ Mengirim notifikasi...</p>";
        
        $firebase = new FirebaseService();
        $result = $firebase->sendNotification(
            $user->fcm_token,
            '🔥 Test Notifikasi',
            'Ini adalah test notifikasi dari Firebase Admin SDK. Jika Anda melihat ini, berarti notifikasi berhasil!',
            [
                'tipe' => 'test',
                'link' => url('/dashboard')
            ]
        );
        
        echo "<h3>Hasil:</h3>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        
        if ($result['success']) {
            echo "<p class='success' style='font-size: 18px;'>✅ BERHASIL! Notifikasi telah dikirim!</p>";
            echo "<p class='warning'>Cek Windows notification Anda sekarang! 🔔</p>";
        } else {
            echo "<p class='error' style='font-size: 18px;'>❌ GAGAL mengirim notifikasi!</p>";
            echo "<p>Error: <code>{$result['message']}</code></p>";
        }
    } else {
        echo "<p class='error'>❌ User tidak ditemukan atau tidak memiliki FCM token</p>";
    }
    echo "</div>";
}

echo "<div class='step'>";
echo "<h2>Step 4: Cek Laravel Log</h2>";

$logPath = storage_path('logs/laravel.log');
if (file_exists($logPath)) {
    $logLines = file($logPath);
    $recentLogs = array_slice($logLines, -30); // 30 baris terakhir
    
    echo "<p class='success'>✅ Log file ditemukan</p>";
    echo "<p>Menampilkan 30 baris terakhir:</p>";
    echo "<pre style='max-height: 300px; overflow-y: auto;'>";
    
    foreach ($recentLogs as $line) {
        if (stripos($line, 'firebase') !== false || stripos($line, 'notification') !== false) {
            echo "<span class='warning'>" . htmlspecialchars($line) . "</span>";
        } else {
            echo htmlspecialchars($line);
        }
    }
    echo "</pre>";
} else {
    echo "<p class='error'>❌ Log file tidak ditemukan</p>";
}
echo "</div>";

?>

    <div class="step">
        <h2>📋 Checklist Debug:</h2>
        <ol>
            <li>✅ Service Account JSON ada dan valid</li>
            <li>❓ User sudah klik "Aktifkan Notifikasi"?</li>
            <li>❓ FCM Token tersimpan di database?</li>
            <li>❓ Test send notification berhasil?</li>
            <li>❓ Windows notification muncul?</li>
        </ol>
        
        <h3>Jika masih belum muncul:</h3>
        <ul>
            <li>Pastikan browser permission untuk notification = <strong>Allow</strong></li>
            <li>Pastikan Windows notification settings untuk browser Anda = <strong>ON</strong></li>
            <li>Cek Laravel log untuk error</li>
            <li>Coba di browser berbeda (Chrome/Edge recommended)</li>
        </ul>
    </div>

    <div class="step">
        <h2>🚀 Next: Test dengan Checkout Produk</h2>
        <ol>
            <li>Login sebagai <strong>User/Pembeli</strong></li>
            <li>Checkout produk via WhatsApp</li>
            <li>Login sebagai <strong>Admin</strong> di tab lain</li>
            <li>Admin akan menerima notifikasi Windows! 🎉</li>
        </ol>
    </div>

</body>
</html>
