<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\SeriesMeta;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Matche;
use App\Models\User;
use App\Mail\EventCreatedMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Services\SeriesTemplatesService;


class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;


    protected function afterCreate(): void
    {
        $data = $this->form->getState();
        // dd($data);
        $event = $this->record;
        $tournament = Tournament::find($data['tournament_id']);
        if (!$tournament) {
            logger()->error('Tournament not found', ['tournament_id' => $data['tournament_id']]);
            return;
        }

        $this->createSeriesMeta(
            tournament: $tournament,
            eventId: $event->id,
            data: $data,
        );

        $this->createTeams($data['teams'] ?? [], $event->id);
        $this->createTeamPrices($data['team_prices'] ?? [], $event->id);

        if ($tournament->team_creator === 'admin') {
            $teams = Team::where('event_id', $event->id)->get()->toArray();
    
            if (!empty($teams)) {
                $startTime = Carbon::parse($data['series_start_all'] ?? now());
                $countRounds = (isset($data['custom_count_rounds']) ? $data['custom_count_rounds'] : $tournament->count_rounds) ?? 1;    
                $this->createMatches($event->id, $teams, $startTime, $countRounds);
            }
        }
        
        $this->redirect(EventResource::getUrl());

        // Рассылка писем на email пользователям
        if($tournament->type !== 'solo_private'){
            $users = User::where('id', '>', 117)->get();
    
            $event->load(['tournament', 'seriesMeta']);
            
            foreach ($users as $user) {
                Mail::to($user->email)->send(new EventCreatedMail($event));
            }
        }
    }

    protected function createSeriesMeta(Tournament $tournament, int $eventId, array $data): void
    {
        $seriesMeta = [];

        $startDate = Carbon::parse($data['series_start_all']);
        $endDate = Carbon::parse($data['series_end_all']);
        if(isset($data['custom_count_rounds'])){
            $countRounds = $data['custom_count_rounds'];
        } else {
            $countRounds = $tournament->count_rounds;
        }

        for ($round = 1; $round <= $countRounds; $round++) {
            for ($seriesIndex = 1; $seriesIndex <= $tournament->count_series; $seriesIndex++) {
                $seriesMeta[] = [
                    'event_id' => $eventId,
                    'stadium_id' => $data['stadium_id'],
                    'league_id' => $data['league_id'],
                    'size_field' => $data['size_field'],
                    'start_date' => $startDate->copy(),
                    'end_date' => $endDate->copy(),
                    'round' => $round,
                    'series' => $seriesIndex,
                    'price' => $data['series_price_all'] ?? (isset($data['player_price']) ? $data['player_price'] * 18 : null),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($tournament->subtype === 'regular') {
                    $startDate->addDays(7);
                    $endDate->addDays(7);
                }
            }
        }

        SeriesMeta::insert($seriesMeta);
    }


    // Создание команд
    protected function createTeams(array $teams, int $eventId): void
    {
        $userId = auth()->id();
        $now = now();

        $preparedTeams = [];

        foreach ($teams as $team) {
            if (!empty($team['name'])) {
                $preparedTeams[] = [
                    'event_id' => $eventId,
                    'owner_id' => $userId,
                    'name' => $team['name'],
                    'color_id' => $team['color_id'] ?? null,
                    'status' => 'paid',
                    'player_request_status' => 'needed',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($preparedTeams)) {
            Team::insert($preparedTeams);
        }
    }


    // Создания матчей
    protected function createMatches(int $eventId, array $teams, $startTime, int $countRounds): void
    {
        $service = new SeriesTemplatesService();
        $templateMatches = $service->getMatchTemplate();

        // Убедимся, что $startTime — это экземпляр Carbon
        $startTime = $startTime instanceof \Carbon\Carbon
            ? $startTime->copy()
            : \Carbon\Carbon::parse($startTime);
        
        $seriesMetaIdx = SeriesMeta::where('event_id', $eventId)->pluck('id')->toArray();

        $matches = [];

        for ($round = 1; $round <= $countRounds; $round++) {

            if (!isset($seriesMetaIdx[$round - 1])) {
                continue;
            }
            
            foreach ($templateMatches as [$i, $j]) {
                if (!isset($teams[$i], $teams[$j])) {
                    // Защита от выхода за пределы массива
                    continue;
                }

                $matches[] = [
                    'event_id'   => $eventId,
                    'series_meta_id'   => $seriesMetaIdx[$round - 1],
                    'team1_id'   => $teams[$i]['id'],
                    'team2_id'   => $teams[$j]['id'],
                    'start_time' => $startTime->copy(),
                    'series'     => 1,
                    'round'      => $round,
                    'status'     => 'scheduled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Увеличиваем время на 6 минут для следующего матча
                $startTime->addMinutes(6);
            }
        }

        if (!empty($matches)) {
            Matche::insert($matches);
        }

    }

    protected function createTeamPrices(array $prices, int $eventId): void
    {
        $now = now();
        $teamPrices = [];

        foreach ($prices as $index => $price) {
            $teamPrices[] = [
                'event_id' => $eventId,
                'team_index' => $index,
                'price' => $price['price'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

        }

        if (!empty($teamPrices)) {
            \DB::table('event_team_prices')->insert($teamPrices);
        }
    }

}

