<?php

namespace App\Services;
use LiqPay;

class LiqPayService
{
    protected $liqPay;
    public function __construct()
    {
        // Передайте ваши ключи сюда или через параметры конструктора
        $this->liqPay = new LiqPay(config('app.liqpay_public_key'), config('app.liqpay_private_key'));
    }

    /**
     * Генерация формы оплаты
     */
    public function generatePaymentForm(array $params)
    {
        return $this->liqPay->cnb_form($params);
    }

    /**
     * Обработка проверки подписи и декодирование данных
     */
    public function decodeResponse($data, $signature)
    {
        if ($this->liqPay->str_to_sign(config('liqpay.private_key') . $data . config('liqpay.private_key')) !== $signature) {
            throw new \Exception('Invalid signature');
        }
        return $this->liqPay->decode_params($data);
    }
}
