<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\EcpayPaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CartService $cartService)
    {
        $user = $request->user();

        $checkoutItems = $cartService->getSharedCartItems($request);
        $items = $checkoutItems['items'] ?? collect();

        if ($items->isEmpty() && $user) {
            $pending = Order::where('user_id', $user->id)
                ->whereIn('order_status', [Order::NOT_SELECTED_PAYMENT, Order::WAITING_FOR_THE_TRANSFER])
                ->where('created_at', '>=', now()->subMinutes(30))
                ->latest()
                ->first();

            if ($pending) {
                return Inertia::location(route('account.orders'));
            }
        }

        if ($items->isEmpty()) {
            return redirect()
                ->route('products.index')
                ->with('error', '購物車是空的，請先加入商品再結帳');
        }

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
    public function store(CheckoutRequest $request, CartService $cartService, EcpayPaymentService $ecpay)
    {
        $order = $this->createOrderByCart($request, $cartService, $ecpay);

        return response()->json([
            'pay_url' => route('ecpay.checkout', ['order' => $order->order_number]),
        ]);
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

    private function createOrderByCart($request, $cartService, $ecpay)
    {
        $validated = $request->validated();
        $user = $request->user();

        if (!$user) {
            throw ValidationException::withMessages([
                'auth' => '請先登入後再結帳',
            ]);
        }

        $checkoutItems = $cartService->getSharedCartItems($request);
        $items = $checkoutItems['items'] ?? collect();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => '購物車是空的，無法建立訂單',
            ]);
        }

        //總金額（商品總額 + 運費）
        $subtotal = (int)($checkoutItems['subtotal'] ?? 0);
        $shippingFee = Order::calculateShippingFee($subtotal);
        $total = $subtotal + $shippingFee;

        // 建立 Order
        return DB::transaction(function () use ($user, $validated, $items, $total, $shippingFee) {
            $order = Order::create([
                'user_id' => $user->id,
                'amount' => $total,
                'shipping_email' => $validated['shipping_email'],
                'shipping_city' => $validated['shipping_city'],
                'shipping_district' => $validated['shipping_district'],
                'shipping_zip_code' => $validated['shipping_zip_code'],
                'shipping_address_detail' => $validated['shipping_address_detail'],
                'address' => '0',
                'recipient_phone' => $validated['recipient_phone'],
                'recipient_name' => $validated['recipient_name'],
                'note' => $validated['note'] ?? null,
                'order_status' => Order::NOT_SELECTED_PAYMENT,
            ]);

            $paymentToken = 'RE' . $order->id . now()->format('His') . random_int(10, 99);
            $order->update(['payment_token' => $paymentToken]);

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

            $optionIds = $items->pluck('product_option_id')->filter()->unique()->values()->all();

            if (!empty($optionIds)) {
                $cart = $user->getPurchaseCartOrCreate();
                $cart->cartItems()->whereIn('product_option_id', $optionIds)->delete();
            }

            return $order;
        });
    }
}
