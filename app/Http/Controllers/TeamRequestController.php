<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamColor;
use App\Models\Team;
use App\Models\Event;
use App\Models\SeriesMeta;
use App\Models\EventTeamPrice;
use App\Services\SeriesTemplatesService;
use Illuminate\Support\Facades\Auth;
use LiqPay\LiqPay;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class TeamRequestController extends Controller
{
    

    public function create(Request $request)
    {

        // Валидация параметра id
        $validated = $request->validate([
            'id' => 'required|exists:events,id',
        ]);

        // Получаем ID события
        $eventId = $validated['id'];

        $event = Event::where('id', '=', $eventId)->first();

        $isPromoCode = $event->access_code;

        $colors = TeamColor::all();

        $teams = Team::where('event_id', '=', $eventId)->orderBy('id')->get();

        $eventTeamPrice = EventTeamPrice::where('event_id', $event->id)->get()->toArray();
        if($teams->count()){
            foreach($teams as $idx => $team){
                $price = $eventTeamPrice[$idx+1]['price'] ?? 0;
            }
        }else{
            $price = $eventTeamPrice[0]['price'] ?? 0;
        }      

        return view('teams.request.create', compact(
            'colors', 'teams', 'eventId', 'event', 'price', 'isPromoCode'
        ));
    }

    public function store(Request $request)
    {        
        $validated = $request->validate([
            'name' => [
                'required',
                Rule::unique('teams')->where(function ($query) use ($request) {
                    return $query->where('event_id', $request->event_id);
                })
            ],
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'color_id' => 'required',
            'event_id' => 'required|integer|exists:events,id',
            'price' => 'required|numeric|min:0',
        ]);

        $event = Event::find($request->event_id);

        if($request->promo_code_1 && $request->promo_code_2 && $request->promo_code_3 && $request->promo_code_4){
            $validated = $request->validate([
                'promo_code_1' => 'required|numeric|digits:1',
                'promo_code_2' => 'required|numeric|digits:1',
                'promo_code_3' => 'required|numeric|digits:1',
                'promo_code_4' => 'required|numeric|digits:1',
            ]);

            $enteredCode = $request->promo_code_1
                . $request->promo_code_2
                . $request->promo_code_3
                . $request->promo_code_4;


            if ($enteredCode !== $event->access_code) {
                return back()->withErrors(['promo_code' => 'Код не співпадає.']);
            }
        }


        $color = TeamColor::find($request->color_id);
        if (!$color) {
            return back()->withErrors(['color' => 'Колір не знайдено.']);
        }

        $teams = Team::where('event_id', $request->event_id)->get();

             
        // Проверка на уникальность цвета
        $existingColor = $teams->firstWhere('color_id', $color->id);

        if ($existingColor) {
            return back()->withErrors(['color_id' => 'Цей колір вже зайнятий.']);
        }


        $event = Event::with('tournament')->find($request->event_id);

        // Защита от создания команды, если все места заняты.
        if($teams->count() >= $event->tournament->count_teams ){
            return back()->withErrors(['count_teams' => "Неможна створити команду! Кількість команд у цьому турнірі вже {$event->tournament->count_teams}. "]);
        }

        // Создание команды
        $team = Team::create([
            'owner_id' => Auth::id(),
            'name' => $request->name,
            'color_id' => $request->color_id,
            'event_id' => $request->event_id,
            'status' => 'awaiting_payment',
            'player_request_status' => 'needed'
        ]);

        // ЗАПОЛНЕНИЯ ТАБЛИЦЫ series_teams        
        // получаем индекс только что добавленной команды для приминения в шаблоне
        $lastIndex = ($teams->keys()->last() ?? -1) + 1;
        // получаем шаблон серий
        $service = new SeriesTemplatesService();
        $templateSeries = $service->getTemplateShedule($event->tournament->count_teams);
        $seriesTeams = [];

        foreach($templateSeries as $idxRound => $round){
            foreach($round as $idxSeries => $series){
                
                if(in_array($lastIndex, $series)){

                    $seriesMetaId = SeriesMeta::where('event_id', $event->id)
                        ->where('series', $idxSeries+1)
                        ->where('round', $idxRound+1)
                        ->first();

                   if ($seriesMetaId) {
                        $seriesTeams[] = [
                            'series_meta_id' => $seriesMetaId->id,
                            'team_id' => $team->id,
                            'status' => 'open',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                }
            }
        }

        if(!empty($seriesTeams)){
            \DB::table('series_teams')->insert($seriesTeams);
        }       

        // Логика для обработки логотипа
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('img/team_logo', 'public');
            $team->update(['logo' => $logoPath]);
        }

        // Проверяем количество матчей. Если оно равно нужному количеству, то гинерируем матчи. 
        // Получаем актуальный список команд
        $teams = Team::where('event_id', $request->event_id)->get();

        if ($event && $event->tournament && $teams->count() == $event->tournament->count_teams) {
            $this->generateMatches($event, $teams);
        }

        // Получения баланса польователя
        $user = auth()->user();    
        $balance = $user->balance;
        $reserved_balance = $user->reserved_balance;
        $price = floatval($request->input('price'));   
        $amount = $price - ($balance - $reserved_balance);

        if($amount > 0){
            // Перенаправление на оплату
            return redirect()->route('teams.request.payment', ['team_id' => $team->id, 'amount' => $amount]);
        } else {
            // снимаем с баланса
            $user->balance = $balance - $price;
            $user->save();
            $team->status = 'paid';
            $team->save();
            return redirect()->route('profile');
        }
    }

    public function pay($team_id, $amount)
    {

        // dd($team_id, $amount);
        $team = Team::findOrFail($team_id);
        $event = Event::findOrFail($team->event_id);

        $liqpay = new \LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));

        $params = [
            'action'      => 'pay',
            'amount'      => $amount,
            'currency'    => 'UAH',
            'description' => "Оплата заявки на турнир #{$event->id} командой {$team->name}",
            // 'order_id'    => 'team_' . $team->id . '_' . time(),
            'order_id'    => $team->id,
            'version'     => '3',
            'result_url' => route('teams.request.success', $team->id),            
            'server_url'  => route('teams.request.callback'),
            
        ];

        $form = $liqpay->cnb_form($params);

        return view('payment.pay', compact('form'));
    }

    public function liqpayCallback(Request $request)
    {

        Log::info('LiqPay callback получен', ['request' => $request->all()]);
        // Логируем входящие данные
        Log::info('LiqPay callback', $request->all());
        
        Log::info('I am here Полученные данные от LiqPay');
        // $liqpay = new \LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));
    
        // Получаем данные от LiqPay
        $data = $request->input('data');
        $signature = $request->input('signature');
    
        // Декодируем данные
        $decodedData = json_decode(base64_decode($data), true);
        Log::info('Полученные данные от LiqPay', $decodedData);

        $privateKey = env('LIQPAY_PRIVATE_KEY');
        $generatedSignature = base64_encode(sha1($privateKey . $data . $privateKey, true));

        if ($generatedSignature !== $signature) {
            Log::warning('Ошибка проверки подписи', ['expected' => $generatedSignature, 'received' => $signature]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // Обновляем статус команды в базе
        $orderIdParts = explode('_', $decodedData['order_id']);
        $teamId = end($orderIdParts); // Берем последний элемент (ID команды)
        $team = Team::find($teamId);


        if ($team) {
            $team->status = 'paid';
            $team->save();
            Log::info("Статус команды #{$team->id} обновлен на 'paid'");
        }

        // Проверяем статус оплаты
        if ($decodedData['status'] === 'success' || $decodedData['status'] === 'sandbox') {
            $team->status = 'paid';
            $team->save();
            Log::info("Статус команды #{$team->id} обновлен на 'paid'");            

        } else {
            Log::warning("Ошибка оплаты LiqPay для команды ID {$decodedData['order_id']}");
            Log::warning("Ошибка оплаты LiqPay для команды ID {$team->id}");
        }

        return response()->json(['message' => 'Payment processed']);
    }

    public function success($team_id)
    {

        $team = Team::findOrFail($team_id);

        if ($team->status === 'paid') {
            return redirect()->route('profile')->with('success', 'Оплата прошла успешно! Команда зарегистрирована.');
        } else {
            return redirect()->route('profile')->with('error', 'Оплата в обработке, попробуйте позже.');
        }
    }

    public function checkPaymentStatus($team_id)
    {
        $team = Team::findOrFail($team_id);

        $liqpay = new \LiqPay(env('LIQPAY_PUBLIC_KEY'), env('LIQPAY_PRIVATE_KEY'));

        $params = [
            'action'   => 'status',
            'version'  => '3',
            'order_id' => $team->id, // тот же order_id, что ты отправлял при оплате
        ];

        $res = $liqpay->api("request", $params);

        // dd($res);

        if ($res->status === 'success' || $res->status === 'sandbox') {
            
            // Обновляем статус команды и баланс пользователя
            $user = auth()->user();                
            $event = Event::findOrFail($team->event_id);
            $eventTeamPrices = EventTeamPrice::where('event_id', $event->id)->get()->toArray();
            $keyTeam = array_search($team->id, array_column($eventTeamPrices, 'team_id'));
            $team_price = $eventTeamPrices[$keyTeam]['price'] ?? 0;            
            $user->balance += $res->amount - $team_price;
            $user->save();
            
            $team->update(['status' => 'paid']);
            return redirect()->route('profile')->with('success', "Оплата підтверджена!");
        } else {
            return redirect()->route('profile')->with('notice', "Оплата не знайдена або в обробці");
        }
    }

    // protected function updateBalance($team_price)

    protected function generateMatches($event, $teams)
    {

        $teamsArray = $teams->toArray(); // превращаем коллекцию в массив для быстрого доступа по индексам
        $seriesMetas = SeriesMeta::where('event_id', $event->id)->get()->groupBy('round');

      
    
        $service = new SeriesTemplatesService();
    
        // Получаем шаблоны сериалов и матчей
        $templateSeries = $service->getTemplateCalendar($event->tournament->count_teams);
        $templateMatches = $service->getMatchTemplate();


        $matches = [];

       foreach ($seriesMetas as $round => $roundMetaCollection) {
            $seriesMetaItem = $roundMetaCollection->first();

            foreach ($templateSeries as $indexSeries => $series) {
                if (!isset($series[$round - 1])) {
                    continue; // нет нужной тройки — пропускаем
                }

                $teamTriple = $series[$round - 1];

                foreach ($templateMatches as $templateMatche) {
                    $team1Index = $teamTriple[$templateMatche[0]] ?? null;
                    $team2Index = $teamTriple[$templateMatche[1]] ?? null;

                    if (!isset($teamsArray[$team1Index], $teamsArray[$team2Index])) {
                        continue;
                    }

                    $matches[] = [
                        'event_id' => $event->id,
                        'series_meta_id' => $seriesMetaItem->id,
                        'team1_id' => $teamsArray[$team1Index]['id'],
                        'team2_id' => $teamsArray[$team2Index]['id'],
                        'start_time' => \Carbon\Carbon::parse($seriesMetaItem->start_date)->format('Y-m-d H:i:s'),
                        'series' => $indexSeries + 1,
                        'round' => $round,
                        'status' => 'scheduled',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
        }


        if (!empty($matches)) {
            \DB::table('matches')->insert($matches);
        }        
    }

}
