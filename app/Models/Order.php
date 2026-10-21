<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    public const STATUSES = [
        'pending'          => 'Menunggu',
        'awaiting_payment' => 'Menunggu pembayaran',
        'processing'       => 'Diproses',
        'shipped'          => 'Dikirim',
        'delivered'        => 'Selesai',
        'out_of_stock'     => 'Stok habis',
        'cancelled'        => 'Dibatalkan',
    ];

    /** Status yang dihitung sebagai penjualan (sudah dibayar). */
    public const PAID = ['processing', 'shipped', 'delivered'];

    protected const STYLES = [
        'pending'          => ['rgba(245,158,11,0.12)', '#D97706'],
        'awaiting_payment' => ['rgba(196,152,74,0.16)', '#A07A2E'],
        'processing'       => ['rgba(59,130,246,0.12)', '#2563EB'],
        'shipped'          => ['rgba(139,92,246,0.12)', '#7C3AED'],
        'delivered'        => ['rgba(34,197,94,0.12)', '#16A34A'],
        'out_of_stock'     => ['rgba(239,68,68,0.12)', '#DC2626'],
        'cancelled'        => ['rgba(120,113,108,0.14)', '#57534E'],
    ];

    protected $fillable = [
        'code', 'customer_name', 'phone', 'address', 'note', 'type',
        'pickup_date', 'status', 'total', 'stock_deducted',
    ];

    protected $casts = ['stock_deducted' => 'boolean', 'pickup_date' => 'date'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusStyleAttribute(): string
    {
        [$bg, $color] = self::STYLES[$this->status] ?? self::STYLES['cancelled'];

        return "background:{$bg};color:{$color}";
    }

    public function getIsPreorderAttribute(): bool
    {
        return $this->type === 'preorder';
    }

    /** Link WhatsApp admin -> pelanggan, pesan menyesuaikan status. */
    public function waLink(): string
    {
        $phone = preg_replace('/\D+/', '', $this->phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        $rp = 'Rp ' . number_format($this->total, 0, ',', '.');
        $shop = config('yoghurtq.name');

        $msg = match ($this->status) {
            'awaiting_payment' => "Halo {$this->customer_name}! Stok pesanan #{$this->code} tersedia. Total {$rp}. Silakan lanjut ke pembayaran ya 🙏",
            'out_of_stock'     => "Halo {$this->customer_name}, mohon maaf stok untuk pesanan #{$this->code} sedang habis. Mau kami tawarkan varian atau ukuran lain? 🙏",
            default            => "Halo {$this->customer_name}! Pesanan #{$this->code} Anda sedang " . mb_strtolower($this->status_label) . ". Terima kasih sudah memesan {$shop} 🙏",
        };

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($msg);
    }
}
