<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - UMKM Marketplace</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-header .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .email-body {
            padding: 40px 30px;
            color: #374151;
            line-height: 1.6;
        }
        .email-body h2 {
            color: #1f2937;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
        }
        .email-body p {
            margin: 15px 0;
            font-size: 15px;
        }
        .reset-button {
            display: inline-block;
            padding: 15px 40px;
            margin: 25px 0;
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: transform 0.2s;
        }
        .reset-button:hover {
            transform: translateY(-2px);
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #92400e;
        }
        .footer {
            background-color: #f9fafb;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 8px 0;
            font-size: 13px;
            color: #6b7280;
        }
        .footer a {
            color: #7c3aed;
            text-decoration: none;
        }
        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 25px 0;
        }
        .link-text {
            word-break: break-all;
            color: #7c3aed;
            font-size: 13px;
            padding: 12px;
            background-color: #f3f4f6;
            border-radius: 6px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="icon">🔐</div>
            <h1>Reset Password</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Halo, {{ $name }}!</h2>
            
            <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>UMKM Marketplace</strong>.</p>
            
            <p>Untuk melanjutkan proses reset password, silakan klik tombol di bawah ini:</p>

            <center>
                <a href="{{ url('password/reset', $token) }}?email={{ urlencode($email) }}" class="reset-button">
                    Reset Password Sekarang
                </a>
            </center>

            <p>Atau salin dan tempel link berikut di browser Anda:</p>
            <div class="link-text">
                {{ url('password/reset', $token) }}?email={{ urlencode($email) }}
            </div>

            <div class="info-box">
                <p><strong>⚠️ Penting:</strong> Link ini hanya berlaku selama 24 jam. Jika Anda tidak melakukan permintaan ini, abaikan email ini dan password Anda akan tetap aman.</p>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6b7280;">
                Jika tombol di atas tidak berfungsi, salin dan tempel link di atas ke address bar browser Anda.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>UMKM Marketplace</strong></p>
            <p>Platform Jual-Beli UMKM Indonesia</p>
            <p style="margin-top: 15px;">
                Email ini dikirim secara otomatis. Mohon untuk tidak membalas email ini.
            </p>
            <p>
                &copy; {{ date('Y') }} UMKM Marketplace. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
