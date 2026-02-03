# 🔔 Panduan Lengkap Sistem Notifikasi - Updated & Enhanced

## 🎉 **Fitur Baru yang Ditambahkan**

### ✅ **Yang Sudah Diperbaiki:**

1. **Bug Fix: API getUnread**
   - Fixed field `is_read` → `dibaca` (sesuai database schema)
   - Sekarang API berfungsi dengan benar

2. **New API Endpoint: getCount**
   - Endpoint baru untuk real-time badge counter
   - Route: `/notifikasi/api/count`
   - Return: `{"count": 5}`

3. **Notification Helper Class**
   - Helper functions untuk membuat notifikasi dengan mudah
   - Pre-built templates untuk berbagai jenis notifikasi
   - Location: `app/Helpers/NotificationHelper.php`

4. **Interactive Notification Dropdown**
   - Komponen dropdown notifikasi dengan Alpine.js
   - Auto-refresh setiap 30 detik
   - Notification sound ketika ada notifikasi baru
   - Mark as read & delete inline
   - Location: `resources/views/components/notification-dropdown.blade.php`

---

## 📖 **Cara Menggunakan Notification Helper**

### **1. Basic Usage**

```php
use App\Helpers\NotificationHelper;

// Send notification to single user
NotificationHelper::send(
    $userId,                    // User ID
    'Title Here',               // Title
    'Message content here',     // Message
    'system',                   // Type (optional)
    null,                       // Reference ID (optional)
    route('some.route')         // Link (optional)
);

// Send to multiple users
NotificationHelper::send(
    [1, 2, 3, 4],              // Array of user IDs
    'Title',
    'Message'
);
```

---

### **2. Pre-built Templates**

#### **A. New Order Notification**
```php
// Automatically notifies seller and all admins
NotificationHelper::newOrder(
    $orderId,           // Order ID
    $orderNumber,       // e.g., "#ORD-001"
    $sellerUserId       // Seller's user ID
);
```

#### **B. Order Status Changed**
```php
// Notify buyer about order status
NotificationHelper::orderStatusChanged(
    $buyerUserId,
    $orderId,
    $orderNumber,
    'dikirim'  // Status: dikonfirmasi, dikirim, selesai, dibatalkan
);
```

#### **C. New Chat Message**
```php
NotificationHelper::newChatMessage(
    $recipientUserId,
    $senderName,        // "John Doe"
    $chatId
);
```

#### **D. Seller Approval Status**
```php
// Approved
NotificationHelper::sellerApprovalStatus($sellerUserId, 'approved');

// Rejected
NotificationHelper::sellerApprovalStatus($sellerUserId, 'rejected');
```

#### **E. Payment Confirmation**
```php
NotificationHelper::paymentConfirmed(
    $buyerUserId,
    $orderId,
    $orderNumber
);
```

#### **F. Broadcast to All Users**
```php
// Send notification to ALL users
NotificationHelper::broadcast(
    'Maintenance Notice',
    'Website will be under maintenance on Sunday 2AM-4AM',
    route('home')
);
```

#### **G. Send to Specific Role**
```php
// Send to all admins
NotificationHelper::sendToRole(
    'admin',
    'Important Notice',
    'Please review pending seller approvals'
);

// Send to all sellers
NotificationHelper::sendToRole(
    'penjual',
    'New Feature Available',
    'Check out our new analytics dashboard!'
);
```

---

## 🔌 **Cara Menggunakan Notification Dropdown**

### **1. Include Component**

Di file layout atau navbar Anda:

```blade
<!-- In your navbar -->
<div class="flex items-center gap-4">
    <!-- Other nav items -->
    
    @include('components.notification-dropdown')
</div>
```

### **2. Fitur Dropdown:**

- ✅ Auto-refresh setiap 30 detik
- ✅ Real-time badge counter
- ✅ Mark all as read
- ✅ Delete notification inline
- ✅ Click to navigate
- ✅ Notification sound
- ✅ Responsive design

---

## 🎨 **Update UI Notifikasi di Sidebar**

Ganti badge lama dengan real-time counter:

```blade
<!-- Before -->
<span id="notif-badge-admin" class="hidden ..."></span>

<!-- After: Gunakan Alpine.js -->
<span 
    x-data="{ count: 0 }"
    x-init="setInterval(async () => { 
        const res = await fetch('{{ route('notifikasi.api.count') }}');
        const data = await res.json();
        count = data.count;
    }, 30000)"
    x-show="count > 0"
    x-text="count"
    class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"
></span>
```

---

## 📊 **Contoh Implementasi Lengkap**

### **Scenario: User membuat pesanan baru**

```php
// In PesananController.php

use App\Helpers\NotificationHelper;

public function store(Request $request)
{
    // ... validation & create order logic
    
    $order = Pesanan::create([...]);
    
    // Send notifications automatically
    NotificationHelper::newOrder(
        $order->id,
        $order->nomor_pesanan,
        $order->produk->user_id  // Seller ID
    );
    
    return redirect()->back()->with('success', 'Pesanan berhasil dibuat!');
}
```

### **Scenario: Admin konfirmasi pesanan**

```php
// In AdminPesananController.php

public function konfirmasi($id)
{
    $order = Pesanan::findOrFail($id);
    $order->update(['status' => 'dikonfirmasi']);
    
    // Notify buyer
    NotificationHelper::orderStatusChanged(
        $order->user_id,
        $order->id,
        $order->nomor_pesanan,
        'dikonfirmasi'
    );
    
    return redirect()->back()->with('success', 'Pesanan dikonfirmasi!');
}
```

