<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Ecpay\PaymentController;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\EcpayPaymentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CartService $cartService)
    {
        $user = $request->user();

        if ($user) {
            $pending = Order::where('user_id', $user->id)
                ->whereIn('order_status', [Order::NOT_SELECTED_PAYMENT, Order::WAITING_FOR_THE_TRANSFER])
                ->where('created_at', '>=', now()->subMinutes(30))
                ->latest()
                ->first();

            if ($pending && !empty($pending->payment_token)) {
                return redirect()->route('order.show', ['order' => $pending->order_number], 303);
            }
        }

        $checkoutItems = $cartService->getSharedCartItems($request);
        $items = $checkoutItems['items'] ?? collect();

        if ($items->isEmpty()) {
            return redirect()
                ->route('products.index')
                ->with('error', '購物車是空的，請先加入商品再結帳');
        }

        return Inertia::render('Front/Checkout/Index', []);

        // $checkoutItems = $cartService->getSharedCartItems($request);
        // // dd($checkoutItems);
        // $items = $checkoutItems['items'] ?? collect();

        // if ($items->isEmpty()) {
        //     return redirect()
        //         ->route('products.index')
        //         ->with('error', '購物車是空的，請先加入商品再結帳');
        // }
        // return Inertia::render('Front/Checkout/Index', []);
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
        // dd('test');
        // $user = $request->user();

        // $pending = Order::where('user_id', $user->id)
        //     ->whereIn('order_status', [Order::NOT_SELECTED_PAYMENT, Order::WAITING_FOR_THE_TRANSFER])
        //     ->where('created_at', '>=', now()->subMinutes(30))
        //     ->latest()
        //     ->first();

        // if ($pending) {
        // if ($pending && !empty($pending->payment_token)) {
        //     return redirect()->route('order.show', ['order' => $pending->order_number], 303);
        // }
        // return Inertia::location(route('ecpay.checkout', ['order' => $pending->order_number]));
        // return redirect()->route('ecpay.checkout', ['order' => $pending->order_number], 303);
        // }

        $order = $this->createOrderByCart($request, $cartService, $ecpay);
        // return redirect()
        //     ->route('ecpay.checkout', ['order' => $order->order_number])
        //     ->setStatusCode(303);

        // return Inertia::location(route('ecpay.checkout', ['order' => $order->order_number]));
        return response()->json([
            'pay_url' => route('ecpay.checkout', ['order' => $order->order_number]),
        ]);

        // return $this->createOrderByCart($request, $cartService, $ecpay);
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

    // public function placeOrder(Request $request)
    // {
    //     return $this->createOrderByCart($request);
    // }

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
        // $checkoutItems = $this->getCheckoutItems($user, $validated['selected_ids']);
        $items = $checkoutItems['items'] ?? collect();

        if ($items->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => '購物車是空的，無法建立訂單',
            ]);
        }

        // $cart = $user->getPurchaseCartOrCreate();

        //總金額（商品總額 + 運費）
        $subtotal = (int)($checkoutItems['subtotal'] ?? 0);
        $shippingFee = $this->calculateShippingFee($subtotal);
        $total = $subtotal + $shippingFee;
        // $paymentToken = 'RE' . str_pad((string)$order->id, 8, '0', STR_PAD_LEFT) . now()->format('His'); // 2+8+6=16

        // dd($total);

        // dd($checkoutItems);
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
                // 'order_number' => $order_number, //model自動建
                'order_status' => Order::NOT_SELECTED_PAYMENT, //變數
                // 'payment_method' => $validated['payment_method'],
                // 'payment_status' => 'pending',
                // 'fulfilment_status' => 'pending',
            ]);

            $paymentToken = 'RE' . $order->id . now()->format('His') . random_int(10, 99);
            $order->update(['payment_token' => $paymentToken]);

            // "id" => 88
            // "product_id" => 8
            // "product_option_id" => 10
            // "product_name" => "explicabo nostrum"
            // "option_text" => "sit enim"
            // "price" => 302
            // "original_price" => 5944
            // "qty" => 1
            // "img_url" => null
            // "image" => null
            // "subtotal" => 302

            // 建立 order_items
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


        // $html = $ecpay->generateCheckoutHtml($order->order_number, (int)$order->amount);
        // return response($html);
        // return Inertia::location(route('ecpay.checkout', ['order' => $order->order_number]));
        // return redirect()
        //     ->route('ecpay.checkout', ['order' => $order->order_number])
        //     ->setStatusCode(303);
    }

    private function calculateShippingFee($subtotal)
    {
        return $subtotal <= 0 ? 0 : ($subtotal >= 2000 ? 0 : 100);
    }
}
