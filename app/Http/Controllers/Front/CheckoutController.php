<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
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
    public function store(CheckoutRequest $request, CartService $cartService, OrderService $orderService)
    {
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

        $subtotal = (int) ($checkoutItems['subtotal'] ?? 0);

        $order = $orderService->createFromCart($user, $request->validated(), $items, $subtotal);

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

}
