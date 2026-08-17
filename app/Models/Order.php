<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_number',
        'payment_order_number',
        'order_status',
        'amount',
        'address',
        'user_id',
        'payment_method',
        'payment_token',
        'shipping_city', 
        'shipping_district', 
        'shipping_address_detail', 
        'shipping_zip_code', 
        'note', 
        'recipient_phone', 
        'recipient_name', 
        'shipping_email'
    ];

    protected $appends = ['order_status_label', 'is_payment_pending',  'payment_expire_at_label', 'is_payment_expired'];

    const NOT_SELECTED_PAYMENT = 'not_selected_payment';
    const WAITING_FOR_THE_TRANSFER = 'waiting_for_the_transfer';
    const PAID = 'paid';
    const SEND_BEFORE_PAID = 'send_before_paid';
    const CANCELLED = 'cancelled';

    const ORDER_STATUSES = [
        0 => self::NOT_SELECTED_PAYMENT,
        1 => self::WAITING_FOR_THE_TRANSFER,
        2 => self::PAID,
        3 => self::CANCELLED
    ];

    const ORDER_STATUS_LABELS = [
        self::NOT_SELECTED_PAYMENT => '未選擇付款',
        self::WAITING_FOR_THE_TRANSFER => '等待匯款',
        self::PAID => '已付款',
        self::CANCELLED => '已取消',
    ];

    // 含 send_before_paid,給後台「變更狀態」動作用;一般篩選/顯示用 ORDER_STATUS_LABELS 就好
    const ALL_ORDER_STATUS_LABELS = self::ORDER_STATUS_LABELS + [
        self::SEND_BEFORE_PAID => '已出貨（未付款）',
    ];

    const PAYMENT_PENDING_STATUSES = [
        self::NOT_SELECTED_PAYMENT,
        self::WAITING_FOR_THE_TRANSFER,
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            // 訂單編號：加上隨機數，解決高併發重複問題
            // 結果範例: LPB20231211102030123
            $query->order_number = $query->order_number ?? 'LPB' . now()->format('YmdHis') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            // 如果沒傳狀態，預設為 NOT_SELECTED_PAYMENT
            if (is_null($query->order_status)) {
                $query->order_status = self::NOT_SELECTED_PAYMENT;
            }

            $query->payment_order_number = $query->payment_order_number ?? null;
        });
    }

    public static function orderStatusesIndex($targetName)
    {
        return array_search($targetName, self::ORDER_STATUSES);
    }

    public static function calculateShippingFee($subtotal)
    {
        return $subtotal <= 0 ? 0 : ($subtotal >= 2000 ? 0 : 100);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getRouteKeyName()
    {
        return 'order_number';
    }

    public function getOrderStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            self::NOT_SELECTED_PAYMENT => '尚未選擇付款方式',
            self::WAITING_FOR_THE_TRANSFER => '待付款',
            self::PAID => '已付款',
            self::SEND_BEFORE_PAID => '已出貨（未付款）',
            self::CANCELLED => '已取消',
            default => '未知狀態',
        };
    }

    public function getIsPaymentPendingAttribute()
    {
        return in_array($this->order_status, self::PAYMENT_PENDING_STATUSES, true);
    }

    public function getPaymentExpireAtLabelAttribute(): ?string
    {
        return $this->created_at
            ? $this->created_at->copy()
            ->utc()
            ->addHours(4)
            ->setTimezone('Asia/Taipei')
            ->format('Y-m-d H:i')
            : null;
    }

    public function getIsPaymentExpiredAttribute(): bool
    {
        if (!$this->created_at) return false;

        $expireAtUtc = $this->created_at->copy()
            ->utc()
            ->addHours(4);

        return Carbon::now('UTC')->greaterThan($expireAtUtc);
    }
}
