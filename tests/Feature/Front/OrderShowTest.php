<?php

namespace Tests\Feature\Front;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class OrderShowTest extends TestCase
{
    use RefreshDatabase;

    protected function makeOrder(User $owner): Order
    {
        return Order::create([
            'user_id' => $owner->id,
            'recipient_name' => 'Test Recipient',
            'recipient_phone' => '0900000000',
            'shipping_email' => 'test@example.com',
            'shipping_city' => '台北市',
            'shipping_district' => '信義區',
            'shipping_address_detail' => '測試路1號',
            'shipping_zip_code' => '110',
            'address' => 'test',
            'order_status' => 'not_selected_payment',
            'amount' => 100,
        ]);
    }

    public function test_owner_can_view_their_order(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->actingAs($owner)
            ->get(route('order.show', $order))
            ->assertOk();
    }

    public function test_other_authenticated_user_cannot_view_it(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->actingAs($stranger)
            ->get(route('order.show', $order))
            ->assertForbidden();
    }

    public function test_guest_cannot_view_it(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $this->get(route('order.show', $order))
            ->assertForbidden();
    }

    public function test_guest_can_view_it_with_a_valid_signed_url(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $signedUrl = URL::temporarySignedRoute(
            'order.show',
            now()->addMinutes(30),
            ['order' => $order->order_number]
        );

        $this->get($signedUrl)->assertOk();
    }

    public function test_guest_cannot_view_it_with_a_tampered_signature(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $signedUrl = URL::temporarySignedRoute(
            'order.show',
            now()->addMinutes(30),
            ['order' => $order->order_number]
        );

        $tampered = preg_replace('/signature=\w+/', 'signature=deadbeef', $signedUrl);

        $this->get($tampered)->assertForbidden();
    }

    public function test_guest_cannot_view_it_with_an_expired_signed_url(): void
    {
        $owner = User::factory()->create();
        $order = $this->makeOrder($owner);

        $signedUrl = URL::temporarySignedRoute(
            'order.show',
            now()->subMinute(),
            ['order' => $order->order_number]
        );

        $this->get($signedUrl)->assertForbidden();
    }
}
