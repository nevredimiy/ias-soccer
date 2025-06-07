<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LiqPayService;

class LiqPayController extends Controller
{
    protected $liqpay;

    public function __construct(LiqPayService $liqpay)
    {
        $this->liqpay = $liqpay;
    }

    // 1️⃣ Генерируем платежную форму
    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $amount = $request->amount;
        $order_id = uniqid();

        $form = $this->liqpay->getForm(
            $amount,
            $order_id,
            "Поповнення балансу на {$amount} грн",
            route('liqpay.result'),
            route('liqpay.callback')
        );

        return view('liqpay.pay', compact('form'));
    }

    // 2️⃣ Страница после успешного платежа
    public function result()
    {
        return redirect()->route('profile')->with('success', 'Оплата пройшла успішно!');
    }

    // 3️⃣ Обработка callback от LiqPay
    public function callback(Request $request)
    {
         // Логируем входящие данные
         Log::info('LiqPay callback', $request->all());

        // Получаем данные от LiqPay
        $data = $request->input('data');
        $signature = $request->input('signature');

        $generated_signature = base64_encode(sha1(env('LIQPAY_PRIVATE_KEY') . $data . env('LIQPAY_PRIVATE_KEY'), true));

        if ($signature !== $generated_signature) {
            Log::error('LiqPay: Невірний підпис!', ['data' => $data]);
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $response = json_decode(base64_decode($data), true);
        Log::info('LiqPay callback:', $response);

        if ($response['status'] === 'success' || $response['status'] === 'sandbox') {
            $user = User::where('id', $response['order_id'])->first();

            if ($user) {
                $user->increment('balance', $response['amount']);

                Transaction::create([
                    'user_id' => $user->id,
                    'amount' => $response['amount'],
                    'type' => 'deposit',
                    'description' => 'Поповнення через LiqPay',
                ]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    // public function callback(Request $request)
    // {
    //     Log::info('LiqPay callback', $request->all());

    //     $data = json_decode(base64_decode($request->input('data')), true);

    //     if ($data['status'] === 'success' || $data['status'] === 'sandbox') {
    //         $team = Team::find($data['order_id']);
    //         if ($team) {
    //             $team->status = 'paid';
    //             $team->save();
    //             Log::info("Статус команды #{$team->id} обновлен на 'paid'");
    //         }
    //     }

    //     return response()->json(['status' => 'ok']);
    // }
}
