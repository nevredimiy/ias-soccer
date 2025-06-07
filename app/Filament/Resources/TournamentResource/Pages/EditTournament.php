<?php

namespace App\Filament\Resources\TournamentResource\Pages;

use App\Filament\Resources\TournamentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTournament extends EditRecord
{
    protected static string $resource = TournamentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

     // Метод позволяет перейти в список, после редактировании
     protected function getRedirectUrl(): string
     {
         // Перенаправление на список событий
         return $this->getResource()::getUrl('index');
     }
}
