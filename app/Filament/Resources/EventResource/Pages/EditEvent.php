<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

    class EditEvent extends EditRecord
    {
        protected static string $resource = EventResource::class;

        protected function getHeaderActions(): array
        {
            return [
                Actions\DeleteAction::make(),
            ];
        }

        protected function mutateFormDataBeforeSave(array $data): array
        {
            $this->teamPricesToSync = $data['team_prices'] ?? [];
            // dd($this->teamPricesToSync[0]['price']);
            unset($data['team_prices']); // Убираем, если это не нужно в основной таблице

            return $data;
        }

        // Метод позволяет перейти в список, после редактировании
        protected function getRedirectUrl(): string
        {
            // Перенаправление на список событий
            return $this->getResource()::getUrl('index');
        }

        protected function mutateFormDataBeforeFill(array $data): array
        {

            // Пример: добавить массив команд из таблицы teams
            $data['teams'] = \App\Models\Team::where('event_id', $this->record->id)
                ->get(['name', 'color_id'])
                ->toArray();

            // Пример: достать мета-инфу по series
            $meta = \App\Models\SeriesMeta::where('event_id', $this->record->id)->first();
            if ($meta) {
                $data['series_start_all'] = $meta->start_date;
                $data['series_end_all'] = $meta->end_date;
                $data['series_price_all'] = $meta->price;
                $data['player_price'] = round($meta->price / 18);
                $data['stadium_id'] = $meta->stadium_id;
                $data['league_id'] = $meta->league_id;
                $data['size_field'] = $meta->size_field;
            }

            $eventId = $data['id'] ?? null;

            // добавить массив команд из таблицы teams
             if ($eventId) {
                $teamPrices = \DB::table('event_team_prices')
                    ->where('event_id', $eventId)
                    ->orderBy('id') // Или другой порядок, если надо
                    ->pluck('price')
                    ->map(fn ($price) => ['price' => $price])
                    ->toArray();

                $data['team_prices'] = $teamPrices;
            }

            return $data;
        }

        protected function afterSave(): void
        {
            // Сохраняем мета-данные серии
            \App\Models\SeriesMeta::updateOrCreate(
                ['event_id' => $this->record->id],
                [
                    'start_date' => $this->data['series_start_all'] ?? null,
                    'end_date' => $this->data['series_end_all'] ?? null,
                    'price' => $this->data['series_price_all'] ?? null,
                    'stadium_id' => $this->data['stadium_id'] ?? null,
                    'league_id' => $this->data['league_id'] ?? null,
                    'size_field' => $this->data['size_field'] ?? null,
                ]
            );

            $teams = \App\Models\Team::where('event_id', $this->record->id)->get();

            foreach ($teams as $index => $team) {
                if (!empty($this->data['teams'][$index]['name'])) {
                    $team->update([
                        'name' => $this->data['teams'][$index]['name'],
                        'color_id' => $this->data['teams'][$index]['color_id'] ?? null,
                    ]);
                }
            }

            if (!isset($this->teamPricesToSync)) {
                return;
            }
        

            $eventId = $this->record->id;
            $prices = \App\Models\EventTeamPrice::where('event_id', $this->record->id)->get();
            $now = now();

            foreach ($this->teamPricesToSync as $index => $price) {
                \App\Models\EventTeamPrice::where('event_id', $eventId)
                    ->where('team_index', $index)
                    ->update(['price' => $price['price']]);

            }


        }


        

    }
