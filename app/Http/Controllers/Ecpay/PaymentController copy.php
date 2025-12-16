<?php

namespace App\Http\Controllers\Ecpay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

use Illuminate\Support\Facades\Log;
use Ecpay\Sdk\Response\VerifiedArrayResponse;

class PaymentController extends Controller
{
  // 1) 建立訂單並產生自動送出的 HTML Form
    public function checkout(Request $req)
    {
        $order_number = $req->input('order_number');
        $amount = $req->input('amount');

        $factory = new Factory([
            'hashKey'    => config('services.ecpay.hash_key'),
            'hashIv'     => config('services.ecpay.hash_iv'),
            'merchantId' => config('services.ecpay.merchant_id'),
            'mode'       => 0,
            // 'env'        => config('services.ecpay.env')
        ]);
        // $autoSubmit = $factory->create('AutoSubmitFormService');
        $autoSubmitFormService = $factory->create('AutoSubmitFormWithCmvService');

        $params = [
            // === 必填 ===
            'MerchantID'        => config('services.ecpay.merchant_id'),
            'MerchantTradeNo'   => $order_number, //訂單編號
            'MerchantTradeDate' => now()->format('Y/m/d H:i:s'),
            'PaymentType'       => 'aio',
            'TotalAmount'       => (int) $amount, // 從請求中取得金額
            'TradeDesc'         => urlencode('Laravel 測試訂單'),  //商品描述
            'ItemName'          => '測試商品',  //商品名稱

            // === 回傳網址 ===
            'ReturnURL'      => config('services.ecpay.return_url'), // 用來接收綠界後端回傳的付款結果通知
            'OrderResultURL' => config('services.ecpay.front_url'),  // Client
            // 'NotifyURL'      => config('services.ecpay.notify_url'),

            // === 付款選項 ===
            'ChoosePayment' => 'Credit',
            // 'ChoosePayment'  => 'ALL',   // 讓使用者在綠界頁面自行挑
            'EncryptType'    => 1,
            // 'BuyerEmail' => $request->email,
        ];

        $action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';
        $html = $autoSubmitFormService->generate($params, $action);
        return response($html);
    }

    // 2)當消費者付款完成後，特店接受綠界的付款結果訊息，並回應接收訊息
    public function returnUrl(Request $req)
    {

        // Log::info('Hit returnUrl', [
        //     'raw' => $req->all(),
        //     '_POST' => $_POST
        // ]);

        $factory = new Factory([
            'hashKey'    => config('services.ecpay.hash_key'),
            'hashIv'     => config('services.ecpay.hash_iv'),
            'merchantId' => config('services.ecpay.merchant_id'),
        ]);

        $checkoutResponse = $factory->create(VerifiedArrayResponse::class);
        $data = $checkoutResponse->get($req->all());
        Log::info('綠界付款成功資料驗證完成', $data);

        $order = Order::where('payment_token', $data['MerchantTradeNo'])
            ->orWhere('order_number', $data['MerchantTradeNo'])
            ->first();

        $order->update([
            'order_status' => 2,
            'payment_method' => $data['PaymentType'] == 'Credit_CreditCard' ? 'credit_card' : $data['PaymentType'],
            'payment_order_number' => $data['TradeNo'],
            'payment_status' => 'paid',
            // 'paid_at' => now(),
            // 'transaction_id' => $data['TradeNo'],
        ]);

       return response('1|OK', 200);
    }



    // 導回訂單頁
    public function frontOrderResultURL(Request $req)
    {
        Log::info('OrderResult 回傳內容', $req->all());
        $merchantTradeNo = $req->input('MerchantTradeNo');
        $order = Order::where('order_number', $merchantTradeNo)
            ->orWhere('payment_token', $merchantTradeNo)
            ->firstOrFail();

        return redirect()->route('order.show', ['order' => $order->order_number])->with('success', '付款完成！訂單已成立');
    }


    public function retry(Order $order)
    {
        // Log::info($order);
        // 確認是該使用者的訂單
        // if ($order->user_id !== auth()->id()) {
        //     abort(403);
        // }


        if ($order->payment_status === 'paid') {
            return redirect()->route('order.show', ['order' => $order->order_number])
                ->with('error', '此訂單無需補繳');
        }


        // $paymentToken = $order->order_number . '-R' . now()->format('His'); //超過20字元
        $paymentToken = 'RE' . $order->id . now()->format('His');
        $order->update(['payment_token' => $paymentToken]);

        return $this->checkout(new Request([
            'order_number' => $paymentToken,
            'amount' => $order->amount
        ]));
    }
}
