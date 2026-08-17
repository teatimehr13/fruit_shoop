<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Models\ProductOption;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    public function index(Request $request) {
        return Inertia::render('Front/Cart/Index');
    }

    public function create()
    {
        //
    }

    public function store(AddToCartRequest $request)
    {
        $data = $request->validated();

        if ($request->user()) {
            return $this->addToDBCart($request->user(), $data);
        }

        return $this->addToCookieCart($data);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(UpdateCartRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        if (!$user) {
            return $this->updateToCookieCart($data);
        } else {
            return $this->updateToDBCart($user, $data);
        }
    }

    public function destroy(RemoveFromCartRequest $request)
    {
        $optionId = $request->validated('product_option_id');
        $user     = $request->user();

        if ($user) {
            return $this->deleteFromDBCart($user, $optionId);
        } else {
            return $this->deleteFromCookieCart($optionId);
        }
    }


    private function addToDBCart(User $user, array $data)
    {
        //來自app > Models > User.php裡面
        $cart = $user->getPurchaseCartOrCreate();

        $qty = (int) $data['qty'];
        $optionId = (int) $data['product_option_id'];

        $product_option = ProductOption::findOrFail($optionId);
        $cartItem = $cart->cartItems()->where('product_option_id', $product_option->id)->first();

        if ($cartItem) {
            $cartItem->increment('qty', $qty);
        } else {
            $cartItem = $cart->cartItems()->create([
                'product_option_id' => $product_option->id,
                'qty'               => $qty,
            ]);
        }

        return response()->json([
            'data' => $cartItem
        ]);
    }

    private function addToCookieCart(array $data)
    {
        $cookieCart = $this->cartService->readCookieCart();

        $qty = (int) $data['qty'];
        $optionId = (int) $data['product_option_id'];

        if (isset($cookieCart[$optionId])) {
            $cookieCart[$optionId] += $qty;
        } else {
            $cookieCart[$optionId] = $qty;
        }

        $this->cartService->writeCookieCart($cookieCart);
        return response()->json(['msg' => '加入購物車成功']);
    }


    private function updateToDBCart(User $user, array $data)
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

    private function updateToCookieCart(array $data)
    {
        $qty = (int) $data['qty'];
        $optionId = (int) $data['product_option_id'];
        $cart = $this->cartService->readCookieCart();

        if (!isset($cart[$optionId])) {
            return;
        }

        if ($qty > 0) {
            $cart[$optionId] = $qty;
        } else {
            unset($cart[$optionId]);
        }

        $this->cartService->writeCookieCart($cart);

        return response()->noContent();
    }

    private function deleteFromDBCart(User $user, int $optionId)
    {
        $cart = $user->getPurchaseCartOrCreate();
        $cartItem = $cart->cartItems()
            ->where('product_option_id', $optionId)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }
    }

    private function deleteFromCookieCart(int $optionId)
    {
        $cart = $this->cartService->readCookieCart();
        if (isset($cart[$optionId])) {
            unset($cart[$optionId]);
        }

        $this->cartService->writeCookieCart($cart);
    }
}
