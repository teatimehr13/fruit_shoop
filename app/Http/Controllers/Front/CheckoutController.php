<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Ecpay\PaymentController;
use App\Models\Order;
use function Termwind\render;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return Inertia::render('Front/Checkout/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function placeOrder(Request $request)
    {
        return $this->createOrderByCart($request);
    }

    private function createOrderByCart(Request $request)
    {
        $validated = $request->validate([
            'selected_ids' => 'required|array|min:1',
            'selected_ids.*' => 'integer|exists:product_options,id',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'note' => 'nullable|string|max:500',
            'order_status' => 'required|integer',
            'payment_method' => 'required|string'
        ]);

        $user = $request->user();
        $checkoutItems = $this->getCheckoutItems($user, $validated['selected_ids']);

        $cart = $user->getPurchaseCartOrCreate();

        // 總金額（商品總額 + 運費）
        $subtotal = $checkoutItems->sum('subtotal');
        // $shippingFee = 60; // 可改成變數
        $shippingFee = $this->calculateShippingFee($subtotal);
        $total = $subtotal + $shippingFee;

        // 建立 Order
        $order_number = now()->format('YmdHis') . rand(1000, 9999);
        $order = Order::create([
            'user_id' => $user->id,
            'amount' => $total,
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'note' => $validated['note'],
            'order_number' => $order_number,
            'order_status' => $validated['order_status'], //變數
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'pending',
            'fulfilment_status' => 'pending',
        ]);

        // 建立 order_items
        foreach ($checkoutItems as $item) {
            // Log::info($item['productOption']['image']);
            $order->orderItems()->create([
                'name' => $item['product']['name'] . ' (' . $item['productOption']['color_name'] . ')',
                'price' => $item['productOption']['price'],
                'quantity' => $item['quantity'],
                'image' => $item['productOption']['image'],
                'product_option_id' => $item['productOption']['id'],
            ]);
        }

        // 購物車變成訂單後，刪除
        $cart->cartItems()->whereIn('product_option_id', $validated['selected_ids'])->delete();
        session()->forget('checkout.selected_ids'); //刪掉選中的購物車商品


        session()->flash('latest_order_number', $order->order_number);
        // return redirect()->route('order.show', ['order' => $order_number]);
        //把參數導到paymentcontroller 
        return app(PaymentController::class)->checkout(new Request([
            'order_number' => $order->order_number,
            'amount' => $order->amount
        ]));
    }
}
