<?php

namespace App\Http\Controllers\Ecpay;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Services\EcpayPaymentService;

class PaymentController extends Controller
{
    public function __construct(private EcpayPaymentService $ecpay)
    {
    }

    public function checkout(string $order)
    {
        $orderModel = Order::where('order_number', $order)->firstOrFail();

        $html = $this->ecpay->generateCheckoutHtml(
            $orderModel->order_number,
            (int) $orderModel->amount
        );

        return response($html);
    }

    // 2) 綠界後端回傳付款結果（ReturnURL）
    public function returnUrl(Request $req)
    {
        // 驗章 + 取得驗證過的資料
        $data = $this->ecpay->verifyReturn($req->all());
        Log::info('綠界付款成功資料驗證完成', $data);

        $paymentType = (string) ($data['PaymentType'] ?? '');

        $merchantTradeNo = $data['MerchantTradeNo'] ?? null;
        if (!$merchantTradeNo) {
            Log::warning('綠界回傳缺少 MerchantTradeNo', $data);
            return response('0|Missing MerchantTradeNo', 400);
        }

        $order = Order::where('payment_token', $merchantTradeNo)
            ->orWhere('order_number', $merchantTradeNo)
            ->first();

        $method = match (true) {
            str_starts_with($paymentType, 'Credit') => 'credit_card',
            str_starts_with($paymentType, 'ATM')    => 'atm',
            str_starts_with($paymentType, 'CVS')    => 'cvs',
            str_starts_with($paymentType, 'BARCODE') => 'barcode',
            default                                  => $paymentType ?: $order->payment_method,
        };

        if (!$order) {
            Log::warning('找不到對應訂單', ['MerchantTradeNo' => $merchantTradeNo]);
            // 綠界規範：沒回 1|OK 可能會重送通知
            return response('1|OK', 200);
        }

        if ($order->order_status === Order::PAID) {
            return response('1|OK', 200);
        }

        $rtnCode = (int)($data['RtnCode'] ?? 0);

        if ($rtnCode === 1) {
            $order->update([
                'order_status' => Order::PAID,
                'payment_method' => $method,
                'payment_order_number' => $data['TradeNo'] ?? $order->payment_order_number,
            ]);
        } else {
            $order->update([
                'order_status' => Order::WAITING_FOR_THE_TRANSFER, // 或 Order::PAYMENT_FAILED
            ]);

            Log::warning('綠界付款未成功', [
                'order_number' => $order->order_number,
                'RtnCode' => $data['RtnCode'] ?? null,
                'RtnMsg'  => $data['RtnMsg'] ?? null,
            ]);
        }

        return response('1|OK', 200);
    }

    // 3) 綠界前端導回（OrderResultURL）
    public function frontOrderResultURL(Request $req)
    {
        $merchantTradeNo = $req->input('MerchantTradeNo');

        $order = Order::where('order_number', $merchantTradeNo)
            ->orWhere('payment_token', $merchantTradeNo)
            ->firstOrFail();

        // 從 ECPay 返回時自動重新登入
        if (!auth()->check() && $order->user_id) {
            auth()->loginUsingId($order->user_id, true);
        }

        return redirect()
            ->route('order.show', ['order' => $order->order_number])
            ->with('success', '付款完成！訂單已成立');
    }

    // 4) 補繳（站內功能）
    public function retry(Order $order)
    {
        // 確保是本人訂單
        // abort_unless($order->user_id === auth()->id(), 403);

        if ($order->order_status === 'paid') {
            return redirect()
                ->route('order.show', ['order' => $order->order_number])
                ->with('error', '此訂單無需補繳');
        }

        $paymentToken = 'RE' . $order->id . now()->format('His');
        $order->update(['payment_token' => $paymentToken]);

        $html = $this->ecpay->generateCheckoutHtml($paymentToken, (int) $order->amount);
        return response($html);
    }

    // 5) 如果有用 notifyUrl，就照同 returnUrl 做驗章與更新（可選）
    public function notifyUrl(Request $req)
    {
        $data = $this->ecpay->verifyReturn($req->all());
        Log::info('Notify 回傳內容', $data);

        return response('1|OK', 200);
    }
}
