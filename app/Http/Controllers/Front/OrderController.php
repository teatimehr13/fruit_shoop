<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
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

    public function create()
    {
        //
    }

    public function store(Request $request) {}

    public function show(Order $order)
    {
        $items = $order->orderItems->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => (int) $item->price,
                'qty' => (int) $item->quantity,
                'image' => $item->image,
                'img_url' => $item->img_url,
                'option_text' => $item->option_text,
                'product_option_id' => $item->product_option_id,
                'line_total' => (int) $item->price * (int) $item->quantity,
            ];
        });

        $subtotal = $items->sum('line_total');
        $shippingFee = $this->calculateShippingFee($subtotal);
        $total = $subtotal + $shippingFee;

        return Inertia::render('Front/Order/Show', [
            'order' => $order,
            'items' => $items,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $total
        ]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    private function calculateShippingFee($subtotal)
    {
        return $subtotal <= 0 ? 0 : ($subtotal >= 2000 ? 0 : 100);
    }
}
