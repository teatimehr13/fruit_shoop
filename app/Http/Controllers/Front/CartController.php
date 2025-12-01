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

    public function store(Request $request)
    {
        return $this->addToDBCart($request);
        // $isLogin = $request->user();
        // if ($isLogin) {
        //     return $this->addToDBCart($request);
        // } else {
        //     return $this->addToCookieCart($request);
        // }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $user = $request->user();

        // if (!$user) {
        //     return $this->updateToCookieCart($request);
        // }

        if ($cartItem->cart->user_id !== $user->id) {
            abort(403);
        }

        return $this->updateToDBCart($request, $cartItem);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $user = $request->user();

        // if (!$user) {
        //     return $this->deleteFromCookieCart($request);
        // }

        if ($cartItem->cart->user_id !== $user->id) {
            abort(403);
        }

        return $this->deleteFromDBCart($cartItem);
    }

    // public function addToCart(Request $request)
    // {
    //     $user = $request->user();
    //     if ($user) {
    //         return $this->addToDBCart($request);
    //     } else {
    //         return $this->addToCookieCart($request);
    //     }
    // }

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

    // public function updateCartItem(Request $request)
    // {
    //     $user_status = $request->user();

    //     if ($user_status) {
    //         $this->updateToDBCart($request);
    //     } else {
    //         $this->updateToCookieCart($request);
    //     }
    // }

    private function updateToDBCart(Request $request, CartItem $cartItem)
    {
        $productOptions =  $request->validate([
            'qty' => 'required|integer|min:0',
        ]);

        $qty = (int)$productOptions['qty'];


        if ($qty > 0) {
            $cartItem->update(['qty' => $qty]);
        } else {
            $cartItem->delete();  //刪除
        }

        return response()->noContent();
    }

    // public function deleteCartItem(Request $request)
    // {
    //     $isLogin = $request->user();
    //     if ($isLogin) {
    //         return $this->deleteFromDBCart($request);
    //     } else {
    //         return $this->deleteFromCookieCart($request);
    //     }
    // }

    private function deleteFromDBCart(CartItem $cartItem)
    {
        $cartItem->delete();
    }

    private function deleteFromCookieCart(Request $request)
    {
        // // Log::info($request->input());
        // if ($request->has('product_option_id')) {
        //     $productOptionId = intval($request->input('product_option_id'));
        //     $cookieCart = $this->getCartFromCookie();

        //     if (isset($cookieCart[$productOptionId])) {
        //         unset($cookieCart[$productOptionId]);
        //         $this->saveCookieCart($cookieCart);
        //         return true;
        //     }
        // }
        // return false;
    }
}
