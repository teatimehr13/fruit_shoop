<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    public function getSharedCartItems(Request $request): array
    {
        $user = $request->user();

        $items = $user
            ? $this->getItemsFromDB($user->id)
            : $this->getItemsFromCookie();

        $totalQuantity = $items->sum('qty');

        $subtotal = $items->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['qty'] ?? 1);
        });

        return [
            'items'          => $items->values(),   
            'total_qty'      => $totalQuantity,     
            'subtotal'       => $subtotal,      
        ];

        // return $this->getItemsFromCookie();
    }

    /**
     * 登入用戶：從 DB cart 撈
     */
    protected function getItemsFromDB(int $userId): Collection
    {
        $cart = Cart::query()
            ->where('user_id', $userId)
            ->with([
                //產品
                'CartItems.productOption.product' => function ($q) {
                    $q->select('id', 'name');
                },
                //產品選項
                'CartItems.productOption' => function ($q) {
                    $q->select('id', 'product_id', 'option_text', 'price', 'original_price');
                },
                //產品圖
                'CartItems.productOption.product.primaryImage' => function ($q) {
                    $q->select();
                },

            ])
            ->first();

        if (!$cart) {
            return collect();
        }

        return $cart->CartItems->map(function ($item) {
            $option  = $item->productOption;
            $product = $option?->product;
             $image   = $product?->primaryImage;

            return [
                'id'                 => $item->id,
                'product_id'         => $option?->product_id,
                'product_option_id'  => $item->product_option_id,
                'product_name'       => $product?->name,
                'option_text'        => $option?->option_text,
                'price'              => $option?->price,
                'original_price'     => $option?->original_price,
                'qty'               => $item->qty,
                'img_url'          => $image?->img_url ?? null,
                'image'          => $image?->image ?? null,
            ];
        })->values();
    }


    protected function getItemsFromCookie(): Collection
    {
        $jsonCart = Cookie::get('cart');

        if (is_null($jsonCart)) {
            return collect();
        }

        $data = json_decode($jsonCart, true);

        if (!is_array($data) || empty($data)) {
            return collect();
        }

        return collect($data);
    }
}
