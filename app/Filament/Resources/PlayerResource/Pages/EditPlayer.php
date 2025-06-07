<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlayer extends EditRecord
{
    protected static string $resource = PlayerResource::class;

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
