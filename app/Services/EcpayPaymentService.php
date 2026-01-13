<?php

namespace App\Services;

use Ecpay\Sdk\Factories\Factory;
use Ecpay\Sdk\Response\VerifiedArrayResponse;

class EcpayPaymentService
{
    private function factory(): Factory
    {
        return new Factory([
            'hashKey'    => config('services.ecpay.hash_key'),
            'hashIv'     => config('services.ecpay.hash_iv'),
            'merchantId' => config('services.ecpay.merchant_id'),
            'mode'       => 0,
        ]);
    }

    public function generateCheckoutHtml(string $merchantTradeNo, int $amount): string
    {
        $factory = $this->factory();
        $autoSubmit = $factory->create('AutoSubmitFormWithCmvService');

        $params = [
            'MerchantID'        => config('services.ecpay.merchant_id'),
            'MerchantTradeNo'   => $merchantTradeNo,
            'MerchantTradeDate' => now()->format('Y/m/d H:i:s'),
            'PaymentType'       => 'aio',
            'TotalAmount'       => (int) $amount,
            'TradeDesc'         => urlencode('Laravel 測試訂單'),
            'ItemName'          => '測試商品',
            'ReturnURL'         => config('services.ecpay.return_url'),
            'OrderResultURL'    => config('services.ecpay.front_url'),
            'ClientBackURL'     => route('account.orders'),
            'ChoosePayment'     => 'Credit',
            'EncryptType'       => 1,
        ];

        $action = 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5';
        return $autoSubmit->generate($params, $action);
    }

    public function verifyReturn(array $payload): array
    {
        $factory = $this->factory();
        $verifier = $factory->create(VerifiedArrayResponse::class);
        return $verifier->get($payload);
    }
}
