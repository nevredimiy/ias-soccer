<?php

namespace App\Filament\Resources\MatcheResource\Pages;

use App\Filament\Resources\MatcheResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatche extends EditRecord
{
    protected static string $resource = MatcheResource::class;

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
