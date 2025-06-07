<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Payment;
use App\Models\User;
use LiqPay;


class CheckBalance extends Component
{
    public $balance;
    public $statusMessage = '';
    protected $user = null;
    
    
    public function mount()
    {
        $this->user = auth()->user();
        $this->balance = $this->user->balance;

         $now = \Carbon\Carbon::now()->subSeconds(20);

        $payment = Payment::where('user_id', $this->user->id)
            ->where('status', 'pending')
            ->where('created_at', '<', $now)
            ->latest()
            ->first();

        
        if($payment){
            $this->checkPayment($payment);
        }

        
    }


    
    public function checkPayment($payment = null)
    {
        
        if (!$payment) {
            $payment = Payment::where('user_id', auth()->user()->id)
                ->where('status', 'pending')
                ->latest()
                ->first();
        }
        
        if (!$payment) {
            $this->statusMessage = 'Немає очікуваних платежів';
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
            $this->user->increment('balance', $payment->amount);
            $payment->update(['status' => 'paid']);

            $this->balance = $this->user->fresh()->balance;
            $this->dispatch('balanceUpdated', balance: $this->balance);
            $this->statusMessage = 'Баланс успішно оновлено!';
            // return redirect()->route('profile'); // Перезагрузка страницы
        } else {
            $this->statusMessage = 'Помилка: ' . $response->err_description;
        }

        // if($response->status === 'error' && $response->err_code === 'payment_not_found'){
        //      $payment->update(['status' => 'failed']);
        // }

        session()->forget('awaiting_payment');
    }

    public function render()
    {
        return view('livewire.check-balance');
    }
}
