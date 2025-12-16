<?php

namespace App\Models;

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
        // 'payment_status',
        // 'fulfilment_status',
        'payment_token',
        'shipping_city', //y
        'shipping_district', //y
        'shipping_address_detail', //y
        'shipping_zip_code', //y
        'note', //y
        'recipient_phone', //y
        'recipient_name', //y
        'shipping_email'
    ];

    protected $appends = ['order_status_label'];

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            // 訂單編號：加上隨機數，解決高併發重複問題
            // 結果範例: LPB20231211102030123
            $query->order_number = $query->order_number ?? 'LPB' . now()->format('YmdHis') . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

            // 狀態預設值：直接用 array_search 查找 Key (0, 1, 2...)
            // 如果沒傳狀態，預設為 0 (NOT_SELECTED_PAYMENT)
            if (is_null($query->order_status)) {
                $query->order_status = array_search(self::NOT_SELECTED_PAYMENT, self::ORDER_STATUSES);
            }

            $query->payment_order_number = $query->payment_order_number ?? null;
        });
    }

    public static function orderStatusesIndex($targetName)
    {
        return array_search($targetName, self::ORDER_STATUSES);
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

    
}
