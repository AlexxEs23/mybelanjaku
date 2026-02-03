<?php

namespace App\Helpers;

use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationHelper
{
    /**
     * Send notification to user(s)
     * 
     * @param int|array $userIds Single user ID or array of user IDs
     * @param string $title
     * @param string $message
     * @param string $type (pesanan, chat, system, payment, etc.)
     * @param int|null $referenceId
     * @param string|null $link
     * @return bool
     */
    public static function send($userIds, $title, $message, $type = 'system', $referenceId = null, $link = null)
    {
        try {
            // Convert single ID to array
            if (!is_array($userIds)) {
                $userIds = [$userIds];
            }

            foreach ($userIds as $userId) {
                Notifikasi::create([
                    'user_id' => $userId,
                    'judul' => $title,
                    'pesan' => $message,
                    'tipe' => $type,
                    'referensi_id' => $referenceId,
                    'link' => $link,
                    'dibaca' => false
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send notification about new order to admin and seller
     */
    public static function newOrder($orderId, $orderNumber, $sellerUserId)
    {
        // Get all admins
        $admins = User::where('role', 'admin')->pluck('id')->toArray();
        
        // Send to seller
        self::send(
            $sellerUserId,
            '🛍️ Pesanan Baru!',
            "Anda mendapat pesanan baru #{$orderNumber}. Segera proses pesanan ini.",
            'pesanan',
            $orderId,
            route('penjual.pesanan.index')
        );

        // Send to admins
        if (!empty($admins)) {
            self::send(
                $admins,
                '🛍️ Pesanan Baru Masuk',
                "Pesanan #{$orderNumber} perlu dikonfirmasi.",
                'pesanan',
                $orderId,
                route('admin.pesanan.index')
            );
        }
    }

    /**
     * Notify buyer about order status change
     */
    public static function orderStatusChanged($buyerUserId, $orderId, $orderNumber, $status)
    {
        $statusMessages = [
            'dikonfirmasi' => "Pesanan #{$orderNumber} telah dikonfirmasi dan sedang diproses.",
            'dikirim' => "Pesanan #{$orderNumber} telah dikirim. Cek detail pengiriman untuk tracking.",
            'selesai' => "Pesanan #{$orderNumber} telah selesai. Terima kasih!",
            'dibatalkan' => "Pesanan #{$orderNumber} telah dibatalkan."
        ];

        $title = match($status) {
            'dikonfirmasi' => '✅ Pesanan Dikonfirmasi',
            'dikirim' => '📦 Pesanan Dikirim',
            'selesai' => '🎉 Pesanan Selesai',
            'dibatalkan' => '❌ Pesanan Dibatalkan',
            default => 'ℹ️ Update Pesanan'
        };

        self::send(
            $buyerUserId,
            $title,
            $statusMessages[$status] ?? "Status pesanan #{$orderNumber} telah diupdate.",
            'pesanan',
            $orderId,
            route('pembeli.pesanan.index')
        );
    }

    /**
     * Notify about new chat message
     */
    public static function newChatMessage($recipientUserId, $senderName, $chatId)
    {
        self::send(
            $recipientUserId,
            '💬 Pesan Baru',
            "{$senderName} mengirim pesan kepada Anda.",
            'chat',
            $chatId,
            route('chat.show', $chatId)
        );
    }

    /**
     * Notify seller about approval status
     */
    public static function sellerApprovalStatus($sellerUserId, $status)
    {
        if ($status === 'approved') {
            self::send(
                $sellerUserId,
                '🎉 Akun Penjual Disetujui!',
                'Selamat! Akun penjual Anda telah disetujui. Anda sekarang dapat mulai berjualan.',
                'system',
                null,
                route('produk.create')
            );
        } else {
            self::send(
                $sellerUserId,
                '❌ Akun Penjual Ditolak',
                'Maaf, permohonan akun penjual Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.',
                'system',
                null,
                route('profile.show')
            );
        }
    }

    /**
     * Send payment confirmation notification
     */
    public static function paymentConfirmed($buyerUserId, $orderId, $orderNumber)
    {
        self::send(
            $buyerUserId,
            '💰 Pembayaran Diterima',
            "Pembayaran untuk pesanan #{$orderNumber} telah diterima dan dikonfirmasi.",
            'payment',
            $orderId,
            route('pembeli.pesanan.index')
        );
    }

    /**
     * Send system notification to all users (broadcast)
     */
    public static function broadcast($title, $message, $link = null)
    {
        $allUserIds = User::pluck('id')->toArray();
        
        return self::send(
            $allUserIds,
            $title,
            $message,
            'system',
            null,
            $link
        );
    }

    /**
     * Send notification to specific role
     */
    public static function sendToRole($role, $title, $message, $type = 'system', $referenceId = null, $link = null)
    {
        $userIds = User::where('role', $role)->pluck('id')->toArray();
        
        if (empty($userIds)) {
            return false;
        }

        return self::send($userIds, $title, $message, $type, $referenceId, $link);
    }
}
