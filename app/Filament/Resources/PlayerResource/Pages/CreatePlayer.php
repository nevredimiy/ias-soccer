<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use App\Models\PlayerInvite;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

     // Метод позволяет перейти в список, после редактировании
     protected function getRedirectUrl(): string
     {
         // Перенаправление на список событий
         return $this->getResource()::getUrl('index');
     }

     protected function afterCreate():void
     {

        PlayerInvite::create([
            'player_id' => $this->record->id,
            'token' => Str::uuid(),
            'used' => false,
        ]);
     }
}
