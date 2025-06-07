<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
