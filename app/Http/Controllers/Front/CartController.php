<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request) {}

    public function create()
    {
        //
    }

    public function store(Request $request) {}

    public function show(string $id)
    {
        //
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

    public function addToCart(Request $request)
    {
        $isLogin = $request->user();
        if ($isLogin) {
            return $this->addToDBCart($request);
        } else {
            return $this->addToCookieCart($request);
        }
    }

    // public function addToCookieCart(Request $request)
    // {
    //     $cookieCart = $this->getCartFromCookie();
    //     $productOptions =  $request->validate([
    //         'quantity' => 'required|integer|min:1',
    //         'id' => 'required|integer|'
    //     ]);


    //     $quantity = intval($productOptions['quantity']); //integer
    //     $productOptionId = intval($productOptions['id']);

    //     session()->flash('cart.selected', [$productOptionId]);

    //     if ($quantity <= 0 || $productOptionId <= 0) {
    //         return response()->json(['msg' => '商品規格不符'], 400);
    //     }

    //     $product_option = ProductOption::findIfEnable($productOptionId);

    //     if (!$product_option) {
    //         return response()->json(['msg' => '商品不存在或未啟用'], 404);
    //     }

    //     $product_option = ProductOption::findIfEnable($productOptionId);

    //     if ($product_option) {
    //         if (isset($cookieCart[$productOptionId])) {
    //             $cookieCart[$productOptionId] += $quantity;
    //         } else {
    //             $cookieCart[$productOptionId] = $quantity;
    //         }
    //     }

    //     // Log::info($cookieCart);
    //     // return;
    //     $this->saveCookieCart($cookieCart);
    //     return response()->json(['msg' => '加入購物車成功']);
    // }


    public function addToDBCart(Request $request)
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

    public function updateCartItem(Request $request)
    {
        $user_status = $request->user();

        if ($user_status) {
            $this->updateToDBCart($request);
        } else {
            $this->updateToCookieCart($request);
        }
    }

    private function updateToDBCart(Request $request)
    {
        $productOptions =  $request->validate([
            'qty' => 'required|integer|min:0',
            'product_option_id' => 'required|integer|exists:product_options,id'
        ]);

        $qty = (int)$productOptions['qty'];
        $optionId = (int)$productOptions['product_option_id'];

        $cart = $request->user()->getPurchaseCartOrCreate();

        $cartItem = $cart->cartItems()
            ->where('product_option_id', $optionId)
            ->first();

        if (!$cartItem) {
            return;
        }

        if ($qty > 0) {
            $cartItem->update(['qty' => $qty]);
        } else {
            $cartItem->delete();  //刪除
        }
    }
}
