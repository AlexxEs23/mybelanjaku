<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('✅ Test email berhasil dikirim dari CheckoutAja!', function($message) {
        $message->to('yantofahri137@gmail.com')
                ->subject('Test Email CheckoutAja - Berhasil!');
    });
    
    echo "✅ Email berhasil dikirim ke yantofahri137@gmail.com!\n";
    echo "📧 Silakan cek inbox atau folder spam.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
