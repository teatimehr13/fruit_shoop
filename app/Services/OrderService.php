<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    /**
     * 依購物車內容建立訂單（含訂單明細），並清空已下單的購物車品項。
     */
    public function createFromCart(User $user, array $shippingData, Collection $items, int $subtotal): Order
    {
        $shippingFee = Order::calculateShippingFee($subtotal);
        $total = $subtotal + $shippingFee;

        return DB::transaction(function () use ($user, $shippingData, $items, $total) {
            $order = $this->createOrder($user, $shippingData, $total);

            $this->assignPaymentToken($order);
            $this->createOrderItems($order, $items);
            $this->clearPurchasedCartItems($user, $items);

            return $order;
        });
    }

    private function createOrder(User $user, array $shippingData, int $total): Order
    {
        return Order::create([
            'user_id' => $user->id,
            'amount' => $total,
            'shipping_email' => $shippingData['shipping_email'],
            'shipping_city' => $shippingData['shipping_city'],
            'shipping_district' => $shippingData['shipping_district'],
            'shipping_zip_code' => $shippingData['shipping_zip_code'],
            'shipping_address_detail' => $shippingData['shipping_address_detail'],
            'address' => '0',
            'recipient_phone' => $shippingData['recipient_phone'],
            'recipient_name' => $shippingData['recipient_name'],
            'note' => $shippingData['note'] ?? null,
            'order_status' => Order::NOT_SELECTED_PAYMENT,
        ]);
    }

    private function assignPaymentToken(Order $order): void
    {
        $paymentToken = 'RE' . $order->id . now()->format('His') . random_int(10, 99);
        $order->update(['payment_token' => $paymentToken]);
    }

    private function createOrderItems(Order $order, Collection $items): void
    {
        foreach ($items as $item) {
            $order->orderItems()->create([
                'name' => $item['product_name'],
                'option_text' => $item['option_text'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'image' => $item['image'],
                'product_option_id' => $item['product_option_id'],
            ]);
        }
    }

    private function clearPurchasedCartItems(User $user, Collection $items): void
    {
        $optionIds = $items->pluck('product_option_id')->filter()->unique()->values()->all();

        if (empty($optionIds)) {
            return;
        }

        $cart = $user->getPurchaseCartOrCreate();
        $cart->cartItems()->whereIn('product_option_id', $optionIds)->delete();
    }
}
