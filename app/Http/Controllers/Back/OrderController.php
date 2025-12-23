<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;


class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $range = $request->string('range')->toString();
        $q      = trim($request->string('q')->toString());

        $orders = Order::query()
            ->when($status && in_array($status, Order::ORDER_STATUSES, true), fn($q) => $q->where('order_status', $status))
            ->when(in_array($range, ['today', '7d', '30d'], true), function ($q) use ($range) {
                $now = now();

                if ($range === 'today') {
                    $q->whereDate('created_at', $now->toDateString());
                } elseif ($range === '7d') {
                    $q->where('created_at', '>=', $now->copy()->subDays(7));
                } elseif ($range === '30d') {
                    $q->where('created_at', '>=', $now->copy()->subDays(30));
                }
            })

            // q 搜尋：訂單號 / Email / 姓名 / 電話
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('order_number', 'like', "%{$q}%")
                        ->orWhere('shipping_email', 'like', "%{$q}%")
                        ->orWhere('recipient_name', 'like', "%{$q}%")
                        ->orWhere('recipient_phone', 'like', "%{$q}%");
                });
            })

            ->latest()
            ->paginate(20)
            ->withQueryString();
        return Inertia::render('Back/Order/Index', [
            'orders' => $orders,
            'statusOptions' => Order::ORDER_STATUS_LABELS,
            'filters' => ['status' => $status, 'range' => $range,  'q' => $q],
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
                'qty' => (int) $item->qty,
                'image' => $item->image,
                'img_url' => $item->img_url,
                'option_text' => $item->option_text,
                'product_option_id' => $item->product_option_id,
                'line_total' => (int) $item->price * (int) $item->qty,
            ];
        });

        $subtotal = $items->sum('line_total');
        $shippingFee = $this->calculateShippingFee($subtotal);
        $total = $subtotal + $shippingFee;

        return Inertia::render('Back/Order/Show', [
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
