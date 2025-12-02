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

        $optionIds = array_keys($data);
        $options = ProductOption::query()
            ->whereIn('id', $optionIds)
            ->with([
                'product:id,name',
                'product.primaryImage'
            ])
            ->get()
            ->keyBy('id');  //用key當id, 
        //ex: [0 => ProductOption {id: 5, ...},] => [5 => ProductOption {id: 5, ...},]

        return collect($optionIds)
            ->map(function ($optionId) use ($data, $options) {
                $option = $options->get($optionId);

                if (!$option) {
                    return null;
                }

                $product = $option->product;
                $image   = $product?->primaryImage;

                return [
                    'id'                => $option->id, // cookie是存option id
                    'product_id'        => $option->product_id,
                    'product_option_id' => $option->id,
                    'product_name'      => $product?->name,
                    'option_text'       => $option->option_text,
                    'price'             => $option->price,
                    'original_price'    => $option->original_price,
                    'qty'               => (int) ($data[$optionId] ?? 0),
                    'img_url'           => $image?->img_url ?? null,
                    'image'             => $image?->image ?? null,
                ];
            })
            ->filter()   // 把 null 的過濾掉
            ->values();

        // 結果（沒用 values）：
        // [0 => ['id' => 1], 2 => ['id' => 3]]
        // 前端收到：{"0": {...}, "2": {...}}  ← 變物件了！

        // 結果（用了 values）：
        // [0 => ['id' => 1], 1 => ['id' => 3]]
        // 前端收到：[{...}, {...}]  ← 乾淨的陣列

    }
}
