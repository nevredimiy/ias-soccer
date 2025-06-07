<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\TeamColor;
use Liqpay\Liqpay;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
   protected $fillable = [
      'owner_id',
      'name',
      'logo',
      'group_photo',
      'color_id',
      'event_id',
      'status',
      'player_request_status',
      'max_players',
      'application_lifetime_days',
      'application_lifetime_hours',
      'application_lifetime_minutes'

   ];

   public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(TeamColor::class, 'color_id');
    }

    public function processPayment()
    {
        $liqpay = new \LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));

        $params = [
            'action'      => 'pay',
            'amount'      => 100, // Укажи цену оплаты
            'currency'    => 'UAH',
            'description' => "Оплата за команду {$this->name}",
            'order_id'    => $this->id,
            'version'     => '3',
            'server_url'  => route('liqpay.callback'),
            'result_url'  => url()->previous(),
        ];

        $form = $liqpay->cnb_form($params);
        
        return response()->json(['form' => $form]);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }


    // создает ссылку на оплату. 
    public function getPaymentLink()
    {
        $liqpay = new \LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));
        $event = Event::findOrFail($this->event_id);

        $params = [
            'action'      => 'pay',
            'amount'      => $event->price,
            'currency'    => 'UAH',
            'description' => "Оплата заявки на турнир #{$event->id} командой {$this->name}",
            'order_id'    => $this->id,
            'version'     => '3',
            'result_url'  => route('teams.request.success', $this->id),
            'server_url'  => route('teams.request.callback'),
        ];

        return $liqpay->cnb_form($params);
    }

    public function applications()
    {
        return $this->hasMany(TeamPlayerApplication::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Player::class, 'player_teams')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function averageRating()
    {
        return $this->players()->avg('rating');
    }

    

}
