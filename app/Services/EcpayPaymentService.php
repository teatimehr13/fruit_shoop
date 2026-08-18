<?php

namespace App\Services;

use App\Models\Order;
use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Response\VerifiedArrayResponse;

class EcpayPaymentService
{
    private const PRODUCTION_ACTION_URL = 'https://payment.ecpay.com.tw/Cashier/AioCheckOut/V5';
    private const STAGE_ACTION_URL = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';

    private function factory(): Factory
    {
        return new Factory([
            'hashKey'    => config('services.ecpay.hash_key'),
            'hashIv'     => config('services.ecpay.hash_iv'),
            'merchantId' => config('services.ecpay.merchant_id'),
            'mode'       => 0,
        ]);
    }

    public function generateCheckoutHtml(Order $order, string $merchantTradeNo): string
    {
        $factory = $this->factory();
        $autoSubmit = $factory->create('AutoSubmitFormWithCmvService');

        $params = [
            'MerchantID'        => config('services.ecpay.merchant_id'),
            'MerchantTradeNo'   => $merchantTradeNo,
            'MerchantTradeDate' => now()->format('Y/m/d H:i:s'),
            'PaymentType'       => 'aio',
            'TotalAmount'       => (int) $order->amount,
            'TradeDesc'         => urlencode('Vegefoods 訂單付款'),
            'ItemName'          => $this->buildItemName($order),
            'ReturnURL'         => config('services.ecpay.return_url'),
            'OrderResultURL'    => config('services.ecpay.front_url'),
            'ClientBackURL'     => config('services.ecpay.client_back_url') ?: route('account.orders'),
            'ChoosePayment'     => 'Credit',
            'EncryptType'       => 1,
        ];

        return $autoSubmit->generate($params, $this->actionUrl());
    }

    public function verifyReturn(array $payload): array
    {
        $factory = $this->factory();
        $verifier = $factory->create(VerifiedArrayResponse::class);
        return $verifier->get($payload);
    }

    private function actionUrl(): string
    {
        return config('services.ecpay.env') === 'production'
            ? self::PRODUCTION_ACTION_URL
            : self::STAGE_ACTION_URL;
    }

    /**
     * ECPay 的 ItemName 是用 # 分隔的逐項清單，例如「南瓜 x2#紅蘿蔔 x1」。
     */
    private function buildItemName(Order $order): string
    {
        $itemName = $order->orderItems
            ->map(fn ($item) => trim($item->name . ($item->option_text ? "（{$item->option_text}）" : '')) . " x{$item->qty}")
            ->implode('#');

        return $itemName !== '' ? $itemName : '商品';
    }
}
