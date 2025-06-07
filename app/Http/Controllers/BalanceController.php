<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use App\Services\LiqPayService;

class BalanceController extends Controller
{
    protected $liqpay;

    // Инжектируйте сервис в конструкторе (если используете)
    public function __construct(LiqPayService $liqpay)
    {
        $this->liqpay = $liqpay;
    }

    public function showForm(Request $request)
    {
        $amount = $request->get('amount', 0);
        // Запоминаем URL страницы, с которой пришли:
        $return_url = url()->previous(); 
        return view('balance.top-up', compact('amount', 'return_url'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $amount = $request->amount;

        $orderId = "balance_{$user->id}_" . time(); // Уникальный ID заказа

        // Записываем платёж в таблицу
        \DB::table('payments')->insert([
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => $amount,
            'status' => 'pending', // Ожидание
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // // Установим сессионный флаг (если нужно для интерфейса)
        // session()->put('awaiting_payment', true);

     
        // $liqpay = new \LiqPay(config('app.liqpay_public_key'), config('app.liqpay_private_key'));

        $return_url = $request->input('return_url', route('profile'));        

        $params = [
            'action'        => 'pay',
            'amount'        => $amount,
            'currency'      => 'UAH',
            'description'   => 'Поповнення балансу',
            'order_id'      => $orderId,
            'version'       => '3',
            'server_url'    => route('balance.callback'), 
            'result_url'    => $return_url,
        ];

        // $form = $liqpay->cnb_form($params);
        $form = $this->liqpay->generatePaymentForm($params);
        return view('payment.pay', compact('form'));
    }

    public function liqpayCallback(Request $request)
    {
        Log::info('LiqPay callback received', $request->all());
        Log::info('LiqPay callback triggered');
        Log::info('Request headers:', $request->headers->all());
        Log::info('Request input:', $request->all());

        $data = json_decode(base64_decode($request->input('data')), true);
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

    public function checkPayment(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Не авторизовано'], 401);
        }

        $payment = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
    
        if (!$payment) {
            $this->statusMessage = 'Немає очікуючих платежів';
            return;
        }
    
        $liqpay = new LiqPay(config('app.liqpay_public_key'), config('app.liqpay_private_key'));
        $response = $liqpay->api('request', [
            'action'   => 'status',
            'version'  => '3',
            'order_id' => $payment->order_id,
        ]);
  
        if ($response->status === 'success') {
            // Обновляем баланс и статус платежа
            $user->increment('balance', $payment->amount);
            $payment->update(['status' => 'paid']);
    
            session()->flash('message', 'Баланс успішно оновлено');
            
            return redirect(request()->header('Referer')); // Перезагрузка страницы
        } else {
            $this->statusMessage = 'Платіж не завершено: ' . $response->status;
        }
    }

}
