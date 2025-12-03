<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ProductOption;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request) {}

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if ($request->user()) {
            return $this->addToDBCart($request);
        }

        return $this->addToCookieCart($request);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'product_option_id' => 'required|integer|exists:product_options,id',
            'qty' => 'required|integer|min:0'
        ]);

        if (!$user) {
            return $this->updateToCookieCart($data);
        } else {
            return $this->updateToDBCart($user, $data);
        }
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'product_option_id' => 'required|integer|exists:product_options,id',
        ]);

        $optionId = $data['product_option_id'];
        $user     = $request->user();

        if ($user) {
            return $this->deleteFromDBCart($user, $optionId);
        } else {
            return $this->deleteFromCookieCart($optionId);
        }

        // return response()->noContent();
    }


    private function addToDBCart(Request $request)
    {
        //來自app > Models > User.php裡面
        $cart = $request->user()->getPurchaseCartOrCreate();
        $productOptions =  $request->validate([
            'qty' => 'required|integer|min:1',
            'product_option_id' => 'required|integer|'
        ]);

        $qty = (int)$productOptions['qty'];
        $optionId = (int)$productOptions['product_option_id'];

        $product_option = ProductOption::findOrFail($optionId);
        $cartItem = $cart->cartItems()->where('product_option_id', $product_option->id)->first();

        if ($cartItem) {
            // $cartItem->qty += $qty;
            // $cartItem->save();
            $cartItem->increment('qty', $qty);
        } else {
            $cartItem = $cart->cartItems()->create([
                'product_option_id' => $product_option->id,
                'qty'               => $qty,
            ]);
        }

        // $cartItem->load([
        //     'productOption.product.primaryImage',
        // ]);

        return response()->json([
            'data' => $cartItem
        ]);
    }

    public function addToCookieCart(Request $request)
    {
        $cookieCart = $this->getCartFromCookie();
        $productOptions =  $request->validate([
            'qty' => 'required|integer|min:1',
            'product_option_id' => 'required|integer|exists:product_options,id'
        ]);


        $qty = (int)$productOptions['qty'];
        $optionId = (int)$productOptions['product_option_id'];

        if (isset($cookieCart[$optionId])) {
            $cookieCart[$optionId] += $qty;
        } else {
            $cookieCart[$optionId] = $qty;
        }

        $this->saveCookieCart($cookieCart);
        return response()->json(['msg' => '加入購物車成功']);
    }


    private function updateToDBCart($user, $data)
    {
        $qty = (int) $data['qty'];
        $optionId = (int) $data['product_option_id'];

        $cart = $user->getPurchaseCartOrCreate();
        $cartItem = $cart->cartItems()
            ->where('product_option_id', $optionId)
            ->first();

        if (!$cartItem) {
            return;
        }

        if ($qty > 0) {
            $cartItem->update(['qty' => $qty]);
        } else {
            $cartItem->delete();
        }

        return response()->noContent();
    }

    private function updateToCookieCart($data)
    {
        $qty = (int) $data['qty'];
        $optionId = (int) $data['product_option_id'];
        $cart = $this->getCartFromCookie();

        if (!isset($cart[$optionId])) {
            return;
        }

        if ($qty > 0) {
            $cart[$optionId] = $qty;
        } else {
            unset($cart[$optionId]);
        }

        $this->saveCookieCart($cart);

        return response()->noContent();
    }

    private function deleteFromDBCart($user, $optionId)
    {
        $cart = $user->getPurchaseCartOrCreate();
        $cartItem = $cart->cartItems()
            ->where('product_option_id', $optionId)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }
    }

    private function deleteFromCookieCart($optionId)
    {
        $cart = $this->getCartFromCookie();
        if (isset($cart[$optionId])) {
            unset($cart[$optionId]);
        }

        $this->saveCookieCart($cart);
    }

    //拿到cookie
    public function getCartFromCookie()
    {
        $jsonCart = Cookie::get('cart');

        if (is_null($jsonCart)) {
            return [];
        }

        $data = json_decode($jsonCart, true);
        Log::info($data);
        return is_array($data) ? $data : [];
    }

    //寫入cookie
    private function saveCookieCart($cookieCart)
    {
        $cartToJson = empty($cookieCart) ? "{}" : json_encode($cookieCart);
        Cookie::queue(
            Cookie::make('cart', $cartToJson, 60 * 24 * 7, '/', null, false, false)
        );
    }

   
}
