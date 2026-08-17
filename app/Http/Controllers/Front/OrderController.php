<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class OrderController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $user = $request->user();
        $orders = Order::query()
            ->where('user_id', $user->id)
            ->where('created_at', '>=', now()->subYears(2))
            ->latest()
            ->paginate(15)
            ->withQueryString();
            
        return Inertia::render('Front/Account/OrderInfo', [
            'orders' => $orders,
        ]);
    }

    public function create(): void
    {
        //
    }

    public function store(Request $request): void
    {
        //
    }

    public function show(Request $request, Order $order): InertiaResponse
    {
        // 允許兩種身分：訂單本人登入查看，或是持有簽章過的臨時連結（例如 ECPay 付款完成導回）
        $isOwner = $request->user() && $request->user()->id === $order->user_id;

        abort_unless($isOwner || $request->hasValidSignature(), 403);

        $items = $order->orderItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (int) $item->price,
                'qty' => (int) $item->qty,
                'image' => $item->image,
                'img_url' => $item->img_url,
                'option_text' => $item->option_text,
                'product_option_id' => $item->product_option_id,
                'line_total' => (int) $item->price * (int) $item->qty,
            ];
        });

        $subtotal = $items->sum('line_total');
        $shippingFee = Order::calculateShippingFee($subtotal);
        $total = $subtotal + $shippingFee;

        return Inertia::render('Front/Order/Show', [
            'order' => $order,
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $total
        ]);
    }

    public function edit(string $id): void
    {
        //
    }

    public function update(Request $request, string $id): void
    {
        //
    }

    public function destroy(string $id): void
    {
        //
    }

}
