<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_defaults_order_status_to_not_selected_payment_when_omitted(): void
    {
        $order = Order::create([
            'user_id' => User::factory()->create()->id,
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '0900000000',
            'shipping_email' => 'test@example.com',
            'shipping_city' => '台北市',
            'shipping_district' => '信義區',
            'shipping_address_detail' => '測試路1號',
            'shipping_zip_code' => '110',
            'address' => 'test',
            'amount' => 100,
            // order_status 故意不傳，驗證預設值
        ]);

        $this->assertSame(Order::NOT_SELECTED_PAYMENT, $order->order_status);
        $this->assertSame('not_selected_payment', $order->order_status);
    }
}
