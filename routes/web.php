<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;



Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Produk Detail - accessible by everyone (SEO-friendly slug)
Route::get('/produk/{slug}', [HomeController::class, 'showProduk'])->name('produk.detail');

// WhatsApp Checkout - can be accessed by guest or auth users
Route::get('/whatsapp-checkout/{id}', [App\Http\Controllers\WhatsAppCheckoutController::class, 'show'])->name('whatsapp.checkout.show');
Route::post('/whatsapp-checkout', [App\Http\Controllers\WhatsAppCheckoutController::class, 'checkout'])->name('whatsapp.checkout');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login.post');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register.post');
    
    // Forgot Password Routes
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    // Reset Password Routes
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    
    // Google OAuth Routes
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return view('admin.dashboard');
        } elseif ($user->role === 'penjual') {
            return view('penjual.dashboard');
        } else {
            return redirect()->route('pembeli.dashboard');
        }
    })->name('dashboard');
    
    // Profile - All Users
    Route::get('/profile', [App\Http\Controllers\ProfilController::class, 'show'])->name('profile.show');
    Route::put('/profile', [App\Http\Controllers\ProfilController::class, 'update'])->name('profile.update');
    Route::post('/profile/apply-seller', [App\Http\Controllers\ProfilController::class, 'applySeller'])->name('profile.apply-seller');
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Save FCM Token for Push Notifications
    Route::post('/save-fcm-token', function (Illuminate\Http\Request $request) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->update(['fcm_token' => $request->fcm_token]);
        return response()->json(['success' => true]);
    });
    
    // Admin Routes - User Management
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/seller-approval', [App\Http\Controllers\Admin\SellerApprovalController::class, 'index'])->name('seller.approval');
        Route::post('/seller-approval/{id}/approve', [App\Http\Controllers\Admin\SellerApprovalController::class, 'approve'])->name('seller.approve');
        Route::post('/seller-approval/{id}/reject', [App\Http\Controllers\Admin\SellerApprovalController::class, 'reject'])->name('seller.reject');
        
        // Pesanan Management for Admin
        Route::get('/pesanan', [App\Http\Controllers\AdminPesananController::class, 'index'])->name('pesanan.index');
        Route::post('/pesanan/{id}/konfirmasi', [App\Http\Controllers\AdminPesananController::class, 'konfirmasi'])->name('pesanan.konfirmasi');
        Route::post('/pesanan/{id}/batalkan', [App\Http\Controllers\AdminPesananController::class, 'batalkan'])->name('pesanan.batalkan');
    });
    
    // Notifikasi Routes - untuk semua user yang login
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [App\Http\Controllers\NotifikasiController::class, 'index'])->name('index');
        Route::post('/{id}/baca', [App\Http\Controllers\NotifikasiController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/baca-semua', [App\Http\Controllers\NotifikasiController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{id}', [App\Http\Controllers\NotifikasiController::class, 'delete'])->name('delete');
    });
    
    // Chat Routes - hanya untuk admin dan penjual
    Route::prefix('chat')->name('chat.')->middleware('role:admin,penjual')->group(function () {
        Route::get('/', [App\Http\Controllers\ChatController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\ChatController::class, 'show'])->name('show');
        Route::post('/{id}/kirim', [App\Http\Controllers\ChatController::class, 'kirimPesan'])->name('kirim');
        Route::post('/create', [App\Http\Controllers\ChatController::class, 'create'])->name('create');
    });
    
    // CRUD Produk - hanya untuk admin dan penjual yang sudah approved
    Route::middleware('approved.seller')->group(function () {
        Route::get('/produk', [App\Http\Controllers\ProdukController::class, 'index'])->name('produk.index');
        Route::get('/produk/create', [App\Http\Controllers\ProdukController::class, 'create'])->name('produk.create');
        Route::post('/produk', [App\Http\Controllers\ProdukController::class, 'store'])->name('produk.store');
        Route::get('/produk/{id}', [App\Http\Controllers\ProdukController::class, 'show'])->name('produk.show');
        Route::get('/produk/{id}/edit', [App\Http\Controllers\ProdukController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{id}', [App\Http\Controllers\ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{id}', [App\Http\Controllers\ProdukController::class, 'destroy'])->name('produk.destroy');
        
        // Pesanan Management for Penjual
        Route::get('/penjual/pesanan', [App\Http\Controllers\PesananController::class, 'penjualIndex'])->name('penjual.pesanan.index');
        Route::put('/penjual/pesanan/{id}/kirim', [App\Http\Controllers\PesananController::class, 'updateStatusByPenjual'])->name('penjual.pesanan.kirim');
    });
    
    // Pembeli Dashboard Routes
    Route::get('/pembeli/dashboard', [App\Http\Controllers\PembeliController::class, 'dashboard'])->name('pembeli.dashboard');
    Route::get('/pembeli/pesanan', [App\Http\Controllers\PembeliController::class, 'pesanan'])->name('pembeli.pesanan.index');
});