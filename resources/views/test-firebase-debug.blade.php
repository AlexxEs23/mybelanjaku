@php
use App\Services\FirebaseService;
use App\Models\User;

$serviceAccountPath = base_path('ecommerceumkm-4dbc3-firebase-adminsdk-fbsvc-8fe7f35302.json');
$serviceAccountExists = file_exists($serviceAccountPath);

if ($serviceAccountExists) {
    $serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);
}

$usersWithToken = User::whereNotNull('fcm_token')->get();

// Test notification jika ada parameter
$testResult = null;
if (request()->has('test_user')) {
    $userId = (int)request('test_user');
    $user = User::find($userId);
    
    if ($user && $user->fcm_token) {
        $firebase = new FirebaseService();
        $testResult = $firebase->sendNotification(
            $user->fcm_token,
            '🔥 Test Notifikasi',
            'Ini adalah test notifikasi dari Firebase Admin SDK. Jika Anda melihat ini, berarti notifikasi berhasil!',
            [
                'tipe' => 'test',
                'link' => url('/dashboard')
            ]
        );
    }
}

$logPath = storage_path('logs/laravel.log');
$recentLogs = [];
if (file_exists($logPath)) {
    $logLines = file($logPath);
    $recentLogs = array_slice($logLines, -30);
}
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Firebase Debug Tool</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .container { max-width: 1200px; margin: 0 auto; }
        .success { color: #4ec9b0; }
        .error { color: #f48771; }
        .warning { color: #dcdcaa; }
        .info { color: #9cdcfe; }
        pre { background: #2d2d2d; padding: 15px; border-radius: 5px; overflow-x: auto; font-size: 13px; }
        button, .btn { background: #0e639c; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background: #1177bb; }
        .step { margin: 20px 0; padding: 20px; background: #252526; border-left: 4px solid #0e639c; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #2d2d2d; padding: 12px; text-align: left; }
        td { padding: 12px; border-bottom: 1px solid #3e3e3e; }
        code { background: #2d2d2d; padding: 2px 6px; border-radius: 3px; }
        h1 { color: #4ec9b0; }
        h2 { color: #9cdcfe; margin-top: 0; }
        ul, ol { line-height: 1.8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔥 Firebase Push Notification - Debug Tool</h1>
        
        <div class="step">
            <h2>Step 1: Cek Service Account JSON</h2>
            @if($serviceAccountExists)
                <p class="success">✅ File service account ditemukan!</p>
                <pre>Project ID: <span class="info">{{ $serviceAccount['project_id'] }}</span>
Client Email: <span class="info">{{ $serviceAccount['client_email'] }}</span>
Private Key: <span class="success">[TERSEDIA]</span></pre>
            @else
                <p class="error">❌ File service account TIDAK ditemukan!</p>
                <p>Path: <code>{{ $serviceAccountPath }}</code></p>
            @endif
        </div>

        <div class="step">
            <h2>Step 2: Cek Users dengan FCM Token</h2>
            @if($usersWithToken->count() > 0)
                <p class="success">✅ Ditemukan {{ $usersWithToken->count() }} user dengan FCM token</p>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>FCM Token</th>
                        <th>Action</th>
                    </tr>
                    @foreach($usersWithToken as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td><span class="info">{{ $user->role }}</span></td>
                        <td><code>{{ Str::limit($user->fcm_token, 30) }}</code></td>
                        <td>
                            <a href="?test_user={{ $user->id }}" class="btn" style="padding: 6px 12px; font-size: 14px;">Test Notifikasi →</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            @else
                <p class="error">❌ TIDAK ada user yang memiliki FCM token!</p>
                <p class="warning">⚠️ Anda harus klik tombol 'Aktifkan Notifikasi' di dashboard terlebih dahulu!</p>
                <ol>
                    <li>Login ke aplikasi</li>
                    <li>Buka dashboard</li>
                    <li>Klik tombol biru <strong>'Aktifkan Notifikasi'</strong></li>
                    <li>Klik <strong>'Allow'</strong> saat browser minta permission</li>
                    <li><a href="{{ url('/test-firebase') }}" class="info">Refresh halaman ini</a></li>
                </ol>
            @endif
        </div>

        @if($testResult)
        <div class="step">
            <h2>Step 3: Hasil Test Notifikasi</h2>
            <pre>{{ json_encode($testResult, JSON_PRETTY_PRINT) }}</pre>
            
            @if($testResult['success'])
                <p class="success" style="font-size: 18px;">✅ BERHASIL! Notifikasi telah dikirim!</p>
                <p class="warning">Cek Windows notification Anda sekarang! 🔔</p>
                <p>Jika notifikasi tidak muncul di Windows:</p>
                <ul>
                    <li>Pastikan browser permission untuk notification = <strong>Allow</strong></li>
                    <li>Cek Windows Settings → Notifications → Pastikan notifikasi untuk browser Anda <strong>ON</strong></li>
                    <li>Coba close dan buka browser lagi</li>
                </ul>
            @else
                <p class="error" style="font-size: 18px;">❌ GAGAL mengirim notifikasi!</p>
                <p>Error: <code>{{ $testResult['message'] }}</code></p>
            @endif
        </div>
        @endif

        <div class="step">
            <h2>Step 4: Laravel Log (30 baris terakhir)</h2>
            @if(count($recentLogs) > 0)
                <p class="success">✅ Log file ditemukan</p>
                <pre style="max-height: 300px; overflow-y: auto;">@foreach($recentLogs as $line)@if(stripos($line, 'firebase') !== false || stripos($line, 'notification') !== false)<span class="warning">{{ $line }}</span>@else{{ $line }}@endif
@endforeach</pre>
            @else
                <p class="error">❌ Log file tidak ditemukan atau kosong</p>
            @endif
        </div>

        <div class="step">
            <h2>📋 Checklist Debug</h2>
            <ul>
                <li class="{{ $serviceAccountExists ? 'success' : 'error' }}">{{ $serviceAccountExists ? '✅' : '❌' }} Service Account JSON</li>
                <li class="{{ $usersWithToken->count() > 0 ? 'success' : 'warning' }}">{{ $usersWithToken->count() > 0 ? '✅' : '⚠️' }} User dengan FCM Token</li>
                <li class="{{ $testResult && $testResult['success'] ? 'success' : 'warning' }}">{{ $testResult && $testResult['success'] ? '✅' : '❓' }} Test Send Notification</li>
            </ul>
            
            <h3>Panduan Lengkap:</h3>
            <ol>
                <li><strong>Aktifkan Notifikasi di Dashboard</strong>
                    <ul>
                        <li><a href="{{ url('/login') }}" class="info">Login</a> → Dashboard</li>
                        <li>Klik tombol "Aktifkan Notifikasi"</li>
                        <li>Allow browser permission</li>
                    </ul>
                </li>
                <li><strong>Test Manual</strong>
                    <ul>
                        <li>Refresh halaman ini</li>
                        <li>Klik "Test Notifikasi" di tabel user</li>
                        <li>Notifikasi Windows harus muncul!</li>
                    </ul>
                </li>
                <li><strong>Test Real dengan Checkout</strong>
                    <ul>
                        <li>Login sebagai User → Checkout produk</li>
                        <li>Login sebagai Admin di tab lain</li>
                        <li>Admin dapat notifikasi Windows! 🎉</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="step">
            <h2>🚀 Quick Actions</h2>
            <a href="{{ url('/') }}" class="btn">🏠 Homepage</a>
            <a href="{{ url('/dashboard') }}" class="btn">📊 Dashboard</a>
            <a href="{{ url('/test-firebase') }}" class="btn">🔄 Refresh Page</a>
            <button onclick="location.reload()" class="btn">↻ Hard Reload</button>
        </div>
    </div>
</body>
</html>
