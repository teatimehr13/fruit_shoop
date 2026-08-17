<?php

namespace Tests\Feature\Ecpay;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function makeOrder(User $owner, array $overrides = []): Order
    {
        return Order::create(array_merge([
            'user_id' => $owner->id,
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '0900000000',
            'shipping_email' => 'test@example.com',
            'shipping_city' => '台北市',
            'shipping_district' => '信義區',
            'shipping_address_detail' => '測試路1號',
            'shipping_zip_code' => '110',
            'address' => 'test',
            'order_status' => Order::NOT_SELECTED_PAYMENT,
            'amount' => 100,
        ], $overrides));
    }

    public function test_guest_is_redirected_to_login_for_checkout(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->get(route('ecpay.checkout', $order->order_number))
            ->assertRedirect(route('login'));
    }

    public function test_other_authenticated_user_cannot_checkout_someone_elses_order(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->actingAs($stranger)
            ->get(route('ecpay.checkout', $order->order_number))
            ->assertForbidden();
    }

    public function test_owner_can_checkout_their_own_order(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->actingAs($owner)
            ->get(route('ecpay.checkout', $order->order_number))
            ->assertOk();
    }

    public function test_guest_is_redirected_to_login_for_retry(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->get(route('payment.retry', $order->order_number))
            ->assertRedirect(route('login'));
    }

    public function test_other_authenticated_user_cannot_retry_someone_elses_order(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->actingAs($stranger)
            ->get(route('payment.retry', $order->order_number))
            ->assertForbidden();
    }

    public function test_owner_can_retry_their_own_order(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->actingAs($owner)
            ->get(route('payment.retry', $order->order_number))
            ->assertOk();
    }
}
