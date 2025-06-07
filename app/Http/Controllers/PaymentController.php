<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use LiqPay;
use App\Models\Payment;
use App\Services\LiqPayService;


class PaymentController extends Controller
{
    protected $liqpay;

    // Инжектируйте сервис в конструкторе (если используете)
    public function __construct(LiqPayService $liqpay)
    {
        $this->liqpay = $liqpay;
    }


    public function pay(Request $request)
    {
        $amount = $request->input('amount');

        if (!$amount || $amount <= 0) {
            return redirect()->back()->with('error', 'Невірна сума!');
        }

        $liqpay = new \LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));

        $params = [
            'action'    => 'pay',
            'amount'    => $amount,
            'currency'  => 'UAH',
            'description' => 'Поповнення балансу',
            'order_id'  => uniqid(),
            'version'   => '3',
            'sandbox'   => 1, // 1 - тестовий режим, 0 - бойовий
            'server_url' => route('liqpay.callback'),
            'result_url' => route('liqpay.result'),
        ];

        $form = $liqpay->cnb_form($params);

        return view('payment.pay', compact('form'));
    }

    public function createPayment(Request $request)
    {
        $user = auth()->user();
        $amount = $request->input('amount'); // сумма пополнения
        
        // создаем платеж в базе
        $payment = Payment::create([
            'user_id' => $user->id,
            'order_id' => uniqid('order_'), // или любой уникальный идентификатор
            'amount' => $amount,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // подготовка параметров для LIQPAY
        // $liqpay = new LiqPay(config('app.liqpay_public_key'), config('app.liqpay_private_key'));
        $params = [
            'action'        => 'pay',
            'version'       => '3',
            'amount'        => $amount,
            'currency'      => 'UAH', // например
            'description'   => 'Пополнение баланса',
            'order_id'      => $payment->order_id,
            'result_url'    => route('profile'), // URL для получения результата
            // 'server_url'    => route('payment.ipn'), // серверный URL для callback
            'server_url'    => route('payment.callback'), // серверный URL для callback
        ];


        $form = $this->liqpay->generatePaymentForm($params);
        return view('payment.form', ['form' => $form]);
    }

    // Метод для фронтенд-уведомления (от пользователя или успех после оплаты)
    public function callback(Request $request)
    {
        $data = $request->input('data');
        $signature = $request->input('signature');

        try {
            // Раскодируем и проверяем подпись
            $decoded = $this->liqpay->decodeResponse($data, $signature);

            // Проверяем статус и обновляем платеж
            if ($decoded['status'] === 'success') {
                $payment = Payment::where('order_id', $decoded['order_id'])->first();

                if ($payment && $payment->status !== 'paid') {
                    $payment->status = 'paid';
                    $payment->save();

                    // Пополнение баланса пользователя
                    $user = User::find($payment->user_id);
                    if ($user) {
                        $user->balance += $payment->amount;
                        $user->save();
                    }
                }

                return response()->json(['status' => 'success']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }

        return response()->json(['status' => 'error'], 400);
    }

    // Метод для серверных уведомлений (IPN)
    public function ipn(Request $request)
    {
        $data = $request->input('data');
        $signature = $request->input('signature');

        try {
            // Раскодируем и проверяем подпись
            $decoded = $this->liqpay->decodeResponse($data, $signature);

            if ($decoded['status'] === 'success') {
                $payment = Payment::where('order_id', $decoded['order_id'])->first();

                if ($payment && $payment->status !== 'paid') {
                    $payment->status = 'paid';
                    $payment->save();

                    // Пополнение баланса пользователя
                    $user = User::find($payment->user_id);
                    if ($user) {
                        $user->balance += $payment->amount;
                        $user->save();
                    }
                }
            }
        } catch (\Exception $e) {
            // Логировать ошибку или отправить ответ
            return response('Error', 400);
        }

        return response('OK', 200);
    }

   

}