### **Scenario: Penjual kirim barang**

```php
// In PesananController.php

public function updateStatusByPenjual(Request $request, $id)
{
    $order = Pesanan::findOrFail($id);
    $order->update([
        'status' => 'dikirim',
        'no_resi' => $request->no_resi
    ]);
    
    // Notify buyer
    NotificationHelper::orderStatusChanged(
        $order->user_id,
        $order->id,
        $order->nomor_pesanan,
        'dikirim'
    );
    
    return redirect()->back()->with('success', 'Pesanan berhasil dikirim!');
}
```

---

## 🔥 **Advanced: Custom Notification Types**

Jika ingin membuat tipe notifikasi custom:

```php
// Send custom notification
NotificationHelper::send(
    $userId,
    '🎁 Promo Spesial!',
    'Dapatkan diskon 50% untuk produk pilihan hari ini!',
    'promo',                    // Custom type
    $promoId,                   // Reference to promo
    route('promo.show', $promoId)
);
```

---

## 🎯 **Best Practices**

### **1. Always Use Helper**
```php
// ❌ JANGAN ini (manual)
Notifikasi::create([
    'user_id' => $userId,
    'judul' => 'Title',
    'pesan' => 'Message',
    // ... banyak field manual
]);

// ✅ GUNAKAN ini (helper)
NotificationHelper::send($userId, 'Title', 'Message');
```

### **2. Use Templates When Available**
```php
// ❌ JANGAN ini
NotificationHelper::send(
    $userId,
    'Pesanan baru',
    'Anda dapat pesanan #'.$orderNo
);

// ✅ GUNAKAN ini (ada template-nya)
NotificationHelper::newOrder($orderId, $orderNumber, $sellerUserId);
```

### **3. Always Provide Link**
```php
// ❌ JANGAN ini (no link)
NotificationHelper::send($userId, 'Title', 'Message');

// ✅ GUNAKAN ini (with link)
NotificationHelper::send(
    $userId, 
    'Title', 
    'Message',
    'system',
    null,
    route('relevant.page')  // ← Important!
);
```

---

## 🧪 **Testing Notification**

### **Test di Tinker:**

```bash
php artisan tinker
```

```php
// Test send notification
$user = \App\Models\User::first();
\App\Helpers\NotificationHelper::send(
    $user->id,
    'Test Notification',
    'This is a test message'
);

// Test broadcast
\App\Helpers\NotificationHelper::broadcast(
    'System Maintenance',
    'Website akan maintenance besok pagi'
);

// Test new order
\App\Helpers\NotificationHelper::newOrder(1, '#ORD-001', 2);
```

---

## 📱 **Firebase Push Notification**

Sistem notifikasi database sudah otomatis trigger Firebase push notification jika:
1. User memiliki `fcm_token` (sudah aktifkan notifikasi di browser)
2. Firebase Server Key sudah dikonfigurasi di `.env`

**Tidak perlu code tambahan!** Firebase akan auto-send saat notifikasi dibuat.

---

## 🎨 **Customization**

### **Custom Notification Icons**

Edit template di `notifikasi/index.blade.php`:

```blade
@php
$icon = match($item->tipe) {
    'pesanan' => '🛍️',
    'chat' => '💬',
    'payment' => '💰',
    'system' => 'ℹ️',
    'promo' => '🎁',
    default => '🔔'
};
@endphp

<span class="text-2xl">{{ $icon }}</span>
```

### **Custom Notification Colors**

```blade
@php
$color = match($item->tipe) {
    'pesanan' => 'border-blue-500 bg-blue-50',
    'chat' => 'border-green-500 bg-green-50',
    'payment' => 'border-yellow-500 bg-yellow-50',
    default => 'border-gray-500 bg-white'
};
@endphp

<div class="notification {{ $color }}">
```

---

## 📈 **Performance Tips**

1. **Paginate Notifications**
   - Sudah implemented: 20 notif per page
   - User tidak overload dengan data

2. **Auto-delete Old Notifications**
   ```php
   // Add to scheduler (app/Console/Kernel.php)
   $schedule->call(function () {
       Notifikasi::where('created_at', '<', now()->subDays(30))
           ->where('dibaca', true)
           ->delete();
   })->daily();
   ```

3. **Index Database**
   - Sudah ada index di `user_id` dan `dibaca`
   - Query akan cepat meski data banyak

---

## 🆘 **Troubleshooting**

### **Notifikasi tidak muncul di dropdown?**
- Cek route `/notifikasi/api/unread` berfungsi
- Cek browser console untuk errors
- Pastikan Alpine.js loaded

### **Badge counter tidak update?**
- Cek route `/notifikasi/api/count` berfungsi
- Pastikan auto-refresh berjalan (30 detik)

### **Firebase push tidak jalan?**
- Cek `FIREBASE_SERVER_KEY` di `.env`
- Cek user punya `fcm_token`
- Lihat logs: `storage/logs/laravel.log`

---

## 🎉 **Kesimpulan**

Sistem notifikasi sekarang **production-ready** dengan:

✅ Database notifications (persistent)  
✅ Firebase push notifications (real-time)  
✅ Helper class (easy to use)  
✅ Pre-built templates (common scenarios)  
✅ Interactive dropdown (better UX)  
✅ Auto-refresh (real-time updates)  
✅ Notification sound (user engagement)  
✅ Badge counters (visual feedback)  
✅ Mobile responsive (works everywhere)  

**Total development time saved: ~8 hours** 🚀

---

**Happy coding! 💻**
